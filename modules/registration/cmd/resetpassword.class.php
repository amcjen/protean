<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2012, Eric Jennings.  All rights reserved.            *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

class PFResetPasswordCommand extends PFDefaultCommand implements PFFormCommand {

  protected $helper;

  public function doExecute(PFRequest $request) {
    parent::doExecute($request);

    $this->helper = PFFactory::getInstance()->createObject('registration.userhelper');

    if (!$this->checkURLPermission($request)) {
      $request->addFeedback('', 'An invalid key was given.  Please try resetting your password again.', E_USER_ERROR);
      return self::statuses('CMD_ERROR');
    }

    //$this->helper->resetPassword('123', '123');

    $form = $this->initializeForm($request);
    $this->populateForm($request, $form);
    $this->page->assign('FORM_ELEMENTS', $form->renderForm());
    return $this->handleSubmittedForm($request, $form);
  }

  public function initializeForm(PFRequest $request) {
    $form = PFFactory::getInstance()->createObject('api.form', $request, 'registration.resetpassword', 'reset');
    $form->setAction($this->session->getURL('registration/resetpassword') . '?key=' . $request->get('key'));

    $passwordRule =  &patForms::createRule('PasswordMatch');
    $passwordRule->setPassword1('password');
    $passwordRule->setPassword2('password2');
    $form->getFormObject()->addRule($passwordRule, PATFORMS_RULE_AFTER_VALIDATION);

    $fields = array('password',
                    'password2');
    $form->applyTrimFilters($fields);

    return $form;
  }

  public function populateForm(PFRequest $request, PFForm $form) { }

  public function handleSubmittedForm(PFRequest $request, PFForm $form) {
    if ($form->isSubmitted()) {
      if ($form->reportErrors()) {
        return self::statuses('CMD_INSUFFICIENT_DATA');
      }
      
      $user = $this->helper->getUserFromPasswordResetKey($request->get('key'));

      try {        
        $this->helper->resetPassword($request->get('password'), $request->get('key'));
      } catch (PFException $e) {
        $e->handleException();
        return self::statuses('CMD_ERROR');
      }

      if ($user) {
        parent::assignDefaults($request);
        $this->helper->login($user->getUsername(), $request->get('password'));
      }

      return self::statuses('CMD_OK');
    }
  }

  public function checkURLPermission($request) {
    $key = urldecode($request->get('key'));
    $count = AuthUserQuery::create()
              ->filterByPasswordResetKey($key)
              ->count();

    if ($count == 0) {
      return false;
    }
    return true;
  }
}

?>