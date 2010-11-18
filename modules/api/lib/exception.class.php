<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

require_once 'modules/api/lib/errorstack.class.php';

class PFException extends Exception {
	protected $errortype;
	protected $verbose_errortype;
	protected $timestamp;
	protected $xml_error;

	const ERR_FILE_NOT_READABLE		= 'File is not readable';
	const ERR_TEMPLATE_ERROR			= 'Template error';

	public function __construct($app, $message, $code = 1) {
		$this->setMessage($app, $message);
		$this->code = $code;
		$this->timestamp = time();
		$this->setErrorTypes($app);

		parent::__construct($this->message, $code);
	}

	public static function enrich(Exception $e) {	
		if ($e->getCode() == 0) {
			$code = E_USER_ERROR;
		} else {
			$code = $e->getCode();
		}

		return new PFException('', $e->getMessage(), $code);		
	}

	public function getXMLError() {
		$code = $this->getCode();

		$err = "<errorentry>\r\n";
		$err .= "\t<datetime>" . date('Y-m-d H:i:s (T)') . "</datetime>\r\n";
		$err .= "\t<timestamp>" . $this->timestamp . "</timestamp>\r\n";
		$err .= "\t<errornum>" . $code . "</errornum>\r\n";
		$err .= "\t<errortype>" . $this->verbose_errortype[$code] . "</errortype>\r\n";
		$err .= "\t<errormsg>" . $this->getMessage() . "</errormsg>\r\n";
		$err .= "\t<scriptname>" . $this->getFile() . "</scriptname>\r\n";
		$err .= "\t<scriptlinenum>" . $this->getLine() . "</scriptlinenum>\r\n";

		$stack_array = explode("\n\r", $this->getTraceAsString());
		$count = count($stack_array);

		$err .= "\t<stacktrace>\n\r";

		for ($i=0; $i<$count; $i++) 
			$err .= "\t\t<traceitem>" . $stack_array[$i] . "</traceitem>\n\r";

		$err .= "\t</stacktrace>\n\r";
		$err .= "</errorentry>\n\r\n\r";

		$this->xml_error = $err;

		return $this->xml_error;
	}

	public function getSlimError() {
		return '[' . date('D M j H:i:s Y') . '] [' . 
			$this->errortype[$this->getCode()] . '] [client ' . 
			@$_SERVER['REMOTE_ADDR'] . '] [URI ' . 
			@$_SERVER['REQUEST_URI'] . '] ' .
			$this->getMessage() . ' at ' . 
			$this->getFile() . ':' . 
			$this->getLine() . "\n";
	}

	public function getErrorType() {
		if (PF_DEBUG_VERBOSE == true) {
			return $this->verbose_errortype[$this->getCode()];
		} else {
			return $this->errortype[$this->getCode()];
		}
	}

	public function getTimestamp() {
		return $this->timestamp;
	}

	public function setCode($code) {
		$this->code = $code;
	}

	public function setMessage($app, $message) {
		if ($app == '') {
			if (is_array($message)) {
				$this->message = sprintf($message[0], $message[1]);
			} else {
				$this->message = $message;
			}
		} else {
			if (is_array($message)) {
				$this->message = sprintf(PFLanguage::getInstance()->getTranslation($app, $message[0]), $message[1]);
			} else {
				$this->message = PFLanguage::getInstance()->getTranslation($app, $message);
			}
		}
	}

