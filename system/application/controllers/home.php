<?php

class Home extends Controller {

	public function index() {
		require("config.php");
		$this->load->model("home_model");
		$data = array();
		$data['news'] = $this->home_model->getAllNews();
		$this->load->view("home", $data);
	}
	
	public function archive() {
		require("config.php");
		$this->load->model("home_model");
		$data = array();
		$this->load->library('pagination');
		$config['base_url'] = WEBSITE.'/index.php/home/archive/';
		$config['total_rows'] = $this->home_model->getNewsAmount();
		$config['per_page'] = $config['newsLimit']; 
		$this->pagination->initialize($config); 
		$data['pages'] = $this->pagination->create_links();
		$data['news'] = $this->home_model->getArchiveNews();
		$this->load->view("archive", $data);
	}
	
	public function _checkPlayer($id) {
		$this->load->model("home_model");
		if($this->home_model->playerExistsOnAccount($id)) return true; else { $this->form_validation->set_message('_checkPlayer', 'This character does not belongs to you.'); return false; }
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
	
	public function view($id) {
		require("config.php");
		$id = (int)$id;
		$ide = new IDE;
		if(empty($id)) $ide->redirect(WEBSITE."/index.php/home/");
		$this->load->model("home_model");
		$data = array();
		$data['news'] = $this->home_model->loadNews($id);
		$this->load->plugin('captcha');
		$vals = array(
					'word'		 => '',
					'img_path'	 => 'system/captcha/',
					'img_url'	 => WEBSITE.'/system/captcha/',
					'font_path'	 => WEBSITE.'/system/fonts/texb.ttf',
					'img_width'	 => '156',
					'img_height' => 30,
					'expiration' => 120
				);
			if(!$_POST) {
				$cap = create_captcha($vals);	
			}
			if($data['news'] == false) {
				error("Could not find news.");
				$ide->redirect(WEBSITE."/index.php/home", 2);
			}
			else {
					if($_POST) {
						$this->load->library("form_validation");
						$this->form_validation->set_rules('character', 'Character', 'required|callback__checkPlayer');
						$this->form_validation->set_rules('body', 'Comment', 'required|min_lenght[3]|max_lenght[300]');
						$this->form_validation->set_rules('captcha', 'Captcha', 'required|callback__checkCaptcha');
						if($this->form_validation->run() == TRUE) {
							$this->home_model->addComment($id, $_POST['character'], $_POST['body']);
							success("Comment has been posted.");
							$cap = create_captcha($vals);
							$_POST['body'] = "";
						}
						else {
							$cap = create_captcha($vals);
						}
					}
				$this->load->library('pagination');
				$config['base_url'] = WEBSITE.'/index.php/home/view/'.$id.'/';
				$config['total_rows'] = $this->home_model->getCommentsAmount($id);
				$data['comments'] = $this->home_model->getComments($id);
				$config['per_page'] = $config['commentLimit'];
				$config['uri_segment'] = 4;
				$this->pagination->initialize($config); 
				$data['pages'] = $this->pagination->create_links();
				$data['characters'] = $this->home_model->getCharacters();
				$this->load->helper("form_helper");
				$data['id'] = $id;
				$_SESSION['captcha'] = @$cap['word'];
				$data['captcha'] = @$cap['image'];
				$this->load->view("view_news", $data);
				
			}	
	}
	
	public function delete_comment($id) {
		$ide = new IDE;
		$this->load->model("home_model");
		$comment = $this->home_model->getComment($id);
		if(empty($comment))
			$ide->redirect(WEBSITE."/index.php/home");
		else {
			if($ide->isAdmin()) {
				$this->home_model->deleteComment($id);
				$ide->redirect(WEBSITE."/index.php/home/view/".$comment[0]['news_id']);
			}
			else {
				$characters = $this->home_model->getCharacters();
				if(in_array($comment[0]['author'], $characters[0])) {
					$this->home_model->deleteComment($id);
					$ide->redirect(WEBSITE."/index.php/home/view/".$comment[0]['news_id']);
				}
				else {
					$ide->redirect(WEBSITE."/index.php/home/view/".$comment[0]['news_id']);
				}
			}
		}
	}
	
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */