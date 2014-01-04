<?php
require("system/system.php");
require("../config.php");
auth();
$server_vocations = array();
	foreach($config['server_vocations'] as $id=>$name) {
		$server_vocations[$id] = $name;
	}
echo json_encode($server_vocations);