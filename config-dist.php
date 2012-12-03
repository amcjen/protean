<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2011, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/


// GENERAL SETTINGS
define('PF_SITE_NAME', '%%PHING_PROTEAN_LONGNAME%%');
define('PF_DATABASE_NAME', '%%PHING_PROTEAN_DATABASE_NAME%%');

define('PF_SCRIPT_TIMEOUT', 30);
define('PF_TIMEZONE', 'America/Los_Angeles');


// BUILD SETTINGS
define('PF_VERSION','4.1');
define('PF_ENVIRONMENT', 'development');


// FILE PATHS AND URL SETTINGS
define('PF_URL', '%%PHING_PROTEAN_URL%%');
define('PF_URL_SECURE', '%%PHING_PROTEAN_SECUREURL%%');
define('PF_SHORT_URLS', true);

define('PF_USE_LOCAL_PEAR', false);
define('PF_PEAR_BASE', '/usr/local/php5/lib/php');

define('PF_TEMP_PATH', '%%PHING_PROTEAN_BASEDIR%%/tmp');

define('PF_DEFAULT_URI', '/content/default');


// DEBUG SETTINGS
define('PF_PROFILER', true);
define('PF_PROFILER_RUNS_PATH', '%%PHING_PROTEAN_BASEDIR%%/tmp/profiler');

define('PF_DEBUG_EMAIL', false);
define('PF_DEBUG_EMAIL_ADDRESS', '%%PHING_PROTEAN_DEBUG_EMAIL%%');

define('PF_DEBUG_VERBOSE', true);
define('PF_DEBUG_LOG', '%%PHING_PROTEAN_BASEDIR%%/logs/pf_error.log');

define('PF_QUERY_DEBUG', true);
define('PF_QUERY_LOG', '%%PHING_PROTEAN_BASEDIR%%/logs/pf_query.log');

define('PF_AJAX_DEBUG', false);
define('PF_AJAX_LOG', '%%PHING_PROTEAN_BASEDIR%%/logs/pf_ajax.log');

define('PF_APP_ROUTER_DEBUG', false);
define('PF_APP_CONTROLLER_DEBUG', false);
define('PF_ERROR_WORDWRAP_COUNT', 0);

define('PF_TEMPLATE_DEBUG', false);
define('PF_MULTI_LANGUAGE_DEBUG', false);


// TEMPLATE/FORM SETTINGS
define('PF_TEMPLATE_FORCE_RECOMPILE', false);
define('PF_FORM_FIELD_ERROR_HIGHLIGHTING', true);


// SESSION SETTINGS
define('PF_SESSION_NAME', 'id');
define('PF_SESSION_PERSIST_NAME', 'persist');
define('PF_SESSION_STORE', 'file');
define('PF_SESSION_PATH', PF_TEMP_PATH);
define('PF_SESSION_EXPIRE', 31536000);
define('PF_SESSION_AUTH_EXPIRE', 31536000);
define('PF_SESSION_UNIQUE_KEY', 'as33#)(J**a3SS:DJLSDFU}*&W');
define('PF_SESSION_PASSWORD_HASH_COST', 8);


// CACHE SETTINGS
define('PF_CACHE_ENABLED', true);
define('PF_CACHE_USER_TTL', 3600);
define('PF_CACHE_CONTROLLER_MAP', false);
define('PF_CACHE_TEMPLATES', false);

define('PF_CACHE_MEMCACHE_SERVER_HOST_1', false);
define('PF_CACHE_MEMCACHE_SERVER_HOST_2', false);
define('PF_CACHE_MEMCACHE_SERVER_HOST_3', false);
define('PF_CACHE_MEMCACHE_SERVER_HOST_4', false);

define('PF_CDN_URL', '%%PHING_PROTEAN_URL%%');


// JOB QUEUE SETTINGS
define('PF_JOBQUEUE_HOST', '127.0.0.1');
define('PF_JOBQUEUE_PORT', 11300);
define('PF_JOBQUEUE_CONN_TIMEOUT', null);


