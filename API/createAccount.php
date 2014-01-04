<?php
require("system/system.php");
require("../config.php");
auth();
require("../system/application/libraries/POT/OTS.php");

/* Status types */
define('OK', 0);
define('MISSING_VAR', 1);
define('ACCOUNTNAME_TAKEN', 2);
define('UNKNOWN', 3);

/* Variable is missing? */
if(!isset($_GET['accountName']) || !isset($_GET['password']) || !isset($_GET['email'])) {
	die(json_encode(array('status'=>MISSING_VAR)));
}
	
$ots = POT::getInstance();
$ots->connect(POT::DB_MYSQL, array('host' => HOSTNAME, 'user' => USERNAME, 'database' => DATABASE, 'password' => PASSWORD));
$account = new OTS_Account();
$account->find($_GET['accountName']);

/* Account name taken? */
if($account->isLoaded()) {
	die(json_encode(array('status'=>ACCOUNTNAME_TAKEN)));
}
$name = $account->createNamed($_GET['accountName']);
$account->setPassword(sha1($_GET['password']));
$account->setEmail($_GET['email']);
$account->setCustomField('premdays', PREMDAYS);
try {
	$account->save();
}
catch(Exception $e) {
	die(json_encode(array('status'=>UNKNOWN, 'message'=>$e->getMessage())));
}
die(json_encode(array('status'=>OK, 'id'=>$account->getId())));
