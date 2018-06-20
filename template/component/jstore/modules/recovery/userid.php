<?php if($_REQUEST['action'] == 'success'):?>
    <h1>Recovery Successful</h1>
    <p>Please check your email for the recovered details.</p>
    <p><input class="button" type="button" value="Back to Login" onclick="window.location='<?php store_info('login');?>';" /></p>
<?php else:?>
    <h1>Forgot your User ID?</h1>
    <p>Enter your email address below. Click the <span class="bold priority" style="color:#333333;">Continue</span> to received your user ID information from your email.</p>
    <p><label for="email">Enter email address:</label><input type="text" name="email" id="email" style="width:300px;" class="input-box email required" /></p>
    <p>
        <input class="button" type="submit" value="Continue" />&nbsp;
        <input class="button" type="button" value="Back to Login" onclick="window.location='<?php store_info('login');?>';" />
    </p>
    <input type="hidden" name="recover" value="userid" />
<?php endif;?>