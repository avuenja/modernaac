<?php 
	alert("Our system has been upgraded to newest version of Modern AAC with this upgrade great functionality comes as well. We need you to set your unique nickname in order to use new community modules. It can be anything, might be your nick ingame, people will recognize you by this.");
	error(validation_errors());
	echo form_open("account/setNickname");
?>
	<div class='message'>
	<div class='title'>Change my community nickname</div>
	<div class='content'>
	<b>This nickname will be unchangable!</b><br/><br/>
	<label>Nickname</label>
		<input type='text' name='nickname'/><br/>
	<input type='checkbox' name='rules'/> I do know that this nickname will be seen by anyone, and it is not similar to my account name or account password and it doesn't break any standard rules and no offense anyone.
	<br/><br/>
	<input type='submit' value='Save'/>
	</form>
	</div>
	</div>