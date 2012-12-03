<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2012, Eric Jennings.  All rights reserved.            *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

require_once 'modules/thirdparty/smarty/Smarty.class.php';

/*
* smarty_prefilter_i18n()
* This function takes the language file, and rips it into the template
*/
function smarty_prefilter_i18n($tpl_source, &$smarty) {
	try {			
		if (!is_object(PFLanguage::getInstance())) {
			throw new PFException('', 'Error loading template\'s multilanguage support', E_USER_ERROR);
		}
	} catch (PFException $e) {
		$e->handleException();
	}

	return preg_replace_callback('/##(.+?)##/', '_compile_lang', $tpl_source);
}

function _compile_lang($key) {
	try {
		return PFLanguage::getInstance()->getTranslation(PFRegistry::getInstance()->get('APPNAME'), $key[1]);
	} catch (PFException $e) {
		$e->handleException();
	} 
}

/**
 * Protean register resource functions, to support application-prepended file names
 *
 */
function protean_get_template($tplName, &$tplSource, &$smarty) {
	list($appName, $tplFile) = explode('.', $tplName, 2);

	if (PFRegistry::getInstance()->isValueSet('pf_theme') == false) {
		$theme = 'default';
	} else {
		$theme = PFRegistry::getInstance()->get('pf_theme');
	}

	$path = PF_BASE . '/modules/' . $appName . '/tpl/' . $theme . '/html/' . $tplFile;
	try {			
		$tplSource = file_get_contents($path, true);
		return true;
	} catch (Exception $e) {			
		PFException::handleVanillaException($e->getMessage(), $e->getCode(), __FILE__, __LINE__);
		return false;
	}
}

function protean_get_timestamp($tplName, &$tplTimestamp, &$smarty) {	
	list($appName, $tplFile) = explode('.', $tplName, 2);

	if (PFRegistry::getInstance()->isValueSet('pf_theme') == false) {
		$theme = 'default';
	} else {
		$theme = PFRegistry::getInstance()->get('pf_theme');
	}

	$path = PF_BASE . '/modules/' . $appName . '/tpl/' . $theme . '/html/' . $tplFile;		
	return filemtime($path);
}

function protean_get_secure($tpl_name, &$smarty) {
	return true;
}

function protean_get_trusted($tpl_name, &$smarty) { }

/**
@package api
*/
class PFTemplate extends Smarty { 

	protected $appName;
	protected $controllerMap;
	protected $debug;

	public function __construct($appName, $languageTable='global') {
		parent::__construct();
		$this->error_reporting = E_ALL & ~E_NOTICE;

		if (PFRegistry::getInstance()->isValueSet('pf_theme') == false) {
			PFRegistry::getInstance()->set('pf_theme', 'default');
		} 

		$this->template_dir = null;

		$this->appName = $appName;
		$this->controllerMap = PFRegistry::getControllerMap();
		
		if (PF_TEMPLATE_DEBUG) {
			$this->debug = true;
			$this->debugging = true;
		} else {
			$this->debug = false;
			$this->debugging = false;
		}

		PFRegistry::getInstance()->set('APPNAME', $this->appName);

		$this->addTemplateDir(PF_BASE . '/modules/' . $this->appName . '/tpl/' . PFRegistry::getInstance()->get('pf_theme') . '/html/');
		$this->addTemplateDir(PF_BASE);

		if (!is_dir(PF_TEMP_PATH . '/tpl_cmp') && is_writable(PF_TEMP_PATH)) {
			mkdir(PF_TEMP_PATH . '/tpl_cmp', 0770);
		}
		$this->compile_dir = PF_TEMP_PATH . '/tpl_cmp';

		if (!is_dir(PF_TEMP_PATH . '/tpl_cfg') && is_writable(PF_TEMP_PATH)) {
			mkdir(PF_TEMP_PATH . '/tpl_cfg', 0770);
		}
		$this->config_dir = PF_TEMP_PATH . '/tpl_cfg';

		if (!is_dir(PF_TEMP_PATH . '/tpl_cch') && is_writable(PF_TEMP_PATH)) {
			mkdir(PF_TEMP_PATH . '/tpl_cch', 0770);
		}
		$this->cache_dir = PF_TEMP_PATH . '/tpl_cch';

    if (PF_CACHE_ENABLED == true && PF_CACHE_TEMPLATES == true) {
      $this->loadCacheResource('apc'); 
      $this->setCaching(true);
    }
		/*
		Change this line if translation of dynamic data is needed ;-)
		$this->register_prefilter("smarty_prefilter_i18n");
		to this
		$this->register_outputfilter("smarty_prefilter_i18n");
		this change makes it possible even to translate dynamic data e.g. options because translation is done after compilation of template [xaos, 20050206]
		*/
		$this->registerFilter('pre', 'smarty_prefilter_i18n');

		$this->registerResource('protean', array('protean_get_template',
			'protean_get_timestamp',
			'protean_get_secure',
			'protean_get_trusted'));

		$this->compile_id = PFLanguage::getInstance()->getCurrentLanguage();

		PFLanguage::getInstance()->loadTranslationTable($this->appName, $languageTable);
		if (PF_TEMPLATE_FORCE_RECOMPILE == true) {
			$this->force_compile = true;
		}
	}

