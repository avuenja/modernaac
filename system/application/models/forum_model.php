<?php 

class Forum_model extends Model {
	
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	public function getBoards() {
		$ide = new IDE;
		$access = ($ide->isLogged()) ? $_SESSION['access'] : 0;
		$logged = ($ide->isLogged()) ? "" : "AND `requireLogin` != '1'";
		$guild = ($ide->isLogged()) ? "AND (`b`.`guild` IS NULL OR `b`.`guild` IN (SELECT `guild_id` FROM guild_ranks WHERE `id` IN (SELECT rank_id FROM players WHERE account_id = ".$_SESSION['account_id'].")))" : "AND `b`.`guild` IS NULL";
			return $this->db->query("SELECT `b`.`id`, `b`.`name`, `b`.`description`, `b`.`closed`, `b`.`moderators`, `u`.`name` AS `author`, `p`.`thread_id`, `t`.`name` AS `thread_title`, `p`.`time` FROM `forums` AS `b` LEFT JOIN (SELECT `time`, `thread_id`, `board_id`, `author` FROM `posts` ORDER BY `time` DESC) AS `p` ON `p`.`board_id` = `b`.`id` LEFT JOIN `players` AS `u` ON `u`.`id` = `p`.`author` LEFT JOIN `threads` AS `t` ON `t`.`id` = `p`.`thread_id` WHERE `b`.`access` <= '".$access."' ".$logged." ".$guild." GROUP BY `b`.`id` ORDER BY `b`.`order` ASC;")->result_array();
	}
	
	public function getBoardInfo($id) {
		$ide = new IDE;
		$guild = ($ide->isLogged()) ? "AND (`guild` IS NULL OR `guild` IN (SELECT `guild_id` FROM guild_ranks WHERE `id` IN (SELECT rank_id FROM players WHERE account_id = '".$_SESSION['account_id']."')))" : "AND `guild` IS NULL";
		return @$this->db->query("SELECT `name`, `description`, `id`, `closed`, `moderators`, `requireLogin`, `guild`, `access` FROM `forums` WHERE `id` = ".$this->db->escape($id)." {$guild}")->result_array();
	}
	