	protected function setErrorTypes($app) {
		if ($app == '') {
			$this->errortype = array (
				E_PHP5_ERROR 				=> 'Error',
				E_UNKNOWN_ERROR			=> 'Error',
				E_INSUFFICIENT_DATA	=> 'Warning',
				E_ERROR           	=> 'Error',
				E_WARNING         	=> 'Warning',
				E_PARSE           	=> 'Error',
				E_NOTICE          	=> 'Warning',
				E_CORE_ERROR      	=> 'Error',
				E_CORE_WARNING    	=> 'Warning',
				E_COMPILE_ERROR   	=> 'Error',
				E_COMPILE_WARNING 	=> 'Warning',
				E_USER_FATAL	     	=> 'Fatal',
				E_USER_ERROR      	=> 'Error',
				E_USER_WARNING    	=> 'Warning',
				E_USER_NOTICE     	=> 'Notice',
				E_STRICT          	=> 'Notice'
				);

			$this->verbose_errortype = array (
				E_PHP5_ERROR	 			=> 'Error',
				E_UNKNOWN_ERROR			=> 'Unknown Error',
				E_INSUFFICIENT_DATA	=> 'Warning',
				E_ERROR           	=> 'Error',
				E_WARNING         	=> 'Warning',
				E_PARSE           	=> 'Parsing Error',
				E_NOTICE          	=> 'Notice',
				E_CORE_ERROR      	=> 'Core Error',
				E_CORE_WARNING    	=> 'Core Warning',
				E_COMPILE_ERROR   	=> 'Compile Error',
				E_COMPILE_WARNING 	=> 'Compile Warning',
				E_USER_FATAL	     	=> 'User Fatal',
				E_USER_ERROR      	=> 'User Error',
				E_USER_WARNING    	=> 'User Warning',
				E_USER_NOTICE     	=> 'User Notice',
				E_STRICT          	=> 'Runtime Notice'
				);
		} else {
			$this->errortype = array (
				E_PHP5_ERROR 				=> PFLanguage::GetInstance()->GetTranslation('api','ERROR'),
				E_UNKNOWN_ERROR 		=> PFLanguage::GetInstance()->GetTranslation('api','ERROR'),
				E_INSUFFICIENT_DATA	=> PFLanguage::GetInstance()->GetTranslation('api','INSUFFICIENT_DATA'),
				E_ERROR           	=> PFLanguage::GetInstance()->GetTranslation('api','ERROR'),
				E_WARNING         	=> PFLanguage::GetInstance()->GetTranslation('api','WARNING'),
				E_PARSE           	=> PFLanguage::GetInstance()->GetTranslation('api','ERROR'),
				E_NOTICE          	=> PFLanguage::GetInstance()->GetTranslation('api','WARNING'),
				E_CORE_ERROR      	=> PFLanguage::GetInstance()->GetTranslation('api','ERROR'),
				E_CORE_WARNING    	=> PFLanguage::GetInstance()->GetTranslation('api','WARNING'),
				E_COMPILE_ERROR   	=> PFLanguage::GetInstance()->GetTranslation('api','ERROR'),
				E_COMPILE_WARNING 	=> PFLanguage::GetInstance()->GetTranslation('api','WARNING'),
				E_USER_FATAL      	=> PFLanguage::GetInstance()->GetTranslation('api','FATAL'),
				E_USER_ERROR      	=> PFLanguage::GetInstance()->GetTranslation('api','ERROR'),
				E_USER_WARNING    	=> PFLanguage::GetInstance()->GetTranslation('api','WARNING'),
				E_USER_NOTICE     	=> PFLanguage::GetInstance()->GetTranslation('api','NOTICE'),
				E_STRICT          	=> PFLanguage::GetInstance()->GetTranslation('api','NOTICE')
				);

			$this->verbose_errortype = array (
				E_PHP5_ERROR	 			=> PFLanguage::GetInstance()->GetTranslation('api','ERROR'),
				E_UNKNOWN_ERROR 		=> PFLanguage::GetInstance()->GetTranslation('api','UNKNOWN_ERROR'),
				E_INSUFFICIENT_DATA	=> PFLanguage::GetInstance()->GetTranslation('api','INSUFFICIENT_DATA'),
				E_ERROR           	=> PFLanguage::GetInstance()->GetTranslation('api','ERROR'),
				E_WARNING         	=> PFLanguage::GetInstance()->GetTranslation('api','WARNING'),
				E_PARSE           	=> PFLanguage::GetInstance()->GetTranslation('api','PARSING_ERROR'),
				E_NOTICE          	=> PFLanguage::GetInstance()->GetTranslation('api','NOTICE'),
				E_CORE_ERROR      	=> PFLanguage::GetInstance()->GetTranslation('api','CORE_ERROR'),
				E_CORE_WARNING    	=> PFLanguage::GetInstance()->GetTranslation('api','CORE_WARNING'),
				E_COMPILE_ERROR   	=> PFLanguage::GetInstance()->GetTranslation('api','COMPILE_ERROR'),
				E_COMPILE_WARNING 	=> PFLanguage::GetInstance()->GetTranslation('api','COMPILE_WARNING'),
				E_USER_FATAL      	=> PFLanguage::GetInstance()->GetTranslation('api','USER_FATAL'),
				E_USER_ERROR      	=> PFLanguage::GetInstance()->GetTranslation('api','USER_ERROR'),
				E_USER_WARNING    	=> PFLanguage::GetInstance()->GetTranslation('api','USER_WARNING'),
				E_USER_NOTICE     	=> PFLanguage::GetInstance()->GetTranslation('api','USER_NOTICE'),
				E_STRICT          	=> PFLanguage::GetInstance()->GetTranslation('api','RUNTIME_NOTICE')
				);
		}
	}

	public function errorHandlerOverride($errno, $errmsg, $filename, $linenum) {	
		$this->code = $errno;
		$this->message = $errmsg;
		$this->file = $filename;
		$this->line = $linenum;	
	}	

	public function __toString() {	
		return $this->getFormattedMessage();
	}

	public function getFormattedMessage() {
		if (PF_DEBUG_VERBOSE == true && $this->getCode() != E_INSUFFICIENT_DATA) {

			$file = explode('htdocs', $this->getFile());
			if (count($file) > 1) {
				$file = $file[1];
			}
			if (PF_ERROR_WORDWRAP_COUNT > 0) {
				return wordwrap($this->getErrorType() . ': ' . $this->message, PF_ERROR_WORDWRAP_COUNT, "<br />\n") . 
					"<br />\n" . '(' . $file . ':' . $this->getLine() . ')';
			} else {
				return $this->getErrorType() . ': ' . $this->message . "<br />\n" . '(' . $file . ':' . $this->getLine() . ')';
			}
		} else {
			return $this->message;
		}
	}

