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
require_once 'modules/content/cmd/default.class.php';

class PFRestCommand extends PFDefaultCommand {

	public function doExecute(PFRequest $request) {	
		try {
			parent::doExecute($request);
			$this->response = PFRestHelper::processRequest($request);
			$method = 'handle' . $this->response->getMethod();
			return $this->$method($request);
		} catch (PFException $e) {
			$e->handleRestException();
		}
	}

	protected function notImplemented() {
		PFRestHelper::sendResponse(501, '{"status":"error","code":"notimplemented","message":"Not Implemented"}', 'application/json');
	}

	public function handleGet(PFRequest $request) {
		return $this->notImplemented();
	}

	public function handlePost(PFRequest $request) {
		return $this->notImplemented();
	}

	public function handlePut(PFRequest $request) {
		return $this->notImplemented();
	}

	public function handleDelete(PFRequest $request) {
		return $this->notImplemented();
	}
}
?>