	public function getThreads($id) {
		require("config.php");
		$this->load->helper("url");
		$page = $this->uri->segment(4);
			$page = (empty($page)) ? 0 : $page;
		return @$this->db->query("SELECT `t`.`id`, `t`.`name`, `t`.`sticked`, `t`.`closed`, `u`.`name` AS `author`, `p`.`time` AS `post_time`, `p`.`author` AS `post_author`
FROM `threads` AS `t`
LEFT JOIN (SELECT `time`, `thread_id`, (SELECT `name` FROM `players` WHERE `id` = `author`) AS `author` FROM `posts` ORDER BY `time` DESC) AS `p` ON `p`.`thread_id` = `t`.`id`
LEFT JOIN `players` AS `u` ON `u`.`id` = `t`.`author`
WHERE `t`.`board_id` = ".$this->db->escape($id)." GROUP BY `t`.`id` ORDER BY `t`.`sticked` DESC, `post_time` DESC LIMIT ".$page.", ".$config['threadsLimit'].";")->result_array();
	}
	
	public function getThreadsAmount($id) {
		return @$this->db->query("SELECT `id` FROM `threads` WHERE `board_id` = ".$this->db->escape($id)."")->num_rows;
	}
	
	public function getPosts($id) {
		require("config.php");
		$this->load->helper("url");
		$page = $this->uri->segment(4);
			$page = (empty($page)) ? 0 : $page;
		return @$this->db->query("SELECT `p`.`title`, `p`.`text`, `p`.`time`, `u`.`name` AS `author`, `p`.`board_id`, `p`.`id`, `a`.`avatar` FROM `posts` AS `p` LEFT JOIN `players` AS `u` ON `u`.`id` = `p`.`author` LEFT JOIN `accounts` AS `a` ON `a`.`id` = `u`.`account_id` WHERE `p`.`thread_id` = ".$this->db->escape($id)." ORDER BY `p`.`id` ASC LIMIT ".$page.", ".$config['postsLimit'])->result_array();
	}
	
	public function getPostsAmount($id) {
		return @$this->db->query("SELECT `id` FROM `posts` WHERE `thread_id` = ".$this->db->escape($id)."")->num_rows;
	}
	
	public function getThreadInfo($id) {
		return $this->db->query("SELECT `id`, `name`, `sticked`, `closed`, `author`, `time`, `board_id` FROM `threads` WHERE `id` = ".$this->db->escape($id))->result_array();
	}
	
	public function getCharacters() {
		return $this->db->query("SELECT `id`, `name` FROM `players` WHERE `account_id` = ".$_SESSION['account_id']."")->result_array();
	}
	
	public function characterExistsOnAccount($id) {
		if($this->db->query("SELECT `id` FROM `players` WHERE `id` = ".$this->db->escape($id)." AND `account_id` = ".$_SESSION['account_id']."")->num_rows == 0) return false; else return true;
	}
	
	public function postThread($board, $character, $title, $body) {
		
		$board = $this->db->escape($board);
		$character = $this->db->escape($character);
		$title = $this->db->escape($title);
		$body = $this->db->escape($body);
		
		$time = $_SERVER['REQUEST_TIME'];
		$sql = $this->db->query("INSERT INTO `threads` (`id`, `name`, `sticked`, `closed`, `author`, `time`, `board_id`) VALUES('', ".$title.", '0', '0', ".$character.", ".$time.", ".$board.")");
		$this->db->query("INSERT INTO `posts` (`id`, `title`, `text`, `time`, `author`, `board_id`, `thread_id`) VALUES('', ".$title.", ".$body.", ".$time.", ".$character.", ".$board.", LAST_INSERT_ID())");
	}
	
	public function postReply($board, $thread, $character, $title, $body) {
		$board = $this->db->escape($board);
		$thread = $this->db->escape($thread);
		$character = $this->db->escape($character);
		$title = $this->db->escape($title);
		$body = $this->db->escape($body);
		$time = $_SERVER['REQUEST_TIME'];
		
		$this->db->query("INSERT INTO `posts` (`id`, `title`, `text`, `time`, `author`, `board_id`, `thread_id`) VALUES('', ".$title.", ".$body.", ".$time.", ".$character.", ".$board.", ".$thread.")");
		$this->db->query("UPDATE `threads` SET `time` = ".$time." WHERE `id` = ".$thread."");	
	}
	
	public function isModerator($list, $characters) {
		$moderators = explode(",", $list);
		return (in_array($moderators[0], $characters[0])) ? true : false;
	}
	
	public function getPostInfo($id) {
		return $this->db->query("SELECT `id`, `title`, `text`, `time`, `author`, `board_id`, `thread_id` FROM `posts` WHERE `id` = ".$this->db->escape($id)."")->result_array();
	}
	
	public function isAuthor($id) {
		return ($this->db->query("SELECT `id` FROM `players` WHERE `id` = ".$this->db->escape($id)." AND `account_id` = ".$_SESSION['account_id']."")->num_rows == 0) ? false : true;
	}
	
	public function editPost($id, $title, $body) {
		$this->db->query("UPDATE `posts` SET `title` = ".$this->db->escape($title).", `text` = ".$this->db->escape($body)." WHERE `id` = ".$this->db->escape($id)."");
	}
	
	public function deletePost($id) {
		$post = $this->getPostInfo($id);
		$thread = $this->db->query("SELECT `id` FROM `posts` WHERE `thread_id` = ".$this->db->escape($post[0]['thread_id'])."")->num_rows;
			if($thread <= 1)
				$this->db->query("DELETE FROM `threads` WHERE `id` = ".$this->db->escape($post[0]['thread_id'])."");
		$this->db->query("DELETE FROM `posts` WHERE `id` = ".$this->db->escape($id)."");
	}
	
	public function deleteThread($id) {
		$this->db->query("DELETE FROM `posts` WHERE `thread_id` = ".$this->db->escape($id)."");
		$this->db->query("DELETE FROM `threads` WHERE `id` = ".$this->db->escape($id)."");
	}
	
	public function getBoardsName() {
		return $this->db->query("SELECT `id`, `name` FROM `forums`")->result_array();
	}
	
	public function createBoard($name, $description, $access, $closed, $order, $login, $moderators, $guild=NULL) {
		$data = array('name' => $name,
					  'description' => $description,
					  'access' => $access,
					  'closed' => $closed,
					  'order' => $order,
					  'requireLogin' => $login,
					  'moderators' => $moderators,
					  'guild' => $guild);
		
		try {		  
			$this->db->insert('forums', $data);
		}catch(Exception $e) {
			print_r($e->getMessage());	
		}
		
	}
	
	public function fetchBoard($id) {
		$id = $this->db->escape($id);
		return $this->db->query("SELECT * FROM `forums` WHERE `id` = ".$id."")->result_array();
	}
	
	public function editBoard($id, $name, $description, $access, $closed, $order, $login, $moderators) {
		$data = array('name' => $name,
					  'description' => $description,
					  'access' => $access,
					  'closed' => $closed,
					  'order' => $order,
					  'requireLogin' => $login,
					  'moderators' => $moderators);
					  
		$this->db->update('forums', $data, array('id' => $id));
	}
	
	public function deleteBoard($id) {
		$id = $this->db->escape($id);
		$this->db->query("DELETE FROM `posts` WHERE `board_id` = ".$id."");
		$this->db->query("DELETE FROM `threads` WHERE `board_id` = ".$id."");
		$this->db->query("DELETE FROM `forums` WHERE `id` = ".$id."");
	}

	public function deleteBoardByGuild($id) {
		$bid = $this->db->get_where('forums', array('guild' => $id))->result_array();
		$this->deleteBoard($bid[0]['id']);
	}
		
	public function stickThread($id) {
		$this->db->update('threads', array('sticked' => 1), array('id' => $id));
	}
	
	public function unstickThread($id) {
		$this->db->update('threads', array('sticked' => 0), array('id' => $id));
	}
	
	public function closeThread($id) {
		$this->db->update('threads', array('closed' => 1), array('id' => $id));
	}
	
	public function openThread($id) {
		$this->db->update('threads', array('closed' => 0), array('id' => $id));
	}
	
	public function getFirstPost($id) {
		$this->db->select('id');
		$this->db->order_by('id ASC');
		$this->db->limit(1);
		return $this->db->get_where('posts', array('thread_id' => $id))->result_array();
	}
	
	public function truncateThread($id, $first) {
		$this->db->delete('posts', array('thread_id' => $id, 'id !=' => $first));
	}
}

?>
