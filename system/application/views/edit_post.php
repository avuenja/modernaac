<?php 
if(empty($_POST['title'])) @$_POST['title'] = $post[0]['title'];
if(empty($_POST['post'])) $_POST['post'] = $post[0]['text'];
echo "<h1>Editing post in ".$thread[0]['name']."</h1>";
echo '<script type="text/javascript" src="'.WEBSITE.'/public/js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>';
echo '<script type="text/javascript" src="'.WEBSITE.'/public/js/tinyMCE.js"></script>';
echo error(validation_errors());
echo form_open("forum/edit/".$id);
	echo "<label>Title</label> <input value='".@$_POST['title']."' type='text' size='42' name='title' maxlenght='64'><br />";
	echo "<textarea name='post' class='tinymce'>".@$_POST['post']."</textarea><br />";
	echo "<input name='submit' type='submit' value='Edit'>";
echo "</form>";
?>