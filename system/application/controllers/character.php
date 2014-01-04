<?php

class Character extends Controller {
	function index() {
		parent::Controller();
	}
	
	function _characterExists($name) {
			$this->load->model("character_model");
			if($this->character_model->characterExists($name)) {
				$this->form_validation->set_message('_characterExists', 'This character name already exists, please choose another one!');
				return false;
			}
			else
				return true;
		}
	
	function _checkCity($id) {
		$this->config->load('create_character.php');
		if(!array_key_exists($id, $this->config->item('cities'))) {
			$this->form_validation->set_message('_checkCity', 'Unknown City');
			return false;
		}
		else
			return true;
	}
	
	function _checkWorld($id) {
		$this->config->load('create_character.php');
		if(!array_key_exists($id, $this->config->item('worlds'))) {
			$this->form_validation->set_message('_checkWorld', 'Unknown World');
			return false;
		}
		else
			return true;
	}
	
	function _checkVocation($id) {
		$this->config->load('create_character.php');
		if(!array_key_exists($id, $this->config->item('vocations'))) {
			$this->form_validation->set_message('_checkVocation', 'Unknown Vocation');
			return false;
		}
		else
			return true;
	}
	
	function _checkSex($id) {
		if($id != 0 and $id != 1) {
			$this->form_validation->set_message('_checkSex', 'Unknown Sex');
			return false;
		}
		else
			return true;
	}
	
	function _checkDelay() {
			global $config;
			if(!isset($_SESSION['characterDelay'])) $_SESSION['characterDelay'] = 0;
			if($config['characterDelay']) {
				if(@(time()-$_SESSION['characterDelay']) > 240) {
					return true;
				}
				else {
					$this->form_validation->set_message('_checkDelay', 'You cannot create another character just after another. Please wait few minutes.');
					return false;
				}
			}
			else
				return true;
		}
	
	function _validName($name) {
		require("config.php");
		$name = explode(" ", $name);
			foreach($name as $unit) {
				if(in_array(strtolower($unit), $config['invalidNameTags'])) {
					$this->form_validation->set_message('_validName', 'Invalid Name');
					return false;
				}
				else if(strlen($unit) == 1) {
					$this->form_validation->set_message('_validName', 'Invalid Name');
					return false;
				}
				else
					continue;
			}
	}
	
	// Function which make the player more real by tatu hunter
	// Eg: elder'Druid = Elder'Druid
	//	   elder'druid = Elder'druid
	//     druid theMaster = Druid themaster
	function strFirst($name) {
		$name = explode(' ', trim($name));
		for($i=0, $t = sizeof($name); $i<$t; ++$i)
			for($j=0, $l=strlen($name[$i]); $j<$l; ++$j)
				!$j ? 
				($name[$i][$j] = !$i ? ($name[$i][$j] == strtoupper($name[$i][$j]) ? $name[$i][$j] : strtoupper($name[$i][$j])): $name[$i][$j])  : 
				($name[$i][$j] = ($name[$i][$j-1] == '\'' ? $name[$i][$j] : 
				strtolower($name[$i][$j])));
	
		$ret = '';
		foreach($name as $k)
			$ret .= $k . ' ';
	
		return trim($ret);
	}
	
