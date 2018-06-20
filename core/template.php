<?php
if(!defined('IMPARENT')){exit();} // No direct access

/* JCMS Template Functions */

/* Locate Template */
function locate_templates($template_names,$type = 'page',$load = false,$require_once = true){
	if(!is_array($template_names)){
		return '';
	}
	
	$template = '';
	foreach($template_names as $template_name){
		if(!$template_name){
			continue;
		}
		
		if(file_exists(GBL_ROOT_TEMPLATE.'/html/'.$type.'/'.$template_name)){
			$template = GBL_ROOT_TEMPLATE.'/html/'.$type.'/'.$template_name;
			break;
		}elseif(file_exists(GBL_ROOT_TEMPLATE.'/'.$template_name)){
			$template = GBL_ROOT_TEMPLATE.'/'.$template_name;
			break;
		}
	}
	
	if($template == '' && file_exists(GBL_ROOT_TEMPLATE.'/'.$type.'.php')){
		$template = GBL_ROOT_TEMPLATE.'/'.$type.'.php';
	}
	
	$template = apply_filters(__FUNCTION__,$template);
	
	if($load && $template !=''){
		load_template($template,$require_once,$type);
	}
	return $template;
}

/* Load Template */
function load_template($template,$require_once = true,$type){
	global $jcms_db,$config,$post,$page,$pathinfo;
	/*
	if(get_option('site_caching') == 'yes' && in_array($type,array('home','page','post'))){
		$name = ($type == 'home' ? 'home' : $type.'_'.$page->ID.'_'.$page->title);
		
		$cached_page = GBL_ROOT_CONTENT.'/cache/page/'.($type == 'home' ? 'home' : $type).'-'.md5('cache-'.$name);
		//if($_REQUEST['action'] == 'clear_cache' && file_exists($cached_page)){
		if(file_exists($cached_page)){
			if(date('mdY',filemtime($cached_page)) != date('mdY')){
				unlink($cached_page);
			}elseif($_REQUEST['clear_cache'] == 'yes'){
			}
		}
		
		if(!file_exists($cached_page)){
			ob_start();
			include($template);
			$txtbuffer = ob_get_clean();
			if(!function_exists('file_put_contents')){
				file_put_contents($cached_page,$txtbuffer);
			}else{
				if($handle = fopen($cached_page, 'a')){
					fwrite($handle,$txtbuffer);
				}
				fclose($handle);
			}
		}
		
		if(!function_exists('file_get_contents')){
			echo file_get_contents($cached_page);
		}else{
			$handle = fopen($cached_page, 'r');
			$contents = fread($handle, filesize($cached_page));
			fclose($handle);
			echo $contents;
		}
	}else{
	*/
		if($require_once){
			require_once($template);
		}else{
			require($template);
		}
	/*}*/
}

/* Header */
function get_header(){
	global $page;	
	
	$template = locate_templates(apply_filters(__FUNCTION__,array($page->slug.'.php','header-'.$page->ID.'.php')),'header',true);
	if($template == ''){
		// If header template doesn't exists, return Internal Server Error
		header('HTTP/1.1 500 Internal Server Error');
		exit();
	}
}

/* Footer */
function get_footer(){
	global $page;
	
	$template = locate_templates(apply_filters(__FUNCTION__,array($page->slug.'.php','footer-'.$page->ID.'.php')),'footer',true);
	
	if($template == ''){
		// If header template doesn't exists, return Internal Server Error
		header('HTTP/1.1 500 Internal Server Error');
		exit();
	}
}

/* Get Homepage Template */
function get_home_template(){
	global $page;
	
	$template = locate_templates(apply_filters(__FUNCTION__,array('homepage.php','frontpage.php','default.php')),'home',true);	
	
	if($template == ''){
		// If header template doesn't exists, return Internal Server Error
		header('HTTP/1.1 500 Internal Server Error');
		exit();
	}
}


/* Get Post Template */
function get_post_template(){
	global $page;
	
	$template = locate_templates(apply_filters(__FUNCTION__,array($page->slug.'.php','post-'.$page->ID.'.php')),'post',true);	
	
	if($template == ''){
		// If header template doesn't exists, return Internal Server Error
		header('HTTP/1.1 500 Internal Server Error');
		exit();
	}
}

