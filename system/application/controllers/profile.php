<?php 
	class Profile extends Controller {
		
		public function index() {
			global $ide;
			$ide->goPrevious();
		}
		
		public function view($name) {
			global $ide;
			$this->load->model("profile_model", "profile");
			$data['profile'] = $this->profile->load($name);
			$data['name'] = $name;
			if(empty($data['profile'])) $ide->goPrevious();
			if(empty($data['profile'][0]['nickname'])) $ide->goPrevious();
			$data['friends'] = $this->profile->getFriends($data['profile'][0]['id']);
			$data['isFriend'] = ($ide->isLogged()) ?$this->profile->isFriend($data['profile'][0]['id'], $_SESSION['account_id']) : false;
			$data['videos'] = $this->profile->getVideos($data['profile'][0]['id']);
			$data['id'] = $data['profile'][0]['id'];
			$this->load->view("profile_view", $data);
		}
		
		
		public function update() {
			global $ide;
			$ide->requireLogin();
			$this->load->model("profile_model", "profile");
			$data['profile'] = $this->profile->load($_SESSION['account_id'], true);
			$this->load->helper("form_helper");
			if($_POST) {
				$this->load->library("form_validation");
				$this->form_validation->set_rules('rlname', 'Real Name', 'required|alpha_space');
				$this->form_validation->set_rules('location', 'Location', 'required|alpha_space');
				$this->form_validation->set_rules('about_me', 'About Me', 'required|max_length[350]');
				if($this->form_validation->run()) {
					$this->profile->update($_SESSION['account_id'], $_POST['rlname'], $_POST['location'], $_POST['about_me']);
					$ide->redirect(WEBSITE."/index.php/account/index/4");
				}
			}
			$this->load->view("profile_update", $data);
		}
		
		public function avatar() {
			global $ide;
			$ide->requireLogin();
			$this->load->model("profile_model", "profile");
			$data['profile'] = $this->profile->load($_SESSION['account_id'], true);
			$this->load->helper("form_helper");
			if($_FILES && empty($_POST['avatar'])) {
					$config['upload_path'] = 'public/uploads/avatars/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['max_size']	= '360';
					$config['file_name'] = time();
					$config['max_width']  = '1424';
					$config['max_height']  = '1268';
					$this->load->library('upload', $config);
					if ( ! $this->upload->do_upload()) {
						$data['error'] = $this->upload->display_errors();
					}
					else {
						$info = $this->upload->data();
						$image = $info['file_name'];
						$config['image_library'] = 'gd2';
						$config['source_image']	= 'public/uploads/avatars/'.$image;
						$config['create_thumb'] = TRUE;
						$config['maintain_ratio'] = FALSE;
						$config['width']	 = 90;
						$config['height']	= 90;
						$this->load->library('image_lib', $config); 
						$this->image_lib->resize();
						unlink('public/uploads/avatars/'.$image);
						$name = (int)$image;
						$format = substr($image, -3);
						$image = $name."_thumb.".$format;
						$this->profile->setAvatar($_SESSION['account_id'], $image);
							if(!empty($data['profile'][0]['avatar']))
								unlink("public/uploads/avatars/".$data['profile'][0]['avatar']);
						$ide->redirect(WEBSITE."/index.php/account/index/5");
					}
				}
			else if(!empty($_POST['avatar'])) {
				if(!empty($data['profile'][0]['avatar']))
							unlink("public/uploads/avatars/".$data['profile'][0]['avatar']);
				$this->profile->setAvatar($_SESSION['account_id'], "");
				$ide->redirect(WEBSITE."/index.php/account/index/5");
			}
			$this->load->view("profile_avatar", $data);
		}
		
		public function addFriend($id) {
			global $ide;
			$ide->requireLogin();
			$this->load->model("profile_model", "profile"); 
			if($id == $ide->loggedAccountId()) $ide->goPrevious();
			if(!$this->profile->exists($id)) $ide->goPrevious();
			if($this->profile->isFriend($id, $ide->loggedAccountId())) $ide->goPrevious();
			$this->profile->addFriend($id, $ide->loggedAccountId());
			success("Invitation has been sent, please wait till the user will accept.");
		}
		
		public function accept($id) {
			global $ide;
			$ide->requireLogin();
			$id = (int)$id;
			if(empty($id)) $ide->goPrevious();
			$this->load->model("profile_model", "profile");
			if(!$this->profile->pendingInvitationExists($id, $ide->loggedAccountId())) $ide->goPrevious();
			$this->profile->AcceptFriendship($id, $ide->loggedAccountId());
			success("Your friendship has been accepted.");
		}
		
		public function removeFriend($id) {
			global $ide;
			$ide->requireLogin();
			$this->load->model("profile_model", "profile");
			$this->profile->removeFriend($id, $ide->loggedAccountId());
			$ide->goPrevious();
		}
		
		public function community() {
			$this->load->model("profile_model", "profile");
			$data['videos'] = $this->profile->getLatestVideos();
			$data['comments'] = $this->profile->getLatestComments();
			$this->load->view("community", $data);
		}
	}
?>