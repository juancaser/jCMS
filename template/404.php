<?php if(!defined('IMPARENT')){exit();} // No direct access
global $page;
$page->title = 'Sorry the page you are lookin are either moved or it doesn\'t exists.';
 ?>
<?php get_header(); ?>
<div id="page" class="inner-wrapper">
	<h1 style="font-size:24px;">Sorry the page you are lookin are either moved or it doesn't exists.</h1>
    <!--p>Please try using the search field below.</p>
    <form id="pagenotfound-search" action="<?php get_siteinfo('url');?>/search">
            <input type="text" name="s" id="s"/>&nbsp;<input type="submit" id="search" value="SEARCH" />
    </form-->
    <div id="page404">
        <!--h2>Did you try searching? Enter a keyword(s) in the search field above. Or, try one of the links below.</h2-->
        <div style="padding:10px;height:300px;width:737px;background:#FFFFFF url('<?php get_siteinfo('template_directory');?>/images/404.jpg') no-repeat 50% 50%;">
        	<h2>Oh snap! i broke the bike.</h2>
        </div>
        <h3>Did you try searching? Enter a keyword(s) in the search field above. Or, try one of the links below.</h3>
        <div class="toplinks">
	        <div class="cols col1">
            	<h3>Top 3 Best Sellers</h3>
				<?php
                $bestseller = jcms_db_get_rows("SELECT ID,item_name,item_description,item_product_image_thumb,meta FROM #_store_product_info WHERE meta LIKE '%bestseller%' ORDER BY item_sort_order ASC LIMIT 0,3");
                ?>
                <ul class="product">
				<?php for($i=0;$i <= count($bestseller);$i++): $li = $bestseller[$i]; $meta = (object) unserialize($li->meta);?>
                	<?php if($meta->bestseller == 'yes' && $li->item_name!=''):?>
						<?php $thumb = $li->item_product_image_thumb; ?>
                        <li>
                            <div class="left" style="background-color:#F0F0F0;width:100px;height:100px;">
							<?php 
							if($thumb!=''){
								echo '<img src="'.$thumb.'" />';
							}else{
								echo '<div style="text-align:center;color:#808080;padding-top:40px;">NO IMAGE</div>';
							}
							?></div>                    	
                            <div class="left" style="padding-left:10px;width:245px;">
                                <h4><a href="<?php item_url($li->ID);?>"><?php echo $li->item_name;?></a></h4>
                                <p><?php echo strip_tags(make_excerpt(html_entity_decode(stripslashes($li->item_description)),150,'...'));?></p>
                                <p><a style="font-style:italic;color:#FF7E00;" href="<?php echo item_url($li->ID,false);?>">More details</a></p>
                            </div>
                            <div class="clear"></div>
                        </li>
					<?php endif;?>
                <?php endfor;?>
                </ul>
            </div>
            <div class="cols col2">
            	<h3>Latest Blogs</h3>
				<?php
				$blog_parent = jcms_db_get_row("SELECT ID FROM #_pages WHERE slug='blog'");
                $latest_blogs = jcms_db_get_rows("SELECT ID,title,content FROM #_pages WHERE parent_page='".$blog_parent->ID."' and status='published' ORDER BY date_created DESC LIMIT 0,3");
                ?>
                <ul class="product">
				<?php for($i=0;$i <= count($latest_blogs);$i++): $li = $latest_blogs[$i];?>
                	<?php if($li->title!=''):?>
                        <li>
                        	<h4><a href="<?php the_permalink($li->ID);?>"><?php echo $li->title;?></a></h4>
                            <p><?php echo strip_tags(make_excerpt(html_entity_decode(stripslashes($li->content)),150,'...'));?></p>
                            <p><a style="font-style:italic;color:#FF7E00;" href="<?php echo the_permalink($li->ID,false);?>">Read More</a></p>
                        </li>
					<?php endif;?>
                <?php endfor;?>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
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
