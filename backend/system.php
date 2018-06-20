<?php
include('backend-load.php'); // Backend bootstrap loader
global $backend_page;
$action_message = get_global_mesage('system_action_message');

define('SYSTEM_URL',BACKEND_DIRECTORY.'/system.php');

include(GBL_ROOT_BACKEND.'/modules/system/action.php');

switch($_REQUEST['mod']){
	case 'edit':
		if($_REQUEST['id'] > 0){	
			$page = get_page($_REQUEST['id']);
			$meta = (object) unserialize($page->meta);
			$title = 'Update '.($page->parent_page > 0 ? 'Sub' : '').($_REQUEST['type'] == 'post' ? ' Post' : ' Page');
		}else{
			$title = 'Add New '.($_REQUEST['parent_page'] > 0 ? 'Sub' : '').($_REQUEST['type'] == 'post' ? ' Post' : ' Page');
		}
		break;
	case 'sysinfo':
		$title = 'System Information';
		break;
	case 'layout':
		$title = 'Page Layouts';
		enque_script('jquery-ui');
		enque_style('jquery-ui-theme');
		break;
	default:
		$title = 'System Settings';
		break;
	
}
$backend_page = (object) array('title'=> $title);
the_backend_header();
?>
<div class="page-wrapper">
    <div class="inner">    	
		<?php include(GBL_ROOT_BACKEND.'/modules/system/'.($_REQUEST['mod']!=''?$_REQUEST['mod']:'main').'.php');?>
    </div>
</div>
<?php the_backend_footer(); ?>