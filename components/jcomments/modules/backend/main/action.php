<?php
if(!defined('IMPARENT')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access

if($_REQUEST['action'] == 'delete'){
	$ids = join(",",$_REQUEST['chk']['comment']);	
	if(jcms_db_delete("#_comments","ID IN (".$ids.")")){
		$msg = 'Comments successfully deleted.';
		$class = 'success';
	}else{
		$msg = 'Error occured while deleting comments. Please try again.';
		$class = 'error';
	}
	set_global_mesage('component_action_message',$msg,$class);
	redirect(BACKEND_DIRECTORY.'/components.php?comp='.$_REQUEST['comp'].($_REQUEST['view']!=''?'&view='.$_REQUEST['view']:''),'js');
}

if($_REQUEST['action'] == 'approve'){
	$ids = join(",",$_REQUEST['chk']['comment']);
	if(jcms_db_update("#_comments",array('comment_status' => 'published'),"ID IN (".$ids.")")){	
		$msg = 'Comments successfully approved.';
		$class = 'success';
	}else{
		$msg = 'Error occured while approving comments. Please try again.';
		$class = 'error';
	}
	set_global_mesage('component_action_message',$msg,$class);
	redirect(BACKEND_DIRECTORY.'/components.php?comp='.$_REQUEST['comp'].($_REQUEST['view']!=''?'&view='.$_REQUEST['view']:''),'js');
}

?>