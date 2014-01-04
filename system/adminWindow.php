<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script>
	function sendRequest() {
		$('.loader').show();
		var form = $('#request').serialize();
		$('input[name=input]').val('');
		$.ajax({
			url: '<?php echo WEBSITE; ?>/index.php/ajax_cs/adminRequest',
			  type: 'post',
			  data: form,
			  success: function(data) {
			  	$('#adminWindow .content').prepend(data);
			  	$('.loader').hide();
			  }
		});
	}
</script>
<style>
	#window {
		width: 700px;
		height: 200px;
		border: 3px solid white;
		margin: 0px auto;	
		background-color: black;	
		color: white;
		font-family: system;
		font-size: 13px;
		overflow: auto;
	}
	
	#window .title {
		border-bottom: 1px solid white;
		padding: 4px;
		font-weight: bold;
	}
	
	#window .content {
		text-align: left;
		background-color: #fff;

	}
	
	#input {
		width: 700px;
		height: 30px;
		margin: 0px auto;
		text-align: left;
	}
	
	#adminWindow {
		width: 700px;
		margin: 0px auto;	
	}
</style>
<div class='message' id='adminWindow'>
<div class='title'>Admin Window</div>
<div class='content' style='height: 150px; overflow: auto; text-align: left; background: #fff;'>
	<?php 
			foreach($_SESSION['actions'] as $action) {
				echo date("H:i:s", $action['time'])." > ".$action['action']."<br/>";
			}
	?>
</div>
<div id='input'>
	<form id='request' onSubmit='sendRequest(); return false;'>
	<input type='text' style='width: 630px; height: 27px; background: none; border: none; border-top: 1px solid silver;' name='input'/>
	<input type='submit' value='Send'/>
	</form>
</div>
<div class='bar'><?php echo loader(); ?></div>
</div>
