<?php
if(!defined('JCMS')){exit();} // No direct access
 // Add / Update User
if($_REQUEST['action'] == 'save'){
	$user = update_user($_REQUEST);
	$msg = 'User profile successfully '.($_REQUEST['id'] > 0 ? 'updated' : 'created');
	$class = 'success';
	if(!$user){
		$msg = 'Error occure while creating new user profile';
		$class = 'error';
	}
	set_global_mesage('users_action_message',$msg,$class);
	redirect(BACKEND_DIRECTORY.'/users.php?mod='.$_REQUEST['mod'].($user->ID > 0 ? '&id='.$user->ID : ''),'js');
}

if($_REQUEST['action'] == 'delete'){
	$msg = '';
	$class='success';
	
	if(!delete_user($_REQUEST['chk'])){
		$class = 'error';
	}
	set_global_mesage('users_action_message',($class == 'error' ? 'Error occured while delete some data.' : 'User(s) successfully deleted.'),$class);
	redirect(BACKEND_DIRECTORY.'/users.php','js');
	break;
}
?>