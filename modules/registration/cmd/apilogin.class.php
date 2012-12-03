<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2012, Eric Jennings.  All rights reserved.            *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

class PFAPILoginCommand extends PFRestCommand  {

  public function doExecute(PFRequest $request) {
    return parent::doExecute($request);
  }

  public function handleGet(PFRequest $request) {
		return $this->notImplemented();
	}

	public function handlePost(PFRequest $request) {
	  $username = $request->get('login-emailaddress');
    $password = $request->get('login-password');
    $persistent = $request->get('rememberlogin');
    $redirectUrl = $this->session->retrieve('pf.session_redirect_url');

    if (empty($username) || empty($password)) {
      return PFRestHelper::sendResponse(400, '{"status":"error","code":"missinginfo","message":"Please enter both an email address and a password"}', 'application/json');
    }
    $userHelper = PFFactory::getInstance()->createObject('registration.userhelper');

    try {
      $userHelper->login($username, $password, $persistent);
      return PFRestHelper::sendResponse(200, '{"status":"success","code":"loginsucceeded","message":"You are successfully logged in","data":{redirectUrl: ' + $redirectUrl + '}}', 'application/json');
    } catch (Exception $e) {
      return PFRestHelper::sendResponse(401, '{"status":"error","code":"loginfailed","message":"Login failed, please try again!"}', 'application/json');
    }
	}

	public function handlePut(PFRequest $request) {
		return $this->notImplemented();
	}

	public function handleDelete(PFRequest $request) {
		return $this->notImplemented();
	}
}
?>