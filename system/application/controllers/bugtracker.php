<?php 
class Bugtracker extends Controller {
	public function index() {
		$ide = new IDE;
		$ide->redirect(WEBSITE."/index.php/bugtracker/main");
	}
	
	public function main() {
		require("config.php");
		$ide = new IDE;
		$data = array();
		$this->load->model("bugtracker_model");
		$data['bugs'] = $this->bugtracker_model->getBugs();
		$this->load->library('pagination');
		$config['base_url'] = WEBSITE.'/index.php/bugtracker/main/';
		$config['total_rows'] = $this->bugtracker_model->getBugsAmount();
		$config['per_page'] = $config['bugtrackerPageLimit'];
		$this->pagination->initialize($config); 
		$data['pages'] = $this->pagination->create_links();
		$this->load->view("bugtracker_main.php", $data);
	}
	
	public function view($id) {
		$ide = new IDE;
		if(empty($id)) $ide->redirect(WEBSITE."/index.php/bugtracker/main");
		$data = array();
		$this->load->model("bugtracker_model");
		$data['bug'] = $this->bugtracker_model->getBug($id);
		if(count($data['bug']) == 0) $ide->redirect(WEBSITE."/index.php/bugtracker/main");
		$this->load->view("bugtracker_view", $data);
	}
}
?>