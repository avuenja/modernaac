<?php 
echo '<script type="text/javascript" src="'.WEBSITE.'/public/js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>';
echo '<script type="text/javascript" src="'.WEBSITE.'/public/js/tinyMCE.js"></script>';
echo error(validation_errors());
echo form_open("admin/edit_news/".$id);

echo "<label>Title</label> <input name='title' type='text' size='64' value='".$news[0]['title']."' maxlenght='64'><br />";

echo "<textarea name='body' class='tinymce'>".$news[0]['body']."</textarea><br />";
echo "<input type='submit' value='Edit news'>";
echo "</form>";

?>