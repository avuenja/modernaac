<?php 
echo "<div class='toolbar'>";
echo "<a href='".WEBSITE."/index.php/admin/poll_add'>Create a new Poll</a>";
echo "</div>";
if(count($news) == 0) 
	alert("There is no news yet.");
else {
	echo "<table width='100%'>";
	echo "<tr><td><center><b>ID</b></center></td><td><center><b>Title</b></center></td><td><center><b>Created</b></center></td><td><center><b>Edit</b></center></td><td><center><b>Edit</b></center></td></tr>";
	foreach($news as $value) {
		echo "<tr class='highlight'><td><center>".$value['id']."</center></td><td><center>".$value['title']."</center></td><td><center>".UNIX_TimeStamp($value['time'])."</center></td><td><center><a href='".WEBSITE."/index.php/admin/edit_news/".$value['id']."'><img src='".WEBSITE."/public/images/edit.gif'></a></center></td><td><center><a href='#' onClick=\"if(confirm('Are you sure you want to remove ".$value['title']." news?')) window.location.href='".WEBSITE."/index.php/admin/delete_news/".$value['id']."';\"><img src='".WEBSITE."/public/images/false.gif'></a></center></td></tr>";
	}
	echo "</table>";
}

echo "<center>".$pages."</center>";
?>