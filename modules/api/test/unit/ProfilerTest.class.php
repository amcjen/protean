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
require_once 'modules/api/lib/profiler.class.php';

class ProfilerTest extends PHPUnit_Framework_TestCase {

	private $profiler;
	
	public function setUp() {
		$this->profiler = PFProfiler::getInstance();
	}
	
	public function tearDown() {
	
	}
	
	public function testTime() {
	
		$this->profiler->setStartTime(microtime(true));
		usleep(10000);
		$var = $this->profiler->getTime();
		
		$this->assertEquals(0.01, (float)$var, 'Profiler time exceeds delta.', .05);
	}
}

?>