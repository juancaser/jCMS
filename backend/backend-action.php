<?php if(!defined('JCMS')){exit();} // No direct access
global $pathinfo,$config;

// Page redirection
if(!isset($_SESSION['__BACKEND_USER']) && $pathinfo->script!='login.php'){
	redirect(BACKEND_DIRECTORY.'/login.php','js');
}elseif(isset($_SESSION['__BACKEND_USER']) && $pathinfo->script=='login.php'){
	redirect(BACKEND_DIRECTORY,'js');
}

if($_REQUEST['action']!=''){
	switch($_REQUEST['form']){

		case 'help': // Help
			break;
		case 'components': // Components
			break;
	}	
}
?>