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
require_once 'modules/api/lib/errorstack.class.php';
require_once 'modules/api/lib/exception.class.php';

class ErrorStackTest extends PHPUnit_Framework_TestCase {

	private $errorstack;
	private $exception;
	private $exception2;
	
	public function setUp() {
	
		$this->errorstack = PFFactory::getInstance()->createObject('api.errorstack');
		
		$this->exception = new PFException('', 'test message', E_USER_NOTICE);
		$this->exception2 = new PFException('api', 'WARNING', E_USER_WARNING);
	}
	
	public function tearDown() {
	
	}
	
	public function testAppend() {
		
		$this->errorstack->append($this->exception);
		$stack = $this->errorstack->getErrorStack();
		
		$this->assertSame($this->exception, $stack[0]);
	}
	
	public function testGetErrorStack() {
		
		$this->errorstack->append($this->exception);
		$this->errorstack->append($this->exception2);
		
		$count = count($this->errorstack->getErrorStack());
		
		$this->assertEquals(2, $count);
	}
	
	public function testClearErrorStack() {
		
		$this->errorstack->append($this->exception);
		$this->errorstack->append($this->exception2);
		
		$this->errorstack->clearErrorStack();
		$count = count($this->errorstack->getErrorStack());
		
		$this->assertEquals(0, $count);
	}
}

?>