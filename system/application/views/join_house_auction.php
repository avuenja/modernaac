<?php 
echo "<h1>Auction on ".$house[0]['name']."</h1>";
$bid = (empty($house[0]['bid'])) ? 1 : $house[0]['bid']+1;
echo "<br/><center><b>Minimum bid: ".$bid." gold</b></center>";
if(empty($_POST['bid']))
	$_POST['bid'] = $bid+1;
	if(empty($characters)) 
		error("You don't have character on your account which passes the requirements.");
	else {
		error(validation_errors());
		echo form_open("houses/join_auction/".$id);
			echo "<br/><label>Character</label><select name='character'>";
			foreach($characters as $character) {
				echo "<option ".set_select('character', $character['id'])." value='".$character['id']."'>".$character['name']."</option>";
			}
			echo "</select><br/><br/>";
		echo "<label>Maximum Bid</label>";
			echo "<input type='text' name='bid' value='".set_value('bid')."' size='6'/> gold <br/>";
			echo "<label></label><input type='submit' value='Bid'/>";
		echo "</form>";
	}
?>