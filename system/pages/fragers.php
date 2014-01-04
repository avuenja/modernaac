<?php
//Script by Elf
//Made for Modern AAC by Paxton
require("config.php");
$ots = POT::getInstance();
$ots->connect(POT::DB_MYSQL, connection());
$SQL = $ots->getDBHandle();
echo '<div style="text-align: center; font-weight: bold;">Top 30 frags on ' . $config['server_name'] . '</div>
<table border="0" cellspacing="1" cellpadding="4" width="100%">
 <tr>
  <td class="white" style="text-align: center; font-weight: bold;">Name</td>
  <td class="white" style="text-align: center; font-weight: bold;">Frags</td>
 </tr>';

$i = 0;
foreach($SQL->query('SELECT `p`.`name` AS `name`, COUNT(`p`.`name`) as `frags`
 FROM `killers` k
 LEFT JOIN `player_killers` pk ON `k`.`id` = `pk`.`kill_id`
 LEFT JOIN `players` p ON `pk`.`player_id` = `p`.`id`
WHERE `k`.`unjustified` = 1 AND `k`.`final_hit` = 1
 GROUP BY `name`
 ORDER BY `frags` DESC, `name` ASC
 LIMIT 0,30;') as $player)
{
 $i++;
 echo '<tr class="highlight">
  <td><a href="'.WEBSITE.'/index.php/character/view/'.$player['name'].'"><center>' . $player['name'] . '</center></a></td>
  <td><center>' . $player['frags'] . '</center></td>
 </tr>';
}

echo '</table>';
?>