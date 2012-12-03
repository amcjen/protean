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
final class PFErrorHandler { 

	protected static $errorCount = 0;

	static public function errorHandler($errno, $errstr, $errfile, $errline) {
		if (error_reporting() != 0) {	
			self::$errorCount++;

			if (self::$errorCount > 3) {
				printr('RECURSIVE ERROR: ' . $errstr . ' (Error Number ' . $errno .') ' . $errfile . ':' . $errline);
				exit;
			}

			$e = new PFException('', $errstr, $errno);	
			$e->errorHandlerOverride($errno, $errstr, $errfile, $errline);

			if (PF_DEBUG_VERBOSE == true) {			
				$e->handleException();
			} else {
				$e->logException();
			}
		}
	}

	static public function exceptionHandler(Exception $e) {

		if (get_class($e) == 'PFException') {
			$e->setCode(E_USER_ERROR);
			$e->setMessage('', '[Uncaught Exception] - ' . $e->getMessage());
			$e->handleException();
		} else {
			// Reflection Exceptions throw a -1 error code, so let's set the message a little more
			// robust if this exception is a ReflectionException
			if (get_class($e) == 'ReflectionException') {
				$msg = 'Reflection Exception: ' . $e->getMessage(); 
			} else {
				$msg = $e->getMessage();
			}
			$pfe = new PFException('', $msg, $e->getCode());
			$pfe->handleException();
		}
	}
}

?>
