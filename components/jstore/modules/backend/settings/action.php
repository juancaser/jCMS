<?php
if(!defined('JCMS')){exit();} // No direct access
if($_REQUEST['action'] == 'save-settings'){
	$options = array(
		'store_taskbar' => $_REQUEST['store_taskbar'],
		'featured_page' => ($_REQUEST['featured_page']!='' ? $_REQUEST['featured_page'] : 'no'),
		'featured_page_animation_speed' => ($_REQUEST['featured_page_animation_speed']!='' ? $_REQUEST['featured_page_animation_speed'] : '3000'),
		'store_featured_article' => ($_REQUEST['store_featured_article']!='' ? $_REQUEST['store_featured_article'] : ''),		
		'store_user_registration' => $_REQUEST['store_user_registration'],
		'store_price_visibility' => $_REQUEST['store_price_visibility'],		
		'comment' => $_REQUEST['comment'],
		'comment_requires_login' => $_REQUEST['comment_requires_login'],
		'comment_moderation' => $_REQUEST['comment_moderation'],
		'store_live_support' => $_REQUEST['store_live_support'],
		'sb_category_lists_level' => ($_REQUEST['sb_category_lists_level'] > 0 ? $_REQUEST['sb_category_lists_level'] : 2),		
		'sb_category_animate' => ($_REQUEST['sb_category_animate']  == 1 ? '1' : '0'),
		'store_shopping_cart' => $_REQUEST['store_shopping_cart'],
		'store_ship_to' => $_REQUEST['store_ship_to'],
		'store_checkout_type' => $_REQUEST['store_checkout_type'],
		'store_owner' => $_REQUEST['store_owner'],
		'store_address' => $_REQUEST['store_address'],
		'store_email' => $_REQUEST['store_email'],
		'store_phone' => $_REQUEST['store_phone'],
		'store_fax' => $_REQUEST['store_fax'],
		'store_sms' => $_REQUEST['store_sms'],
		'store_im' => serialize($_REQUEST['store_im'])

	);
	$has_error = false;
	foreach($options as $key => $value){
		if(!update_option($key,$value)){
			$has_error  = true;
		}
	}
	if(!$has_error){
		$msg = 'Settings successfully saved.';
		$class = 'success';
	}else{
		$msg = 'Error occured while saving. Please try again.';
		$class = 'error';
	}
	set_global_mesage('component_action_message',$msg,$class);
	redirect(BACKEND_DIRECTORY.'/components.php?comp='.$_REQUEST['comp'].'&mod='.$_REQUEST['mod'],'js');
}
?>