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
require_once 'modules/thirdparty/phpmailer/class.phpmailer.php';

class PFMailer extends PHPMailer { 

	protected $debug;
	
	function __construct() {
	
		if (PF_EMAIL_DEBUG) {
			$this->debug = true;
		} else {
			$this->debug = false;
		}
		
		if (PF_EMAIL_USE_SMTP) {
			$this->isSMTP();
			$this->SMTPAuth = true;
			$this->SMTPDebug = false;
		} else {
			$this->isSendmail();
		}
		$this->WordWrap = 80;
		
		$this->From     = PF_DEFAULT_FROM_EMAIL_ADDRESS;
		$this->FromName = PF_DEFAULT_FROM_EMAIL_NAME;
		$this->Host     = PF_EMAIL_SERVER;
		$this->Username = PF_EMAIL_SERVER_USERNAME;
		$this->Password = PF_EMAIL_SERVER_PASSWORD;
	}
	
	function error_handler($msg) {
		$e = new PFException('api', array(ERROR_SENDING_EMAIL, $msg), E_USER_ERROR);
		$e->logException();
		
		throw $e;
	}
	
	public function send() {
	
		if ($this->debug) {
			$this->logDebug("Attempting to send message:\n");
		}
		
		// override the email addresses if it's for dev, PF_EMAIL_REDIRECT
		// if (PF_EMAIL_REDIRECT != 'PF_EMAIL_REDIRECT' && PF_EMAIL_REDIRECT != '') {
		// 		parent::clearAllRecipients();
		// 		parent::addAddress(PF_EMAIL_REDIRECT);
		// 		$this->Subject = "DEV: " . $this->Subject;
		// 	}
		
		if (parent::send()) {
			if ($this->debug) {
				$this->logDebug("Successfully sent message:\n");
			}
				
			return true;
		}
		
		$this->logError();
		throw new PFException('api', 'ERROR_SENDING_EMAIL', E_USER_WARNING);
	}
	
	public function logError() {
		if ($this->isError()) {
			$e = new PFException('', $this->ErrorInfo, E_USER_WARNING);
			$e->logException();
		}
		
		if ($this->Mailer == 'smtp' && count($this->smtp->error) > 0) {
			$e = new PFException('', printRBuffered($this->smtp->error), E_USER_WARNING);
			$e->logException();
		}
	}
	
	public function logDebug($msg) {
		
		if (@touch(PF_EMAIL_DEBUG_LOG) && is_writable(PF_EMAIL_DEBUG_LOG)) {
			
			$message = '[' . date('D M j H:i:s Y') . '] ' . $msg;
			$message .= $this->createHeader();
			$message .= $this->createBody();
			$message .= "--END--\n\n";
			
			error_log($message, 3, PF_EMAIL_DEBUG_LOG);
		}
	}
	
	public function readHTMLFromURL($url) {
		try {	
			return file_get_contents($url);		
		} catch (Exception $e) {		
			PFException::handleVanillaException($e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
		}
	}
}

?>