<?php 
	if(!$_POST) {
		$_POST['title'] = $video[0]['title'];
		$_POST['description'] = $video[0]['description'];
	}

error(validation_errors());
echo form_open("video/edit/".$id);?>
<h2>Editing <?php echo $video[0]['title'];?></h2>
<label>Title</label>
	<input type='text' value="<?php echo set_value('title'); ?>" name='title'/><br/>
<label>Description</label>
	<textarea name='description' cols='30' rows='5'><?php echo set_value('description'); ?></textarea><br/>
	<label></label><input type='submit' value='Edit'/>
</form>