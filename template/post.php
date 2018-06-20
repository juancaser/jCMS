<?php if(!defined('IMPARENT')){exit();} // No direct access ?>
<?php get_header(); ?>
<?php breadcrumb('Home','<span class="sep"></span>');?>
<div id="page" class="inner-wrapper">
	<div id="post">
		<?php if(have_posts()): the_post(); ?>
            <div <?php post_class();?>>
                <h1><?php the_title();?></h1>
                <div class="content"><?php the_content();?></div>
            </div>
        <?php endif;?>
	</div>
</div>
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