<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2012, Eric Jennings.  All rights reserved.            *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

class PFSignupCommand extends PFDefaultCommand implements PFFormCommand { 

	public function doExecute(PFRequest $request) {
		parent::doExecute($request);
		$form = $this->initializeForm($request);
		$this->populateForm($request, $form);
		$page = PFRegistry::getInstance()->getPage();
		$page->assign('FORM_ELEMENTS', $form->renderForm());	
		return $this->handleSubmittedForm($request, $form);
	}
	
	public function initializeForm(PFRequest $request) { 
		$form = PFFactory::getInstance()->createObject('api.form', $request, 'registration.signup', 'create');
		$form->setAction(PFSession::getInstance()->getURL('registration/signup'));
		
		$emailRule	=	&patForms::createRule('Email');
		$el = &$form->getFormObject()->getElementByName('username');
		$el->addRule($emailRule, PATFORMS_RULE_AFTER_VALIDATION);
		
		$passwordRule =	&patForms::createRule('PasswordMatch');
		$passwordRule->setPassword1('password');
		$passwordRule->setPassword2('password2');
		$form->getFormObject()->addRule($passwordRule, PATFORMS_RULE_AFTER_VALIDATION);

		$fields = array('username',
										'password',
										'password2'
									 );
		$form->applyTrimFilters($fields);
		return $form;
	}	
		
	public function populateForm(PFRequest $request, PFForm $form) { }
				
	public function handleSubmittedForm(PFRequest $request, PFForm $form) {	
		if ($form->getFormObject()->isSubmitted()) {	
			if ($form->reportErrors()) {			
				return self::statuses('CMD_INSUFFICIENT_DATA');
			}

			$user = PFFactory::getInstance()->createObject('registration.userhelper');
			
			$con = Propel::getConnection(PF_DATABASE_NAME);
			
			try {				
				$con->beginTransaction();				
				$timestamp = mktime();
				$user->createUser($con, $request, $timestamp);
				$con->commit();				
			} catch (Exception $e) {
				$con->rollback();
				PFException::handleVanillaException($e->getMessage(), $e->getCode());
				return self::statuses('CMD_ERROR');
			}

			$user->login($request->getProperty('username'), $request->getProperty('password'));
			
			if (PFSession::getInstance()->isRegistered('pf.redirect_url')) {
				$redirectUrl = '/' . str_replace('.', '/', PFSession::getInstance()->get('pf.redirect_url'));
				$this->redirectTo($redirectUrl, true);
			} else {
				return self::statuses('CMD_OK');
			}
		}
	}
}
?>