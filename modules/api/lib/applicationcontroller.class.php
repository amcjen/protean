<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

class PFApplicationController { 

	private static $STATUS_STRINGS = array (
		'CMD_DEFAULT',
		'CMD_OK',
		'CMD_ERROR',
		'CMD_INSUFFICIENT_DATA',
		'CMD_FORM_FAILED',
		'CMD_UNAUTHORIZED'
		);

	protected static $baseCommand;
	protected $controllerMap;
	protected $invoked = array();

	protected $debug;

	public function __construct(PFControllerMap $map) {

		$this->debug = PF_APP_CONTROLLER_DEBUG;

		$this->controllerMap = $map;
		list($defaultApp, $defaultCmd) = explode('.', PF_DEFAULT_COMMAND);


		if (!self::$baseCommand) {	
			self::$baseCommand = new ReflectionClass('PFCommand');
			PFFactory::getInstance()->initCommandObject($defaultApp .  '.default');	
		}
	}

	public function getView(PFRequest $request) {

		list($app, $tpl) = explode('.', $this->getResource($request, 'View'));
		if (!empty($tpl)) {
			return $tpl . '.tpl';
		} else {
			return '';
		}
	}

	public function getViewApplication(PFRequest $request) {

		@list ($appname, $view) = explode('.', $this->getResource($request, 'View'));
		return $appname;
	}

	public function getViewHeader(PFRequest $request) {

		@list($app, $tpl) = explode('.', $this->getResource($request, 'ViewHeader'));

		if (!empty($tpl)) {
			return $tpl . '.tpl';
		} else {
			return '';
		}
	}

	public function getViewHeaderApplication(PFRequest $request) {

		@list ($appname, $view) = explode('.', $this->getResource($request, 'ViewHeader'));
		return $appname;
	}

	public function getViewFooter(PFRequest $request) {

		list($app, $tpl) = explode('.', $this->getResource($request, 'ViewFooter'));
		if (!empty($tpl)) {
			return $tpl . '.tpl';
		} else {
			return '';
		}
	}

	public function getViewFooterApplication(PFRequest $request) {

		@list ($appname, $view) = explode('.', $this->getResource($request, 'ViewFooter'));
		return $appname;
	}

	public function getForward(PFRequest $request) {

		$forward = $this->getResource($request, 'Forward');
		return $forward;
	}

	public function getLogin(PFRequest $request) {

		$login = $this->getResource($request, 'Login');
		return $login;
	}

	public function getPermissions(PFRequest $request) {

		$roles = $this->getResource($request, 'Permissions');
		return $roles;
	}

	public function getTheme(PFRequest $request) {

		$theme = $this->getResource($request, 'Theme');
		return $theme;
	}

	private function getResource(PFRequest $request, $resource) {

		$app = $request->getProperty('app');
		$cmd = $request->getProperty('cmd');

		$cmdString = $app . '.' . $cmd;
		$previous = $request->getLastCommandRun();

		if (is_object($previous)) {
			$status = $previous->getStatus();
		}

		if (!isset($status)) {
			$status = 0;
		}

		$acquire = 'get' . $resource;

		PFApplicationHelper::getInstance()->loadControllerMap($app);
		$this->controllerMap = PFRegistry::getInstance()->getControllerMap();

		$res = $this->controllerMap->$acquire($cmdString, $status);

		if (!$res) {
			$res = $this->controllerMap->$acquire($cmdString, 0);
		}
		if (!$res) {
			$res = $this->controllerMap->$acquire(PF_DEFAULT_COMMAND, $status);
		}
		if (!$res) {
			$res = $this->controllerMap->$acquire(PF_DEFAULT_COMMAND, 0);
		}

		if ($this->debug) {				
			PFDebugStack::append($acquire . '(' . $cmdString . ') -> ' . $res, __FILE__, __LINE__);	
		}

		return $res;
	}

	public function getCommand(PFRequest $request) {

		$previous = $request->getLastCommandRun();
		$commandType = 'Running';

		if (!$previous) {
			$app = $request->getProperty('app');
			$cmd = $request->getProperty('cmd');

		} else {		
			$commandType = 'Forwarded to';
			$command = $this->getForward($request);

			if (!$command) {
				return NULL;
			}

			list($app, $cmd) = explode('.', $command);
			$request->setProperty('app', $app);
			$request->setProperty('cmd', $cmd);
		}

		if ($this->debug) {
			PFDebugStack::append('-- ' . $commandType . ' command ' . $app . '.' . $cmd, __FILE__, __LINE__);
		}

		$cmdObject = $this->resolveCommand($app, $cmd);

		if (!$cmdObject) {

			PFApplicationHelper::getInstance()->loadControllerMap($app);
			$this->controllerMap = PFRegistry::getInstance()->getControllerMap();
			$cmdObject = $this->resolveCommand($app, $cmd);

			if (!$cmdObject) {
				if ($this->debug) {
					PFDebugStack::append('-- ' . $commandType . ' command ' . 'content' . '.' . '404', __FILE__, __LINE__);
				}
				$cmdObject = $this->resolveCommand('content', '404');
				$request->setProperty('app', 'content');
				$request->setProperty('cmd', '404');
			}
		}

		$cmdClass = get_class($cmdObject);
		@$this->invoked[$cmdClass]++;

		if ($this->invoked[$cmdClass] > 1) {
			PFErrorStack::clearErrorStack();
		}

		if ($this->invoked[$cmdClass] > 2) {
			throw new PFException('api', array('CIRCULAR_COMMAND_FORWARDING', $cmdClass), E_USER_ERROR);
		}

		return $cmdObject;
	}

	protected function resolveCommand($app, $command) {

		$app = str_replace(array('.','/'), '', $app);
		$command = str_replace(array('.','/'), '', $command);

		$classroot = $this->controllerMap->getClassroot($app . '.' . $command);

		$newroot = explode('.', $classroot);

		$app = $newroot[0];
		$command = $newroot[1];

		$filepath = PF_BASE . '/modules/' . $app . '/cmd/' . $command . '.class.php';
		$classname = 'PF' . ucfirst($command) . 'Command';

		if (file_exists($filepath)) {

			require_once $filepath;

			if (class_exists($classname)) {

				$cmdClass = new reflectionClass($classname);
				if ($cmdClass->isSubClassOf(self::$baseCommand)) {
					return $cmdClass->newInstance();
				}
			}
		}

		return NULL;
	}
}

?>