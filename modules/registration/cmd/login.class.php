<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2012, Eric Jennings.  All rights reserved.            *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

class PFLoginCommand extends PFDefaultCommand implements PFMultiFormCommand {

  public function doExecute(PFRequest $request) {
    parent::doExecute($request);

    $forms = $this->initializeForms($request);
    $this->populateForms($request, $forms);
    $this->page->assign('LOGIN_FORM_ELEMENTS', $forms['login']->renderForm());
    $this->page->assign('CREATE_FORM_ELEMENTS', $forms['create']->renderForm());
    return $this->handleSubmittedForm($request, $forms);
  }

  public function initializeForms(PFRequest $request) {
    $forms = array();

    $form = PFFactory::getInstance()->createObject('api.form', $request, 'registration.login', 'signin');
    $form->setAction($this->session->getURL('registration/login', true));

    $fields = array('login-emailaddress',
                    'login-password');
    $form->applyTrimFilters($fields);
    $forms['login'] = $form;

    $form2 = PFFactory::getInstance()->createObject('api.form', $request, 'registration.account', 'createaccount');
    $form2->setAction($this->session->getURL('registration/account', true));

    $fields2 = array('firstname',
                    'lastname',
                    'email',
                    'password',
                    'password2');
    $form2->applyTrimFilters($fields2);
    $forms['create'] = $form2;

    return $forms;
  }

  public function populateForms(PFRequest $request, array $forms) { }

  public function handleSubmittedForm(PFRequest $request, array $forms) {
    try {
      if ($request->isPropertySet('signin')) {
        $this->handleSigninForm($request, $forms['login']);
      }
      if ($request->isPropertySet('createaccount')) {
        $this->handleCreateForm($request, $forms['create']);
      }
    } catch (PFException $e) {
      $e->handleException();
    }
  }

  protected function handleSigninForm(PFRequest $request, PFForm $form) {
    if ($form->getFormObject()->isSubmitted()) {
      $userHelper = PFFactory::getInstance()->createObject('registration.userhelper');
      
      $request->unsetProperty('signin');
      
      if ($form->reportErrors()) {
        return self::statuses('CMD_INSUFFICIENT_DATA');
      }

      $username = $request->get('login-emailaddress');
      $password = $request->get('login-password');
      $persistent = $request->get('rememberlogin');
      $redirectUrl = $this->session->retrieve('pf.session_redirect_url');

      $userHelper = PFFactory::getInstance()->createObject('registration.userhelper');

      if (PFRequestHelper::getRESTSlot(1) && $userHelper->doesCurrentUserHavePermission('admin')) {
        $party = PartyQuery::create()->findPK(PFRequestHelper::getRESTSlot(1));
        if (!$party) {
          return self::statuses('CMD_ERROR');
        }
        $originalPartyUsername = $this->session->retrieve('user_username');
        
        $userHelper->login($username, $password, $persistent, true);
        $this->page->assign('CURRENTLY_LOGGED_IN', true);
        $this->page->assign('CURRENTLY_LOGGED_IN_AS', $this->session->get('user_fullname'));
        
        $this->session->register('shadow_party_id', $party->getPartyId());
        $this->session->register('shadow_party_name', $party->getFirstName() . ' ' . $party->getLastName());
        $this->session->register('shadow_original_username', $originalPartyUsername);
        
        return PFRestHelper::sendResponse(200);
      } else {
        if (!$userHelper->login($username, $password, $persistent)) {
          return self::statuses('CMD_ERROR');
        } else {
          $this->page->assign('CURRENTLY_LOGGED_IN', true);
          $this->page->assign('CURRENTLY_LOGGED_IN_AS', $this->session->get('user_fullname'));

          $this->redirectToSessionUrl();
          return self::statuses('CMD_OK');
        }
      }
    }
  }

  protected function handleCreateForm(PFRequest $request, PFForm $form) {
    
    if ($form->isSubmitted()) {	
			if ($form->reportErrors()) {
				return self::statuses('CMD_INSUFFICIENT_DATA');
			}
			
			try {
			  $userHelper = PFFactory::getInstance()->createObject('registration.userhelper');
				$userHelper->createUser($request);
				$userHelper->login($request->get('email'), $request->get('password'));
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