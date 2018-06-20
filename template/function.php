<?php if(!defined('IMPARENT')){exit();} // No direct access
global $pathinfo,$page;
register_script('template-js',get_siteinfo('template_directory',false).'/js/functions.js','jquery','0.0.b1');
register_script('form-validation',get_siteinfo('template_directory',false).'/js/validation.js','jquery','0.0.b1');
register_script('cart',get_siteinfo('template_directory',false).'/component/jstore/cart.js','jquery','0.0.b1');
//register_script('DD_belatedPNG',get_siteinfo('template_directory',false).'/js/DD_belatedPNG_0.0.8a-min.js','jquery','0.0.8a');

register_script('lightbox',get_siteinfo('template_directory',false).'/js/lightbox/jquery.lightbox-0.5.pack.js','jquery','0.5');
register_style('lightbox-css',get_siteinfo('template_directory',false).'/js/lightbox/jquery.lightbox-0.5.css','jquery','0.5');

$userinfo = $_SESSION['__FRONTEND_USER']['info']; // User informations
enque_script('jquery');
//enque_script('jquery-ui');

enque_script('lightbox');

enque_script('template-js');
enque_script('DD_belatedPNG');
enque_script('cart');

enque_style('lightbox-css');

/**
 * Sidebar Loader
 */
function get_sidebar($name){
	if(file_exists(GBL_ROOT_TEMPLATE.'/sidebar-'.$name.'.php')){
		require_once(GBL_ROOT_TEMPLATE.'/sidebar-'.$name.'.php');
	}	
}

/**
 * Textarea Formating
 */
function format_textarea($string){
	$str = $string;
	$str = str_replace('\r\n','',$str);
	$str = str_replace('<br>','',$str);
	return $str;
}

/**
 * Category Top Navigation 
 */
function make_category_topnav($id,$list_id = ''){
	if($id!=''){
		if(!is_array($id)){
			$ids = explode(',',$id);
		}	
		$ids = "'".join("','",$id)."'";	
		$categories = jcms_db_get_rows("SELECT ID,cat_slug,cat_title FROM #_store_product_categories WHERE ID IN (".$ids.") OR cat_slug IN (".$ids.") AND cat_status='active' ORDER BY cat_sort_order ASC");
		if(count($categories) > 0){
			$lists='<ul'.($list_id!='' ? ' id="'.$list_id.'"' : '').'>'."\n";
			for($i=0;$i < count($categories);$i++){
				$category = $categories[$i];
				$lists.='<li><a href="'.category_url($category->ID,false).'">'.$category->cat_title.'</a></li>'."\n";
			}
			$lists.='</ul>'."\n";
			echo $lists;
		}		
	}
}

/**
 * Featured Products
 */
function featured_products(){
	$sql = "SELECT pi.ID,pi.item_name,pi.item_name,pi.item_product_image,pi.meta,pc.cat_title AS category FROM #_store_product_info AS pi 
	LEFT JOIN #_store_product_categories AS pc ON pc.ID=pi.item_category
	WHERE pi.item_status='active' AND pi.meta LIKE '%:\"featured_product\";%' ORDER BY pi.item_sort_order LIMIT 0,12";
	$products = jcms_db_get_rows($sql);
	$c = 1;
	for($i=0;$i < count($products);$i++){
		$product = $products[$i];
		$meta = (object) unserialize($product->meta);
		if($meta->featured_product == 'yes'){
			echo '
			<div class="products'.($c == 1 ? ' first' : '').'">
				<h3><a href="'.item_url($product->ID,false).'">'.$product->item_name.'</a></h3>
				<h4>'.$product->category.'</h4>
				<div class="thumb">
					<img src="'.$product->item_product_image_thumb.'" />
				</div>
				<div class="tag">'.strtoupper($meta->tagline).'</div>
			</div>
			';
			
			if($i ==(count($products)-1)){
				echo '<div class="clear"></div>';
			}elseif($c >= 3){
				echo '<div class="clear"></div>';
				$c = 0;
			}
			$c++;
		}		
	}
}

/**
 * Featured Categories
 */
