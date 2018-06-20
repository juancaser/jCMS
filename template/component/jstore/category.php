<?php
if(!defined('IMPARENT')){exit();} // No direct access
get_header();
?>
<?php breadcrumb('Home','<span class="sep"></span>');?>
<div id="category-listings" class="inner-wrapper">
    <h1 class="cat-title"><?php echo $page->title;?></h1>
	<?php
    $products = get_product_infos($page->ID);
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
							<?php if($product->item_excerpt!=''):?>                                
                                <p><?php echo strip_tags(stripslashes($product->item_excerpt));?></p>
                            <?php else:?>
                                <p><?php echo strip_tags(make_excerpt(html_entity_decode(stripslashes($product->item_description)),150,'...'));?></p>
                            <?php endif;?>
                        </div>                    
                        <div class="clear"></div>
                    </div>
                    <div class="sub">
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
	
    
	<?php $categories = get_categories($page->ID,array('ID','title','description','excerpt','thumb','meta'));?>
    <?php if(count($categories) > 0):?>
        <div class="grids sub-categories">
	        <h2 class="subcat-title">Sub Categories</h2>
            <?php
            $float = 'left';
			$cols = round($product_count / 2);
			$c = 1;
            ?>
			<?php for($a=0;$a < count($categories);$a++):$cat = $categories[$a];$meta = (object) unserialize($cat->meta);?>
            
                <div class="grid category <?php echo $float;?>">
                    <!--div class="grid-wrapper">
                        <h3><a href="<?php category_url($cat->ID);?>"><?php echo $cat->title;?></a></h3>
                        <?php if($cat->excerpt!=''):?>                                
                            <p><?php echo strip_tags(stripslashes(html_entity_decode($cat->excerpt)));?></p>
                        <?php else:?>
                            <p><?php echo strip_tags(make_excerpt(stripslashes(html_entity_decode(stripslashes($cat->description))),150,'...'));?></p>
                        <?php endif;?>
                    </div-->
                    
                    
                    <div class="grid-wrapper">
                        <div class="thumb">
                        	<?php echo (array_key_exists('product_'.$cat->ID,$_SESSION['_CART']) ? '<div class="in-cart" title="'.$cat->title.' is already in your cart."></div>' : '');?>
                        	<a href="<?php category_url($cat->ID);?>"><img src="<?php echo $cat->thumb;?>" style="width:100px;height:100px;" /></a>
                        </div>
                        <div class="info" style="width:240px;">
                            <h3><a href="<?php category_url($cat->ID);?>"><?php echo $cat->title;?></a></h3>
							<?php if($cat->excerpt!=''):?>                                
                                <p><?php echo strip_tags(stripslashes(html_entity_decode($cat->excerpt)));?></p>
                            <?php else:?>
                                <p><?php echo strip_tags(make_excerpt(stripslashes(html_entity_decode(stripslashes($cat->description))),150,'...'));?></p>
                            <?php endif;?>
                        </div>                    
                        <div class="clear"></div>
                    </div>
                    
                    
                    <div class="sub">
                        <a class="right" href="<?php category_url($cat->ID);?>">MORE PRODUCTS &raquo;</a>
                        <div class="clear"></div>
                    </div>
                </div>
                <?php $float = ($float == 'left' ? 'right' : 'left'); ?>
            <?php endfor;?>
            <div class="clear"></div>
        </div>
	<?php endif;?>
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