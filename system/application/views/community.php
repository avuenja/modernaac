<div class='message'  style='overflow: hidden; width: 30%; float: left;'>
	<div class='title'>Latest Videos</div>
	<div class='content'>
		<?php 
			if(empty($videos))
				echo "No videos!";
			else {
				foreach($videos as $video) {
						echo "<div style='float: left; width: 150px; text-align: center; border-bottom: 1px dotted silver; margin-bottom: 10px;'>";
							echo "<div style=''>";
							echo "<a href='".WEBSITE."/index.php/video/view/".$video['id']."'><img style='border: 1px groove silver; padding: 1px;' src='http://i1.ytimg.com/vi/".$video['youtube']."/default.jpg'/></a>";
							echo "</div>";
							echo "<div style='float: left; margin-left: 5px;'>";
							echo "<a href='".WEBSITE."/index.php/video/view/".$video['id']."'>".$video['title']."</a><br/>";
							echo "Views: ".$video['views']."<br/>";
							echo "Added: ".ago($video['time'])."<br/>";
							echo "</div>";
						echo "</div>";
					}
			}
		?>
	</div>
</div>

<div class='message'  style='overflow: hidden; width: 350px; float: right; margin-left: 20px; margin-bottom: 10px;'>
	<div class='title'>Latest Comments</div>
		<?php 
			if(empty($comments))
				echo "No Comments!";
			else {
				echo "<div style='height: 200px; overflow: auto;'>";
				foreach($comments as $comment) {
					echo "<div style='border-bottom: 1px dotted silver; margin-bottom: 5px; padding: 5px;'><b>Author</b>: ".$comment['author']." &nbsp; ".ago($comment['time'])."<br/>".$comment['body']."<br/><br/></div>";
				}
				echo "</div>";
			}
		?>
</div>

<div class='message' style='overflow: hidden; width: 350px; float: right; margin-left: 20px; margin-bottom: 10px;'>
	<div class='title'>Facebook Social Recommendations</div>
	<div class='content'>
		<iframe src="http://www.facebook.com/plugins/recommendations.php?site=<?php echo WEBSITE;?>&amp;width=350&amp;height=300&amp;header=false&amp;colorscheme=light&amp;font=tahoma" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:350px; height:300px;" allowTransparency="true"></iframe>
	</div>
</div>

