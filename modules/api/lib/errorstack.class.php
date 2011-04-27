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
final class PFErrorStack { 

	static private $errorstack;

	public function __construct() {
		self::$errorstack = array();
	}

	static public function append(Exception &$e) {
		self::$errorstack[] = $e;
	}

	static public function getErrorStack() {
		return self::$errorstack;
	}

	static public function getErrorStackAsFormattedString() {
		$str = '';

		if (!self::$errorstack) {
			self::$errorstack = array();
		}

		foreach (self::$errorstack as $error) {
			$errorString = $error->getFormattedMessage();

			if ($errorString=='' || !$errorString) {
				return false;
			} elseif ($error->getCode() == E_USER_NOTICE) {
				$str .= '<span class="notice">' . $errorString . '</span><br />' . "\n";
			} else {
				$str .= '<span class="error">' . $errorString . '</span><br />' . "\n";
			}

		}
		return $str;
	}

	static public function clearErrorStack() {
		self::$errorstack = array();
	}
}

?>