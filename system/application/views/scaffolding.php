<?php 
require("config.php");
alert("<b>Scaffolding</b> allows you to build database backend software. Just choose one of the tables in your database.<br /><br /> <b>Note</b>: table must got a primary ID in order to work properly.");
echo form_open("admin/scaffolding");
echo "<select name='table'>";
	foreach($tables as $name) {
		echo "<option value=".$name['TABLE_NAME'].">".$name['TABLE_NAME']."</option>";
	}
echo "</select>";
echo "&nbsp; <input type='submit' value='Scaffold'>";
echo "</form>";
?>