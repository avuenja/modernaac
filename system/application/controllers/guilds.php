<?php
class Guilds extends Controller {

	public function index() {
		$this->load->helper("form");
		$this->load->model("guilds_model");
		require_once("system/application/config/create_character.php");
		$data = array();
		$data['config'] = $config;
		$data['guilds'] = @$this->guilds_model->getGuildsList((int)$_REQUEST['world_id']);
		$this->load->view("guilds", $data);
	}
	
	public function view($id = null, $action = 0) {
		$ide = new IDE;
		if(empty($id)) $ide->redirect('../');
		if($action == 1) { success("You have joined the guild."); echo "<br />";}
		$ots = POT::getInstance();
		$ots->connect(POT::DB_MYSQL, connection());
		$guild = $ots->createObject('Guild');
		try {$guild->load($id); } catch(Exception $e) {show_error('Problem occured while loading guild. Err code: 220612072010 Futher information: '.$e->getMessage()); }
		if(!$guild->isLoaded()) error("Could not find guild.");
		$data['guild'] = $guild;
		$this->load->model("guilds_model");
		$data['viceleaders'] = $this->guilds_model->isViceLeader($id);  
		$data['leaders'] = $this->guilds_model->isLeader($id); 
		$this->load->view("view_guild", $data);
	}
	
	public function _checkPlayer($id) {
		$this->load->model("guilds_model");
		if($this->guilds_model->checkPlayerCreatingGuild($id)) {
			return true;
		}
		else {
			$this->form_validation->set_message('_checkPlayer', 'Could not find character.');
			return false;
		}
	}
	
	public function _checkGuildName($name){
		$this->load->model("guilds_model");
		if($this->guilds_model->checkGuildName($name)) {
			return true;
		}
		else {
			$this->form_validation->set_message('_checkGuildName', 'Guild name is already taken.');
			return false;
		}
	}
	
	public function create() {
		$ide = new IDE;
		$ide->requireLogin();
		$this->load->helper("form");
		$this->load->model("guilds_model");
		require_once("system/application/config/create_character.php");
		$this->load->library("form_validation");
		if(isset($_POST['submit'])) {
			$this->form_validation->set_rules('character', 'Character', 'required|numeric|callback__checkPlayer');
			$this->form_validation->set_rules('name', 'Guild Name', 'required|alpha_space|callback__checkGuildName');
		}
		if($this->form_validation->run() == true) {
			$id = $this->guilds_model->createGuild($_POST['name'], $_POST['character']);
			$this->load->model("forum_model");
			global $config;
			
			// Get name of the creator, for he is gonna be a moderator!
			$ots = POT::getInstance();
			$ots->connect(POT::DB_MYSQL, connection());
			$owner = new OTS_Player();
			try {$owner->load($_POST['character']); } catch(Exception $e) {show_error('Problem occured while loading character. Err code: 220812072010 Futher information: '.$e->getMessage()); }
        	if(!$owner->isLoaded())
        		error("A strange error happend while trying to create the guild board, this guild won't have a board!");
        	else	
				$this->forum_model->createBoard(str_replace('%NAME%', $_POST['name'], $config['guildboardTitle']), str_replace('%NAME%', $_POST['name'], $config['guildboardTitle']), 0, 0, 9999, 1, $owner->getName(), $id);
					
			$ide->redirect(WEBSITE."/index.php/guilds/view/".$id);
			success("{$_POST['name']} has been created.");
		}
		$data = array();
		$data['characters'] = $this->guilds_model->getCharactersAllowedToCreateGuild($config['levelToCreateGuild']);
		$data['config'] = $config;
		$this->load->view("create_guild", $data);
		
	}
	
