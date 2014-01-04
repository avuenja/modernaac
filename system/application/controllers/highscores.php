<?php
/*This controller will not use model for basic DB access for gathering information about stastitics, the database access will be made within thin controller!
There is no point of using model for such simple DB access.*/

class Highscores extends Controller {
	
	function __construct() {
    	parent::Controller();
    	$this->load->helper('url');
  	}
	
	public function index($world = 0, $skills = 'level', $page = 0) {
		global $config;
		$this->load->library('pagination');
		$this->load->helper("form");
		$this->load->model('highscore_model');
		
		$skills = isset($_POST['skill']) ? $_POST['skill'] : $skills;
		$world = isset($_POST['world']) ? $_POST['world'] : $world;

		$configs['base_url'] = WEBSITE.'/index.php/highscores/index/'.$world.'/'.$skills.'/';
		$configs['per_page'] = $config['highscore']['per_page'];
		$configs['full_tag_open'] = '<p>';
		$configs['full_tag_close'] = '</p>';
		$configs['uri_segment'] = 5;
		
		$skill = $this->highscore_model->getSkill($skills, $world, $page, $configs['per_page']);
		
		$data['type'] = $skill['skill'];
		$data['world'] = $world;
		$data['skills'] = array('None', 'Fist Fighting', 'Club Fighting', 'Sword Fighting', 'Axe Fighting', 'Distance Fighting', 'Shielding', 'Fishing');
		
		$data['players'] = $skill['data'];
		
		$configs['total_rows'] = $skill['total'] > $config['highscore']['total'] ? $config['highscore']['total'] : $skill['total'];
		
		$this->pagination->initialize($configs); 
		$this->load->view("highscores", $data);
	}
}