/* Get Page Template */
function get_page_template(){
	global $page;
	
	$template = locate_templates(apply_filters(__FUNCTION__,array($page->slug.'.php','page-'.$page->ID.'.php')),'page',true);
	
	if($template == ''){
		// If header template doesn't exists, return Internal Server Error
		header('HTTP/1.1 500 Internal Server Error');
		exit();
	}
}

/* Get Search Template */
function get_search_template(){
	global $page;
	if(file_exists(GBL_ROOT_TEMPLATE.'/search.php')){
		$page = (object) array(
				'ID' => '-1',
				'title' => 'Search for "'.$_REQUEST['s'].'"'
			);
		load_template(GBL_ROOT_TEMPLATE.'/search.php',true,'search');
	}else{
		// If header template doesn't exists, return Internal Server Error
		header('HTTP/1.1 500 Internal Server Error');
		exit();
	}
}

/* Get Component Template */
function get_component_template(){
	global $page;
	if(file_exists(GBL_ROOT_TEMPLATE.'/component/'.$page->component.'/'.$page->slug.'.php')){
		load_template(GBL_ROOT_TEMPLATE.'/component/'.$page->component.'/'.$page->slug.'.php',true,'component');
	}else{
		// If header template doesn't exists, return Internal Server Error
		header('HTTP/1.1 500 Internal Server Error');
		exit();
	}
}

/* Get user Template */
function get_user_template(){
	global $pathinfo;
	if(file_exists(GBL_ROOT_TEMPLATE.'/component/user/'.$pathinfo->filename.'.php')){
		if(file_exists(GBL_ROOT_TEMPLATE.'/component/user/functions.php')){
			load_template(GBL_ROOT_TEMPLATE.'/component/user/functions.php',true,'user');
		}		
		load_template(GBL_ROOT_TEMPLATE.'/component/user/'.$pathinfo->filename.'.php',true,'user');
	}else{
		// If header template doesn't exists, return Internal Server Error
		header('HTTP/1.1 500 Internal Server Error');
		exit();
	}
}

/* Get 404 Template */
function get_404_template(){
	global $page;
	if(file_exists(GBL_ROOT_TEMPLATE.'/404.php')){
		header('HTTP/1.1 404 Not Found');
		$page = (object) array(
				'ID' => '-1',
				'title' => 'Page not found'
			);
		load_template(GBL_ROOT_TEMPLATE.'/404.php',true,'404');
	}else{
		// If header template doesn't exists, return Internal Server Error
		header('HTTP/1.1 500 Internal Server Error');
		exit();
	}
}

/* Check Current Page */
function page_is($type){
	global $pathinfo,$page,$config;	

	switch($type){
		case 'home':
			if(($pathinfo->filename == '' || $pathinfo->script == 'index.php') && $_REQUEST['p'] == '')
				return true;
			break;
			
		case 'page':
			if($page->page_type == 'page')
				return true;
			break;
			
		case 'post':
			if($page->page_type == 'post')
				return true;
			break;

		case 'component':
			if($page->page_type == 'component')
				return true;
			break;

		case 'search':
			if($pathinfo->dirname == '/search'  || ($pathinfo->script == 'index.php' && $_REQUEST['p'] == 'search'))
				return true;			
			break;	
		default:
			return false;
			break;
	}
}


/* Posts Functions */

/* Main Title */
function title($args = NULL){
	global $config,$page;
	$default = array(
			'default' => '',
			'title' =>'',
			'separator' => ' - ',
			'separator_position' => 'right',
			'echo' => '1'
		);
	
	$args = fill_arguments($default,$args);
	
	$args['separator'] = str_replace('[amp]','&',$args['separator']);
	$args['separator'] = str_replace('[spc]',' ',$args['separator']);

	$title = '';
	$title = ($args['separator_position'] == 'left' ? $args['separator'] : '');
	if($args['title']!='' || $page->title!=''){
		$title.= ($args['title'] !='' ? $args['title'] : $page->title);
	}else{
		$title = ($args['default'] !='' ? $args['default'] : $page->title);
	}
	$title.= ($args['separator_position'] == 'right' ? $args['separator'] : '');
	
	$title = apply_filters(__FUNCTION__,$title);
										 
	if($args['echo'] == '1'){
		echo $title;
	}else{
		return $title;
	}	
}

