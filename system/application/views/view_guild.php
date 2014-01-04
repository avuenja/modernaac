<?php
$ide = new IDE;
try { $ide->loadInjections('guild_view'); } catch(Exception $e) { error($e->getMessage()); }
$logo = (file_exists("public/guild_logos/".$guild->getId().".gif")) ? "<img src='".WEBSITE."/public/guild_logos/".$guild->getId().".gif' width='64' height='64'>" : "<img src='".WEBSITE."/public/guild_logos/default.gif'>";
	echo "<div style='float: left; padding-right: 10px;'>$logo</div>";
	echo "<h1>".$guild->getName()."</h1>";
	echo $guild->getCustomField("motd");
	echo "<br /><br />Guild owner: <b><a href='".WEBSITE."/index.php/character/view/".$guild->getOwner()."'>".$guild->getOwner()."</a></b><br /><br />";
	
	$rank_list = $guild->getGuildRanksList();
	$rank_list->orderBy('level', POT::ORDER_DESC);
	$showed_players = 1;
		if($ide->isLogged()) {
		$ots = POT::getInstance();
		$ots->connect(POT::DB_MYSQL, connection());
		$account_logged = new OTS_Account();
		$account_logged->load($_SESSION['account_id']);
		$account_players = $account_logged->getPlayers();
		}
	echo "<table width='100%'>";
		foreach($rank_list as $rank)
		{
			$players_with_rank = $rank->getPlayersList();
			$players_with_rank->orderBy('name');
			$players_with_rank_number = count($players_with_rank);
			if($players_with_rank_number > 0)
			{
				echo "<tr class='rankBar'><td><b>".$rank->getName()."</b></td></tr>";
				foreach($players_with_rank as $player)
				{
					$guild_nick = $player->getGuildNick();
					if(!empty($guild_nick)) $guild_nick = "($guild_nick)"; else $guild_nick = "";
					if($ide->isLogged()) {$leave = (in_array($player->getName(), $account_players)) ? "<a href='#' onClick='if(confirm(\"Are you sure you want to leave ".$guild->getName()." with a ".$player->getName()." ?\")) window.location.href=\"".WEBSITE."/index.php/guilds/leave/".$guild->getId()."/".$player->getId()."\"'><img src='".WEBSITE."/public/images/false.gif'/></a>" : ""; }
					echo @"<tr class='playerGuildBar'><td>$leave<a href=\"".WEBSITE."/index.php/character/view/".$player->getName()."\">".$player->getName()."</a> $guild_nick</td></tr>";
				}
			}
		}
	echo "</table>";
		
	if(!$ide->isLogged()) {
		alert("You need to be logged in to access any options.");
	}
	else {

		require_once('system/application/libraries/POT/InvitesDriver.php');
		new InvitesDriver($guild);
		$invited_list = $guild->listInvites();
		if(count($invited_list) == 0)
			echo "<center><b>This guild did not invite anyone.</b></center>";
		else {
		echo "<table width='100%'>";
		echo "<tr><td><center><b>Name</b></center></td><td><center><b>Join</b></center></td></tr>";
		$characters = array();
		if($ide->isLogged()) {
			foreach($account_players as $player_from_acc) {
				$characters[] = $player_from_acc->getName();
			}
		}
			
		foreach($invited_list as $invited_player)
			{
				
						
						
						if($ide->isLogged() && in_array($invited_player->getName(), $characters)) {
							echo "<tr><td><center><a href=\"".WEBSITE."/index.php/character/view/".$invited_player->getName()."\">".$invited_player->getName()."</a></center></td><td><center><a href='".WEBSITE."/index.php/guilds/join/".$guild->getId()."/".$invited_player->getId()."'>Join</a></center></td></tr>";
							
						}
						else {
							echo "<tr><td><center><a href=\"".WEBSITE."/index.php/character/view/".$invited_player->getName()."\">".$invited_player->getName()."</a></center></td><td><center>Cannot join</center></td></tr>";
						
						}
				
				
			}
		echo "</table>";
		}
	
	}
	if($ide->isLogged() && in_array($guild->getOwner(), $account_players) || $leaders) {
		echo "<table style='text-align: center; margin-top: 10px;'><tr class='first'><td>Guild Management</td></tr><tr><td>";
		echo "<a href='".WEBSITE."/index.php/guilds/management/".$guild->getId()."'>Guild Management</a>";	
		echo "</td></tr></table>";
	}
	
	if(($ide->isLogged() && $viceleaders)) {
		echo "<table style='text-align: center; margin-top: 10px;'><tr class='first'><td>Guild Management</td></tr><tr><td>";
		echo "<a href='".WEBSITE."/index.php/guilds/management/".$guild->getId()."'>Guild Management</a>";	
		echo "</td></tr></table>";
	}

?>