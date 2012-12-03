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
class PFRestRequest { 

	private $requestVars;
	private $data;
	private $httpAccept;
	private $method;

	public function __construct() {
		$this->requestVars	= array();
		$this->data	= '';
		$this->httpAccept = (strpos(@$_SERVER['HTTP_ACCEPT'], 'json')) ? 'json' : 'xml';
		$this->method = 'get';
	}

	public function setData($data) {
		$this->data = $data;
	}

	public function setMethod($method) {
		$this->method = $method;
	}

	public function setRequestVars($requestVars) {
		$this->requestVars = $requestVars;
	}

	public function populateRequest(PFRequest $request) {
		foreach ($this->requestVars as $key => $val) {
			$request->set($key, $val);
		}
	}

	public function getData() {
		return $this->data;
	}

	public function getMethod() {
		return $this->method;
	}

	public function getHttpAccept() {
		return $this->httpAccept;
	}

	public function getRequestVars() {
		return $this->requestVars;
	}
}

?>