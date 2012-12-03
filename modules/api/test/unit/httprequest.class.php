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
class PFHttpRequest {

  public function get($url, $credentials, $status=null, $wait=3) {
    return $this->request('get', $url, $credentials, null, $status, $wait);
  }

  public function post($url, $credentials, $data, $status=null, $wait=3) {
    return $this->request('post', $url, $credentials, $data, $status, $wait);
  }

  public function put($url, $credentials, $data, $status=null, $wait=3) {
    return $this->request('put', $url, $credentials, $data, $status, $wait);
  }

  public function delete($url, $credentials, $status=null, $wait=3) {
    return $this->request('delete', $url, $credentials, null, $status, $wait);
  }

  protected function request($verb, $url, $credentials, $fields=null, $status=null) {
    $time = microtime(true);

    //$fields = (is_array($fields)) ? http_build_query($fields) : $fields;
    printr($verb);
    printr($url);
    printr($credentials);
    printr($fields);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $verb);
    //curl_setopt($ch, CURLOPT_HEADER, TRUE);
    if ($credentials) {
      curl_setopt($ch, CURLOPT_USERPWD, $credentials);
    }
    if ($verb == 'post' || $verb == 'put') {
      curl_setopt($ch, CURLOPT_POSTFIELDS, array($fields));
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
    }
    //curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $head = curl_exec($ch);
    if (!$head) {
      printr(curl_error($ch));
      return false;
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    printr('CURL VERB:');
    printr($verb);
    printr('CURL OUTPUT:');
    printr($head);

    if ($status === null) {
      if ($httpCode < 400) {
        return $head;
      } else {
        return false;
      }
    } elseif ($status == $httpCode) {
      return $head;
    }

    return false;
  }
}
?>