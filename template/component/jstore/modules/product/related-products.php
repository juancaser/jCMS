<?php if(!defined('IMPARENT')){exit();} // No direct access ?>
<div id="related-product" class="grids related-products">
	<?php
	$userinfo = get_user($_SESSION['__FRONTEND_USER']['info']->ID);
	$product_id = ($product->ID > 0 ? $product->ID : $_REQUEST['product_id']);
	$product_category = ($product->item_category > 0 ? $product->item_category : $_REQUEST['category']);
	
    $related_products = get_related_product_infos($product_id,$product_category);
    $related_product_count = count($related_products);
    ?>
	<?php if($related_product_count > 0):?>
        <?php
        $c = 1;
        $float = 'left';
        ?>                
        <?php for($i=0;$i < $related_product_count;$i++):$related_product = $related_products[$i];$product_meta = (object) unserialize($related_product->meta);?>
	        <div id="product<?php echo $product_id;?>" class="grid product <?php echo $float;?>">
                <?php echo ($product_meta->featured_product == 'yes' ? '<div class="featured"></div>' : ''); ?>
                <div class="grid-wrapper product-wrapper">
                    <div class="thumb">
                        <?php echo (array_key_exists('product_'.$related_product->ID,$_SESSION['_CART']) ? '<div class="in-cart" title="'.$related_product->item_name.' is already in your cart."></div>' : '');?>
                        <a href="<?php item_url($related_product->ID);?>"><img src="<?php echo $related_product->item_product_image_thumb;?>" title="<?php echo $related_product->item_name;?>" alt="" /></a>
                    </div>
                    <div class="info">
                        <h3><a href="<?php item_url($related_product->ID);?>"><?php echo $related_product->item_name;?></a></h3>
                        <?php if($related_product->item_excerpt!=''):?>                                
                            <p><?php echo strip_tags($related_product->item_excerpt);?></p>
                        <?php else:?>
                            <p><?php echo strip_tags(make_excerpt(html_entity_decode(stripslashes($related_product->item_description)),150,'...'));?></p>
                        <?php endif;?>
                    </div>                    
                    <div class="clear"></div>
                </div>
                <div class="sub">
                    <a class="right" href="<?php item_url($related_product->ID);?>">DETAILS</a>
                    <div class="clear"></div>
                </div>
            </div>
            <?php
            if($float == 'left'){
                $float = 'right';
            }else{
                $float = 'left';
            }
            if($i ==($related_product_count-1)){
                echo '<div class="clear"></div>';
            }elseif($c >= 2 || ($related_product_count - 1) == $i){
                echo '<div class="clear"></div>';
                $c = 0;
            }
            $c++;
            ?>
        <?php endfor;?>    
	<?php endif; ?>
    <a class="right more" href="<?php category_url($related_product->item_category);?>">More related products</a>
    <div class="clear"></div>
</div>