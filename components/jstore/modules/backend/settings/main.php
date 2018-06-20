<?php
if(!defined('IMPARENT')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access
enque_script('jquery-ui');
enque_style('jquery-ui-theme');
enque_script('uploadify');
enque_script('swfobject');
enque_style('uploadify-css');

$title = 'Store Settings';
?>
<style>
	#welcome_ss { list-style-type: none; margin: 0; padding: 0; width: 60%; }
	#welcome_ss li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
	#welcome_ss li span { position: absolute; margin-left: -1.3em; }
	.ui-helper-reset{
		line-height:1.4;
	}
</style>

<script type="text/javascript">
$(document).ready(function(){
	$('#tabs').tabs();
	$('#layout').accordion();
	
	$('#add_featured_page').click(function(){
		$('#hp_content').find('ul').append('<li>test</li>');
	});
	
	$('#slide_show_image').uploadify({
		'uploader' : '<?php get_siteinfo('url');?>/core/js/uploadify/uploadify.swf',
		'script' : '<?php get_siteinfo('url');?>/core/js/uploadify/uploadify.php',
		'cancelImg' : '<?php get_siteinfo('url');?>/core/js/uploadify/cancel.png',
		'multi' : false,
		'auto' : true,
		'fileExt' : '*.jpg;*.gif;*.png',
		'fileDesc' : 'Image Files (*.jpg, *.gif, *.png)',
		'removeCompleted' : true,
		'folder' : '/content/uploads/<?php echo date('Y');?>/<?php echo date('m');?>',
		'queueID' : 'uploading',
		'buttonText': 'Browse',
		'onComplete':function(event, queueID, fileObj, response){
			$('#hp_content').find('ul').append('<li>test</li>');
		}
	});

	$('#welcome_ss').sortable();
	$('#welcome_ss').disableSelection();
});
</script>
<form id="item-viewer" method="post" action="<?php echo BACKEND_DIRECTORY;?>/components.php"> 

    <table class="viewer" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td class="sidebar">
                <div class="box">
                    <div class="title">Settings</div>
                    <div class="content">
	                    <p style="margin-bottom:5px;"><input type="button" class="button" value="Save Changes" onclick="document.getElementById('item-viewer').submit();" /></p>
	                    <p><input class="button" type="button" value="Back" onclick="window.location='<?php echo BACKEND_DIRECTORY;?>/components.php?comp=<?php echo JSTORE_ID;?>';" /></p>
                    </div>
                </div>                
            </td>
        	<td class="content">
            	<!--h2><?php //echo $title;?></h2-->
                <?php load_documentations(); ?>
               <?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>
                <div id="tabs" class="tabs">                
                    <ul>
                        <li><a href="#tabs1">General</a></li>
                        <li><a href="#tabs2">Layout</a></li>
                        <li><a href="#tabs3">Shopping Cart</a></li>
                        <li><a href="#tabs4">Contact Information</a></li>
                    </ul>
                    <div id="tabs1">
                        <table class="system-settings" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td class="col1"><label>User Registration</label></td>
                                <td class="col2">
                                    <label style="display:inline;"><input type="radio" name="store_user_registration" value="on"<?php echo (get_option('store_user_registration') == 'on' ? ' checked="checked"' : '');?> /> On</label>
                                    <label style="display:inline;"><input type="radio" name="store_user_registration" value="off"<?php echo (get_option('store_user_registration') == 'off' ? ' checked="checked"' : '');?> /> Off</label>
                                    <div  style="width:500px;" class="info">Any user registered before the change can still login. Only new user affected.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="col1"><label>Price Visibility</label></td>
                                <td class="col2">
                                    <label><input type="radio" name="store_price_visibility" value="all"<?php echo (get_option('store_price_visibility') == 'all' ? ' checked="checked"' : '');?> /> Visible to all, login not-required</label>
                                    <label><input type="radio" name="store_price_visibility" value="login"<?php echo (get_option('store_price_visibility') == 'login' ? ' checked="checked"' : '');?> /> Requires user to login to view</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col1"><label><input type="checkbox" name="comment" value="yes"<?php echo (get_option('comment') == 'yes' ? ' checked="checked"' : '');?> /> Comment</label></td>
                                <td class="col2">
                                	<?php if(get_option('comment') == 'yes'):?>
                                        <strong>Posting</strong><br />
                                        <label><input type="radio" name="comment_requires_login" value="yes"<?php echo (get_option('comment_requires_login') == 'yes' ? ' checked="checked"' : '');?> /> Requires user to login</label>
                                        <label><input type="radio" name="comment_requires_login" value="no"<?php echo (get_option('comment_requires_login') == 'no' ? ' checked="checked"' : '');?> /> Anyone can posts comment</label>
                                        <div  style="width:500px;" class="info">May still require user to input some personal information.</div>
                                        
                                        <strong>Moderation</strong><br />
                                        <label><input type="checkbox" name="comment_moderation" value="yes"<?php echo (get_option('comment_moderation') == 'yes' ? ' checked="checked"' : '');?> /> Posted comment requires administrator approval.</label>
                                        <div  style="width:500px;" class="info">If checked, all comments will not appear on the page until the administrator approved it.</div>
                                    <?php else:?>
                                        <input type="hidden" name="comment_requires_login" value="<?php echo get_option('comment_requires_login');?>" />
                                        <input type="hidden" name="comment_moderation" value="<?php echo get_option('comment_moderation');?>" />
                                        <div  style="width:500px;" class="info">Comment disabled.</div>
                                    <?php endif;?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="tabs2">
                    
                    	<div class="box2">
                        	<div class="header">
                            	<span class="title" style="font-size:12px;">Taskbar</span>
                                <div class="minmax" title="Click to toggle"></div>
                                <div class="clear"></div>
                            </div>
                            <div class="content">
                                <table class="system-settings" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td class="col1"><label>Taskbar</label></td>
                                        <td class="col2">
                                            <label style="display:inline;"><input type="radio" name="store_taskbar" value="yes"<?php echo (get_option('store_taskbar') == 'yes' ? ' checked="checked"' : '');?> /> Yes</label>
                                            <label style="display:inline;"><input type="radio" name="store_taskbar" value="no"<?php echo (get_option('store_taskbar') == 'no' ? ' checked="checked"' : '');?> /> No</label>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    	<div class="box2">
                        	<div class="header">
                            	<span class="title" style="font-size:12px;">Homepage</span>
                                <div class="minmax" title="Click to toggle"></div>
                                <div class="clear"></div>
                            </div>
                            <div class="content">
                                <table class="system-settings" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td class="col1"><label>Featured Page</label></td>
                                        <td class="col2">
                                        	<label><input<?php echo (get_option('featured_page') == 'yes' ? ' checked="checked"' : '');?> type="checkbox" name="featured_page" value="yes" /> Animated slideshow</label>
	                                        <div class="info" style="width:400px;">Checking this option will animate the featured page. Unchecking this would make slideshow changed every browser refreshed.</div>
                                        	<label>Animation Speed</label>
                                            <input style="width:100px;" type="text" name="featured_page_animation_speed" value="<?php echo (get_option('featured_page_animation_speed') > 0 ? get_option('featured_page_animation_speed') : '3000');?>" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    
                    	<div class="box2">
                        	<div class="header">
                            	<span class="title" style="font-size:12px;">Sidebar</span>
                                <div class="minmax" title="Click to toggle"></div>
                                <div class="clear"></div>
                            </div>
                            <div class="content">
                                <table class="system-settings" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td class="col1"><label>Live Support</label></td>
                                        <td class="col2">
                                            <label style="display:inline;"><input type="radio" name="store_live_support" value="yes"<?php echo (get_option('store_live_support') == 'yes' ? ' checked="checked"' : '');?> /> Yes</label>
                                            <label style="display:inline;"><input type="radio" name="store_live_support" value="no"<?php echo (get_option('store_live_support') == 'no' ? ' checked="checked"' : '');?> /> No</label>
                                            <div class="info" style="width:400px;">Click <a target="_blank" href="http://www.livezilla.net/downloads/en/" style="text-decoration:underline;">here</a> to download and install application for livesupport.</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col1"><label for="sb_category_lists_level">Category Menu Level</label></td>
                                        <td class="col2">
                                            <input type="text" name="sb_category_lists_level" id="sb_category_lists_level" value="<?php echo (get_option('sb_category_lists_level') !='' ? get_option('sb_category_lists_level') : '2');?>" style="width:50px;" />
                                            <label style="display:inline;" for="sb_category_animate"><input type="checkbox" name="sb_category_animate" id="sb_category_animate"<?php echo (get_option('sb_category_animate') =='1' ? ' checked="checked"' : '');?> value="1" />Animate</label>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                    	<!--div class="box2 box2_closed">
                        	<div class="header">
                            	<span class="title" style="font-size:12px;">Ads</span>
                                <div class="minmax" title="Click to toggle"></div>
                                <div class="clear"></div>
                            </div>
                            <div class="content">
                            </div>
                        </div-->
                        
                    </div>
                    <div id="tabs3">
                        <table class="system-settings" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td class="col1"><label>Shopping Cart</label></td>
                                <td class="col2">
                                    <label style="display:inline;"><input type="radio" name="store_shopping_cart" value="yes"<?php echo (get_option('store_shopping_cart') == 'yes' ? ' checked="checked"' : '');?> /> Yes</label>
                                    <label style="display:inline;"><input type="radio" name="store_shopping_cart" value="no"<?php echo ((get_option('store_shopping_cart') == 'no' || get_option('store_shopping_cart') == '') ? ' checked="checked"' : '');?> /> No</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col1"><label>Order Checkout</label></td>
                                <td class="col2">
                                    <label><input type="radio" name="store_checkout_type" value="1"<?php echo ((get_option('store_checkout_type') == '1' || get_option('store_checkout_type') == '') ? ' checked="checked"' : '');?> /> No direct checkout</label>
                                    <label><input type="radio" name="store_checkout_type" value="2"<?php echo (get_option('store_checkout_type') == '2' ? ' checked="checked"' : '');?> /> Direct checkout (<em>For future release</em>)</label>
                                    <div style="width:350px;" class="info">May require 3rd-party application/software e.g. PayPal</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="tabs4">
                        <table class="system-settings" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td class="col1"><label for="store_owner">Store Owner</label></td>
                                <td class="col2"><input type="text" name="store_owner" id="store_owner" value="<?php echo get_option('store_owner');?>" /></td>
                            </tr>
                            <tr>
                                <td class="col1"><label for="store_address">Store Address</label></td>
                                <td class="col2"><textarea name="store_address" id="store_address"><?php echo get_option('store_address');?></textarea></td>
                            </tr>
                            <tr>
                                <td class="col1"><label for="store_email">Store Email</label></td>
                                <td class="col2"><input type="text" name="store_email" id="store_email" value="<?php echo get_option('store_email');?>" /></td>
                            </tr>
                            <tr>
                                <td class="col1"><label for="store_phone">Store Phone</label></td>
                                <td class="col2"><input type="text" name="store_phone" id="store_phone" value="<?php echo get_option('store_phone');?>" style="width:150px;" /></td>
                            </tr>
                            <tr>
                                <td class="col1"><label for="store_fax">Fax</label></td>
                                <td class="col2"><input type="text" name="store_fax" id="store_fax" value="<?php echo get_option('store_fax');?>" style="width:150px;" /></td>
                            </tr>
                            <tr>
                                <td class="col1"><label for="store_sms">SMS</label></td>
                                <td class="col2"><input type="text" name="store_sms" id="store_sms" value="<?php echo get_option('store_sms');?>" style="width:150px;" /></td>
                            </tr>
                            <tr>
                                <td class="col1"><label>IM</label></td>
                                <td class="col2">
                                    <?php $im = (object) unserialize(stripslashes(get_option('store_im'))); ?>
                                    <label class="left"><input id="skype" type="text" name="store_im[skype]" value="<?php echo $im->skype;?>" style="width:100px;" /></label>
                                    <label class="left"><input id="ym" type="text" name="store_im[ym]" value="<?php echo $im->ym;?>" style="width:100px;" /></label>
                                    <div class="clear"></div>
                                    <label class="left"><input id="msn" type="text" name="store_im[msn]" value="<?php echo $im->msn;?>" style="width:100px;" /></label>
                                    <label class="left"><input id="icq" type="text" name="store_im[icq]" value="<?php echo $im->icq;?>" style="width:100px;" /></label>
                                    <div class="clear"></div>
                                    <label class="left"><input id="aol" type="text" name="store_im[aol]" value="<?php echo $im->aol;?>" style="width:100px;" /></label>
                                    <label class="left"><input id="gtalk" type="text" name="store_im[gtalk]" value="<?php echo $im->gtalk;?>" style="width:100px;" /></label>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <input type="hidden" name="comp" value="<?php echo JSTORE_ID;?>" />
    <input type="hidden" name="mod" value="settings" />
    <input type="hidden" name="action" value="save-settings" />
</form>