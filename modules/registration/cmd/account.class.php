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
@package shop
*/	
class PFAccountCommand extends PFResourceCommand {

	protected $helper;
	protected $userId;
	protected $partyId;
	
	public function __construct() {
		parent::__construct();

		$this->helper = PFFactory::getInstance()->createObject('registration.userhelper');
		$this->userId = $this->session->get('user_id');
		$this->partyId = $this->session->get('user_party_id');
	    $this->rewardsPointsHelper = PFFactory::getInstance()->createObject('shop.rewardspointshelper');
    	$this->page->assign('PARTY_CURRENT_POINTS', $this->rewardsPointsHelper->getCurrentPointsForParty($this->partyId));

	    
	}
	
	public function index(PFRequest $request) { 					// GET 		/registration/account
		$this->page->assign('user', $this->helper->getUser($this->userId));
		$this->page->assign('orderhistory', $this->helper->getOrderHistoryForParty($this->partyId));
		//$this->page->assign('billingcountries', $this->helper->getAvailableBillingAddressCountries());
		//$this->page->assign('shippingcountries', $this->helper->getAvailableShippingAddressCountries());
		$this->make($request);
		return self::statuses('CMD_DEFAULT');
	}
	
	public function make(PFRequest $request) {						// GET 		/registration/account/make
		$form = $this->initializeCreateForm($request);
		$this->populateCreateForm($request, $form);
		$this->page->assign('FORM_ELEMENTS', $form->renderForm());
		return $this->handleSubmittedCreateForm($request, $form);
	}
	
	public function create(PFRequest $request) { 					// POST 	/registration/account
		$form = $this->initializeCreateForm($request);
		$this->page->assign('FORM_ELEMENTS', $form->renderForm());	
		return $this->handleSubmittedCreateForm($request, $form);
	}
	
	public function show(PFRequest $request, $key) {			// GET 		/registration/account/:integer:
		return self::statuses('CMD_DEFAULT');
	}
	
	public function edit(PFRequest $request, $key) {			// GET 		/registration/account/:integer:/edit
		return self::statuses('CMD_DEFAULT');
	}
	
	public function update(PFRequest $request, $key) {		// PUT 		/registration/account/:integer:
		try {
			$this->helper->updateUser($this->userId, $request);
			$json = PFRestHelper::makeResponse('ok', '201', 'User successfully updated');		
			PFRestHelper::sendResponse(201, $json);
		} catch (Exception $e) {
			$e = PFException::enrich($e);
			$e->handleRestException();
		}
	}
	
	public function destroy(PFRequest $request, $key) {		// DELETE /registration/account/:integer:
		PFRestHelper::sendResponse(501, json_encode(array('status' => 'notimplemented')), 'application/json');
	}
	
	public function initializeCreateForm(PFRequest $request) {
		$form = PFFactory::getInstance()->createObject('api.form', $request, 'registration.account', 'createaccount');
		$form->setAction($this->session->getURL('registration/account', true));
		
		$emailRule	=	&patForms::createRule('Email');
		$el = &$form->getFormObject()->getElementByName('email');
		$el->addRule($emailRule, PATFORMS_RULE_AFTER_VALIDATION);
		
		$passwordRule =	&patForms::createRule('PasswordMatch');
		$passwordRule->setPassword1('password');
		$passwordRule->setPassword2('password2');
		$form->getFormObject()->addRule($passwordRule, PATFORMS_RULE_AFTER_VALIDATION);

		$fields = array('firstname',
		                'lastname',
		                'email',
										'password',
										'password2'
									 );
		$form->applyTrimFilters($fields);
		return $form;
	}
	
	public function populateCreateForm(PFRequest $request, PFForm $form) {
	  $user = $this->helper->getUser($this->userId);
	  
	  $form->getFormObject()->setValues(
      array(
        'firstname' => $user->getParty()->getFirstName(),
        'lastname' => $user->getParty()->getLastName(),
        'email' => $user->getParty()->getEmail()
        ),
        false
      );
	}
		
	protected function handleSubmittedCreateForm(PFRequest $request, PFForm $form) {
		if ($form->isSubmitted()) {	
			if ($form->reportErrors()) {
				return self::statuses('CMD_INSUFFICIENT_DATA');
			}
			
			try {
				$this->helper->createUser($request);
				$this->helper->login($request->get('email'), $request->get('password'));
			} catch (PFException $e) {
				$e->handleException();
				return self::statuses('CMD_ERROR');
			}

			if ($this->session->isRegistered('pf.redirect_url')) {
				$redirectUrl = '/' . str_replace('.', '/', $this->session->get('pf.redirect_url'));
				$this->redirectTo($redirectUrl, true);
			} else {
				return self::statuses('CMD_OK');
			}
		}
	}
}

?>