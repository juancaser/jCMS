<?php if($_REQUEST['action']=='reset'):?>
	<h1>Reset you Password</h1>
    <p>Type your new Password. Click <span class="bold priority" style="color:#333333;">Continue</span> to update.</p>
    <?php _d($recover_message,'<div style="color: red; font-size: 13px; padding-bottom: 10px;">','</div>');?>
    <p><label for="activation_code">Activation Code:</label>
    <input type="text" name="activation_code" id="activation_code" style="width:300px;" value="<?php echo $_REQUEST['code'];?>" class="input-box required required" /><br/>
    <p><label for="password">New Password:</label><input type="password" name="password" id="password" style="width:150px;" value="" class="input-box email required" /></p>
    <p><input class="button" type="submit" value="Continue" />&nbsp;<input type="button" class="button" value="Back to Login" onclick="window.location='<?php store_info('login');?>';" /></p>
    <input type="hidden" name="action" value="password_reset_verified" />
<?php else:?>
    <h1>Confirm your identity to reset password</h1>
    <p style="padding-bottom:5px;">Enter your email address below. Click the <span class="bold priority" style="color:#333333;">Continue</span> to received password reset link from your email.</p>
   	<?php _d($recover_message,'<div style="color: red; font-size: 13px; padding-bottom: 10px;">','</div>');?>
    <p style="padding-top:5px;"><label for="email">Enter email address:</label><input type="text" name="email" id="email" style="width:300px;" value="<?php echo $_REQUEST['email'];?>" class="input-box email required" /></p>
    <p><input class="button" type="submit" value="Continue" />&nbsp;<input type="button" class="button" value="Back to Login" onclick="window.location='<?php store_info('login');?>';" /></p>
    <input type="hidden" name="action" value="password_reset_verify" />
<?php endif;?>