<?php 
echo "<a href='".WEBSITE."/index.php/admin/create_board'>Create new board</a>";

if(count($boards) == 0)
	alert("There is no boards yet, click link above to create one.");
else {
	echo "<table width='100%'>";
	echo "<tr><td width='75%'><center><b>Forum Name</b></center></td><td><center><b>Edit</b></center></td><td><center><b>Delete</b></center></td></tr>";
	foreach($boards as $board) {
		echo "<tr class='highlight'><td width='75%'><center>".$board['name']."</center></td><td><center><a href='".WEBSITE."/index.php/admin/edit_board/".$board['id']."'><img src='".WEBSITE."/public/images/edit.gif'></a></center></td><td><center><a href='#' onClick=\"if(confirm('Are you sure you want to delete this board?')) window.location.href='".WEBSITE."/index.php/admin/delete_board/".$board['id']."';\"><img src='".WEBSITE."/public/images/false.gif'></a></center></td></tr>";
	}
	echo "</table>";
}
?>