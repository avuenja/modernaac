<?php

function isValidEmail($email) {

	if(preg_match('/^\w[-.\w]*@(\w[-._\w]*\.[a-zA-Z]{2,}.*)$/', $email, $matches)) {
		if(function_exists('checkdnsrr')) {
			if(checkdnsrr($matches[1] . '.', 'MX')) return true;
			if(checkdnsrr($matches[1] . '.', 'A')) return true;
		} else {
			if(!empty($matches[1])) {
				exec("nslookup -type=MX {$matches[1]}", $result);
				foreach ($result as $line) {
					if(eregi("^{$matches[1]}",$line))
						return true;
				}
				return false;
			}
			return false;
      }
	}
	return false;
}

function wordWrapIgnoreHTML($string, $length = 45, $wrapString = "\n") 
   { 
     $wrapped = ''; 
     $word = ''; 
     $html = false; 
     $string = (string) $string; 
     for($i=0;$i<strlen($string);$i+=1) 
     { 
       $char = $string[$i]; 
       
       /** HTML Begins */ 
       if($char === '<') 
       { 
         if(!empty($word)) 
         { 
           $wrapped .= $word; 
           $word = ''; 
         } 
         
         $html = true; 
         $wrapped .= $char; 
       } 
       
       /** HTML ends */ 
       elseif($char === '>') 
       { 
         $html = false; 
         $wrapped .= $char; 
       } 
       
       /** If this is inside HTML -> append to the wrapped string */ 
       elseif($html) 
       { 
         $wrapped .= $char; 
       } 
       
       /** Whitespace characted / new line */ 
       elseif($char === ' ' || $char === "\t" || $char === "\n") 
       { 
         $wrapped .= $word.$char; 
         $word = ''; 
       } 
       
       /** Check chars */ 
       else 
       { 
         $word .= $char; 
         
         if(strlen($word) > $length) 
         { 
           $wrapped .= $word.$wrapString; 
           $word = ''; 
         } 
       } 
     } 

    if($word !== ''){ 
        $wrapped .= $word; 
    } 
     
     return $wrapped; 
   } 

function error($string) {
	if(!empty($string)) {
	$string = str_replace("<p>", "", $string);
	$string = str_replace("</p>", "<br>", $string);
	echo '<div class="ui-widget">
			<div class="ui-state-error ui-corner-all" style="font-size: 13px; padding-left: 5px; font-family: verdana;"> 
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em; color:black;"></span> 
				<strong>Error:</strong><br /><br />'.$string.'</p>
			</div>
		</div><br />';
		}
}

function alert($string) {
	if(!empty($string)) {
	$string = str_replace("<p>", "", $string);
	$string = str_replace("</p>", "<br>", $string);
	echo '<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="font-size: 13px; padding-left: 5px; font-family: verdana;"> 
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em; color:black;"></span>
				<strong>Alert:</strong><br><br />'.$string.'</p>
			</div>
		</div><br />';
	}
}

function success($string) {
	if(!empty($string)) {
	$string = str_replace("<p>", "", $string);
	$string = str_replace("</p>", "<br>", $string);
	echo '<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="font-size: 13px; padding-left: 5px; font-family: verdana;"> 
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em; color:black;"></span>
				<strong>Success:</strong><br><br />'.$string.'</p>
			</div>
		</div><br />';
	}
}

function truncateString($text, $nbrChar, $append='...') {
     if(strlen($text) > $nbrChar) {
          $text = substr($text, 0, $nbrChar);
          $text .= $append;
     }
     return $text;
}

function ago( $timestamp )
		{
			if ( $timestamp <= 0 )
			{
				return 'a while ago';
			}
			
			if ( $timestamp > time( ) )
			{
				return 'in the future';
			}

			$current = time( );
			$difference = $current - $timestamp;

			if ( $difference < 60 )
				$interval = 's';
			elseif ( $difference >= 60 and $difference < 60 * 60 )
				$interval = 'n';
			elseif ( $difference >= 60 * 60 and $difference < 60 * 60 * 24 )
				$interval = 'h';
			elseif ( $difference >= 60 * 60 * 24 and $difference < 60 * 60 * 24 * 7 )
				$interval = 'd';
			elseif ( $difference >= 60 * 60 * 24 * 7 and $difference < 60 * 60 * 24 * 30 )
				$interval = 'w';
			elseif ( $difference >= 60 * 60 * 24 * 30 and $difference < 60 * 60 * 24 * 365 )
				$interval = 'm';
			elseif ( $difference >= 60 * 60 * 24 * 365 )
				$interval = 'y';

			switch ( $interval )
			{
				case 'm':
					$months_difference = floor( $difference / 60 / 60 / 24 / 29 );
					while ( mktime( 
						date( 'H', $timestamp ), 
						date( 'i', $timestamp ), 
						date( 's', $timestamp ),
						date( 'n', $timestamp ) + $months_difference,
						date( 'j', $current ),
						date( 'Y', $timestamp )
					) < $current )
					{
						$months_difference++;
					}
					$amount = $months_difference;

					if ( $amount == 12 )
					{
						$amount--;
					}

					return $amount.' month'.( $amount != 1 ? 's' : null ).' ago';
					break;

				case 'y':
					$amount = floor( $difference / 60 / 60 / 24 / 365 );
					return $amount.' year'.( $amount != 1 ? 's' : null ).' ago';
					break;

				case 'd':
					$amount = floor( $difference / 60 / 60 / 24 );
					return $amount.' day'.( $amount != 1 ? 's' : null ).' ago';
					break;

				case 'w':
					$amount = floor( $difference / 60 / 60 / 24 / 7 );
					return $amount.' week'.( $amount != 1 ? 's' : null ).' ago';
					break;

				case 'h':
					$amount = floor( $difference / 60 / 60 );
					return $amount.' hour'.( $amount != 1 ? 's' : null ).' ago';
					break;

				case 'n':
					$amount = floor( $difference / 60 );
					return $amount.' minute'.( $amount != 1 ? 's' : null ).' ago';
					break;

				case 's':
					return $difference.' second'.( $difference != 1 ? 's' : null ).' ago';
					break;
			}
		}

