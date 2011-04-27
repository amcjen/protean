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
require_once 'modules/api/lib/requesthelper.class.php';

class RequestHelperTest extends PHPUnit_Framework_TestCase {

	public function setUp() { 
			$_SERVER['argv'][] = 'uri=/content/default';
			$_SERVER['REQUEST_URI'] = '';

			$this->appHelper = PFApplicationHelper::getInstance();
			$this->appHelper->init(PF_BASE . '/modules/api/test/fake/command.xml');
			$this->appHelper->loadControllerMap('content', PF_BASE . '/modules/api/test/fake/command.xml');
			$this->appController = $this->appHelper->appController();
	}
	
	public function tearDown() { }
	
	
	public function testGetDefaultURIApplication() {
		$this->assertEquals('content', PFRequestHelper::getDefaultURIApplication());
	}
	
	public function testGetCurrentURIApplication() {
	
		$_SERVER['REQUEST_URI'] = '/content/default';
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$this->assertEquals('content', PFRequestHelper::getCurrentURIApplication());
		
		$_SERVER['REQUEST_URI'] = '/content/defaultadmin';
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$this->assertEquals('content', PFRequestHelper::getCurrentURIApplication());
		
		$_SERVER['REQUEST_URI'] = '/content/staticpage';
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$this->assertEquals('content', PFRequestHelper::getCurrentURIApplication());
	}
	
	public function testGetURIApplication() {
	
		$uri = '/content/default';
		$verb = 'get';
		$this->assertEquals('content', PFRequestHelper::getURIApplication($uri, $verb));
		
		$uri = '/content/defaultadmin';
		$verb = 'post';
		$this->assertEquals('content', PFRequestHelper::getCurrentURIApplication($uri, $verb));
		
		$uri = '/content/staticpage';
		$verb = 'get';
		$this->assertEquals('content', PFRequestHelper::getCurrentURIApplication($uri, $verb));
	}
	
	public function testGetCurrentURICommand() {
		
		$_SERVER['REQUEST_URI'] = '/content/default';
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$this->assertEquals('default', PFRequestHelper::getCurrentURICommand());
		
		$_SERVER['REQUEST_URI'] = '/content/defaultadmin';
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$this->assertEquals('default', PFRequestHelper::getCurrentURICommand());
		
		$_SERVER['REQUEST_URI'] = '/content/staticpage';
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$this->assertEquals('static', PFRequestHelper::getCurrentURICommand());
	}
	
	public function testGetURICommand() {
	
		$uri = '/content/default';
		$verb = 'get';
		$this->assertEquals('default', PFRequestHelper::getURICommand($uri, $verb));
		
		$uri = '/content/defaultadmin';
		$verb = 'post';
		$this->assertEquals('default', PFRequestHelper::getURICommand($uri, $verb));
		
		$uri = '/content/staticpage';
		$verb = 'get';
		$this->assertEquals('static', PFRequestHelper::getURICommand($uri, $verb));
	}

	public function testGetCurrentURIPattern() {
		
		$_SERVER['REQUEST_URI'] = '/content/defaultadmin';
		$this->assertEquals('/content/defaultadmin', PFRequestHelper::getCurrentURIPattern());
		
		$_SERVER['REQUEST_URI'] = '/content/default/1';
		$this->assertEquals('/content/default/:integer:', PFRequestHelper::getCurrentURIPattern());
		
		$_SERVER['REQUEST_URI'] = '/content/default/15432/edit';
		$this->assertEquals('/content/default/:integer:/edit', PFRequestHelper::getCurrentURIPattern());
	}

	public function testGetURIPattern() {
		$this->assertEquals('/content/default', PFRequestHelper::getURIPattern('/content/default'));
		$this->assertEquals('/content/default', PFRequestHelper::getURIPattern('/content/default/'));
		$this->assertEquals('/content/default/:integer:', PFRequestHelper::getURIPattern('/content/default/1'));	
		$this->assertEquals('/content/default/:integer:', PFRequestHelper::getURIPattern('/content/default/1009192238812'));	
		$this->assertEquals('/content/default/:integer:', PFRequestHelper::getURIPattern('/content/default/1/'));
		$this->assertEquals('/content/default/:integer:/edit', PFRequestHelper::getURIPattern('/content/default/1/edit'));
		$this->assertEquals('/content/default/:integer:/edit', PFRequestHelper::getURIPattern('/content/default/1/edit/'));
		$this->assertEquals('/content/default/:integer:/edit', PFRequestHelper::getURIPattern('/content/default/1/edit|get'));
	}
	
	public function testGetCurrentURI() {
		
		$_SERVER['REQUEST_URI'] = '/content/default';
		$this->assertEquals('/content/default', PFRequestHelper::getCurrentURI());
		
		$_SERVER['REQUEST_URI'] = '/content/default/1';
		$this->assertEquals('/content/default/1', PFRequestHelper::getCurrentURI());
		
		$_SERVER['REQUEST_URI'] = '/content/default/1/';
		$this->assertEquals('/content/default/1', PFRequestHelper::getCurrentURI());
		
		$_SERVER['REQUEST_URI'] = '/content/default/1/edit';
		$this->assertEquals('/content/default/1/edit', PFRequestHelper::getCurrentURI());
		
		$_SERVER['REQUEST_URI'] = '/content/default/1/edit/';
		$this->assertEquals('/content/default/1/edit', PFRequestHelper::getCurrentURI());
	}

	public function testGetRESTSlot() {

		$_SERVER['REQUEST_URI'] = '/content/default/1';
		$this->assertEquals('1', PFRequestHelper::getRESTSlot(1));
		
		$_SERVER['REQUEST_URI'] = '/content/default/1/edit';
		$this->assertEquals('edit', PFRequestHelper::getRESTSlot(2));
		
		$_SERVER['REQUEST_URI'] = '/content/default/1/edit/';
		$this->assertEquals('edit', PFRequestHelper::getRESTSlot(2));
	}

	public function testGetHTTPVerb() {

		$_SERVER['REQUEST_METHOD'] = 'GET';
		$this->assertEquals('get', PFRequestHelper::getHTTPVerb());
		
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$this->assertEquals('post', PFRequestHelper::getHTTPVerb());

		$_SERVER['REQUEST_METHOD'] = 'PUT';
		$this->assertEquals('put', PFRequestHelper::getHTTPVerb());
		
		$_SERVER['REQUEST_METHOD'] = 'DELETE';
		$this->assertEquals('delete', PFRequestHelper::getHTTPVerb());
	}
	
	public function testGetHTTPVerbForURIPattern() {

		$pattern = '/content/default';
		$this->assertEquals('get', PFRequestHelper::getHTTPVerbForURIPattern($pattern));
		
		$pattern = '/content/default/1|post';
		$this->assertEquals('post', PFRequestHelper::getHTTPVerbForURIPattern($pattern));

		$pattern = '/content/defaultadmin/4/edit|put';
		$this->assertEquals('put', PFRequestHelper::getHTTPVerbForURIPattern($pattern));
		
		$pattern = '/content/defaultadmin/4/edit/|delete';
		$this->assertEquals('delete', PFRequestHelper::getHTTPVerbForURIPattern($pattern));
	}
}

?>
