<?php 
$ide = new IDE;
$GLOBALS['videos'] = $videos;
try { $ide->loadInjections("videos_search"); } catch (Exception $e) { error($e->getMessage()); }
if(empty($videos))
		echo "<center><b>There is no videos yet.</b></center>";
	else {
		echo "<b><center>Showing results of ".decodeString($query)."</center></b>";
		echo "<div style='clear: both;'></div><center>".$pages."</center>";
		foreach($videos as $video) {
			echo "<div style='float: left; margin-left: 10px;'>";
				echo "<div style='float: left;'>";
				echo "<a href='".WEBSITE."/index.php/video/view/".$video['id']."'><img style='border: 1px groove silver; padding: 1px;' src='http://i1.ytimg.com/vi/".$video['youtube']."/default.jpg'/></a>";
				echo "</div>";
				echo "<div style='float: left; margin-left: 5px;'>";
				echo "<a href='".WEBSITE."/index.php/video/view/".$video['id']."'>".$video['title']."</a><br/>";
				echo "Views: ".$video['views']."<br/>";
				echo "Added: ".ago($video['time'])."<br/>";
				echo "Author: <a href=\"".WEBSITE."/index.php/character/view/".$video['author']."\">".$video['author']."</a>";
				echo "</div>";
			echo "</div>";
		}
		echo "<div style='clear: both;'></div><center>".$pages."</center>";
		
	}

?>