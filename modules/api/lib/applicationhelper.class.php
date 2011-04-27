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
class PFApplicationHelper { 

	private static $instance;

	private function __construct() { }

	static function getInstance() {
		if (!self::$instance) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}

	public function init($configFileOverride='') {
		$app = PFRequestHelper::getDefaultURIApplication();	
		
		$map = $this->loadControllerMap($app, $configFileOverride);
		PFRegistry::getInstance()->setControllerMap($map);
	}

	public function appController() {
		if (PFRegistry::getAppController()) {
			return PFRegistry::getAppController();
		}

		$map = PFRegistry::getControllerMap();

		if (!is_object($map)) {
			throw new PFException('api', 'NO_CONTROLLER_MAP', E_USER_ERROR);
		}

		$appController = PFFactory::getInstance()->createObject('api.applicationcontroller', $map);
		PFRegistry::setAppController($appController);
		return $appController;
	}

	public function loadControllerMap($app, $configFileOverride='') {

		$configFile = PF_BASE . '/modules/' . $app . '/cmd/command.xml';

		if ($configFileOverride != '') {
			$configFile = $configFileOverride;
		}

		if (!file_exists($configFile)) {	
			$default = explode('/', PF_DEFAULT_URI);
			$configFile = PF_BASE . '/modules/' . $default[1] . '/cmd/command.xml';
		}

		if (PF_CACHE_ENABLED == true && PF_CACHE_CONTROLLER_MAP == true) {
			PFFactory::getInstance()->initObject('api.controllermap');
			PFFactory::getInstance()->initObject('api.cacheapc');

			$map = PFCacheAPC::getInstance()->fetch($configFile);

			if ($map instanceof PFControllerMap) {
				if (PF_APP_CONTROLLER_DEBUG == true) {
					PFDebugStack::append('Reading controller map ' . $configFile . ' from cache', __FILE__, __LINE__);
				}

				return $map;
			}
		}

		$options = @SimpleXml_load_file($configFile);
		
		if (!$options instanceof SimpleXMLElement) {
			throw new PFException('api', array('COMMAND_FILE_NOT_RESOLVABLE', $configFile), E_USER_ERROR);
		}

		$map = PFFactory::getInstance()->createObject('api.controllermap');

		foreach ($options->command as $defaultCommand) {
			$map->addCommand(PF_DEFAULT_URI . '|get', (string)$defaultCommand);
		}
		
		foreach ($options->view as $defaultView) {
			$statusString = trim($defaultView['status']);
			$status = PFCommand::statuses($statusString);
			$map->addView(PF_DEFAULT_URI . '|get', $status, (string)$defaultView);
		}

		foreach ($options->viewheader as $defaultViewHeader) {
			$statusString = trim($defaultViewHeader['status']);
			$status = PFCommand::statuses($statusString);
			$map->addViewHeader(PF_DEFAULT_URI . '|get', $status, (string)$defaultViewHeader);
		}

		foreach ($options->viewfooter as $defaultViewFooter) {
			$statusString = trim($defaultViewFooter['status']);
			$status = PFCommand::statuses($statusString);
			$map->addViewFooter(PF_DEFAULT_URI . '|get', $status, (string)$defaultViewFooter);
		}

		foreach ($options->uri as $uri) {
			if (empty($uri['verb'])) {
				$uri['verb'] = 'get';
			}
			
			$commandName = strtolower(trim($uri['name']) . '|' . strtolower(trim($uri['verb'])));

			if ($uri->command) {			
				$map->addCommand($commandName, (string)$uri->command);
			}

			if ($uri->view) {		
				foreach ($uri->view as $view) {			
					$statusString = trim($view['status']);
					$status = PFCommand::statuses($statusString);
					$map->addView($commandName, $status, (string)$view); 
				}
			}

			if ($uri->viewheader) {		
				foreach ($uri->viewheader as $header) {
					$statusString = trim($header['status']);
					$status = PFCommand::statuses($statusString);
					$map->addViewHeader($commandName, $status, (string)$header); 
				}
			}

			if ($uri->viewfooter) {		
				foreach ($uri->viewfooter as $footer) {
					$statusString = trim($footer['status']);
					$status = PFCommand::statuses($statusString);
					$map->addViewFooter($commandName, $status, (string)$footer); 
				}
			}

			if ($uri->status) {		
				foreach ($uri->status as $status) {
					$statusString = trim($status['value']);
					$statusValue = PFCommand::statuses($statusString);
					//printr('Adding forward from ' . $commandName . ' to ' . (string)$status->forward[0] . ' for status ' . $statusValue);
					$map->addForward($commandName, $statusValue, (string)$status->forward[0]);
					//printr($map);
				}	
			}

			if ($uri->login) {			
				foreach ($uri->login as $login) {
					$statusString = trim($login['status']);
					$status = PFCommand::statuses($statusString);
					$map->addLogin($commandName, $status, (string)$login); 
				}
			}

			if (isset($uri->permissions) && $uri->permissions) {				
				foreach ($uri->permissions->role as $role) {					
					$statusString = trim($role['status']);
					$status = PFCommand::statuses($statusString);
					$map->addPermission($commandName, $status, (string)$role); 
				}
			}

			if (isset($uri->theme) && $uri->theme) {				
				foreach ($uri->theme as $theme) {					
					$statusString = trim($theme['status']);
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

		return $map;
	}
}

?>
