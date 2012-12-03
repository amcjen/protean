<?php
/**************************************************************************\
* Protean Framework                                                        *
* http://www.loopshot.com                                                  *
* Written by:  Eric Jennings <ericj@loopshot.com>                          *
* Copyright 2006-2010 Eric Jennings                                        *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the GNU Lesser General Public License as published   *
*  by the Free Software Foundation; either version 2 of the License, or    *
*  (at your option) any later version.                                     *
\**************************************************************************/
	
class PFUnshadowCommand extends PFDefaultCommand { 

	public function doExecute(PFRequest $request) {		
		parent::doExecute($request);
		
		$this->session->unregister('shadow_party_id');
		$this->session->unregister('shadow_party_name');
		
		$userHelper = PFFactory::getInstance()->createObject('registration.userhelper');
		$userHelper->login($this->session->retrieve('shadow_original_username'), '', false, true);
		
		$this->redirectTo('/shop/admin');
	}
}

?>