<h1>Reset your password</h1>
<p>Enter your new password below. Click the <span class="bold priority">"Update"</span> to change.</p>
<p><label for="new_password"></label>
<input type="password" name="new_password" id="new_password" style="width:150px;" value="" class="input-box required" /></p>
<p><input class="button" type="submit" value="Update" />&nbsp;
<input class="button" type="button" value="Back to Login" onclick="window.location='<?php store_info('login');?>';" /></p>
<input type="hidden" name="action" value="reset-password" />
<input type="hidden" name="userid" value="<?php echo $_REQUEST['userid'];?>" />
