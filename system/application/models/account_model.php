<?php
class Account_model extends Model {

	function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function check_login() {
		require("config.php");
		$this->db->select('id, page_access, nickname');
		$sql = $this->db->get_where('accounts', array('name' => $_POST['name'], 'password' => sha1($_POST['pass'])));
		$row = $sql->row_array();
			if(!empty($row)) {
			$_SESSION['account_id'] = $row['id'];
			$_SESSION['access'] = $row['page_access'];
			$_SESSION['nickname'] = $row['nickname'];
				if($row['page_access'] >= $config['adminAccess'])
					$_SESSION['admin'] = 1;
				else
					$_SESSION['admin'] = 0;
			}
		return $sql->num_rows ? true : false;
	}
	
	function getRecoveryKey($name) {
		$this->db->select('key');
		$sql = $this->db->get_where('accounts', array('name' => $name))->row_array();
		return $sql['key'];
	}
	
	function generateKey($name) {
		$key = rand(1000,9999).'-'.rand(1000,9999).'-'.rand(1000,9999).'-'.rand(1000,9999);
		$save = sha1(str_replace("-", "", $key));
		$this->db->update('accounts', array('key' => $save), array('name' => $name));
		
		return $key;
	}
	
	public function getAccountID() {
		$this->db->select('id');
		$sql = $this->db->get_where('accounts', array('name' => $_SESSION['name']))->row_array();
		return (int)$sql['id'];
	}
	
	public function getCharacters() {
		$this->db->select('id, name, level');
		return $this->db->get_where('players', array('account_id' => $_SESSION['account_id']), array('deleted' => 0))->result();
	}
	
	public function checkPassword($pass) {
		$this->db->select('id');
		return ($this->db->get_where('accounts', array('name' => $_SESSION['name'], 'password' => sha1($pass)))->num_rows) ? true : false; 
	}
	
	public function changePassword($pass, $name) {
		$this->db->update('accounts', array('password' => sha1($pass)), array('name' => $name));
	}
	
	public function isUserPlayer($id) {
		$this->db->select('id');
		return ($this->db->get_where('players', array('account_id' => $_SESSION['account_id'], 'id' => $id))->num_rows) ? true : false;
	}
	
	public function getPlayerComment($id) {
		$this->db->select('comment, hide_char');
		return $this->db->get_where('players', array('id' => $id))->result_array();
	}
	
	public function changeComment($id, $comment, $hide = false) {
		$hide = $hide ? 1 : 0;
		$this->db->update('players', array('comment' => $comment, 'hide_char' => $hide), array('id' => $id));
	}
	
	public function deletePlayer($id) {
		$this->db->update('players', array('deleted' => 1), array('id' => $id));
	}
	
	public function nicknameExists($name) {
		$this->db->select('id');
		return ($this->db->get_where('accounts', array('nickname' => $name))->num_rows) ? true : false;
	}
	public function emailExists($email) {
		$this->db->select('id');
		return ($this->db->get_where('accounts', array('email' => $email))->num_rows) ? true : false;
	}	
	public function setNickname($id, $nick) {
		$this->db->update('accounts', array('nickname' => $nick), array('id' => $id));
	}
	
	public function checkKey($key, $email) {
		return ($this->db->get_where('accounts', array('key' => sha1($key), 'email' => $email))->num_rows) ? true : false;
	}
	
	public function recoveryAccount($key, $email, $password) {
		$this->db->update('accounts', array('password' => sha1($password)), array('key' => sha1($key), 'email' => $email));
	}
	
	public function load($id) {
		$this->db->select('id, rlname, location, about_me, nickname');
		return $this->db->get_where('accounts', array('id' => $id))->result_array();
	}
	
	public function checkMessages() {
		$this->db->select('id');
		return $this->db->get_where('messages', array('to' => $_SESSION['account_id'], 'unread' => 1, 'delete_to' => 0))->num_rows;
	}
}

?>
