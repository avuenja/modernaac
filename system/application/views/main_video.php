<?php 
	$ide = new IDE;
	$GLOBALS['videos'] = $videos;
	try { $ide->loadInjections("videos_main"); } catch (Exception $e) { error($e->getMessage()); }
	if(!$ide->isLogged()) 
		alert("You need to be logged, in order to add videos");
	else {
		echo "<div class='toolbar'>";
			echo "<a href='".WEBSITE."/index.php/video/add'>Add video</a> | ";
			echo "<a href='".WEBSITE."/index.php/video/my'>My Videos</a> | ";
		echo "</div>";	
	}
	echo "<div class='video_search'>";
		echo form_open("video/doSearch");
			echo "Search: &nbsp; <input type='text' name='query'/>";
			echo "<input type='submit' value='Search'/>";
		echo "</form>";
	echo "</div>";
	if(empty($videos))
		echo "<center><b>There is no videos yet.</b></center>";
	else {
		foreach($videos as $video) {
			echo "<div style='float: left; width:50%;overflow:hidden;'>";
				echo "<div style='float: left;'>";
				echo "<a href='".WEBSITE."/index.php/video/view/".$video['id']."'><img style='border: 1px groove silver; padding: 1px;' src='http://i1.ytimg.com/vi/".$video['youtube']."/default.jpg'/></a>";
				echo "</div>";
				echo "<div style='float: left; margin-left: 5px;'>";
				echo "<a href='".WEBSITE."/index.php/video/view/".$video['id']."'>".truncateString($video['title'], 20)."</a><br/>";
				echo "Views: ".$video['views']."<br/>";
				echo "Added: ".ago($video['time'])."<br/>";
				echo "Author: <a href=\"".WEBSITE."/index.php/character/view/".$video['author']."\">".$video['author']."</a>";
				echo "</div>";
			echo "</div>";
		}
		
		
	}
?>
<div style='clear: both;'></div>