<?php
require("system/system.php");
require("../config.php");
auth();
$cities = array();
	foreach($config['cities'] as $id=>$name) {
		$cities[$id] = $name;
	}
echo json_encode($cities);