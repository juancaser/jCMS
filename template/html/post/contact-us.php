<?php if(!defined('IMPARENT')){exit();} // No direct access 
enque_script('form-validation');
$custom = unserialize($page->meta);
$message = '';
$ms = '';
if($_REQUEST['action'] == 'contact-us'){
	$ok = false;
	if($_REQUEST['contact_name']!='' && $_REQUEST['email_address']!='' && $_REQUEST['message']!=''){

		$body = file_get_contents(GBL_ROOT_CONTENT.'/mail/contact-us/'.($_REQUEST['subject'] == '2' ? 'order' : 'general').'.html');
		$body = eregi_replace("[\]",'',$body);
		$smarty = array(
				'site_name' => get_siteinfo('name',false),
				'site_url' => get_siteinfo('url',false),
				'email_address' => $user->email_address,
				'message' => $_REQUEST['message'],
				'contact_name' => $_REQUEST['contact_name']
			);
		foreach($smarty as $key => $value){$body = str_replace('{'.$key.'}',$value,$body);}
		//setContent($body);
		
		$to  = trim($_REQUEST['contact_name']).' <'.trim($_REQUEST['email_address']).'>';
		$subject = JS_STORE_NAME.' - '.($_REQUEST['subject'] == '2' ? 'Where\'s my Order?' : 'You have a message!');
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'To: '.JS_STORE_CONTACT_EMAIL_NAME.' <'.JS_STORE_CONTACT_EMAIL.'>'."\r\n";
		$headers .= 'From: '.trim($_REQUEST['contact_name']).' <'.trim($_REQUEST['email_address']).'>' . "\r\n";
		if(mail($to, $subject, $body, $headers)){ // Send email now
			$message = JS_CONTACT_MESSAGE;
			$ok = true;
		}else{
			$message = 'Error occured while sending your message. Please try again.';			
		}
	}else{		
		$message = 'Required field empty. Please try again.';			
	}
}


?>
<?php get_header(); ?>
<?php breadcrumb('Home','<span class="sep"></span>');?>
<div id="page" class="inner-wrapper">
	<?php if($ok):?>
    <h3 style="padding-bottom:10px;font-size:18px;font-weight:normal;"><?php echo $message;?></h3>
    <p><input type="button" value="Back to Homepage" onclick="window.location='<?php get_siteinfo('url');?>';" /></p>
	<?php else:?>
        <div id="contactus">
            <div id="address" class="left">
                <h4>Phone</h4>
                <p style="padding-bottom:10px;"><?php echo get_option('store_phone');?></p>
                <h4>Email</h4>
                <p style="padding-bottom:10px;"><?php echo get_option('store_email');?></p>
                <h4>Mailing Address</h4>
                <address><?php echo get_option('store_address');?></address>
            </div>
            <div id="form" class="left">
                <?php if(have_posts()): the_post(); ?>
                    <div <?php post_class();?>>
                        <h1><?php the_title();?></h1>
                        <div class="content">                            
							<?php the_content();?>
                            <form action="<?php the_permalink();?>" method="post" class="validate">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td class="col1"><label for="contact_name">Contact Name</label></td>
                                        <td class="col2"><input type="text" name="contact_name" id="contact_name" class="required" /></td>
                                    </tr>
                                    <tr>
                                        <td class="col1"><label for="email_address">Email Address</label></td>
                                        <td class="col2"><input type="text" name="email_address" id="email_address" /></td>
                                    </tr>
                                    <tr>
                                        <td class="col1"><label for="email_subject">Subject</label></td>
                                        <td class="col2">
                                            <select id="email_subject" name="subject" style="width:100px;padding:3px 1px 3px 3px;">
                                                <option value="1"<?php echo ($_REQUEST['subject'] == 'general' ? ' selected="selected"':'')?>>General</option>
                                                <option value="2"<?php echo ($_REQUEST['subject'] == 'order' ? ' selected="selected"':'')?>>Order</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col1"><label for="message">Message</label></td>
                                        <td class="col2"><textarea name="message" id="message" class="required"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td class="col1"></td>
                                        <td class="col2"><input type="submit" value="Submit" />&nbsp;<input type="reset" value="Clear" /></td>
                                    </tr>
                                </table>
                                <input type="hidden" name="action" value="contact-us" />
                            </form>
                        </div>
                    </div>
                <?php endif;?>
            </div>
            <div class="clear"></div>
		<?php endif;?>
    </div>
</div>
<?php get_footer(); ?>