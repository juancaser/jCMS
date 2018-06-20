<?php
if(!defined('IMPARENT')){exit();} // No direct access

/* JCMS Page Functions */
$page = array(); // Current page container
$post = NULL; // Current post container
$posts = NULL; // Current post container
$post_index = 0; // Current posts index
$post_count = 0; // Post Count
$post_oe = ''; // Post Count


/* Page valid DB fields */
function _page_db_fields(){
	$db_fields = array(
		'ID',
		'slug',
		'title',
		'content',
		'page_type',
		'author',
		'parent_page',
		'status',
		'date_created',
		'date_modified',
		'menu_order',
		'meta',
		'page_key'
	); 
	return $db_fields;
}

/* Add New Category */
function make_page_fields($fields){
	$f = $fields;
	if(is_array($fields)){$f = join(',',$f);}	
	$f = str_replace('ID','pg.ID',$f);
	$f = str_replace('slug','pg.slug',$f);
	$f = str_replace('title','pg.title',$f);
	$f = str_replace('content','pg.content',$f);
	$f = str_replace('page_type','pg.page_type',$f);
	$f = str_replace('author','pg.author',$f);
	$f = str_replace('parent_page','pg.parent_page',$f);
	$f = str_replace('status','pg.status',$f);
	$f = str_replace('date_created','pg.date_created',$f);
	$f = str_replace('date_modified','pg.date_modified',$f);
	$f = str_replace('menu_order','pg.menu_order',$f);
	$f = str_replace('meta','pg.meta',$f);
	$f = str_replace('page_key','pg.page_key',$f);
	return $f;
}

/* Add New Page */
function add_page($page_data = NULL){
	if($page_data != NULL){		
		$db_fields = _page_db_fields();
		$page_data['date_created'] = date('Y-m-d h:i:s A');		
		foreach($page_data as $key => $value){
			if(in_array($key,$db_fields)){
				$data[$key] = $value;
			}				
		}
		$id = jcms_db_insert_row($GLOBALS['page_tbl_name'],$data);
		if($id > 0){
			$page = get_page($id);
			return $page;
		}else{
			return false;
		}
	}else{
		return;
	}
}

/* Update Page */
function update_page($page_data = NULL){
	if($page_data != NULL){
		$db_fields = _page_db_fields();
		if($page_data['id']!=''){ // Update
			$field = '';
			$id = mysql_escape_string($page_data['id']);
	
			foreach($page_data as $key => $value){
				if(in_array($key,$db_fields)){
					$field.=(!empty($field) ? ',' : '').$key."='".mysql_escape_string($value)."'";
				}				
			}
			$q = jcms_db_query("UPDATE ".$GLOBALS['page_tbl_name']." SET ".$field." WHERE ".(is_numeric($id) ? "ID='".$id."'" : "slug='".$id."'"));
			if($q){
				$page = get_page($id);
				return $page;
			}else{
				return false;
			}
		}else{ // Add
			$page = add_page($page_data);
			return $page;			
		}
	}else{
		return;
	}
}

/* Fetch Single Page */
function get_page($id,$fields = ''){
	if($fields){		
		$f = make_page_fields($fields);
	}else{
		$f = 'pg.*';
	}
	$page = jcms_db_get_row("SELECT ".$f." FROM ".$GLOBALS['page_tbl_name']." AS pg WHERE ".(is_numeric($id) ? "pg.ID='".$id."'" : "pg.slug='".$id."'"));
	return $page;
}

/* Fetch All Pages */
function get_pages(){
	$pages = jcms_db_get_rows("SELECT pg.* FROM ".$GLOBALS['page_tbl_name']." AS pg");
	return $pages;
}

/* Fetch All Pages by meta */
function get_pages_by_meta($key,$value){
	$meta = ':"'.$key.'";';
	$p = jcms_db_get_rows("SELECT * FROM ".$GLOBALS['page_tbl_name']." AS p WHERE meta LIKE '%".$meta."%' ORDER BY menu_order ASC");
	if(count($p) > 0){
		$pages = array();
		for($i=0;$i < count($p);$i++){
			$meta = unserialize($p[$i]->meta);
			if($meta[$key] == $value){
				$pages[] = $p[$i];
			}		
		}
		if(count($pages) > 0){
			return $pages;
		}
	}else{
		return;
	}
}


