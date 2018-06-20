<?php if(!defined('IMPARENT')){exit();} // No direct access ?>
<?php get_header(); ?>
<div id="page" class="inner-wrapper">    
<?php if(have_posts() && $_REQUEST['s']!=''): ?>
	<div id="search-result">
		<h1>Search for "<?php echo $_REQUEST['s'];?>"</h1>
		<?php while (have_posts()): the_post();?>
			<div <?php post_class('result');?>>
				<?php 
				$pl = '';
				if(post_type() == 'categories'){
					$pl = category_url(the_id(false),false);
				}elseif(post_type() == 'products'){
					$pl = item_url(the_id(false),false);
				}elseif(post_type() == 'post' || post_type() == 'page'){
					$pl = the_permalink(the_id(false),false);
				}
                ?>

				<h2><a href="<?php echo $pl;?>"><?php the_title();?></a></h2>
				<div class="content"><?php the_content('excerpt=1&link_to_post=0&more_text=...<a  class="readmore" href="'.$pl.'#more">Read More</a>');?></div>
				<div class="info">Permalink &raquo; <a href="<?php echo $pl;?>"><?php echo $pl;?></a></div>
			</div>
		<?php endwhile;?>
	</div>
	<div id="search-more">
		<h3>Didn't find what your lookin for? Try searching it again on the search field below.</h3>
		<form id="pagenotfound-search" action="<?php get_siteinfo('url');?>/search">
			<input type="text" name="s" id="s"/>&nbsp;<input type="submit" id="search" value="SEARCH" />
		</form>
	</div>
<?php else:?>
	<div id="search-result">
		<h1>Search for "<?php echo $_REQUEST['s'];?>" return nothing. Please try another search.</h1>
		<form id="pagenotfound-search" action="<?php get_siteinfo('url');?>/search">
			<input type="text" name="s" id="s"/>&nbsp;<input type="submit" id="search" value="SEARCH" />
		</form>
	</div>
<?php endif;?>
	
</div>
<?php get_footer(); ?>