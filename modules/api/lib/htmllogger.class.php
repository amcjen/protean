<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

/**
@package api
*/
class PFHTMLLogger implements PFObserver {

	public static function format($page) {
		$page->Assign('PF_HTML_LOGGER_HEADER', 'Protean Logger');	

		if ($page->get_template_vars('PF_HTML_LOGGER_BODY') == '') {
			$page->assign('PF_HTML_LOGGER_BODY', '');	
		}

		$footer = 'Protean Framework v' . PF_VERSION;

		if (isset($_SERVER['SERVER_NAME'])) {
			$footer .= ' running on ' . @$_SERVER['SERVER_NAME'];
		}

		$page->assign('PF_HTML_LOGGER_FOOTER', $footer);	
	}

	public function update(PFObservable $observer) {

		$debugStack = $observer->getDebugStack();

		$lastMsg = end($debugStack);
		PFTemplateHelper::getInstance()->append('PF_HTML_LOGGER_BODY', $lastMsg['message'] . '<br /><small>at ' . basename($lastMsg['file']) . ':' . $lastMsg['line'] . '</small><br /><br />');
	}
}

?>