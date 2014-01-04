<?php 
$logo = (file_exists("public/guild_logos/".$id.".gif")) ? "<img src='".WEBSITE."/public/guild_logos/".$id.".gif' width='64' height='64'>" : "<img src='".WEBSITE."/public/guild_logos/default.gif'>";
@error($error);
echo "<label>Current logo</label>".$logo;
echo form_open_multipart('guilds/logo/'.$id);
echo '<input type="file" name="logo" max_file_size="131072" />';
echo '<input type="submit" value="Upload" />';
echo "</form>";;
?>