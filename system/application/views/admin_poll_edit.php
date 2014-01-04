<?php 
echo error(validation_errors());
echo form_open("admin/poll_edit/".$poll['id']);
echo "<fieldset><legend>Poll</legend>";
echo '<input type="hidden" value="'.$poll['id'].'" name="id" />';
echo "<label for='question'>Question</label> <input id='question' name='question' type='text' size='64' value='".$poll['question']."' maxlength='64'><br />";
echo "<label for='date_start'>Date Start</label> <input id='date_start' readonly class='timepicker' name='date_start' type='text' size='20' value='".$poll['date_start']."' maxlength='19'><br />";
echo "<label for='date_end'>Date End</label> <input id='date_end' readonly class='timepicker' name='date_end' type='text' size='20' value='".$poll['date_end']."' maxlength='19'><br />";
echo "<label>Status</label> <input id='status'  name='status' type='radio' value='1' ". ($poll['status'] ? 'checked' : '') ."> Enable <input id='status'  name='status' type='radio' value='0' ". ($poll['status'] ? '' : 'checked' )."> Disable<br /><br />";
echo "<label>Options</label><br><Br>";
$i=0;
if(sizeof($poll['answers']) > 0) {
foreach($poll['answers'] as $k => $v)
	echo ++$i.". <input id='answer' name='answers[".$k."]' type='text' size='30' value='".$v."'><br />";
echo "<br><input type='submit' value='Edit Poll'>";
}else{
	echo "The is no options yet.";
}
echo "</form>";

echo form_open("admin/poll_edit/".$poll['id']);
echo "<fieldset><legend>New Option</legend>";
echo '<input type="hidden" value="'.$poll['id'].'" name="id" />';
echo "<input id='answer' name='answer' type='text' size='30' value=''><input type='submit' value='New Option'>";
echo "</fieldset>";
echo "</form>";
?>