<?php 
class video extends controller {
	public function index() {
		$ide = new IDE;
		$ide->redirect(WEBSITE."/index.php/video/main");
	}
	
	public function main($action = 0) {
		if($action == 1) error("You need to have atleast one character on your account in order to add a video.");
		if($action == 2) error("Video could not be found.");
		if($action == 3) error("Video could not be loaded.");
		$this->load->model("videos_model");
		$data = array();
		$this->load->helper("form_helper");
		$data['videos'] = $this->videos_model->getMainVideos();
		$this->load->view("main_video", $data);
	}
	
	function _checkCharacter($id) {
		$this->load->model("videos_model");
		if($this->videos_model->checkCharacter($id))
			return true;
		else {
			$this->form_validation->set_message('_checkCharacter', 'Could not find this character.');
			return false;
		}
	}
	
	function _checkVideo($id) {
		if(@fopen("http://gdata.youtube.com/feeds/api/videos/".$id,"r"))
			return true;
		else {
			$this->form_validation->set_message('_checkVideo', 'Video could not be found. Please check YouTube Link.');
			return false;
		}
	}
	
	function videoExists($id) {
		$this->load->model("videos_model");
		if($this->videos_model->videoExists($id)) {
			$this->form_validation->set_message('_videoExists', 'This video already exists in our database.');
			return false;
		}
		else
			return true;
	}
	
	public function add() {
		$ide = new IDE;
		$ide->requireLogin();
		$data = array();
		$this->load->model("videos_model");
		$data['characters'] = $this->videos_model->getCharacters();
		if(empty($data['characters'])) $ide->redirect(WEBSITE."/index.php/video/main/1");
		$this->load->helper("form_helper");
			if($_POST) {
				$url = @parse_url($_POST['link']);
				@parse_str($url['query']);
				@$_POST['link'] = $v;
				$this->load->library("form_validation");
				$this->form_validation->set_rules('character', 'Character', 'required|integer|callback__checkCharacter');
				$this->form_validation->set_rules('title', 'Title', 'required|alpha_space|max_length[64]|min_length[3]');
				$this->form_validation->set_rules('description', 'Description', 'max_length[300]');
				$this->form_validation->set_rules('link', 'YouTube Link', 'required|callback__checkVideo|callback__videoExists');
				
				if($this->form_validation->run() == true) {
					$this->videos_model->addVideo($_POST['link'], $_POST['character'], $_POST['title'], $_POST['description']);
					success("Your video has been added.");
					$ide->redirect(WEBSITE."/index.php/video/main", 2);
				}
			}
		$this->load->view("add_video", $data);
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
		$ide = new IDE;
		if(!is_array(@$_SESSION['watched'])) @$_SESSION['watched'] = array();
		if(empty($id)) $ide->redirect(WEBSITE."/index.php/video/main");
		$this->load->model("videos_model");
		$data = array();
		$data['video'] = $this->videos_model->loadVideo($id);
		$this->load->library('pagination');
		$config['base_url'] = WEBSITE.'/index.php/video/view/'.$id.'/';
		$config['uri_segment'] = 4;
		$config['total_rows'] = $this->videos_model->getCommentsAmount($id);
		if(!in_array($id, @$_SESSION['watched'])) {
			$this->videos_model->addView($id);
			$_SESSION['watched'][] = $id;
		}
		$config['per_page'] = $config['videoCommentsLimit']; 
		$this->pagination->initialize($config); 
		$data['pages'] = $this->pagination->create_links();
		if(empty($data['video'])) $ide->redirect(WEBSITE."/index.php/video/main/2");
			if(!@fopen("http://gdata.youtube.com/feeds/api/videos/".$data['video']['youtube'],"r")) $ide->redirect(WEBSITE."/index.php/video/main/3");
		setTitle($data['video'][0]['title']);
		$this->load->helper("form_helper");
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
			else {
				$this->load->library("form_validation");
				$this->form_validation->set_rules('character', 'Character', 'required|integer|callback__checkCharacter');
				$this->form_validation->set_rules('comment', 'Comment', 'max_length[300]');
				$this->form_validation->set_rules('captcha', 'Captcha', 'required|callback__checkCaptcha');
				if($this->form_validation->run() == true) {
					$this->videos_model->createComment($id, $_POST['character'], $_POST['comment']);
					success("Comment has been added.");
					echo "<script>$(document).ready(function() { $('#add_comment').hide();  });</script>";
					$cap = create_captcha($vals);
				}
				else {
							$cap = create_captcha($vals);
				}
			}
		$_SESSION['captcha'] = @$cap['word'];
		$data['captcha'] = @$cap['image'];
		if($ide->isLogged()) $data['characters'] = $this->videos_model->getCharacters();
		$data['comments'] = $this->videos_model->getComments($id);
		$this->load->view("view_video", $data);
	}
	
