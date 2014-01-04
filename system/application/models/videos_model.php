<?php 
class videos_model extends model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	public function getCharacters() {
		$this->db->select('id, name');
		return $this->db->get_where('players', array('account_id' => $_SESSION['account_id'], 'deleted' => 0))->result_array();
	}
	
	public function checkCharacter($id) {
		return $this->db->get_where('players', array('id' => $id, 'account_id' => $_SESSION['account_id']))->num_rows ? true : false;
	}
	
	public function addVideo($id, $character, $title, $description) {
		$data = array('author' => $character,
					'title' => $title,
					'description' => $description,
					'youtube' => $id,
					'time' => $_SERVER['REQUEST_TIME']);

		$this->db->insert("videos", $data);
	}
	
	public function videoExists($id) {
		return $this->db->get_where('videos', array('youtube' => $id))->num_rows ? true : false;
	}
	
	public function getMainVideos() {
		$this->db->select('v.*, p.name as author');
		$this->db->from('videos v');
		$this->db->join('players p', 'p.id = v.author');
		$this->db->orderby('views desc');
		return $this->db->get()->result_array();
	}
	
	public function loadVideo($id) {
		return $this->db->query("SELECT videos.id, videos.title, videos.description,  videos.views, videos.youtube, videos.time, players.name as author FROM `videos` LEFT JOIN players ON players.id = videos.author WHERE videos.id = $id")->result_array();
	}
	
	public function getComments($id) {
		require("config.php");
		$this->load->helper("url");
		$page = $this->uri->segment(4);
			$page = (empty($page)) ? 0 : $page;
		return $this->db->query("SELECT video_comments.id, video_comments.time, video_comments.text, players.name as author FROM video_comments LEFT JOIN players ON players.id = video_comments.author WHERE video_comments.video = '".$id."' ORDER BY id DESC LIMIT ".$page.", ".$config['videoCommentsLimit']."")->result_array();
	}
	
	public function getCommentsAmount($id) {
		return $this->db->query("SELECT `id` FROM `video_comments` WHERE `video` = '".$id."'")->num_rows;
	}
	
	public function createComment($video, $character, $text) {
		$data['author'] = $character;
		$data['video'] = $video;
		$data['time'] = time();
		$data['text'] = $text;
		$this->db->insert("video_comments", $data);
	}
	
	public function getCommentAuthor($id) {
		return $this->db->query("SELECT players.name as author FROM `video_comments` LEFT JOIN players ON players.id = video_comments.author  WHERE video_comments.id = '".$id."'")->result_array();
	}
	
	public function deleteComment($id) {
		$this->db->query("DELETE FROM video_comments WHERE id = '".$id."'");
	}
	
	public function searchVideos($string) {
		require("config.php");
		$this->load->helper("url");
		$page = $this->uri->segment(4);
			$page = (empty($page)) ? 0 : $page;
		$string = str_replace(" ", "%", $string);
		return $this->db->query("SELECT videos.id, videos.title, videos.views, videos.youtube, videos.time, players.name as author FROM `videos` LEFT JOIN players ON players.id = videos.author WHERE videos.title LIKE \"%".$string."%\" OR videos.description LIKE \"%".$string."%\" ORDER BY `views` DESC LIMIT ".$page.", ".$config['videoSearchLimit']."")->result_array();
	}
	
	public function searchVideosAmount($string) {
		$string = str_replace(" ", "%", $string);
		return $this->db->query("SELECT videos.id FROM `videos` WHERE videos.title LIKE \"%".$string."%\" OR videos.description LIKE \"%".$string."%\"")->num_rows();
	}
	
	public function getMyVideos() {
		return $this->db->query("SELECT videos.id, videos.title, videos.views, videos.youtube, videos.time, players.name as author FROM `videos` LEFT JOIN players ON players.id = videos.author WHERE players.account_id = '".$_SESSION['account_id']."'")->result_array();
	}
	
	public function addView($id) {
		$this->db->query("UPDATE videos SET views = views+1 WHERE `id` = $id");
	}
	
	public function updateVideo($id, $title, $description) {
		$this->db->query("UPDATE videos SET title = '".$title."', description = '".$description."' WHERE id = '".$id."'");
	}
	
	public function delete($id) {
		$this->db->query("DELETE FROM video_comments WHERE video = '".$id."'");
		$this->db->query("DELETE FROM videos WHERE id = '".$id."'");
	}
}
?>