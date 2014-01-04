<?php
echo form_open("character/view");
?>
<div class='message'>
<div class='title'>Character lookup</div>
<div class='content'>
<label>Character name</label> <input name='name' id='search' type='text'> 
<input type='submit' value='Search'>
</form>
</div>
</div>
<br/>
<?php 
	if(!empty($_SESSION['sCharacters'])) {
		echo "<div class='message'><div class='title'>History</div><div id='history' class='content'>";
			foreach($_SESSION['sCharacters'] as $character) {
				echo "<b><a href=\"".url('character/view/'.$character['name'])."\">".ucwords($character['name'])."</a></b> - ".ago($character['time'])."<br/>";
			}
		?>
			<a href='#' onClick='$("#history").load("<?php echo url('character/clearHistory');?>");'>Clear History</a>
		<?php 
		echo "</div></div>";
	}
?>