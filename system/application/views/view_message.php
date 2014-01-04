<?php 
	echo "<div class='message'><div class='title'>".$message[0]['title']."</div><div class='content'>";
	echo "<label>From</label>".$message[0]['from_nick']."<br/><br/>";
	echo "<label>To</label>".$message[0]['to_nick']."<br/><br/>";
	echo "<label>Date</label>".UNIX_TimeStamp($message[0]['time'])." &nbsp; ".ago($message[0]['time'])."<br/><br/>";
	echo nl2br($message[0]['text']);
	echo "</div></div>";
	echo "<a href='".url('msg/write/'.$message[0]['from_nick'])."'><b>Respond</b></a>";
?>