<h2>Outbox</h2>
<?php 
	if(empty($messages))
		alert("You don't have any messages.");
	else {
		echo "<center>".$pages."</center>";
		echo "<table width='100%'>";
			echo "<tr><td width='50%'><center><b>Title</b></center></td><td><center><b>To</b></center></td><td><center><b>Date</b></center></td><td><center><b>Actions</b></center></td></tr>";
			foreach($messages as $message) {
				echo "<tr><td width='50%'><center><a href='".url('msg/view/'.$message['id'])."'>".$message['title']."</a></center></td><td><center><a href='".url('profile/view/'.$message['author'])."'>".$message['author']."</a></center></td><td><center>".UNIX_TimeStamp($message['time'])."</center></td><td><center><a href='#' onClick='if(confirm(\"Are you sure?\")) window.location.href=\"".url('msg/delete/'.$message['id'])."\"'><img src='".WEBSITE."/public/images/false.gif'/></a></center></td></tr>";
			}
		echo "</table>";
		echo "<center>".$pages."</center>";
	}
?>