/* Delete Page */
function delete_page($id){
	if(is_array($id)){
		$ids = "'".join("','",$id)."'";
		jcms_db_query("DELETE FROM ".$GLOBALS['page_tbl_name']." WHERE ID IN (".$ids.") OR slug IN (".$ids.")");
	}else{
		jcms_db_query("DELETE FROM ".$GLOBALS['page_tbl_name']." WHERE ".(is_numeric($id) ? "ID='".$id."'" : "slug='".$id."'"));
		if(is_page_exists($id)){
			return false;
		}else{
			return true;
		}
	}	
}

/* Trashed Page */
function trashed_page($id,$trash = true){
	$ids = "'".join("','",$id)."'";
	$trashed_item = jcms_db_get_rows("SELECT ID,status,page_key FROM ".$GLOBALS['page_tbl_name']." AS pg WHERE pg.ID IN (".$ids.") OR pg.slug IN (".$ids.")");
	for($i=0;$i < count($trashed_item);$i++){
		$item = $trashed_item[$i];		
		if($trash){
			$status = 'trash';
			$page_key = $item->status;
		}else{
			$status = ($item->page_key !='' ? $item->page_key : 'published');
			$page_key = ($item->page_key !='' ? $item->status : '');
		}
		if(has_child_pages($item->ID)){
			jcms_db_query("UPDATE ".$GLOBALS['page_tbl_name']." SET parent_page='0',page_key='' WHERE parent_page='".$item->ID."'");
		}
		jcms_db_query("UPDATE ".$GLOBALS['page_tbl_name']." SET status='".$status."',page_key='".$page_key."' WHERE ID='".$item->ID."'");
	}
	if(is_page_trashed($id)){
		return false;
	}else{
		return true;
	}
}

/* Fetch Page Relatives */
function get_page_relatives($parent = 0,$fields = '',$type = 'page'){
	if(is_array($fields)){
		$f = make_page_fields($fields);
	}else{
		$f = 'pg.*';
	}
	$pages = jcms_db_get_rows("SELECT ".$f." FROM ".$GLOBALS['page_tbl_name']." AS pg WHERE pg.parent_page='".($parent > 0 ? $parent : '0')."' AND page_type='".$type."' ORDER BY pg.title ASC");
	return $pages;
}

/* Check if page has child page*/
function has_child_pages($id,$type = 'page'){
	$page = jcms_db_get_row("SELECT COUNT(*) AS _count FROM ".$GLOBALS['page_tbl_name']." WHERE parent_page='".$id."' AND page_type='".$type."'");
	if($page->_count > 0){
		return true;
	}else{
		return false;
	}
}

/* Check if Page exists */
function is_page_exists($id){
	if(is_array($id)){
		$ids = "'".join("','",$id)."'";
		$page = jcms_db_get_row("SELECT COUNT(*) AS page_count FROM ".$GLOBALS['page_tbl_name']." WHERE  pg.ID IN (".$ids.") OR pg.slug IN (".$ids.")");
		if($page->page_count > 0){
			return true;
		}else{
			return false;
		}

	}else{
		$page = jcms_db_get_row("SELECT TRUE AS isfound FROM ".$GLOBALS['page_tbl_name']." WHERE ".(is_numeric($id) ? "ID='".$id."'" : "slug='".$id."'"));
		if($page->isfound){
			return true;
		}else{
			return false;
		}
	}
}

/* Check if Page is trashed */
function is_page_trashed($id,$trash = true){
	$status = 'trash';
	if(!$trash){
		$status = 'published';
	}
	if(is_array($id)){
		$ids = "'".join("','",$id)."'";
		$page = jcms_db_get_row("SELECT COUNT(*) AS page_count FROM ".$GLOBALS['page_tbl_name']." WHERE  ID IN (".$ids.") OR pg.slug IN (".$ids.") AND status='".$status."'");
		if($page->page_count > 0){
			return true;
		}else{
			return false;
		}

	}else{
		$page = jcms_db_get_row("SELECT TRUE AS isfound FROM ".$GLOBALS['page_tbl_name']." WHERE ".(is_numeric($id) ? "ID='".$id."'" : "slug='".$id."'")." AND status='".$status."'");
		if($page->isfound){
			return true;
		}else{
			return false;
		}
	}
}

