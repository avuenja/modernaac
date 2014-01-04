<?php 
	echo "<h1>Members of ".$guild[0]['name']."</h1><br/>";
	echo "<table width='100%'>";
	echo "<tr><td><b><center>Name</center></b></td><td><b><center>Rank</center></b></td><td><b><center>Actions</center></b></td></tr>";
		foreach($members as $member) {
			if(empty($member['name'])) continue;
			echo "<tr class='highlight'><td><center><a href=\"".WEBSITE."/index.php/character/view/".$member['name']."\">".$member['name']."</a></center></td><td><center>".$member['guild_rank']."</center></td><td><center><a href='".WEBSITE."/index.php/guilds/changeDescription/".$id."/".$member['id']."' class='tipsy' title='Change Description'><img src='".WEBSITE."/public/images/edit.gif'/></a> <a href='".WEBSITE."/index.php/guilds/changeRank/".$id."/".$member['id']."' class='tipsy' title='Change Rank'><img src='".WEBSITE."/public/images/interface.gif'/></a> <a href='#' onClick='if(confirm(\"Are you sure?\")) window.location.href=\"".WEBSITE."/index.php/guilds/kick/".$id."/".$member['id']."\"' class='tipsy' title='Kick'><img src='".WEBSITE."/public/images/false.gif'/></a></center></td></tr>";
		}
	echo "</table>";
?>