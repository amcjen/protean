<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" /> 
		<meta name="keywords" content="Protean Framework" /> 
		<meta name="description" content="" />
		<title>Protean Framework</title>
 		<link rel="Shortcut Icon" href="/favicon.ico" /> 
		<link rel="stylesheet" type="text/css" media="screen" href="/modules/content/tpl/default/css/960-reset-text.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="/modules/content/tpl/default/css/style.css" />
	</head>

	<body>
		<div id="doc" class="container_16">
			<div id="hd" class="grid_16 alpha">
				<div class="grid_13 alpha">
			  	<img id="headerlogo" src="#" alt="Protean Framework" border="0" /> 
				</div>
				<div class="grid_3 omega" id="header-nav">
					{if $CURRENTLY_LOGGED_IN}
					<span>Hi {$CURRENTLY_LOGGED_IN_AS}!</span> |
					<a href="/registration/account">My Account</a> |
					<a href="/registration/logout">Logout</a>
					{else}
					<a href="/registration/login">Login</a>
					{/if}
				</div>
			</div>
			{include file=$ERRORSTACK_TPL}