<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

require_once 'config.php';
require_once 'PHPUnit/Framework.php';
require_once 'modules/api/lib/request.class.php';

class RequestTest extends PHPUnit_Framework_TestCase {

	private $request;
	
	public function setUp() {
	
		$this->request = PFFactory::getInstance()->createObject('api.request');
		$this->request->init();
	}
	
	public function tearDown() {
	
	}
	
	public function testGetProperty() {
	
		$this->request->setProperty('var1', 'test');
		$var = $this->request->getProperty('var1');
		
		$this->assertEquals('test', $var);
	}
}

?>