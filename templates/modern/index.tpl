<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<meta name="Description" content="Web Solutions, http://armentaa.com/" />
<meta name="Keywords" content="your, keywords" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="Distribution" content="Global" />
<meta name="author" content="Unknown" />
<meta name="Robots" content="index,follow" />
<link rel="stylesheet" href="{$path}/templates/modern/images/modern.css" type="text/css" />
{$head}

<title>{$title}</title>
	
</head>

<body>
<div id="wrap">
<a href="{$path}"><div id="header">
</div></a>
<br class="clear" />
<div id="nav">
    <ul>
			<li><a href="{$path}">Home</a></li>
			{if $logged == 1}
				<li><a href="{$path}/index.php/account">Account</a></li>
			{else}
				<li><a href="{$path}/index.php/account/create">Register</a></li>
				<li><a href="{$path}/index.php/account/login">Login</a></li>
			{/if}
			<li><a href="{$path}/index.php/character/view">Search</a></li>
			<li><a href="{$path}/index.php/character/online">Online</a></li>
			<li><a href="{$path}/index.php/guilds">Guilds</a></li>
			<li><a href="{$path}/index.php/highscores">Highscores</a></li>	
			<li><a href="{$path}/index.php/forum">Forum</a></li>	
			{if $logged == 1}
			<li><a href="{$path}/index.php/bugtracker">Bug Tracker</a></li>
			{/if}
    </ul>
</div>
<div class="transbox">
{$main}
</div>
<div class="footer">
Page rendered in <b>{$renderTime}</b>.<br />
<a href="http://code.google.com/p/modernaac/" target="_blank">Modern AAC</a>, powered by IDE Engine.<br />
Website template by <a href="http://armentaa.com/" target="_blank">Armenta.</a><br />
{$admin}
</div>
</div>
</body>
</html>