<?php
if(!defined('IMPARENT')){exit();} // No direct access
global $config;
switch($_REQUEST['action']){
	case 'save':	
		$msg = '';
		$class='success';
		$order = update_order($_REQUEST);
		if(!$order){
			$class='error';
		}
		set_global_mesage('component_action_message',($class == 'error' ? 'Error occured while updating order.' : 'Order(s) successfully updated.'),$class);
		redirect(BACKEND_DIRECTORY.'/components.php?comp='.$_REQUEST['comp'].'&mod='.$_REQUEST['mod'].'&opt='.$_REQUEST['opt'].'&id='.$_REQUEST['id'],'js');
		break;		
	case 'delete':
		$msg = '';
		$class='success';
		if(!delete_order($_REQUEST['chk']['order'])){
			$class = 'error';
		}

		set_global_mesage('component_action_message',($class == 'error' ? 'Error occured while deleting some data.' : 'Order(s) successfully deleted.'),$class);
		redirect(BACKEND_DIRECTORY.'/components.php?comp='.$_REQUEST['comp'].'&mod='.$_REQUEST['mod'],'js');
		break;
}
?>