/* Get All Posts */
function get_all_posts(){
	global $page,$posts,$post_count;
	
	$number_of_posts = apply_filters('number_of_posts',6);		
	$postcount = jcms_db_get_rows("SELECT COUNT(ID) AS post_count FROM ".$GLOBALS['page_tbl_name']." WHERE page_type='post'");
	
	$postcount = apply_filters('get_all_post_count',$postcount[0]->post_count);
	
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
		
		if(page_is('search')){
			$s = rawurldecode(strtolower($_REQUEST['s']));
			$sql = 'SELECT pg.* FROM '.$GLOBALS['page_tbl_name'].' AS pg WHERE 
				pg.page_type IN (\'page\',\'post\') AND 
					pg.title LIKE \'%'.mysql_escape_string($s).'%\' OR 
						pg.content LIKE \'%'.mysql_escape_string($s).'%\' OR
							pg.meta LIKE \'"%'.mysql_escape_string($s).'%"\' LIMIT '.$offset.','.$rowsperpage;
			$search = jcms_db_get_rows($sql);
			$posts = apply_filters('search_posts',$search);
		}else{
			$posts = apply_filters('get_all_posts',jcms_db_get_rows('SELECT pg.* FROM '.$GLOBALS['page_tbl_name'].' AS pg LIMIT '.$offset.','.$rowsperpage));
		}
		
		return true;
		
	}else{
		$posts = NULL;
		
		return false;		
		
	}	
}

/* Get Posts */
function get_post(){
	global $page,$posts;	
	$posts[] = $page;	
}

/* Check if there is a posst - Homepage*/
function have_posts(){
	global $posts,$post_count,$post_index;

	if($posts && ($post_index <= $post_count)){
        $post_count = (count($posts) - 1);
        return true;
    }else {
        $post_count = 0;
        return false;
    }
}

/* The post */
function the_post(){
 global $posts,$post_count, $post_index,$page;
	if ($post_index > $post_count) {
		return false;
	}
	$post = setup_postdata($posts[$post_index]);
	$post_index++;			
	return $post;
}

/* Setup Post - Homepage*/
function setup_postdata($post_data){
	global $post,$post_oe,$posts;
	$post = $post_data;	
	
	if(count($posts) > 0){
		if($post_oe == ''){
			$post_oe = 'odd ';
		}elseif($post_oe == 'odd'){
			$post_oe = 'even ';
		}elseif($post_oe == 'even'){
			$post_oe = 'odd ';
		}
	}
	return $post;
}

/* Post title*/
function the_title($args = NULL){
	global $post;
	
	$default = array('echo' => '1');
	
	$args = fill_arguments($default,$args);
	
	if($post->title!=''){
	
		$title = $post->title;
		
		if($args['echo'] == '1'){
			echo $title;
		}else{
			return $title;
		}
	}
}

/* Post Content*/
function the_content($args = NULL){
	global $post;
		
	$excerpt_length = apply_filters('excerpt_length',200);
	
	$default = array(
			'more_text' => '[...]',
			'link_to_post' => '1',
			'excerpt' => '0',
			'echo' => '1'
		);
	
	$args = fill_arguments($default,$args);
	
	if($post->content!=''){
		$content = $post->content;
		$content = html_entity_decode($content);
		$content = stripslashes($content);
		$content = bb2html($content);
		
		if(!page_is('page') || !page_is('post')){			
			if($args['excerpt'] == '1'){		
				$content = strip_tags($content);
				if(strlen($content) > $excerpt_length){
					$more = $args['more_text'];
					
					if($args['link_to_post'] == '1'){
					
						$more = ' <a class="post-readmore" href="'._generate_page_permalink($post->ID).'">'.$args['more_text'].'</a>';
						
					}
					
					$content = substr($content,0,$excerpt_length).$more;
				}
			}
		}
				
		if($args['echo'] == '1'){
			echo $content;
		}else{
			return $content;
		}
	}
}

