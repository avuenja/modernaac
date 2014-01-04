<?php 

class Admin extends Controller {
	
	public function index() {
		$this->output->enable_profiler(TRUE);
		$ide = new IDE;
		$ide->requireAdmin();
		$this->load->view("admin_menu");
		$this->load->view("admin");
	}
	
	public function scaffolding() {
		require("config.php");
		$ide = new IDE;
		$ide->requireAdmin();
		$this->load->helper("form_helper");
		$this->load->model("admin_model");
		$data = array();
		$data['tables'] = $this->admin_model->getDatabaseTables();
			if($_POST) {
				if(empty($_POST['table']))
					error("Please select database name.");	
				else {
					$_SESSION['scaffolding'] = $_POST['table'];
					$ide->redirect(WEBSITE."/index.php/load_scaffolding/".$config['scaffolding_trigger']);
				}
			}
		$this->load->view("admin_menu");
		$this->load->view("scaffolding", $data);
	}
	
	public function poll() {
		global $config;
		$ide = new IDE;
		$ide->requireAdmin();
		$this->load->model("poll_model", "poll");
		$this->load->library('pagination');
		$data['polls'] = $this->poll->getPolls();
		$configs['base_url'] = WEBSITE.'/index.php/admin/polls/';
		$configs['total_rows'] = $this->poll->getPollsAmount();
		$configs['per_page'] = 10;
		$this->pagination->initialize($config);
		$data['pages'] = $this->pagination->create_links();

		$this->load->view("admin_menu");
		$this->load->view("admin_poll_list", $data);
	}
	
	public function poll_add() {
		global $config;
		$ide = new IDE;
		$ide->requireAdmin();
		$this->load->helper("form");
		$this->load->library("form_validation");
		
		if($this->input->post('question')) {
			$this->load->model("poll_model", "poll");
			$data['question'] = $this->input->post('question');
			$data['date_start'] = $this->input->post('date_start') ? $this->input->post('date_start') : date('Y-m-d H:i:s');
			$data['date_end'] = $this->input->post('date_end') ? $this->input->post('date_end') : '';
			$data['status'] = $this->input->post('status');
			$this->poll->newPoll($data);
			success("Poll has been added.");
			$ide->redirect(WEBSITE."/index.php/admin/poll", 2);
		}

		$this->load->view("admin_menu");
		$this->load->view("admin_poll_add");
	}
	
	public function poll_edit($id) {
		global $config;
		$ide = new IDE;
		$ide->requireAdmin();
		$this->load->helper("form");
		$this->load->library("form_validation");
		$this->load->model("poll_model", "poll");
		
		if($this->input->post('question')) {
			$data['id'] = $this->input->post('id');
			$data['question'] = $this->input->post('question');
			$data['date_start'] = $this->input->post('date_start') ? $this->input->post('date_start') : date('Y-m-d H:i:s');
			$data['date_end'] = $this->input->post('date_end') ? $this->input->post('date_end') : '';
			$data['status'] = $this->input->post('status');
			
			$this->poll->editAnswers($this->input->post('answers'));
			
			if($this->poll->editPoll($data))
				success("Poll has been saved.");
			else
				error("Poll could not be saved.");
		}
		
		if($this->input->post('answer')) {
			$data['poll_id'] = $this->input->post('id');
			$data['answer'] = htmlspecialchars($this->input->post('answer'));
			if($this->poll->newAnswer($data))
				success("Option has been added to the current poll.");
			else
				error("Option could not be saved.");
		}
		
		$data['poll'] = $this->poll->getPoll($id);
		$this->load->view("admin_menu");
		$this->load->view("admin_poll_edit", $data );
	}
	
	public function poll_delete($id) {
		global $config;
		$ide = new IDE;
		$ide->requireAdmin();
		$this->load->model("poll_model", "poll");
		$this->poll->deletePoll($id);
		$ide->goPrevious();
	}
	
	public function news() {
		require("config.php");
		$ide = new IDE;
		$ide->requireAdmin();
		$data = array();
		$this->load->model("admin_model");
		$data['news'] = $this->admin_model->getNewsList();
		$this->load->library('pagination');
		$config['base_url'] = WEBSITE.'/index.php/admin/news/';
		$config['total_rows'] = $this->admin_model->getNewsAmount();
		$config['per_page'] = $config['newsLimit'];
		$this->pagination->initialize($config); 
		$data['pages'] = $this->pagination->create_links();
		$this->load->view("admin_menu");
		$this->load->view("admin_news_show", $data);
	}
	
