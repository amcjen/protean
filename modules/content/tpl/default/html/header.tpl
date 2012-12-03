<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>{$PF_PAGE_TITLE|default:$smarty.const.PF_SITE_NAME}</title>
		<meta name="keywords" content="$PF_PAGE_KEYWORDS|default:Protean Framework" /> 
		<meta name="description" content="{$PF_PAGE_DESCRIPTION|default:"Protean Framework"}">
		<meta name="author" content="Protean">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="{$CDN_IMAGE_URL}/favicon.png">
		<link rel="apple-touch-icon" href="{$CDN_IMAGE_URL}/apple-touch-icon.png">
		{$PF_HEAD_CSS_INCLUDES}	
		<script src="/modules/thirdparty/html5boilerplate/js/libs/modernizr-1.6.min.js"></script>
	</head>

	<body>
	<div id="container" class="container_16">
		<header>
			<p id="jsnotice">
			  <strong>JavaScript is currently disabled.</strong> This site requires JavaScript to function correctly.<br />
			  Please <a href="http://enable-javascript.com/" target="_blank"> enable JavaScript in your browser</a>!
			</p>
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
			{include file='modules/content/tpl/default/html/errorstack.tpl'}
		</header>