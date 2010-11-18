<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
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
define('PF_VERSION','3.2');


// FILE PATHS AND URL SETTINGS
define('PF_URL', '%%PHING_PROTEAN_URL%%');
define('PF_URL_SECURE', '%%PHING_PROTEAN_SECUREURL%%');
define('PF_SHORT_URLS', true);

define('PF_ROOT_DIRECTORY', $_SERVER['DOCUMENT_ROOT']);

define('PF_USE_LOCAL_PEAR', false);
define('PF_PEAR_BASE', '/usr/local/php5/lib/php');

define('PF_TEMP_PATH', '%%PHING_PROTEAN_BASEDIR%%/tmp');

define('PF_DEFAULT_COMMAND', 'content.default');


// DEBUG SETTINGS
define('PF_APD_PROFILING', false);
define('PF_APD_TRACE_PATH', '%%PHING_PROTEAN_BASEDIR%%/logs');

define('PF_DEBUG_ALWAYS_DISPLAY_LOGGER', false);

define('PF_DEBUG_EMAIL', false);
define('PF_DEBUG_EMAIL_ADDRESS', '%%PHING_PROTEAN_DEBUG_EMAIL%%');

define('PF_DEBUG_VERBOSE', true);
define('PF_DEBUG_LOG', '%%PHING_PROTEAN_BASEDIR%%/logs/pf_error.log');

define('PF_QUERY_DEBUG', true);
define('PF_QUERY_LOG', '%%PHING_PROTEAN_BASEDIR%%/logs/pf_query.log');

define('PF_AJAX_DEBUG', false);
define('PF_AJAX_LOG', '%%PHING_PROTEAN_BASEDIR%%/logs/pf_ajax.log');

define('PF_APP_CONTROLLER_DEBUG', false);
define('PF_ERROR_WORDWRAP_COUNT', 0);

define('PF_TEMPLATE_DEBUG', false);

define('PF_MULTI_LANGUAGE_DEBUG', false);


// PROFILER SETTINGS
define('PF_PROFILER', true);
define('PF_PROFILER_MARKS', false);


// TEMPLATE/FORM SETTINGS
define('PF_TEMPLATE_FORCE_RECOMPILE', true);
define('PF_FORM_FIELD_ERROR_HIGHLIGHTING', true);


// CMS SETTINGS
define('PF_CMS_ENABLED', false);


// SESSION SETTINGS
define('PF_SESSION_NAME', 'id');
define('PF_SESSION_PERSIST_NAME', 'persist');
define('PF_SESSION_PATH', PF_TEMP_PATH);
define('PF_SESSION_EXPIRE', 1440);
define('PF_SESSION_AUTH_EXPIRE', 1440);
define('PF_SESSION_UNIQUE_KEY', 'as33#)(J**a3SS:DJLSDFU}*&W');
define('PF_JSON_UNIQUE_KEY', '$IIJSL)*SSSJ**SDasSS:DJLSDFU}*&W');


// CACHE SETTINGS
define('PF_CACHE_ENABLED', true);
define('PF_CACHE_USER_TTL', 3600);
define('PF_CACHE_CONTROLLER_MAP', false);

define('PF_CACHE_MEMCACHE_SERVER_HOST_1', false);
define('PF_CACHE_MEMCACHE_SERVER_HOST_2', false);
define('PF_CACHE_MEMCACHE_SERVER_HOST_3', false);
define('PF_CACHE_MEMCACHE_SERVER_HOST_4', false);


// MAILER SETTINGS
define('PF_EMAIL_USE_SMTP', false);
define('PF_DEFAULT_FROM_EMAIL_NAME', 'Somename');
define('PF_DEFAULT_FROM_EMAIL_ADDRESS', 'me@here.com');
define('PF_EMAIL_SERVER', 'mail.loopshot.com');
define('PF_EMAIL_SERVER_USERNAME', 'me@here.com');
define('PF_EMAIL_SERVER_PASSWORD', '');
define('PF_EMAIL_DB_USERNAME', '');
define('PF_EMAIL_DB_PASSWORD', '');
define('PF_ADD_NEW_USER_TO_MAILINGLIST', false);


// ANALYTICS SETTINGS
define('PF_ANALYTICS_TRACKING', false);
define('PF_ANALYTICS_ID', 'UA-12345-1');


// SHOP MODULE SETTINGS
define('PF_SHOP_CHECK_INVENTORY', false);
define('PF_SHOP_DEFAULT_PRODUCT_ITEM', 1);
define('PF_SHOP_SAVE_CREDITCARD_NUMBER', false);
define('PF_SHOP_FLAT_SHIPPING_FEE', false);
define('PF_SHOP_HANDLING_FEE', false);
define('PF_SHOP_EMAIL_ADMINS', false);

define('PF_SHOP_GATEWAY_DEBUG', true);
define('PF_SHOP_GATEWAY_LOG', '%%PHING_PROTEAN_BASEDIR%%/logs/pf_gateway.log');

define('PF_SHOP_GATEWAY_AUTHNET_LOGIN', '');
define('PF_SHOP_GATEWAY_AUTHNET_TRAN_KEY', '');
define('PF_SHOP_GATEWAY_AUTHNET_PASSWORD', '');
define('PF_SHOP_GATEWAY_AUTHNET_TEST_REQUEST', true);
define('PF_SHOP_GATEWAY_AUTHNET_HOST', 'secure.authorize.net');
define('PF_SHOP_GATEWAY_AUTHNET_PORT', '443');
define('PF_SHOP_GATEWAY_AUTHNET_PATH', '/gateway/transact.dll');

require_once 'modules/api/lib/common.php';
?>
