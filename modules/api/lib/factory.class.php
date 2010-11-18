<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

final class PFFactory {

	static private $instance;

	private function __construct() { }

	static public function getInstance() {	
		if(self::$instance == NULL) {
			self::$instance = new PFFactory();
		}

		return self::$instance;
	}

	public function initObject ($class) {
		try {			
			list ($appname, $classname) = explode('.', $class);

			if ($appname != 'thirdparty') {	
				$filename = PF_BASE . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $appname . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . $classname . '.class.php';	

				if (!is_readable($filename)) {
					throw new PFException('', PFException::ERR_FILE_NOT_READABLE . ': ' . $filename . '.', E_USER_ERROR);
				}

				require_once($filename);
			}	
		} catch (PFException $e) {		
			$e->handleException();
		}
	}

	public function createObject ($class,
		$p1='_UNDEF_',$p2='_UNDEF_',$p3='_UNDEF_',$p4='_UNDEF_',
		$p5='_UNDEF_',$p6='_UNDEF_',$p7='_UNDEF_',$p8='_UNDEF_',
		$p9='_UNDEF_',$p10='_UNDEF_',$p11='_UNDEF_',$p12='_UNDEF_',
	$p13='_UNDEF_',$p14='_UNDEF_',$p15='_UNDEF_',$p16='_UNDEF_') {

		try {		
			list ($appname, $classname) = explode('.', $class);

			// load class file if not in third-party directory (since we already added those to the
				// include_path earlier)
				if ($appname != 'thirdparty') {	
					$filename = PF_BASE . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $appname . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . $classname . '.class.php';	

					if (!is_readable($filename)) {
						throw new PFException('', PFException::ERR_FILE_NOT_READABLE . ': ' . $filename . '.', E_USER_FATAL);
					}

					require_once($filename);
				}

				if ($appname == 'api') {
					$classname = 'PF' . ucfirst($classname);
				} else {
					$classname = 'PF' . ucfirst($appname) . ucfirst($classname);
				}

				if (is_string($p1) && $p1 == '_UNDEF_') {
					$obj = new $classname;
				}
				else {				
					$input = array($p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11,$p12,$p13,$p14,$p15,$p16);
					$i = 1;
					$code = '$obj = new ' . $classname . '(';

					foreach ($input as $test) {			
						if ((is_string($test) && $test == '_UNDEF_') || $i == 17) {
							break;
						}
						else {
							$code .= '$p' . $i . ',';
						}
						$i++;
					}

					$code = substr($code,0,-1) . ');';
					eval($code);
				}
			} catch (PFException $e) {		
				$e->handleException();
				$obj = false;
			}		
			return $obj;
		}

		public function initCommandObject ($class) {
			try {		
				list ($appname, $classname) = explode('.', $class);

				if ($appname != 'thirdparty') {		
					$filename = PF_BASE . '/modules/' . $appname . '/cmd/' . $classname . '.class.php';	

					if (!is_readable($filename)) {
						throw new PFException('', PFException::ERR_FILE_NOT_READABLE . ': ' . $filename . '.', E_USER_FATAL);
					}

					require_once($filename);
				}

			} catch (PFException $e) {			
				$e->handleException();
			}
		}

		public function createCommandObject ($class,
			$p1='_UNDEF_',$p2='_UNDEF_',$p3='_UNDEF_',$p4='_UNDEF_',
			$p5='_UNDEF_',$p6='_UNDEF_',$p7='_UNDEF_',$p8='_UNDEF_',
			$p9='_UNDEF_',$p10='_UNDEF_',$p11='_UNDEF_',$p12='_UNDEF_',
		$p13='_UNDEF_',$p14='_UNDEF_',$p15='_UNDEF_',$p16='_UNDEF_') {

			try {	
				list ($appname, $classname) = explode('.', $class);

				if ($appname != 'thirdparty') {
					$filename = PF_BASE . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $appname . DIRECTORY_SEPARATOR . 'cmd' . DIRECTORY_SEPARATOR . $classname . '.class.php';

					if (!is_readable($filename)) {
						throw new PFException('', PFException::ERR_FILE_NOT_READABLE . ': ' . $filename . '.', E_USER_FATAL);
					}			
					require_once($filename);
				}

				$classname = 'PF' . ucfirst($classname) . 'Command';

				if($p1 == '_UNDEF_' && $p1 != 1) {
					$obj = new $classname;
				}
				else {		
					$input = array($p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11,$p12,$p13,$p14,$p15,$p16);
					$i = 1;
					$code = '$obj = new ' . $classname . '(';

					foreach ($input as $test) {	
						if (($test == '_UNDEF_' && $test != 1 ) || $i == 17) {
							break;
						}
						else {
							$code .= '$p' . $i . ',';
						}
						$i++;
					}

					$code = substr($code,0,-1) . ');';
					eval($code);
				}
			} catch (PFException $e) {

				$e->handleException();
				$obj = false;
			}

			return $obj;
		}

		public function createPropelObject ($class) {
			try {		
				$filename = PF_BASE . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . $class . '.php';
				$peerfilename = PF_BASE . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . $class . 'Peer.php';

				if (!is_readable($filename)) {
					throw new PFException('', PFException::ERR_FILE_NOT_READABLE . ': ' . $filename . '.', E_USER_ERROR);
				}

				require_once($filename);
				require_once($peerfilename);

				$obj = new $class;			
			} catch (PFException $e) {			
				$e->handleException();
				$obj = false;
			}

			return $obj;
		}
	}

	?>