<?php 
echo error(validation_errors());
echo form_open("admin/create_board");
	echo "<label>Name</label><input type='text' maxlenght='64' name='name' value='".@$_POST['name']."'><br />";
	echo "<label>Description</label>";
	echo "<textarea name='description'>".@$_POST['description']."</textarea>";
	echo "<i>Minimum access to see and use the board.</i><br /><label>Access</label>";
	echo "<input type='text' name='access' value='".@$_POST['access']."'><br />";
	echo "<label>Closed</label>";
	echo "<select name='closed'><option ".set_select('closed', 0, TRUE)." value='0'>No</option><option ".set_select('closed', 1)." value='1'>Yes</option></select>";
	
	echo "<br /><i>Specify if user must be logged in to see the board.</i><br /><label>Login required</label><select name='login'><option ".set_select('login', 0, TRUE)." value='0'>No</option><option ".set_select('login', 1)." value='1'>Yes</option></select><br />";
	echo "<i>Moderators, divide them by comma \",\" example: Paxton,Teragon,Mafiozo<br />";
	echo "<label>Moderators</label><input type='text' name='moderators' value='".@$_POST['moderators']."'><br />";
	echo "<i>Number of position in the listing, the lower it is the higher to board will be.</i><br />";
	echo "<label>Order</label><input type='text' name='order' value='".@$_POST['order']."'><br />";
	echo "<input type='submit' value='Create'>";
	
echo "</form>";
?>