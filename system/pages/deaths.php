<?php
	if(!defined('BASEPATH')) exit('No direct script access allowed');

	require("config.php");

	$ots = POT::getInstance();
	$ots->connect(POT::DB_MYSQL, connection());
	$SQL = $ots->getDBHandle();
	$deaths = $SQL->query("SELECT `player_deaths`.`id`, `player_deaths`.`date`, `player_deaths`.`level`, `players`.`name`, `players`.`world_id` FROM `player_deaths` LEFT JOIN `players` ON `player_deaths`.`player_id` = `players`.`id` ORDER BY `player_deaths`.`date` DESC LIMIT 0,{$config['latestdeathlimit']};");

	echo "<div style=\"text-align: center; font-weight: bold;\">Latest Deaths</div><table border=\"0\" cellspacing=\"1\" cellpadding=\"4\" width=\"100%\">";

	if($deaths->rowCount())
	{
		foreach($deaths as $death)
		{
			$i = 0;
			$killers = $SQL->query("SELECT `environment_killers`.`name` AS `monster_name`, `players`.`name` AS `player_name`, `players`.`deleted` AS `player_exists`
				FROM `killers` LEFT JOIN `environment_killers` ON `killers`.`id` = `environment_killers`.`kill_id`
				LEFT JOIN `player_killers` ON `killers`.`id` = `player_killers`.`kill_id` LEFT JOIN `players` ON `players`.`id` = `player_killers`.`player_id`
				WHERE `killers`.`death_id` = {$SQL->quote($death['id'])} ORDER BY `killers`.`final_hit` DESC, `killers`.`id` ASC")->fetchAll();

			echo "<tr class=\"highlight\"><td width=\"22%\">".date("j M Y, H:i", $death['date'])."</td><td><a href=\"".WEBSITE."/index.php/character/view/{$death['name']}\">{$death['name']}</a> ";

			foreach($killers as $killer)
			{
				$i++;
				$str = (count($killers) >= 20 ? "annihilated" : (count($killers) >= 15 ? "eliminated" : (count($killers) >= 10 ? "crushed" : (count($killers) >= 5 ? "slain" : "killed"))));
				if(!empty($killer['monster_name']))
					$killer['monster_name'] = (!in_array($i, array(1, count($killers))) ? str_replace(array("an ", "a "), array("", ""), $killer['monster_name']) : $killer['monster_name']);

				echo (!empty($killer['player_name']) ? ($i == 1 ? "{$str} at Level {$death['level']} by " : ($i == count($killers) ? " and " : ", ")).(!empty($killer['monster_name']) ? "{$killer['monster_name']} of " : "").($killer['player_exists'] == 0 ? "<a href=\"".WEBSITE."/index.php/character/view/{$killer['player_name']}\">{$killer['player_name']}</a>" : $killer['player_name']) : ($i == 1 ? "died at Level {$death['level']} by {$killer['monster_name']}" : ($i == count($killers) ? " and {$killer['monster_name']}" : ", {$killer['monster_name']}")));
			}

			echo ".</td></tr>";
		}
	}
	else
		echo "<tr class=\"highlight\"><td style=\"text-align: center;\">No one died on {$config['server_name']}.</td></tr>";
		
	echo "</table>";
?>