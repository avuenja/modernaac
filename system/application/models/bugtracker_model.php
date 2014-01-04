<?php 

class bugtracker_model extends Model {
	
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	public function getBugs() {
		global $config;
		$this->load->helper("url");
		$page = $this->uri->segment(3);
		$page = (empty($page)) ? 0 : $page;
		return $this->db->query("SELECT bugtracker.id, bugtracker.category, bugtracker.time, bugtracker.title, bugtracker.done, bugtracker.priority, bugtracker.closed, players.name FROM bugtracker LEFT JOIN players ON players.id = bugtracker.author ORDER BY priority DESC LIMIT ".$page.", ".$config['bugtrackerPageLimit']." ")->result_array();
	}
	
	public function getBugsAmount() {
		return $this->db->query("SELECT `id` FROM `bugtracker`")->num_rows;
	}
	
	public function getBug($id) {
		return $this->db->query("SELECT bugtracker.id, bugtracker.text, bugtracker.category, bugtracker.time, bugtracker.title, bugtracker.done, bugtracker.priority, bugtracker.closed, players.name FROM bugtracker LEFT JOIN players ON players.id = bugtracker.author WHERE bugtracker.id = ".$this->db->escape($id)."")->result_array();
	}
}

?>