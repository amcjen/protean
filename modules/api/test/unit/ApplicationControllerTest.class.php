<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2011, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

require_once 'config.php';
require_once 'PHPUnit/Framework.php';
require_once 'modules/api/lib/applicationcontroller.class.php';
require_once 'modules/api/lib/applicationhelper.class.php';
require_once 'modules/content/cmd/default.class.php';

class ApplicationControllerTest extends PHPUnit_Framework_TestCase {

	private $appHelper;
	private $appController;
	private $request;
	
	public function setUp() {
		$_SERVER['argv'][] = 'pf.uri=/content/default';
		
		$this->appHelper = PFApplicationHelper::getInstance();
		$this->appHelper->init(PF_BASE . '/modules/api/test/fake/command.xml');
		$this->appController = $this->appHelper->appController();
		$this->request = PFFactory::getInstance()->createObject('api.request');
	}
	
	public function tearDown() {
	
	}
	
	public function testGetView() {
	
		$view = $this->appController->getView($this->request);
		$this->assertEquals('index.tpl', $view);
	}
	
	public function testGetViewApplication() {
	
		$app = $this->appController->getViewApplication($this->request);
		$this->assertEquals('content', $app);
	}
	
	public function testGetViewHeader() {
	
		$tpl = $this->appController->getViewHeader($this->request);
		$this->assertEquals('header.tpl', $tpl);
	}
	
	public function testGetViewHeaderApplication() {
	
		$app = $this->appController->getViewHeaderApplication($this->request);
		$this->assertEquals('content', $app);
	}
	
	public function testGetViewFooter() {
	
		$tpl = $this->appController->getViewFooter($this->request);
		$this->assertEquals('footer.tpl', $tpl);
	}
	
	public function testGetViewFooterApplication() {
	
		$app = $this->appController->getViewFooterApplication($this->request);
		$this->assertEquals('content', $app);
	}
	
	public function testGetCommand() {
	
		$cmd = $this->appController->getCommand($this->request);
		$defaultCommand = new ReflectionClass('PFDefaultCommand');

		$this->assertTrue($defaultCommand->isInstance($cmd), 'DefaultCommand not instantiated correctly.');
	}
	
	public function testGetLogin() {
		
		$this->assertFalse((bool)$this->appController->getLogin($this->request));
		
		$request = PFFactory::getInstance()->createObject('api.request');
		$request->set('pf.uri', '/content/changepassword');
		
		$this->assertTrue((bool)$this->appController->getLogin($request));
	}
	
	public function testGetForward() {
		
		$this->assertFalse((bool)$this->appController->getForward($this->request));
		
		$request = PFFactory::getInstance()->createObject('api.request');
		$request->set('pf.uri', '/content/addressbook');
		$this->assertEquals('/content/account', $this->appController->getForward($request));
		
		$request->set('pf.uri', '/content/addressbook/1');
		$this->assertEquals('/content/addressbook/:integer:/edit', $this->appController->getForward($request));
	}

	public function testGetPermissions() {
	
		// test a command statement with no permissions
		$permissions = $this->appController->getPermissions($this->request);
		$this->assertTrue(count($permissions) == 0);
		
		// try a command statement with a couple of permissions set.  Make sure all of them are included
		$request = PFFactory::getInstance()->createObject('api.request');
		$request->set('pf.uri', '/content/addressbook');

		$permissions = $this->appController->getPermissions($request);

		$this->assertTrue(in_array('shop.user', $permissions));
		$this->assertTrue(in_array('shop.admin', $permissions));
	}

	public function testGetTheme() {
	
		$request = PFFactory::getInstance()->createObject('api.request');
		$request->set('pf.uri', '/content/forgotpassword');
		
		$theme = $this->appController->getTheme($request);
		$this->assertEquals('sometheme', $theme);
	}
}

?>