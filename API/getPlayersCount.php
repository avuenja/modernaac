<?php
require('../config.php');
require("system/system.php");
auth();
$db = new database();
$return = array();
$config['newchar_vocations'][0][] = 'Account Manager';
$filter = "'".join("','", $config['newchar_vocations'][0])."'";
$sql = $db->query("SELECT count(1) FROM `players` WHERE name NOT IN ({$filter})")->fetch_array();
echo $sql[0];