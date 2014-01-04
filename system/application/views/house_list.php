<?php 
	require("config.php");
	$ide = new IDE;
	$GLOBALS['houses'] = $houses;
	try { $ide->loadInjections("house_list"); } catch (Exception $e) { error($e->getMessage()); }
	echo form_open("houses/main");
	
	if(empty($config['cities'])) show_error("Cities are not configured properly.");
	if(empty($config['worlds'])) show_error("Worlds are not configured properly.");
	
	echo "<b>World</b> &nbsp; ";
		echo "<select name='world'>";
			foreach($config['worlds'] as $id=>$city) {
				echo "<option ".set_select('world', $id)." value='".$id."'>".$city."</option>";
			}
	echo "</select>&nbsp; ";
	
	echo "<b>Town</b> &nbsp; ";
		echo "<select name='town'>";
			foreach($config['cities'] as $id=>$city) {
				echo "<option ".set_select('town', $id)." value='".$id."'>".$city."</option>";
			}
		echo "</select> &nbsp; ";
		
	echo "<b>Free</b> &nbsp; ";
	echo "<input type='checkbox' ".set_checkbox('free', '1')."  value='1' name='free'/> &nbsp;";
	
	echo "<b>Guild</b> &nbsp; ";
	echo "<input type='checkbox' value='1' ".set_checkbox('guild', '1')." name='guild'/>";
	echo "&nbsp; <input type='submit' value='Search'/>";
	
	echo "</form>";
	
	if(empty($houses))	
		alert("No houses found.");
	else {
		echo "<center>".$pages."</center>";
		foreach($houses as $house) {
			echo "<div class='houses_list_box'>";
				echo "<div class='house_title'>";
					echo $house['name'];
					echo "<div style='float: right; margin-right: 5px;'><a href='".WEBSITE."/index.php/houses/view/".$house['id']."'>View</a></div>";
				echo "</div>";
				
				echo "<div class='house_content'>";
				
						echo "<b>Town</b> ".@$config['cities'][$house['town']]." &nbsp; &nbsp; ";
						echo "<b>World</b> ".@$config['worlds'][$house['world_id']]." &nbsp; &nbsp; ";
						echo "<b>Size</b> ".$house['size']."sqm &nbsp; &nbsp; ";
						echo "<b>Beds</b> ".$house['beds']." &nbsp; &nbsp; ";
						echo "<b>Rent</b> ".$house['rent']." gold &nbsp; &nbsp; ";
						echo "<b>Doors</b> ".$house['doors']." &nbsp; &nbsp; <br/>";
		
						echo ($house['owner'] == 0) ? "This house is <font color='green'><b>free</b></font>." : "This house is <font color='red'><b>taken</b></font>. It's been paid on ".UNIX_TimeStamp($house['paid'])." | ".ago($house['paid']);
						echo ($house['guild'] == 1) ? "<br/><b>This is a guild house!</b>" : "";
				echo "</div>";
			echo "</div>";
		}
		echo "<center>".$pages."</center>";
	}
?>