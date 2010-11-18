<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
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

class PFTemplate extends Smarty { 

	protected $appName;
	protected $debug;
	protected $more_template_dir = array();

	public function __construct($appName, $languageTable='global') {
		$this->error_reporting = null;

		if (PFRegistry::getInstance()->isValueSet('pf_theme') == false) {
			PFRegistry::getInstance()->set('pf_theme', 'default');
		} 

		$this->appName = $appName;
		if (PF_TEMPLATE_DEBUG) {
			$this->debug = true;
		} else {
			$this->debug = false;
		}

		PFRegistry::getInstance()->set('APPNAME', $this->appName);

		$this->template_dir = PF_BASE . '/modules/' . $this->appName . '/tpl/' . PFRegistry::getInstance()->get('pf_theme') . '/html/';
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

		parent::smarty();

		/*
		Change this line if translation of dynamic data is needed ;-)
		$this->register_prefilter("smarty_prefilter_i18n");
		to this
		$this->register_outputfilter("smarty_prefilter_i18n");
		this change makes it possible even to translate dynamic data e.g. options because translation is done after compilation of template [xaos, 20050206]
		*/
		$this->register_prefilter('smarty_prefilter_i18n');

		$this->register_resource('protean', array('protean_get_template',
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
		$header = $this->get_template_vars('PF_HEADER');
		if (empty($header)) {
			$this->setHeader();
		}

		$footer = $this->get_template_vars('PF_FOOTER');
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
			$this->template_dir = PF_BASE . '/modules/' . $appName . '/tpl/' . PFRegistry::getInstance()->get('pf_theme') . '/html/';
		} else {
			$this->template_dir = $templateDir;
		}

		$this->template_dir = array_merge(array($this->template_dir), $this->more_template_dir);

		if (parent::template_exists($tplName)) {
			$ret = parent::fetch($tplName, $cache_id, $compile_id, $display);
		} else {
			throw new PFException('api', array('TEMPLATE_DOES_NOT_EXIST', $this->template_dir[0] . $tplName), E_USER_ERROR);
		}

		$this->template_dir = $curTemplateDir;
		return $ret;
	}

	public function smartyFetch($tplName, $display=false) {	
		$this->setLanguageIDs($cache_id, $compile_id);

		if (parent::template_exists($tplName)) {
			$ret = parent::fetch($tplName, $cache_id, $compile_id, $display);
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
		list($defaultApp, $defaultCmd) = explode('.', PF_DEFAULT_COMMAND);

		if (empty($appName)) {
			$appName = $defaultApp;
		}	

		if (empty($tplName)) {
			$tplName = 'header.tpl';
		}

		$this->assign('PF_ERRORSTACK', PFErrorstack::getErrorStackAsFormattedString());
		$header = $this->fetch($appName, $tplName);
		$this->assign('PF_HEADER', $header);
	}

	public function setFooter($appName='', $tplName='footer.tpl') {
		list($defaultApp, $defaultCmd) = explode('.', PF_DEFAULT_COMMAND);

		if (empty($appName)) {
			$appName = $defaultApp;
		}	

		if (empty($tplName)) {
			$tplName = 'footer.tpl';
		}

		$footer = $this->fetch($appName, $tplName);		
		$this->assign('PF_FOOTER', $footer);
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

	public function _process_template($tpl_file, $compile_path) {
		if ($this->debug) {
			PFDebugStack::append('Template File: ' . $tpl_file . '<br />Compile Path: ' . $compile_path, __FILE__, __LINE__);
		}

		if (!$this->force_compile && file_exists($compile_path)) {	
			if (!$this->compile_check) {
				return true;
			} else {
				if (!$this->_fetch_template_info($tpl_file, $template_source, $template_timestamp)) {
					if ($this->debug) {
						PFDebugStack::append('Failed fetching template info: ' . $tpl_file, __FILE__, __LINE__);
					}
					return false;
				}
				if ($template_timestamp > filemtime($compile_path) || $this->languageFilesAreModified($compile_path, $lang_path)) {	

					if ($this->debug) {
						PFDebugStack::append('Compiled template: ' . $tpl_fil, __FILE__, __LINE__);
					}

					if ($template_timestamp >= filemtime($lang_path)) {
						$timestamp = $template_timestamp;
					} else {
						$timestamp = filemtime($lang_path);
					}

					$this->_compile_template($tpl_file, $template_source, $template_compiled);
					$this->_write_compiled_template($compile_path, $template_compiled, $timestamp);
					return true;
				} else {					
					if ($this->debug) {
						PFDebugStack::append('Did not compile template: ' . $tpl_file, __FILE__, __LINE__);
					}
					return true;
				}
			}
		} else {

			if ($this->force_compile) {
				$this->clear_cache();
			}

			if (!$this->_fetch_template_info($tpl_file, $template_source, $template_timestamp)) {
				if ($this->debug) {
					PFDebugStack::append('Failed fetching template info (2): ' . $tpl_file, __FILE__, __LINE__);
				}
				return false;
			}

			if ($this->debug) {
				PFDebugStack::append('Force-compiled template: ' . $tpl_file, __FILE__, __LINE__);
			}

			$this->_compile_template($tpl_file, $template_source, $template_compiled);
			$this->_write_compiled_template($compile_path, $template_compiled, $template_timestamp);

			return true;
		}
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

	public function addTemplateDir($dir) {
		$this->more_template_dir[] = $dir;
	}

	public function getTemplateDir() {
		return $this->template_dir;
	}

	public function getMoreTemplateDir() {
		return $this->more_template_dir;
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

	public function setTemplateDir($dir) {
		return $this->template_dir = $dir;
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
