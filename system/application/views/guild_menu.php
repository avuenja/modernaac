<div class="toolbar">
	<a href='<?php echo WEBSITE;?>/index.php/guilds/invite/<?php echo $id; ?>'>Invite Player</a> | 
	<a href='<?php echo WEBSITE;?>/index.php/guilds/members/<?php echo $id; ?>'>Manage Members</a> | 
	<a href='<?php echo WEBSITE;?>/index.php/guilds/motd/<?php echo $id; ?>'>Change MOTD</a> | 
	<a href='<?php echo WEBSITE;?>/index.php/guilds/logo/<?php echo $id; ?>'>Change Logo</a> | 
	<a href='#' onClick="if(confirm('Are you sure you want to delete the guild? You cannot restore this guild after deletion.'))window.location.href='<?php echo WEBSITE?>/index.php/guilds/delete/<?php echo $id;?>';">Delete guild</a> | 
</div>