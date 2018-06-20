<?php
if(!defined('IMPARENT')){exit();} // No direct access
enque_script('form-validation');
$meta = (object) $_REQUEST['meta'];

$captcha = captcha();
$failed = false;
if($_REQUEST['action'] == 'add' && validEmail(trim($_REQUEST['email_address']),false)){
	//if($captcha->status == 'SUCCESS'){
		$_REQUEST['user_name'] = strtolower(trim($_REQUEST['email_address']));
		$_REQUEST['display_name'] = trim($_REQUEST['meta']['first_name']);
		$_REQUEST['ip_address'] = get_ipaddress();
		$_REQUEST['backend_access'] = 'no';
		$_REQUEST['user_group'] = 'users';
		$_REQUEST['meta'] = serialize($_REQUEST['meta']);
		$user = add_user($_REQUEST);
		if($user->ID > 0){
			$code = $user->activation_code;			
			$body = file_get_contents(GBL_ROOT_CONTENT.'/mail/user/registration-activate.html');
			$body = eregi_replace("[\]",'',$body);
			$smarty = array(
				'site_name' => get_siteinfo('name',false),
				'site_url' => get_siteinfo('url',false),
				'activation_link' => store_info('activate',false).'?code='.$code,
				'activation_code' => $code,
				'email_address' => trim($_REQUEST['email_address']),
				'user_name' => trim($_REQUEST['user_name']),
			);
			foreach($smarty as $key => $value){$body = str_replace('{'.$key.'}',$value,$body);}

			setFrom(JS_EMAIL_FROM,JS_EMAIL_FROM_NAME);
			setTo(trim($_REQUEST['email_address']),trim($_REQUEST['user_name']));
			setCC(JS_EMAIL_CC, JS_EMAIL_CC_NAME);
			setSubject(JS_EMAIL_SUBJECT_REGISTRATION);
			setContent($body);
			
			if(__send_mail('smtp')){ // Send email now
				redirect(store_info('activate',false),'js');
			}else{
				redirect(store_info('activate',false).'?code='.$code,'js');
			}
		}else{
			$failed = true;
		}
	/*}else{
		$failed = true;
	}*/
}
get_header(); ?>
<script type="text/javascript">
$(document).ready(function(){
	$('.ajax').blur(function(){
		$this = $(this);
		if($this.val()!=''){
			$.ajax({
				type: 'POST',
				url: template_directory + '/ajax.php',
				data: 'action=check&type=' + $this.attr('id') + '&data=' + $this.val(),
				success: function(data){
					var obj = $.parseJSON(data);
					if(obj.status == 1){
						$this.addClass('error');
						$('#' + $this.attr('id') + '_ajax').html('Already exists, please select another one!');
					}else{
						$this.removeClass('error');
						$('#' + $this.attr('id') + '_ajax').html('');
					}
				}
			});	
		}
	}).focus(function(){
		$(this).removeClass('error');
		$('#' + $(this).attr('id') + '_ajax').html('');		
	});
});
</script>
<?php breadcrumb('Home','<span class="sep"></span>');?>
<div id="page" class="inner-wrapper">
	<div id="register">
    	<?php if($_REQUEST['action'] == 'success'):?>
    	<?php else:?>
            <h1>Ready to register with <?php get_siteinfo('name');?>?</h1>
            <h2>It's our typical registration - it's free and fairly simple to complete.</h2>
            <p>Already registered or want to make changes to your account? Click <a href="<?php store_info('login');?>">here</a></p>
            <?php if($failed):?>
            	<div class="msgbox" style="margin-top:10px;font-size:14px;color:red;">Registration failed, recheck and submit again.</div>
            <?php endif;?>            
            <form method="post" action="<?php store_info('register');?>" class="validate">
                <h3>Tell us about yourself</h3>
                <table cellpadding="0" cellspacing="0" border="0">                
                	<tr>
                    	<td colspan="2"><span style="color:red;font-size:16px;">*</span> Required fields.</td>
                    </tr>
                    <tr>
                        <td class="col1"><label for="first_name">First name<span class="required">*</span></label><input type="text" name="meta[first_name]" id="first_name" class="required" value="<?php echo $meta->first_name;?>" /></td>
                        <td class="col2"><label for="last_name">Last name<span class="required">*</span></label><input type="text" name="meta[last_name]" id="last_name" class="required" value="<?php echo $meta->last_name;?>" /></td>
                    </tr>
                    <tr>
                        <td class="col3" colspan="2"><label for="business_name">Business Name</label><input type="text" name="meta[business_name]" id="business_name" style="width:500px;" value="<?php echo $meta->business_name;?>" /></td>
                    </tr>
                    <tr>
                        <td class="col3" colspan="2"><label for="street1">Address</label><input type="text" name="meta[street1]" id="street1" style="width:500px;" value="<?php echo $meta->street1;?>" /></td>
                    </tr>
                    <tr>
                        <td class="col3" colspan="2"><input type="text" name="meta[street2]"style="width:500px;" value="<?php echo $meta->street2;?>" /></td>
                    </tr>
                    <tr>
                        <td class="col3" colspan="2" style="padding-top:5px;">
                            <div class="left" style="padding-right:50px;"><label for="city">City</label><input type="text" name="meta[city]" id="city" style="width:150px;" value="<?php echo $meta->city;?>" /></div>
                            <div class="left" style="padding-right:50px;"><label for="state">State</label>
                                <select name="meta[state]" id="state" style="width:200px;">
                                    <option value="">--State--</option>
                                    <?php							
                                    foreach(us_states::get() as $key => $value){
                                        if($key !='non-US'){
                                            echo '<option value="'.$key.'"'.($meta->state == $key ? ' selected="selected"' : '').'>'.$value.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="left"><label for="zip">ZIP / Postal code</label><input type="text" name="meta[zip]" id="zip" style="width:100px;" value="<?php echo $meta->zip;?>" /></div>                    	
                            <div class="clear"></div>
                        </td>
                    </tr>
                    <?php if(get_option('store_intl_ship') == 'yes'):?>
                    <tr>
                        <td class="col3" colspan="2"><label for="country">Country<span class="required">*</span></label>
                            <select name="meta[country]" id="city" style="width:350px;">
                                <option value="">--Country--</option>
                                <?php							
                                foreach(iso_3166::get() as $key => $value){
                                    echo '<option value="'.$key.'"'.($meta->country == $key ? ' selected="selected"' : '').'>'.$value.'</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <?php endif;?>
                    <tr>
                        <td class="col3" colspan="2">
                            <label>Date of birth<span class="required">*</span></label>
                            <div class="left" style="padding-right:10px;">
                                <select name="meta[dob_month]" style="width:100px;" class="required">
                                    <option value="">--Month--</option>
                                    <option value="01"<?php echo ($meta->dob_month == '01' ? ' selected="selected"' : '');?>>January</option>
                                    <option value="02"<?php echo ($meta->dob_month == '02' ? ' selected="selected"' : '');?>>February</option>
                                    <option value="03"<?php echo ($meta->dob_month == '03' ? ' selected="selected"' : '');?>>March</option>
                                    <option value="04"<?php echo ($meta->dob_month == '04' ? ' selected="selected"' : '');?>>April</option>
                                    <option value="05"<?php echo ($meta->dob_month == '05' ? ' selected="selected"' : '');?>>May</option>
                                    <option value="06"<?php echo ($meta->dob_month == '06' ? ' selected="selected"' : '');?>>June</option>
                                    <option value="07"<?php echo ($meta->dob_month == '07' ? ' selected="selected"' : '');?>>July</option>
                                    <option value="08"<?php echo ($meta->dob_month == '08' ? ' selected="selected"' : '');?>>August</option>
                                    <option value="09"<?php echo ($meta->dob_month == '09' ? ' selected="selected"' : '');?>>September</option>
                                    <option value="10"<?php echo ($meta->dob_month == '10' ? ' selected="selected"' : '');?>>October</option>
                                    <option value="11"<?php echo ($meta->dob_month == '11' ? ' selected="selected"' : '');?>>November</option>
                                    <option value="12"<?php echo ($meta->dob_month == '12' ? ' selected="selected"' : '');?>>December</option>
                                </select>
                            </div>
                            <div class="left" style="padding-right:10px;">
                                <select  name="meta[dob_day]" style="width:100px;" class="required">
                                    <option value="">--Day--</option>
                                    <?php
                                    for($i=1;$i <= 31;$i++){
                                        $day = ($i < 10 ? '0'.$i : $i);
                                        echo '<option value="'.$day.'"'.($meta->dob_day == $i ? ' selected="selected"' : '').'>'.$day.'</option>'."\n";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="left">
                                <select name="meta[dob_year]" style="width:100px;" class="required">
                                    <option value="">--Year--</option>
                                    <?php							
                                    for($i= (date('Y') - 100);$i <= (date('Y') - 17);$i++){
                                        echo '<option value="'.$i.'"'.($meta->dob_year == $i ? ' selected="selected"' : '').'>'.$i.'</option>'."\n";
                                    }
                                    ?>
                                </select> 
                            </div>                    	
                            <div class="clear info">
                                <p>You must be at least 18 years old to register.</p>
                            </div>                        
                        </td>
                    </tr>

                    <tr>
                        <td class="col3" colspan="2"><label for="primary_phone">Primary telephone number</label>
                            <div class="left" style="padding-right:10px;"><input type="text" name="meta[primary_phone]" id="primary_phone" style="width:150px;" value="<?php echo $meta->primary_phone;?>" /></div>
                            <div class="left"><span style="font-size:14px;">Ext.:</span> <input type="text" name="meta[phone_extension_no]" style="width:50px;" /></div>                    	
                            <div class="info clear">
                                <p>Example: 123-456-7890</p>
                                <p>Telephone is required in case there are questions about your account.</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="col3" colspan="2">
                            <div class="left" style="padding-right:50px;">
                                <label for="email_address">Email address<span class="required">*</span></label>
                                <input type="text" name="email_address" id="email_address" class="required email ajax" style="width:200px;" value="<?php echo $_REQUEST['email_address'];?>" />
								<div class="info" id="email_address_ajax" style="display:block;color:red;"></div>
                            </div>
                            <div class="left">
                                <label for="user_password">Create your password<span class="required">*</span></label><input type="password" name="user_password" id="user_password" maxlength="20" style="width:200px;" class="strength required" />
                                <div class="info">
                                    <p>User password is case-sensitive.</p>
                                </div>
                            </div>                    	
                            <div class="clear"></div>                        
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="info">
                                <p>Don't worry we wont sell your email to other company. You can always change your email preferences after registration.</p>
                            </div>
                        </td>
                    </tr>                
                </table>
                <!--h3>Are you a human?</h3-->
                <h3>Terms and Condition</h3>
                <table cellpadding="0" cellspacing="0" border="0">
                    <!--tr>
                        <td class="col3" colspan="2">
                            <p>For added security, please enter the code below to confirmed that you are a human and not a machine.</p>
                        </td>
                        
                    </tr-->
                    <tr>
                        <td class="col3" colspan="2">
                            <?php echo $captcha->display;?>
                        </td>
                       </tr>
                    <tr>
                        <td class="col3" colspan="2">
                            <p><label style="font-size:13px;"><input type="checkbox" name="agree" value="yes" class="checkbox" /> I accept the <a target="_blank" href="<?php the_permalink('terms-and-condition');?>">Terms and Condition</a> and <a target="_blank" href="<?php the_permalink('privacy-policy');?>">Privacy Policy</a>. And I'm at least 18 years old</label></p>
    
                        </td>
                    </tr>
                    <tr>
                        <td class="col3" colspan="2" style="padding-top:50px;"><input id="submit" type="submit" value="Continue" /></td>
                    </tr>
                </table>  
                <input type="hidden" name="captcha_verify" value="yes" />
                <input type="hidden" name="action" value="add" />
                <?php if($_REQUEST['debug'] == 'yes'):?>
                <input type="hidden" name="debug" value="yes" />
                <?php endif;?>
            </form>
		<?php endif;?>
	</div>    
</div>
<div id="quicklinks-2" class="inner-wrapper">
	<?php do_action('product_footer_ad_1');?>
    <div class="left">
        <a href="<?php get_siteinfo('url');?>"><img src="<?php get_siteinfo('template_directory');?>/images/logo.png" /></a>
    </div>
    <div class="right">
        <a href="<?php the_permalink('free');?>"><img src="<?php get_siteinfo('template_directory');?>/images/free-button.png" /></a>
        <a href="<?php the_permalink('stockdesigns');?>#vectorize"><img src="<?php get_siteinfo('template_directory');?>/images/vectorize-art.png" /></a>
        <a href="<?php the_permalink('stockdesigns');?>#stock-arts"><img src="<?php get_siteinfo('template_directory');?>/images/our-stock-art.png" /></a>
        <a href="<?php the_permalink('stockdesigns');?>#personalize"><img src="<?php get_siteinfo('template_directory');?>/images/your-design.png" /></a>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<?php get_footer(); ?>
