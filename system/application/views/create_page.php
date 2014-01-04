<?php 
	error(validation_errors());
	echo form_open("admin/createPage");
		echo "<label>Page name</label>";
		echo "<input type='text' value='".set_value('name')."' name='name'/>";
		echo " <input type='submit' value='Create'/>";
	echo "</form>";;
?>