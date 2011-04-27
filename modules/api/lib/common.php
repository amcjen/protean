<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2011, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

// set timezone for PHP 5.3 and greater
date_default_timezone_set(PF_TIMEZONE);

// force internal PHP encoding to be UTF-8
mb_internal_encoding('UTF-8');
setlocale(LC_CTYPE, 'C');
header('Content-Type: text/html; charset=UTF-8');

// set script timeout to our config setting
set_time_limit(PF_SCRIPT_TIMEOUT);

// set timeout for sessions
ini_set('session.gc_maxlifetime', PF_SESSION_EXPIRE);

// The built-in PHP5 reflection class defines this error code (-1).  we define it here.
define('E_PHP5_ERROR', -1);

// Unknown error, usually from an SQL Exception from Propel.  we define it here.
define('E_UNKNOWN_ERROR', 0);

// Added an invalid data error code.  We put it high so it won't interfere w/ PHP's built-in error codes
define('E_INSUFFICIENT_DATA', 65536);
define('E_USER_FATAL', 32767);
define('E_USER_LOG', 16383);

/*function __autoload($classname) {
$filename = str_replace ('_', '/', $classname) . '.php';
require_once $filename;
}*/

$ds = DIRECTORY_SEPARATOR;
$ps = PATH_SEPARATOR;

