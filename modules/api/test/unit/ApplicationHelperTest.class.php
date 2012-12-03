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
require_once 'modules/api/lib/applicationhelper.class.php';
require_once 'modules/api/lib/registry.class.php';

class ApplicationHelperTest extends PHPUnit_Framework_TestCase {

	private $appHelper;
	
	public function setUp() {
		$this->appHelper = PFApplicationHelper::getInstance();
		$this->appHelper->init(PF_BASE . '/modules/api/test/fake/command.xml');
	}
	
	public function tearDown() {
	
	}
	
	public function testControllerMapCreation() {
	
		$map = PFRegistry::getInstance()->getControllerMap();
		
		$mapClass = new ReflectionClass('PFControllerMap');
		$this->assertTrue($mapClass->isInstance($map), 'Controller map not instantiated correctly.');
	}
	
	public function testAppControllerCreation() {
	
		$appController = $this->appHelper->appController();
 
		$appControllerClass = new ReflectionClass('PFApplicationController');
		$this->assertTrue($appControllerClass->isInstance($appController), 'Application controller not instantiated correctly.');
	}
}

?>
