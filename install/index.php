<?php 
require("../config.php");
if(empty($config['server_name']) or $config['server_name'] != "%SERVER_NAME%") {
	header("Location: ../");
	exit();
}
if(!file_exists('iq.php')) exit('Wrong way of cheating.');
require('iq.php');
function is_really_writable($file)
{	
	if (DIRECTORY_SEPARATOR == '/' AND @ini_get("safe_mode") == FALSE)
	{
		return is_writable($file);
	}
	if (is_dir($file))
	{
		$file = rtrim($file, '/').'/'.md5(rand(1,100));
		if (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE)
		{
			return FALSE;
		}
		fclose($fp);
		@chmod($file, DIR_WRITE_MODE);
		@unlink($file);
		return TRUE;
	}
	elseif (($fp = fopen($file, FOPEN_WRITE_CREATE)) === FALSE)
	{
		return FALSE;
	}
	fclose($fp);
	return TRUE;
}	
	$name = @$_POST['server_name'];
	$hostname = @$_POST['hostname'];
	$login = @$_POST['login'];
	$password = @$_POST['password'];
	$dbtable = @$_POST['table'];
	$license = @$_POST['license'] ;
	$error = "";
	$realIP = ($config['servers'][0]['address'] == '127.0.0.1' || $config['servers'][0]['address'] == 'localhost')?'':gethostbyname($config['servers'][0]['address']);
	if(!$_POST) {
		$vapus = json_decode(file_get_contents("http://vapus.net/api.getServersByIp?ip={$realIP}&port={$config['servers'][0]['port']}"), true);
		$vapusID = 0;		
		if(isset($vapus[0]['id']))
			$vapusID = $vapus[0]['id'];
	}
	if($_POST) {

		if(empty($hostname) or empty($name) or empty($login) or empty($dbtable))
			$error .= "<li>All fields are required.</li>";
			
		if(strtolower($_POST['answer']) != strtolower(base64_decode($iq[$_POST['question']]['answer'])))
			$error .= "<li>The IQ answer is wrong. I'm sorry but probably you aren't smart enough for this system.</li>";
			
		if($license != 1)
			$error .= "<li>You must read and accept license</li>";
			
		if(@!mysql_connect($hostname, $login, $password))
			$error .= "<li>Could not connect to database.</li>";
			
		if(@!mysql_select_db($dbtable))
			$error .= "<li>Could not find your database.</li>";
					
		if(empty($error)) {
			$content = @file_get_contents('../config.php') or die("Read access denied to config.php, please chmod 666"); // This is very very critical as nothing else will work then, therefore it's a 'die' 
			$content = str_replace("%SERVER_NAME%", $name, $content);
			$content = str_replace("%DB_HOST%", $hostname, $content);
			$content = str_replace("%DB_LOGIN%", $login, $content);
			$content = str_replace("%DB_PASS%", $password, $content);
			$content = str_replace("%DB_NAME%", $dbtable, $content);
			$content = str_replace("%VAPUS_ID%", $_POST['vapusid'], $content);
			$handle = @file_put_contents('../config.php', $content) or die("Write access denied to config.php, please chmod 666");
			$db = file_get_contents("dbSCHEMA.txt");
			$queries = explode ( ";", $db ); 
			$i = 0;
			foreach ($queries as $query) 
			{ 
				$i++;
			   	@mysql_query ($query); 
			} 
			echo "Changed config.php <br/>";
			echo $i." queries executed.<br />";
			exit("<center>Modern AAC has been installed! You can always change any value in config.php in the main directory, for additional security you can remove the whole install folder. Thank you for choosing this system. You can now view your website <a href='../'>here</a>.</center>");
		}
			
		
	}
	else {
		$_POST['hostname'] = "127.0.0.1";
		$_POST['login'] = "root";
		$_POST['table'] = "name of database";
		$_POST['server_name'] = "name of server";	
	}
?>
<html>
<head>
<title>Instaling Modern AAC - Powered by IDE Engine</title>
<link REL="stylesheet" href="style.css" type="text/css">
</head>
<body>
<div id="wrapper">
<div id="header"></div>
<div id="content">            		
	<div id="left_stroke"></div>
	<div id="right_stroke"></div>
<center>
<div><?php echo $error;?></div>
<form action='index.php' method='post'>
<fieldset>
<legend>Server info</legend>
<b>Server name</b><br/>
<input type='text' value="<?php echo $_POST['server_name']; ?>" name='server_name'/><br/>
</fieldset>
<fieldset>
<legend>Database info</legend>
<b>Hostname</b><br/>
<input type='text' value='<?php echo $_POST['hostname'];?>'name='hostname'/><br/>
<b>Login</b><br/>
<input type='text' value='<?php echo $_POST['login'];?>'name='login'/><br/>
<b>Password</b><br/>
<input type='password' value='<?php echo @$password;?>'name='password'/><br/>
<b>Database</b><br/>
<input type='text' value='<?php echo $_POST['table'];?>'name='table'/><br/>
</fieldset>
<fieldset style='font-size: 12px;'>
<legend>Requirement Test</legend>
Hello! I'm really sorry that I have to make you read this, but it's really important. As well all know, OT community has changed a lot since few years, to the worse of course. I decided to put this small IQ test in here, to allow only people above the critical minimum of any 'human' knowledge. It is because OT community is being from day to day taken over by complete idiots, and I see more and more stupid topics at forums with questions that have answers in them or they are complete stupid.
I'm not telling everyone is like that, but unfortunatelly loads of people. I know, this IQ test is easy to be cheated, but if you are able to cheat it, you must be smart enough to just answer the simple as fuck question and have fun with Modern AAC. Thank you, and again sorry but I had to do this. Signed, Paxton. <br/> You gonna be now asked random question.<br/><br/>
<?php 
	$jebany_numerek = array_rand($iq);
	echo "<b>".base64_decode($iq[$jebany_numerek]['question'])."</b><br/>";
	echo "<input type='text' name='answer'/>";
	echo "<input type='hidden' name='question' value='".$jebany_numerek."'/>";
?>
</fieldset>
	<br/><div class="field"><?php echo nl2br(file_get_contents("license.txt"));?>
	<br/><br/><input type='checkbox' name='license' value='1'/>I accept to the following license.<br/><br/>
	</div><br/>
<input type='submit' value='Install'/><br/>
<b>VAPus ID detection</b>
<div class="field">ID For <?php echo $config['servers'][0]['address'].':'.$config['servers'][0]['port'] ?> gave <?php echo ($vapusID)?'ID:'.$vapusID:'no result,<br /> if this is a public server, please sign it up on the <a href="http://vapus.net">VAPus Otlist</a>.<br /> ModernAAC will still function, but some features might be disabled.'; ?></div>
<input type="hidden" name="vapusid" value="<?php echo $vapusID; ?>" />
</form>
</center>

</div>
</div>
<div id="footer"></div></div>
</body>
</html>
