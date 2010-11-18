<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/
	
require_once 'modules/thirdparty/phpmailer/class.phpmailer.php';

class PFMailer extends PHPMailer { 

	function __construct() {
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
		if (PF_EMAIL_REDIRECT != 'PF_EMAIL_REDIRECT' && PF_EMAIL_REDIRECT != '') {
			parent::clearAllRecipients();
			parent::addAddress(PF_EMAIL_REDIRECT);
			$this->Subject = "DEV: " . $this->Subject;
		}
		
		if (parent::send()) {		
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
	
	public function readHTMLFromURL($url) {
		try {	
			return file_get_contents($url);		
		} catch (Exception $e) {		
			PFException::handleVanillaException($e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
		}
	}
}

?>