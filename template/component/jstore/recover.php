<?php
if(!defined('IMPARENT')){exit();} // No direct access
global $page;
if(in_array($_REQUEST['action'],array('recoverUserID','recoverPassword'))){
	enque_script('form-validation');
}

enque_script('form-validation');
get_header();
?>
<style type="text/css">
#recovery p{
	padding-bottom:20px;
}
#recovery .priority{
	color:#FF7E00;
}
#recovery label{
	display:block;
	padding-bottom:3px;
}
</style>
<div id="page" class="inner-wrapper">
	<div id="recovery">
        <form class="validate" method="post" action="<?php store_info('recover');?>">
        	<?php echo $page->content;?>
		</form>
	</div>
</div>
<div id="quicklinks-2" class="inner-wrapper">
	<?php do_action('product_footer_ad_1');?>
    <div class="left">
        <a href="<?php get_siteinfo('url');?>"><img src="<?php get_siteinfo('template_directory');?>/images/logo.png" /></a>
    </div>
    <div class="right">
        <a href="<?php the_permalink('free');?>"><img src="<?php get_siteinfo('template_directory');?>/images/free-button.png" /></a>
        <a href="<?php the_permalink('stockdesigns');?>#vectorize"><img src="<?php get_siteinfo('template_directory');?>/images/vectorize-art.png" /></a>
        <a href="<?php the_permalink('stockdesigns');?>#stock-arts"><img src="<?php get_siteinfo('template_directory');?>/images/our-stock-art.png" /></a>
        <a href="<?php the_permalink('stockdesigns');?>#personalize"><img src="<?php get_siteinfo('template_directory');?>/images/your-design.png" /></a>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<?php get_footer(); ?>