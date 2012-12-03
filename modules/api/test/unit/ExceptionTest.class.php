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
require_once 'modules/api/lib/exception.class.php';

class ExceptionTest extends PHPUnit_Framework_TestCase {

	private $exception;
	private $exception2;
	
	public function setUp() {
	
		$this->exception = new PFException('', 'test message', E_USER_NOTICE);
		$this->exception2 = new PFException('api', 'WARNING', E_USER_WARNING);
	}
	
	public function tearDown() {
	
	}
	
	public function testErrorCode() {
		
		$this->assertEquals(E_USER_NOTICE, $this->exception->getCode());
		$this->assertEquals(E_USER_WARNING, $this->exception2->getCode());
	}
}

?>