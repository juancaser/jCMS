<?php
include('backend-load.php'); // Backend bootstrap loader
enque_script('uploadify');
enque_script('swfobject');
enque_style('uploadify-css');

global $backend_page;
$action_message = get_global_mesage('filemanager_action');
define('FILEMANAGER_URL',BACKEND_DIRECTORY.'/filemanager.php');

$mod = 'main';
switch($_REQUEST['mod']){
	case 'upload':
		$mod = 'upload';		
		break;
}
load_page_module('filemanager',$mod); // Load Page module
the_backend_header();
?>
<div class="page-wrapper">
    <div class="inner">
        <form class="form" id="gallery-viewer" method="post" action="<?php echo MEDIA_URL;?>">        
            <?php display_page();?>
            <input type="hidden" name="form" value="media" />
        </form>
    </div>
</div>
<?php the_backend_footer(); ?>