/* Get post ID */
function the_id($echo = true){
	global $post;
	
	if($echo){
		echo $post->ID;
	}else{
		return $post->ID;
	}	
}

/* Get post type */
function post_type(){
	global $post;
	return $post->page_type;

}

/* Get Post Permalink */
function the_permalink($id = '',$echo = true){
	global $post;
	if($id!=''){
		if($echo){
			echo _generate_page_permalink($id);
		}else{
			return _generate_page_permalink($id);
		}
	}else{
		if($echo){
			echo _generate_page_permalink($post->ID);
		}else{
			return _generate_page_permalink($post->ID);
		}	
	}
}

/* Post Class */
function post_class($class = '',$echo = true){
	global $post,$post_oe;
	$cls = 'class="'.$post_oe.$post->page_type.'-'.$post->ID.' '.$post->page_type.'-'.$post->slug.($class!=''?' '.$class : '').'"';
	if($echo){
		echo $cls;
	}else{
		return $cls;
	}
}


/* Header Action Callback */
function the_head(){
	do_action('the_head');
}

/* Footer Action Callback */
function the_foot(){
	do_action('the_foot');
}

/* Meta information Action Callback */
function the_meta(){
	do_action('the_meta');
}



/* Utitity*/
/* Convert all BBCodes*/
function bb2html($content){
	$temp_cont = $content;

	//Siteinfo
	$si = array('name','description','template_directory','backend_directory','home','url');
	for($i=0;$i < count($si);$i++){
		$temp_cont = str_replace('[siteinfo='.$si[$i].']',get_siteinfo($si[$i],false),$temp_cont);
	}

	//Permalink
	preg_match_all("|\\[permalink=(.*)\\]|U",$temp_cont,$pl, PREG_PATTERN_ORDER);
	for($i=0;$i < count($pl[1]);$i++){
		$temp_cont = str_replace('[permalink='.$pl[1][$i].']',the_permalink($pl[1][$i],false),$temp_cont);
	}

	return $temp_cont;
}

/* Template Initialization */
function _template_init(){
	global $page;
	$desc = '';
	$keyw = '';


	if(file_exists(GBL_ROOT_TEMPLATE.'/function.php')){ // Local Template Function,load it if it exists on template 
		require_once(GBL_ROOT_TEMPLATE.'/function.php'); // directory
	}
		
	$_page = (array)$page;
	$meta = (array)unserialize($_page['meta']);
	if(count($meta) > 0){
		$desc = ($meta['meta_description'] !='' ? $meta['meta_description'] : (get_option('site_description')!=''?get_option('site_description'):''));
		$keyw = ($meta['meta_keywords'] !='' ? $meta['meta_keywords'] : (get_option('site_keywords')!=''?get_option('site_keywords'):''));
	}
																													
	if(page_is('home')){ // Home		
		get_all_posts();
		
		// Set Page meta description/keywords		
		add_meta('type=name&type_value=description&content='.get_option('site_description'));
		add_meta('type=name&type_value=keywords&content='.get_option('site_keywords'));

		get_home_template();		
	}elseif(page_is('post')){ // Post
		get_post();
		
		// Set Page meta description/keywords		
		add_meta('type=name&type_value=description&content='.$desc);
		add_meta('type=name&type_value=keywords&content='.$keyw);
		
		get_post_template();
	}elseif(page_is('page')){ // Post
		get_post();		
		// Set Page meta description/keywords		
		add_meta('type=name&type_value=description&content='.$desc);
		add_meta('type=name&type_value=keywords&content='.$keyw);
		
		get_page_template();
	}elseif(page_is('search')){ // Search
		get_all_posts();
		get_search_template();		
	}elseif(page_is('component')){ // Component
		// Set Page meta description/keyword
		add_meta('type=name&type_value=description&content='.$desc);
		add_meta('type=name&type_value=keywords&content='.$keyw);

		get_component_template();
	}else{ // 404
		get_404_template();
	}
}
add_action('jcms_init','_template_init','high');
?>