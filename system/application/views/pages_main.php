<?php 
	if(empty($pages)) echo "<center><b>There are not pages yet.</b></center>";
	else {
		echo "<table width='100%'>";
		echo "<tr><td><center><b>Page name</b></center></td><td><center><b>Actions</b></center></td></tr>";
		foreach($pages as $page) {
			echo "<tr class='highlight'><td><center><a href='".WEBSITE."/index.php/p/v/".$page."'>".ucfirst($page)."</a></center></td><td><center><a href='".WEBSITE."/index.php/admin/editPage/".$page."' class='tipsy' title='Edit page'><img src='".WEBSITE."/public/images/edit.gif'/></a> <a href='#' onClick=\"if(confirm('Are you sure you want to remove this page?')) window.location.href='".WEBSITE."/index.php/admin/deletePage/".$page."';\" class='tipsy' title='Delete Page'><img src='".WEBSITE."/public/images/false.gif'/></a></center></td></tr>";
		}
		echo "</table>";;
	}
?>