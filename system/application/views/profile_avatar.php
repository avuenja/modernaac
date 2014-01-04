<div class='message'>
	<div class='title'>Update Profile Avatar</div>
	<div class='content'>
		<?php 
			@error($error);
			error(validation_errors());
			echo form_open_multipart('profile/avatar'); ?>
			<label>Avatar</label>
					<input type="file" name="userfile" size="20" />
					<br/>
					<label>&nbsp;</label><input value='off' type='checkbox' name='avatar'/> Delete avatar<br/>
					<label></label><input type='submit' value='Update'/>
			</form>
	</div>
	<div class='bar'>Make sure the avatar is appropriate!</div>
</div>

<?php 

	if(!empty($profile[0]['avatar']))
		echo "Current Avatar: <br/> <img src='".WEBSITE."/public/uploads/avatars/".$profile[0]['avatar']."'/>";
?>