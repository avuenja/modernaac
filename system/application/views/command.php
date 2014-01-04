
<style>
#command {
	width: 95%;
	height: 300px;
	background-color: black;
	font-family: system;
	color: white;
	font-size: 14px;
	padding: 10px;
	border: 3px groove silver;
	overflow: auto;
}

#status {
	font-size: 12px;
}
</style>
<?php alert("This feature gives you access to command promp of your server. Only people with IP which is set in config.php are allowed to use this as this gives you full control over your machine. (If privilages are set). You will not see the output of the console till the process is finished.");?>
<script>
	function sendCMD() {
		var cmd = $('#cmd').val();
		if(cmd != "") {
			$('#execution').fadeIn();
			$('#cmd').attr('disabled', true);
			$('#send').attr('disabled', true);
			$.ajax({
				  url: '<?php echo WEBSITE;?>/index.php/admin/execute',
				  data: 'cmd='+cmd,
				  type: 'post',
				  success: function(data) {
					$('#cmd').removeAttr('disabled');
					$('#send').removeAttr('disabled');
				  	$("#status").html("Last command run: <b>"+cmd+"</b>");
				  	$("#cmd").val("");
				    $('#command').prepend(data);
				    $('#execution').fadeOut();
				  }
			});
		}
	}
</script>
<div id="status"></div>
<div id="command">
	<?php echo getContent("system/systems/command_prompt.php");?>
</div>
<form method='post' action='<?php echo WEBSITE;?>/index.php/admin/command' onSubmit='sendCMD(); return false;'>
<b>|/></b> <input type='text'  id='cmd' style='width: 85%; display: inline;'/>
<input type='submit' id='send' value='Send'/>
</form>
<div id='execution' style='display: none;'><img src='<?php echo WEBSITE;?>/public/images/spinner.gif' /> &nbsp; &nbsp; <b>Please wait... Your command is being executed.</b></div>
