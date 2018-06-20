<?php //if(!defined('IMPARENT')){exit();} // No direct access ?>
<?php
global $pathinfo;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php backend_title();?></title>
<link type="text/css" href="<?php echo BACKEND_DIRECTORY;?>/backend-styles.css" rel="stylesheet" />
<link type="text/css" href="<?php echo BACKEND_DIRECTORY;?>/layout.css" rel="stylesheet" />
<script type="text/javascript">
var backend_directory = '<?php echo BACKEND_DIRECTORY;?>';
var site_url = '<?php get_siteinfo('url');?>';
</script>
<?php backend_head();?>
<script type="text/javascript" src="<?php echo BACKEND_DIRECTORY;?>/functions.js"></script>
</head>
<body>	
	<?php display_modalbox();?>
    <?php if($pathinfo->script !='login.php'):?>
        <div id="logo-container">
            <div class="logo left">
                <a title="Back to homepage" target="_blank" href="<?php get_siteinfo('url');?>"><?php
                if(file_exists(GBL_ROOT.'/template/backend-logo.gif')){
                    echo '<img src="'.get_siteinfo('template_directory',false).'/backend-logo.gif" />';
                }else{
                    get_option('site_name');
                }
                ?></a>
            </div>
            
            <div class="clear"></div>
        </div>
    <?php endif;?>
	<div class="container<?php echo ($pathinfo->filename !='' ? ' '.$pathinfo->filename.'-container' : '');?>">
    	<?php
		if($pathinfo->filename!='login'){
			include('menu.php');			
		}
		?>
