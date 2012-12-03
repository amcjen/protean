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
require_once 'modules/api/lib/session.class.php';

class SessionTest extends PHPUnit_Framework_TestCase {

	private $session;
	
	public function setUp() {
		$this->session = PFSession::getInstance();
	}
	
	public function tearDown() {
	
	}
	
	public function testRegister() {
	
		$this->session->register('key', 'testString');
		$var = $this->session->retrieve('key');
		
		$this->assertEquals($var, 'testString');
	}
	
	public function testUnregister() {
	
		$this->session->register('key', 'testString');
		$this->session->unregister('key');
		$var = $this->session->retrieve('key');
		
		$this->assertFalse($var);
	}
	
	public function testIsRegistered() {
	
		$this->session->register('key', 'testString');
		
		$this->assertTrue($this->session->isRegistered('key'));
	}
	
	public function testGetURL() {
		$this->assertEquals(PF_URL . '/shop/product', $this->session->getURL('/shop/product'));
		$this->assertEquals(PF_URL . '/shop/product', $this->session->getURL('shop/product'));
		$this->assertEquals(PF_URL . '/shop/product', $this->session->getURL('/shop/product?blah=1'));
		$this->assertEquals(PF_URL . '/shop/product', $this->session->getURL('shop/product?blah=1'));
	}
}

?>