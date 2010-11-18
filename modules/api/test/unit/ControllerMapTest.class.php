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
require_once 'modules/api/lib/controllermap.class.php';

class ControllerMapTest extends PHPUnit_Framework_TestCase {

	private $map;
	
	public function setUp() {
	
		$this->map = PFFactory::getInstance()->createObject('api.controllermap');
	}
	
	public function tearDown() {
	
	}
	
	public function testGetClassRoot() {
	
		$this->map->addClassroot('go', 'rootcmd');
		$var = $this->map->getClassroot('go');
		
		$this->assertEquals('rootcmd', $var);
		
		$var = $this->map->getClassroot('go2');
		
		$this->assertEquals('go2', $var);
	}
	
	public function testGetView() {
	
		$this->map->addView('content.default', 0, 'index.tpl');
		$this->map->addView('content.default', 1, 'main.tpl');
		$this->map->addView('api.go', 1, 'nav.tpl');
		$this->map->addView('api.go', 0, 'other.tpl');
		
		$var = $this->map->getView('content.default', 0);
		$this->assertEquals('index.tpl', $var);
		
		$var = $this->map->getView('content.default', 1);
		$this->assertEquals('main.tpl', $var);
		
		$var = $this->map->getView('api.go', 1);
		$this->assertEquals('nav.tpl', $var);
		
		$var = $this->map->getView('api.go', 0);
		$this->assertEquals('other.tpl', $var);
	}
	
	public function testGetViewHeader() {
	
		$this->map->addViewHeader('content.default', 0, 'index.tpl');
		$this->map->addViewHeader('content.default', 1, 'main.tpl');
		$this->map->addViewHeader('api.go', 1, 'nav.tpl');
		$this->map->addViewHeader('api.go', 0, 'other.tpl');
		
		$var = $this->map->getViewHeader('content.default', 0);
		$this->assertEquals('index.tpl', $var);
		
		$var = $this->map->getViewHeader('content.default', 1);
		$this->assertEquals('main.tpl', $var);
		
		$var = $this->map->getViewHeader('api.go', 1);
		$this->assertEquals('nav.tpl', $var);
		
		$var = $this->map->getViewHeader('api.go', 0);
		$this->assertEquals('other.tpl', $var);
	}
	
	public function testGetViewFooter() {
	
		$this->map->addViewFooter('content.default', 0, 'index.tpl');
		$this->map->addViewFooter('content.default', 1, 'main.tpl');
		$this->map->addViewFooter('api.go', 1, 'nav.tpl');
		$this->map->addViewFooter('api.go', 0, 'other.tpl');
		
		$var = $this->map->getViewFooter('content.default', 0);
		$this->assertEquals('index.tpl', $var);
		
		$var = $this->map->getViewFooter('content.default', 1);
		$this->assertEquals('main.tpl', $var);
		
		$var = $this->map->getViewFooter('api.go', 1);
		$this->assertEquals('nav.tpl', $var);
		
		$var = $this->map->getViewFooter('api.go', 0);
		$this->assertEquals('other.tpl', $var);
	}
	
	public function testGetForward() {
	
		$this->map->addForward('content.default', 0, 'default2');
		$this->map->addForward('content.default', 1, 'default3');
		$this->map->addForward('api.go', 1, 'go3');
		$this->map->addForward('api.go', 0, 'go2');
		
		$var = $this->map->getForward('content.default', 0);
		$this->assertEquals('default2', $var);
		
		$var = $this->map->getForward('content.default', 1);
		$this->assertEquals('default3', $var);
		
		$var = $this->map->getForward('api.go', 1);
		$this->assertEquals('go3', $var);
		
		$var = $this->map->getForward('api.go', 0);
		$this->assertEquals('go2', $var);
	}
	
	public function testGetLogin() {
	
		$this->map->addLogin('content.default', 0, true);
		$this->map->addLogin('content.default', 1, false);
		
		$var = $this->map->getLogin('content.default', 0);
		$this->assertEquals(true, $var);
		
		$var = $this->map->getLogin('content.default', 1);
		$this->assertEquals(false, $var);
	}
	
	public function testGetPermissions() {
	
		$this->map->addPermission('content.default', 0, 'data.admin.read');
		$this->map->addPermission('content.default', 1, 'data.admin.write');
		
		$var = $this->map->getPermissions('content.default', 0);
		$this->assertEquals('data.admin.read', $var[0]);
		
		$var = $this->map->getPermissions('content.default', 1);
		$this->assertEquals('data.admin.write', $var[0]);
	}
	
	public function testGetTheme() {
	
		$this->map->addTheme('content.default', 0, 'default');
		$this->map->addTheme('content.default', 1, 'anothertheme');
		
		$var = $this->map->getTheme('content.default', 0);
		$this->assertEquals('default', $var);
		
		$var = $this->map->getTheme('content.default', 1);
		$this->assertEquals('anothertheme', $var);
	}
	
}

?>