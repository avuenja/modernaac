<?php
require_once("system/application/config/create_character.php");
/*Setting global to alllow plugins to access guild list.*/
$GLOBALS['guilds'] = $guilds;
$ide = new IDE;
	try { $ide->loadInjections("guilds_index"); }
	catch(Exception $e) { error($e->getMessage());}

	if(!$ide->isLogged()){alert("You need to be logged in to create guild.");} else {

		echo "<a class='createGuildLink' href='guilds/create'>Create Guild</a>";
	
	}
if(count($config['worlds'] > 1)) {
	echo form_open("guilds", array('method'=>'post'));
	echo "<label>World:</label>";
	echo "<select name='world_id'>";
	foreach($config['worlds'] as $id=>$world) {
		echo "<option value='$id'>$world</option>";
	}
	echo "</select>";
	echo "<input type='submit' value='Sort'>";
	echo "</form>";
}
if(count($guilds) == 0) 
alert("There is no guilds.");
else {

	echo "<table width='100%'>";
	echo "<tr><td width='15%'><center><b>Logo</b></center></td><td><center><b>Name</b></center></td><td width='15%'><center><b>World</b></center></td></tr>";
	foreach($guilds as $guild) {
		$logo = (file_exists("public/guild_logos/".$guild['id'].".gif")) ? "<img src='".WEBSITE."/public/guild_logos/".$guild['id'].".gif' width='64' height='64'>" : "<img src='".WEBSITE."/public/guild_logos/default.gif'>";
		echo "<tr><td width='15%'><center>$logo</center></td><td><center><a href='guilds/view/".$guild['id']."'>".$guild['name']."</a><br><i>".$guild['motd']."</i></center></td><td width='15%'><center>".$config['worlds'][$guild['world_id']]."</center></td></tr>";
	}
	echo "</table>";

}
?>