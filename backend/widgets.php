<?php
include('backend-load.php'); // Backend bootstrap loader
global $backend_page;
$action_message = get_global_mesage('widget_action_message');

define('WIDGET_URL',BACKEND_DIRECTORY.'/widgets.php');

include(GBL_ROOT_BACKEND.'/modules/widgets/action.php');

$title = 'Widgets';

$backend_page = (object) array('title'=> $title);
the_backend_header();
?>
<div class="page-wrapper">
    <div class="inner">    	
		<?php include(GBL_ROOT_BACKEND.'/modules/widgets/'.($_REQUEST['mod']!=''?$_REQUEST['mod']:'main').'.php');?>
    </div>
</div>
<?php the_backend_footer(); ?>