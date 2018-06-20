<?php
if(!defined('IMPARENT')){exit();} // No direct access

$userinfo = get_user($_SESSION['__FRONTEND_USER']['info']->ID);

$product = (object) unserialize($page->meta);
$meta = (object) unserialize($product->meta);
$product_id = $product->ID;
$p = parse_url($product->item_product_image);
parse_str($p['query'],$p);

$item_price = (object) unserialize($product->item_price);
$sample = (object) $item_price->sample;
$bulk_price = ((is_array($item_price->bulk) && $item_price->bulk!='') ? $item_price->bulk : '');
$has_bulk_price = ((is_array($item_price->bulk) && $item_price->bulk!='') ? true : false);


ksort($bulk_price);


get_header();
?>
<?php breadcrumb('Home','<span class="sep"></span>');?>

<div id="page" class="inner-wrapper">
	<div id="product">
    	<div style="padding-bottom:20px;">
           <div class="left side">
                <div class="image">	            
                    <?php if($meta->featured_product == 'yes'):?><div class="featured"></div><?php endif; ?>
                    <img class="view-image" rel="<?php echo $p['src'];?>" src="<?php echo $product->item_product_image;?>" />
                </div>
                <?php do_action('product_sidebar_ad');?>
            </div>
            <div class="right content">            	
            	<?php do_action('product_content_ad_1');?>
                <?php include('modules/product/calculator.php'); ?>
				<?php include('modules/product/pricelists.php'); ?>
                <div class="details">
                    <h3 style="font-size:13px;font-weight:bold;">Details</h3>
                    <?php
					$has_break = false;
					$desc = html_entity_decode(stripslashes($product->item_description));

					if(is_user_logged()){
						echo $desc;
					}else{
						$d = '</p><p class="login">More details? Click <a href="'.store_info('login',false).'?redirect='.rawurlencode(item_url($product->ID,false)).'" rel="nofollow">here</a> to see. <a href="'.store_info('register',false).'">Not yet a member?</a>';
						content_break($desc,$d,false);
					}
					?>
                    <p style="padding-top:10px;">For more info contact <a href="<?php the_permalink('contact-us');?>" style="text-decoration:underline;color:#FF7E00;"><?=JS_STORE_NAME;?></a></p>
                    
					<?php do_action('product_content_ad_2');?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
	<div id="others">
        <ul class="tabs">            
	        <?php if(get_option('comment') == 'yes'):?>
				<li class="tab active"><a href="#related-products">Related Products</a></li>
            <?php else:?>
	            <li class="tab active"><span>Related Products</span></li>
            <?php endif;?>
            <?php if(get_option('comment') == 'yes'):?>
	            <li class="tab"><a href="#comments">Comments</a></li>
            <?php endif;?>
        </ul>
        <div class="clear"></div>
        <div id="related-products-tab" class="tab-content" style="padding:10px;">
        	<?php include(GBL_ROOT_TEMPLATE.'/component/jstore/modules/product/related-products.php');?>
        </div>
        <?php if(get_option('comment') == 'yes'):?>
            <div id="comments-tab" class="tab-content hide">
                <?php include(GBL_ROOT_TEMPLATE.'/component/jstore/modules/product/comment.php');?>
            </div>
		<?php endif; ?>
	</div>
</div>
<div id="quicklinks-2" class="inner-wrapper" style="padding-top:10px;">
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
