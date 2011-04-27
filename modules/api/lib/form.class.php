<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2011, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/
/**
@package api
*/
// load up patForms classes
require_once 'modules/thirdparty/patForms/patForms.php';
require_once 'modules/thirdparty/patForms/patForms/Parser.php';
require_once 'modules/thirdparty/patError/patErrorManager.php';

class PFForm { 

	protected $request;
	protected $parser;
	protected $form;
	protected $formTemplate;

	public function __construct($request, $formTemplate, $submitName='submit', $action='', $charset='utf-8', $encType='multipart/form-data') {

		list ($appName, $formName) = explode('.', $formTemplate);

		$this->formTemplate = $formTemplate;
		$this->request = $request;

		$this->parser = &patForms_Parser::createParser('SmartyRenderer');
		$this->parser->setNamespace('PFForm');

		if (PFRegistry::getInstance()->isValueSet('pf_override_theme') && 
		file_exists(PF_BASE . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $appName . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . PFRegistry::getInstance()->get('pf_override_theme') . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . $formName . '.form.tpl')) {
			$theme = PFRegistry::getInstance()->get('pf_override_theme');
		} else {
			$theme = PFRegistry::getInstance()->get('pf_theme');	
		}

		$this->parser->parseFile(PF_BASE . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $appName . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . $theme . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . $formName . '.form.tpl');

		$this->form = &$this->parser->getForm();
		$this->form->setAttribute('action', $action);
		$this->form->setAttribute('accept-charset', $charset);
		$this->form->setAttribute('enctype', $encType);

		if (PF_FORM_FIELD_ERROR_HIGHLIGHTING) {
			$errorAttributes = array(
				'class'				=>	'errorfield',
				'description'	=>	'Please fix me, I\'m erroneous!'
				);

			$errAtt =& patForms::createObserver('ErrorAttributes');
			$errAtt->setAttributes($errorAttributes);
			$this->form->attachObserver($errAtt, PATFORMS_OBSERVER_ATTACH_TO_ELEMENTS);
		}

		$this->form->setAutoValidate($submitName);
		$this->form->setRenderer($this->parser);
	}

	public function reportErrors() {
		$errors = $this->form->getValidationErrors();

		if ($errors) {	
			$app = PFRequestHelper::getCurrentURIApplication();
			$errString = '';

			foreach ($errors as $elementName => $elementErrors) {
				if (empty($elementErrors)) {
					continue;
				}

				try {
					if ($elementErrors[0]['code'] == 1) { 
						$errString .= $elementErrors[0]['message'] . ': '.PFLanguage::getInstance()->getTranslation($app, strtoupper($elementName)) . '<br />';
					}
					else{
						$errString .= PFLanguage::getInstance()->getTranslation($app, strtoupper($elementName)) . ': ' . $elementErrors[0]['message'] . '<br />';
					}
				}
				catch (Exception $e){
					$errString .= 'Required field missing.<br />';
				}
			}

			$errString = substr($errString, 0, -6);
			$this->request->addFeedback('', $errString, 'INSUFFICIENT_DATA');
			return true;
		}
		else {
			return false;
		}
	}

	public function setAction($action) {
		$this->form->setAttribute('action', $action);
	}

	public function setCharset($charset='utf-8') {
		$this->form->setAttribute('accept-charset', $action);
	}

	public function setEncodingType($encodingType='multipart/form-data') {
		$this->form->setAttribute('enctype', $action);
	}

	public function renderForm() {
		try {
			$fileName = $this->writeFormToTempDirectory();	
			list ($appName, $formName) = explode('.', $this->formTemplate);
			$formPage = PFFactory::getInstance()->createObject('api.template', $appName);
			$vars = PFRegistry::getInstance()->getPage()->tpl_vars;

			foreach ($vars as $key => $val) {
				$formPage->assign($key, $val);
			}

			$ret = $formPage->fetch('', $appName . '.' . $formName . '.form.tpl', false, PF_TEMP_PATH . DIRECTORY_SEPARATOR . 'tpl_forms' . DIRECTORY_SEPARATOR);
			return $ret;

		} catch (PFException $e) {
			$e->handleException();
		}
	}

	public function getFormObject() {	
		return $this->form;
	}

	public function isSubmitted() {
		PFRequestHelper::resetHTTPVerb();
		if ($this->form->isSubmitted()) {
			$this->form->setSubmitted(false);
			return true;
		}
		return false;
	}

	public function setReadOnlyOnPartialErrors() {
		$readOnly = &patForms::createObserver('ReadonlyFinished');
		$this->form->attachObserver($readOnly, PATFORMS_OBSERVER_ATTACH_TO_ELEMENTS);
	}

	public function applyTrimFilters($fields) {
		foreach ($fields as $field) {	
			$el = &$this->form->getElementByName($field);
			if ($el) {
				$el->applySimpleFilter('trim');
			}
		}
	}

	protected function createTempFormsDirectory() {
		if (!is_dir(PF_TEMP_PATH . DIRECTORY_SEPARATOR . 'tpl_forms') && is_writable(PF_TEMP_PATH)) {
			mkdir(PF_TEMP_PATH . DIRECTORY_SEPARATOR . 'tpl_forms', 0770);
		}
	}

	protected function writeFormToTempDirectory() {
		$this->createTempFormsDirectory();		
		list ($appName, $formName) = explode('.', $this->formTemplate);	
		$filename = PF_TEMP_PATH . DIRECTORY_SEPARATOR . 'tpl_forms' . DIRECTORY_SEPARATOR . $appName . '.' . $formName . '.form.tpl';
		$somecontent = $this->form->renderForm();

		touch($filename);
		if (is_writable($filename)) {
			if (!$handle = fopen($filename, 'w')) {
				throw new PFException('api', array('CANNOT_OPEN_FILE', $filename), E_USER_ERROR);	
			}

			if (fwrite($handle, $somecontent) === FALSE) {
				throw new PFException('api', array('CANNOT_WRITE_TO_FILE', $filename), E_USER_ERROR);	
			}	
			fclose($handle);		
		} else {
			throw new PFException('api', array('CANNOT_WRITE_TO_FILE', $filename), E_USER_ERROR);	
		}
	}
}

?>