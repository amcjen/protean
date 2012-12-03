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
class PFRequestHelper {

  public static function getDefaultURIApplication() {
    $default = explode('/', PF_DEFAULT_URI);
    return $default[1];
  }

  public static function getCurrentURIApplication() {
     return self::getURIApplication(self::getCurrentURIPattern(), self::getHTTPVerb());
  }

  public static function getURIApplication($uri, $verb) {

    if (empty($uri)) {
      $uri = PF_DEFAULT_URI;
    }

    if (empty($verb)) {
      $verb = 'get';
    }

    $uriArray = explode('/', $uri);
    $app = $uriArray[1];

    $appController = PFApplicationHelper::getInstance()->appController();
    $controllerMap = $appController->getControllerMap($app);
    $uriArray = explode('.', $controllerMap->getCommand($uri . '|' . $verb));

    return @$uriArray[0];
  }

  public static function getDefaultURICommand() {
    $default = explode('/', PF_DEFAULT_URI);
    return $default[2];
  }

  public static function getCurrentURICommand() {
    return self::getURICommand(self::getCurrentURIPattern(), self::getHTTPVerb());
  }

  public static function getURICommand($uri, $verb) {

    if (empty($uri)) {
      $uri = PF_DEFAULT_URI;
    }

    if (empty($verb)) {
      $verb = 'get';
    }

    $urlArray = explode('?', $uri);
    $uri = $urlArray[0];

    $uriArray = explode('/', $uri);
    $app = $uriArray[1];

    $controllerMap = PFApplicationHelper::getInstance()->appController()->getControllerMap($app);
    $uriArray = explode('.', $controllerMap->getCommand($uri . '|' . $verb));

    return @$uriArray[1];
  }

  public static function getCurrentURIPattern() {
    if (array_key_exists('REQUEST_URI', $_SERVER)) {
      $uriArray = explode('?', $_SERVER['REQUEST_URI']);
      $uri = @$uriArray[0];
    } else {
      $uri = @$_SERVER['REQUEST_URI'];
    }
    
    // FIXME: suuuucks doing this, but we gotta get it working, and can't refactor wildcards into protean core right now
    $uri = preg_replace('/\/shop\/detail\/(\d+)\/(.*)/', '/shop/detail/$1', $uri);

    return self::getURIPattern(@$uri . '|' . self::getHTTPVerb());
  }

  public static function getURIPattern($uri) {
    $uri = explode('|', $uri);
    $uriArray = explode('/', $uri[0]);

    $uriQueryStringArray = explode('?', @$uriArray[count($uriArray)-1]);
    $uriArray[count($uriArray)-1] = $uriQueryStringArray[0];
    if (empty($uriQueryStringArray[0])) {
      array_pop($uriArray);
    }
    array_shift($uriArray);

    $uri = '/';
    
    foreach ($uriArray as $uriElement) {
      if (is_numeric($uriElement)) {
        $uri .= ':integer:/';
      } else {
        $uri .= $uriElement . '/';
      }
    }

    return substr($uri, 0, -1);
  }

  public static function getCurrentURI() {
    $uriArray = explode('?', @$_SERVER['REQUEST_URI']);
    $uri = $uriArray[0];

    if (substr($uri, -1) == '/') {
      $uri = substr($uri, 0, -1);
    }
    return $uri;
  }

  public static function getRESTSlot($indexOffset) {
    $uriArray = explode('/', @$_SERVER['REQUEST_URI']);
    $uriArray = explode('?', @$uriArray[2 + $indexOffset]);
    return @$uriArray[0];
  }

  public static function getRESTSlotForURIPattern($indexOffset, &$request) {
    $uriArray = $request->get('pf.uri');
    $uriArray = explode('|', $uriArray);
    $uriArray = explode('/', $uriArray[0]);
    $uriArray = explode('?', @$uriArray[2 + $indexOffset]);
    return @$uriArray[0];
  }

  public static function getHTTPVerb() {
    $verb = @strtolower($_SERVER['REQUEST_METHOD']);
    if (empty($verb)) {
      $verb = 'get';
    }
    return $verb;
  }

  public static function resetHTTPVerb() {
    $_SERVER['REQUEST_METHOD'] = 'get';
  }

  public static function getHTTPVerbForURIPattern($uri) {

    @list($uri, $verb) = explode('|', $uri);
    if (empty($verb)) {
      $verb = 'get';
    }
    return $verb;
  }
}

?>