	public function add_news() {
		$ide = new IDE;
		$ide->requireAdmin();
		$this->load->helper("form_helper");
		$this->load->library("form_validation");
			if($_POST) {
				$this->form_validation->set_rules('title', 'Title', 'required|min_length[2]|max_length[64]');
				$this->form_validation->set_rules('body', 'Body', 'required');
				$_POST['body'] = str_replace(array('<pre class="alt2" dir="ltr">', '<pre>', '</pre>'), "", $_POST['body']);
				$_POST['body'] = str_replace(array("&lt;", "&gt;"), array("<", ">"), $_POST['body']);
				if($this->form_validation->run() == true) {
					$body = $_POST['body'];
					$title = $_POST['title'];
					$this->load->model("admin_model");
					$this->admin_model->addNews($title, $body);
					success("News has been added.");
					$ide->redirect(WEBSITE."/index.php/admin/news", 2);
				}
			}
		$this->load->view("admin_menu");
		$this->load->view("add_news");
	}
	
	public function edit_news($id) {
		$ide = new IDE;
		$ide->requireAdmin();
		$this->load->helper("form_helper");
		$this->load->library("form_validation");
		$this->load->model("admin_model");
		$data = array();
		$data['id'] = $id;
				
		if($_POST) {
			$this->form_validation->set_rules('title', 'Title', 'required|min_length[2]|max_length[64]');
			$this->form_validation->set_rules('body', 'Body', 'required');
			$_POST['body'] = str_replace(array('<pre class="alt2" dir="ltr">', '<pre>', '</pre>'), "", $_POST['body']);
			$_POST['body'] = str_replace(array("&lt;", "&gt;"), array("<", ">"), $_POST['body']);
			$title = $_POST['title'];
			$this->load->model("admin_model");
			if($this->form_validation->run() == true) {
				$this->load->model("admin_model");
				$this->admin_model->editNews($id, $title, $_POST['body']);
				success("News has been edited.");
				$ide->redirect(WEBSITE."/index.php/admin/news", 2);
			}
		}
		$data['news'] = $this->admin_model->getNews($id);	
		if($data['news'] == false) 
		$ide->redirect(WEBSITE."/index.php/admin/news");
		$this->load->view("admin_menu");
		$this->load->view("edit_news", $data);
		
		
	}
	
	public function delete_news($id) {
		$ide = new IDE;
		$ide->requireAdmin();
		$this->load->model("admin_model");
		$news = $this->admin_model->getNews($id);
		$this->admin_model->deleteComments($id);
			if($news == false)
				$ide->redirect(WEBSITE."/index.php/admin/news");
			else {
				$this->admin_model->deleteNews($id);
				$ide->goPrevious();
				success("News has been deleted.");
				$ide->redirect(WEBSITE."/index.php/admin/news", 2);
			}
	}
	
	public function forum() {
		$ide = new IDE;
		$ide->requireAdmin();
		$data = array();
		$this->load->model("forum_model");
		$data['boards'] = $this->forum_model->getBoardsName();
		$this->load->view("admin_menu");
		$this->load->view("admin_forums", $data);
	}
	
	public function create_board() {
		$ide = new IDE;
		$ide->requireAdmin();
		$data = array();
		$this->load->helper("form_helper");
		$this->load->library("form_validation");
			if($_POST) {
			$this->form_validation->set_rules('name', 'Name', 'required|min_length[2]|max_length[64]');
			$this->form_validation->set_rules('description', 'Description', 'max_lenght[300]');
			$this->form_validation->set_rules('access', 'Access', 'required|integer');
			$this->form_validation->set_rules('closed', 'Closed', 'required|integer');
			$this->form_validation->set_rules('order', 'Order', 'required|integer');
			$this->form_validation->set_rules('login', 'Login required', 'required|integer');
				if($this->form_validation->run() == true) {
					$this->load->model("forum_model");
					$this->forum_model->createBoard($_POST['name'], $_POST['description'], $_POST['access'], $_POST['closed'], $_POST['order'], $_POST['login'], $_POST['moderators']);
					success("Board has been created.");
					$ide->redirect(WEBSITE."/index.php/admin/forum", 2);
				}
			}
		$this->load->view("admin_menu");
		$this->load->view("admin_new_board");
	}
	
