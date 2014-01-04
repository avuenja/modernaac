<?php echo error(validation_errors()); ?>
<?php echo form_open("account/changepassword");?>
<label>Current password</label><input type='password' name='current'/><br/>
<label>New password</label><input type='password' name='password'/><br/>
<label>Repeat</label><input type='password' name='repeat'/><br/>
<input type='submit' value='Change'/>
</form>