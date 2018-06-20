<?php if(!defined('IMPARENT')){exit();} // No direct access ?>
<?php get_header(); ?>
    <!--div id="featured-article" style="background-image:url('<?php get_siteinfo('template_directory');?>/images/featured.jpg');">
        <a class="learn-more" href="<?php the_permalink('about-us');?>">LEARN MORE</a>
    </div-->
    <div id="featured-page">
    	<?php featured_page();?>
    </div>
    
    <div id="featured-products" class="inner-wrapper">
        <?php featured_categories(); ?>
    </div>
    <div class="clear"></div>
    <div id="quicklinks-2" class="inner-wrapper">
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