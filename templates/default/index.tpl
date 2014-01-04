<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<meta name="Description" content="Information architecture, Web Design, Web Standards." />
<meta name="Keywords" content="your, keywords" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="Distribution" content="Global" />
<meta name="author" content="Unknown" />
<meta name="Robots" content="index,follow" />

<link rel="stylesheet" href="{$path}/templates/default/images/Refresh.css" type="text/css" />
{$head}

<title>{$title}</title>
	
</head>

<body>

		<!--[if IE]>
		<script type="text/javascript"> 
			var IE6UPDATE_OPTIONS = {
				icons_path: "public/ie6update/images/"
			}
		</script>
		<script type="text/javascript" src="public/ie6update/ie6update.js"></script>
		<![endif]-->
<!-- wrap starts here -->
<div id="wrap">
		<!--header -->
		<div id="header">			
				
			<h1 id="logo-text">Mo<span class="gray">dernAAC</span>1.0</h1>		
			<h2 id="slogan">Powered by IDE Engine</h2>
				
			<form class="search" method="post" action="{$path}/index.php/character/view">
				<p>
	  			<input class="textbox" type="text" name="name" value="" />
	 			<input class="button" type="submit" name="Submit" value="Search" />
				</p>
			</form>			
				
		</div>
		
		<!-- menu -->	
		<div  id="menu">
		
			<ul>
				<li{if $controller == "" || $controller == "home"} id="current"{/if}><a href="{$path}">Home</a></li>
				{if $logged == 1}
					<li{if $controller == "account"} id="current"{/if}><a href="{$path}/index.php/account">Account</a></li>
				{else}
					<li{if $controller == "account" && $method == "create"} id="current"{/if}><a href="{$path}/index.php/account/create">Create Account</a></li>
					<li{if $controller == "account" && $method == "login"} id="current"{/if}><a href="{$path}/index.php/account/login">Login</a></li>
				{/if}
					<li{if $controller == "character" && $method == "view"} id="current"{/if}><a href="{$path}/index.php/character/view">Characters</a></li>
					<li{if $controller == "character" && $method == "online"} id="current"{/if}><a href="{$path}/index.php/character/online">Who is Online</a></li>
					<li{if $controller == "guilds"} id="current"{/if}><a href="{$path}/index.php/guilds">Guilds</a></li>
					<li{if $controller == "highscores"} id="current"{/if}><a href="{$path}/index.php/highscores">Highscores</a></li>	
					<li{if $controller == "forum"} id="current"{/if}><a href="{$path}/index.php/forum">Forum</a></li>		
					
			</ul>
		</div>					
			
		<!-- content-wrap starts here -->
		<div id="content-wrap">
				
			<div id="sidebar">
				{$online}
				<h1>Sidebar Menu</h1>
				<div class="left-box">
					<ul class="sidemenu">				
						{if $logged == 1}
						<li><a href="{$path}/index.php/account">Account</a></li>
						{else}
						<li><a href="{$path}/index.php/account/create">Create Account</a></li>
						<li><a href="{$path}/index.php/account/login">Login</a></li>
						{/if}
						<li><a href="{$path}/index.php/character/view">Characters</a></li>
						<li><a href="{$path}/index.php/guilds">Guilds</a></li>
						<li><a href="{$path}/index.php/bugtracker">Bug Tracker</a></li>	
						<li><a href="{$path}/index.php/p/v/fragers">Top fraggers</a></li>	
						<li><a href="{$path}/index.php/video">Videos</a></li>	
						<li><a href="{$path}/index.php/houses/main">Houses</a></li>	
						<li><a href="{$path}/index.php/p/v/deaths">Latest Deaths</a></li>	
						<li><a href="{$path}/index.php/p/v/gallery">Gallery</a></li>	
						<li><a href="{$path}/index.php/profile/community">Community</a></li>	
					</ul>	
					
					<h1>Server status</h1><br />
					{foreach from=$worlds key=id item=world}
						&nbsp; <b>World:</b> {$world} <br />
						&nbsp; <b>Status:</b>  
							{if $serverOnline[$id]}
								<font color='green'>Online</font><br />
								&nbsp; <b>Uptime:</b> {$serverUptime[$id]} <br />
								&nbsp; <b>Players:</b> {$serverPlayers[$id]}/{$serverMax[$id]}<br /><br />

							{else}
								<font color='red'>Offline</font><br />
							{/if}
					{/foreach}
					{$poll}
				</div>
			
				
				
				
				
			</div>
				
			<div id="main" style='padding-top: 10px;'>
				{$main}
			</div>
		
		<!-- content-wrap ends here -->	
		</div>
					
		<!--footer starts here-->
		<div id="footer">
			<a href="{$path}/index.php/credits">Credits</a>, 
			Page rendered in: {$renderTime}
			{$admin}
				
		</div>	

<!-- wrap ends here -->
</div>

</body>
</html>
