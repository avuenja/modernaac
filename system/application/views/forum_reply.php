<?php 
echo "<h1>Replying to ".$thread[0]['name']."</h1>";
if(count($characters) == 0)
	error("You need to create a character in order to post.");
else {
	if(empty($_POST['title'])) $_POST['title'] = "Re: ".$thread[0]['name'];
echo '<script type="text/javascript" src="'.WEBSITE.'/public/js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>';
echo '<script type="text/javascript" src="'.WEBSITE.'/public/js/tinyMCE.js"></script>';
echo error(validation_errors());
echo form_open("forum/reply/".$id);
	echo "<label>Character</label> <select name='character'>";
		foreach($characters as $value) {
			echo "<option value=".$value['id'].">".$value['name']."</option>";
		}
	echo "</select>";
	echo "<br /><br /><label>Title</label> <input value='".@$_POST['title']."' type='text' size='42' name='title' maxlenght='64'><br />";
	echo "<textarea name='post' class='tinymce'>".@$_POST['post']."</textarea><br />";
	echo "<input name='submit' type='submit' value='Reply'>";
echo "</form>";
}
?>