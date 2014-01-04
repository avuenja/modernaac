<?php
echo "<h1>Change Rank</h1><br/>";
echo form_open("guilds/changeRank/".$id."/".$player);
	echo "<label>Rank</label>";
	echo "<select name='new'>";
	foreach($ranks as $rank) {
		if($current[0]['rank_id'] == $rank['id'])
		echo "<option selected='true' value='".$rank['id']."'>".$rank['name']."</option>";
		else
		echo "<option value='".$rank['id']."'>".$rank['name']."</option>";
	}
	echo "</select><br/>";
	echo "<input type='submit' name='submit' value='Change'/>";
echo "</form>";
?>

