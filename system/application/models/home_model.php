<?php 
class home_model extends Model {
	
	public function __construct() {
		parent::Model();
		$this->load->database();
	}
	
	public function getAllNews() {
		require("config.php");
		$this->db->order_by('id desc');
		$sql = $this->db->get('news', $config['newsLimit']);
		$ret['amount'] = $sql->num_rows;
		$ret['news'] = $sql->result_array();
		return $ret;
	}
	
	public function getArchiveNews() {
		require("config.php");
		$this->load->helper("url");
		$page = $this->uri->segment(3);
			$page = (empty($page)) ? 0 : $page;
		
		$this->db->order_by('id desc');
		$sql = $this->db->get('news', $config['newsLimit'], $page);
		$ret['amount'] = $sql->num_rows;
		$ret['news'] = $sql->result_array();
		return $ret;
	}
	
	public function getNewsAmount() {
		return $this->db->get('news')->num_rows;
	}
	
	public function loadNews($id) {
		$sql = $this->db->get_where('news', array('id' => $id));
		return $sql->num_rows ? $sql->result_array() : false;
	}
	
	public function getComments($id) {
		require("config.php");
		
		$this->load->helper("url");
		$page = $this->uri->segment(4);
		$page = (empty($page)) ? 0 : $page;
		
		return $this->db->get_where('comments', array('news_id' => $id), $config['commentLimit'], $page)->result_array();
	}
	
	public function getCommentsAmount($id) {
		return $this->db->get_where('comments', array('news_id' => $id))->num_rows;
	}
	
	public function getCharacters() {
		$this->db->select('id, name');
		return $this->db->get_where('players', array('account_id' => $_SESSION['account_id']))->result_array();
	}
	
	public function playerExistsOnAccount($id) {
		return $this->db->get_where('players', array('id' => $id, 'account_id' => $_SESSION['account_id']))->num_rows ? true : false;
	}
	
	public function addComment($id, $character, $body) {
		$data = array('body' => $body,
					  'news_id' => $id,
					  'time' => $_SERVER['REQUEST_TIME'],
					  'author' => $character);
					  
		$this->db->insert('comments', $data);
	}
	
	public function getComment($id) {
		return $this->db->get_where('comments', array('id' => $id))->result_array();
	}
	
	public function deleteComment($id) {
		$this->db->delete('comments', array('id' => $id));
	}
}
?>