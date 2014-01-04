<?php require("config.php"); 
$GLOBALS['house'] = $house;
$ide = new IDE;
try { $ide->loadInjections("view_house"); } catch (Exception $e) { error($e->getMessage()); }
?>
<div style='width: 160px; padding-right: 5px; float: left;'>

	<?php 
		
		$image = (file_exists("public/houses_maps/".$id.".png")) ? "<img src='".WEBSITE."/public/houses_maps/".$id.".png'/>" : "<img src='".WEBSITE."/public/houses_maps/default.png'/>";
		echo $image;
		echo "<br/>";
			if(empty($house[0]['owner']) && $house[0]['bid'] == 0) {
				echo "<center>Currently no one is bidding at this house.</center> <br/>";
				echo "<center><a href='".WEBSITE."/index.php/houses/start_auction/".$id."'><button class='ide_button' onClick=\"window.location.href='".WEBSITE."/index.php/houses/start_auction/".$id."';\">Start Auction</button></a></center>";
			}
			elseif(empty($house[0]['owner']) && $house[0]['bid'] != 0) {
				echo "<center>The auction on this house is in progress! Currently highers bid is ".$house[0]['bid']." gold.</center> <br/>";
				echo "<center><a href='".WEBSITE."/index.php/houses/join_auction/".$id."'><button class='ide_button' onClick=\"window.location.href='".WEBSITE."/index.php/houses/join_auction/".$id."';\">Join Auction</button></a></center>";
				echo "<br/><center>The auctions finishes at:<br/>".UNIX_TimeStamp($house[0]['endtime'])."</center>";
			}
			
			if($ide->isLogged()) {
				if(in_multiarray($house[0]['owner'], $characters)) {
					echo "<li><a href='#' onClick='if(confirm(\"Are you sure you want to leave this house?\")) window.location.href=\"".WEBSITE."/index.php/houses/abandon/".$id."\";'>Abandon House</a></li>";
				}
			}
	?>
	
</div>

<div style='float: left; margin-top: -17px;'>

	<h1><?php echo $house[0]['name']; ?></h1>
	<?php 
		
		echo (empty($house[0]['owner'])) ? "This house is <font color='green'><b>free</b></font>." : "This house is <font color='red'><b>taken</b></font>.<br/> It's been paid on ".UNIX_TimeStamp($house[0]['paid'])." | ".ago($house[0]['paid'])."<br/><br/>";
		$owner = (empty($house[0]['owner'])) ? "None" : "<a href=\"".WEBSITE."/index.php/character/view/".$house[0]['owner']."\">".$house[0]['owner']."</a>";
		echo "<table width='100%'>";
		echo "<tr class='highlight'><td width='20%'><b>Rent</b></td><td>".$house[0]['rent']." gold</td></tr>";
		echo "<tr class='highlight'><td width='20%'><b>Owner</b></td><td>".$owner."</td></tr>";
		echo "<tr class='highlight'><td width='20%'><b>World</b></td><td>".@$config['worlds'][$house[0]['world_id']]."</td></tr>";
		echo "<tr class='highlight'><td width='20%'><b>City</b></td><td>".@$config['cities'][$house[0]['town']]."</td></tr>";
		echo "<tr class='highlight'><td width='20%'><b>Size</b></td><td>".$house[0]['size']." sqm</td></tr>";
		echo "<tr class='highlight'><td width='20%'><b>Doors</b></td><td>".$house[0]['doors']."</td></tr>";
		echo "<tr class='highlight'><td width='20%'><b>Beds</b></td><td>".$house[0]['beds']."</td></tr>";
		echo "<tr class='highlight'><td width='20%'><b>Tiles</b></td><td>".$house[0]['tiles']."</td></tr>";
		echo "</table>";
		if($house[0]['guild'] == 1) echo "<b>This is a guild house!</b>";
		
	?>
	
	
</div>