<?php
require("system/system.php");
auth();
$db = new database();
$return = array();
$sql = $db->query("SELECT count(1) FROM `bans`")->fetch_array();
echo $sql[0];