	public function assign($tpl_var, $value = null) {
		parent::assign($tpl_var, $value);
	}

	public function assignByRef($tpl_var, &$value) {		
		parent::assign_by_ref($tpl_var, $value);
	}

	public function append($tpl_var, $value=null, $merge=false) {	
		parent::append($tpl_var, $value, $merge);
	}

	public function appendByRef($tpl_var, &$value, $merge=false) {	
		parent::append_by_ref($tpl_var, $value, $merge);
	}

	public function clearAssign($tpl_var) {
		parent::clear_assign($tpl_var);
	}

	public function display($appName, $tplName) {
		$header = $this->tpl_vars['PF_HEADER'];
		if (empty($header)) {
			$this->setHeader();
		}

		$footer = $this->tpl_vars['PF_FOOTER'];
		if (empty($footer)) {
			$this->setFooter();
		}

		if (empty($appName)) {
			$appName = 'content';
		}	

		if (empty($tplName)) {
			$tplName = 'index.tpl';
		}

		$ret = $this->fetch($appName, $tplName, true);
		return $ret;
	}

	public function fetch($appName, $tplName, $display=false, $templateDir='') {
		$this->setLanguageIDs($cache_id, $compile_id);
		$curTemplateDir = $this->template_dir;

		if (PFRegistry::getInstance()->isValueSet('pf_theme') == false) {
			PFRegistry::getInstance()->set('pf_theme', 'default');
		}

		if (empty($templateDir)) {
			$this->setTemplateDir(PF_BASE . '/modules/' . $appName . '/tpl/' . PFRegistry::getInstance()->get('pf_theme') . '/html/');;
		} else {
			$this->setTemplateDir($templateDir);
		}

		if (parent::templateExists($tplName)) {
			$ret = parent::fetch($tplName, $cache_id, $compile_id, null, $display);
		} else {
			throw new PFException('api', array('TEMPLATE_DOES_NOT_EXIST', $this->template_dir[0] . $tplName), E_USER_ERROR);
		}

		$this->setTemplateDir($curTemplateDir);
		return $ret;
	}

	public function getTemplatePath($appName, $tplName) {
		
		if (PFRegistry::getInstance()->isValueSet('pf_theme') == false) {
			PFRegistry::getInstance()->set('pf_theme', 'default');
		}
			
		return PF_BASE . '/modules/' . $appName . '/tpl/' . PFRegistry::getInstance()->get('pf_theme') . '/html/' . $tplName;		
	}