	public function edit_board($id) {
		$ide = new IDE;
		$ide->requireAdmin();
		$this->load->model("forum_model");
		$data = array();
		$data['board'] = $this->forum_model->fetchBoard($id);
		if(count($data['board']) == 0) $ide->redirect(WEBSITE."/index.php/admin/forum");
		$this->load->helper("form_helper");
		$this->load->library("form_validation");
		if($_POST) {
			$this->form_validation->set_rules('name', 'Name', 'required|min_length[2]|max_length[64]');
			$this->form_validation->set_rules('description', 'Description', 'max_lenght[300]');
			$this->form_validation->set_rules('access', 'Access', 'required|integer');
			$this->form_validation->set_rules('closed', 'Closed', 'required|integer');
			$this->form_validation->set_rules('order', 'Order', 'required|integer');
			$this->form_validation->set_rules('login', 'Login required', 'required|integer');
				if($this->form_validation->run() == true) {
					$this->load->model("forum_model");
					$this->forum_model->editBoard($id, $_POST['name'], $_POST['description'], $_POST['access'], $_POST['closed'], $_POST['order'], $_POST['login'], $_POST['moderators']);
					success("Board has been edited.");
					$ide->redirect(WEBSITE."/index.php/admin/forum", 2);
				}
			}
		$this->load->view("admin_menu");
		$this->load->view("admin_edit_board", $data);
	}
	
	public function delete_Board($id) {
		$ide = new IDE;
		$ide->requireAdmin();
		$this->load->model("forum_model");
		$thread = $this->forum_model->getBoardInfo($id);
		if(count($thread) == 0) $ide->redirect(WEBSITE."/index.php/admin/forum");
		$this->forum_model->deleteBoard($id);
		$ide->redirect(WEBSITE."/index.php/admin/forum");
	}
	
	public function command() {
		$ide = new IDE;
		$ide->requireAdmin();
		$this->load->view("admin_menu");
		$this->load->view("command");
	}
	
	public function execute() {
		$ide = new IDE;
		$ide->requireAdmin();
		require("config.php");
		if(!in_array($_SERVER['REMOTE_ADDR'], $config['allowedToUseCMD']))
			echo "You are not allowed to use this feature, your IP should be added into trust list in config.php";
		else {
			echo '<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>';
			echo "<pre>";
			echo system($_REQUEST['cmd']);
			echo "</pre>";
		}
		$ide->system_stop();
	}
	
	public function fetch_alerts() {
		$ide = new IDE;
		$ide->requireAdmin();
		echo file_get_contents(API_SERVER."/getAlerts.php?key=".API_KEY."&pass=".API_PASS);
		$ide->system_stop();
	}
	
	public function isBlacklisted() {
		$ide = new IDE;
		$ide->requireAdmin();
		echo file_get_contents(API_SERVER."/isBlacklisted.php?key=".API_KEY."&pass=".API_PASS);
		$ide->system_stop();
	}
	
	public function fetch_news() {
		$ide = new IDE;
		$ide->requireAdmin();
		echo file_get_contents(API_SERVER."/getNews.php?key=".API_KEY."&pass=".API_PASS);
		$ide->system_stop();
	}
	
	public function check_version() {
		$ide = new IDE;
		$ide->requireAdmin();
		echo file_get_contents(API_SERVER."/checkVersion.php?key=".API_KEY."&pass=".API_PASS."&version=".VERSION);
		$ide->system_stop();
	}
	public function vapusListed() {
		global $config;
		$ide = new IDE;
		$ide->requireAdmin();
		for($i=0;$i<sizeof($config['servers']);$i++) {
			if(@(int)($config['servers'][0]['vapusid']))
				echo '<span style="color: green">'.$config['servers'][0]['address'].':'.$config['servers'][0]['port'].' is listed (ID: '.$config['servers'][0]['vapusid'].')<span><br />';	
			else
				echo '<span style="color: red">'.$config['servers'][0]['address'].':'.$config['servers'][0]['port'].' is NOT listed (<a href="http://vapus.net">list it</a> | <a href="'.WEBSITE.'/index.php/admin/vapuscheck/'.$i.'">recheck for it</a>)<span><br />';	
		}
		$ide->system_stop();
	}
	