function featured_categories(){
	$sql = "SELECT pc1.ID, pc1.cat_title, pc1.cat_image_main, pc1.cat_meta, pc2.cat_title AS parent_category FROM #_store_product_categories AS pc1 
	LEFT JOIN #_store_product_categories AS pc2 ON pc2.ID=pc1.cat_parent 
	WHERE pc1.cat_status='active' AND pc1.cat_meta LIKE '%:\"featured_category\";%' ORDER BY pc1.cat_sort_order LIMIT 0,12";
	$categories = jcms_db_get_rows($sql);
	$cats = array();
	
	// With Sorting
	for($i=0;$i < count($categories);$i++){
		$category = $categories[$i];
		$meta = (object) unserialize($category->cat_meta);
		if($meta->featured_category == 'yes'){
			$cats[$meta->featured_category_order] = (object) array(
										'ID' => $category->ID,
										'url' => category_url($category->ID,false),
										'title' => ($meta->new == 'yes' ? '<span>New!</span> ' : '').$category->cat_title,
										'tagline' => $meta->tagline,
										'tagline2' => strtoupper($meta->tagline2),										
										'main' => $category->cat_image_main
									);
		}		
	}
	
	ksort($cats);
	$c = 1;
	$i = 0;
	foreach($cats as $category){
		echo '
		<div class="products'.($c == 1 ? ' first' : '').'">
			<h3><a href="'.$category->url.'">'.$category->title.'</a></h3>
			<h4>'.$category->tagline.'</h4>
			<div class="thumb">
				<a href="'.$category->url.'"><img src="'.$category->main.'" /></a>
			</div>
			<div class="tag">'.$category->tagline2.'</div>
		</div>
		';
		if($i ==(count($cats)-1)){
			echo '<div class="clear"></div>';
		}elseif($c >= 3){
			echo '<div class="clear"></div>';
			$c = 0;
		}
		$c++;
	}
}

/**
 * Special Promos
 */
function specials_promos(){
	$ids = array();
	$products = jcms_db_get_rows("SELECT ID FROM #_store_product_info WHERE item_status='active' AND meta LIKE '%:\"special\";%'");
	for($i=0;$i < count($products);$i++){$ids[] = $products[$i]->ID;}
	$id = rand(0, (count($ids)-1));	
	$product = get_product_info($ids[$id],array('ID','name','description','product_image','meta'));
	return $product;
}

/* Related Posts Listings */
function related_lists($parent_id,$show_type = 'all',$show = 5){
	$number_of_posts = $show;
	$postcount = jcms_db_get_row("SELECT COUNT(pg.ID) AS post_count FROM ".$GLOBALS['page_tbl_name']." AS pg WHERE pg.parent_page='".$parent_id."' AND pg.status='published'".($show_type != 'all' ? " AND pg.page_type='".$show_type."'" : ""));	
	$postcount =  $postcount->post_count;
	if($postcount > 0){
		$rowsperpage = $number_of_posts;
		$totalpages = ceil($postcount / $rowsperpage);	
		if(isset($_REQUEST['p']) && is_numeric($_REQUEST['p'])){	
			$currentpage = (int) $_REQUEST['p'];		
		}else{		
			$currentpage = 1;		
		}
		
		if($currentpage > $totalpages)
			$currentpage = $totalpages;
		
		if($currentpage < 1){$currentpage = 1;}
		
		$offset = ($currentpage - 1) * $rowsperpage;
		$posts = jcms_db_get_rows("SELECT pg.*,DATE_FORMAT(pg.date_created,'%M %e, %Y') AS published_date FROM ".$GLOBALS['page_tbl_name']." AS pg WHERE pg.parent_page='".$parent_id."' AND pg.status='published'".($show_type != 'all' ? " AND pg.page_type='".$show_type."'" : "")." LIMIT ".$offset.",".$rowsperpage);
		return $posts;
	}else{
		return false;
	}
}

/* Breadcrumb */
function breadcrumb($home = 'Home',$separator = '/'){
	global $page;
	$separator = ($separator == NULL ? '' : $separator);
	$l = '';	
	
	$pi = get_pathinfo();
	$dirs = array();
	$d = $pi->dirs;
	for($i=0;$i < count($d);$i++){
		$_d = $d[$i];
		$bn = basename($_d,'.'.$pi->extension);
		if($pi->filename != $bn){			
			$dirs[] = $bn;
		}
	}
	if(!page_is('home')){
		$l = '<a class="pieces" href="'.get_siteinfo('url',false).'" alt="'.$home.'" title="'.$home.'">'.$home.'</a>';
		if(count($dirs) > 0){		
			if(in_array($page->page_type,array('page','post'))){		
				for($i=0;$i < count($dirs);$i++){
					$dir = $dirs[$i];
					$pg = get_page($dir,array('ID','title'));
					if($pg->title!=''){
						$l.=($l!=''? $separator :'').'<a class="pieces" href="'.the_permalink($pg->ID,false).'">'.$pg->title.'</a>';
					}
				}
			}elseif($page->page_type == 'component'){
				for($i=0;$i < count($dirs);$i++){
					$dir = $dirs[$i];
					$cat = get_category($dir,array('ID','title'));
					if($cat->title!=''){
						$l.=($l!=''? $separator : '').'<a class="pieces" href="'.category_url($cat->ID,false).'">'.$cat->title.'</a>';
					}
				}			
			}		
		}
		$meta = (object) unserialize($page->meta);
		if($meta->item_product_number!=''){
			$page->title = $page->title.' [#'.$meta->item_product_number.']';
		}
		$l.=($l!=''? $separator:' > ').'<span class="pieces">'.$page->title.'</span>';
		echo '<div id="crumbs">'.$l.'</div>';
	}
}

