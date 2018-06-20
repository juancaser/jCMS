<?php
if(!defined('IMPARENT')){exit();} // No direct access
if($_REQUEST['action'] == 'save'){
	$user = update_user($_REQUEST);
}

enque_script('form-validation');
$userinfo = get_user($_SESSION['__FRONTEND_USER']['info']->ID);
$meta = (object) unserialize(stripslashes($userinfo->meta));

$us_states = us_states::get();
$countries = iso_3166::get();

get_header(); ?>
<?php breadcrumb('Home','<span class="sep"></span>');?>
<div id="page" class="inner-wrapper" style="padding:0 0 20px 20px;">
	<div id="my-account">
        <ul class="tabs">            
			<!--li class="<?php echo (!in_array($pathinfo->filename,array('settings','order')) ? 'active' : '');?>"><a href="<?php store_info('user');?>">My Dashboard</a></li>
            <li class="<?php echo ($pathinfo->filename == 'settings' ? 'active' : '');?>"><a href="<?php store_info('user-settings');?>">Profile Settings</a></li-->
            <li class="active"><a href="javascript:void(0);">Profile Settings</a></li>
        </ul>
        <div class="clear"></div>
        <div class="tab-content">
            <?php
			include(GBL_ROOT_TEMPLATE.'/component/jstore/modules/user/settings.php');
			/*if(in_array($pathinfo->filename,array('settings'))){
				include(GBL_ROOT_TEMPLATE.'/component/jstore/modules/user/'.$pathinfo->filename.'.php');
			}else{
				include(GBL_ROOT_TEMPLATE.'/component/jstore/modules/user/dashboard.php');
			}*/
            ?>
        </div>
	</div>
</div>
<?php get_footer(); ?>