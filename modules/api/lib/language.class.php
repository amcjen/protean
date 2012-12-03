<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2012, Eric Jennings.  All rights reserved.            *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/
/**
@package api
*/
class PFLanguage {

	static protected $instance;	
	protected $translationTable;
	protected $defaultLocale;
	protected $currentLocale;
	protected $languageTable;
	
	private function __construct() {
		$this->languageTable['en'] 		= 'en';
		$this->languageTable['en-us'] = 'en';
		$this->languageTable['en-gb'] = 'en';
		$this->languageTable['es'] 		= 'es';
		
		$this->translationTable = Array();
		
		$languages = array_unique(array_values($this->languageTable));
		
		foreach ($languages as $lang) {
			$this->translationTable[$lang] = Array();
		}
	
		$this->defaultLocale = 'en';
		$locale = $this->getHTTPAcceptLanguage();	
		
		if (empty($locale)) {		
			$locale = $this->defaultLocale;
		}

		$this->setCurrentLocale($locale);	
	}
	
	static public function getInstance() {		
		if (self::$instance == NULL) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	public function getAvailableLocales() {	
		return array_keys($this->languageTable);
	}
	
	public function getAvailableLanguages() {	
		return array_unique(array_values($this->languageTable));
	}
	
	public function getCurrentLanguage() {	
		$locale = $this->currentLocale;
		return $this->languageTable[$locale];
	}
	
	public function getCurrentLocale() {	
		return $this->currentLocale;
	}
	
	public function setCurrentLocale($locale) {		
		if (isset($this->languageTable[$locale])) {
			$this->currentLocale = $locale;
		} else {
			return false;
		}
	}
	
	public function getDefaultLocale() {
		return $this->defaultLocale;
	}
	
	public function getHTTPAcceptLanguage() {
		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			$langs = explode(';', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
			$locales = $this->GetAvailableLocales();
			
			foreach ($langs as $value_and_quality) {
				$values = explode(',', $value_and_quality);
				
				foreach ($values as $value) {		
					if (in_array($value, $locales)) {
							return $value;
					}
				}
			}
		}

		return $this->getDefaultLocale();
	}
	
	public function loadTranslationTable($appName, $table='global') {
		$debug = PF_MULTI_LANGUAGE_DEBUG;
	
		$locale = $this->getCurrentLocale();
		$language = $this->getCurrentLanguage();

		if ($debug) {
			PFDebugStack::append('Language: ' . $language . ' -- ' . 'Application: ' . $appName);
		}
		
		$languages = $this->getAvailableLanguages();

		if (!in_array($language, $languages)) {
			throw new PFException('', 'Language "' . $language . '" not available', E_USER_ERROR);
		}

		$path = $this->getLanguageTableFilePath($appName, $table);

		if ($debug) {
			PFDebugStack::append('Language Table File Path: ' . $path);
		}
	
		if (!array_key_exists($appName, $this->translationTable[$language])) {
			$this->translationTable[$language][$appName] = array();
		}

		if (array_key_exists($path, $this->translationTable[$language][$appName])) {
			if ($debug) {
				PFDebugStack::append('Language: ' . $language . ' -- ' . 'Application: ' . $appName . ': ' . $language . '/' . $appName . ' already loaded');
			}
			return true;
		}

		$this->translationTable[$language][$appName][$path] = Array();
		
		if (file_exists($path)) {			
			$entries = file($path);
	
			if ($debug) {			
				PFDebugStack::append('Loading ' . $language . '/' . $appName);
			}
			
			foreach ($entries as $row) {
				if (substr(ltrim($row), 0, 2) == '//') { 
					continue;
				}
					
				$keyValuePair = explode('=', $row);
				
				$key = trim($keyValuePair[0]);
				$value = @$keyValuePair[1];
				
				if(sizeof($keyValuePair) == 1) {
					@$this->translationTable[$language][$appName][$path][$key] .= ' ' . chop($keyValuePair[0]);
					continue;
				}
				
				if (!empty($key)) {
					$this->translationTable[$language][$appName][$path][$key] = chop($value);
				}
			}
			
			return false;
		}
		
		if ($appName == 'api' || $appName == 'content') {
			throw new PFException('', 'Language file ' . $path . ' does not exist', E_USER_ERROR);
		}
		
		return true;
	}
	
	public function unloadTranslationTable($appName, $locale, $path) {	
		$language = $this->languageTable[$locale];
		
		if (empty($language)) {
			throw new PFException('', 'Unsupported locale "' . $locale . '"', E_USER_ERROR);
		}
		
		unset($this->translationTable[$language][$appName][$path]);	
		return true;
	}
	
	public function getTranslation($appName, $key) {	
		if (!isset($this->translationTable[$this->getCurrentLanguage()])) {
			throw new PFException('', 'Language ' . $this->getCurrentLanguage() . ' for current application not available', E_USER_WARNING);
			$this->setCurrentLocale($this->getDefaultLocale());
		}
	
		if (!isset($this->translationTable[$this->getCurrentLanguage()][$appName])) {
			$this->loadTranslationTable($appName);
			$this->setCurrentLocale($this->getDefaultLocale());
		}
	
		$trans = $this->translationTable[$this->getCurrentLanguage()][$appName];
	
		if (!isset($trans)) {
			throw new PFException('', 'Language ' . $this->getCurrentLanguage() . ' for current application not available', E_USER_WARNING);
		}
		
		if (is_array($trans)) {
			foreach ($trans as $table) {
				if (isset($table[$key])) {				
					return $table[$key];
				} else {
					if ($appName == 'api') {
						throw new PFException('', 'Language key "' . $key . '" for application "' . $appName . '" not found', E_USER_WARNING);
					} elseif ($appName == 'content') {
						$key = $this->getTranslation('api', $key);
					} else {
						$key = $this->getTranslation('content', $key);
					}
				}
			}
		}

		return $key;
	}
	
	public function getLanguageTableFilePath($appName, $table) {
		return (PF_BASE . '/modules/' . $appName . '/lang/' . $this->getCurrentLanguage() . '/' . $table . '.lng');
	}
	
	public function getLanguageTableFileDirectory($appName) {	
		return (PF_BASE . '/modules/' . $appName . '/lang/' . $this->getCurrentLanguage() . '/');
	}
}
?>