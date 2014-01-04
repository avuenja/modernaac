<?php
require_once("system/application/config/create_character.php");
	if(in_array(strtolower($player->name), $config['restricted_names']))
		error("You are not authorized to view this character.");
	else {
	if($player->isDeleted()) alert("This character has been deleted.");
	if($player->isNameLocked()) alert("This character has been name locked.");

	try {$comment = nl2br(decodeString(strip_tags($player->getComment())));}catch (Exception $e) {$comment = "Could not load comment.";}
	$nickname = ($account->getCustomField('nickname') == "") ? "Not set yet." : $account->getCustomField('nickname');
	try { $created = $player->getCreated(); } catch (Exception $e) { $created = time()-36000;}
?>
<div class='bar'>Character</div>
<table width='100%'>
	<tr><td width='30%'>Name</td><td><?php echo $player->getName();?></td></tr>
	<tr><td width='30%'>Sex</td><td><?php echo $sex = ($player->getSex() == 1) ? "Male" : "Female" ?></td></tr>
	<tr><td width='30%'>Profession</td><td><?php echo getVocationName($player->getVocation(), $player->getPromotion()); ?></td></tr>
	<tr><td width='30%'>Level</td><td><?php echo $player->getLevel(); ?></td></tr>
	<?php 
		$rank_of_player = $player->getRank();
		if(!empty($rank_of_player)) {
			$guild_id = $rank_of_player->getGuild()->getId();
			$guild_name = $rank_of_player->getGuild()->getName();
			echo "<tr><td width='30%'>Guild membership</td><td>".$rank_of_player->getName()." of the <a href='../../guilds/view/$guild_id'>".$guild_name."</a></td></tr>";
		}
	?>
	<tr><td width='30%'>World</td><td><?php echo $config['worlds'][$player->getWorld()]; ?></td></tr>
	<tr><td width='30%'>Nickname</td><td><?php echo "<a href='".url('profile/view/'.$nickname)."'>".$nickname."</a>"; ?></td></tr>
	<tr><td width='30%'>Last login</td><td><?php echo $lastlogin = ($player->getLastLogin() == 0) ? "Never" : UNIX_TimeStamp($player->getLastLogin()); ?></td></tr>
	<tr><td width='30%'>Comment</td><td><?php echo $comment; ?></td></tr>
	<tr><td width='30%'>Account Status</td><td><?php echo $status = ($account->isPremium()) ? "Premium" : "Free"; ?></td></tr>
	<tr><td width='30%'>Group</td><td><?php echo $config['groups'][$player->getGroup()]; ?></td></tr>
	<tr><td width='30%'>Status</td><td><?php echo $status = ($player->isOnline()) ? "<font color='green'>Online</font>" : "<font color='red'>Offline</font>"; ?></td></tr>
	<tr><td width='30%'>Created</td><td><?php echo ago($created)." | ".UNIX_TimeStamp($created); ?></td></tr>
</table>
<?php 
	$GLOBALS['player'] = $player;
	$ide = new IDE;
	try {
		$ide->loadInjections("character_view");
	}
		catch(Exception $e) {
			error($e->getMessage());
		}
	
}
?>