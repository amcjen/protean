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
require_once ('modules/thirdparty/phpass/PasswordHash.php');

class PFRegistrationUserHelper {

  protected $mailer;
  protected $session;

  public function __construct() {
    $this->mailer = PFFactory::getInstance()->createObject('api.mailer');
    $this->session = PFSession::getInstance()  ;
    PFFactory::getInstance()->initObject('api.template');
  }

  public function login($username, $password, $persistent=false, $admin=false) {
    $user = AuthUserQuery::create()
              ->filterByUsername(strtolower($username))
              ->joinWith('AuthUser.Party')
              ->findOne();

    if (!is_object($user)) {
      throw new PFException('registration', 'USERNAME_NOT_FOUND', E_USER_WARNING);
    }

    if ($admin == false) {
      $hasher = new PasswordHash(PF_SESSION_PASSWORD_HASH_COST, false);
      if (!$hasher->checkPassword($password, $user->getPassword())) {
        // try oldschool MD5 hash password for backwards compatibility
        $md5PassSalt = explode(':', $user->getMd5Password());
        $md5Pass = $md5PassSalt[0];
        $md5Salt = $md5PassSalt[1];
      
        if ($md5Pass != md5($md5Salt . $password)) {
          throw new PFException('registration', 'INVALID_PASSWORD', E_USER_WARNING);
        } else {
          $user->setPassword($hasher->hashPassword($password));
          $user->setMd5Password(null);
          $user->save($con);
        }
      }
      unset($hasher);
      
      if ($persistent) {
        $this->session->setPersistentCookie(525600);
      }
    }

    $this->session->login();

    $this->session->register('user_id', $user->getAuthUserId());
    $this->session->register('user_party_id', $user->getPartyId());
    $this->session->register('user_fullname', $user->getParty()->getFirstName() . ' ' . $user->getParty()->getLastName());
    $this->session->register('user_firstname', $user->getParty()->getFirstName());
    $this->session->register('user_lastname', $user->getParty()->getLastName());
    $this->session->register('user_username', $user->getUsername());
    $this->session->register('user_email', $user->getParty()->getEmail());
    $this->session->register('user_expires', $user->getExpires());
    $this->session->register('user_status', $user->getStatus());

    $this->getAndStoreUserRoles($user->getAuthUserId());

    try {
      $user->setLastLogin(mktime());
      $user->setLastLoginFrom(@$_SERVER['REMOTE_ADDR']);
      $user->save();
    } catch (PFException $e) {
      throw new PFException('', 'Error saving login information', E_USER_WARNING);
    }

    $this->assignDefaults();
    return true;
  }

  public function assignDefaults() {

    $page = PFRegistry::getInstance()->getPage();
    if (!isset($page)) {
      $page = PFTemplateHelper::getInstance();
    }

    $page->assign('CURRENTLY_LOGGED_IN', $this->session->isLoggedIn());
    $page->assign('CURRENTLY_LOGGED_IN_AS', $this->session->retrieve('user_firstname'));
    $page->assign('IS_ADMIN', $this->doesCurrentUserHavePermission('admin'));
  }

  public function logout() {
    $this->session->logout(true);
    $this->assignDefaults();
  }

  public function setResetPasswordKey($email, $con) {

    $user = $this->getUserFromEmail($email);
    $key = sha1(time() . 'rrreeessseeett' . PF_SESSION_UNIQUE_KEY);
    if ($user) {
      $user->setPasswordResetKey($key);
      $user->save($con);

      $this->sendResetPasswordEmail($email, $key);
    } else {
      throw new PFException('', 'Error resetting password key.  No user exists with that email.', E_USER_WARNING);
    }
  }

  public function resetPassword($password, $key, $con=NULL) {

    $user = $this->getUserFromPasswordResetKey($key);

    if ($user) {
      $hasher = new PasswordHash(PF_SESSION_PASSWORD_HASH_COST, false);
      $user->setPassword($hasher->hashPassword($password));
      unset($hasher);
      $user->setPasswordResetKey($key);
      $user->setPasswordResetKey(NULL);
      $user->save($con);
    }
  }

