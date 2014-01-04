<?php
global $config; 

require("system/application/config/create_character.php");
$GLOBALS['players'] = $players;

echo '<br /><div align="center">';
@$_world = (int)$_REQUEST['world'];
if(!@$config['servers'][$_world]['vapusid'])
    echo @"Please edit your config.php:<br /><span style=\"font-size: 8px\">\$config['servers'][{$_world}]['vapusid'] = ID; // Replace ID with the server id from VAPus.net<br />
	To get the ID just register and click on the server the last number in the URL is your ID (/otlist.serv/10 means ID = 10)
    </span>";
else
    echo @'<a href="http://vapus.net/otlist.serv/'.$config['servers'][$_world]['vapusid'].'"><img src="http://vapus.net/otlist.graph/'.$config['servers'][$_world]['vapusid'].'/'.$config['VAPusGraphStep'].'" alt="http://vapus.net Server Graph" /></a>';

echo "</div><br />"; 

$ide = new IDE;
	try { $ide->loadInjections("players_online"); } catch(Exception $e) { error($e->getMessage()); }
echo form_open("character/online", array('method'=>'POST'));
if(count($config['worlds']) > 1) {
	echo "<b>World </b> &nbsp; <select name='world'>";
	echo "<option value=''>All</optino>";
	foreach($config['worlds'] as $key=>$value) {
		echo "<option ".set_select('world', $key)." value='$key'>$value</option>";
	}
	echo "</select>&nbsp; &nbsp;";
	
}
echo "<b>Sort by </b> &nbsp; <select name='sort'>";
echo "<option  value=''>None</option>";
echo "<option ".set_select('sort', 'level')." value='level'>Level</option>";
echo "<option ".set_select('sort', 'Vocation')." value='Vocation'>Profession</option>";
echo "<option ".set_select('sort', 'name')." value='name'>Name</option>";
echo "</select>";
echo "&nbsp; <input type='submit' value='Order'>";
echo "</form>";

echo "</form>";
if(count($players) > 0) {
	echo "<table width='100%'>";
	echo "<tr><td><center><b>Name</b></center></td><td><center><b>Level</b></center></td><td><center><b>Vocation</b></center></td><td><center><b>World</b></center></td></tr>";
	foreach($players as $row) {
		if(in_array(strtolower($row['name']), $config['restricted_names'])) continue;
		echo "<tr><td><center><a href=\"../character/view/".$row['name']."\">".$row['name']."</a></center></td><td><center>".$row['level']."</center></td><td><center>".getVocationName($row['vocation'], $row['promotion'])."</center></td><td><center>".$config['worlds'][$row['world_id']]."</center></td></tr>";
	}
	echo "</table>";
}
else
	alert("There is no players online.");
?>
