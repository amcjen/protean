<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

class PFDefaultCommand extends PFCommand { 
	
	public function doExecute(PFRequest $request) {	
		$page = PFRegistry::getInstance()->getPage();		
		self::assignHeaderPaths($page);
	}
	
	public function assignDefaults(PFRequest $request) {
		
		PFTemplateHelper::getInstance()->assign('PF_BASE', PF_ROOT_DIRECTORY);
		PFTemplateHelper::getInstance()->assign('PF_BASE_CSS_PATH', PFSession::getInstance()->getCSSPath('content'));
		PFTemplateHelper::getInstance()->assign('PF_BASE_JAVASCRIPT_PATH', PFSession::getInstance()->getJSPath('content'));	
		PFTemplateHelper::getInstance()->assign('PF_VERSION', PF_VERSION);
		PFTemplateHelper::getInstance()->assign('PF_SERVER_NAME', @$_SERVER['SERVER_NAME']);
	
		PFTemplateHelper::getInstance()->assign('PF_CONTENT_IMAGE_PATH', PFSession::getInstance()->getImagePath('content'));
	}
	
	public static function assignHeaderPaths($page) {
		PFTemplateHelper::getInstance()->assignCSSIncludes($page);
		PFTemplateHelper::getInstance()->assignJavascriptIncludes($page);
	}
}

?>