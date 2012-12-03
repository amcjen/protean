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
require_once 'modules/thirdparty/twilio/twilio.php';
		
class PFSMSTexter { 

	protected $debug;
	protected $fromNumber;
	protected $twilioClientId;
	protected $twilioAuthToken;
	
	function __construct() {
	
		if (PF_SMS_DEBUG) {
			$this->debug = true;
		} else {
			$this->debug = false;
		}
		
		$this->fromNumber     	= PF_SMS_FROM_NUMBER;
		$this->twilioClientId 	= PF_SMS_TWILIO_CLIENT_ID;
		$this->twilioAuthToken  = PF_SMS_TWILIO_AUTH_TOKEN;
	}
	
	public function send($number, $message) {
	
		if ($this->debug) {
			$this->logDebug("Attempting to send SMS message:\n");
		}	

		$apiVersion = '2010-04-01';
		$client = new TwilioRestClient($this->twilioClientId, $this->twilioAuthToken);
		$response = $client->request('/' . $apiVersion . '/Accounts/' . $this->twilioClientId . '/SMS/Messages', 
								'POST', array(
									'To' => $number,
									'From' => $this->fromNumber,
									'Body' => substr(stripslashes($message), 0, 160)
									));
			
		if ($this->debug) {
			if ($response->IsError) {
				$this->logDebug('Error -- ' . $response->ErrorMessage);
				$this->logError($response->ErrorMessage);
				return false;
			} else {
				$this->logDebug('Successfully sent the following SMS message to ' . $number . ' -- ' . $message);
			}
		}
		
		return true;
	}
	
	protected function logError($message) {	
		$e = new PFException('', $message, E_USER_WARNING);
		$e->logException();
	}
	
	protected function logDebug($message) {
		
		if (@touch(PF_SMS_LOG) && is_writable(PF_SMS_LOG)) {
			$message = '[' . date('Y-m-d H:i:s') . '] Twilio SMS: ' . $message . "\n";
			error_log($message, 3, PF_SMS_LOG);
		}
	}
}

?>