<?php
/**************************************************************************\
* Protean Framework                                                        *
* https://github.com/erictj/protean                                        *
* Copyright (c) 2006-2010, Loopshot Inc.  All rights reserved.             *
* ------------------------------------------------------------------------ *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the BSD License as described in license.txt.         *
\**************************************************************************/

class PFRestHelper { 

	public static function processRequest() { 

		$requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
		$returnObject	= PFFactory::getInstance()->createObject('api.restrequest');
		$data	= array();

		switch ($requestMethod) {
			case 'get':
			$data = $_GET;
			break;
			case 'post':
			$data = $_POST;
			break;
			case 'put':
			parse_str(file_get_contents('php://input'), $putVars);
			$data = $putVars;
			break;
		}

		$returnObject->setMethod($requestMethod);
		$returnObject->setRequestVars($data);

		if (isset($data['data'])) {
			$returnObject->setData(json_decode($data['data']));
		}
		return $returnObject;
	}

	public static function sendResponse($status=200, $content='', $contentType='text/html') { 		
		$statusHeader = 'HTTP/1.1 ' . $status . ' ' . PFRestHelper::getStatusCodeMessage($status);
		header($statusHeader);
		header('Content-type: ' . $contentType);

		if ($contentType != 'text/html') {
			echo $content;
			exit;
		} else {

			$message = '';

			switch ($status) {
				case 400:
				$message = 'A bad request was made to the URL ' . $_SERVER['REQUEST_URI'] . '.';
				break;
				case 401:
				$message = 'You must be authorized to view this page.';
				break;
				case 404:
				$message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
				break;
				case 500:
				$message = 'The server encountered an error processing your request.';
				break;
				case 501:
				$message = 'The requested method is not implemented.';
				break;
			}

			$signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

			$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
			<html>
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
				<title>' . $status . ' ' . PFRestHelper::getStatusCodeMessage($status) . '</title>
				</head>
				<body>
				<h1>' . PFRestHelper::getStatusCodeMessage($status) . '</h1>
				<p>' . $message . '</p>
				<p>' . $content . '</p>
				<hr />
				<address>' . $signature . '</address>
				</body>
				</html>';

			echo $body;
			exit;
		}	
	}

	public static function getStatusCodeMessage($status) {
		$codes = Array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported'
			);

		return (isset($codes[$status])) ? $codes[$status] : '';
	}
}

?>