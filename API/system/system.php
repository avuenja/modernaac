<?php
require("system/trusted_list.php");
require("system/database.php");

function auth() {
	global $allowed;
	if(!in_array($_SERVER['REMOTE_ADDR'], $allowed)) {
		exit("This server could not authenticate your request.");
	}	
	else return true;
}