/* Search Hook */
function search_hook(){	
	$number_of_posts = apply_filters('number_of_posts',6);
	
	$cnt1 = jcms_db_get_row("SELECT COUNT(ID) AS _count FROM ".$GLOBALS['page_tbl_name']." WHERE page_type='post'")->_count;
	$cnt2 = jcms_db_get_row("SELECT COUNT(ID) AS _count FROM #_store_product_categories WHERE cat_status='active'")->_count;
	$cnt3 = jcms_db_get_row("SELECT COUNT(ID) AS _count FROM #_store_product_info WHERE item_status='active'")->_count;

	$postcount = ($cnt1 + $cnt2 + $cnt3);
	$postcount = apply_filters('get_all_post_count',$postcount);
	if($postcount > 0){
		
		$s = rawurldecode(strtolower($_REQUEST['s']));
		$f = 
		$sql = "(SELECT ID, slug, title, content,page_type FROM ".$GLOBALS['page_tbl_name']." WHERE  title LIKE '%".mysql_escape_string($s)."%' OR content LIKE '%".mysql_escape_string($s)."%' OR meta  LIKE '%:\"".mysql_escape_string($s)."\";%' AND status='published') UNION 
				(SELECT ID,cat_slug AS slug, cat_title AS title, cat_description AS content,CONCAT('categories') AS page_type FROM #_store_product_categories WHERE cat_title LIKE '%".mysql_escape_string($s)."%' OR cat_description LIKE '%".mysql_escape_string($s)."%' OR cat_meta  LIKE '%:\"".mysql_escape_string($s)."\";%') UNION 
				(SELECT ID,item_slug AS slug, item_name AS title, item_description AS content,CONCAT('products') AS page_type FROM #_store_product_info WHERE item_name LIKE '%".mysql_escape_string($s)."%' OR item_description LIKE '%".mysql_escape_string($s)."%' OR meta LIKE '%:\"".mysql_escape_string($s)."\";%')";
		$url = get_siteinfo('url',false).'/search'.($_REQUEST['s'] !='' ? '?s='.$_REQUEST['s'] : '');
		$search = jcms_db_pagination($sql,$postcount,15,$url);
		return $search->results;
	}else{
		return NULL;
	}
}
add_filter('search_posts','search_hook');

/* Meta information */
/*function the_metaa(){
	if(get_option('site_description')!=''){
		echo '<meta name="description" content="'.get_option('site_description').'"/>'."\n";
	}
	if(get_option('site_keywords')!=''){
		echo '<meta name="keywords" content="'.get_option('site_keywords').'"/>'."\n";
	}	
}*/

/* content break */
function content_break($content,$more_text = '',$all = false,$echo = true){
	$cnt = '';
	// <!-- pagebreak -->
	if($all){
		$cnt = $content;
	}else{
		$cnt = $content;
		
		$cnt = str_replace('<!-- pagebreak -->','[pb]',$cnt);
		$cnt = str_replace('</p>','[/p]',$cnt);
		$cnt = str_replace('<p>','',$cnt);
		$cnt = substr($cnt,0,strrpos($cnt,'[pb]'));
		$cnt = str_replace('[/p]','</p><p>',$cnt);
		$cnt = '<p>'.trim($cnt).$more_text.'</p>';
	}
	
	if($echo){
		echo $cnt;
	}else{
		return $cnt;
	}
}
/* Featured Page */
function featured_page(){
	$sql = "(SELECT pg.ID,pg.meta,pg.page_type FROM #_pages AS pg WHERE pg.status='published' AND meta LIKE '%featured_page_yes%') UNION
			(SELECT ID,meta,CONCAT('products') AS page_type FROM #_store_product_info WHERE item_status='active' AND meta LIKE '%featured_page_yes%') UNION 
			(SELECT ID,cat_meta AS meta,CONCAT('categories') AS page_type FROM #_store_product_categories WHERE cat_status='active' AND cat_meta LIKE '%featured_page_yes%')";
	$fpg = jcms_db_get_rows($sql,true);
	$fp = '';
	$fp2 = array();
	$fp3 = array();
	for($i=0;$i <= (count($fpg) - 1);$i++){
		$_fpg = (object) unserialize($fpg[$i]->meta);
		if($_fpg->featured_page['image']!=''){
			$custom = '';
			if($_fpg->featured_page['custom'] !=''){
				$custom = stripslashes($_fpg->featured_page['custom']);
				$custom = html_entity_decode($custom);
				preg_match_all('|{siteinfo=(.*)}|U',$custom,$c, PREG_PATTERN_ORDER);
				for($a=0;$a <= count($c[1]);$a++){
					$custom = str_replace('{siteinfo='.$c[1][$a].'}',get_siteinfo($c[1][$a],false),$custom);
				}
				if(in_array($fpg[$i]->page_type,array('page','post'))){
					$custom = str_replace(array('{this_link}','{the_link}','{this_url}','{the_url}'),the_permalink($fpg[$i]->ID,false),$custom);
				}
				if($fpg[$i]->page_type == 'products'){
					$custom = str_replace(array('{product_link}','{product_url}'),item_url($fpg[$i]->ID,false),$custom);
				}
				if($fpg[$i]->page_type == 'categories'){
					$custom = str_replace(array('{category_link}','{category_url}'),category_url($fpg[$i]->ID,false),$custom);
				}
			}
			$fp2[($_fpg->featured_page['order'] > 0 ? intval($_fpg->featured_page['order']) : $i.'999')] = array('ID' => $fpg[$i]->ID,'type' => $fpg[$i]->page_type,'image' => $_fpg->featured_page['image'],'custom' => $custom);
		}
	}
	ksort($fp2);
	$idx = 0;
	foreach($fp2 as $key => $value){
		$fp3[$idx] = $value;
		$idx++;
	}
	if(get_option('featured_page') == 'yes'){
		for($i=0;$i <= (count($fp3) - 1);$i++){
			$fp.='<div id="fp'.$fp3[$i]['ID'].'" class="'.$fp3[$i]['type'].' slide'.($i == 0 ? ' slide_active' : '').'"><img src="'.$fp3[$i]['image'].'" />'.($fp3[$i]['custom'] !='' ? '<div class="custom">'.html_entity_decode($fp3[$i]['custom']).'</div>' : '').'</div>'."\n";
		}		
	}else{
		$i = rand(0,(count($fp3) - 1));
		$fp.='<div id="fp'.$fp3[$i]['ID'].'" class="'.$fp3[$i]['type'].' slide slide_active"><img src="'.$fp3[$i]['image'].'" />'.($fp3[$i]['custom'] !='' ? '<div class="custom">'.html_entity_decode($fp3[$i]['custom']).'</div>' : '').'</div>'."\n";	
	}

	if($fp!=''){
		echo '<div id="slideshow">'.$fp.'</div>';
	}else{
		echo '
		<div id="slideshow" style="background-image:url(\''.get_siteinfo('template_directory',false).'/images/featured.jpg\');">
			<a class="learn-more" href="'.the_permalink('about-us').'">LEARN MORE</a>
		</div>        
        ';
	}
}
function relative_date($time){
	$today = strtotime(date('M j, Y'));
	$reldays = ($time - $today)/86400;
	if($reldays >= 0 && $reldays < 1) {
		return 'Today';
	}else if ($reldays >= 1 && $reldays < 2) {
		return 'Tomorrow';
	}else if ($reldays >= -1 && $reldays < 0) {
		return 'Yesterday';
	}
	
	if (abs($reldays) < 7){
		if($reldays > 0){
			$reldays = floor($reldays);
			return 'In ' . $reldays . ' day' . ($reldays != 1 ? 's' : '');
		}else{
			$reldays = abs(floor($reldays));
			return $reldays . ' day' . ($reldays != 1 ? 's' : '') . ' ago';
		}
	}
	
	if(abs($reldays) < 182){
		return date('l, j F',$time ? $time : time());
	}else{
		return date('l, j F, Y',$time ? $time : time());
	}
}
?>