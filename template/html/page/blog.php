<?php if(!defined('IMPARENT')){exit();} // No direct access ?>
<?php get_header(); ?>
<?php breadcrumb('Home','<span class="sep"></span>');?>
<div id="page" class="inner-wrapper">
	<div id="blog">
		<?php if(have_posts()): the_post(); ?>
        	<?php $id = the_id(false);?>
            <div <?php post_class();?>>
                <h1><?php the_title();?></h1>
                <div class="content"><?php the_content();?></div>
            </div>
        <?php endif;?>
        <?php $recent_blogs = related_lists($id,'post');?>
        <?php if(!$recent_blogs): ?>
        <?php else: ?>
	        <h2>Recent Blog Post</h2>
            <ul>
			<?php for($i=0;$i < count($recent_blogs);$i++): $blog = $recent_blogs[$i]; ?>
            	<li>
                	<h1><a href="<?php the_permalink($blog->ID);?>"><?php echo $blog->title;?></a></h1>
					<div class="content">
						<?php echo make_excerpt(html_entity_decode($blog->content),300,'... <a style="text-decoration:underline;" href="'.the_permalink($blog->ID,false).'#more">Read More</a>');?>
                    </div>
                </li>
            <?php endfor;?>
            </ul>
        <?php endif;?>
	</div>
</div>
<?php get_footer(); ?>