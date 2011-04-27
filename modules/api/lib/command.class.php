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
abstract class PFCommand { 

	private static $STATUS_STRINGS = array (
		'CMD_DEFAULT' => 0,
		'CMD_OK' => 1,
		'CMD_ERROR' => 2,
		'CMD_INSUFFICIENT_DATA' => 3,
		'CMD_FORM_FAILED' => 4,
		'CMD_UNAUTHORIZED' => 5
		);

	private $status;
	private $debug;

	public function __construct() {
		$this->status = self::statuses('CMD_DEFAULT');
		$this->debug = PF_APP_ROUTER_DEBUG;
	}

	final public function __toString() {	
		$class = new ReflectionObject($this);		
		return $class->name;
	}

	public function execute(PFRequest $request) {

		$session = PFSession::getInstance();
		$uriArray = explode('|', $request->get('pf.uri'));
		$uri = $uriArray[0];
		$verb = $uriArray[1];
		$app = PFRequestHelper::getURIApplication($uri, $verb);
		$cmd = PFRequestHelper::getURICommand($uri, $verb);
		$uriPattern = PFRequestHelper::getCurrentURIPattern($uri);

		$appController = PFApplicationHelper::getInstance()->appController();
		
		if ($uri != '/registration/login' && $uri != '/registration/create') {
			$session->set('pf.redirect_url', $uri);	
			$session->set('pf.session_redirect_url', @$_SERVER['REQUEST_URI']);
		}

		$this->assignDefaults($request);

		if ($session->isRegistered('pf.unauthorized_url')) {
			$unauthorizedURL = $session->get('pf.unauthorized_url');
		} else {
			$unauthorizedURL = '/registration/login';
		}

		if ($this->debug) {
			printr('------ START OF COMMAND EXECUTIONS ------');
		}

		try {

			if ($this->checkLogin($request) == false || $this->checkPermissions($request) == false) {

				if ($this->checkLogin($request) == false) {
					$e = new PFException('api', 'MUST_BE_LOGGED_IN', E_USER_ERROR);
				} else {
					$e = new PFException('api', 'INSUFFICIENT_PRIVILEGES_TO_RUN_COMMAND', E_USER_ERROR);
				}

				if ($uri != $unauthorizedURL) {
					PFErrorStack::append($e);
				}

				$this->status = self::statuses('CMD_UNAUTHORIZED');
				$request->setCommand($this);

				$forward = $appController->getForward($request, self::statuses('CMD_UNAUTHORIZED'));

				if ($this->debug) {
					printr('-- Login required or missing permissions to run URI ' . $uri);
				}
					
				// if (!$forward) {
				// 					if ($this->debug) {
				// 						printr('-- Adding forward for unauthorized URI ' . $uri . ' to ' . $unauthorizedURL);
				// 					}
				// 					$map = $appController->getControllerMap($app);
				// 					$map->addForward($request->get('pf.uri'), self::statuses('CMD_UNAUTHORIZED'), $unauthorizedURL);
				// 				}

				if ($session->isRegistered('pf.redirect_url') == false) {
					$session->register('pf.redirect_url', $uri);
				}

			} else {

				if ($session->isRegistered('auth_valid_login')) {
					if ($this->debug) {
						printr('auth_valid_login-isRegistered()');
					}
					$appCmd = $session->unregister('pf.redirect_url');
				}

				if ($session->isRegistered('pf.redirect_url') == true) {
					if ($this->debug) {
						printr('pf.redirct_url-isRegistered(): ' . $session->get('pf.redirect_url'));
					}
					// So here, we are redirecting, add a CMD_OK status forward to $unauthorizedURL, to forward there if 
					// we log in (login returns a CMD_OK status if we log in successfully)
					$unauthorizedApp = PFRequestHelper::getURIApplication($unauthorizedURL, 'get');
					$map = $appController->getControllerMap($unauthorizedApp);
					$map->addForward($unauthorizedURL, self::statuses('CMD_OK'), $session->get('pf.redirect_url')); 
				} 

				if ($this->debug) {
					printr('-- About to run command ' . $app . '.' . $cmd . '(' . $request->get('pf.uri') . ')');
				}

				$request->setCommand($this);
				$this->status = $this->doExecute($request);

				if ($this->debug) {
					printr($_SESSION);
					printr('------- END OF COMMAND EXECUTIONS -------');
				}
			}

		} catch (PFException $e) {
			$e->handleException();
		} catch (Exception $e) {
			printr($e);
			PFException::handleVanillaException($e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
		}
	}

	public function getStatus() {
		return $this->status;
	}

	public static function statuses($statusString='CMD_DEFAULT') {
		if (empty($statusString)) {
			$statusString = 'CMD_DEFAULT';
		}

		return self::$STATUS_STRINGS[$statusString];
	}

	protected function checkPermissions($request) { 	
		$appController = PFApplicationHelper::getInstance()->appController();
		$roles = $appController->getPermissions($request);
		$userHelper = PFFactory::getInstance()->createObject('registration.userhelper');
		
		if (!$userHelper->doesCurrentUserHavePermission($roles)) {
			return false;
		}

		return true;
	}

	protected function checkLogin($request) {	
		$appController = PFApplicationHelper::getInstance()->appController();
		$loginRequired = $appController->getLogin($request);

		if ($loginRequired && !PFSession::getInstance()->isLoggedIn()) {
			return false;
		}

		return true;
	}

	protected function redirectTo($url, $clearSessionRedirect=false) {
		$url = str_replace(PF_URL, '', $url);
		$url = str_replace(PF_URL_SECURE, '', $url);

		if(strpos($url, '/') == 0){
			$url = '/'.ltrim($url,'/');
		}
		if ($clearSessionRedirect == true) {
			PFSession::getInstance()->unregister('pf.redirect_url');
		}
		header('Location: '.$url);
		exit();
	}
	
	protected function redirectToSessionUrl() {
		
		$url = PFSession::getInstance()->get('pf.session_redirect_url');
		
		$url = str_replace(PF_URL, '', $url);
		$url = str_replace(PF_URL_SECURE, '', $url);

		if(strpos($url, '/') == 0){
			$url = '/'.ltrim($url,'/');
		}
		PFSession::getInstance()->unregister('pf.session_redirect_url');

		header('Location: '.$url);
		exit();
	}
	
	abstract function assignDefaults(PFRequest $request);
	abstract function doExecute(PFRequest $request);
}

?>