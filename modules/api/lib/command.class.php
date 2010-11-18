<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

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
		$this->debug = PF_APP_CONTROLLER_DEBUG;
	}

	final public function __toString() {	
		$class = new ReflectionObject($this);		
		return $class->name;
	}

	public function execute(PFRequest $request) {

		$app = PFRequest::getCurrentURLApplication();
		$cmd = PFRequest::getCurrentURLCommand();
		$appCmd = $app . '.' . $cmd;
		$appUrl = $app . '.' . $this->getURLName();
		
		if ($appCmd != 'registration.login') {
			PFSession::getInstance()->register('pf.redirect_url', $appCmd);
		}

		$this->assignDefaults($request);

		if (PFSession::getInstance()->isRegistered('pf.unauthorized_url')) {
			$unauthorizedURL = PFSession::getInstance()->retrieve('pf.unauthorized_url');
		} else {
			$unauthorizedURL = 'registration.login';
		}

		try {

			if ($this->checkLogin($request) == false || $this->checkPermissions($request) == false) {

				if ($this->checkLogin($request) == false) {
					$e = new PFException('api', 'MUST_BE_LOGGED_IN', E_USER_ERROR);
				} else {
					$e = new PFException('api', 'INSUFFICIENT_PRIVILEGES_TO_RUN_COMMAND', E_USER_ERROR);
				}

				if (PFSession::getInstance()->isRegistered('pf.at_login_url') == false) {
					PFErrorStack::append($e);
				}

				$this->status = self::statuses('CMD_UNAUTHORIZED');
				$request->setCommand($this);

				$appController = PFApplicationHelper::getInstance()->appController();
				$forward = $appController->getForward($request, self::statuses('CMD_UNAUTHORIZED'));

				if (!$forward) {
					$map = PFRegistry::getInstance()->getControllerMap();
					$map->addForward($appCmd, self::statuses('CMD_UNAUTHORIZED'), $unauthorizedURL);
				}

				if ($this->debug) {
					PFDebugStack::append('-- Login required or missing permissions to run command ' . $request->getLastAppCmdRun(), __FILE__, __LINE__);
				}

				if (PFSession::getInstance()->isRegistered('pf.redirect_url') == false) {
					PFSession::getInstance()->register('pf.redirect_url', $appCmd);
				}

			} else {

				if (PFSession::getInstance()->isRegistered('auth_valid_login')) {
					if ($this->debug) {
						printr('auth_valid_login-isRegistered()');
					}
					$appCmd = PFSession::getInstance()->unregister('pf.redirect_url');
				}

				if (PFSession::getInstance()->isRegistered('pf.redirect_url') == true) {
					if ($this->debug) {
						printr('pf.redirct_url-isRegistered(): ' . PFSession::getInstance()->get('pf.redirect_url'));
					}
					// So here, we are redirecting, add a CMD_OK status forward to $unauthorizedURL, to forward there if 
					// we log in (login returns a CMD_OK status if we log in successfully)
					$map = PFRegistry::getInstance()->getControllerMap();
					$map->addForward($unauthorizedURL, self::statuses('CMD_OK'), 
						PFSession::getInstance()->retrieve('pf.redirect_url')); 
				} 

				if ($this->debug) {
					printr('Forward Map for ' . $unauthorizedURL);
					printr(PFRegistry::getInstance()->getControllerMap()->getForwardMap($unauthorizedURL));
					printr('-- About to run command ' . $this->getApplicationName() . '.' . $this->getURLName());
				}

				$request->setCommand($this);
				$this->status = $this->doExecute($request);

				if ($this->debug) {
					printr($_SESSION);
					printr('------ END OF COMMAND ------');
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

	public function getApplicationName() {
		$object = new ReflectionObject($this);
		$class = new ReflectionClass($object->name);

		$path = explode(DIRECTORY_SEPARATOR, $class->getFileName());
		return $path[count($path)-3];
	}

	public function getURLName() {
		$object = new ReflectionObject($this);
		$class = new ReflectionClass($object->name);

		$path = explode(DIRECTORY_SEPARATOR, $class->getFileName());
		$fileName = explode('.', array_pop($path));
		return $fileName[0];
	}

	protected function checkPermissions($request) { 	
		$appController = PFApplicationHelper::getInstance()->appController();
		$roles = $appController->getPermissions($request);

		if (!PFRegistrationUser::doesCurrentUserHavePermission($roles)) {
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