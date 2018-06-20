<?php
if(!defined('IMPARENT')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access

/* Categories valid DB fields */
function _category_db_fields(){
	$db_fields = array(
		'ID',
		'cat_slug',
		'cat_title',
		'cat_description',
		'cat_excerpt',
		'cat_parent',
		'cat_image_main',
		'cat_image_thumb',
		'cat_date_added',
		'cat_status',
		'cat_sort_order',
		'cat_meta'
	); 
	return $db_fields;
}
/* Add New Category */
function make_categories_fields($fields){
	$f = join(',',$fields);
	$f = str_replace('slug','cat_slug AS slug',$f);
	$f = str_replace('title','cat_title AS title',$f);
	$f = str_replace('description','cat_description AS description',$f);
	$f = str_replace('excerpt','cat_excerpt AS excerpt',$f);
	$f = str_replace('parent','cat_parent AS parent',$f);
	$f = str_replace('main','cat_image_main AS main',$f);
	$f = str_replace('thumb','cat_image_thumb AS thumb',$f);
	$f = str_replace('date_added','cat_date_added AS date_added',$f);
	$f = str_replace('status','cat_status AS status',$f);
	$f = str_replace('order','cat_sort_order AS order',$f);
	$f = str_replace('meta','cat_meta AS meta',$f);
	return $f;
}

function add_category($category_data = NULL){

	if($category_data != NULL){		
		$db_fields = _category_db_fields();
		$category_data['cat_date_added'] = date('Y-m-d h:i:s A');		
		foreach($category_data as $key => $value){
			if(in_array($key,$db_fields)){
				$data[$key] = $value;
			}				
		}
		$id = jcms_db_insert_row('#_store_product_categories',$data);
		if($id > 0){
			$category = get_category($id);
			return $category;
		}else{
			return false;
		}
	}else{
		return;
	}
}

/* Update Category */
function update_category($category_data = NULL){
	
	if($category_data != NULL){
		$db_fields = _category_db_fields();
		if($category_data['id']!=''){ // Update
			$field = '';
			$id = mysql_escape_string($category_data['id']);
	
			foreach($category_data as $key => $value){
				if(in_array($key,$db_fields)){
					$field.=(!empty($field) ? ',' : '').$key."='".mysql_escape_string($value)."'";
				}				
			}
			$q = jcms_db_query("UPDATE #_store_product_categories SET ".$field." WHERE ".(is_numeric($id) ? "ID='".$id."'" : "cat_slug='".$id."'"));
			if($q){
				$category = get_category($id);
				return $category;
			}else{
				return false;
			}
		}else{ // Add
			$category = add_category($category_data);
			return $category;			
		}
	}else{
		return;
	}
}

/* Get Category */
function get_category($id,$fields = NULL){
	
	if(is_array($fields)){		
		$f = make_categories_fields($fields);
	}else{
		$f = '*';
	}	
	$category = jcms_db_get_row("SELECT ".$f." FROM #_store_product_categories WHERE ".(is_numeric($id) ? "ID='".$id."'" : "cat_slug='".$id."'"));
	return $category;
}


/* Get Categories */
function get_categories($category = '',$fields = NULL){	
	if(is_array($fields)){
		$f = make_categories_fields($fields);
	}else{
		$f = '*';
	}
	$categories = jcms_db_get_rows("SELECT ".$f." FROM #_store_product_categories WHERE cat_status='active' AND cat_parent='".($category > 0 ? $category : "0")."' ORDER BY cat_sort_order ASC");
	return $categories;
}

/* Delete Category */
function delete_categories($id){
	
	$ids = array();
	if(is_array($id)){
		$ids = $id;
	}else{
		$ids[] = $id;
	}
	
	$_ids = "'".join("','",$ids)."'";		
	
	// Categories
	jcms_db_query("UPDATE #_store_product_categories SET cat_parent='0' WHERE cat_parent IN (".$_ids.")");

	// Product
	jcms_db_query("UPDATE #_store_product_info SET item_category='0' WHERE item_category IN (".$_ids.")");
	
	jcms_db_query("DELETE FROM #_store_product_categories WHERE ID IN (".$_ids.")");
	
	$category_category = jcms_db_get_row("SELECT COUNT(ID) AS _count FROM #_store_product_categories WHERE ID IN (".$_ids.")")->_count;
	if($category_category > 0){
		return false;
	}else{
		return true;
	}
}

/* Check if category exists */
function is_category_exists($id){
	$category = jcms_db_get_row("SELECT TRUE AS isfound FROM #_store_product_categories WHERE ".(is_numeric($id) ? "ID='".$id."'" : "cat_slug='".$id."'"));
	if($category->isfound){
		return true;
	}else{
		return false;
	}
}
/* Check if there is a child categories*/
function have_child_category($id){
	if(count_child_category($id) > 0){
		return true;
	}else{
		return false;
	}
}

/* Count child categories*/
function count_child_category($id,$status = 'active'){
	global $tbl_store_categories;
	
	$children_count = jcms_db_get_row("SELECT COUNT(ID) AS _count FROM #_store_product_categories WHERE cat_parent='".$id."' AND cat_status='".$status."'")->_count;
	return $children_count;

}

/* Count child products */
function count_child_products($id,$status = 'active'){
	
	$children_count = jcms_db_get_row("SELECT COUNT(ID) AS _count FROM #_store_product_info WHERE item_category='".$id."' AND item_status='".$status."'")->_count;
	return $children_count;

}

function category_url($id,$echo = true){
	global $config;
	if(is_category_exists($id)){
		$category = get_category($id,array('ID','slug','parent'));	
		$p = $category->slug;	
		if($category->parent > 0){
			$dir = array();
			$cat_parent = $category->parent;
			do{
				$parent = get_category($cat_parent,array('ID','slug','parent'));	
				$dir[] = $parent->slug;
				$cat_parent = $parent->parent;
			} while ($cat_parent > 0);
			krsort($dir);
			$p = implode('/',$dir).'/'.$p;
		}
		$url = get_option('site_url').'/'.$p;
		if($echo){
			echo $url;
		}else{
			return $url;
		}
	}else{
		return '';
	}
}

/* Generate Category Lists */
$cat_level = 1;
function make_lists($id,$level = 1,$parent = 0,$sub_count = false,$lvl1_header = '',$echo = true){
	global $cat_level;
	$pathinfo = get_pathinfo();
	$categories = get_categories($parent,array('ID','slug','title','parent'));
	$list = '';	

	$cat_level  = $cat_level  + 1;
	$cat_count = count($categories);
	for($i=0;$i < $cat_count;$i++){
		$category = $categories[$i];
		
		$class = '';
		//if($parent == 0 && $i == ($cat_count - 1)){
		if($i == ($cat_count - 1)){
			$class = 'last';
		//}elseif($parent == 0 && $i == 0){
		}elseif($i == 0){
			$class = 'first';
		}		
		if(in_array($category->slug,$pathinfo->dirs)){
			$class.=($class!=''?' ' : '').'active';
		}
		$title = ($lvl1_header!=''?'<'.$lvl1_header.'>':'').'<a href="'.category_url($category->ID,false).'">'.$category->title.($sub_count ? ' <span class="sub-count">('.count_child_category($category->ID).')</span>' : '').'</a>'.($lvl1_header!=''?'</'.$lvl1_header.'>':'');
		if($level > 1){
			if(have_child_category($category->ID)){
				if($cat_level  <= $level){
					$list.='<li id="category-'.$category->ID.'" class="'.$class.'">'.$title."\n";
					$list.=make_lists('sub-category-'.$category->ID,$level,$category->ID,$sub_count,'',false);
					$list.='</li>'."\n";				
				}
			}else{
				// Main Level
				$list.='<li id="category-'.$category->ID.'" class="'.$class.'">'.$title.'</li>'."\n";
			}		
		}else{
			// Main Level
			$list.='<li id="category-'.$category->ID.'" class="'.$class.'">'.$title.'</li>'."\n";
		}
	}
	if($list!=''){
		$list = '<ul'.($parent > 0 ? ' id="'.$id.'" class="sub-category level-'.$cat_level.'"' : ' id="'.$id.'" class="main-category"').'>'.$list.'</ul>';		
		$cat_level  = 1;
		if($echo){		
			echo $list;
		}else{
			return $list;
		}
	}else{
		return;
	}
}

/* Generate Category Selection Lists */
$cat_level = 1;
function make_category_select_lists($level = 1,$parent = 0,$current_selection = '',$echo = true){
	global $cat_level,$level_indent;
	$categories = get_categories($parent,array('ID','title','parent'));
	$list = '';
	if($cat_level > 1){
		$indent = 10;
	}else{
		$indent = 5;
	}	
	$cat_level  = $cat_level  + 1;
	$cat_count = count($categories);
	for($i=0;$i < $cat_count;$i++){
		$category = $categories[$i];
		if($level > 1){
			if(have_child_category($category->ID)){
				if($cat_level  <= $level){
					$list.='<option'.($current_selection == $category->ID ? ' selected="selected"' : '').($parent > 0 ? ' style="padding-left:'.($cat_level == 1 ? ($indent * 2) : ($indent * $cat_level)).'px;"' : '').' value="'.$category->ID.'">'.$category->title.'</option>'."\n";
					$list.=make_category_select_lists($level,$category->ID,$current_selection,false);
				}
			}else{
				$list.='<option'.($current_selection == $category->ID ? ' selected="selected"' : '').($parent > 0 ? ' style="padding-left:'.($cat_level == 1 ? ($indent * 2) : ($indent * $cat_level)).'px;"' : '').' value="'.$category->ID.'">'.$category->title.'</option>'."\n";
			}		
		}else{
			$list.='<option'.($current_selection == $category->ID ? ' selected="selected"' : '').($parent > 0 ? ' style="padding-left:'.($cat_level == 1 ? ($indent * 2) : ($indent * $cat_level)).'px;"' : '').' value="'.$category->ID.'">'.$category->title.'</option>'."\n";
		}
	}
	if($list!=''){
		$cat_level  = 1;
		if($echo){		
			echo $list;
		}else{
			return $list;
		}
	}else{
		return;
	}
}

/* Product Category */
if(!jcms_db_is_table_exists('#_store_product_categories')){	
	$structure = "ID BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY, cat_slug VARCHAR(100) NOT NULL, cat_title VARCHAR(100) NOT NULL, cat_description LONGTEXT NOT NULL, cat_excerpt LONGTEXT NOT NULL, cat_parent BIGINT(20) NOT NULL DEFAULT '0', cat_image_main LONGTEXT NOT NULL, cat_image_thumb LONGTEXT NOT NULL, cat_date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, cat_status VARCHAR(20) NOT NULL DEFAULT 'active', cat_sort_order VARCHAR(20) NOT NULL, cat_meta LONGTEXT NOT NULL, UNIQUE (cat_slug)";
	jcms_db_create_table('#_store_product_categories',$structure);
}
// Pre-install
/*if(file_exists(GBL_ROOT.'/store_product_categories_install.php')){
	include(GBL_ROOT.'/store_product_categories_install.php');
}*/


?>