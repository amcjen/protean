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
require_once('modules/thirdparty/pheanstalk/pheanstalk_init.php');

class PFJobQueue {

  protected $bs;

  public function __construct() {
    $this->bs = new Pheanstalk(PF_JOBQUEUE_HOST, PF_JOBQUEUE_PORT, PF_JOBQUEUE_CONN_TIMEOUT);
  }

  public function put($tube, $payload) {
    return $this->bs->putInTube($tube, $payload);
  }

  public function reserve($tube) {
    return $this->bs->reserveFromTube($tube);
  }

  public function bury($job) {
    return $this->bs->bury($job);
  }

  public function delete($job) {
    return $this->bs->delete($job);
  }

  public function statsTube($tube) {
    return $this->bs->statsTube($tube);
  }

  public function peekReady($tube) {
    return $this->bs->peekReady($tube);
  }

  public function clearTube($tube) {
    try {
      $stats = $this->statsTube($tube); 
    } catch (Exception $e) {
      if ($e->getMessage() == 'Server reported NOT_FOUND') {
        return false;
      }
    }

    while ($stats->{'current-jobs-ready'} > 0) {
      $this->delete($this->peekReady($tube));
      $stats = $this->statsTube($tube);
    }
  }
}

?>