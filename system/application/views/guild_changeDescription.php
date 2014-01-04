<?php 
echo "<h1>Change Description</h1><br/>";
echo error(validation_errors());
if(empty($_POST['description'])) $_POST['description'] = $description[0]['guildnick'];
echo form_open("guilds/changeDescription/".$id."/".$player);
echo "<label>Description</label>";
echo "<input type='text' value='".set_value('description')."' name='description'/><br/>";
echo "<input type='submit' value='Change'/>";
echo "</form>";
?>