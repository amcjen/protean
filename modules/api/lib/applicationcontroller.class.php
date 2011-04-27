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
	protected $controllerMap = array();
	protected $invoked = array();

	protected $debug;

	public function __construct(PFControllerMap $map) {

		$this->debug = PF_APP_CONTROLLER_DEBUG;
		$defaultApp = PFRequestHelper::getDefaultURIApplication();
		$this->controllerMap[$defaultApp] = $map;
		
		list($defaultApp, $defaultCmd) = explode('.', $this->controllerMap[$defaultApp]->getCommand(PF_DEFAULT_URI . '|get'));

		if (!self::$baseCommand) {	
			self::$baseCommand = new ReflectionClass('PFCommand');
			PFFactory::getInstance()->initCommandObject($defaultApp . '.' . $defaultCmd);	
		}
	}
	
	public function addControllerMap($app) {

		if (array_key_exists($app, $this->controllerMap)) {
			return;
		}
		
		$map = PFApplicationHelper::getInstance()->loadControllerMap($app);
		$this->controllerMap[$app] = $map;
	}
	
	public function getControllerMap($app) {
		if (!array_key_exists($app, $this->controllerMap)) {
			$this->addControllerMap($app);
		}
		return $this->controllerMap[$app];
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

		@list($app, $tpl) = explode('.', $this->getResource($request, 'View'));
		return $app;
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

		@list($app, $tpl) = explode('.', $this->getResource($request, 'ViewHeader'));
		return $app;
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

		@list($app, $tpl) = explode('.', $this->getResource($request, 'ViewFooter'));
		return $app;
	}

	public function getForward(PFRequest $request) {
		return $this->getResource($request, 'Forward');
	}

	public function getLogin(PFRequest $request) {
		return $this->getResource($request, 'Login');
	}

	public function getPermissions(PFRequest $request) {
		return $this->getResource($request, 'Permissions');
	}

	public function getTheme(PFRequest $request) {
		return $this->getResource($request, 'Theme');
	}

	private function getResource(PFRequest $request, $resource) {

		$uri = PFRequestHelper::getURIPattern($request->get('pf.uri'));
		$verb = PFRequestHelper::getHTTPVerbForURIPattern($request->get('pf.uri'));
		$app = PFRequestHelper::getURIApplication($uri, $verb);
		$cmd = PFRequestHelper::getURICommand($uri, $verb);
		$this->addControllerMap($app);
	
		$cmdString = $uri . '|' . $verb;
		$previous = $request->getLastCommandRun();

		// printr('resource: ' . $resource);
		// printr('pf.uri: ' . $request->get('pf.uri'));
		// printr('uri: ' . $uri);
		// printr('verb: ' . $verb);
		// printr('app: ' . $app);
		// printr('cmd: ' . $cmd);	

		if (is_object($previous)) {
			$status = $previous->getStatus();
		}

		if (!isset($status)) {
			$status = 0;
		}

		$acquire = 'get' . $resource; 
		// printr('acquire: ' . $acquire);
		// printr($cmdString);
		// printr($request);
		// printr($this->controllerMap);

		$res = $this->controllerMap[$app]->$acquire($cmdString, $status);
		
		if (!$res) {
			$res = $this->controllerMap[$app]->$acquire($cmdString, 0);
		}
		if (!$res) {
			$res = $this->controllerMap[$app]->$acquire(PF_DEFAULT_URI . '|get', $status);
		}
		if (!$res) {
			$res = $this->controllerMap[$app]->$acquire(PF_DEFAULT_URI . '|get', 0);
		}

		if ($this->debug) {	
			printr('appController::getResource:  ' . $acquire . '(' . $cmdString . ', ' . $status . ') -> ' . $res);	
		}

		return $res;
	}

	public function getCommand(PFRequest $request) {

		$previous = $request->getLastCommandRun();

		$app = PFRequestHelper::getCurrentURIApplication();
		$cmd = PFRequestHelper::getCurrentURICommand();
		$this->addControllerMap($app);

		if (!$previous) {
			$commandType = 'Running';			
			$uri = PFRequestHelper::getCurrentURIPattern();
			$verb = PFRequestHelper::getHTTPVerb();

		} else {
			$commandType = 'Forwarded to';
			$uri = $this->getForward($request);
			$verb = PFRequestHelper::getHTTPVerbForURIPattern($uri);
		
			if (!$uri) {
				return NULL;
			}
		}
		
		$request->set('pf.uri', $uri . '|' . $verb);

		if ($this->debug) {
			$cmd = PFRequestHelper::getURICommand($uri, $verb);
			printr('-- ' . $commandType . ' command ' . $uri . '|' . $verb);
		}

		$cmdObject = $this->resolveCommand($uri, $verb);

		if (!$cmdObject) {
			
			$cmdObject = $this->resolveCommand($uri, $verb);
			if (!$cmdObject) {
				if ($this->debug) {
					printr('-- ' . $commandType . ' command ' . 'content' . '.' . 'notfound');
				}

				$cmdObject = $this->resolveCommand('/content/notfound', 'get');
				$request->set('pf.uri', '/content/notfound|get');
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

	protected function resolveCommand($uri, $verb) {
		
		if ($uri == '') {
			$uri = PF_DEFAULT_URI;
		}
		
		$app = PFRequestHelper::getURIApplication($uri, $verb);
		$cmd = PFRequestHelper::getURICommand($uri, $verb);
	
		$filepath = PF_BASE . '/modules/' . $app . '/cmd/' . $cmd . '.class.php';
		$classname = 'PF' . ucfirst($cmd) . 'Command';

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