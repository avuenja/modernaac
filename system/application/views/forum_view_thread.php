<?php 
require("config.php");
$ide = new IDE;
echo "<h1>Forum boards on ".$config['server_name']."</h1>";
echo "<div class='forumHistory'|<a href='".WEBSITE."/index.php/forum'>Forum</a> >> <a href='".WEBSITE."/index.php/forum/board/".$board[0]['id']."'>".$board[0]['name']."</a> >> <a href='".WEBSITE."/index.php/forum/thread/".$thread[0]['id']."'>".$thread[0]['name']."</a></div>";
echo "<div class='boardPages'>".$pages."</div>";
	if($board[0]['closed'] != 1)
		if(!$ide->isLogged())
			alert("You need to be logged in to access options.");
if($ide->isLogged()) {
	if($isModerator or $ide->isAdmin()) {
		echo "<fieldset class='moderatingPanel'>";
			echo "<legend>Moderating panel</legend>";
			echo "<a href='#' onClick=\"if(confirm('Are you sure you want to delete this thread?')) window.location.href='".WEBSITE."/index.php/forum/delete_thread/".$id."';\">Delete thread</a> | ";
				if($thread[0]['sticked'] == 0)
			echo "<a href='".WEBSITE."/index.php/forum/stick_thread/".$id."'>Stick thread</a> | ";
				else
			echo "<a href='".WEBSITE."/index.php/forum/unstick_thread/".$id."'>Unstick thread</a> | ";
				if($thread[0]['closed'] == 0)
			echo "<a href='".WEBSITE."/index.php/forum/close_thread/".$id."'>Close thread</a> | ";
				else
			echo "<a href='".WEBSITE."/index.php/forum/open_thread/".$id."'>Open Thread</a> | ";
			echo "<a href='#' onClick=\"if(confirm('Are you sure you want to truncate this thread?')) window.location.href='".WEBSITE."/index.php/forum/truncate_thread/".$id."';\">Truncate Thread</a> | ";
		echo "</fieldset>";
	}
}
				
if($board[0]['closed'] == 1 or $thread[0]['closed'] == 1) 
		echo "<a href='".WEBSITE."/index.php/forum/reply/".$thread[0]['id']."'><img style='margin-bottom: -10px;' src='".WEBSITE."/public/images/forum/closedReply.png'></a>";
	else
		echo "<a href='".WEBSITE."/index.php/forum/reply/".$thread[0]['id']."'><img style='margin-bottom: -10px;' src='".WEBSITE."/public/images/forum/reply.png'></a>";
			
foreach($posts as $post) {
	$avatar = (empty($post['avatar'])) ? "<img width='62' class='avatar' src='".WEBSITE."/public/uploads/avatars/no_avatar.png'/>" : "<img class='avatar' width='62' src='".WEBSITE."/public/uploads/avatars/".$post['avatar']."'/>";
	echo "<div class='forumPost'>";
		echo "<div class='forumPostDate'>#".$post['id']." &nbsp; &nbsp; Posted on: ".UNIX_TimeStamp($post['time'])." &nbsp; (".ago($post['time']).")</div>";
		echo "<table width='100%'>";
			echo "<div class='postTitle'>".$post['title']."</div>";
			echo "<td valign='top' class='forumPostLeft' width='15%'>";
			echo "<div class='forumPostAuthor'><a href=\"".WEBSITE."/index.php/character/view/".$post['author']."\"><b>".ucfirst($post['author'])."</b></a></div>";
			echo $avatar;
			echo "</td>";
			echo "<td valign='top' class='forumPostRight'>";
			echo "<div class='forumPostText'>".strip_tags($post['text'])."</div>";
			echo "</td>";
		echo "</table>";
		echo "<div class='postToolBar'>";
			echo "<form style='display: inline; float: right;' method='post' action='".WEBSITE."/index.php/forum/reply/".$thread[0]['id']."'><textarea style='display: none;' name='post'><quote>Quote <b>".$post['author']."</b> ".$post['text']."</quote><br /></textarea><input type='image' src='".WEBSITE."/public/images/forum/quote.png'></form>";
			if($ide->isLogged()) {
				if(in_array($post['author'], $characters[0]) or $isModerator == true or $ide->isAdmin() == true) {
					echo "<a href='#' onClick=\"if(confirm('Are you sure you want to delete this post?')) window.location.href='".WEBSITE."/index.php/forum/delete_post/".$post['id']."';\"><img style='float: right;' src='".WEBSITE."/public/images/false.gif'></a>";
					echo "<a href='".WEBSITE."/index.php/forum/edit/".$post['id']."'><img src='".WEBSITE."/public/images/forum/edit.png'></a>";
				}
			}
			echo "</div>";
	echo "</div>";
}
echo "<div class='boardPages'>".$pages."</div>";
?>