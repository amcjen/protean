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
require_once 'modules/content/cmd/default.class.php';

abstract class PFResourceCommand extends PFDefaultCommand { 

	public function doExecute(PFRequest $request) {	
		try {
			parent::doExecute($request);
			PFFactory::getInstance()->initObject('api.resthelper');
			$this->response = PFRestHelper::processRequest($request);
			$method = 'handle' . $this->response->getMethod();
			return $this->$method($request);
		} catch (PFException $e) {
			$e->handleRestException();
		}
	}

	public function handleGet(PFRequest $request) {
		
		if (PFRequestHelper::getRESTSlotForURIPattern(1, $request) == 'make') {
			return $this->make($request);
		} elseif (PFRequestHelper::getRESTSlotForURIPattern(1, $request) != '') {
			
			if (PFRequestHelper::getRESTSlotForURIPattern(2, $request) == 'edit') {
				return $this->edit($request, PFRequestHelper::getRESTSlot(1, $request));
			} else {
				return $this->show($request, PFRequestHelper::getRESTSlot(1, $request));
			}
		} else {
			return $this->index($request);
		}
	}

	public function handlePost(PFRequest $request) {
		return $this->create($request);
	}

	public function handlePut(PFRequest $request) {
		return $this->update($request, PFRequestHelper::getRESTSlot(1, $request));
	}

	public function handleDelete(PFRequest $request) {
		return $this->destroy($request, PFRequestHelper::getRESTSlot(1, $request));
	}

	abstract public function index(PFRequest $request);					// GET 		/shop/product
	abstract public function make(PFRequest $request);					// GET 		/shop/product/make
	abstract public function create(PFRequest $request);				// POST 	/shop/product
	abstract public function show(PFRequest $request, $key);		// GET 		/shop/product/:key:
	abstract public function edit(PFRequest $request, $key);		// GET 		/shop/product/:key:/edit
	abstract public function update(PFRequest $request, $key);	// PUT 		/shop/product/:key:
	abstract public function destroy(PFRequest $request, $key);	// DELETE /shop/product/:key:
}
?>