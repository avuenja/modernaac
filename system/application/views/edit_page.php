<?php 
	echo "<b>Link to this page:</b><br /><a href='".WEBSITE."/index.php/p/v/".$page."'>".WEBSITE."/index.php/p/v/".$page."</a><br /><br />";
	echo form_open("admin/editPage/".$page);
	echo "<textarea name='content' style='width: 99%; height: 400px;'>";
		echo $content;
	echo "</textarea>";
	echo "<input type='submit' value='Edit Page'/>";
	echo "</form>";
?>