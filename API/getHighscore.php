<?php
require('../config.php');
require("system/system.php");
auth();
$db = new database();
$return = array();

$world = (isset($_GET['world'])?$_GET['world']:0);
$skill = (isset($_GET['skill'])?$_GET['skill']:0);
$config['newchar_vocations'][$world][] = 'Account Manager';
$filter = "'".join("','", $config['newchar_vocations'][$world])."'";
switch(@$skill) {
	case 1:
		$sql = $db->query('SELECT name,online,level,experience,vocation,promotion, world_id FROM players WHERE players.world_id LIKE "'.$world.'" AND players.deleted = 0 AND players.group_id < '.$config['players_group_id_block'].' AND name NOT IN('.$filter.') ORDER BY level DESC, experience DESC LIMIT 50');
		while($cmd = $sql->fetch_array()){ $return['players'][] = $cmd; }
		$return['type'] = "Experience";
	break;
	case 2:
		$sql = $db->query('SELECT name,online,value,level,vocation,promotion FROM players,player_skills WHERE players.world_id = '.$world.' AND players.deleted = 0 AND players.group_id < '.$config['players_group_id_block'].' AND players.id = player_skills.player_id AND player_skills.skillid = 0 AND name NOT IN('.$filter.') ORDER BY value DESC, count DESC LIMIT 50');
		while($cmd = $sql->fetch_array()){ $return['players'][] = $cmd; }
		$return['type'] = "Fist Fighting";
	break;
	case 3:
		$sql = $db->query('SELECT name,online,value,level,vocation,promotion FROM players,player_skills WHERE players.world_id = '.$world.' AND players.deleted = 0 AND players.group_id < '.$config['players_group_id_block'].' AND players.id = player_skills.player_id AND player_skills.skillid = 1 AND name NOT IN('.$filter.') ORDER BY value DESC, count DESC LIMIT 50');
		while($cmd = $sql->fetch_array()){ $return['players'][] = $cmd; }
		$return['type'] = "Club Fighting";
	break;
	case 4:
		$sql = $db->query('SELECT name,online,value,level,vocation,promotion FROM players,player_skills WHERE players.world_id = '.$world.' AND players.deleted = 0 AND players.group_id < '.$config['players_group_id_block'].' AND players.id = player_skills.player_id AND player_skills.skillid = 2 AND name NOT IN('.$filter.') ORDER BY value DESC, count DESC LIMIT 50');
		while($cmd = $sql->fetch_array()){ $return['players'][] = $cmd; }
		$return['type'] = "Sword Fighting";
	break;
	case 5:
		$sql = $db->query('SELECT name,online,value,level,vocation,promotion FROM players,player_skills WHERE players.world_id = '.$world.' AND players.deleted = 0 AND players.group_id < '.$config['players_group_id_block'].' AND players.id = player_skills.player_id AND player_skills.skillid = 3 AND name NOT IN('.$filter.') ORDER BY value DESC, count DESC LIMIT 50');
		while($cmd = $sql->fetch_array()){ $return['players'][] = $cmd; }
		$return['type'] = "Axe Fighting";
	break;
	case 6:
		$sql = $db->query('SELECT name,online,value,level,vocation,promotion FROM players,player_skills WHERE players.world_id = '.$world.' AND players.deleted = 0 AND players.group_id < '.$config['players_group_id_block'].' AND players.id = player_skills.player_id AND player_skills.skillid = 4 AND name NOT IN('.$filter.') ORDER BY value DESC, count DESC LIMIT 50');
		while($cmd = $sql->fetch_array()){ $return['players'][] = $cmd; }
		$return['type'] = "Distance Fighting";
	break;
	case 7:
		$sql = $db->query('SELECT name,online,value,level,vocation,promotion FROM players,player_skills WHERE players.world_id = '.$world.' AND players.deleted = 0 AND players.group_id < '.$config['players_group_id_block'].' AND players.id = player_skills.player_id AND player_skills.skillid = 5 AND name NOT IN('.$filter.') ORDER BY value DESC, count DESC LIMIT 50');
		while($cmd = $sql->fetch_array()){ $return['players'][] = $cmd; }
		$return['type'] = "Shielding";
	break;
	case 8:
		$sql = $db->query('SELECT name,online,value,level,vocation,promotion FROM players,player_skills WHERE players.world_id = '.$world.' AND players.deleted = 0 AND players.group_id < '.$config['players_group_id_block'].' AND players.id = player_skills.player_id AND player_skills.skillid = 6 AND name NOT IN('.$filter.') ORDER BY value DESC, count DESC LIMIT 50');
		while($cmd = $sql->fetch_array()){ $return['players'][] = $cmd; }
		$return['type'] = "Fishing";
	break;
	case 9:
		$sql = $db->query('SELECT name,online,maglevel,level,vocation,promotion FROM players WHERE players.world_id = '.$world.' AND players.deleted = 0 AND players.group_id < '.$config['players_group_id_block'].' AND name NOT IN('.$filter.') ORDER BY maglevel DESC, manaspent DESC LIMIT 50');
		while($cmd = $sql->fetch_array()){ $return['players'][] = $cmd; }
		$return['type'] = "Magic level";
	break;
	default:
		$sql = $db->query('SELECT name,online,level,experience,vocation,promotion, world_id FROM players WHERE players.world_id LIKE "'.$world.'" AND players.deleted = 0 AND players.group_id < '.$config['players_group_id_block'].' AND name NOT IN('.$filter.') ORDER BY level DESC, experience DESC LIMIT 50');
		while($cmd = $sql->fetch_array()){ $return['players'][] = $cmd; }
		$return['type'] = "Experience";
	break;
}
echo json_encode($return);
