<?php
require_once("system/application/config/create_character.php");
echo "<h1>Create Guild</h1>";
	if(count($characters) == 0) 
		error("All your characters are already in guild or they don't have required level. (<b>".$config['levelToCreateGuild']."</b>)");
	else {
	error(validation_errors());
	echo form_open("guilds/create", array('method'=>'post'));
	echo "<label>Character:</label>";
	echo "<select name='character'>";
		foreach($characters as $character) {
			echo "<option value='".$character['id']."'>".$character['name']." (".$character['level'].")</option>";
		}
	echo "</select><br /><br />";
	echo "<label>Guild name:</label>";
	echo "<input type='text' name='name'><br />";
	echo "<input type='submit' value='Create' name='submit'>";
	echo "</form>";
	
	}
?>