	function create_character($ajax = 0) {
		require_once("system/application/config/create_character.php");
		global $config;
		global $ide;
		$data['worlds'] = $config['worlds'];
		$data['cities'] = $config['cities'];
		$data['vocations'] = $config['vocations'];
		$this->load->model("character_model");
		$count = $this->character_model->getCount();
		if($count >= $config['maxCharacters'] && $ajax == 1) exit;
		if($count >= $config['maxCharacters']) $ide->redirect(WEBSITE."/index.php/account/index/3");
		$this->load->helper('form');
		$this->load->library('form_validation');
		if($_POST) {
			$_POST['name'] = trim(ucwords(strtolower(decodeString($_POST['name']))));
			$this->form_validation->set_rules('name', 'Player Name', "required|min_length[3]|max_length[20]|nickname|callback__characterExists|callback__validName|callback__checkDelay");
			$this->form_validation->set_rules('city', 'City', 'required|integer|callback__checkCity');
			$this->form_validation->set_rules('world', 'World', 'required|integer|callback__checkWorld');
			$this->form_validation->set_rules('vocation', 'Vocation', 'required|integer|callback__checkVocation');
			$this->form_validation->set_rules('sex', 'Sex', 'required|integer|callback__checkSex');
				
			if($this->form_validation->run() == true) {
				$ide = new IDE;
				$char_to_copy_name = $config['newchar_vocations'][$_POST['world']][$_POST['vocation']];
				$ots = POT::getInstance();
				$ots->connect(POT::DB_MYSQL, connection());
				$char_to_copy = new OTS_Player();
                $char_to_copy->find($char_to_copy_name);
				$this->load->model("character_model");
				/* This code (Most of it actually) has been taken from Gesior AAC. */
				$account_logged = $ots->createObject('Account');
				$account_logged->load($this->character_model->getAccountID());
				if(!$char_to_copy->isLoaded()) {	show_error('Sample character could not be found!'); }
				if($_POST['sex'] == "0")
                $char_to_copy->setLookType(136);
                $player = $ots->createObject('Player');
                $player->setName($this->strFirst($_POST['name']));
                $player->setAccount($account_logged);
				$player->setWorld($_POST['world']);
                $player->setGroup($char_to_copy->getGroup());
                $player->setSex($_POST['sex']);
                $player->setVocation($char_to_copy->getVocation());
                $player->setConditions($char_to_copy->getConditions());
                $player->setRank($char_to_copy->getRank());
                $player->setLookAddons($char_to_copy->getLookAddons());
                $player->setTownId($_POST['city']);
                $player->setExperience($char_to_copy->getExperience());
                $player->setLevel($char_to_copy->getLevel());
                $player->setMagLevel($char_to_copy->getMagLevel());
                $player->setHealth($char_to_copy->getHealth());
                $player->setHealthMax($char_to_copy->getHealthMax());
                $player->setMana($char_to_copy->getMana());
                $player->setManaMax($char_to_copy->getManaMax());
                $player->setManaSpent($char_to_copy->getManaSpent());
                $player->setSoul($char_to_copy->getSoul());
                $player->setDirection($char_to_copy->getDirection());
                $player->setLookBody($char_to_copy->getLookBody());
                $player->setLookFeet($char_to_copy->getLookFeet());
                $player->setLookHead($char_to_copy->getLookHead());
                $player->setLookLegs($char_to_copy->getLookLegs());
                $player->setLookType($char_to_copy->getLookType());
                $player->setCap($char_to_copy->getCap());
                $player->setPosX($startPos['x']);
                $player->setPosY($startPos['y']);
                $player->setPosZ($startPos['z']);
                $player->setLossExperience($char_to_copy->getLossExperience());
                $player->setLossMana($char_to_copy->getLossMana());
                $player->setLossSkills($char_to_copy->getLossSkills());
                $player->setLossItems($char_to_copy->getLossItems());
				$player->setLossContainers($char_to_copy->getLossContainers());
                $player->save();
                $_SESSION['characterDelay'] = time();
				unset($player);
                $player = $ots->createObject('Player');
                $player->find($_POST['name']);
				if($player->isLoaded())
                {
					$player->setCustomField('world_id', (int) $_POST['world']);
                    $player->setSkill(0,$char_to_copy->getSkill(0));
                    $player->setSkill(1,$char_to_copy->getSkill(1));
                    $player->setSkill(2,$char_to_copy->getSkill(2));
                    $player->setSkill(3,$char_to_copy->getSkill(3));
                    $player->setSkill(4,$char_to_copy->getSkill(4));
                    $player->setSkill(5,$char_to_copy->getSkill(5));
                    $player->setSkill(6,$char_to_copy->getSkill(6));
                    $player->save();
					$SQL = POT::getInstance()->getDBHandle();
                    $loaded_items_to_copy = $SQL->query("SELECT * FROM player_items WHERE player_id = ".$char_to_copy->getId()."");
                    foreach($loaded_items_to_copy as $save_item)
						$SQL->query("INSERT INTO `player_items` (`player_id` ,`pid` ,`sid` ,`itemtype`, `count`, `attributes`) VALUES ('".$player->getId()."', '".$save_item['pid']."', '".$save_item['sid']."', '".$save_item['itemtype']."', '".$save_item['count']."', '".$save_item['attributes']."');");
                        
					if($ajax == 0)
						$ide->redirect(WEBSITE.'/index.php/account/index/1');
					else
						$ide->criticalRedirect(WEBSITE.'/index.php/account/index/1');
                }
			}
		}
		if($ajax == 1) {
			error(validation_errors());
			$ide->system_stop();
		}
		else
			$this->load->view('create_character', $data);
	}
	
	public function view($name = null) {
		global $config;
		if(!@is_array($_SESSION['sCharacters']))
			$_SESSION['sCharacters'] = array();
		$ide = new IDE;
		if(!empty($name)) {
			$name = decodeString($name);
			$data['character'] = $name;
			$ots = POT::getInstance();
			$ots->connect(POT::DB_MYSQL, connection());
			$player = $ots->createObject('Player');
            $player->find($name);
			if(!$player->isLoaded()) {
				$ide->redirect("../../character/view/");
			}
			else {
				if(!in_multiarray($name, $_SESSION['sCharacters'])) {
					array_unshift($_SESSION['sCharacters'], array("name"=>$name, "time"=>time()));
					if(count($_SESSION['sCharacters']) > $config['characterSearchLimit'])
						array_pop($_SESSION['sCharacters']);
				}
				
				$data['player'] = $player;
				$data['account'] = $player->getAccount();
			}
			$this->load->view('view_character.php', $data);
		}
		else {
			$this->load->helper("form");
				if($_POST) {
					$name = decodeString($_POST['name']);
					$ots = POT::getInstance();
					$ots->connect(POT::DB_MYSQL, connection());
					$player = new OTS_Player();
					$player->find($name);
					if($player->isLoaded()) {
						$ide->redirect("../character/view/$name");
					}
					else {
						error("$name could not be found.");
					}
					
				}
			$this->load->view('character_search.php');
		}
	
	}
	
	public function online() {
		$this->load->helper('form');
		$this->load->model("character_model");
		$data['players'] = $this->character_model->getPlayersOnline();
		$this->load->view("online_players.php", $data);
	}
	
	public function clearHistory() {
			global $ide;
			$_SESSION['sCharacters'] = array();
				echo "History Cleared!";
			$ide->system_stop();
		}
}

?>