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
class PFControllerMap { 

	protected $viewMap;
	protected $viewHeaderMap;
	protected $viewFooterMap;
	protected $forwardMap;
	protected $commandMap;
	protected $loginMap;
	protected $permissionMap;
	protected $themeMap;

	public function __construct() {
		$this->viewMap = array();
		$this->viewHeaderMap = array();
		$this->viewFooterMap = array();
		$this->forwardMap = array();
		$this->commandMap = array();
		$this->loginMap = array();
		$this->permissionMap = array();
		$this->themeMap = array();
	}

	public function addCommand($uri, $command) {
		$this->commandMap[$uri] = $command;
	}

	public function getCommand($uri) {
		return @$this->commandMap[$uri];
	}

	public function addView($uri, $status=0, $view) {
		if (!isset($uri)) {
			$uri = PF_DEFAULT_URI . '|get';
		}
		$this->viewMap[$uri][$status] = $view;
	}

	public function getView($uri, $status) {
		return @$this->viewMap[$uri][$status];
	}

	public function addViewHeader($uri, $status=0, $viewHeader) {
		if (!isset($uri)) {
			$uri = PF_DEFAULT_URI . '|get';
		}
		$this->viewHeaderMap[$uri][$status] = $viewHeader;
	}

	public function getViewHeader($uri, $status) {
		return @$this->viewHeaderMap[$uri][$status];
	}

	public function addViewFooter($uri, $status=0, $viewFooter) {
		if (!isset($uri)) {
			$uri = PF_DEFAULT_URI . '|get';
		}
		$this->viewFooterMap[$uri][$status] = $viewFooter;
	}

	public function getViewFooter($uri, $status) {
		return @$this->viewFooterMap[$uri][$status];
	}

	public function addForward($uri, $status=0, $newCommand) {
		$this->forwardMap[$uri][$status] = $newCommand;
	}

	public function getForward($uri, $status) {
		$uriVerbArray = explode('|', $uri);

		$uri = PFRequestHelper::getURIPattern($uriVerbArray[0]);

		if (count($uriVerbArray) > 1) {
			$uri = $uri . '|' . $uriVerbArray[1];
		}

		return @$this->forwardMap[$uri][$status];
	}

	public function getForwardMap($uri) {
		return @$this->forwardMap[$uri];
	}

	public function addLogin($uri, $status=0, $login) {
		$this->loginMap[$uri][$status] = $login;
		$this->addForward($uri, 5, '/registration/login');
	}

	public function getLogin($uri, $status) {
		return @$this->loginMap[$uri][$status];
	}

	public function addPermission($uri, $status=0, $role) {
		$role = strtolower($role);
		if (!@in_array($role, $this->permissionMap[$uri][$status])) {
			$this->permissionMap[$uri][$status][] = $role;
		}
	}

	public function getPermissions($uri, $status) {
		return @$this->permissionMap[$uri][$status];
	}

	public function addTheme($uri, $status=0, $theme) {
		$theme = strtolower($theme);
		if (!@in_array($theme, $this->themeMap[$uri][$status])) {
			$this->themeMap[$uri][$status] = $theme;
		}
	}

	public function getTheme($uri, $status) {
		return @$this->themeMap[$uri][$status];
	}
}

?>