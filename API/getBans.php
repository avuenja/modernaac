<?php
require("system/system.php");
auth();
$db = new database();
$return = array();
$sql = $db->query("SELECT `type`, `value`, `param`, `expires`, `added`, `comment`, `reason`, `action`, `statement` FROM `bans` WHERE `active` = '1'");
	while($cmd = $sql->fetch_array()) {
		$return[] = $cmd;
	}
echo json_encode($return);