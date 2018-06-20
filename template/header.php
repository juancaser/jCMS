<?php if(!defined('IMPARENT')){exit();} // No direct access ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php the_meta();?>
<title><?php echo (page_is('home') ? get_siteinfo('name',false) : title('default='.get_siteinfo('name',false).'&separator_position=right&echo=0&separator=[spc][amp]#8212;[spc]').get_siteinfo('name',false));?></title>
<link type="text/css" href="<?php get_siteinfo('template_directory');?>/global.css" rel="stylesheet" />
<link type="text/css" href="<?php get_siteinfo('template_directory');?>/layout.css" rel="stylesheet" />
<!--[if IE 7]>
<link defer rel="stylesheet" href="<?php get_siteinfo('template_directory');?>/ie7.css" type="text/css" media="screen" />
<![endif]-->
<!--[if IE 6]>
<link defer rel="stylesheet" href="<?php get_siteinfo('template_directory');?>/ie6.css" type="text/css" media="screen" />
<![endif]-->
<script type="text/javascript">
var url = '<?php get_siteinfo('url');?>';
var template_directory = '<?php get_siteinfo('template_directory');?>';
var animatesbcat = <?php echo (get_option('sb_category_animate') == '1' ? 'true' : 'false');?>;
var animate_ss = <?php echo (get_option('featured_page') == 'yes' ? 'true' : 'false');?>;
<?php if(get_option('featured_page_animation_speed') > 0):?>
var animation_speed = <?php echo get_option('featured_page_animation_speed');?>;
<?php endif;?>
</script>
<?php if(get_option('sb_category_animate') == '1'):?><style type="text/css">#categories ul li li ul {display:none;}</style><?php endif;?>
<?php the_head();?>
<!--[if lte IE 6]><script type="text/javascript">
$(document).ready(function(){
  DD_belatedPNG.fix('.png');
});
</script><![endif]-->
</head>
<body>
	<?php do_action('taskbar');?>
	<div class="wrapper">
    	<div id="header">
        	<div class="the-left">
                <a class="logo" href="<?php get_siteinfo('url');?>" alt="<?php get_siteinfo('name');?> - <?php get_siteinfo('description');?>" title="<?php get_siteinfo('name');?> - <?php get_siteinfo('description');?>">&nbsp;</a>
                <img src="<?php get_siteinfo('template_directory');?>/images/introducing.png" class="left" />
                <div class="clear"></div>
                <?php if(get_option('store_email') !=''):?>
                <div class="email">e: <span><?php echo strtoupper(get_option('store_email'));?></span></div>
                <?php endif;?>
                <?php if(get_option('store_phone') !=''):?>
                <div class="phone">t: <span><?php echo strtoupper(get_option('store_phone'));?></span></div>
                <?php endif;?>
            </div>
            <div class="the-right">
	            <?php if(get_option('store_taskbar') != 'yes'):?>
                    <div class="right-1">
                        <a href="<?php the_permalink('free');?>" class="free-button"></a>
                        <div class="right">                            
                            <ul>
                                <li class="first"><a href="<?php get_siteinfo('url');?>" title="Home">Home</a></li>
                                <?php if(!is_user_logged()):?>
                                    <li><a href="<?php store_info('login');?>" title="Log In">Log In</a></li>
                                    <?php if(get_option('store_user_registration') =='on'):?>
                                        <li><a href="<?php store_info('register');?>" title="Register">Register</a></li>
                                    <?php endif;?>
                                <?php else:?>                                
                                    <li><a href="<?php store_info('user');?>" title="My Account">My Account</a></li>
                                    <?php if(get_option('store_shopping_cart') =='yes' && ($_REQUEST['action'] != 'checkout' && $_REQUEST['step']=='')):?>
                                        <li><a href="<?php store_info('cart');?>" title="Shopping Cart">Shopping Cart</a></li>
                                        <?php if(count($_SESSION['_CART']) > 0):?>
                                            <li><a href="<?php store_info('checkout');?>" title="Checkout">Checkout</a></li>
                                        <?php endif;?>
                                    <?php endif;?>
                                    <li><a href="<?php store_info('logout');?>" title="Logout">Logout</a></li>
                                <?php endif;?>                            
                            </ul>
                            <div class="clear"></div>
                            <?php if(get_option('store_shopping_cart') =='yes' && is_user_logged()):?>
                                <div class="shopping-cart">
                                    <span class="label">SHOPPING CART:</span>
                                    <span class="cart">now in your cart <span class="items"><span class="no"><?php echo intval(count($_SESSION['_CART']));?></span> items</span></span>
                                    <div class="clear"></div>
                                </div>
                            <?php endif;?>
                        </div>
                        <div class="clear"></div>
                    </div>
				<?php else:?>
					<div style="height:75px;"></div>
                <?php endif;?>
            	<div class="right-2">
                	<form action="<?php get_siteinfo('url');?>/search">
                    	<input type="text" name="s" id="s" />&nbsp;<input id="search" type="submit" value="SEARCH" />
                    </form>
                    <ul>
                        <li><a href="<?php the_permalink('samples');?>" title="Samples">Samples</a></li>
                        <li><a href="<?php the_permalink('faq');?>" title="Frequently Asked Questions">FAQs</a></li>
                        <li><a href="<?php the_permalink('stockdesigns');?>" title="Stock Designs">Stock Designs & Art</a></li>
                        <li><a href="<?php the_permalink('about-us');?>" title="About Us">About Us</a></li>
                        <li class="last"><a href="<?php the_permalink('contact-us');?>" title="Contact Us">CONTACT US</a></li>
                    </ul>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    	<div id="top-navigation">
			<?php //make_category_topnav(array('1','2','4','6'),'category-nav');?>
            <ul id="category-nav">
                <li><a href="<?php category_url('patch-kits');?>">PATCH KITS</a></li>
                <li><a href="<?php category_url('custom-bottles');?>">WATER BOTTLES</a></li>
                <li><a href="<?php category_url('custom-cages');?>">BIKE CAGES</a></li>
                <li><a href="<?php category_url('custom-clothing');?>">CLOTHING</a></li>
                <li><a href="<?php category_url('custom-locks');?>">BIKE LOCKS</a></li>
                <li><a href="<?php category_url('custom-bikes');?>">CUSTOM BIKES</a></li>
                <li><a href="<?php category_url('custom-saddles');?>">BIKE SADDLES</a></li>
                <li><a href="<?php store_info('best-sellers');?>">BEST SELLERS</a></li>
            </ul>
            <div class="clear"></div>
        </div>
        <?php get_sidebar('main');?>
		<div class="inner">	