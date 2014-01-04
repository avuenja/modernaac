<?php include("public/js/keyboard.php");?>
<link rel="stylesheet" type="text/css" href="<?php echo WEBSITE; ?>/public/css/keyboard.css">
<?php 
	error(validation_errors());
	echo "<div class='message'>";
	echo "<div class='title'>Login</div>";
	echo "<div class='content'>";
	echo "<img style='float: left;' src='".WEBSITE."/public/images/login.png'/>";
	echo form_open('account/login');	
	?>
	<label for="name">Account Name</label><input type="password" value="<?php echo set_value('name'); ?>" class="keyboardInput" name="name"/><br>
	<label for="name">Password</label><input  type="password"" value="<?php echo set_value('pass'); ?>" class="keyboardInput" name="pass"/><br>
	<label>&nbsp;</label><input class='sub' type="submit" value="Login"/>
	<?php 
	echo "</form><br/>";
	echo "</div>";
	echo "<div class='bar'>Dont have an account yet? Click <a href='".WEBSITE."/index.php/account/create'><b>here</b></a> to register!</div>";
	echo "</div>";
	echo "<div class='messageAdd'><b>Account lost? Click <a href='".WEBSITE."/index.php/account/lost'>here</a>!</b></div>";

?>