/* Generate Pernalink */
function _generate_page_permalink($id){
	global $config;
	if($config->mod_rewrite){ // ON	
		
		$cp = jcms_db_get_row("SELECT ID,slug,page_type,parent_page FROM ".$GLOBALS['page_tbl_name']." WHERE ".(is_numeric($id) ? "ID='".$id."'" : "slug='".$id."'")." AND status='published'");		
		
		$filename = $cp->slug.($cp->page_type == 'post' ? '.html' : '');
		
		if($cp->parent_page != '0'){
			$dir = array();
			$parent_page = $cp->parent_page;
			do{
				$parent = jcms_db_get_row("SELECT ID,slug,parent_page FROM ".$GLOBALS['page_tbl_name']." WHERE ID='".$parent_page."' AND status='published' AND page_type='page'");

				$dir[] = $parent->slug;
				$parent_page = $parent->parent_page;
			} while ($parent_page > 0);
			krsort($dir);
			$filename = implode('/',$dir).'/'.$filename;
		}
	
		return $config->site_url.'/'.$filename;
		
	}elseif(!$config->mod_rewrite){ // OFF
		return;
	}
}

/* Page Constructor
 * Usually used if you want to create a page on-the-fly without storing
 * it on database
 **/
function _page_constructor($args){
	global $page;
	$default = array(
			'ID' => '-1', // Let's keep it out of the valid loop
			'slug' => '',
			'component' => '', // Only works if the page_type is component
			'title' => '',
			'content' => '',
			'page_type' => 'page',
			'author' => '0',
			'parent_page' => '0',
			'status' => 'published',
			'date_created' => date('Y-m-d h:i:s A'),
			'date_modified' => date('Y-m-d h:i:s A'),
			'menu_order' => '0',
			'meta' => ''
		);
	$args = fill_arguments($default,$args);
	$page = (object) $args;
}

/* Current Page Initialization */
function _current_page_init(){
	global $page,$pathinfo;
	if(is_page_exists($pathinfo->filename)){
		$page = get_page($pathinfo->filename);
		$u = parse_url($_SERVER['REQUEST_URI']);
		if(get_siteinfo('url',false).$u['path'] != _generate_page_permalink($page->ID)){
			$page = array();
		}
	}
}

$_level = 1;
function make_select_lists_page2($level = 1,$parent = 0,$current_selection = '',$echo = true){
	global $_level,$level_indent;
	$pages = get_page_relatives($parent,array('ID','title','parent_page'));
	$page_count = count($pages);
	$list = '';
	if($_level > 1){
		$indent = 10;
	}else{
		$indent = 5;
	}	
	$_level  = $_level  + 1;

	for($i=0;$i < $page_count;$i++){
		$page = $pages[$i];
		if($level > 1){
			if(has_child_pages($page->ID)){
				if($cat_level  <= $level){
					$list.='<option'.($current_selection == $page->ID ? ' selected="selected"' : '').($parent > 0 ? ' style="padding-left:'.($_level == 1 ? ($indent * 2) : ($indent * $_level)).'px;"' : '').' value="'.$page->ID.'">'.$page->title.'</option>'."\n";
					$list.=make_category_select_lists($level,$page->ID,$current_selection,false);
				}
			}else{
				$list.='<option'.($current_selection == $page->ID ? ' selected="selected"' : '').($parent > 0 ? ' style="padding-left:'.($_level == 1 ? ($indent * 2) : ($indent * $_level)).'px;"' : '').' value="'.$page->ID.'">'.$page->title.'</option>'."\n";
			}		
		}else{
			$list.='<option'.($current_selection == $page->ID ? ' selected="selected"' : '').($parent > 0 ? ' style="padding-left:'.($_level == 1 ? ($indent * 2) : ($indent * $_level)).'px;"' : '').' value="'.$page->ID.'">'.$page->title.'</option>'."\n";
		}
	}
	if($list!=''){
		$_level  = 1;
		if($echo){		
			echo $list;
		}else{
			return $list;
		}
	}else{
		return;
	}
}


function make_select_lists_page($level = 1,$parent = 0,$current_selection = '',$echo = true){
	global $_level;
	$list = '';
	
	$pages = get_page_relatives($parent,array('ID','title','parent_page'));
	$page_count = count($pages);
	
	$padding = ($_level == 1 ? 10 : (10 * $_level));
	
	for($i=0;$i < $page_count;$i++){
		$page = $pages[$i];
		$list.='<option'.($current_selection == $page->ID ? ' selected="selected"' : '').($parent > 0 ? ' style="padding-left:'.$padding.'px;"' : '').' value="'.$page->ID.'">'.$page->title.'</option>'."\n";
		if(($level > 1) && has_child_pages($page->ID) && ($_level <= $level)){
			$_level  = $_level  + 1;
			$list.=make_select_lists_page($level,$page->ID,$current_selection,false);
		}
	}
	if($list!=''){
		$_level  = 1;
		if($echo){		
			echo $list;
		}else{
			return $list;
		}
	}else{
		return;
	}
}

add_action('jcms_init','_current_page_init','high');
add_action('jcms_init','addons','high');
?>