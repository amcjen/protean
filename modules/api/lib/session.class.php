<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

class PFSession {

	static private $instance;
	private $callbacks;

	private function __construct($session_expire='', $session_auth_expire='') {
		$debug = false;
		$this->formVariables = array();

		try {
			if (defined('PF_SESSION_PATH') && PF_SESSION_PATH != '') {
				if (!is_dir(PF_SESSION_PATH . '/sessions') && is_writable(PF_SESSION_PATH)) {
					mkdir(PF_SESSION_PATH . '/sessions', 0770);
				}
				session_save_path(PF_SESSION_PATH . '/sessions');
			}

			if ($session_expire != '') {
				session_set_cookie_params($session_expire * 60);
				session_cache_expire($session_expire);
			} else {
				if (defined('PF_SESSION_EXPIRE') && PF_SESSION_EXPIRE != '') {
					session_set_cookie_params(PF_SESSION_EXPIRE * 60);
					session_cache_expire(PF_SESSION_EXPIRE);
				}
			}

			if ($session_auth_expire == '') {
				$session_auth_expire = PF_SESSION_AUTH_EXPIRE;
			}

			if (defined('PF_SESSION_NAME') && PF_SESSION_NAME != '') {
				session_name(PF_SESSION_NAME);
			}

			ini_set('session.save_handler', 'files');

			if (php_sapi_name() != 'cli') {
				session_start();
			}

			$fingerprint = PF_SESSION_UNIQUE_KEY . @$_SERVER['HTTP_USER_AGENT'];

			if (!$this->isRegistered('auth_fingerprint')) {
				$this->register('auth_fingerprint', md5($fingerprint . session_id()));
			} elseif ($this->retrieve('auth_fingerprint') != md5($fingerprint . session_id()))  {
				// We comment this out b/c some Yahoo! user agents change during the session
				// $this->Logout();
				// $this->Destroy();
				//throw new PFException('', 'You have been logged out.  Please log in again.', E_USER_WARNING);
			}

			if (!$this->isRegistered('auth_expire')) {
				$this->register('auth_expire', time() + ($session_auth_expire * 60)); // Session start time + n seconds
			} else {
				if (time() >= $this->retrieve('auth_expire') && $this->isLoggedIn()) {
					$this->logout();
				} else {
					$this->register('auth_expire', time() + ($session_auth_expire * 60));
				}
			}

			if ($debug) {
				throw new PFException('', 'Session Variables: ' . date('M-d-Y H:i:s', $this->retrieve('auth_expire')), E_USER_NOTICE);
			}
		} catch (PFException $e) {
			$e->handleException();
		}
	}

	function __destruct() {
		session_write_close();
	}

	static public function getInstance($session_expire='', $session_auth_expire='') {
		if(self::$instance == NULL) {
			self::$instance = new PFSession($session_expire, $session_auth_expire);
		}

		return self::$instance;
	}

	public function retrieve($name) {
		if (!isset($_SESSION[$name]) || trim($_SESSION[$name]) == '') {
			return false;
		}
		return @unserialize($_SESSION[$name]);
	}

	public function get($name) {
		return $this->retrieve($name);
	}

	public function register($name, $value) {
		$_SESSION[$name] = serialize($value);
	}

	public function set($name, $value) {
		$this->register($name, $value);
	}

	public function unregister($name) {
		unset($_SESSION[$name]);
	}

	public function __unset($name) {
		$this->unregister($name);
	}

	public function isRegistered($name) {
		if (isset($_SESSION[$name]) && trim($_SESSION[$name]) != '' && @unserialize($_SESSION[$name]) != NULL) {
			return true;
		}

		return false;
	}

	public function __isset($name) {
		return $this->isRegistered($name);
	}

