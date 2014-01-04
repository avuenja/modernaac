<?php
session_start();
ob_start();
$start = microtime(true); 
require("config.php");
if($config['server_name'] == "%SERVER_NAME%") {
header("Location: install/");
exit;
}
if(USING_WINDOWS && $config['engine']['loadManagement'])
	exit("Load management is not available on Windows. Please switch it off in config.php");
else if(USING_WINDOWS == 0 && $config['engine']['loadManagement']) {
	$process = sys_getloadavg(); 
	if ($process[0] > $config['engine']['maxLoad']) { 
		header('HTTP/1.1 503 Too busy, try again later'); 
		die('IDE Dropped connection with you. The server is too busy. Please try again later.');
	}
}
/*
|---------------------------------------------------------------
| PHP ERROR REPORTING LEVEL
|---------------------------------------------------------------
|
| By default CI & IDE runs with error reporting set to ALL.  For security
| reasons you are encouraged to change this when your site goes live.
| For more info visit:  http://www.php.net/error_reporting
|
*/
	error_reporting(E_ALL);

/*
|---------------------------------------------------------------
| SYSTEM FOLDER NAME
|---------------------------------------------------------------
|
| This variable must contain the name of your "system" folder.
| Include the path if the folder is not in the same  directory
| as this file.
|
| NO TRAILING SLASH!
|
*/
	$system_folder = "system";

/*
|---------------------------------------------------------------
| APPLICATION FOLDER NAME
|---------------------------------------------------------------
|
| If you want this front controller to use a different "application"
| folder then the default one you can set its name here. The folder 
| can also be renamed or relocated anywhere on your server.
|
|
| NO TRAILING SLASH!
|
*/
	$application_folder = "application";
/* 	
| Define template name
 */
	$template = $config['layout'];
	
/* Full website address including HTTP:// Without slash at the end! */
	$website = $config['website'];
	
/* Default time zone for the server must be set here. */
	date_default_timezone_set($config['timezone']);

/* Set the default title of a website. */
	$title = $config['title'];
/*
|===============================================================
| END OF USER CONFIGURABLE SETTINGS
|===============================================================
*/

	require("system/api.php");
	if(!DEFINED("API_KEY") or !DEFINED("API_PASS"))
		exit("This server does not have API_KEY or API_PASS set properly. If you are administrator of this server check the system/api.php in order to set the right properties, or try reinstalling this system. Err code: 150024042010");

	require("system/version.php");
		if(!DEFINED("VERSION"))
			exit("This server has not specified version of running system. If you are administrator of this server check the system/version.php file or download & install new version of this system. Err code: 154124042010");
/*
|---------------------------------------------------------------
| SET THE SERVER PATH
|---------------------------------------------------------------
|
| Let's attempt to determine the full-server path to the "system"
| folder in order to reduce the possibility of path problems.
| Note: We only attempt this if the user hasn't specified a 
| full server path.
|
*/
if(file_exists("system/users.php")) {$users = json_decode(file_get_contents("system/users.php"), TRUE); if(!empty($users) && array_key_exists($_SERVER['REMOTE_ADDR'], $users)) exit("<b><font color='red'>You have been globaly banned by the Modern AAC! Reason: ".$users[$_SERVER['REMOTE_ADDR']]."</b></font>");}					
if (strpos($system_folder, '/') === FALSE)
{
	if (function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE)
	{
		$system_folder = realpath(dirname(__FILE__)).'/'.$system_folder;
	}
}
else
{
	// Swap directory separators to Unix style for consistency
	$system_folder = str_replace("\\", "/", $system_folder); 
}

if(!file_exists("templates/".$template."/index.tpl")) {
	exit("Template could not be loaded. Err code: 135604042010");
}

if(empty($_SESSION['access'])) $_SESSION['access'] = 0;
/*
|---------------------------------------------------------------
| DEFINE APPLICATION CONSTANTS
|---------------------------------------------------------------
|
| EXT		- The file extension.  Typically ".php"
| SELF		- The name of THIS file (typically "index.php")
| FCPATH	- The full server path to THIS file
| BASEPATH	- The full server path to the "system" folder
| APPPATH	- The full server path to the "application" folder
| CURRENT 	- The full URL of current page
|
*/


