<?php
//Script created by Elf 
//Changed by Paxton to work with Modern AAC
$ots = POT::getInstance();
$ots->connect(POT::DB_MYSQL, connection());
$SQL = $ots->getDBHandle();
echo'	<div class="message"><div class="title">Most powerfull guilds</div>	<div class="content">
					
<table border="0" cellspacing="3" cellpadding="4" width="100%">
	<tr>';

foreach($SQL->query('SELECT `g`.`id` AS `id`, `g`.`name` AS `name`,
	COUNT(`g`.`name`) as `frags`
FROM `killers` k
	LEFT JOIN `player_killers` pk ON `k`.`id` = `pk`.`kill_id`
	LEFT JOIN `players` p ON `pk`.`player_id` = `p`.`id`
	LEFT JOIN `guild_ranks` gr ON `p`.`rank_id` = `gr`.`id`
	LEFT JOIN `guilds` g ON `gr`.`guild_id` = `g`.`id`
WHERE `k`.`unjustified` = 1 AND `k`.`final_hit` = 1
	GROUP BY `name`
	ORDER BY `frags` DESC, `name` ASC
	LIMIT 0, 4;') as $guild) {
		if(empty($guild['name'])) continue;
		$is = 1;
	echo'		<td style="width: 25%; text-align: center;">
			<a href="'.WEBSITE.'/index.php/guilds/view/' . $guild['id'] . '"><img src="'.WEBSITE.'/public/guild_logos/' . ((file_exists('public/guild_logos/' . $guild['id'] . '.gif')) ? $guild['id'].'.gif' : 'default.gif') . '" width="64" height="64" border="0"/><br />' . $guild['name'] . '</a><br />' . $guild['frags'] . ' kills
		</td>';
	}

echo'	</tr>
</table>';
	if(empty($is)) {
		echo "<center><font color='red'>There is no guilds yet.</font></center>";
	}
echo "</div>";
echo "</div><br/>";
?>
