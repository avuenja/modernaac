<?php 
error(validation_errors());
if(empty($_POST['motd'])) $_POST['motd'] = $motd;
echo form_open("guilds/motd/".$id."/");
echo "<textarea name='motd'>".set_value('motd')."</textarea><br/>";
echo "<input type='submit' value='Change'/>";
echo "</form>";
?>