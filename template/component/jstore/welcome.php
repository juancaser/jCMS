<?php
if(!defined('IMPARENT')){exit();} // No direct access
function login_title(){
	return 'Welcome to '.get_siteinfo('name',false).' &#8212; ';
}
add_filter('title','login_title');

get_header();
?>
<div id="page" class="inner-wrapper">
	<div id="welcome">
    	<h1>Welcome to <?php get_siteinfo('name');?></h1>
        <p>Thank you for registering at <?php get_siteinfo('name');?>.</p>
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