	public function smartyFetch($tplName, $display=false) {	
		$this->setLanguageIDs($cache_id, $compile_id);

		if (parent::templateExists($tplName)) {
			$ret = parent::fetch($tplName, $cache_id, $compile_id, null, $display);
		} else {
			throw new PFException('api', array('TEMPLATE_DOES_NOT_EXIST', $this->template_dir . $tplName), E_USER_ERROR);
		}
		return $ret;
	}

	public function isCached($tpl_file, $cache_id=null, $compile_id=null) {
		if (!$this->caching) {
			return false;
		}

		if (!isset($compile_id)) {
			$this->setLanguageIDs($cache_id, $compile_id);
		}

		return parent::is_cached($tpl_file, $cache_id, $compile_id);
	}

	public function setHeader($appName='', $tplName='header.tpl') {	
		list(, $defaultApp, ) = explode('/', PF_DEFAULT_URI);

		if (empty($appName)) {
			$appName = $defaultApp;
		}	

		if (empty($tplName)) {
			$tplName = 'header.tpl';
		}

		$this->assign('PF_ERRORSTACK', PFErrorstack::getErrorStackAsFormattedString());

		//$header = $this->fetch($appName, $tplName);
		$this->assign('PF_HEADER', $this->getTemplatePath($appName, $tplName));
	}

	public function setFooter($appName='', $tplName='footer.tpl') {
		list(, $defaultApp, ) = explode('/', PF_DEFAULT_URI);

		if (empty($appName)) {
			$appName = $defaultApp;
		}	

		if (empty($tplName)) {
			$tplName = 'footer.tpl';
		}

		//$this->assign('PF_FOOTER', $this->fetch($appName, $tplName));
		$this->assign('PF_FOOTER', $this->getTemplatePath($appName, $tplName));
	}

	public function trigger_error($error_msg, $error_type = E_USER_WARNING) {
		throw new PFException('', PFException::ERR_TEMPLATE_ERROR . ': ' . $error_msg, $error_type);
	}

	public function assignDefaults() {	
		PFTemplateHelper::getInstance()->assignDefaults($this);
	}

	public function setLanguageIDs(&$cache_id, &$compile_id) {
		$cache_id = $compile_id = $this->appName . '-' . PFLanguage::getInstance($this->appName)->getCurrentLanguage();	
	}

	protected function languageFilesAreModified($compilePath, &$langPath) {

		$tableDirectory = PFLanguage::getInstance()->getLanguageTableFileDirectory($this->appName);

		if (@is_dir($tableDirectory)) {
			$tableHandle = opendir($tableDirectory);
			while (false !== ($tableFilename = readdir($tableHandle))) {		
				$parts = pathinfo($tableFilename);

				if($parts['extension'] == 'lng' && filemtime($tableDirectory . $tableFilename) > filemtime($compilePath)) {			
					if ($this->debug) {
						$dbg = 'Table file: ' . $tableDirectory . $tableFilename . '<br />';
						$dbg .= 'Compile file: ' . $compilePath . '<br />';
						$dbg .= 'Table File mtime: ' . filemtime($tableDirectory . $tableFilename) . '<br />';
						$dbg .= 'Compile File mtime: ' . filemtime($compilePath) . '<br />';
						PFDebugStack::append($dbg, __FILE__, __LINE__);
					}

					$langPath = $tableDirectory . $tableFilename;
					return true;
				} 
			}
		}

		return false;
	}

	public function getTemplateDirs() {
		return $this->template_dir;
	}

	public function getCompileDir() {
		return $this->compile_dir;
	}

	public function getConfigDir() {
		return $this->config_dir;
	}

	public function getCacheDir() {
		return $this->cache_dir;
	}

	public function setCompileDir($dir) {
		return $this->compile_dir = $dir;
	}

	public function setConfigDir($dir) {
		return $this->config_dir = $dir;
	}

	public function setCacheDir($dir) {
		return $this->cache_dir = $dir;
	}

	public function getAppName() { 
		return $this->appName;
	}
}

?>