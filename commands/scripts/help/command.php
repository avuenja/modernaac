<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
require("commands/commands.php");

echo "This is example command!";

echo "<br/>The list of arguments passed to it:<pre>";
var_dump($args);
echo "</pre>";

?>
