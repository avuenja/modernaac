<?php 
require("config.php");
$ide = new IDE;
echo "<h1>Forum boards on ".$config['server_name']."</h1>";
echo "<div class='boardInformation'>";
	echo "<div class='boardInformationTitle'>".$board[0]['name']."</div>";
	echo "<div class='boardInformationDescription'>".$board[0]['description']."</div>";
	echo "<div class='boardInformationModerators'>Moderators: ".$board[0]['moderators']."</div>";
echo "</div>";
echo "<div class='forumHistory'|<a href='".WEBSITE."/index.php/forum'>Forum</a> >> <a href='".WEBSITE."/index.php/forum/board/".$board[0]['id']."'>".$board[0]['name']."</a></div>";

if(count($threads) == 0) alert("There is no threads yet.");
if($board[0]['closed'] != 1)
		if(!$ide->isLogged())
			alert("You need to be logged in to access options.");
	
echo "<div class='boardPages'>".$pages."</div>";
	if($board[0]['closed'] == 1) 
		echo "<a href='".WEBSITE."/index.php/forum/new_thread/".$board[0]['id']."'><img style='margin-bottom: -20px;' src='".WEBSITE."/public/images/forum/closedReply.png'></a>";
	else
		echo "<a href='".WEBSITE."/index.php/forum/new_thread/".$board[0]['id']."'><img style='margin-bottom: -20px;' src='".WEBSITE."/public/images/forum/newthread.png'></a>";

foreach($threads as $thread) {
	if($thread['sticked'] == 1 and $thread['closed'] == 1)
		$status = "stickclose.png";
	else if($thread['sticked'] == 1 and $thread['closed'] == 0)
		$status = "sticked.png";
	else if($thread['sticked'] == 0 and $thread['closed'] == 1)
		$status = "closedthread.png";
	else
		$status = "open.png";
		
	if(empty($thread['post_author']))
			$last = "No posts";
		else
			$last = "By <a href=\"".WEBSITE."/index.php/character/view/".$thread['post_author']."\">".truncateString($thread['post_author'], 10)."</a><br /> ".ago($thread['post_time']);
	echo "<div class='threadTable'>";
		echo "<div class='threadStatus'>";
			echo "<img src='".WEBSITE."/public/images/forum/".$status."'>";
		echo "</div>";
		echo "<div class='threadLeft'>";
			echo "<div class='threadTitle'><a href='".WEBSITE."/index.php/forum/thread/".$thread['id']."'>";
				echo $thread['name'];
			echo "</a></div>";
			echo "<div class='threadAuthor'>";
				echo "Posted by: ".ucfirst($thread['author']);
			echo "</div>";
		echo "</div>";
		echo "<div class='threadRight'>";
			echo $last;
		echo "</div>";
	echo "</div>";
}
echo "<div class='boardPages'>".$pages."</div>";
?>