// MAILER SETTINGS
define('PF_EMAIL_USE_SMTP', false);
define('PF_DEFAULT_FROM_EMAIL_NAME', 'Somename');
define('PF_DEFAULT_FROM_EMAIL_ADDRESS', 'me@here.com');
define('PF_EMAIL_SERVER', 'mail.loopshot.com');
define('PF_EMAIL_SERVER_USERNAME', 'me@here.com');
define('PF_EMAIL_SERVER_PASSWORD', '');
define('PF_EMAIL_DB_USERNAME', '');
define('PF_EMAIL_DB_PASSWORD', '');
define('PF_EMAIL_DEBUG', false);
define('PF_EMAIL_DEBUG_LOG', '%%PHING_PROTEAN_BASEDIR%%b/logs/pf_mailer.log');
define('PF_ADD_NEW_USER_TO_MAILINGLIST', false);


// SMS TEXT SETTINGS
define('PF_SMS_TWILIO_CLIENT_ID', 'aaa');
define('PF_SMS_TWILIO_AUTH_TOKEN', 'aaa');
define('PF_SMS_FROM_NUMBER', '555-555-1234');
define('PF_SMS_DEBUG', true);
define('PF_SMS_LOG', '%%PHING_PROTEAN_BASEDIR%%/logs/pf_sms_message.log');


// THIRD-PARTY INCLUDES SETTINGS
define('PF_INCLUDE_GA_TRACKING', false);
define('PF_INCLUDE_SNAPENGAGE', false);
define('PF_INCLUDE_GET_SATISFACTION', false);


// SHOP MODULE SETTINGS
define('PF_SHOP_CHECK_INVENTORY', false);
define('PF_SHOP_DEFAULT_PRODUCT_ITEM', 1);
define('PF_SHOP_SAVE_CREDITCARD_NUMBER', false);
define('PF_SHOP_FLAT_SHIPPING_FEE', false);
define('PF_SHOP_HANDLING_FEE', false);
define('PF_SHOP_EMAIL_ADMINS', false);

define('PF_SHOP_GATEWAY_DEBUG', true);
define('PF_SHOP_GATEWAY_LOG', '%%PHING_PROTEAN_BASEDIR%%/logs/pf_gateway.log');

define('PF_SHOP_GATEWAY_AUTHNET_TEST_REQUEST', true);
define('PF_SHOP_GATEWAY_AUTHNET_LOGIN', 'abc');
define('PF_SHOP_GATEWAY_AUTHNET_TRAN_KEY', 'abc');
define('PF_SHOP_GATEWAY_AUTHNET_TEST_LOGIN', 'abc');
define('PF_SHOP_GATEWAY_AUTHNET_TEST_TRAN_KEY', 'abc');
define('PF_SHOP_GATEWAY_AUTHNET_CIM_HOST_PRODUCTION', 'api.authorize.net');
define('PF_SHOP_GATEWAY_AUTHNET_CIM_HOST_TEST', 'apitest.authorize.net');
define('PF_SHOP_GATEWAY_AUTHNET_CIM_PORT', '443');
define('PF_SHOP_GATEWAY_AUTHNET_CIM_PATH', '/xml/v1/request.api');

define('PF_SHOP_GATEWAY_STRIPE_TEST_REQUEST', true);
define('PF_SHOP_GATEWAY_STRIPE_TEST_SECRET_KEY', 'D7bES9biF1eeGhBENJrnQO6tDVMrnt5o');
define('PF_SHOP_GATEWAY_STRIPE_TEST_PUBLISH_KEY', 'pk_3GALjNFDV4KXTd4QPkLBibsHpXkar');
define('PF_SHOP_GATEWAY_STRIPE_LIVE_SECRET_KEY', 'sTHNScUGnEXIhVcmiibWrSPYCevHlx1Y');
define('PF_SHOP_GATEWAY_STRIPE_LIVE_PUBLISH_KEY', 'pk_sn1AJYs7zVjkWG6ubFBj2rpMFy9Ko');
define('PF_SHOP_GATEWAY_STRIPE_SCRIPT', 'https://js.stripe.com/v1/');

define('PF_SHOP_GATEWAY_PAYPAL_TEST_REQUEST', true);
define('PF_SHOP_GATEWAY_PAYPAL_LOG', '/www/standard/www.missouriquiltco.com/logs/pf_paypal.log');
define('PF_SHOP_GATEWAY_PAYPAL_SELLER_EMAIL', 'ericj_1354059916_biz@loopshot.com');

define('PF_USPS_RATECALCULATOR_USERNAME', '493MISSO0374');

define('PF_ES_HOST_1', 'localhost');
define('PF_ES_HOST_2', false);
define('PF_ES_HOST_3', false);
define('PF_ES_HOST_4', false);

// SITE SPECIFIC DEFINES


require_once 'modules/api/lib/common.php';
?>
