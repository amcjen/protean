<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2012, Eric Jennings.  All rights reserved.            *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

class PFForgotCommand extends PFDefaultCommand implements PFFormCommand {

  protected $helper;

  public function doExecute(PFRequest $request) {
    parent::doExecute($request);

    $this->helper = PFFactory::getInstance()->createObject('registration.userhelper');

    $form = $this->initializeForm($request);
    $this->populateForm($request, $form);
    $this->page->assign('FORM_ELEMENTS', $form->renderForm());
    return $this->handleSubmittedForm($request, $form);
  }

  public function initializeForm(PFRequest $request) {
    $form = PFFactory::getInstance()->createObject('api.form', $request, 'registration.forgot', 'reset');
    $form->setAction($this->session->getURL('registration/forgot'));
    $fields = array('email');
    $form->applyTrimFilters($fields);

    return $form;
  }

  public function populateForm(PFRequest $request, PFForm $form) { }

  public function handleSubmittedForm(PFRequest $request, PFForm $form) {

    if ($form->isSubmitted()) {
      if ($form->reportErrors()) {
        return self::statuses('CMD_INSUFFICIENT_DATA');
      }

      $con = Propel::getConnection(PF_DATABASE_NAME);
      $con->beginTransaction();

      try {
        if (!$this->helper->doesEmailExist($request->get('email'))) {
          throw new PFException('', 'The e-mail address "' . $request->get('email') . '" cannot be found.  Please try again.', E_USER_WARNING);
        }
        $this->helper->setResetPasswordKey($request->get('email'), $con);
        $con->commit();

      } catch (PFException $e) {
        $e->handleException();
        return self::statuses('CMD_ERROR');
      }

      return self::statuses('CMD_OK');
    }
  }
}
?>