<?php
require("system/system.php");
auth();
$db = new database();
$return = array();
$sql = $db->query("SELECT `name`, `level`, `world_id`, `sex`, `vocation` FROM `players` WHERE `online` = '1'");
	while($cmd = $sql->fetch_array()) {
		$return[] = $cmd;
	}
echo json_encode($return);