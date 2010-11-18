<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/
	
class PFProfiler {

	static protected $instance;
	protected $startTime;								
	protected $timeMarks;								
	protected $saveMarks;								
	protected $lastTime; 								

	private function __construct() {
		$this->startTime = microtime(true);
		$this->lastTime = $this->startTime;
		$this->endTime = 0;
		
		$this->saveMarks = true;
		
		if ($this->saveMarks == true) {
			$this->timeMarks[0]['type'] =  'mark';
			$this->timeMarks[0]['time'] =  $this->startTime;
			$this->timeMarks[0]['message'] = 'Beginning of profiling';
		}
	}
	
	static public function getInstance() {
		if (self::$instance == NULL) {
			self::$instance = new PFProfiler();
		}		
		return self::$instance;
	}
	
	public function saveMarks($flag=true) {
		$this->saveMarks = $flag;
	}
	
	public function setStartTime($time) {
		$this->startTime = $time;
	}
	
	public function setMark($message) {	
		if ($this->saveMarks == true) {
			$i = count($this->timeMarks);
			$this->timeMarks[$i]['type'] =  'mark';
			$this->timeMarks[$i]['time'] =  microtime(true);
			$this->timeMarks[$i]['timeoffset'] =  sprintf('%.5f', (microtime(true) - $this->lastTime));
			$this->timeMarks[$i]['message'] = $message;
			$this->lastTime = $this->timeMarks[$i]['time'];
		}
	
		$sapi_type = php_sapi_name();
		if (substr($sapi_type, 0, 3) == 'cli') {
				echo '.';
		}
	}
	
	public function setNote($message) {	
		if ($this->saveMarks == true) {
			$i = count($this->timeMarks);
			$this->timeMarks[$i]['type'] =  'note';
			$this->timeMarks[$i]['time'] =  '';
			$this->timeMarks[$i]['message'] = $message;
		}
	}
	
	public function displayMarks($rawOutput=false) {
		$msg = '';		
		
		for ($i=0; $i<count($this->timeMarks); $i++) {
			if ($this->timeMarks[$i]['type'] == 'mark') {
				$msg .= $i . ' Mark' . ': ';
			} else {
				$msg .= $i . ' Note' . ': ';
			}
				
			if ($i == 0) {
				$msg .= '0.00000/0.00000';
			} else {
				if ($this->timeMarks[$i]['type'] == 'mark') {
					$msg .= $this->timeMarks[$i]['time'] . '/' . $this->timeMarks[$i]['timeoffset'];
				} else {
					$msg .= '-------';
				}
			}
			
			$msg .= ' - ' . $this->timeMarks[$i]['message'] . "\n";
						
			if ($rawOutput == true) {			
				$sapi_type = php_sapi_name();
				if (substr($sapi_type, 0, 3) == 'cli') {				
					if ($i == 0) {
						echo "\n";
					}
					
					echo $msg . "\n";
				} else {				
					printr($msg);
				}
			} 
		}
		
		if ($rawOutput == false) {
			return $msg;
		}
	}
	
	public function getTime() {	
		return sprintf('%.5f', (microtime(true) - $this->startTime));
	}
	
	public function testProfiler() {
		$this->setNote('Test Note');	
		$this->setMark('Test Start');
		sleep(1);
		$this->setMark('Sleep 1 second');
		sleep(3);
		$this->setMark('Sleep 3 seconds');		
		$this->setNote('Test Note');
		$this->setNote('Test Note 2');		
		$this->setMark('Test End');		
		$this->setNote('Test Note');
	}
}
?>