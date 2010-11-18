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
require_once 'modules/api/lib/registry.class.php';

class RegistryTest extends PHPUnit_Framework_TestCase {

	private $registry;
	
	public function setUp() {
		$this->registry = PFRegistry::getInstance();
	}
	
	public function tearDown() {
	
	}
	
	public function testSetGet() {
	
		$this->registry->set('val', 123);
		$var = $this->registry->get('val');
		
		$this->assertEquals(123, $var);
	}
	
	public function testIsValueSet() { 
	
		$this->registry->set('val', 123);
		
		$this->assertTrue($this->registry->isValueSet('val'));
	}
	
	public function testSetControllerMap() { 
	
		$map = PFFactory::getInstance()->createObject('api.controllermap');
		
		$this->registry->setControllerMap($map);
		$newMap = PFRegistry::getControllerMap();
		
		$this->assertSame($map, $newMap);
	}
	
	public function testSetPage() { 
	
		$page = PFFactory::getInstance()->createObject('api.template', 'api');
		
		$this->registry->setPage($page);
		$newPage = PFRegistry::getPage();
		
		$this->assertSame($page, $newPage);
	}
}

?>