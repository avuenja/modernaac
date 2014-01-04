<?php 
	class Msg extends Controller {
		public function index() {
			global $ide;
			$ide->redirect(WEBSITE."/index.php/msg/inbox");
		}
		
		public function inbox() {
			global $ide;
			global $config;
			$ide->requireLogin();
			$this->load->model("msg_m", "msg");
			$data['messages'] = $this->msg->getInbox();
			$this->load->library('pagination');
			$config['base_url'] = WEBSITE.'/index.php/msg/inbox/';
			$config['total_rows'] = $this->msg->getInboxAmount();
			$config['per_page'] = $config['messagesLimit']; 
			$this->pagination->initialize($config); 
			$data['pages'] = $this->pagination->create_links();
			$this->load->view("msg_menu");
			$this->load->view("inbox", $data);
		}
		
		public function view($id) {
			$id = (int)$id;
			global $ide;
			$ide->requireLogin();
			if(empty($id)) $ide->goPrevious();
			$this->load->model("msg_m", "msg");
			$data['message'] = $this->msg->load($id);
				if($data['message'][0]['unread'] == 1 && $data['message'][0]['to'] == $_SESSION['account_id'])
					$this->msg->read($id);
			if(empty($data['message'])) $ide->goPrevious();
			$this->load->view("msg_menu");
			$this->load->view("view_message", $data);
		}
		
		public function outbox() {
			global $ide;
			global $config;
			$ide->requireLogin();
			$this->load->model("msg_m", "msg");
			$data['messages'] = $this->msg->getOutbox();
			$this->load->library('pagination');
			$config['base_url'] = WEBSITE.'/index.php/msg/outbox/';
			$config['total_rows'] = $this->msg->getOutboxAmount();
			$config['per_page'] = $config['messagesLimit']; 
			$this->pagination->initialize($config); 
			$data['pages'] = $this->pagination->create_links();
			$this->load->view("msg_menu");
			$this->load->view("outbox", $data);
		}
		
		public function delete($id) {
			$id = (int)$id;
			global $ide;
			if(empty($id)) $ide->goPrevious();
			$ide->requireLogin();
			$this->load->model("msg_m", "msg");
			$message = $this->msg->load($id);
			if(empty($message)) $ide->goPrevious();
				if($message[0]['to'] == $_SESSION['account_id'])
					$this->msg->delete($id, true);
				else
					$this->msg->delete($id, false);
			$ide->goPrevious();
		}
		
		function _checkTo($name) {
			$this->load->model("msg_m", "msg");
			if(strtolower($name) == strtolower($_SESSION['nickname'])) {
				$this->form_validation->set_message('_checkTo', 'You cannot send message to yourself.');
				return false;
			}
			else if($this->msg->nickExists($name)) {
				return true;
			}
			else {
				$this->form_validation->set_message('_checkTo', 'Person with such nickname does not exists.');
				return false;
			}
		}
		
		public function write($to = null) {
			global $ide;
			$ide->requireLogin();
			$data['to'] = $to;
			$this->load->helper("form_helper");
			$this->load->model("msg_m", "msg");
				if($_POST){
					$_POST['to'] = ucwords(strtolower($_POST['to']));
					$this->load->library("form_validation");
					$this->form_validation->set_rules('title', 'Title', 'required|min_length[2]|max_length[64]');
					$this->form_validation->set_rules('text', 'Message', 'required|min_length[2]|max_length[1500]');
					$this->form_validation->set_rules('to', 'To', 'required|callback__checkTo');
					if($this->form_validation->run()) {
						$to = $this->msg->getIdByNick($_POST['to']);
						$this->msg->write($_SESSION['account_id'], $to[0]['id'], $_POST['title'], $_POST['text']);
						success("Message has been sent!");
						$ide->redirect(url('msg/inbox'), 2);
						
					}
				}
			$this->load->view("msg_menu");
			$this->load->view("write_message", $data);
			
		}
		
	}
?>