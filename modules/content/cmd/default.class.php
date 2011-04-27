<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2011, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

class PFDefaultCommand extends PFCommand { 
	
	protected $page;
	protected $session;
	protected $templateHelper;
	
	public function __construct() {
		parent::__construct();
		$this->session = PFSession::getInstance();
		$this->page = PFRegistry::getInstance()->getPage();
		$this->templateHelper = PFTemplateHelper::getInstance();
	}
	
	public function doExecute(PFRequest $request) {	
		$this->assignHeaderPaths();
	}
	
	public function assignDefaults(PFRequest $request) {
		
		$this->templateHelper->assign('PF_BASE', PF_BASE);
		$this->templateHelper->assign('PF_BASE_CSS_PATH', $this->session->getCSSPath('content'));
		$this->templateHelper->assign('PF_BASE_JAVASCRIPT_PATH', $this->session->getJSPath('content'));	
		$this->templateHelper->assign('PF_VERSION', PF_VERSION);
		$this->templateHelper->assign('PF_SERVER_NAME', @$_SERVER['SERVER_NAME']);
		$this->templateHelper->assign('PF_CONTENT_IMAGE_PATH', $this->session->getImagePath('content'));
	}
	
	public function assignHeaderPaths() {
		$this->templateHelper->assignCSSIncludes($this->page);
		$this->templateHelper->assignJavascriptIncludes($this->page);
	}
}

?>