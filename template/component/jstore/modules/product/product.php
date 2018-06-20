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
$eaprice = $item_price->ea;
$bulk_price = ((is_array($item_price->bulk) && $item_price->bulk!='') ? $item_price->bulk : '');
$has_bulk_price = ((is_array($item_price->bulk) && $item_price->bulk!='') ? true : false);
ksort($bulk_price);
get_header();
?>
<?php breadcrumb('Home','<span class="sep"></span>');?>

<script type="text/javascript">
	<?php if(count($eaprice) > 0){
	echo "cart.single_price = '".strval($eaprice)."';";
	} ?>
	<?php if(count($bulk_price) > 0 && is_array($bulk_price)){
	echo 'cart.bulk_price = '.json_encode($bulk_price).';';
	} ?>
</script>

<div id="page" class="inner-wrapper" style="padding:0 0 20px 20px;">
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
                <?php include('modules/product/calculator.php');?>
                <?php
				if($eaprice > 0){
					include('modules/product/pricelists.php');
				}
				?>
                
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
                    <p style="padding-top:10px;">For more info or samples contact <a href="<?php the_permalink('contact-us');?>" style="text-decoration:underline;color:#FF7E00;"><?=JS_STORE_NAME;?></a></p>
                    
					<?php do_action('product_content_ad_2');?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
		<?php
        $products = get_related_product_infos($product->ID,$product->item_category);;
        $product_count = count($products);
        ?>
		<?php if($product_count > 0):?>
            <h3 class="related-title">Related Product</h3>
            <div class="related">
                <?php
                $c = 1;
                $float = 'left';
                ?>                
                <?php for($i=0;$i < $product_count;$i++):$product = $products[$i];$product_meta = (object) unserialize($product->meta);?>
                    <div class="product <?php echo $float;?>">
                        <?php if($product_meta->featured_product == 'yes'):?><div class="featured"></div><?php endif;?>
                        <div class="product-wrapper">
                            <div class="thumb">
                            	<?php echo (array_key_exists('product_'.$product->ID,$_SESSION['_CART']) ? '<div class="in-cart" title="'.$product->item_name.' is already in your cart."></div>' : '');?>
                            	<a href="<?php item_url($product->ID);?>"><img src="<?php timthumb($product->item_product_image_thumb,100,100);?>" title="<?php echo $product->item_name;?>" /></a>
                            </div>
                            <div class="info">
                                <h3><a href="<?php item_url($product->ID);?>"><?php echo $product->item_name;?></a></h3>
                                <?php
								$price_data = (object) unserialize($product->item_price);
								$item_price = ($price_data->price > 0 ? $price_data->price : $product->item_price);
								?>
                                <?php if(get_option('store_shopping_cart') =='yes' && is_user_logged()):?>	                                
                                    <form id="frm<?php echo $product->ID;?>" class="frm_shopping_cart" action="<?php store_info('cart');?>" method="post">
                                        <input type="hidden" name="action" value="add" />
                                        <input type="hidden" name="product[id]" value="product_<?php echo $product->ID;?>" />
                                        <input type="hidden" name="product[item_id]" value="<?php echo $product->ID;?>" />                                    
                                        <input type="hidden" name="product[type]" value="product" />
                                        <input type="hidden" name="product[name]" value="<?php echo stripslashes($product->item_name);?>" />
                                        <input type="hidden" name="product[quantity]" value="1" />
                                        <input type="hidden" name="product[price]" value="<?php echo $item_price;?>" />
                                        <input type="hidden" name="product[return]" value="<?php echo get_siteinfo('url',false).$_SERVER['REQUEST_URI'];?>" />
									</form>
									<?php if($item_price > 0):?>
	                                	<p class="price">Price: <span>$<?php echo $item_price;?></span></p>
                                    <?php endif;?>
                                <?php endif;?>
								<?php if($product->item_excerpt!=''):?>                                
                                    <p><?php echo strip_tags($product->item_excerpt);?></p>
                                <?php else:?>
                                    <p><?php echo strip_tags(make_excerpt(html_entity_decode(stripslashes($product->item_description)),150,'...'));?></p>
                                <?php endif;?>
                            </div>                    
                            <div class="clear"></div>
                        </div>
                        <div class="sub">
	                        <?php if(get_option('store_shopping_cart') =='yes' && is_user_logged() && $product->item_price !=''):?>
								<a class="cat-cart left" href="javascript:document.getElementById('frm<?php echo $product->ID;?>').submit();">ADD TO CART</a>
                            <?php endif; ?>
                            <a class="right" href="<?php item_url($product->ID);?>">DETAILS</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <?php
                    if($float == 'left'){
                        $float = 'right';
                    }else{
                        $float = 'left';
                    }
					if($i ==($product_count-1)){
						echo '<div class="clear"></div>';
					}elseif($c >= 2 || ($product_count - 1) == $i){
                        echo '<div class="clear"></div>';
                        $c = 0;
                    }
                    $c++;
                    ?>
                <?php endfor;?>
            </div>
        <?php endif; ?>
    </div>
	<div id="others">
        <ul class="tabs">            
	        <?php if(get_option('comment') == 'yes'):?>
				<li class="tab active"><a href="#related-products">Related Products</a></li>
            <?php else:?>
	            <li class="tab active"><a href="javascript:void(0);">Related Products</a></li>
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
