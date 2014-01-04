<?php
/**
 * @title Poll System for modern AAC
 * @description model for poll system made for modern aac
 * @version 0.0.1
 * @author tatu hunter (diorgesl@gmail.com)
 * @copyright tatu hunter
 */
 
class Poll_model extends Model {
	
	public function __construct() {
    	parent::__construct();
    	$this->load->database();
  	}

	// Return the last poll inserted in database with answers, votes, total votes and if the client alreadt voted
	public function getLastPoll(){
		$ide = new IDE;
		$query = 'SELECT p.id, p.question, GROUP_CONCAT(a.answer SEPARATOR \';\') AS answers, GROUP_CONCAT(a.id SEPARATOR \';\') AS answers_id
				  FROM poll p
				  JOIN poll_answer a ON p.id = a.poll_id
				  WHERE p.status = 1 AND date_start <= NOW() AND date_end >= NOW()';
		
		$result = $this->db->query($query)->row_array();
		
		$data['question'] = $result['question'];
		$data['poll_id'] = $result['id'];
		$data['answers'] = array_combine(explode(';', $result['answers_id']), explode(';', $result['answers']));	
		
		$data['total'] = 0;
		foreach($data['answers'] as $a => $v) {
			$data['votes'][$a] =  $this->db->query('SELECT * FROM poll_votes WHERE answer_id = '. (int) $a.'')->num_rows();
			$data['total'] += $data['votes'][$a];
			$data['voted'] =  $this->isVoted($data['poll_id'], ($ide->isLogged() ? $ide->loggedAccountId() : false));
		}

		return $data;
	}
	
	// Return a poll by ID with your options
	public function getPoll($id) {
		
		$query = 'SELECT p.*, GROUP_CONCAT(a.answer SEPARATOR \';\') AS answers, GROUP_CONCAT(a.id SEPARATOR \';\') AS answers_id
				  FROM poll p
				  LEFT OUTER JOIN poll_answer a ON p.id = a.poll_id
				  WHERE p.id = ?';

		$result = $this->db->query($query, $id)->row_array();
		
		if($result['answers'])
			$result['answers'] = array_combine(explode(';', $result['answers_id']), explode(';', $result['answers']));

		return $result;
	}
	
	// Return all polls
	public function getPolls() {
		return $this->db->get('poll')->result_array();
	}
	
	// Return the number of polls in db
	public function getPollsAmount() {
		return $this->db->count_all('poll');
	}
	
	// Create a new Poll
	public function newPoll($data) {
		return $this->db->insert('poll', $data);
	}
	
	// Edit a poll
	public function editPoll($data) {
		return $this->db->update('poll', $data, array('id' =>$data['id']));
	}
	
	// Delete a poll
	public function deletePoll($id) {
		$this->db->delete(array('poll_votes', 'poll_answer'), array('poll_id' => $id));
		$this->db->delete('poll', array('id' => $id));
	}
	
	// Add an option to poll
	public function newAnswer($data) {
		return $this->db->insert('poll_answer', $data);
	}
	
	// Edit the options
	public function editAnswers($data) {
		foreach($data as $k => $v) {
			empty($v) ? $this->deleteAnswer($k) : $this->updateAnswer($k, htmlspecialchars($v));
		}
	}
	
	// Delete an option from poll
	public function deleteAnswer($id) {
		$this->db->delete('poll_answer', array('id' => $id));
		$this->db->delete('poll_votes', array('answer_id' => $id));
	}
	
	// Update the options if changed
	public function updateAnswer($index, $value) {
		$this->db->update('poll_answer', array('answer' => $value), array('id' => $index));	
	}
	
	public function isVoted($poll_id, $account_id) {
		return $this->db->get_where('poll_votes', array('poll_id' => (int) $poll_id, 'account_id' => (int) $account_id))->num_rows();
	}
	
	// Add a vote
	public function doVote($data) {
		$ide = new IDE;
		if(!$ide->isLogged())
			redirect('/account/login', 'refresh');
			
		$data['account_id'] = $ide->loggedAccountId();
		return $this->db->insert('poll_votes', $data);
	}
}
?>