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
require_once 'modules/api/lib/factory.class.php';
require_once 'modules/api/lib/request.class.php';
require_once 'modules/content/cmd/default.class.php';

class LanguageTest extends PHPUnit_Framework_TestCase {

	private $language;
	
	public function setUp() {
	
		$this->language = PFLanguage::getInstance();
	}
	
	public function tearDown() {
	
	}
	
	public function testAvailableLocales() {
	
		$loc = $this->language->getAvailableLocales();
		
		$this->assertTrue(in_array('en', $loc), 'Locale \'en\' is not available');
	}
	
	public function testAvailableLanguages() {
	
		$lang = $this->language->getAvailableLanguages();
	
		$this->assertTrue(in_array('en', $lang), 'Language \'en\' is not available');
	}
	
	public function testTranslation() {
	
		$this->assertTrue($this->language->loadTranslationTable('api'), 'Failed loading translation table');
		
		$prot = $this->language->getTranslation('api', PROTEAN_FRAMEWORK);
		
		$this->assertEquals('Protean Framework', $prot, '$language->GetTranslation() failed:');
	}
}

?>