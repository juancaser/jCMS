<?php
include('backend-load.php'); // Backend bootstrap loader
global $backend_page;
$action_message = get_global_mesage('users_action_message');

include(GBL_ROOT_BACKEND.'/modules/users/action.php');

switch($_REQUEST['mod']){
	case 'edit':
		if($_REQUEST['id'] > 0){	
			$title = 'Update User Profile';
		}else{
			$title = 'New User Profile';
		}
		break;
	default:
		$title = 'View Users';
		break;
	
}
$backend_page = (object) array('title'=> $title);
the_backend_header();
?>
<div class="page-wrapper">
    <div class="inner">    	
		<?php include(GBL_ROOT_BACKEND.'/modules/users/'.($_REQUEST['mod']!=''?$_REQUEST['mod']:'main').'.php');?>
    </div>
</div>
<?php the_backend_footer(); ?>