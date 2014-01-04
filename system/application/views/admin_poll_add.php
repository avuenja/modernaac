<?php 
echo error(validation_errors());
echo form_open("admin/poll_add");
echo "<label for='question'>Question</label> <input id='question' name='question' type='text' size='64' value='".set_value('question')."' maxlength='64'><br />";
echo "<label for='date_start'>Date Start</label> <input id='date_start' readonly class='timepicker' name='date_start' type='text' size='20' value='".set_value('date_start')."' maxlength='19'><br />";
echo "<label for='date_end'>Date End</label> <input id='date_end' readonly class='timepicker' name='date_end' type='text' size='20' value='".set_value('date_end')."' maxlength='19'><br />";
echo "<label>Status</label> <input id='status'  name='status' type='radio' value='1' checked> Enable <input id='status'  name='status' type='radio' value='0'> Disable<br />";
echo "<input type='submit' value='Create Poll'>";
echo "</form>";
?>