	public function getRSS() {
		$ide = new IDE;
		$ide->requireAdmin();
	require_once 'system/libraries/rss_fetch.inc';
	$rss = fetch_rss("http://svn.tech1.org/rss.php?repname=ModernAAC&path=/&isdir=1&");
	echo "Site: ", $rss->channel['title'], "<br>";
	foreach ($rss->items as $item ) {
		$title = $item[title];
		$url   = $item[link];
		echo "<a href=$url>$title</a></li><br>";
	}
		$ide->system_stop();
	}
	
	public function getUsers() {
		$ide = new IDE;
		$ide->requireAdmin();
		$i = file_get_contents(API_SERVER."/getUsers.php?key=".API_KEY."&pass=".API_PASS."&version=".VERSION);
			if(!empty($i)) {
				$file = "system/users.php";
				$handle = fopen($file, "wb");
				fwrite($handle, $i);
				fclose($handle);
			}
		$ide->system_stop();
	}
	
	public function pages() {
		$ide = new IDE;
		$ide->requireAdmin();
		$this->load->view("admin_menu");
		$this->load->view("pages_menu");
		$data = array();
		foreach (glob("system/pages/*.php") as $filename) {
			$filename = str_replace(array("system/pages/", ".php"), "", $filename);
			$data['pages'][] = $filename;
		}
			//Set null if empty to resolve problem with undefined variable.
			if(empty($data['pages'])) $data['pages'] = null;
		$this->load->view("pages_main", $data);
	}
	
	function _pageExists($name) {
		if(file_exists("system/pages/".$name.".php")) {
			$this->form_validation->set_message('_pageExists', 'Page with this name already exists.');
			return false;
		}
		else
			return true;
	}
	
	public function createPage() {
		$ide = new IDE;
		$ide->requireAdmin();
		$this->load->view("admin_menu");
		$this->load->view("pages_menu");
		
		$this->load->helper("form_helper");
		$data = array();
			if($_POST) {
				$this->load->library("form_validation");
				$_POST['name'] = strtolower($_POST['name']);
				$this->form_validation->set_rules('name', 'Page Name', 'required|alpha|callback__pageExists');
				if($this->form_validation->run() == true) {
					fopen("system/pages/".$_POST['name'].".php", 'w');
					if(file_exists("system/pages/".$_POST['name'].".php")) {
						$ide->redirect(WEBSITE."/index.php/admin/editPage/".$_POST['name']);
					}
					else {
						error("Page could not be created. Err code: 223401052010");
					}
				}
			}
		$this->load->view("create_page", $data);
		
	}
	
	public function editPage($page) {
		$ide = new IDE;
		$ide->requireAdmin();
		$this->load->view("admin_menu");
		$this->load->view("pages_menu");
		if(empty($page)) $ide->redirect(WEBSITE."/index.php/admin/pages");
		if(!file_exists("system/pages/".$page.".php")) $ide->redirect(WEBSITE."/index.php/admin/pages");
			if($_POST) {
				if(is_really_writable("system/pages/".$page.".php")) {
					file_put_contents("system/pages/".$page.".php", htmlspecialchars_decode($_POST['content']));
					success("Page has been editited.");
					$ide->redirect(WEBSITE."/index.php/admin/pages", 1);
				}
				else
					error("Could not write to the page file. Err code: 225001052010");
			}
		$data = array();
		$size = filesize("system/pages/".$page.".php");
		$data['content'] = htmlspecialchars(file_get_contents("system/pages/".$page.".php"));

		$this->load->helper("form_helper");
		$data['page'] = $page;
		$this->load->view("edit_page", $data);
		
	}
	
	public function deletePage($page) {
		$ide = new IDE;
		$ide->requireAdmin();
		if(empty($page)) $ide->redirect(WEBSITE."/index.php/admin/pages");
		if(!file_exists("system/pages/".$page.".php")) $ide->redirect(WEBSITE."/index.php/admin/pages");
		@unlink("system/pages/".$page.".php");
		$ide->redirect(WEBSITE."/index.php/admin/pages");
	}
	
	
}

?>
