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
class PFCacheAPC implements PFCache { 

	static private $instance;

	private function __construct() {
		self::init();
	}

	static public function getInstance() {

		if(self::$instance == NULL) {
			self::$instance = new PFCacheAPC();
		}

		return self::$instance;
	}

	static public function init() {

		if (!extension_loaded('apc')) {
			throw new PFException('api', array('PHP_EXTENSION_NOT_AVAILABLE', 'apc'), E_USER_WARNING);
		}
	}

	static public function fetch($key) { 

		return unserialize(apc_fetch($key)); 
	} 

	static public function store($key, &$data, $ttl=null) { 

		if (!isset($ttl)) {
			$ttl = PF_CACHE_USER_TTL;
		}

		return apc_store($key, serialize($data), $ttl); 
	} 

	static public function delete($key) { 

		return apc_delete($key); 
	} 
}

?>