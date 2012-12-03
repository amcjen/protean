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
class PFCacheMemcache implements PFCache { 

	static private $instance;
	static private $connection;

	static public function getInstance() {

		if(self::$instance == NULL) {

			self::init();

			ini_set('memcache.hash_strategy', 'consistent');
			ini_set('memcache.chunk_size', 32768);

			self::$instance = new PFCacheMemcache();
			self::$connection = new MemCache;
			self::$connection->pconnect(PF_CACHE_MEMCACHE_SERVER_HOST_1, 11211);
		}

		return self::$instance;
	}

	static public function init() {

		if (!extension_loaded('memcache')) {
			throw new PFException('api', array('PHP_EXTENSION_NOT_AVAILABLE', 'memcache'), E_USER_WARNING);
		}
	}

	static public function fetch($key) { 

		return unserialize(self::$connection->get(PF_SITE_NAME.$key));
	} 

	static public function store($key, &$data, $ttl=null) {

		return self::$connection->set(PF_SITE_NAME.$key, serialize($data), MEMCACHE_COMPRESSED, $ttl);
	} 

	static public function delete($key) { 

		return self::$connection->delete(PF_SITE_NAME.$key); 
	} 

	static public function addServer($host, $port=11211, $weight=10) {

		self::$connection->addServer($host, $port, true, $weight); 
	}

	static public function flush() {

		self::$connection->flush(); 
	}

	static public function setCompression($flag=true) {

		define('MEMCACHE_COMPRESSED', $flag);
	}

	static public function getStats($extended=true) {

		if ($extended == true) {
			return self::$connection->getExtendedStats();
		} else {
			return self::$connection->getStats();
		}
	}

}

?>