	public function join($guild_name, $player_name) {
		$guild_name = (int)$guild_name;
		$player_name = (int)$player_name;
		$ide = new IDE;
		if(empty($guild_name) or empty($player_name)) $ide->redirect(WEBSITE."/index.php/guilds");
		$ots = POT::getInstance();
		$ots->connect(POT::DB_MYSQL, connection());
		$guild = $ots->createObject('Guild');
		try {$guild->load($guild_name); } catch(Exception $e) {show_error('Problem occured while loading guild. Err code: 220912072010 Futher information: '.$e->getMessage()); }
		if(!$guild->isLoaded()) $ide->redirect(WEBSITE."/index.php/guilds");
		$player = new OTS_Player();
		try {$player->load($player_name);; } catch(Exception $e) {show_error('Problem occured while loading player. Err code: 221012072010 Futher information: '.$e->getMessage()); }
		if(!$player->isLoaded()) $ide->redirect(WEBSITE."/index.php/guilds");
		if($player->getAccount()->getId() != $_SESSION['account_id']) $ide->redirect(WEBSITE."/index.php/guilds");
		require('system/application/libraries/POT/InvitesDriver.php');
		new InvitesDriver($guild);
		$invited_list = $guild->listInvites();
		if(!in_array($player->getName(), $invited_list)) $ide->redirect(WEBSITE."/index.php/guilds");
		$this->load->model("guilds_model");
		$online = $this->guilds_model->CanUpdate($player_name);
		if($online['online'] != 0) {
			error("".$online['name']." is logged in, please logout first.");
			return false;
		}
		else {
			$guild->acceptInvite($player);
			$ide->redirect(WEBSITE."/index.php/guilds/view/".$guild->getId()."/1");
		}
	}
	
	
	public function management($id) {
		$ide = new IDE;
		$ide->requireLogin();
		$id = (int)$id;
		if(empty($id)) $ide->redirect(WEBSITE."/index.php/guilds");
		$data = array();
		$this->load->model("guilds_model");
		$data['guild'] = $this->guilds_model->getGuildInfo($id);
		if(empty($data['guild'])) $ide->redirect(WEBSITE."/index.php/guilds");
		if(!$this->guilds_model->isGuildLeader($data['guild'][0]['ownerid']) and !$this->guilds_model->isViceLeader($id) and !$this->guilds_model->isLeader($id)) $ide->redirect(WEBSITE."/index.php/guilds");
		if($this->guilds_model->isViceLeader($id)) {
			$data['id'] = $id;
			$this->load->view("guild_vice_menu", $data);
			$this->load->view("guild_management", $data);
		}
		elseif($this->guilds_model->isGuildLeader($data['guild'][0]['ownerid']) or $this->guilds_model->isLeader($id)) {
			$data['id'] = $id;
			$this->load->view("guild_menu", $data);
			$this->load->view("guild_management", $data);
		}
	}
	
	function _isInvitable($name) {
		$this->load->model("guilds_model");
		$player = $this->guilds_model->isInvitable($name);
			if(empty($player)) {
				$this->form_validation->set_message('_isInvitable', 'Could not find this player.');
				return false;
			}
			else if($player[0]['rank_id'] != 0) {
				$this->form_validation->set_message('_isInvitable', 'This player is already in guild.');
				return false;
			}
                        else if($player[0]['online'] != 0) {
                                $this->form_validation->set_message('_isInvitable', 'This player is online!');
                                return false;
                        }
			else
				return true;
	}
	
	public function invite($id) {
		$ide = new IDE;
		$ide->requireLogin();
		$id = (int)$id;
		if(empty($id)) $ide->redirect(WEBSITE."/index.php/guilds");
		$data = array();
		$this->load->model("guilds_model");
		$data['guild'] = $this->guilds_model->getGuildInfo($id);
		if(empty($data['guild'])) $ide->redirect(WEBSITE."/index.php/guilds");
		if(!$this->guilds_model->isGuildLeader($data['guild'][0]['ownerid']) and !$this->guilds_model->isViceLeader($id) and !$this->guilds_model->isLeader($id)) $ide->redirect(WEBSITE."/index.php/guilds");
		$data['id'] = $id;
			if($_POST) {
				$_POST['name'] = decodeString($_POST['name']);
				$this->load->library("form_validation");
				$this->form_validation->set_rules('name', 'Player Name', 'required|nickname|callback__isInvitable');
				if($this->form_validation->run() == true) {
					$player_id = $this->guilds_model->getCharacterId($_POST['name']);
					$this->guilds_model->invite($id, $player_id[0]['id']);
					success($_POST['name']." has been invited to ".$data['guild'][0]['name']);
					$ide->redirect(WEBSITE."/index.php/guilds/management/".$id, 2);
				}
			}
		if($this->guilds_model->isViceLeader($id)) {
			$this->load->helper("form_helper");
			$this->load->view("guild_vice_menu", $data);
			$this->load->view("guild_invite", $data);
		}
		elseif($this->guilds_model->isGuildLeader($data['guild'][0]['ownerid']) or $this->guilds_model->isLeader($id)) {
			$this->load->helper("form_helper");
			$this->load->view("guild_menu", $data);
			$this->load->view("guild_invite", $data);
		}
	}
	
	public function members($id) {
		$ide = new IDE;
		$ide->requireLogin();
		$id = (int)$id;
		if(empty($id)) $ide->redirect(WEBSITE."/index.php/guilds");
		$data = array();
		$this->load->model("guilds_model");
		$data['guild'] = $this->guilds_model->getGuildInfo($id);
		if(empty($data['guild'])) $ide->redirect(WEBSITE."/index.php/guilds");
		if(!$this->guilds_model->isGuildLeader($data['guild'][0]['ownerid']) and !$this->guilds_model->isLeader($id)) $ide->redirect(WEBSITE."/index.php/guilds");
		$data['id'] = $id;
		$data['members'] = $this->guilds_model->getMembers($id);
		$this->load->view("guild_menu", $data);
		$this->load->view("guild_members", $data);
	}
	
