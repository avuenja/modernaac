<?php 
class Highscore_model extends Model {
	
	function getSkill($skillid, $worldid, $limit = 0, $offset = 0) {
		global $config;
		$this->load->database();
		
		$binds = array($worldid, $config['players_group_id_block']);
		if(is_numeric($skillid) && in_array($skillid, array(1,2,3,4,5,6,7))) {
			$query = 'SELECT p.name, p.online, s.value, p.level, p.vocation, p.promotion 
				  	  FROM players p
				  	  INNER JOIN player_skills s on (s.player_id = p.id)
				  	  WHERE p.world_id = ? AND p.deleted = 0 AND p.group_id < ? AND s.skillid = ?
					  ORDER BY s.value DESC';
			$binds[] = (int) ( $skillid - 1 );
		}else {
			$skillid = in_array($skillid, array('level', 'magic')) ? $skillid : 'level';
			switch($skillid) {
				case 'magic':
					$query = 'SELECT name, online, maglevel, vocation, promotion, world_id
					  	  FROM players
					  	  WHERE world_id = ? AND deleted = 0 AND group_id < ? AND name != "Account Manager"
						  ORDER BY maglevel DESC, manaspent DESC';
				break;
				case 'level':
				default:
					$query = 'SELECT name, online, level, experience, vocation, promotion, world_id
					  	  FROM players
					  	  WHERE world_id = ? AND deleted = 0 AND group_id < ? AND name != "Account Manager"
						  ORDER BY experience DESC';
			}			
		}
		
		$total = $this->db->query($query, $binds);
								   
		if($limit > 0 && is_numeric($limit) && !$offset)
			$query .= " LIMIT ".$limit;
		
		if($limit >= 0 and $offset > 0 and is_numeric($offset))
			$query .= " LIMIT ".$limit.",".$offset;
			
		$data = $this->db->query($query, $binds);

		return array('skill' => $skillid, 'data' => $data->result_array(), 'total' => $total->num_rows);
	}		
}
?>