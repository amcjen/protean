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

class PFDebugStack implements PFObservable { 

	protected static $instance;
	private $debugstack;
	private $observers;

	private function __construct() {
		$this->debugstack = array();
		$this->observers = array();
	}

	static public function getInstance() {
		if (self::$instance == NULL) {
			self::$instance = new PFDebugStack();
		}

		return self::$instance;
	}

	public function attach(PFObserver $observer) {	
		$this->observers[] = $observer;
	}

	public function detach(PFObserver $observer) {	
		$this->observers = array_diff($this->observers, array($observer));
	}

	public function notify() {
		foreach ($this->observers as $obs) {
			$obs->update($this);
		}
	}

	public static function append($message, $file, $line) {	
		$debugStack = PFDebugStack::getInstance();
		if (is_array($message)) {
			$message = printRBuffered($message);
		}
		$debugStack->appendToDebugstack($message, $file, $line);

	}

	public function appendToDebugstack($message, $file, $line) {
		$this->debugstack[] = array('message' => $message, 'file' => $file, 'line' => $line);
		$this->notify();
	}

	public function getDebugStack() {
		return $this->debugstack;
	}

	public function clearDebugStack() {
		$this->debugstack = array();
	}
}

?>
