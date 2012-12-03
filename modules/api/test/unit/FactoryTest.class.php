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
require_once 'modules/api/lib/factory.class.php';
require_once 'modules/api/lib/request.class.php';
require_once 'modules/content/cmd/default.class.php';

class FactoryTest extends PHPUnit_Framework_TestCase {

	private $factory;
	
	public function setUp() {
	
	}
	
	public function tearDown() {
	
	}
	
	public function testCreateObject() {
	
		$req = PFFactory::getInstance()->createObject('api.request');
		$req2 = new PFRequest();
		
		$this->assertEquals($req2, $req);
	}
	
	public function testCreateExistingObject() {
	
		$req = PFFactory::getInstance()->createObject('api.request');
		$req2 = PFFactory::getInstance()->createObject('api.request');
		
		$this->assertEquals($req2, $req);
	}
	
	public function testCreateCommandObject() {
	
		$cmd = PFFactory::getInstance()->createCommandObject('content.default');
		$cmd2 = new PFDefaultCommand();
		
		$this->assertEquals($cmd2, $cmd);
	}
}

?>