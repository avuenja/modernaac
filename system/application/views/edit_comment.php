<?php 
error(validation_errors());
echo form_open("account/editcomment/".$id);
if(empty($_POST['comment'])) $_POST['comment'] = $comment[0]['comment'];
echo "<b>Editing ".$id." comment.";
	echo "<textarea name='comment'>".$_POST['comment']."</textarea><br/>";
		if($comment[0]['hide_char'] == 1)
			echo "<label>Hide Character</label><input checked type='checkbox' name='hide' value='1'/><br/><br/>";
		else
			echo "<label>Hide Character</label><input type='checkbox' name='hide' value='1'/><br/><br/>";
	echo "<input type='submit' value='Edit'/>";
echo "</form>";

?>