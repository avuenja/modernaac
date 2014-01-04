<center><b>Bug tracker allows you to view current bugs on the server, also you are able to send bug notices to be fixed.</b></center>
<br />
<?php 
	if(count($bugs) == 0)
		alert("No bugs has been submited.");
	else {
	echo "<center>".$pages."</cemter>";
		echo "<table width='100%'>";
		echo "<tr><td width='2%'></td><td width='30%'><center><b>Title</b></center></td><td><center><b>Category</b></center></td><td><center><b>Priority</b></center></td><td><center><b>Author</b></center></td><td><center><b>Done</b></center></td><td><center><b>Time</b></center></td></tr>";
			foreach($bugs as $bug) {
				$status = ($bug['closed'] == 0) ? "open.gif" : "closed.gif";
				echo "<tr class='bugtrackerRow'><td><center><img src='".WEBSITE."/public/images/bugtracker/".$status."'></center></td><td width='30%'><center><a href='".WEBSITE."/index.php/bugtracker/view/".$bug['id']."'>".truncateString($bug['title'], 26)."</a></center></td><td><center>".bugtracker_getCategory($bug['category'])."</center></td><td><center>".bugtracker_getPriorityImage($bug['priority'])." ".bugtracker_getPriority($bug['priority'])."</center></td><td><center><a href=\"".WEBSITE."/index.php/character/view/".$bug['name']."\">".$bug['name']."</a></center></td><td><center><div class='bugtrackerProgressBar'><div class='bugtrackerProgress' style='width: ".$bug['done']."%;'></div></div>".$bug['done']."%</center></td><td><center>".ago($bug['time'])."</center></td></tr>";
			}
		echo "</table>";
	}
	echo "<center>".$pages."</cemter>";
?>
