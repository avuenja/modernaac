<?php 
	class Msg_m extends Model {
		
		public function __construct() {
			parent::__construct();
			$this->load->database();
		}
	
		public function getInbox() {
			global $config;
			$this->load->helper("url");
			$page = $this->uri->segment(3);
			$page = (empty($page)) ? 0 : $page;
			return $this->db->query("SELECT messages.id, messages.from as author_id, messages.title, messages.time, messages.unread, accounts.nickname as author FROM messages LEFT JOIN accounts ON accounts.id = messages.from WHERE messages.delete_to = 0 AND messages.to = '".$_SESSION['account_id']."' ORDER BY messages.id DESC  LIMIT ".$page.", ".$config['messagesLimit'].";")->result_array();
		}
		
		public function getInboxAmount() {
			return $this->db->query("SELECT id FROM messages WHERE `to` = '".$_SESSION['account_id']."' AND delete_to = 0")->num_rows;
		}
		
		public function getOutbox() {
			global $config;
			$this->load->helper("url");
			$page = $this->uri->segment(3);
			$page = (empty($page)) ? 0 : $page;
			return $this->db->query("SELECT messages.id, messages.from as author_id, messages.title, messages.time, messages.unread, accounts.nickname as author FROM messages LEFT JOIN accounts ON accounts.id = messages.to WHERE messages.delete_from = 0 AND messages.from = '".$_SESSION['account_id']."' ORDER BY messages.id DESC  LIMIT ".$page.", ".$config['messagesLimit'].";")->result_array();
		}
		
		public function getOutboxAmount() {
			return $this->db->query("SELECT id FROM messages WHERE `from` = '".$_SESSION['account_id']."' AND delete_from = 0")->num_rows();
		}
		
		public function load($id) {
			return $this->db->query("SELECT m.*, a.nickname as from_nick, b.nickname AS to_nick FROM messages AS m, accounts AS a, accounts AS b WHERE a.id = m.from AND b.id = m.to AND (m.to = '".$_SESSION['account_id']."' OR m.from = '".$_SESSION['account_id']."') AND m.id = ".$this->db->escape($id).";")->result_array();
		}
		
		public function delete($id, $who = true) {
			if($who)
				$this->db->query("UPDATE messages SET delete_to = 1 WHERE id = ".$this->db->escape($id)."");
			else
				$this->db->query("UPDATE messages SET delete_from = 1 WHERE id = ".$this->db->escape($id)."");
		}
		
		public function nickExists($name) {
			return $this->db->get_where('accounts', array('nickname' => $name))->num_rows() ? true : false;
		}
		
		public function getIdByNick($name) {
			$this->db->select('id');
			return $this->db->get_where('accounts', array('nickname' => $name))->result_array();
		}
		
		public function read($id) {
			$this->db->update('messages', array('unread' => 0), array('id' => $id));
		}
		
		public function write($from, $to, $title, $text) {
			$data['from'] = $from;
			$data['to'] = $to;
			$data['title'] = $title;
			$data['text'] = $text;
			$data['time'] = $_SERVER['REQUEST_TIME'];
			$data['delete_from'] = 0;
			$data['delete_to'] = 0;
			$data['unread'] = 1;
			$this->db->insert("messages", $data);
		}
	}
?>