<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

final class PFRegistry {

	static private $instance;
	private $data;
	private $map;
	private $page;
	
	private function __construct() {
		$this->data = array();
		$this->map = NULL;
		$this->page = NULL;
	}
	
	static public function getInstance() {
		if(self::$instance == NULL) {
			self::$instance = new PFRegistry();
		}

		return self::$instance;
	}
	
	public function init() {
		if (count($this->configData) > 0) {		
			return;
		}
		
		return $this->getConfiguration();
	}
	
	private function getConfiguration() {
		return;
	}
	
	public function set($key, $value) {
		if (!isset($this->data[$key])) {
			$this->data[$key] = $value;
			return true;
		}
		
		return false;	
	}
	
	public function get($key) {
		if (isset($this->data[$key])) {	
			return $this->data[$key];
		} else {
			return false;
		}
	}
	
	public function isValueSet($key) {	
		if (isset($this->data[$key])) {
			return true;
		} else {
			return false;
		}
	}
	
	public function __isset($key) {
		return $this->isValueSet($key);
	}
	
	public function setControllerMap($map) {	
		$this->map = $map;
	}
	
	static public function getControllerMap() {
		return self::$instance->map;
	}
	
	public function setPage($page) {
		$this->page = $page;
	}
	
	static public function getPage() {
		return self::$instance->page;
	}
}

?>