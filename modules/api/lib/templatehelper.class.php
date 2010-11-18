<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

class PFTemplateHelper { 

	static protected $instance;
	protected $data;
	protected $javascriptIncludes;
	protected $cssIncludes;

	private function __construct() {
		$this->data = array();
		$this->javascriptIncludes = array();
		$this->cssIncludes = array();
	}

	static public function getInstance() {
		if (self::$instance == NULL) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function assign($key, $value) {
		$this->data[$key] = $value;
	}

	public function append($key, $value) {
		if (isset($this->data[$key])) {		
			$this->data[$key] = $this->data[$key] . $value;
		} else {		
			$this->data[$key] = $value;
		}
	}

	public function addJavascriptInclude($filepath) {
		if (array_search($filepath, $this->javascriptIncludes, true) === false) {
			$this->javascriptIncludes[] = $filepath;
		}
	}

	public function assignJavascriptIncludes($page=false) {
		$jsPath = '';

		foreach ($this->javascriptIncludes as $value) {
			$jsPath .= '<script type="text/javascript" src="' . $value. '"></script>' . "\n";			
		}

		if ($page == false) {		
			PFTemplateHelper::getInstance()->assign('PF_JAVASCRIPT_INCLUDES', $jsPath);
		} else {		
			$page->assign('PF_JAVASCRIPT_INCLUDES', $jsPath);
		}
	}

	public function addCSSInclude($filepath, $browserCheck='') {	
		if ($browserCheck != '') {
			$filepath .= ':' . $browserCheck;
		}
		if (array_search($filepath, $this->cssIncludes, true) === false) {
			$this->cssIncludes[] = $filepath;
		}
	}

	public function assignCSSIncludes($page=false) {
		$cssPath = '';

		foreach ($this->cssIncludes as $value) {	
			$cssValue = explode(':', $value);

			if (count($cssValue) > 1) {
				$cssPath .= "\t\t" . '<!--[if lte ' . $cssValue[1] . ']>' . "\n";
				$cssPath .= "\t\t" . '<link rel="stylesheet" type="text/css" media="all" href="' . $cssValue[0]. '" />' . "\n";
				$cssPath .= "\t\t<![endif]-->\n";
			} else {
				$cssPath .= "\t\t" . '<link rel="stylesheet" type="text/css" media="all" href="' . $cssValue[0]. '" />' . "\n";
			}
		}

		if ($page == false) {		
			PFTemplateHelper::getInstance()->assign('PF_HEAD_CSS_INCLUDES', $cssPath);
		} else {	
			$page->assign('PF_HEAD_CSS_INCLUDES', $cssPath);
		}
	}

	public function clearAssign($key) {
		$this->data[$key] = '';
	}

	public static function assignDefaults($page) {
		foreach (PFTemplateHelper::getInstance()->data as $key => $value) {
			$page->assign($key, $value);
		}
	}
}

?>
