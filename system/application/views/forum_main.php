<?php 
require("config.php");
echo "<h1>Forum boards on ".$config['server_name']."</h1><br />";

if(count($boards) == 0)
	alert("There is no boards to display.");
else {
foreach($boards as $board) {
		if(empty($board['author']))
			$last = "No posts";
		else
			$last = "<a href='".WEBSITE."/index.php/forum/thread/".$board['thread_id']."'>".truncateString($board['thread_title'], 20)."</a><br />by <a href=\"".WEBSITE."/index.php/character/view/".$board['author']."\">".truncateString($board['author'], 10)."</a><br /> ".ago($board['time']);
	$status = ($board['closed'] == 0) ? "openBoard.png" : "closed.png";
	echo "<div class='forumBoard'>";
		echo "<div class='boardStatus'>";
			echo "<img src='".WEBSITE."/public/images/forum/".$status."'>";
		echo "</div>";
		echo "<div class='leftBoard'>";
			echo "<div class='boardTitle'><a href='".WEBSITE."/index.php/forum/board/".$board['id']."'>".$board['name']."</a></div>";
			echo "<div class='boardDescription'>".$board['description']."</div>";
		echo "</div>";
		echo "<div class='rightBoard'>";
		echo $last;
		echo "</div>";
	echo "</div>";
	echo "<div style='clear: both;'></div>";
}
}

?>