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
require_once 'modules/api/lib/request.class.php';

class RequestTest extends PHPUnit_Framework_TestCase {

	private $request;
	
	public function setUp() {
	
		PFApplicationHelper::getInstance()->loadControllerMap('content', PF_BASE . '/modules/api/test/fake/command.xml');
		$this->request = PFFactory::getInstance()->createObject('api.request');
	}
	
	public function tearDown() {
	
	}
	
	public function testSetGetProperty() {
	
		$this->request->setProperty('var1', 'test');
		$var = $this->request->getProperty('var1');
		
		$this->assertEquals('test', $var);
	}
	
	public function testSetGet() {
	
		$this->request->set('var2', 'test2');
		$var = $this->request->get('var2');
		
		$this->assertEquals('test2', $var);
	}
	
	public function testGetCurrentURIPattern() {
		
		$_SERVER['REQUEST_URI'] = '/content/default';
		$this->assertEquals('/content/default', PFRequestHelper::getCurrentURIPattern());
		
		$_SERVER['REQUEST_URI'] = '/content/default/';
		$this->assertEquals('/content/default', PFRequestHelper::getCurrentURIPattern());
		
		$_SERVER['REQUEST_URI'] = '/content/default/1';
		$this->assertEquals('/content/default/:integer:', PFRequestHelper::getCurrentURIPattern());
		
		$_SERVER['REQUEST_URI'] = '/content/default/1/';
		$this->assertEquals('/content/default/:integer:', PFRequestHelper::getCurrentURIPattern());
		
		$_SERVER['REQUEST_URI'] = '/content/default/1/edit';
		$this->assertEquals('/content/default/:integer:/edit', PFRequestHelper::getCurrentURIPattern());
		
		$_SERVER['REQUEST_URI'] = '/content/default/1/edit/';
		$this->assertEquals('/content/default/:integer:/edit', PFRequestHelper::getCurrentURIPattern());
		
		$_SERVER['REQUEST_URI'] = '/content/default?yep=1';
		$this->assertEquals('/content/default', PFRequestHelper::getCurrentURIPattern());
		
		$_SERVER['REQUEST_URI'] = '/content/default/?yep=1';
		$this->assertEquals('/content/default', PFRequestHelper::getCurrentURIPattern());
		
		$_SERVER['REQUEST_URI'] = '/content/default/1?yep=1';
		$this->assertEquals('/content/default/:integer:', PFRequestHelper::getCurrentURIPattern());
		
		$_SERVER['REQUEST_URI'] = '/content/default/1/?yep=1';
		$this->assertEquals('/content/default/:integer:', PFRequestHelper::getCurrentURIPattern());
		
		$_SERVER['REQUEST_URI'] = '/content/default/1/edit?yep=1';
		$this->assertEquals('/content/default/:integer:/edit', PFRequestHelper::getCurrentURIPattern());
		
		$_SERVER['REQUEST_URI'] = '/content/default/1/edit/?yep=1';
		$this->assertEquals('/content/default/:integer:/edit', PFRequestHelper::getCurrentURIPattern());
	}
}

?>