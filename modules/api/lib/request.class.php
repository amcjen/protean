<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/
	
class PFRequest { 

	protected $properties;
	protected $feedback;
	protected $command;
	protected $queryString;
	
	public function __construct() {
	
		$this->properties = array();
		$this->feedback = array();
		$this->exceptions = array();
		$this->command = NULL;
		$this->queryString = NULL;
		$this->init();
	}
	
	public function init() {
		if (php_sapi_name() == 'cli') {	
			foreach ($_SERVER['argv'] as $arg) {
				if (strpos($arg, '=')) {	
					list($key, $value) = explode('=', $arg);
					$this->setProperty($key, $value);
				}
			}
			
			return;
		}

		foreach ($_REQUEST as $requestKey => $requestVar) {
			$this->setProperty($requestKey, $requestVar);
		}
		
		$this->initQueryString();
		$this->setProperty('lang', PFRequest::getCurrentURLLanguage());
		$this->setProperty('app',  PFRequest::getCurrentURLApplication());	
		$this->setProperty('cmd', PFRequest::getCurrentURLCommand());

		PFRegistry::getInstance()->set('APPNAME', $this->getProperty('app')); 
	}
	
	public function getProperty($key) {
		return @html_entity_decode($this->properties[$key], ENT_QUOTES, 'UTF-8');
	}
	
	public function get($key) {
		return $this->getProperty($key);
	}
	
	public function setProperty($key, $value) {	
		if (is_array($value)) {
			$data = array();
			foreach ($value as $valueElement) {
				$data[] = htmlentities($valueElement, ENT_QUOTES, 'UTF-8');
			}
			
			$this->properties[$key] = $data;
			$_REQUEST[$key] = $data;
			
		} else {
			$this->properties[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');
			$_REQUEST[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');
		}
	}
	
	public function set($key, $value) {
		$this->setProperty($key, $value);
	}
	
	public function unsetProperty($key){
		unset($this->properties[$key]);
		unset($_REQUEST[$key]);
		unset($_POST[$key]);
		unset($_GET[$key]);
	}
	
	public function __unset($key) {
		$this->unsetProperty($key);
	}

	public function isPropertySet($key){
		return (isset($_REQUEST[$key]) || isset($_POST[$key]) || isset($_GET[$key]));
	}
	
	public function __isset($key) {
		return $this->isPropertySet($key);
	}
	
	public function getLastApplicationRun() {
		return $this->command->getApplicationName();
	}

	public function getLastCommandURLNameRun() {
		return $this->command->getURLName();
	}
	
	public function getLastCommandRun() {
		return $this->command;
	}

	public function getLastAppCmdRun() {
		return $this->getLastApplicationRun() . '.' . $this->getLastCommandURLNameRun();
	}
	
	public function setCommand(PFCommand $command) {	
		$this->command = $command;
	}
	
	public function addFeedback($app, $message, $status) {
		$fullMessage = $message;

		if ($app == '') {
			if (is_array($message)) {
				$fullMessage = sprintf($message[0], $message[1]);
			} 
		} else {
			if (is_array($message)) {
				$fullMessage = sprintf(PFLanguage::getInstance()->getTranslation($app, $message[0]), $message[1]);
			} else {
				$fullMessage = PFLanguage::getInstance()->getTranslation($app, $message);
			}
		}
		
		array_push($this->feedback, $fullMessage);
		
		switch ($status) {			
			case 'INSUFFICIENT_DATA':
				$e = new PFException($app, $message, E_INSUFFICIENT_DATA);
				PFErrorstack::append($e);
				array_push($this->exceptions, $e);
				break;			
			case 'NOTICE':
				$e = new PFException($app, $message, E_USER_NOTICE);
				PFErrorstack::append($e);
				array_push($this->exceptions, $e);
				break;
			case E_USER_WARNING:
				$e = new PFException($app, $message, $status);
				PFErrorstack::append($e);
				array_push($this->exceptions, $e);
				break;
			case E_USER_ERROR:
			case E_USER_NOTICE:
				$e = new PFException($app, $message, $status);
				PFErrorstack::append($e);
				array_push($this->exceptions, $e);
				break;			
			default:
				$e = new PFException($app, $message, E_ERROR);
				PFErrorstack::append($e);
				array_push($this->exceptions, $e);
		}
	}
	
	public function getFeedback() {
		return $this->feedback;
	}
	
	public function getExceptions() {
		return $this->exceptions;
	}

	public function rebuildErrorStack(){
		if ($this->exceptions){
			foreach ($this->exceptions as $exception){
				$this->appendToErrorStack($exception);
			}
		}
	}

	public function appendToErrorStack(PFException $e){
		PFErrorstack::append($e);
	}

	public function getFeedbackString($separator='\n') {
		return implode($separator, $this->feedback);
	}
	
	public function initQueryString() {
		$qString = array();
		$qStringArray = explode('&', @$_SERVER['QUERY_STRING']);
		
		foreach ($qStringArray as $element) {		
			$el = explode('=', $element);			 
			if (array_key_exists(1, $el)) {		
				$qString[$el[0]] = $el[1];
			}
		}
		
		PFRegistry::getInstance()->set('pf_query_string', $qString);
	}
	
	static public function getCurrentURLLanguage() {
		$uriArray = explode('/', @$_SERVER['REQUEST_URI']); 
		if (PF_SHORT_URLS == true) {
			return PFLanguage::getInstance()->getDefaultLocale();
		} else {
			if (@$uriArray[2]) {
				return $uriArray[2];
			} else {
				return PFLanguage::getInstance()->getDefaultLocale();
			}
		}
	}
	
	static public function getCurrentURLApplication() {
		$uriArray = explode('/', @$_SERVER['REQUEST_URI']); 
			
		if (PF_SHORT_URLS == true) {
			$index = 1;
		} else {
			$index = 3;
		}

		if (@$uriArray[$index+2] && is_dir(PF_BASE . '/modules/' . $uriArray[$index+2])) {
			return $uriArray[$index+2];
		} elseif (@$uriArray[$index]) {
			return $uriArray[$index];
		} else {
			list($app, $cmd) = explode('.', PF_DEFAULT_COMMAND);
			return $app;
		}
	}
		
	static public function getCurrentURLCommand() {
		$uriArray = explode('/', @$_SERVER['REQUEST_URI']); 
		if (PF_SHORT_URLS == true) {
			$index = 2;
		} else {
			$index = 4;
		}

		if (@$uriArray[$index+2] && is_dir(PF_BASE . '/modules/' . $uriArray[$index+1])) {
			$cmd = explode('?', $uriArray[$index+2]);
			return $cmd[0];		
		} elseif (@$uriArray[$index]) {
			$cmd = explode('?', $uriArray[$index]);
			return $cmd[0];
		} else {
			list($app, $cmd) = explode('.', PF_DEFAULT_COMMAND);
			return $cmd;
		}
	}

	static public function explodeURI() {
		$uriArray = explode('/', @$_SERVER['PHP_SELF']);

		return $uriArray;
	}
	
	static public function getRESTSlot($indexOffset) {
		$uriArray = explode('/', @$_SERVER['REQUEST_URI']); 

		if (PF_SHORT_URLS == true) {
			$index = 2;
		} else {
			$index = 4;
		}
		
		$uriArray = explode('?', @$uriArray[$index + $indexOffset]); 
		return @$uriArray[0];
	}
}

?>