	public function changeDescription($id, $player) {
		$ide = new IDE;
		$ide->requireLogin();
		$id = (int)$id;
		$player = (int)$player;
		if(empty($id)) $ide->redirect(WEBSITE."/index.php/guilds");
		if(empty($player)) $ide->redirect(WEBSITE."/index.php/guilds");
		$this->load->model("guilds_model");
		$data = array();
		$data['guild'] = $this->guilds_model->getGuildInfo($id);
		if(empty($data['guild'])) $ide->redirect(WEBSITE."/index.php/guilds");
		if(!$this->guilds_model->isGuildLeader($data['guild'][0]['ownerid']) and !$this->guilds_model->isLeader($id)) $ide->redirect(WEBSITE."/index.php/guilds");
		if(!$this->guilds_model->isGuildMember($id, $player)) $ide->redirect(WEBSITE."/index.php/guilds/members/".$id);
		$data['description'] = $this->guilds_model->getMemberDescription($player);
		$this->load->helper("form_helper");
		$data['player'] = $player;
		$data['id'] = $id;
			if($_POST) {
				$this->load->library("form_validation");
				$this->form_validation->set_rules('description', 'Description', 'alpha_space');
				if($this->form_validation->run() == true) {
					$this->guilds_model->changeDescription($player, $_POST['description']);
					success("Description has been changed.");
					$ide->redirect(WEBSITE."/index.php/guilds/members/".$id, 2);
				}
			}
		$this->load->view("guild_menu", $data);
		$this->load->view("guild_changeDescription", $data);
	}
	
	function _isValidRank($guild, $rank) {
		$this->load->model("guilds_model");
		$ranks = $this->guilds_model->getRanksID($guild);
			foreach($ranks as $ext) {
				$external[] = $ext['id'];
			}
		if(in_array($rank, $external)) {
			return true;
		}
		else
			return false;
	}
	
	public function changeRank($id, $player) {
		$ide = new IDE;
		$ide->requireLogin();
		$id = (int)$id;
		$player = (int)$player;
		if(empty($id)) $ide->redirect(WEBSITE."/index.php/guilds");
		if(empty($player)) $ide->redirect(WEBSITE."/index.php/guilds");
		$this->load->model("guilds_model");
		$data = array();
		$data['guild'] = $this->guilds_model->getGuildInfo($id);
		if(empty($data['guild'])) $ide->redirect(WEBSITE."/index.php/guilds");
		if(!$this->guilds_model->isGuildLeader($data['guild'][0]['ownerid']) and !$this->guilds_model->isLeader($id)) $ide->redirect(WEBSITE."/index.php/guilds");
		if(!$this->guilds_model->isGuildMember($id, $player)) $ide->redirect(WEBSITE."/index.php/guilds/members/".$id);

		$this->load->helper("form_helper");
			if($_POST) {
				$_POST['new'] = (int)$_POST['new'];
				if(!is_int($_POST['new']))
					@$error .= "Rank must be an integer<br/>";
					
				if(empty($_POST['new']))
					@$error .= "Invalid Rank<br/>";
					
				if(!$this->_isValidRank($id, $_POST['new']))
					@$error .= "Invalid Rank Name</br>";
					
				if(!empty($error))
					error($error);
				else {
					$this->guilds_model->changeRank($player, $_POST['new']);
					success("Rank has been changed.");
					$ide->redirect(WEBSITE."/index.php/guilds/members/".$id, 2);
				}
					
			}
		$data['current'] = $this->guilds_model->getMemberRank($player);
		$data['ranks'] = $this->guilds_model->getRanks($id);
		$data['player'] = $player;
		$data['id'] = $id;
		$this->load->view("guild_menu", $data);
		$this->load->view("guild_changeRank", $data);
	}
	
	public function kick($id, $player) {
		$ide = new IDE;
		$ide->requireLogin();
		$id = (int)$id;
		$player = (int)$player;
		if(empty($id)) $ide->goPrevious();
		if(empty($player)) $ide->goPrevious();
		$this->load->model("guilds_model");
		$data = array();
		$data['guild'] = $this->guilds_model->getGuildInfo($id);
		if(empty($data['guild'])) $ide->goPrevious();
		if(!$this->guilds_model->isGuildLeader($data['guild'][0]['ownerid']) and !$this->guilds_model->isLeader($id)) $ide->goPrevious();
		if(!$this->guilds_model->isGuildMember($id, $player)) $ide->goPrevious();
		
		$online = $this->guilds_model->CanUpdate($player);
		if($online['online'] != 0) {
			error("".$online['name']." is logged in, it needs to be logged out.");
			return false;
		}
		else {
			$this->guilds_model->kick($player);
			$ide->redirect(WEBSITE."/index.php/guilds/members/".$id);
		}
	}
	
