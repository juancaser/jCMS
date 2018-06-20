<?php
if(!defined('IMPARENT')){exit();} // No direct access
get_header();
?>
<?php breadcrumb('Home','<span class="sep"></span>');?>
<div id="category-listings" class="inner-wrapper">
    <h1 class="cat-title"><?php echo $page->title;?></h1>
	<?php
    $products = get_bestsellers();
    $product_count = count($products);
    ?>	
	<?php if($product_count > 0):?>
		<div class="products grids">    
            <?php
            $c = 1;
            $float = 'left';
            ?>
            <?php for($i=0;$i < $product_count;$i++):$product = $products[$i];$product_meta = (object) unserialize($product->meta);?>
                <div class="grid product <?php echo $float;?>">
                    <?php if($product_meta->featured_product == 'yes'):?><div class="featured"></div><?php endif;?>
                    <div class="grid-wrapper">
                        <div class="thumb">
                        	<?php echo (array_key_exists('product_'.$product->ID,$_SESSION['_CART']) ? '<div class="in-cart" title="'.$product->item_name.' is already in your cart."></div>' : '');?>
                        	<a href="<?php item_url($product->ID);?>"><img src="<?php echo $product->item_product_image_thumb;?>" style="width:100px;height:100px;" /></a>
                        </div>
                        <div class="info">
                            <h3><a href="<?php item_url($product->ID);?>"><?php echo $product->item_name;?></a></h3>
							<?php
							$item_price = (object) unserialize($product->item_price);
							$eaprice = $item_price->ea;							
                            ?>
							<?php if(get_option('store_shopping_cart') =='yes' && is_user_logged()):?>
								<?php if($eaprice > 0):?>
                                    <form id="frm<?php echo $product->ID;?>" class="calculator" action="<?php store_info('cart');?>" method="post">
                                    	<?php
										$cart_info = getCartID($product->ID);
										$cart_id = $cart_info['cart_id'];
                                        ?>
										<?php if($cart_id !=''):?>
                                        <input type="hidden" name="cart[cart_id]" value="<?php echo $cart_id ;?>" />
                                        <?php endif;?>
                                        <input type="hidden" name="cart[product_id]" value="<?php echo $product->ID;?>" />
                                        <input type="hidden" name="cart[name]" value="<?php echo stripslashes($product->item_name);?>" />
                                        <input type="hidden" name="cart[details][order_type]" value="regular" />
                                        <input type="hidden" name="action" value="add" />
                                        <input type="hidden" name="cart[item_price]" value="<?php echo $eaprice;?>" />
                                        <input type="hidden" name="cart[quantity]" value="<?php echo ($cart_info['quantity'] > 0 ? ($cart_info['quantity'] + 1) : '1');?>" />
                                    </form>
                                    <p class="price" style="padding-bottom:5px;">Price: <span style="color:#FF7E00;font-weight:bold;">$<?php echo $eaprice;?> ea</span></p>
                                <?php endif;?>
                            <?php endif;?>
							<?php if($product->item_excerpt!=''):?>                                
                                <p><?php echo strip_tags(stripslashes($product->item_excerpt));?></p>
                            <?php else:?>
                                <p><?php echo strip_tags(make_excerpt(html_entity_decode(stripslashes($product->item_description)),150,'...'));?></p>
                            <?php endif;?>
                        </div>                    
                        <div class="clear"></div>
                    </div>
                    <div class="sub">
                    	<?php if(get_option('store_shopping_cart') =='yes' && is_user_logged() && $eaprice > 0):?>
							<a class="cat-cart left" href="javascript:document.getElementById('frm<?php echo $product->ID;?>').submit();">ADD TO CART</a>
                        <?php endif;?>
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
                if($c >= 2 || ($product_count - 1) == $i){
                    echo '<div class="clear"></div>';
                    $c = 0;
                }
                $c++;
                ?>
            <?php endfor;?>
		</div>
    <?php /*else:?>
    	<p style="margin-top:20px;font-style:italic;">No <?php echo $page->title;?> products</p>*/ ?>
    <?php endif;?>
    <div class="content">
	    <?php echo stripslashes($page->content);?>
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