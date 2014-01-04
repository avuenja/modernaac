<?php 
$ide = new IDE;
$GLOBALS['video'] = $video;
try { $ide->loadInjections("view_video"); } catch (Exception $e) { error($e->getMessage()); }
?>
<object width="100%" height="305"><param name="movie" value="http://www.youtube.com/v/<?php echo $video[0]['youtube']; ?>&hl=en_GB&fs=1&hd=1&border=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed id="video" src="http://www.youtube.com/v/<?php echo $video[0]['youtube'];?>&hl=en_GB&fs=1&hd=1&border=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="100%" height="305"></embed></object>


<div style='border: 1px solid silver; padding: 5px;'>
	<b>Added by</b> <a href='<?php echo WEBSITE; ?>/index.php/character/view/<?php echo $video[0]['author']; ?>'><?php echo $video[0]['author'];?></a>
	&nbsp; &nbsp; &nbsp; <b>Date</b> <?php echo ago($video[0]['time']); ?>
	&nbsp; &nbsp; &nbsp; <b>Views</b> <?php echo $video[0]['views']; ?>
	<br/>
	<fieldset style='padding: 3px; border: 1px silver dotted;'>
	<legend>Description</legend>
	<?php echo nl2br($video[0]['description']); ?>
	</fieldset>
</div>
<?php 
	if(!$ide->isLogged())
		alert("You need to be logged, in order to add comments.");
	else if(empty($characters)) 
		alert("You need to have atleast one character in order to post comments.");
	else {
		error(validation_errors());
		echo "<div id='add_comment' style='padding: 10px; border: 1px dotted silver; margin-top: 5px;'>";
		echo form_open("video/view/".$video[0]['id']);
			echo "<label>Character</label>";
			echo "<select name='character'>";
				foreach($characters as $character) {
					echo "<option value='".$character['id']."'>".$character['name']."</option>";
				}
			echo "</select><br/>";
			echo "<label>Comment</label><br/>";
			echo "<textarea name='comment'>".set_value('comment')."</textarea><br/>";
			echo "<label></label>";
			echo $captcha."<br/>";
			echo "<label>Captcha</label>";
			echo "<input type='text' name='captcha'/><br/>";
			echo "<label></label><input type='submit' value='Send'/>";
		echo "</form>";
		echo "</div>";
		if(empty($comments))
			alert("There is no comments on this video yet.");
		else {
			echo "<center>".$pages."</center>";
			foreach($comments as $comment) {		
				if($ide->isLogged())
					if($ide->isAdmin() or in_multiarray($comment['author'], $characters))
						$delete = "<a href='#' onClick='if(confirm(\"Are you sure you want to delete this comment?\")) window.location.href=\"".WEBSITE."/index.php/video/deleteComment/".$comment['id']."\"' ><img src='".WEBSITE."/public/images/false.gif'/></a> &nbsp; &nbsp; ";
					else
						$delete = "";
				echo "<div class='video_comment'>".$delete;
					echo "Author: ";
					echo "<a href=\"".WEBSITE."/index.php/character/view/".$comment['author']."\">".$comment['author']."</a>";
					echo "&nbsp; &nbsp; Time: ";
					echo ago($comment['time']);
					echo "<br/>".nl2br(strip_tags($comment['text']));
				
				echo "</div>"; 
			}
			echo "<center>".$pages."</center>";
		}
	}
?>






