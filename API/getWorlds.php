<?php
require("system/system.php");
require("../config.php");
auth();
$worlds = array();
	foreach($config['worlds'] as $id=>$name) {
		$worlds[$id] = $name;
	}
echo json_encode($worlds);