<?php 
        alert("This interface allows you to change your account password. You need to provide email which has been used when registering account and recovery-key if you don't have it please contact the administration of this server.");
        error(validation_errors());
        echo form_open("account/lost");
?>
        <div class='message'>
        <div class='title'>Lost account interface</div>
        <div class='content'>
        <label>E-mail</label>
                <input type='text' name='email'/><br/>
        <label>Recovery Key</label>
                <input type='text' name='key'/><br/>
        <label>New password</label>
                <input type='password' name='password'/><br/>
        <label>Repeat</label>
                <input type='password' name='repeat'/><br/>
        <label>&nbsp;</label>
        <input type='submit' value='Change Password'/>
        </div>
        </div>
        </form>