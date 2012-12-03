<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2012, Eric Jennings.  All rights reserved.            *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

class PFLogoutCommand extends PFDefaultCommand { 

	public function doExecute(PFRequest $request) {	
		parent::doExecute($request);
		$user = PFFactory::getInstance()->createObject('registration.userhelper');	
		$user->logout();
		$this->assignDefaults($request);
		return self::statuses('CMD_OK');
	}
}

?>