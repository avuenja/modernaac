<?php 
// Controller for latestdeaths.
class Deaths extends Controller {
	public function index() {
		require("config.php");
		$this->load->database();
		if(@$_REQUEST['world'] == 0) 
			$world = 0;
		else 
			$world = (int)@$_REQUEST['world'];	
			
		$world_name = ($config['worlds'] == $world);
			
		$players_deaths = $this->db->query('SELECT `player_deaths`.`id`, `player_deaths`.`date`, `player_deaths`.`level`, `players`.`name`, `players`.`world_id` FROM `player_deaths` LEFT JOIN `players` ON `player_deaths`.`player_id` = `players`.`id` ORDER BY `date` DESC LIMIT 0,'.$config['latestdeathlimit'])->result();
	if (!empty($players_deaths))		
		{
			foreach ($players_deaths as $death)
			{
				$sql = $this->db->query('SELECT environment_killers.name AS monster_name, players.name AS player_name, players.deleted AS player_exists FROM killers LEFT JOIN environment_killers ON killers.id = environment_killers.kill_id LEFT JOIN player_killers ON killers.id = player_killers.kill_id LEFT JOIN players ON players.id = player_killers.player_id WHERE killers.death_id = '.$death->id.' ORDER BY killers.final_hit DESC, killers.id ASC')->result();
			$players_rows = '<td><a href="?subtopic=characters&name='.urlencode($death->name).'"><b>'.$death->name.'</b></a> ';
					$i = 0;
					$count = count($death);
				foreach($sql as $deaths)
				{ 
					$i++;
					if($deaths->player_name != "")
					{
						if($i == 1)
							$players_rows .= "killed at level <b>".$death->level."</b>";
						else if($i == $count)
							$players_rows .= " and";
						else
							$players_rows .= ",";

						$players_rows .= " by ";
						if($deaths->monster_name != "")
							$players_rows .= $deaths->monster_name." summoned by ";

						if($deaths->player_exists == 0)
							$players_rows .= "<a href=\"index.php?subtopic=characters&name=".urlencode($deaths->player_name)."\">";

						$players_rows .= $deaths->player_name;
						if($deaths->player_exists == 0)
							$players_rows .= "</a>";
					}
					else
					{
						if($i == 1)
							$players_rows .= "died at level <b>".$death->level."</b>";
						else if($i == $count)
							$players_rows .= " and";
						else
							$players_rows .= ",";

						$players_rows .= " by ".$deaths->monster_name;
					}
						$players_rows .= "</td>";
					$data['deaths'][] = array('players_rows'=>$players_rows,'date'=>$death->date,'name'=>$death->name,'player_name'=>$deaths->player_name,'monster_name'=>$deaths->monster_name,'player_exists'=>$deaths->player_exists); 
					$players_rows = "";
				}
			}
			$this->load->helper("form");
			$this->load->view("deaths", $data);
		}
		else
		{
		echo "<h1>There are no players killed yet.</h1>";
		}

	}
}
?>