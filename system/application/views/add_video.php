<?php 
	echo "<center><b>This interface allows you to add videos. Please type <a href='http://youtube.com'>YouTube</a> link.</b></center><br/>";
	error(validation_errors());
	echo form_open("video/add");
		echo "<label>Character</label>";
		echo "<select name='character'>";
			foreach($characters as $character) {
				echo "<option value='".$character['id']."'>".$character['name']."</option>";
			}
		echo "</select><br/><br/>";
		
		echo "<label>Title</label>";
		echo "<input type='text' size='42' name='title' value='".set_value('title')."' maxlength='64'/><br/>";
		echo "<label>Description</label>";
		echo "<textarea name='description'>".set_value('description')."</textarea><br/>";
		echo "<label>YouTube Link</label>";
		echo "<input type='text' value='".set_value('link')."' name='link' size='42'/><br/>";
		echo "<input type='submit' value='Add Video'/>";
	echo "</form>";

?>