	public function leave($id, $player) {
		$ide = new IDE;
		$ide->requireLogin();
		$id = (int)$id;
		$player = (int)$player;
		if(empty($id)) $ide->redirect(WEBSITE."/index.php/guilds");
		if(empty($player)) $ide->redirect(WEBSITE."/index.php/guilds");
		$this->load->model("guilds_model");
		$data = array();
		$data['guild'] = $this->guilds_model->getGuildInfo($id);
		if(empty($data['guild'])) $ide->redirect(WEBSITE."/index.php/guilds");
		if(!$this->guilds_model->isGuildMember($id, $player)) $ide->redirect(WEBSITE."/index.php/guilds/view/".$id);
		
		$online = $this->guilds_model->CanUpdate($player);
		if($online['online'] != 0) {
			error("Character ".$online['name']." is logged in, please logout with it first.");
			return false;
		}
		else {
			$this->guilds_model->leave($player);
			$ide->redirect(WEBSITE."/index.php/guilds/view/".$id);
		}
	}
	
	public function motd($id) {
		$ide = new IDE;
		$ide->requireLogin();
		$id = (int)$id;
		if(empty($id)) $ide->redirect(WEBSITE."/index.php/guilds");
		$data = array();
		$this->load->model("guilds_model");
		$data['guild'] = $this->guilds_model->getGuildInfo($id);
		if(empty($data['guild'])) $ide->redirect(WEBSITE."/index.php/guilds");
		if(!$this->guilds_model->isGuildLeader($data['guild'][0]['ownerid']) and !$this->guilds_model->isLeader($id)) $ide->redirect(WEBSITE."/index.php/guilds");
		$data['id'] = $id;
		$data['motd'] = $data['guild'][0]['motd'];
			if($_POST) {
				$_POST['motd'] = decodeString($_POST['motd']);
				$this->load->library("form_validation");
				$this->form_validation->set_rules('motd', 'MOTD', 'max_lenght[255]|alpha_space');
				if($this->form_validation->run() == true) {
					$this->guilds_model->changeMotd($id, $_POST['motd']);
					success("MOTD has been changed.");
					$ide->redirect(WEBSITE."/index.php/guilds/management/".$id, 2);
				}
				
			}
		$this->load->helper("form_helper");
		$this->load->view("guild_menu", $data);
		$this->load->view("guild_motd", $data);
	}
	
	public function logo($id) {
		$ide = new IDE;
		$ide->requireLogin();
		$id = (int)$id;
		if(empty($id)) $ide->goPrevious();
		$data = array();
		$this->load->model("guilds_model");
		$data['guild'] = $this->guilds_model->getGuildInfo($id);
		if(empty($data['guild'])) $ide->goPrevious();
		if(!$this->guilds_model->isGuildLeader($data['guild'][0]['ownerid']) and !$this->guilds_model->isLeader($id)) $ide->redirect(WEBSITE."/index.php/guilds");
		$data['id'] = $id;
		
				require("config.php");
				$cfg['upload_path'] = 'public/guild_logos';
				$cfg['allowed_types'] = 'gif';
				$cfg['max_size']	= '128';
				$cfg['max_width']  = '64';
				$cfg['max_height']  = '64';
				$cfg['file_name'] = $id;
				$cfg['overwrite'] = true;
				$this->load->library('upload', $cfg);
				if($this->upload->do_upload("logo"))
					success("Logo has been changed.");
				$data['error'] = $this->upload->display_errors();
		
			
		$this->load->helper("form_helper");
		$this->load->view("guild_menu", $data);
		$this->load->view("guild_logo", $data);
	}
	
	public function delete($id) {
		$ide = new IDE;
		$ide->requireLogin();
		$id = (int)$id;
		if(empty($id)) $ide->redirect(WEBSITE."/index.php/guilds");
		$data = array();
		$this->load->model("guilds_model");
		$data['guild'] = $this->guilds_model->getGuildInfo($id);
		if(empty($data['guild'])) $ide->redirect(WEBSITE."/index.php/guilds");
		if(!$this->guilds_model->isGuildLeader($data['guild'][0]['ownerid']) and !$this->guilds_model->isLeader($id)) $ide->redirect(WEBSITE."/index.php/guilds");
		$this->guilds_model->deleteGuild($id);
		
		$this->load->model("forum_model");		
		$this->forum_model->deleteBoardByGuild($id);
		
		$ide->redirect(WEBSITE."/index.php/guilds");
	}

}
?>
