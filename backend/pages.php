<?php
include('backend-load.php'); // Backend bootstrap loader
global $backend_page;
$action_message = get_global_mesage('page_action_message');
enque_script('jquery-ui');
enque_style('jquery-ui-theme');
enque_script('uploadify');
enque_script('swfobject');
enque_style('uploadify-css');

define('PAGE_URL',BACKEND_DIRECTORY.'/pages.php');

include(GBL_ROOT_BACKEND.'/modules/pages/action.php');

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
	default:
		$title = 'View '.($_REQUEST['id'] > 0 ? 'Sub' : '').' Pages';
		break;
	
}
$backend_page = (object) array('title'=> $title);
the_backend_header();
?>
<div class="page-wrapper">
    <div class="inner">    	
		<?php include(GBL_ROOT_BACKEND.'/modules/pages/'.($_REQUEST['mod']!=''?$_REQUEST['mod']:'main').'.php');?>
    </div>
</div>
<?php the_backend_footer(); ?>