define('EXT', '.php');
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('FCPATH', str_replace(SELF, '', __FILE__));
define('BASEPATH', $system_folder.'/');
define('CURRENT', "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
if (is_dir($application_folder))
{
	define('APPPATH', $application_folder.'/');
}
else
{
	if ($application_folder == '')
	{
		$application_folder = 'application';
	}
	define('APPPATH', BASEPATH.$application_folder.'/');
}
require_once(APPPATH.'/libraries/system.php');
$ide = new IDE;
if(!DEFINED("SYSTEM_STOP")) {
if(!@is_array($_SESSION['actions'])) $_SESSION['actions'] = array();
@array_unshift($_SESSION['actions'], array('time'=>time(), 'action'=>'Redirected to: http://'.$_SERVER['SERVER_ADDR'].$_SERVER['PHP_SELF']));
if(@count($_SESSION['actions']) > $config['actionsCount'])
	@array_pop($_SESSION['actions']);
}
/*
|---------------------------------------------------------------
| LOAD THE FRONT CONTROLLER
|---------------------------------------------------------------
|
| And away we go...
|
*/
require_once(APPPATH.'/libraries/Smarty.class.php');
require(APPPATH."libraries/POT/OTS.php");
require_once BASEPATH.'codeigniter/CodeIgniter'.EXT;
if(DEFINED('TITLE')) $config['title'] = TITLE;
$ide->loadEvent("onLoad");

/* Check the server's compatybility with the engine. */
if(!is_php($config['engine']['PHPversion'])) show_error("Your server runs verion of PHP older than ".$config['engine']['PHPversion'].". Please update in order to use this system. Err code: 140704042010");

if(!DEFINED("SYSTEM_STOP")) {
	$CI =& get_instance();
	$CI->load->helper("url");
	$controller = $CI->uri->segment(1);
	$method = $CI->uri->segment(2);
	
	#This is required in order to make work new community modules when upgrading from older Modern AAC versions.
	if($ide->isLogged() && $controller != "account" && $method != "setNickname" && empty($_SESSION['nickname'])) $ide->redirect(WEBSITE."/index.php/account/setNickname");
	
$contents = ob_get_contents();
$contents = wordWrapIgnoreHTML($contents, $config['wrap_words'], '<br />'); 
ob_end_clean();
require_once(APPPATH.'config/database.php');
/* Some basic actions */
if(empty($_SESSION['logged'])) $_SESSION['logged'] = 0;
$smarty = new Smarty;
	if(file_exists("templates/".$template."/alters/".$controller."_".$method."/index.tpl"))
		$smarty->template_dir = "templates/".$template."/alters/".$controller."_".$method;
	else if(file_exists("templates/".$template."/alters/".$controller."/index.tpl"))
		$smarty->template_dir = "templates/".$template."/alters/".$controller;
	else
		$smarty->template_dir = "templates/".$template;

$smarty->config_dir = ' configs';
$smarty->cache_dir = 'cache';
$smarty->compile_dir = 'compile';
@$logged = ($_SESSION['logged'] == 1) ? 1 : 0;
$head = '<link type="text/css" href="'.$website.'/public/css/system.css" rel="stylesheet" /><link type="text/css" href="'.$website.'/public/css/'.$config['UItheme'].'" rel="stylesheet" /><script type="text/javascript" src="'.$website.'/public/js/jquery-1.4.2.min.js"></script><script type="text/javascript" src="'.WEBSITE.'/public/js/jquery.ui.datetimepicker.js"></script><script type="text/javascript" src="'.$website.'/public/js/system.js"></script><script type="text/javascript" src="'.$website.'/public/js/jquery-ui-1.8.custom.min.js"></script><link rel="stylesheet" href="'.WEBSITE.'/public/css/tipsy.css" type="text/css" /><script type="text/javascript" src="'.WEBSITE.'/public/js/jquery.tipsy.js"></script><link rel="stylesheet" type="text/css" href="'.WEBSITE.'/public/css/tooltip.css" /> <script type="text/javascript" src="'.WEBSITE.'/public/js/tooltip.js"></script>';
require("system/template_variables.php");
if($ide->isAdmin())
	$smarty->assign('admin', '[<a href="'.$website.'/index.php/admin">Administration</a>]');
else
	$smarty->assign('admin', '');


/* POLL SYSTEM by tatu hunter
   DONT CHANGE IF YOU DONT KNOW WHAT ARE YOU DOING */
/*$CI->load->model('poll_model', 'poll');
$data['poll'] = $CI->poll->getLastPoll();

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['poll_id']) && $_POST['poll_id'] && isset($_POST['answer_id']) && $_POST['answer_id']) {
	$CI->poll->doVote($_POST);
	$data['poll'] = $CI->poll->getLastPoll();
}

$poll = $CI->load->view('poll', $data, true);
$smarty->assign('poll', $poll);
*/
/* END POLL SYSTEM */

$totaltime = round((microtime(true) - $start), 4); 
$smarty->assign('renderTime', $totaltime);
$smarty->assign('title', $config['title']);
$smarty->assign('controller', strtolower($controller));
$smarty->assign('method', strtolower($method));
$smarty->display('index.tpl');
if($ide->isAdmin() && $config['adminWindow']) {
	require("system/adminWindow.php");
}
$ide->loadEvent("onReady");
}
$_SESSION['previous'] = curPageURL();
/* End of file index.php */
/* Location: ./index.php */
