<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

class PFApplicationHelper { 

	private $currentLoadedApp;
	private static $instance;

	private function __construct() {

		$this->currentLoadedApp = '';
	}

	static function getInstance() {

		if (!self::$instance) {

			self::$instance = new self();
		}

		return self::$instance;
	}

	public function init() {

		$this->loadControllerMap();
	}

	public function appController() {

		$map = PFRegistry::getControllerMap();

		if (!is_object($map)) {
			throw new PFException('api', 'NO_CONTROLLER_MAP', E_USER_ERROR);
		}

		return PFFactory::getInstance()->createObject('api.applicationcontroller', $map);
	}

	public function loadControllerMap($app='', $configFileOverride='') {

		if ($app == '') {	
			$app = PFRequest::getCurrentURLApplication();
		}

		if ($this->currentLoadedApp == $app) {		
			return;
		} else {				
			$this->currentLoadedApp = $app;
		}

		$configFile = PF_BASE . '/modules/' . $app . '/cmd/command.xml';

		if ($configFileOverride != '') {
			$configFile = $configFileOverride;
		}

		if (!file_exists($configFile)) {
			$default = explode('.', PF_DEFAULT_COMMAND);

			$configFile = PF_BASE . '/modules/' . $default[0] . '/cmd/command.xml';
		}

		if (PF_CACHE_ENABLED == true && PF_CACHE_CONTROLLER_MAP == true) {

			PFFactory::getInstance()->initObject('api.controllermap');
			PFFactory::getInstance()->initObject('api.cacheapc');

			$map = PFCacheAPC::getInstance()->fetch($configFile);

			if ($map instanceof PFControllerMap) {

				if (PF_APP_CONTROLLER_DEBUG == true) {
					PFDebugStack::append('Reading controller map ' . $configFile . ' from cache', __FILE__, __LINE__);
				}

				PFRegistry::getInstance()->setControllerMap($map);
				return;
			}
		}

		$options = @SimpleXml_load_file($configFile);

		if (!$options instanceof SimpleXMLElement) {
			throw new PFException('api', array('COMMAND_FILE_NOT_RESOLVABLE', $configFile), E_USER_ERROR);
		}

		$map = PFFactory::getInstance()->createObject('api.controllermap');

		foreach ($options->view as $defaultView) {

			$statusString = trim($defaultView['status']);
			$status = PFCommand::statuses($statusString);
			$map->addView(PF_DEFAULT_COMMAND, $status, (string)$defaultView);
		}

		foreach ($options->viewheader as $defaultViewHeader) {

			$statusString = trim($defaultViewHeader['status']);
			$status = PFCommand::statuses($statusString);
			$map->addViewHeader(PF_DEFAULT_COMMAND, $status, (string)$defaultViewHeader);
		}

		foreach ($options->viewfooter as $defaultViewFooter) {

			$statusString = trim($defaultViewFooter['status']);
			$status = PFCommand::statuses($statusString);
			$map->addViewFooter(PF_DEFAULT_COMMAND, $status, (string)$defaultViewFooter);
		}

		foreach ($options->command as $command) {	
			$commandName = trim($command['name']);

			if ($command->classroot) {			
				$map->addClassroot($commandName, (string)$command->classroot);
			}

			if ($command->view) {		
				foreach ($command->view as $view) {			
					$statusString = trim($view['status']);
					$status = PFCommand::statuses($statusString);
					$map->addView($commandName, $status, (string)$view); 
				}
			}

			if ($command->viewheader) {		
				foreach ($command->viewheader as $header) {
					$statusString = trim($header['status']);
					$status = PFCommand::statuses($statusString);
					$map->addViewHeader($commandName, $status, (string)$header); 
				}
			}

			if ($command->viewfooter) {		
				foreach ($command->viewfooter as $footer) {
					$statusString = trim($footer['status']);
					$status = PFCommand::statuses($statusString);
					$map->addViewFooter($commandName, $status, (string)$footer); 
				}
			}

			if ($command->status) {		
				foreach ($command->status as $status) {
					$statusString = trim($status['value']);
					$statusValue = PFCommand::statuses($statusString);
					$map->addForward($commandName, $statusValue, (string)$status->forward[0]);
				}	
			}

			if ($command->login) {			
				foreach ($command->login as $login) {
					$statusString = trim($login['status']);
					$status = PFCommand::statuses($statusString);
					$map->addLogin($commandName, $status, (string)$login); 
				}
			}

			if (isset($command->permissions) && $command->permissions) {				
				foreach ($command->permissions->role as $role) {					
					$statusString = trim($login['status']);
					$status = PFCommand::statuses($statusString);
					$map->addPermission($commandName, $status, (string)$role); 
				}
			}

			if (isset($command->theme) && $command->theme) {				
				foreach ($command->theme as $theme) {					
					$statusString = trim($footer['status']);
					$status = PFCommand::statuses($statusString);
					$map->addTheme($commandName, $status, (string)$theme);
				}
			}
		}

		if (PF_CACHE_ENABLED == true && PF_CACHE_CONTROLLER_MAP == true) {		
			if (PF_APP_CONTROLLER_DEBUG == true) {
				PFDebugStack::append('Storing controller map ' . $configFile . ' to cache', __FILE__, __LINE__);
			}

			PFCacheAPC::getInstance()->store($configFile, $map, PF_CACHE_USER_TTL);
		}

		PFRegistry::getInstance()->setControllerMap($map);
	}
}

?>