	public function handleException() {
		$code = $this->getCode();
		$this->logException();

		if ($code == E_USER_ERROR || $code == E_ERROR || $code == E_CORE_ERROR || $code == E_COMPILE_ERROR) {
			if (PF_DEBUG_EMAIL == true) {
				ob_start();
				debug_print_backtrace();
				echo "SERVER VARIABLES:\n";
				print_r($_SERVER);
				if (isset($_SESSION)) {
					echo "SESSION VARIABLES:\n";
					print_r($_SESSION);
				}
				echo "REQUEST VARIABLES:\n";
				print_r($_REQUEST);
				$globalVars = ob_get_contents();
				ob_end_clean();

				mail(PF_DEBUG_EMAIL_ADDRESS, PF_SITE_NAME . ': Critical Application Error', $this->getSlimError() . "\n\n" . $globalVars);
			}
		}

		if ($code == E_USER_FATAL) {
			printr($this->getSlimError());
			ob_start();
			debug_print_backtrace();
			$trace = ob_get_contents();
			ob_end_clean();
			printr($trace);

			exit;
		}

		if ($code == E_USER_ERROR || $code == E_ERROR || $code == E_CORE_ERROR || $code == E_COMPILE_ERROR || $code == E_PHP5_ERROR) {	
			$this->displayException();
		} else {
			PFErrorstack::Append($this);
		}
	}

	static public function handleVanillaException($message, $code, $file='', $line='') {
		if ($code == 0) {
			$code = E_USER_ERROR;
		}

		$e = new PFException('', $message, $code, false);

		if ($file != '')
			$e->file = $file;

		if ($line != '')
			$e->line = $line;

		$e->logException();

		if ($code == E_USER_ERROR || $code == E_ERROR || $code == E_CORE_ERROR || $code == E_COMPILE_ERROR) {
			if (PF_DEBUG_EMAIL == true) {
				ob_start();
				debug_print_backtrace();
				print_r($_SERVER);
				print_r($_SESSION);
				print_r($_REQUEST);
				$globalVars = ob_get_contents();
				ob_end_clean();
				mail(PF_DEBUG_EMAIL_ADDRESS, PF_SITE_NAME . ': Critical Application Error', $e->getSlimError() . "\n\n" . $globalVars);
			}
		}

		if ($code == E_USER_FATAL) {
			printr($e->getSlimError());
			ob_start();
			debug_print_backtrace();
			$trace = ob_get_contents();
			ob_end_clean();
			printr($trace);

			exit;
		}

		$cmd = PFFactory::createCommandObject(PF_DEFAULT_COMMAND);
		$request = PFFactory::getInstance()->createObject('api.request');
		$cmd->assignDefaults($request);
		$cmd->assignHeaderPaths(PFRegistry::getInstance()->getPage());

		if ($code == E_USER_ERROR || $code == E_ERROR || $code == E_CORE_ERROR || $code == E_COMPILE_ERROR || $code == E_PHP5_ERROR) {	
			$e->displayException();
		} else {
			PFErrorstack::append($e);
		}
	}

	public function handleRestException() {
		PFRestHelper::sendResponse(500, $this->getSlimError());
		$this->logException();
	}

	protected function displayException() {
		try {	
			list($defaultApp, $defaultCmd) = explode('.', PF_DEFAULT_COMMAND);

			$page = PFFactory::getInstance()->createObject('api.template', $defaultApp);
			$page->assign('ERROR', $this->__toString());
			$page->assign('STACK', $this->xml_error);

			$cmd = PFFactory::createCommandObject(PF_DEFAULT_COMMAND);
			$request = PFFactory::getInstance()->createObject('api.request');
			$cmd->assignDefaults($request);
			$cmd->assignHeaderPaths($page);
			PFTemplateHelper::getInstance()->assignDefaults($page);

			if (PF_PROFILER) {
				$page->assign('PF_PROFILE_TIME', PFProfiler::getInstance()->getTime());
			}
			$page->display($defaultApp, 'error.tpl');
		} catch (PFException $e) {

			echo $e;
			echo '<br /><pre>';
			echo $e->getXMLError();
			echo '</pre>';
		}
		exit;
	}

	public function logException() {
		if (is_writable(PF_DEBUG_LOG) && touch(PF_DEBUG_LOG)) {
			error_log($this->getSlimError(), 3, PF_DEBUG_LOG);
		} 
	}
}

class PFValidationException extends Exception {
	protected $ormObject;

	public function __construct($object) {
		$this->ormObject = $object;
	}

	public function handleException() {
		$string = '';
		foreach ($this->ormObject->getValidationFailures() as $failure) {
			$string .= $failure->getMessage() . "\n";
		}

		return $string;
	}

	public function handleRestException() {
		PFRestHelper::sendResponse(400, nl2br($this->handleException()));
	}
}
?>