	public function deleteComment($id) {
		$ide = new IDE;
		$this->load->model("videos_model");
		$comment = $this->videos_model->getCommentAuthor($id);
		if(empty($comment)) $ide->goPrevious();
		$characters = $this->videos_model->getCharacters();
		if(!$ide->isAdmin() && !in_multiarray($comment[0]['author'], $characters)) 
			$ide->goPrevious();
		else {
			$this->videos_model->deleteComment($id);
			$ide->goPrevious();
		}
		
		exit;
	}
	
	public function doSearch() {
		$ide = new IDE;
		if(empty($_REQUEST['query']))
			$ide->goPrevious();
		else 
			$ide->redirect(WEBSITE."/index.php/video/search/".$_REQUEST['query']);
	}
	
	public function search($query) {
		require("config.php");
		$ide = new IDE;
		if(empty($query)) $ide->redirect(WEBSITE."/index.php/video/main");
		$data = array();
		$this->load->model("videos_model");
		$data['videos'] = $this->videos_model->searchVideos($query);
		$this->load->library('pagination');
		$config['base_url'] = WEBSITE.'/index.php/video/search/'.$query.'/';
		$config['uri_segment'] = 4;
		$config['total_rows'] = $this->videos_model->searchVideosAmount($query);
		$data['query'] = $query;
		$config['per_page'] = $config['videoSearchLimit']; 
		$this->pagination->initialize($config); 
		$data['pages'] = $this->pagination->create_links();
		$this->load->view("video_search", $data);
	}
	
	public function my() {
		$ide = new IDE;
		$ide->requireLogin();
		$this->load->model("videos_model");
		$data = array();
		$data['videos'] = $this->videos_model->getMyVideos();
		$this->load->view("my_videos", $data);
	}
	
	public function edit($id) {
		$ide = new IDE;
		$ide->requireLogin();
		$this->load->model("videos_model");
		$data['video'] = $this->videos_model->loadVideo($id);
		if(empty($data['video'])) $ide->goPrevious();
		$characters = $this->videos_model->getCharacters();
		if(! in_multiarray($data['video'][0]['author'], $characters))$ide->goPrevious();
			if($_POST) {
				$this->load->library("form_validation");
				$this->form_validation->set_rules('title', 'Title', 'required|alpha_space|max_length[64]|min_length[3]');
				$this->form_validation->set_rules('description', 'Description', 'max_length[300]');
				if($this->form_validation->run() == true) {
					$this->videos_model->updateVideo($id, $_POST['title'], $_POST['description']);
					success("You have edited this video!");
					$ide->redirect(WEBSITE."/index.php/video/my", 2);
				}
			}
		$data['id'] = $id;
		$this->load->helper("form_helper");
		$this->load->view("edit_video", $data);
	}
	
	public function delete($id) {
		$ide = new IDE;
		$ide->requireLogin();
		$this->load->model("videos_model");
		$data['video'] = $this->videos_model->loadVideo($id);
		if(empty($data['video'])) $ide->goPrevious();
		$characters = $this->videos_model->getCharacters();
		if(! in_multiarray($data['video'][0]['author'], $characters) && !$ide->isAdmin())$ide->goPrevious();
		$this->videos_model->delete($id);
		$ide->goPrevious();
	}
}
?>