<?php
class Character_model extends Model {
	
	function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	public function getAccountID() {
		$this->db->select('id');
		$sql = $this->db->get_where('accounts', array('name' => $_SESSION['name']))->row_array();
		return (int)$sql['id'];
	}
	
	public function getPlayersOnline() {
		@$world = (int)$_REQUEST['world'];
		@$order = $_REQUEST['sort'];
		
		$where = array('online' => 1);
		if(!empty($world))
			$where['world_id'] = $world;
		
		$o = "level DESC";
		
		$allowed = array('level', 'vocation', 'name');
		if(!empty($order))
			if(in_array($order, $allowed))
				$o = "$order DESC";

		$this->db->select('name, level, world_id, vocation, promotion');
		$this->db->order_by($o);
		return $this->db->get_where('players', $where)->result_array();
	}
	
	public function getCount() {
		$this->db->where(array('account_id' => $_SESSION['account_id'], 'deleted' => 0));
		$this->db->from('players');
		return $this->db->count_all_results();
	}
	
	public function characterExists($name) {
		$this->db->select('id');
		return ($this->db->get_where('players', array('name' => $name))->num_rows ? true : false);
	}
	
}

?>