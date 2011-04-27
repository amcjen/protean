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
interface PFFormCommand {
	public function initializeForm(PFRequest $request);
	public function populateForm(PFRequest $request, PFForm $form);
	public function handleSubmittedForm(PFRequest $request, PFForm $form);
}

/**
@package api
*/
interface PFMultiFormCommand {
	public function initializeForms(PFRequest $request);
	public function populateForms(PFRequest $request, array $formArray);
	public function handleSubmittedForm(PFRequest $request, array $formArray);
}

/**
@package api
*/
interface PFRestfulCommand {
	public function handleGet(PFRequest $request);
	public function handlePost(PFRequest $request);
	public function handlePut(PFRequest $request);
	public function handleDelete(PFRequest $request);
}
?>