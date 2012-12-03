<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2012, Eric Jennings.  All rights reserved.            *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/
/**
@package api
*/	
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
					$this->set($key, $value);
				}
			}
			
			return;
		}

		foreach ($_REQUEST as $requestKey => $requestVar) {
			$this->set($requestKey, $requestVar);
		}
		
		$this->set('pf.uri', PFRequestHelper::getCurrentURIPattern() . '|' . PFRequestHelper::getHTTPVerb());

		PFRegistry::getInstance()->set('APPNAME', $this->get('app')); 
	}
	
	public function getProperty($key) {
		if ( isset($this->properties[$key]) && is_array($this->properties[$key]) ){
		 	$new_array = $this->properties[$key];
			foreach ($this->properties[$key] as $index => $value){
				$new_array[$index] = @html_entity_decode($value, ENT_QUOTES, 'UTF-8');		
			}
		 	return $new_array;
		}
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
	
	public function isPropertySet($key){
		return (isset($_REQUEST[$key]) || isset($_POST[$key]) || isset($_GET[$key]));
	}
	
	// public function getLastApplicationRun() {
	// 	return PFRequestHelper::getCurrentURIApplication();
	// 	return $this->command->getApplicationName();
	// }
	// 
	// public function getLastCommandURLNameRun() {
	// 	return PFRequestHelper::getCurrentURICommand();
	// 	return $this->command->getURLName();
	// }
	
	public function setCommand(PFCommand $command) {
		$this->command = $command;
	}
	
	public function getLastCommandRun() {
		return $this->command;
	}

	// public function getLastAppCmdRun() {
	// 	return $this->getLastApplicationRun() . '.' . $this->getLastCommandURLNameRun();
	// }
	
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
}

?>