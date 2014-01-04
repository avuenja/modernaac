<?php 

	if(empty($videos))
		alert("You don't have any videos yet.");
	else {
		foreach($videos as $video) {
			echo "<div style='clear: both;'>";
				echo "<div style='float: left;'>";
				echo "<a href='".WEBSITE."/index.php/video/view/".$video['id']."'><img style='border: 1px groove silver; padding: 1px;' src='http://i1.ytimg.com/vi/".$video['youtube']."/default.jpg'/></a>";
				echo "</div>";
				echo "<div style='float: left; margin-left: 5px;'>";
				echo "<a href='".WEBSITE."/index.php/video/view/".$video['id']."'>".$video['title']."</a><br/>";
				echo "Views: ".$video['views']."<br/>";
				echo "Added: ".ago($video['time'])."<br/>";
				echo "Author: <a href=\"".WEBSITE."/index.php/character/view/".$video['author']."\">".$video['author']."</a><br/>";
				echo "<a href='".WEBSITE."/index.php/video/edit/".$video['id']."' class='tipsy' title='Edit Video'><img src='".WEBSITE."/public/images/edit.gif'/></a>";
					echo "<a class='tipsy' onClick=\"if(confirm('Are you sure you want to delete this video?')) window.location.href='".WEBSITE."/index.php/video/delete/".$video['id']."';\" title='Delete Video'><img src='".WEBSITE."/public/images/false.gif'/></a>";
				echo "</div>";
			echo "</div>";
		}
	}

?>