// load 3rd-party paths into "include_path". (If run from command line,
	// we're probably running unit tests, so manually determine the full path
	// from __FILE__.
	if (php_sapi_name() == 'cli') {
		$explodedPath = explode($ds . 'modules', __FILE__);
		$pfRoot = $explodedPath[0];
	} else {
		$pfRoot = $_SERVER['DOCUMENT_ROOT'];
	}

	// we save the root in a define, for use elsewhere
	define('PF_BASE', $pfRoot);

	ini_set('include_path', PF_BASE . $ps . ini_get('include_path'));

	if (PF_PROFILER) {
		require_once 'modules/api/lib/profiler.class.php';
		PFProfiler::getInstance()->start();
	}
	
	// require all interfaces here
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'cache.interface.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'command.interface.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'observable.interface.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'observer.interface.php';

	// base classes required all over the place
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'controllermap.class.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'exception.class.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'errorhandler.class.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'debugstack.class.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'factory.class.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'command.class.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'mailer.class.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'smstexter.class.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'restcommand.class.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'resourcecommand.class.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'template.class.php';
	require_once 'modules' . $ds . 'registration' . $ds . 'lib' . $ds . 'userhelper.class.php';

	// set error handling overrides
	$pf_handler = new PFErrorHandler();
	set_error_handler(array($pf_handler, 'errorHandler'), E_ALL);
	set_exception_handler(array($pf_handler, 'exceptionHandler'));
	ini_set('display_errors', false);
	ini_set('html_errors', false);

	// require all singletons here
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'language.class.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'session.class.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'registry.class.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'controller.class.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'applicationhelper.class.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'templatehelper.class.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'requesthelper.class.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'imagefile.class.php';
	require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'resthelper.class.php';
	require_once 'modules' . $ds . 'thirdparty' . $ds . 'patForms' . $ds . 'patForms' . $ds . 'Datasource' . $ds . 'Propel.php';

	if (PF_CMS_ENABLED == true) {
		require_once 'modules' . $ds . 'api' . $ds . 'lib' . $ds . 'cmshelper.class.php';
	}

	if (file_exists(PF_BASE . $ds . 'modules' . $ds . 'shop' . $ds . 'lib' . $ds . 'cart.class.php')) {
		require_once 'modules' . $ds . 'shop' . $ds . 'lib' . $ds . 'cart.class.php';
	}

	// load PEAR path
	if (PF_USE_LOCAL_PEAR == true) {
		$pf_inc = PF_BASE . $ds . 'modules' . $ds . 'thirdparty' . $ds . 'pear' . $ps;
	} else {
		$pf_inc = PF_PEAR_BASE . $ps;
	}

	// add any additional third-party library include paths below
	$pf_inc .= PF_BASE . $ds . 'modules' . $ds . 'thirdparty' . $ds . 'smarty' . $ps;
	$pf_inc .= PF_BASE . $ds . 'modules' . $ds . 'thirdparty' . $ds . 'patError' . $ps;
	$pf_inc .= PF_BASE . $ds . 'modules' . $ds . 'thirdparty' . $ds . 'patForms' . $ps;
	$pf_inc .= PF_BASE . $ds . 'modules' . $ds . 'thirdparty' . $ds . 'patForms' . $ds . 'patForms' . $ps;
	$pf_inc .= PF_BASE . $ds . 'modules' . $ds . 'thirdparty' . $ds . 'fpdf' . $ps;

	$pf_inc .= PF_BASE . $ds . 'modules' . $ds . 'db' . $ps;
	ini_set('include_path', $pf_inc . $ps . ini_get('include_path'));

	try {
		require_once 'propel' . $ds . 'Propel.php';
		require_once 'propel' . $ds . 'om' . $ds . 'BaseObject.php';

		if (file_exists(PF_BASE . $ds . 'modules' . $ds . 'db' . $ds . 'conf' . $ds . 'protean-conf.php')) {
			Propel::init(PF_BASE . $ds . 'modules' . $ds . 'db' . $ds . 'conf' . $ds . 'protean-conf.php');
		} else {
			throw new PFException('', 'Propel failed to load. Config file not found.', E_USER_ERROR);
		}

		// handle full query logging if this constant is set true
		if (PF_QUERY_DEBUG) {
			$config = Propel::getConfiguration(PropelConfiguration::TYPE_OBJECT);
			$config->setParameter('debugpdo.logging.details.method.enabled', true);
			$config->setParameter('debugpdo.logging.details.time.enabled', true);
			$config->setParameter('debugpdo.logging.details.mem.enabled', true);
		}

	} catch (PFException $e) {
		$e->handleException();
	} catch (Exception $e) {
		PFException::handleVanillaException($e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
	}

	// Memcache Support
	if (PF_CACHE_ENABLED) {

		try {
			PFFactory::getInstance()->initObject('api.cachememcache');

			if (PF_CACHE_MEMCACHE_SERVER_HOST_1 != false)
				PFCacheMemcache::getInstance()->addServer(PF_CACHE_MEMCACHE_SERVER_HOST_1);

			if (PF_CACHE_MEMCACHE_SERVER_HOST_2 != false)
				PFCacheMemcache::getInstance()->addServer(PF_CACHE_MEMCACHE_SERVER_HOST_2);

			if (PF_CACHE_MEMCACHE_SERVER_HOST_3 != false)
				PFCacheMemcache::getInstance()->addServer(PF_CACHE_MEMCACHE_SERVER_HOST_3);

			if (PF_CACHE_MEMCACHE_SERVER_HOST_4 != false)
				PFCacheMemcache::getInstance()->addServer(PF_CACHE_MEMCACHE_SERVER_HOST_4);

		} catch (PFException $e) {
			$e->HandleException();
		} catch (Exception $e) {
			PFException::HandleVanillaException($e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
		}
	}

	try {
		PFLanguage::getInstance();
		$request = PFFactory::getInstance()->createObject('api.request');

		$lang = $request->get('lang');
		$app = $request->get('app');

		if (isset($lang)) {
			PFLanguage::getInstance()->setCurrentLocale($lang);
		}

		if (!isset($app)) {
			$app = 'content';
		}
	} catch (PFException $e) {
		$e->handleException();
	}

	try {
		PFLanguage::getInstance()->loadTranslationTable('api', 'global');
		PFLanguage::getInstance()->loadTranslationTable($app, 'global');
	} catch (PFException $e) {
		$e->handleException();
	}

	function printr($arr, $buffered=false) {
		if (php_sapi_name() != 'cli') {
			echo '<pre>';
			$newLine = '<br />';
		} else {
			$newLine = "\n";
		}
		if (!empty($arr)) {
			print_r($arr, $buffered);
			echo $newLine;
		}
		if (php_sapi_name() != 'cli') {
			echo '</pre>';
		}
	}

	function printrBuffered($arr) {
		return (printr($arr, true));
	}

	// arrayToXML($array, $rootNodeName, $xml)
	//
	// Take a multi dimensional array and convert it to a simple XML string.
	// Not the most advanced method to do this, but works for fairly basic XML documents.
	// Also allows us to keep the code cleaner instead of having XML Strings everywhere.
	function arrayToXML($array, $rootNodeName='data', $xml=null){
		if (is_null($xml)){
			$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
		}

		// loop through the data passed in.
		foreach($array as $key => $value){
			// no numeric keys in our xml please!
			if (is_numeric($key)){
				$key = "unknownNode_". (string) $key;
			}

			// delete any char not allowed in XML element names
			$key = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key);

			// if there is another array found recrusively call this function
			if (is_array($value)){
				$node = $xml->addChild($key);
				// recrusive call.
				arrayToXML($value, $rootNodeName, $node);
			}
			else {
				// add single node.
				$value = htmlentities($value);
				$xml->addChild($key,$value);
			}
		}

		// pass back as string. or simple xml object if you want!
		return $xml->asXML();
	}


	?>
