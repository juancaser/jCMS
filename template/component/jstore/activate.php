<?php
if(!defined('IMPARENT')){exit();} // No direct access
global $page;
get_header();
?>
<div id="page" class="inner-wrapper">
	<div id="welcome">
    	<h1>Activate your <?php get_siteinfo('name');?> account</h1>
        <p>Type your activation code and click <strong>Activate</strong> to proceed.</p>
        <?php _d($page->content,'<div class="msgbox error" style="margin:10px 0;width:400px;">','</div>');?>
        <form action="<?php store_info('activate')?>" method="post">
        	<p style="padding-top:10px;"><input type="text" name="code" style="width:300px;" value="<?php echo $_REQUEST['code']?>" />&nbsp;<input type="submit" value="Activate" /></p>
            <input type="hidden" name="action" value="activate" />
        </form>
	</div>
</div>
<?php get_footer(); ?>