	public function destroy() {
		$_SESSION = array();

		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-42000, '/');
		}

		@session_destroy();
		self::$instance = NULL;
	}

	public function recreate() {
		PFSession::getInstance()->destroy();
		PFSession::getInstance();
	}

	public function login() {
		$this->register('auth_valid_login', true);

		if (count($this->callbacks) > 0) {
			foreach ($this->callbacks as $callback) {	
				try {		
					$code  = '$obj = new ' . $callback['class'] . ';';
					$code .= '$obj->' . $callback['method'] . '(' . implode(',', $callback['params']) . ');';
					eval($code);
				} catch (Exception $e) {
					PFException::handleVanillaException($e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());	
				}
			}
		}
	}

	public function logout($quiet=false) {
		if ($quiet == false) {
			PFErrorStack::append(new PFException('content', 'YOU_HAVE_BEEN_LOGGED_OUT', E_USER_NOTICE));
		}

		$this->destroy();
	}

	public function isPersistentCookieSet() {
		return (@$_COOKIE[PF_SESSION_PERSIST_NAME] == '1');

	}

	public function setPersistentCookie($seconds=31536000) {
		return $this->setCookie(PF_SESSION_PERSIST_NAME, '1', time() + $seconds);
	}

	public function removePersistentCookie() {
		return $this->setCookie(PF_SESSION_PERSIST_NAME, '0', time() - 3600);
	}

	public function setCookie($name, $value, $expires) {
		if ($expires == 0) {
			$expires = 0;
		} else {
			$expires = time() + $expires;
		}

		return setcookie($name, $value, $expires, '/');
	}

	public function getCookie($name) {
		if (isset($_COOKIE[$name])) {
			return $_COOKIE[$name];
		} else {
			return false;
		}
	}

	public function removeCookie($name) {
		return setcookie($name, '', time() - 3600);
	}

	public function isLoggedIn() {
		return $this->isRegistered('auth_valid_login');
	}

	public function storeFormVariables($formName, $variables) {
		foreach ($variables as $name => $value) {
			if (is_array($value)) {
				$_SESSION['form-' . $formName][$name] = $value;
			} else {
				$_SESSION['form-' . $formName][$name] = addslashes($value);
			}
		}
	}

	public function registerLoginCallback($className, $methodName, $parameters=array()) {
		$this->callbacks[$className . '.' . $methodName] = array('class' => $className, 'method' => $methodName, 'params' => $parameters);
	}

	public function unregisterLoginCallback($className, $methodName) {	
		unset($this->callbacks[$className . '.' . $methodName]);
	}

	public function getFormVariables($formName) {
		return $_SESSION['form-' . $formName];
	}

	public function isFormVariableSet($formName) {
		return isset($_SESSION['form-' . $formName]) && trim($_SESSION['form-' . $formName]) != '';
	}

	public function getURL($text, $ssl='', $lang='') {		
		if ($ssl === true) {
			$fullpath = PF_URL_SECURE;
		} else if ($ssl === false) {
			$fullpath = PF_URL;
		} else {
			if (isset($_SERVER['HTTPS'])) {
				$fullpath = PF_URL_SECURE;
			} else {
				$fullpath = PF_URL;
			}
		}

		if ($lang == '') {
			$lang = PFLanguage::getInstance()->getCurrentLanguage();
		}

		if (count($_COOKIE) < 1) {
			if (strpos($text, '?')) {
				$delim = '&';
			}
			else {
				$delim = '?';
			}

			if (php_sapi_name() == 'cli') {			
				if (PF_SHORT_URLS) {
					return $fullpath . '/' . $text;
				} else {
					return $fullpath . '/main/' . $lang . '/' . $text;
				}

			} else {	
				if (PF_SHORT_URLS) {
					return $fullpath . '/' . $text;
				} else {
					return $fullpath . '/main/' . $lang . '/' . $text;
				}
			}

		}
		else {
			return $fullpath . '/' . $text;
		}
	}

	public function getSelfURL($ssl=false, $lang='') {
		$text = PFRequest::getCurrentURLApplication() . '/' . PFRequest::getCurrentURLCommand();
		return $this->getURL($text, $ssl, $lang);
	}

	public function getSelfURLWithVars($ssl=false, $lang='') {
		$text = PFRequest::getCurrentURLApplication() . '/' . PFRequest::getCurrentURLCommand() . '?' . $_SERVER["QUERY_STRING"];
		return $this->getURL($text, $ssl, $lang);
	}

	public function getApplicationPath($appName, $ssl=false) {
		if ($ssl) {
			$fullpath = 'https://' . $_SERVER["HTTP_HOST"];
		} else {
			$fullpath = '';
		}
		return $fullpath . '/modules/' . $appName;
	}

	public function getImagePath($appName, $ssl=false) {
		if ($ssl || @$_SERVER['SERVER_PORT'] == '443') {
			$fullpath = 'https://' . $_SERVER["HTTP_HOST"];
		} else {
			$fullpath = '';
		}
		return $fullpath . '/modules/' . $appName . '/tpl/' . PFRegistry::getInstance()->get('pf_theme') . '/images';
	}

	public function getCSSPath($appName, $ssl=false) {
		if ($ssl || @$_SERVER['SERVER_PORT'] == '443') {
			$fullpath = 'https://' . $_SERVER["HTTP_HOST"];
		} else {
			$fullpath = '';
		}
		return $fullpath . '/modules/' . $appName . '/tpl/' . PFRegistry::getInstance()->get('pf_theme') . '/css';
	}

	public function getJSPath($appName, $ssl=false) {
		if ($ssl || @$_SERVER['SERVER_PORT'] == '443') {
			$fullpath = 'https://' . $_SERVER["HTTP_HOST"];
		} else {
			$fullpath = '';
		}
		return $fullpath . '/modules/' . $appName . '/tpl/' . PFRegistry::getInstance()->get('pf_theme') . '/js';
	}

	public function getSWFPath($appName, $ssl=false) {
		if ($ssl || @$_SERVER['SERVER_PORT'] == '443') {
			$fullpath = 'https://' . $_SERVER["HTTP_HOST"];
		} else {
			$fullpath = '';
		}
		return $fullpath . '/modules/' . $appName . '/tpl/' . PFRegistry::getInstance()->get('pf_theme') . '/swf';
	}
}
?>
