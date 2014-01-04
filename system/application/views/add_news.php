<?php 
echo '<script type="text/javascript" src="'.WEBSITE.'/public/js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>';
echo '<script type="text/javascript" src="'.WEBSITE.'/public/js/tinyMCE.js"></script>';
echo error(validation_errors());
echo form_open("admin/add_news");

echo "<label>Title</label> <input name='title' type='text' size='64' value='".set_value('title')."' maxlenght='64'><br />";

echo "<textarea name='body' class='tinymce'>".set_value('body')."</textarea><br />";
echo "<input type='submit' value='Add news'>";
echo "</form>";

?>