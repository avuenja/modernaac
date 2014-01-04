<?php
/* 
+I.D.E ENGINE+
Controller of Account for Modern AAC - Powered by IDE Engine.
A lot of new functionality and variables can be hard-coded here.
If you do NOT understand the code, do NOT change anything in here.
*/
	
	class Account extends Controller {
	
		/* Main index of Account controllers, also work as a __construct(); It is called by engine as a default. */
		function index($action = 0) {
			if($action == 1) success("Your new character has been created!");
			if($action == 2) success("Your nickname has been set! Thank you!");
			if($action == 3) success("You have exceded the maximum amount of characters per account.");
			if($action == 4) success("Your profile has been updated.");
			if($action == 5) success("Your avatar has been updated!");
			$this->load->model("Account_model");
			if(empty($_SESSION['account_id'])) $_SESSION['account_id'] = $this->Account_model->getAccountID();
			$ide = new IDE;
			$ide->requireLogin();
			if(empty($_SESSION['nickname'])) $ide->redirect(WEBSITE."/index.php/account/setNickname");
			$data = array();
			$data['loggedUser'] = $_SESSION['name'];
			$data['characters'] = $this->Account_model->getCharacters();
			$data['messages'] = $this->Account_model->checkMessages();
			$ots = POT::getInstance();
			$ots->connect(POT::DB_MYSQL, connection());
			$account = $ots->createObject('Account');
			try { $account->find($_SESSION['name']); } catch(Exception $e) {show_error('There was a problem during loading account. Err code: 220212072010 Futher details: '.$e->getMessage());}
			$data['account'] = $account;
			$data['acc'] = $this->Account_model->load($_SESSION['account_id']);
			$recovery_key = $this->Account_model->getRecoveryKey($_SESSION['name']);
			if($recovery_key === "") alert("You don't have recovery key set up. Click <a href='".WEBSITE."/index.php/account/generate_recovery_key'><b>here</b></a> to create one. We strongly recommend to create one now for security reasons.");
			/* Load view of account page and send data to it. */
			$this->load->view('account', $data);
		}
		
		/*
		Function to check if account with this name already exists, it is used by create controller as a callaback in form validation. 
		It should be made as an abstract class of database in Model, but I don't think there is point of it.
		*/
		function _account_exists($name) {
			$ots = POT::getInstance();
			$ots->connect(POT::DB_MYSQL, connection());
			$account = new OTS_Account();
			try { $account->find($name); } catch(Exception $e) {show_error('There was a problem during loading account. Err code: 220512072010 Futher details: '.$e->getMessage());}
			if($account->isLoaded()) { $this->form_validation->set_message('_account_exists', 'Account with this name already exists.');return false;} else return true;
		}
		
		function _checkCaptcha($word) {
			if(strtolower($word) == strtolower($_SESSION['captcha']) && !empty($_SESSION['captcha'])) {
				return true;
			}
			else {
				$this->form_validation->set_message('_checkCaptcha', 'Captcha word is incorrect.');
				return false;
			}
		}
		
		function _nicknameExists($name) {
			$this->load->model("account_model");
			if($this->account_model->nicknameExists($name)) {
				$this->form_validation->set_message('_nicknameExists', 'This nickname already exists!.');
				return false;
			}
			else
				return true;
		}
		function _emailExists($email) {
			$this->load->model("account_model");
			if($this->account_model->emailExists($email)) {
				$this->form_validation->set_message('_emailExists', 'This email is already used by another account already exists!');
				return false;
			}
			else
				return true;
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
		
		function _checkDelay() {
			global $config;
			if(!isset($_SESSION['accountDelay'])) $_SESSION['accountDelay'] = 0;
			if($config['accountDelay']) {
				if(@(time()-$_SESSION['accountDelay']) > 240) {
					return true;
				}
				else {
					$this->form_validation->set_message('_checkDelay', 'You cannot create another account just after another. Please wait few minutes.');
					return false;
				}
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

		/* Controller of creating new account. New values can be hard-coded here. (only experienced users) */
		function create($ajax = 0) {
			require_once("system/application/config/create_character.php");
			$ide = new IDE;
			global $config;
			if($ajax == 1 && $ide->isLogged()) exit;
			if($ide->isLogged()) $ide->redirect(WEBSITE.'/index.php/account');
			$this->load->plugin('captcha');
			$this->load->helper('form');
			
			$vals = array(
					'word'		 => '',
					'img_path'	 => 'system/captcha/',
					'img_url'	 => WEBSITE.'/system/captcha/',
					'font_path'	 => WEBSITE.'/system/fonts/texb.ttf',
					'img_width'	 => '156',
					'img_height' => 30,
					'expiration' => 120
				);
			if(!$_POST && $ajax == 0) {
				$cap = create_captcha($vals);	
			}
			if($_POST) {
				$this->load->library('form_validation');
				$_POST['nickname'] = ucfirst(strtolower($_POST['nickname']));
				$this->form_validation->set_rules('name', 'Account Name', 'required|min_length[4]|max_length[32]|callback__account_exists|alpha_numeric|callback__checkDelay');
				$this->form_validation->set_rules('nickname', 'Nickname', 'required|min_length[4]|max_length[32]|callback__nicknameExists');
				$this->form_validation->set_rules('password', 'Password', 'required|matches[repeat]|min_length[4]|max_length[255]');
				$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__emailExists');
				$this->form_validation->set_rules('character_name', 'Character Name', 'required|min_length[4]|max_length[32]|nickname|callback__characterExists|callback__validName');
				$this->form_validation->set_rules('city', 'City', 'required|integer|callback__checkCity');
				$this->form_validation->set_rules('world', 'World', 'required|integer|callback__checkWorld');
				$this->form_validation->set_rules('vocation', 'Vocation', 'required|integer|callback__checkVocation');
				$this->form_validation->set_rules('sex', 'Sex', 'required|integer|callback__checkSex');
				$this->form_validation->set_rules('captcha', 'Captcha', 'required|callback__checkCaptcha');
				if($this->form_validation->run() == TRUE) {
					require(APPPATH.'config/ide_default.php');
					$ots = POT::getInstance();
					$ots->connect(POT::DB_MYSQL, connection());
					$account = new OTS_Account();
					$name = $account->createNamed($_POST['name']);
					$account->setPassword(sha1($_POST['password']));
					$account->setEmail($_POST['email']);
					$account->setCustomField('nickname', $_POST['nickname']);
					$account->setCustomField('premdays', PREMDAYS);
					$account->setCustomField('lastday', $_SERVER['REQUEST_TIME']);
					try {
						$account->save();
						unset($account);
						$_SESSION['logged'] = 1;
						$_SESSION['name'] = $_POST['name'];
						$_SESSION['nickname'] = $_POST['nickname'];
						$_SESSION['accountDelay'] = time();
						
						$account = $ots->createObject('Account');
						$account->find($_POST['name']);
						
						$sample = new OTS_Player();
						$sample->find($config['newchar_vocations'][$_POST['world']][$_POST['vocation']]);
						if(!$sample->isLoaded()) {	show_error('Sample character could not be found!'); }
						
						// Create new character
						$player = $ots->createObject('Player');
						$player->setName($this->strFirst($_POST['character_name']));
                		$player->setAccount($account);
						$player->setWorld($_POST['world']);
                		$player->setGroup($sample->getGroup());
                		$player->setSex($_POST['sex']);
                		$player->setVocation($sample->getVocation());
                		$player->setConditions($sample->getConditions());
               		 	$player->setRank($sample->getRank());
               		 	$player->setLookAddons($sample->getLookAddons());
                		$player->setTownId($_POST['city']);
                		$player->setExperience($sample->getExperience());
                		$player->setLevel($sample->getLevel());
                		$player->setMagLevel($sample->getMagLevel());
                		$player->setHealth($sample->getHealth());
                		$player->setHealthMax($sample->getHealthMax());
                		$player->setMana($sample->getMana());
                		$player->setManaMax($sample->getManaMax());
                		$player->setManaSpent($sample->getManaSpent());
                		$player->setSoul($sample->getSoul());
                		$player->setDirection($sample->getDirection());
                		$player->setLookBody($sample->getLookBody());
                		$player->setLookFeet($sample->getLookFeet());
                		$player->setLookHead($sample->getLookHead());
                		$player->setLookLegs($sample->getLookLegs());
                		$player->setLookType($sample->getLookType());
                		$player->setCap($sample->getCap());
						$player->setPosX($startPos['x']);
               			$player->setPosY($startPos['y']);
                		$player->setPosZ($startPos['z']);
                		$player->setLossExperience($sample->getLossExperience());
                		$player->setLossMana($sample->getLossMana());
                		$player->setLossSkills($sample->getLossSkills());
                		$player->setLossItems($sample->getLossItems());
						$player->setLossContainers($sample->getLossContainers());
                		$player->save();
						$_SESSION['characterDelay'] = $_SERVER['REQUEST_TIME'];
						unset($player);
                		$player = $ots->createObject('Player');
                		$player->find($_POST['character_name']);
						if($player->isLoaded())
                		{
                    		$player->setSkill(0,$sample->getSkill(0));
                    		$player->setSkill(1,$sample->getSkill(1));
                    		$player->setSkill(2,$sample->getSkill(2));
                    		$player->setSkill(3,$sample->getSkill(3));
                    		$player->setSkill(4,$sample->getSkill(4));
                    		$player->setSkill(5,$sample->getSkill(5));
                    		$player->setSkill(6,$sample->getSkill(6));
                    		$player->save();
							$SQL = POT::getInstance()->getDBHandle();
                    		$loaded_items_to_copy = $SQL->query("SELECT * FROM player_items WHERE player_id = ".$sample->getId()."");
                    		foreach($loaded_items_to_copy as $save_item)
								$SQL->query("INSERT INTO `player_items` (`player_id` ,`pid` ,`sid` ,`itemtype`, `count`, `attributes`) VALUES ('".$player->getId()."', '".$save_item['pid']."', '".$save_item['sid']."', '".$save_item['itemtype']."', '".$save_item['count']."', '".$save_item['attributes']."');");
						
							if($ajax == 0)
								$ide->redirect(WEBSITE.'/index.php/account');
							else
								$ide->criticalRedirect(WEBSITE.'/index.php/account');
						}
					}
					catch(Exception $e) {
						error($e->getMessage());
					}
				}
				else {
					if($ajax == 0) $cap = create_captcha($vals);
				}
			}
			if($ajax == 0) {
				$_SESSION['captcha'] = $cap['word'];
				$data['captcha'] = $cap['image'];
			}
			#Load view of creating account
			if($ajax == 1) {
				echo error(validation_errors());
				$ide->system_stop();
			}
			else {
				$this->load->view('create', $data);
			}
		}
		
		/* Function to check if passed login and password are correct, it uses abstract database model. */
		function _check_login() {
			$this->load->model("Account_model");
			if($this->Account_model->check_login() == false) {
				$this->form_validation->set_message("_check_login", "Account name or password are incorrect.");
				return false;
			}
			else
				return true;
		}
		
		/* Login controller  */
		function login($action = 0) {
			if((int) $action == 1) success("You have been logged out.");
			if((int) $action == 2) success("Your account has been recovered. You may login now.");
			$ide = new IDE;
			$this->load->helper("form");
			$this->load->library("form_validation");
			if($_POST) {
				$this->form_validation->set_rules('name', 'Account Name', 'required|callback__check_login');
				$this->form_validation->set_rules('pass', 'Password', 'required');
				if(in_array($_POST['name'], $GLOBALS['config']['restrictedAccounts']))
					error("The account you try to access is restricted!");
				else {
					if($this->form_validation->run() == true) {
						$_SESSION['logged'] = 1;
						$_SESSION['name'] = $_POST['name'];
						if(!empty($_SESSION['forward'])) {
							$forward = $_SESSION['forward'];
							$_SESSION['forward'] = "";
							$ide->redirect($forward);
						}
						else 
							$ide->redirect(WEBSITE.'/index.php/account');
					
					}
				}
			}
			/* Load view of login page. */
			$this->load->view("login");
			
		}
		/* Function to logout from account. */
		function logout() {
			$ide = new IDE;
			$_SESSION['logged'] = '';
			$_SESSION['account_id'] = '';
			$_SESSION['name'] = '';
			$_SESSION['admin'] = 0;
			$_SESSION['forward'] = "";
			$ide->redirect('login/1');
		}
		
		/* Controller to generate random recovery key and save it, accessed by user, only once per account. */
		function generate_recovery_key() {
			$this->load->helper("form");
			$ide = new IDE;
			$ide->requireLogin();
			$this->load->model("Account_model");
			if($_POST) {
				$data['info'] = '';
				$key = $this->Account_model->generateKey($_SESSION['name']);
				success("<center><font size='4'>$key</font></center>");
				alert("<b>Save this recovery key, you see this key only once! You will never see it again, don't refresh or move away from this website until you save it!</b>");
			}
			else
			$data['info'] = '<center id=\'info\'><b>Press this button to generate your unique recovery key. <br>Remember! You can do this only once! Your recovery key will be shown only once! Write it down, for security reasons we recommend to not save it on computers hard drive!</b></center><br><center><input type=\'submit\' value=\'Generate\' name=\'submit\'></center>';
			/* Load view of generating new recovery key. */
			$this->load->view('generate_recovery_key', $data);
		
		}
		
		function _checkCurrentPassword($pass) {
			$this->load->model("account_model");
			if($this->account_model->checkPassword($pass)) 
				return true;
			else {
				$this->form_validation->set_message("_checkCurrentPassword", "Current password is incorrect.");
				return false;
			}
		}
		
		function changepassword() {
			$ide = new IDE;
			$ide->requireLogin();
			$this->load->helper("form_helper");
			if($_POST) {
				$this->load->library("form_validation");
				$this->form_validation->set_rules('current', 'Current Password', 'required|callback__checkCurrentPassword');
				$this->form_validation->set_rules('password', 'Password', 'required|matches[repeat]|min_length[4]|max_length[255]');
				if($this->form_validation->run() == true) {
					$this->load->model("account_model");
					$this->account_model->changePassword($_POST['password'], $_SESSION['name']);
					success("Your password has been changed.");
					$ide->redirect(WEBSITE."/index.php/account", 2);
				}
			}
			$this->load->view("changepassword");
		}
		
		function editcomment($id) {
			$ide = new IDE;
			$ide->requireLogin();
			if(empty($id)) $ide->redirect(WEBSITE."/index.php/account");
			$this->load->model("account_model");
			if(!$this->account_model->isUserPlayer($id)) $ide->redirect(WEBSITE."/index.php/account");
			$data['id'] = $id;
				if($_POST) {
					$this->load->library("form_validation");
						$this->form_validation->set_rules('comment', 'Comment', 'max_length[255]|alpha_ide');
					if($this->form_validation->run() == true) {
						if(@$_POST['hide'] == 1)
							$this->account_model->changeComment($id, $_POST['comment'], true);
						else
							$this->account_model->changeComment($id, $_POST['comment'], false);
						success("Your comment has been changed.");
						$ide->redirect(WEBSITE."/index.php/account", 2);
					}
				}
			$data['comment'] = $this->account_model->getPlayerComment($id);
			$this->load->helper("form_helper");
			$this->load->view("edit_comment", $data);
		}
		
		function deletePlayer($id) {
			$ide = new IDE;
			$ide->requireLogin();
			$id = (int)$id;
			if(empty($id)) $ide->redirect(WEBSITE."/index.php/account");
			$this->load->model("account_model");
			if(!$this->account_model->isUserPlayer($id)) $ide->redirect(WEBSITE."/index.php/account");
			$this->account_model->deletePlayer($id);
			$ide->redirect(WEBSITE."/index.php/account");
		}
		
		public function setNickname() {
			$ide = new IDE;
			$ide->requireLogin();
			if(!empty($_SESSION['nickname'])) $ide->goPrevious();
			$this->load->helper("form_helper");
				if($_POST) {
					$_POST['nickname'] = ucfirst(strtolower($_POST['nickname']));
					$this->load->library("form_validation");
					$this->form_validation->set_rules('nickname', 'Nickname', 'required|min_length[4]|max_length[32]|nickname|callback__nicknameExists');
					$this->form_validation->set_rules('rules', 'Rules', 'required');
					if($this->form_validation->run()) {
						$this->load->model("account_model");
						$this->account_model->setNickname($ide->loggedAccountId(), $_POST['nickname']);
						$_SESSION['nickname'] = $_POST['nickname'];
						$ide->redirect(WEBSITE."/index.php/account/index/2");
					}
				}
			$this->load->view("setNickname");
			
		}
		
		function _validKey($key) {
			$this->load->model("account_model");
			$_POST['key'] = str_replace("-", "", $_POST['key']);
			if($this->account_model->checkKey($_POST['key'], $_POST['email'])) {
				return true;
			}
			else {
				$this->form_validation->set_message("_validKey", "Could not change password. Make sure email and recovery key are valid.");
				return false;
			}
		}
		
		public function lost() {
			$ide = new IDE;
			$this->load->helper("form_helper");
				if($_POST) {
					$_POST['key'] = str_replace("-", "", $_POST['key']);
				
					$this->load->library("form_validation");
					$this->form_validation->set_rules('password', 'Password', 'required|matches[repeat]|min_length[4]|max_length[255]');
					$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
					$this->form_validation->set_rules('key', 'Recovery Key', 'required|callback__validKey');
					
					if($this->form_validation->run()) {
						$this->load->model("account_model");
						$this->account_model->recoveryAccount($_POST['key'], $_POST['email'], $_POST['password']);
						$ide->redirect(WEBSITE."/index.php/account/login/2");
					}
				}
			$this->load->view("account_lost");
		}
		
	}

?>