  public function doesUsernameExist($username) {

    $count = AuthUserQuery::create()
            ->filterByUsername(strtolower($username))
            ->count();

    if ($count > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function doesEmailExist($email) {

    $count = AuthUserQuery::create()
            ->joinWith('AuthUser.Party')
            ->usePartyQuery()
              ->filterByEmail(strtolower($email))
            ->endUse()
            ->count();

    if ($count > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function getUser($authUserId) {
    return AuthUserQuery::create()
            ->joinWith('AuthUser.Party')
            ->joinWith('Party.PartyLocaleLocationXref', Criteria::LEFT_JOIN)
            ->joinWith('PartyLocaleLocationXref.LocaleLocation', Criteria::LEFT_JOIN)
            ->joinWith('Party.ShopOrderPaymentMethod', Criteria::LEFT_JOIN)
            ->filterByAuthUserId($authUserId)
            ->findOne();
  }

  public function getUserFromUsername($username) {
    return AuthUserQuery::create()
            ->joinWith('AuthUser.Party')
            ->joinWith('Party.PartyLocaleLocationXref', Criteria::LEFT_JOIN)
            ->joinWith('PartyLocaleLocationXref.LocaleLocation', Criteria::LEFT_JOIN)
            ->joinWith('Party.ShopOrderPaymentMethod', Criteria::LEFT_JOIN)
            ->filterByUsername(strtolower($username))
            ->findOne();
  }

  public function getUserFromEmail($email) {
    return AuthUserQuery::create()
            ->joinWith('AuthUser.Party')
            ->joinWith('Party.PartyLocaleLocationXref', Criteria::LEFT_JOIN)
            ->joinWith('PartyLocaleLocationXref.LocaleLocation', Criteria::LEFT_JOIN)
            ->joinWith('Party.ShopOrderPaymentMethod', Criteria::LEFT_JOIN)
            ->usePartyQuery()
              ->filterByEmail(strtolower($email))
            ->endUse()
            ->findOne();
  }

  public function getUserFromPartyId($partyId) {
    return AuthUserQuery::create()
            ->joinWith('AuthUser.Party')
            ->joinWith('Party.PartyLocaleLocationXref', Criteria::LEFT_JOIN)
            ->joinWith('PartyLocaleLocationXref.LocaleLocation', Criteria::LEFT_JOIN)
            ->joinWith('Party.ShopOrderPaymentMethod', Criteria::LEFT_JOIN)
            ->filterByPartyId($partyId)
            ->findOne();
  }

  public function getUserFromPasswordResetKey($key) {
    return AuthUserQuery::create()
            ->joinWith('AuthUser.Party')
            ->joinWith('Party.PartyLocaleLocationXref', Criteria::LEFT_JOIN)
            ->joinWith('PartyLocaleLocationXref.LocaleLocation', Criteria::LEFT_JOIN)
            ->joinWith('Party.ShopOrderPaymentMethod', Criteria::LEFT_JOIN)
            ->filterByPasswordResetKey($key)
            ->findOne();
  }

  public function getAndStoreUserRoles($userID) {
    $permissionRoles = array();

    $c = new Criteria();
    $c->add(AuthUserRoleXrefPeer::AUTH_USER_ID, $userID);
    $roles = AuthUserRoleXrefPeer::doSelectJoinAll($c);

    foreach($roles as $role) {
      $key = $role->getAuthRole()->getRoleKey();
      $permissionRoles[$key]['name'] = $role->getAuthRole()->getName();
      $permissionRoles[$key]['status'] = $role->getAuthRole()->getStatus();
      $permissionRoles[$key]['expires'] = $role->getAuthRole()->getExpires();
    }

    $roles = AuthUserGroupRoleXrefPeer::getAllRolesForUserInGroup($userID);

    for ($i=0; $i<count($roles); $i++) {
      $key = $roles[$i]['key'];
      $permissionRoles[$key]['name'] = $roles[$i]['name'];
      $permissionRoles[$key]['status'] = $roles[$i]['status'];
      $permissionRoles[$key]['expires'] = $roles[$i]['expires'];
    }

    $this->session->register('user_roles', $permissionRoles);
    $this->assignDefaults();
  }

  public function doesCurrentUserHavePermission($roles) {

    $userRoles = $this->session->get('user_roles');

    // if no user roles, make it an empty array
    if (!$userRoles) {
      $userRoles = array();
    }

    // if no roles are given from the command.xml file, there are no permission locks on the page
    if (empty($roles)) {
      return true;
    }

    // convert to array if $roles was a single item, so the foreach can loop over it
    if (!is_array($roles)) {
      $roles = array($roles);
    }

    // printr($roles);
    // printr($userRoles);
    foreach ($roles as $role) {
      if (array_key_exists($role, $userRoles)) {
        if ($userRoles[$role]['status'] == 'A' &&
            ($userRoles[$role]['expires'] == '' || ($userRoles[$role]['expires'] > mktime()))) {
          return true;
        }
      }
    }

    return false;
  }

  public function getOrderHistoryForParty($partyId, $limit=10) {

    return ShopOrderQuery::create()
            ->filterByPartyId($partyId)
            ->orderByCreatedAt(Criteria::DESC)
            ->limit($limit)
            ->find();
  }

  public function setStatus($userId, $status, $timestamp) {

    $user = AuthUserPeer::retrieveByPk($userId);

    if (is_object($user)) {

      $user->setStatus($status);
      $user->setUpdatedAt($timestamp);
      $user->save();

      // Update session vars if this user is us
      if ($userId == $this->session->retrieve('user_id')) {
        $this->session->register('user_expires', $user->getExpires());
        $this->session->register('user_status', $user->getStatus());
      }
      return true;

    } else {
      return false;
    }
  }

  public function getStatus($userId) {

    $user = AuthUserPeer::retrieveByPk($userId);

    if (is_object($user)) {
    return ($user->getStatus());
    } else {
      return false;
    }
  }

  public function createUser($request) {

    $con = Propel::getConnection(PF_DATABASE_NAME);

    try {
      $con->beginTransaction();

      if ($this->doesUsernameExist($request->get('email'))) {
        throw new PFException('registration', 'USERNAME_EXISTS', E_USER_WARNING);
      }

      if (trim($request->get('password')) != trim($request->get('password2'))) {
        throw new PFException('registration', array('PASSWORDS_DONT_MATCH'), E_USER_WARNING);
      }

      $party = PFFactory::getInstance()->createPropelObject('Party');
      $user = PFFactory::getInstance()->createPropelObject('AuthUser');
      $shippingAddress = PFFactory::getInstance()->createPropelObject('LocaleLocation');
      $shippingAddressXref = PFFactory::getInstance()->createPropelObject('PartyLocaleLocationXref');

      $party->setFirstName($request->get('firstname'));
      $party->setLastName($request->get('lastname'));
      $party->setEmail(strtolower($request->get('email')));
      $party->setTelephone(strtolower($request->get('telephone')));
      $party->save($con);

      $user->setUsername(strtolower($request->get('email')));
      $hasher = new PasswordHash(PF_SESSION_PASSWORD_HASH_COST, false);
      $user->setPassword($hasher->hashPassword($request->get('password')));
      unset($hasher);
      $user->setStatus('A');
      $user->setPartyId($party->getPartyId());
      $user->save($con);

      $shippingAddress->setName($request->get('shippingaddress-name'));
      $shippingAddress->setAddress1($request->get('shippingaddress-address1'));
      $shippingAddress->setAddress2($request->get('shippingaddress-address2'));
      $shippingAddress->setAddress3($request->get('shippingaddress-address3'));
      $shippingAddress->setCity($request->get('shippingaddress-city'));
      $shippingAddress->setRegion($request->get('shippingaddress-region'));
      $shippingAddress->setPostalCode($request->get('shippingaddress-postalcode'));
      $shippingAddress->setLocaleCountry($request->get('shippingaddress-country'));
      $shippingAddress->save($con);

      $shippingAddressXref->setPartyId($party->getPartyId());
      $shippingAddressXref->setLocaleLocationId($shippingAddress->getLocaleLocationId());
      $shippingAddressXref->setType('shipping');
      $shippingAddressXref->save($con);

      $con->commit();
    } catch (PFException $e) {
      $con->rollback();
      throw $e;
    }

    return $user->getAuthUserId();
  }

  public function updateUser($userId, $request) {

    $con = Propel::getConnection(PF_DATABASE_NAME);

    try {
      $con->beginTransaction();

      $user = AuthUserQuery::create()
              ->joinWith('AuthUser.Party')
              ->joinWith('Party.ShopOrderPaymentMethod', Criteria::LEFT_JOIN)
              ->filterByAuthUserId($userId)
              ->findOne($con);
      if (!$user) {
        throw new PFException('registration', array('ID_NOT_FOUND', $userId), E_USER_WARNING);
      }

      if ($request->isPropertySet('user-emailaddress') && $this->doesUsernameExist($request->get('user-emailaddress'))) {
        $otherUser = $this->getUserFromUsername($request->get('user-emailaddress'));
        if ($otherUser->getAuthUserId() != $userId) {
          throw new PFException('registration', 'USERNAME_EXISTS', E_USER_WARNING);
        }
      }

      $party = $user->getParty();

      if ($request->isPropertySet('billingaddress-postalcode')) {

        $billingAddress = PFFactory::getInstance()->createPropelObject('LocaleLocation');
        $billingAddress->setName($request->get('billingaddress-name'));
        // $billingAddress->setAddress1($request->get('billingaddress-address1'));
        // $billingAddress->setAddress2($request->get('billingaddress-address2'));
        // $billingAddress->setAddress3($request->get('billingaddress-address3'));
        // $billingAddress->setCity($request->get('billingaddress-city'));
        // $billingAddress->setRegion($request->get('billingaddress-region'));
        $billingAddress->setPostalCode($request->get('billingaddress-postalcode'));
        // $billingAddress->setLocaleCountryId($request->isPropertySet('billingaddress-country')?$request->get('billingaddress-country'):NULL);
        if (!LocaleLocation::isBillingAddressComplete($billingAddress)) {
          throw new PFException('registration', 'BILLING_ADDRESS_INCOMPLETE', E_USER_WARNING);
        }
        $billingAddress->save($con);
        $party->setBillingAddress($billingAddress->getLocaleLocationId());
      }

      if ($request->isPropertySet('shippingaddress-address1') ||
          $request->isPropertySet('shippingaddress-city') ||
          $request->isPropertySet('shippingaddress-region') ||
          $request->isPropertySet('shippingaddress-postalcode')) {

        $shippingAddress = PFFactory::getInstance()->createPropelObject('LocaleLocation');
        $shippingAddress->setName($request->get('shippingaddress-name'));
        $shippingAddress->setAddress1($request->get('shippingaddress-address1'));
        $shippingAddress->setAddress2($request->get('shippingaddress-address2'));
        $shippingAddress->setAddress3($request->get('shippingaddress-address3'));
        $shippingAddress->setCity($request->get('shippingaddress-city'));
        $shippingAddress->setRegion($request->get('shippingaddress-region'));
        $shippingAddress->setPostalCode($request->get('shippingaddress-postalcode'));
        $shippingAddress->setLocaleCountry($request->get('shippingaddress-country'));
        if (!LocaleLocation::isShippingAddressComplete($shippingAddress)) {
          throw new PFException('registration', 'BILLING_ADDRESS_INCOMPLETE', E_USER_WARNING);
        }
        $shippingAddress->save($con);
        $party->setShippingAddress($shippingAddress->getLocaleLocationId());
      }

      if ($request->isPropertySet('firstname') || $request->isPropertySet('lastname')) {
        $party->setFirstName($request->get('firstname'));
        $party->setLastName($request->get('lastname'));
      }

      if ($request->isPropertySet('user-emailaddress')) {
        $party->setEmail(strtolower($request->get('user-emailaddress')));
      }

      if ($request->isPropertySet('telephone')) {
        if (!$party->validateTelephone($request->get('telephone'))) {
          throw new PFException('registration', array('INVALID_TELEPHONE'), E_USER_WARNING);
        }

        $party->setTelephone(strtolower($request->get('telephone')));
      }
      $party->save($con);

      if ($request->isPropertySet('user-password')) {

        if (trim($request->get('user-password')) != trim($request->get('user-passwordconfirm'))) {
          throw new PFException('registration', array('PASSWORDS_DONT_MATCH'), E_USER_WARNING);
        }

        $hasher = new PasswordHash(PF_SESSION_PASSWORD_HASH_COST, false);
        $user->setPassword($hasher->hashPassword(trim($request->get('user-password'))));
        unset($hasher);
      }
      if ($request->isPropertySet('user-emailaddress')) {
        $user->setUsername(strtolower($request->get('user-emailaddress')));
      }
      $user->save($con);
      
      /* TODO if needed for Stripe card saving
      // CIM
      $authNetCim = PFFactory::getInstance()->createObject('registration.authnetcim');
      $authNetCim->doCustomerProfile($party);

      if ($authNetCim->isError()) {
        throw new PFException('', $authNetCim->getErrorMessage(), E_USER_WARNING);
      }

      if ($authNetCim->getCustomerProfileID() && !$party->getAuthNetProfileId()) {
        $party->setAuthNetProfileId($authNetCim->getCustomerProfileID());
        $party->save($con);
      }

      // save regardless here, b/c AuthNet interaction
      $con->commit();

      $paymentMethod = $this->updatePaymentMethod($party, $request);

      if ($paymentMethod) {
        $party->addShopOrderPaymentMethod($paymentMethod);
        $authNetCim->doPaymentProfile($party);

        if ($authNetCim->isError()) {
          throw new PFException('', $authNetCim->getErrorMessage(), E_USER_WARNING);
        }

        if ($authNetCim->getPaymentProfileId()) {
          $paymentMethod->setAuthNetPaymentProfileId($authNetCim->getPaymentProfileId());
          $paymentMethod->save();
        }
      }
      */
      $con->commit();
    } catch (Exception $e) {
      $con->rollback();
      throw $e;
    }

    return $user->getAuthUserId();
  }

  private function updatePaymentMethod($party, $request) {
    $ccNum = $request->get('paymentinfo-number');
    $ccType = $request->get('paymentinfo-type');
    $ccCCV = $request->get('paymentinfo-ccv');
    $ccExpMon = $request->get('paymentinfo-expirationmonth');
    $ccExpYear = $request->get('paymentinfo-expirationyear');

    if ($ccNum && $ccType && $ccCCV && $ccExpMon && $ccExpYear) {
      $paymentMethod = $party->getFirstCreditPaymentMethod();
      if($paymentMethod === null) {
        $paymentMethod = new ShopOrderPaymentMethod();
        $paymentMethod->setPartyId($party->getPartyId());
      }
      $paymentMethod->setLocaleLocationIdBilling($party->getBillingAddress()->getLocaleLocationId());
      $paymentMethod->setType($ccType);
      $paymentMethod->setName($party->getBillingAddress()->getName());
      $paymentMethod->setNumber($ccNum);
      $paymentMethod->setCcv($ccCCV); // Note this won't actually save it's more for the auth net class, it's blanked during save()
      $paymentMethod->setExpirationDate(strtotime($ccExpYear . '-' . $ccExpMon . '-01'));
      $paymentMethod->setActive(true);
      return $paymentMethod;
    }
    return false;
  }

  public function sendResetPasswordEmail($email, $key) {

    $this->mailer->addAddress($email);
    $this->mailer->Subject = PF_SITE_NAME . ' - Password Reset Request';
    $this->mailer->From = PF_DEFAULT_FROM_EMAIL_ADDRESS;
    $this->mailer->FromName = PF_DEFAULT_FROM_EMAIL_NAME;

    $emailPage = new PFTemplate('registration');
    $emailPage->assign('key', urlencode($key));

    //$html = $emailPage->fetch('registration', 'email-resetpassword-html.tpl');
    $plain = $emailPage->fetch('registration', 'email-resetpassword-txt.tpl');

    //$this->mailer->Body = $html;
    //$this->mailer->AltBody = $plain;

    $this->mailer->Body = $plain;

    try {
      if ($this->mailer->send()) {
        return true;
      }

      $this->mailer->logError();

    } catch (Exception $e) {
      $e = PFException::enrich($e);
      $e->handleException();
      $this->mailer->logError();
    }
  }
}
?>
