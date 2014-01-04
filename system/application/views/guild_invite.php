<?php 
echo error(validation_errors());
echo form_open("guilds/invite/".$id);

	echo "<h1>Invite player to ".$guild[0]['name']."</h1>";
	echo "<br/><label>Player name: </label>";
	echo "<input type='text' value='".set_value('name')."' name='name'><br/>";
	echo "<input type='submit' value='Invite'/>";

echo "</form>";
?>