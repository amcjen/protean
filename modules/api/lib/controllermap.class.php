<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

class PFControllerMap { 

	protected $viewMap;
	protected $viewHeaderMap;
	protected $viewFooterMap;
	protected $forwardMap;
	protected $classrootMap;
	protected $loginMap;
	protected $permissionMap;
	protected $themeMap;

	public function __construct() {
		$this->viewMap = array();
		$this->viewHeaderMap = array();
		$this->viewFooterMap = array();
		$this->forwardMap = array();
		$this->classrootMap = array();
		$this->loginMap = array();
		$this->permissionMap = array();
		$this->themeMap = array();
	}

	public function addClassroot($command, $classroot) {
		$this->classrootMap[$command] = $classroot;
	}

	public function getClassroot($command) {
		if ($name = @$this->classrootMap[$command]) {
			return $name;
		} else {
			return $command;
		}	
	}

	public function addView($command=PF_DEFAULT_COMMAND, $status=0, $view) {
		$this->viewMap[$command][$status] = $view;
	}

	public function getView($command, $status) {
		return @$this->viewMap[$command][$status];
	}

	public function addViewHeader($command=PF_DEFAULT_COMMAND, $status=0, $viewHeader) {
		$this->viewHeaderMap[$command][$status] = $viewHeader;
	}

	public function getViewHeader($command, $status) {
		return @$this->viewHeaderMap[$command][$status];
	}

	public function addViewFooter($command=PF_DEFAULT_COMMAND, $status=0, $viewFooter) {
		$this->viewFooterMap[$command][$status] = $viewFooter;
	}

	public function getViewFooter($command, $status) {
		return @$this->viewFooterMap[$command][$status];
	}

	public function addForward($command, $status=0, $newCommand) {
		$this->forwardMap[$command][$status] = $newCommand;
	}

	public function getForward($command, $status) {
		return @$this->forwardMap[$command][$status];
	}

	public function getForwardMap($command) {
		return @$this->forwardMap[$command];
	}

	public function addLogin($command, $status=0, $login) {
		$this->loginMap[$command][$status] = $login;
	}

	public function getLogin($command, $status) {
		return @$this->loginMap[$command][$status];
	}

	public function addPermission($command, $status=0, $role) {
		$role = strtolower($role);

		if (!@in_array($role, $this->permissionMap[$command][$status])) {
			$this->permissionMap[$command][$status][] = $role;
		}
	}

	public function getPermissions($command, $status) {
		return @$this->permissionMap[$command][$status];
	}

	public function addTheme($command, $status=0, $theme) {
		$theme = strtolower($theme);

		if (!@in_array($theme, $this->themeMap[$command][$status])) {
			$this->themeMap[$command][$status] = $theme;
		}
	}

	public function GetTheme($command, $status) {
		return @$this->themeMap[$command][$status];
	}
}

?>
