<?php

class Guilds_Model extends Model {
	
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	public function getGuildsList($world_id = null) {
		$ext = (!empty($world_id)) ? "WHERE `world_id` = '$world_id'": "";
		$guilds = array();
		$this->db->select('id, name, world_id, motd');
		if($world_id)
			$sql = $this->db->get_where('guilds', array('world_id' => $world_id))->result();
		else
			$sql = $this->db->get('guilds')->result();
			
		foreach($sql as $cmd)
			$guilds[] = array("id"=>$cmd->id, "world_id"=>$cmd->world_id, "name"=>$cmd->name, "motd"=>$cmd->motd);
		
		return $guilds;
	}
	
	public function getCharactersAllowedToCreateGuild($level = 0) {
		$characters = array();
		$this->db->select('id, name, level');
		$sql = $this->db->get_where('players', array('online' => 0, 'rank_id' => 0, 'level' => $level, 'account_id' => $_SESSION['account_id']))->result();
		foreach($sql as $cmd) {
			$characters[] = array('id'=>$cmd->id, 'name'=>$cmd->name,'level'=>$cmd->level);
		}
		return $characters;
	}
	
	public function checkPlayerCreatingGuild($id) {
		return $this->db->get_where('players', array('id' => $id, 'level' => LEVELTOCREATEGUILD, 'account_id' => $_SESSION['account_id']))->num_rows() ? true : false;
	}
	
	public function checkGuildName($name) {
		return $this->db->get_where('guilds', array('name' => $name))->num_rows() ? true : false;
	}
	
	public function createGuild($name, $character) {
		$ots = POT::getInstance();
		$ots->connect(POT::DB_MYSQL, connection());
		$player = new OTS_Player();
		$player->load($character);
		$new_guild = new OTS_Guild();
		$new_guild->setCreationData($_SERVER['REQUEST_TIME']);
		$new_guild->setName($name);
		$new_guild->setOwner($player);
		$new_guild->save();
		$new_guild->setCustomField('motd', 'New guild. Leader must edit this text :)');
		$new_guild->setCustomField('creationdata', $_SERVER['REQUEST_TIME']);
		$new_guild->setCustomField('world_id', $player->getWorld());
		$ranks = $new_guild->getGuildRanksList();
		$ranks->orderBy('level', POT::ORDER_DESC);
		foreach($ranks as $rank)
			if($rank->getLevel() == 3)
			{
				$player->setRank($rank);
				$player->save();
			}
		return $new_guild->getId();
	}
	
	public function getGuildInfo($id) {
		return $this->db->get_where('guilds', array('id' => $id))->result_array();
	}
	
	public function isGuildLeader($id) {
		return $this->db->query("SELECT `id` FROM `players` WHERE `id` = ".$this->db->escape($id)." AND `account_id` = '".$_SESSION['account_id']."' ")->num_rows() ? true : false;
	}

	public function isViceLeader($id) {
        	$viceRank = 2;
	        return $this->db->query("SELECT `p`.`id`, `p`.`name` FROM `guild_ranks` AS `r` LEFT JOIN `players` AS `p` ON `p`.`rank_id` = `r`.`id` WHERE `r`.`guild_id` = ".$this->db->escape($id)." AND `r`.`level` = '".$viceRank."' AND `p`.`account_id` = '".$_SESSION['account_id']."' ")->num_rows() ? true : false;
    	}
		
	public function isLeader($id) {
        	$Rank = 3;
	        return $this->db->query("SELECT `p`.`id`, `p`.`name` FROM `guild_ranks` AS `r` LEFT JOIN `players` AS `p` ON `p`.`rank_id` = `r`.`id` WHERE `r`.`guild_id` = ".$this->db->escape($id)." AND `r`.`level` = '".$Rank."' AND `p`.`account_id` = '".$_SESSION['account_id']."' ")->num_rows() ? true : false;
    	} 
	
	public function isInvitable($name) {
		return $this->db->query("SELECT `rank_id`, `online` FROM `players` WHERE `name` = \"".$name."\"")->result_array();
	}
	
	public function getCharacterId($name) {
		$this->db->select('id');
		return $this->db->get_where('players', array('name' => $name))->result_array();
	}
	
	public function invite($id, $player) {
		$this->db->insert('guild_invites', array('player_id' => $player, 'guild_id' => $id));
	}
	
	public function getMembers($id) {
		return $this->db->query("SELECT players.id, players.name, guild_ranks.name AS guild_rank FROM guild_ranks LEFT JOIN players ON rank_id = guild_ranks.id WHERE guild_ranks.guild_id = ".$this->db->escape($id)." ORDER BY guild_ranks.level DESC")->result_array();
	}
	
	public function isGuildMember($id, $player) {
		return $this->db->query("SELECT players.rank_id FROM players, guild_ranks WHERE players.rank_id = guild_ranks.id AND players.id = '".$player."' AND guild_ranks.guild_id = ".$this->db->escape($id)."")->num_rows() ? true : false;
	}
	
	public function getMemberDescription($id) {
		$this->db->select('guildnick');
		return $this->db->get_where('players', array('id' => $id))->result_array();
	}
	
	public function changeDescription($player, $desc) {
		$this->db->update('players', array('guildnick' => $desc), array('id' => $player));
	}
	
	public function getMemberRank($id) {
		$this->db->select('rank_id');
		return $this->db->get_where('players', array('id' => $id))->result_array();
	}
	
	public function getRanks($id) {
		$this->db->select('name, id');
		return $this->db->get_where('guild_ranks', array('guild_id' => $id))->result_array();
	}
	
	public function getRanksID($id) {
		$this->db->select('id');
		return $this->db->get_where("guild_ranks", array('guild_id' => $id))->result_array();
	}
	
	public function changeRank($id, $rank) {
		$this->db->update('players', array('rank_id' => $rank), array('id' => $id));
	}
	
	public function kick($id) {
		$this->db->update('players', array('guildnick' => '', 'rank_id' => 0), array('id' => $id));
	}
	
	public function leave($id) {
		$this->db->update('players', array('guildnick' => '', 'rank_id' => 0), array('id' => $id));
	}
	
	public function changeMotd($id, $motd) {
		$this->db->update('guilds', array('motd' => $motd), array('id' => $id));
	}
	
	public function deleteGuild($id) {
		$this->db->query("DELETE FROM `guilds` WHERE `id` = ".$this->db->escape($id)."");
		//Triggers should do rest of the job.
			if(file_exists("public/guild_logos/".$id.".gif"))
				unlink("public/guild_logos/".$id.".gif");
	}
	public function canUpdate($id) {
		$sql = $this->db->query("SELECT `online`, `name` FROM `players` WHERE `id` = ".$this->db->escape($id)."")->row_array();
		return $sql;	
	}
	
}

?>
