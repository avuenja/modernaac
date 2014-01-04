<?php 
$ide = new IDE;
try { $ide->loadInjections("view_news"); }catch(Exception $e) { error($e->getMessage());}
	echo "<div class='news'>";
	echo "<div class='newsTitle'>".$news[0]['title']."</div>";
	echo "<div class='newsBody'>".$news[0]['body']."</div>";
	echo "<div class='newsFooter'>Posted on: ".UNIX_TimeStamp($news[0]['time'])." </div>";
	echo "</div>";
	
	echo "<h1>Comments</h1>";
	if(!$ide->isLogged())
		alert("You need to be logged in to write comments.");
	else {
		if(count($characters) == 0)
			error("You need to have a character on the server to comment on the news.");
		else {
			echo error(validation_errors());
			echo form_open(WEBSITE."/index.php/home/view/".$id);
				echo "<br /><label>Character</label><select name='character'>";
					foreach($characters as $character) {
						echo "<option value='".$character['id']."'>".$character['name']."</option>";
					}
				echo "</select><br /><br />";
				echo "<textarea style='width: 99%;' name='body'>".@$_POST['body']."</textarea><label></label>";
				echo $captcha;
				echo "<br/><label>Captcha</label>";
				echo "<input type='text' name='captcha'/><br/>";
				echo "<input type='submit' value='Comment'>";
			echo "</form>";
		}
	}
	echo "<center>".$pages."</center>";
	foreach($comments as $comment) {
		if($ide->isAdmin())
			$delete = "<a href='#' onClick=\"if(confirm('Are you sure you want to delete this comment?')) window.location.href='".WEBSITE."/index.php/home/delete_comment/".$comment['id']."';\" ><img src='".WEBSITE."/public/images/false.gif'></a>";
		else if($ide->isLogged()) {
			if(@in_array($comment['author'], $characters)) {
				$delete = "<a href='#' onClick=\"if(confirm('Are you sure you want to delete this comment?')) window.location.href='".WEBSITE."/index.php/home/delete_comment/".$comment['id']."';\" ><img src='".WEBSITE."/public/images/false.gif'></a>";
			}
			else
				$delete = "";
		}
		else
			$delete = "";
		echo "<div class='comment'>";
		echo "<div class='commentBody'>".strip_tags($comment['body'])."</div>";
		echo "<div class='commentFooter'>".$delete." Posted on: ".UNIX_TimeStamp($comment['time'])." by <a href=\"".WEBSITE."/index.php/character/view/".$comment['author']."\">".$comment['author']."</a></div>";
		echo "</div>";
	}
	echo "<center>".$pages."</center>";
?>