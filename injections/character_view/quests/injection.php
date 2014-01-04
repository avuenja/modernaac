<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$characterPageQuests = array();
/* A list of quests for character/view page. It will be listed on the page as a table to show acomplished missions.
Array contains array of quests which includes STORAGE ID, STORAGE VALUE (Required to finish quest) and NAME 
To create new quest copy line accross and change values.
*/
$player = $GLOBALS['player'];
/* Eg. $characterPageQuests[] = array('storage'=>5000, 'value'=>1, 'name'=>'Demon Helmet Quest'); */
$characterPageQuests[] = array('storage'=>5000, 'value'=>1, 'name'=>'Demon Helmet Quest');


 if(count($characterPageQuests) != 0) {
		echo "<div class='bar'>Quests</div>";
		echo "<table width='100%'>";
		echo "<tr><td width='90%'><center><b>Quest Name</b></center></td><td><b><center>Status</center></b></td></tr>";
		$SQL = POT::getInstance()->getDBHandle();
		foreach($characterPageQuests as $value) {
			$quest = $SQL->query("SELECT `value` FROM `player_storage` WHERE `player_id` = ".$player->getId()." AND `key` = '".$value['storage']."' AND `value` = '".$value['value']."'")->fetch();
			$status = ($quest) ? "true" : "false";
			echo "<tr><td width='90%'><center>".$value['name']."</center></td><td><center><img src='../../../public/images/$status.gif'></center></td></tr>";
		}
		echo "</table>";
	}
?>