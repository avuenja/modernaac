<?php
	if(!defined('BASEPATH')) exit('No direct script access allowed');

	$player = $GLOBALS['player'];
	$SQL = POT::getInstance()->getDBHandle();
	$deaths = $SQL->query("SELECT `player_deaths`.`id`, `player_deaths`.`date`, `player_deaths`.`level` FROM `player_deaths` WHERE `player_deaths`.`player_id` = {$player->getId()} ORDER BY `player_deaths`.`date` DESC LIMIT 0,10;");

	if($deaths->rowCount())
	{
		echo "<div class=\"bar\">Deaths</div>";

		foreach($deaths as $death)
		{
			$i = 0;
			$killers = $SQL->query("SELECT `environment_killers`.`name` AS `monster_name`, `players`.`name` AS `player_name`, `players`.`deleted` AS `player_exists` FROM `killers` LEFT JOIN `environment_killers` ON `killers`.`id` = `environment_killers`.`kill_id`
				LEFT JOIN `player_killers` ON `killers`.`id` = `player_killers`.`kill_id` LEFT JOIN `players` ON `players`.`id` = `player_killers`.`player_id`
				WHERE `killers`.`death_id` = {$SQL->quote($death['id'])} ORDER BY `killers`.`final_hit` DESC, `killers`.`id` ASC")->fetchAll();

			echo "<table style=\"width: 100%;\"><tr class=\"highlight\"><td width=\"22%\">".date("j M Y, H:i", $death['date'])."</td><td width=\"78%\">";

			foreach($killers as $killer)
			{
				$i++;
				$str = (count($killers) >= 20 ? "Annihilated" : (count($killers) >= 15 ? "Eliminated" : (count($killers) >= 10 ? "Crushed" : (count($killers) >= 5 ? "Slain" : "Killed"))));
				if(!empty($killer['monster_name']))
					$killer['monster_name'] = (!in_array($i, array(1, count($killers))) ? str_replace(array("an ", "a "), array("", ""), $killer['monster_name']) : $killer['monster_name']);

				echo (!empty($killer['player_name']) ? ($i == 1 ? "{$str} at Level {$death['level']} by " : ($i == count($killers) ? " and " : ", ")).(!empty($killer['monster_name']) ? "{$killer['monster_name']} of " : "").($killer['player_exists'] == 0 ? "<a href=\"".WEBSITE."/index.php/character/view/{$killer['player_name']}\">{$killer['player_name']}</a>" : $killer['player_name']) : ($i == 1 ? "Died at Level {$death['level']} by {$killer['monster_name']}" : ($i == count($killers) ? " and {$killer['monster_name']}" : ", {$killer['monster_name']}")));
			}

			echo ".</td></tr></table>";
		}
	}
?>