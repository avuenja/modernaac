<?php 
echo "<div class='errors'>";
echo error(validation_errors());
echo "</div>";
echo form_open('character/create_character', array('id'=>'createCharacter')); 
?>
<script>
	function createCharacter() {
		$('.loader').show();
		var form = $('#createCharacter').serialize();
		$.ajax({
			url: '<?php echo WEBSITE; ?>/index.php/character/create_character/1',
			  type: 'post',
			  data: form,
			  success: function(data) {
			  	$('.errors').html(data);
			  	$('.loader').hide();
			  }
		});
	}
</script>
<div class='message'>
<div class='title'>Create new Character</div>
<div class='content'>
<label>Character name</label><input type='text' value="<?php echo set_value('name'); ?>" name='name'><br><br>
<label>City</label><select name='city'>
<?php 
	foreach($cities as $key=>$value) {
		echo '<option value="'.$key.'">'.$value.'</option>';
	}
?>
</select><br><br>
<label>Sex</label><select name='sex'>
	<option value="1">Male</option>
	<option value="0">Female</option>
</select><br><br>
<label>Vocation</label><select name='vocation'>
<?php 
	foreach($vocations as $key=>$value) {
		echo '<option value="'.$key.'">'.$value.'</option>';
	}
?>
</select><br><br>
<label>World</label><select name='world'>
<?php 
	foreach($worlds as $key=>$value) {
		echo '<option value="'.$key.'">'.$value.'</option>';
	}
?>
</select><br><br/>
<label>&nbsp;</label>
<input type='submit' value='Create' name='submit' class='sub'/> <?php echo loader();?>
</div></div>
</form>
