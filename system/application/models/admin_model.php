<?php 
class Admin_model extends Model {

	function Admin_model () {
		parent::Model();
		$this->load->database();
	}

	public function getDatabaseTables() {
		return $sql = $this->db->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".DATABASE."'")->result_array();
	}

	public function getNewsList() {
		$this->load->helper("url");
		$page = $this->uri->segment(3);
			if(empty($page))
				$page = "0";
		
		return $this->db->get("news", 10, $page)->result_array();
	}

	public function getNewsAmount() {
		return $this->db->count_all('news');
	}

	public function addNews($title, $body) {
		$data = array('title' => htmlspecialchars($title),
			'body' => $body,
			'time' => $_SERVER['REQUEST_TIME']);
			
		$this->db->insert('news', $data);
	}

	public function getNews($id) {
		$sql = $this->db->getwhere("news", array('id' => $id));
		if($sql->num_rows == 0)
			return false;
		else
			return $sql->result_array();
	}

	public function editNews($id, $title, $body) {
		$data = array('title' => htmlspecialchars($title),
				'body' => $body);
		$this->db->update('news', $data, array('id' => $id));
	}

	public function deleteNews($id) {
		$this->db->delete('news', array('id' => $id));
	}

	public function deleteComments($id) {
		$this->db->delete('comments', array('news_id' => $id));
	}
}
?>