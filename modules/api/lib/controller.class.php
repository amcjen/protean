<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2011, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/
/**
@package api
*/
class PFController { 

	private static $instance;

	private function __construct() {}

	static function getInstance() {	
		if (!self::$instance) {		
			self::$instance = new self();
		}

		return self::$instance;
	}

	static public function run() {
		PFController::getInstance()->init();
		PFController::getInstance()->handleRequest();
	}

	protected function init() {	
		try {	
			PFApplicationHelper::getInstance()->init();	
		} catch (PFException $e) {
			echo $e;
		}
	}

	protected function handleRequest() {
		try {

			$request = PFFactory::getInstance()->createObject('api.request');
			$appController = PFApplicationHelper::getInstance()->appController();

			$theme = $appController->getTheme($request);	

			if (isset($theme) && $theme) {
				PFRegistry::getInstance()->set('pf_theme', $theme);
			} else {
				PFRegistry::getInstance()->set('pf_theme', 'default');
			}

			if ($request->isPropertySet('theme') || PFSession::getInstance()->isRegistered('pf.theme')) {				
				if ($request->isPropertySet('theme')) {
					$theme = $request->get('theme');
				} else {
					$theme = PFSession::getInstance()->retrieve('pf.theme');
				}

				if ($theme != 'default') {
					PFRegistry::getInstance()->set('pf_override_theme', $theme);
				}
				PFSession::getInstance()->register('pf.theme', $theme);
			}

			$app = $appController->getViewApplication($request);
			PFRegistry::getInstance()->setPage(PFFactory::getInstance()->createObject('api.template', $app));

			while ($cmd = $appController->getCommand($request)) {
				$cmd->execute($request);
			}
			
			if ($request->get('pf.uri') == '/content/notfound|get') {
				header('HTTP/1.1 404 Not Found');
				header('Content-type: text/html; charset=utf-8');
			}
			
			$this->invokeView($appController, $request);

		} catch (PFException $e) {
			$e->handleException();
		}
	}

	private function invokeView($appController, $request) {
		$page = PFRegistry::getInstance()->getPage();
		PFTemplateHelper::getInstance()->assignDefaults($page);

		$headerApp = $appController->getViewHeaderApplication($request);
		$header = $appController->getViewHeader($request);
		$footerApp = $appController->getViewFooterApplication($request);
		$footer = $appController->getViewFooter($request);
		$viewApp = $appController->getViewApplication($request);
		$view = $appController->getView($request);

		$page->setHeader($headerApp, $header);
		$page->setFooter($footerApp, $footer);
		$page->display($viewApp, $view);
	}
}

?>