function alertBox($string) {
	echo "<script>alert('$string');</script>";
}

function requireLogin() {
	if(!empty($_SERVER["HTTP_REFERER"]))
		 $_SESSION['forward'] = $_SERVER["HTTP_REFERER"];
	if(empty($_SESSION['logged'])) header('Location: '.WEBSITE.'/index.php/account/login');
}

function UNIX_TimeStamp($time) {
	return date("Y-m-d H:i:s",$time);
}

function loadConfig($file) {
	require_once(APPPATH."config/$file.php");
}

function connection() {
	loadConfig('database');
	return array('host' => HOSTNAME, 'user' => USERNAME, 'database' => DATABASE, 'password' => PASSWORD);
}

function bugtracker_getPriority($id) {
	$priority = array(1=>"Low", 2=>"Medium", 3=>"High", 4=>"Urgent");
		if(array_key_exists($id, $priority))
			return $priority[$id];
		else
			return "Error";
}

function bugtracker_getCategory($id) {
	$category = array(1=>"Bugs", 2=>"Ideas", 3=>"Problems");
		if(array_key_exists($id, $category))
			return $category[$id];
		else
			return "Error";
}

function bugtracker_getPriorityImage($id) {
	if($id == 1)
		return "<img src='".WEBSITE."/public/images/bugtracker/low.gif'>";
	else if($id == 2)
		return "<img src='".WEBSITE."/public/images/bugtracker/medium.gif'>";
	else if($id == 3 or $id == 4)
		return "<img src='".WEBSITE."/public/images/bugtracker/high.gif'>";
}

function getContent($file) {
	if(file_exists($file))
		return file_get_contents($file);
	else
		return false;
}

function decodeString($string) {
	return str_replace(array("\'", '\"'), array("'", '"'), $string);
}

function getVocationName($voc, $promotion) {
	require("config.php");
	if($promotion == 0) {
		return @$config['server_vocations'][$voc];
	}
	else {
		return @$config['promotions'][$voc];
	}
}
/* Another thing from the VAPus PHP project */
/* Covert timestamps to Xh Xm [Xs] format */
function uptimeParse($sec, $displaySec=false) {
	$hour = (int)($sec / 3600);
	$sec = $sec - ($hour * 3600);
	$min = (int)($sec / 60);
	$sec = $sec - ($min * 60);
			
	$string = "{$hour}h {$min}m";
	if($displaySec) $string .= " {$sec}s";
	return $string;
}

function setTitle($string) {
	@DEFINE("TITLE", $string);
}

function in_multiarray($value, $array, $case_insensitive = false){
    foreach($array as $item){
        if(is_array($item)) $ret = in_multiarray($value, $item, $case_insensitive);
        else $ret = ($case_insensitive) ? strtolower($item)==$value : $item==$value;
        if($ret)return $ret;
    }
    return false;
}

function url($address, $public = false) {
	if($public) 
		return WEBSITE."/public/".$address;
	else
		return WEBSITE."/index.php/".$address;
}

function loader() {
	return "<img class='loader' src='".WEBSITE."/public/images/loading.gif'/>";
}

function addAction($string) {
	array_unshift($_SESSION['actions'], array('time'=>time(), 'action'=>$string));
}

function printc($string) {
	echo date("H:i:s", time())." > ".$string."<br/>";
} 

function callCommand($arg) {
	require('commands/commands.php');
	$command = explode(" ", $arg);
	$cmd = (is_array($command))?$command[0]:$command;
	$args = explode(",", str_replace($cmd, "", $arg));
		foreach($args as $key=>$input) {$args[$key] = trim($input);}
	if(!array_key_exists($cmd, $commands)) {
		throw new exception("Command not found. Tried: ".$cmd);
	}
	else {
		global $ide;
		if($commands[$cmd]['access'] > $ide->getAccess()) 
			throw new exception('You don\'t have enough access to use this command.');
		else {
			if(!file_exists("commands/scripts/".$commands[$cmd]['path']))
				throw new exception('Could not load command on: commands/scripts/'.$commands[$cmd]['path']);
			else {
				include("commands/scripts/".$commands[$cmd]['path']);
				$output = ob_get_contents();
				ob_end_clean();
				return $output;
			}
		}
	}
}

function lg($key) {
	global $language;
	return @$language[$key];
}

function curPageURL() {
 $pageURL = 'http';
 if (@$_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

require('IDE/main.php');
?>
