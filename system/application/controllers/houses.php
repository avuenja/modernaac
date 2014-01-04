<?php 

	class Houses extends Controller {
		
		public function index() {
			$ide = new IDE;
			$ide->redirect(WEBSITE."/index.php/houses/main");
		}
		
		public function main($action = 0) {
			require("config.php");
			$ide = new IDE;
			$this->load->model("house_model");
			$data = array();
			$this->load->helper("form_helper");
			$this->load->library('pagination');
			$config['base_url'] = WEBSITE.'/index.php/houses/main/';
			$config['total_rows'] = $this->house_model->getHousesAmount();
			$config['per_page'] = $config['housesLimit']; 
			$this->pagination->initialize($config); 
			$data['pages'] = $this->pagination->create_links();
			$data['houses'] = $this->house_model->getHouses();
			$this->load->view("house_list", $data);
		}
		
		public function view($id) {
			$ide = new IDE;
			$id = (int)$id;
			if(empty($id)) $ide->goPrevious();
			$this->load->model("house_model");
			$data = array();
			$data['house'] = $this->house_model->loadHouse($id);
			if(empty($data['house'])) $ide->goPrevious();
				if($ide->isLogged())
					$data['characters'] = $this->house_model->getCharactersName();
			$data['id'] = $id;
			$this->load->view("view_house", $data);
		}
		
		function _checkCharacter($id) {
			$id = (int)$id;
			$this->load->model("house_model");
			$this->load->helper("url");
			$house = (int)$this->uri->segment(3);
			$data = $this->house_model->loadHouse($house);
			$character = $this->house_model->loadCharacter($id);
				if(empty($character)) {
					$this->form_validation->set_message('_checkCharacter', 'Character could not be found.');
					return false;
				}
				else if(!empty($character[0]['owner'])) {
					$this->form_validation->set_message('_checkCharacter', 'This character is already owning a house.');
					return false;
				}
				else if(!empty($character[0]['player_id'])) {
					$this->form_validation->set_message('_checkCharacter', 'This character is already bidding on house.');
					return false;
				}
				else if($_REQUEST['bid'] > $character[0]['balance']) {
					$this->form_validation->set_message('_checkCharacter', 'This character does not have enough money. The money must be in the bank.');
					return false;
				}
				else if($character[0]['world_id'] != $data[0]['world_id']) {
					$this->form_validation->set_message('_checkCharacter', 'The character must be on the same world as the house.');
					return false;
				}
				else
					return true;
		}
		
		function _checkBid($bid) {
			$bid = (int)$bid;
			$this->load->model("house_model");
			$this->load->helper("url");
			$house = (int)$this->uri->segment(3);
			$data = $this->house_model->getBids($house);
				if($data[0]['endtime'] > time()) {
					$this->form_validation->set_message('_checkBid', 'This auction has already finished.');
					return false;
				}
				elseif($bid <= $data[0]['bid']) {
					$this->form_validation->set_message('_checkBid', 'The bid must be higher than the minimum bid.');
					return false;
				}
				elseif($bid <= $data[0]['limit']) {
					$this->form_validation->set_message('_checkBid', 'You have been outbided! The current bid is '.$bid.'.');
					$this->house_model->setBid($house, $bid);
					return false;
				}
				else
					return true;
		}
		
		function _checkStartingBid($bid) {
			if($bid < 1) {
				$this->form_validation->set_message('_checkStartingBid', 'The big must be atleast 1 gold.');
				return false;
			}
			else
				return true;
		}
		
		public function start_auction($id) {
			$ide = new IDE;
			$id = (int)$id;
			$ide->requireLogin();
			if(empty($id)) $ide->goPrevious();
			$this->load->model("house_model");
			$data = array();
			$data['house'] = $this->house_model->loadHouse($id);
			if(empty($data['house'])) $ide->goPrevious();
			if(!empty($data['house'][0]['owner'])) $ide->goPrevious();
			if($data['house'][0]['bid'] > 0) $ide->redirect(WEBSITE."/index.php/houses/join_auction/".$id);
			if($data['house'][0]['guild'] == 0) 
				$data['characters'] = $this->house_model->getAllowedCharacters($data['house'][0]['world_id']);
			else
				$data['characters'] = $this->house_model->getAllowedGuildCharacters($data['house'][0]['world_id']);
			$data['id'] = $id;
			$this->load->helper("form_helper");
				if($_POST) {
					$this->load->library("form_validation");
					$this->form_validation->set_rules('bid', 'Bid', 'required|integer|callback__checkStartingBid');
					$this->form_validation->set_rules('character', 'Character', 'required|integer|callback__checkCharacter');
					if($this->form_validation->run() == true) {
						$this->house_model->createAuction($id, $_POST['character'], $_POST['bid'], $data['house'][0]['world_id']);
						$ide->redirect(WEBSITE."/index.php/houses/view/".$id);
					}
				}
			$this->load->view("start_house_auction", $data);
		}
		
		public function join_auction($id) {
			$ide = new IDE;
			$id = (int)$id;
			$ide->requireLogin();
			if(empty($id)) $ide->goPrevious();
			$this->load->model("house_model");
			$data = array();
			$data['house'] = $this->house_model->loadHouse($id);
			if(empty($data['house'])) $ide->goPrevious();
			if(!empty($data['house'][0]['owner'])) $ide->goPrevious();
			if(empty($data['house'][0]['bid'])) $ide->redirect(WEBSITE."/index.php/houses/start_auction/".$id);
			if($data['house'][0]['guild'] == 0) 
				$data['characters'] = $this->house_model->getAllowedCharacters($data['house'][0]['world_id']);
			else
				$data['characters'] = $this->house_model->getAllowedGuildCharacters($data['house'][0]['world_id']);
			if($data['house'][0]['endtime'] > time()) $ide->redirect(WEBSITE."/houses/view/".$id);
			$data['id'] = $id;
			$this->load->helper("form_helper");
				if($_POST) {
					$this->load->library("form_validation");
					$this->form_validation->set_rules('bid', 'Bid', 'required|integer|callback__checkBid');
					$this->form_validation->set_rules('character', 'Character', 'required|integer|callback__checkCharacter');
					if($this->form_validation->run() == true) {
						$new = $data['house'][0]['limit']+1;
						$this->house_model->newBid($id, $_POST['character'], $_POST['bid'], $new, $data['house'][0]['world_id']);
						$ide->redirect(WEBSITE."/index.php/houses/view/".$id);
					}
				}
			$this->load->view("join_house_auction", $data);
			
		}
		
		public function abandon($id) {
			$ide = new IDE;
			$id = (int)$id;
			$ide->requireLogin();
			if(empty($id)) $ide->goPrevious();
			$this->load->model("house_model");
			$house = $this->house_model->loadHouse($id);
			if(empty($house)) $ide->goPrevious();
			$characters = $this->house_model->getCharactersName();
			if(in_multiarray($house[0]['owner'], $characters)) {
				$this->house_model->abandon($id);
				$ide->goPrevious();
			}
			else
				$ide->goPrevious();
		}
		
		
		
	}

?>