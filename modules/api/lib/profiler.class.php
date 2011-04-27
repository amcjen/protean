<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2011, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/
/**
@package api
*/
class PFProfiler { 

	static private $instance;
	static private $enabled;

	private function __construct() {
		self::init();
		self::$enabled = PF_PROFILER;
	}
	
	static public function getInstance() {

		if(self::$instance == NULL) {
			self::$instance = new PFProfiler();
		}

		return self::$instance;
	}
	
	static public function init() {

		if (!extension_loaded('xhprof')) {
			die(sprintf('xhprof extension must be installed to use profiling'));
		}
	}

	static public function start() { 
		if (self::$enabled) {
			xhprof_enable();
		}
	} 

	static public function stop() { 
		if (self::$enabled) {
		 	$data = xhprof_disable();
		
			if (!is_dir(PF_PROFILER_RUNS_PATH)) {
				mkdir(PF_PROFILER_RUNS_PATH);
			}
			
			include_once PF_BASE . '/modules/thirdparty/xhprof/xhprof_lib/utils/xhprof_lib.php';
			include_once PF_BASE . '/modules/thirdparty/xhprof/xhprof_lib/utils/xhprof_runs.php';
			$xhprof_runs = new XHProfRuns_Default(PF_PROFILER_RUNS_PATH);
			$run_id = $xhprof_runs->save_run($data, 'xhprof');
		}
	} 
}
?>