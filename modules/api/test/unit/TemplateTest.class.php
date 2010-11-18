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
require_once 'modules/api/lib/template.class.php';

class TemplateTest extends PHPUnit_Framework_TestCase {

	private $template;
	
	public function setUp() {
		PFRegistry::getInstance()->set('pf_theme', 'default');
		$this->template = PFFactory::getInstance()->createObject('api.template', 'content');
	}
	
	public function tearDown() {
	
	}
	
	public function testAssign() {
	
		$this->template->assign('test', 12321);

		$var = $this->template->get_template_vars('test');
		
		$this->assertEquals(12321, $var);
	}
	
	public function testDirectories() {
	
		$this->assertTrue(is_dir($this->template->getTemplateDir()) && 
											is_dir($this->template->getCompileDir()) &&
											is_dir($this->template->getConfigDir()) && 
											is_dir($this->template->getCacheDir()));
	}
}

?>