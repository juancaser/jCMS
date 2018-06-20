<?php
if(!defined('JCMS')){exit();} // No direct access
if($_REQUEST['action'] == 'save'){
	$tpl = update_emailtpl($_REQUEST['template']);
	if(!$tpl){
		$msg = 'Error occured while saving the template.';
		$url = BACKEND_DIRECTORY.'/components.php?comp='.$_REQUEST['comp'].'&mod='.$_REQUEST['mod'];
		$class = 'error';
	}else{
		if($_REQUEST['template']['id'] > 0){
			$msg = 'Email template updated successfully';
		}else{
			$msg = 'Email template added successfully';
		}			
		$url = BACKEND_DIRECTORY.'/components.php?comp='.$_REQUEST['comp'].'&mod='.$_REQUEST['mod'].'&opt=edit&id='.$tpl->ID;
		$class = 'success';
	}
	set_global_mesage('component_action_message',$msg,$class);
	redirect($url,'js');
}
if($_REQUEST['action'] == 'remove'){
}
?>