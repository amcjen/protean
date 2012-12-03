<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2012, Eric Jennings.  All rights reserved.            *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

require_once 'config.php';

class UserTest extends PHPUnit_Framework_TestCase {

	protected $user;
	
	public function setUp() { 
		$this->user = PFFactory::getInstance()->createObject('registration.userhelper');
	}

	public function tearDown() { }

	public function testSuccessfulLogin() {	
		$this->assertTrue($this->user->login('ethomjen@gmail.com', '123'));
	}
}
?>