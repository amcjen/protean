<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

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
			PFProfiler::getInstance()->setMark('Starting PFController->HandleRequest()');

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
					$theme = $request->getProperty('theme');
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

			PFProfiler::getInstance()->setMark('Starting GetCommand() loop in HandleRequest()');

			while ($cmd = $appController->getCommand($request)) {
				$cmd->execute($request);
			}	
			PFProfiler::getInstance()->setMark('Finished GetCommand() loop in HandleRequest()');
			$this->invokeView($appController, $request);
			PFProfiler::getInstance()->setMark('Finished InvokeView() in HandleRequest()');

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

		if (PF_PROFILER) {
			$page->assign('PF_PROFILE_TIME', PFProfiler::getInstance()->getTime());
		}
		if (PF_PROFILER_MARKS) {
			PFDebugStack::append(PFProfiler::getInstance()->displayMarks(), __FILE__, __LINE__);
		}

		if (PF_DEBUG_VERBOSE == true) {
			PFHTMLLogger::format($page);
			$debugDiv = $page->Fetch('content', 'logger.tpl');
			$page->assign('PF_HTML_LOGGER', $debugDiv);
		}

		$throwAwayVar = $page->fetch($viewApp, $view);
		$page->setHeader($headerApp, $header);
		$page->setFooter($footerApp, $footer);

		$page->display($viewApp, $view);
	}
}

?>