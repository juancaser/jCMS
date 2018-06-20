<?php
if(!defined('IMPARENT')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access

/**
 * PRODUCT INFORMATION
 */

/* Items valid DB fields */
function _product_info_db_fields(){
	$db_fields = array(
		'item_slug',
		'item_product_number',
		'item_price',
		'item_name',
		'item_description',
		'item_excerpt',
		'item_category',
		'item_product_image',
		'item_product_image_thumb',
		'item_date_created',
		'item_status',
		'item_sort_order',
		'meta'
	); 
	return $db_fields;
}

function make_product_info_fields($fields){
	$f = join(',',$fields);
	$f = str_replace('slug','item_slug AS slug',$f);
	$f = str_replace('price','item_price AS price',$f);
	$f = str_replace('product_no','item_product_number AS product_no',$f);
	$f = str_replace('name','item_name AS name',$f);
	$f = str_replace('title','item_name AS title',$f);
	$f = str_replace('description','item_description AS description',$f);
	$f = str_replace('excerpt','item_excerpt AS excerpt',$f);
	$f = str_replace('item_price','item_price AS item_price',$f);
	$f = str_replace('category','item_category AS category',$f);
	$f = str_replace('product_image','item_product_image AS product_image',$f);
	$f = str_replace('thumbnail','item_product_image_thumb AS thumbnail',$f);	
	$f = str_replace('date_added','item_date_added AS date_added',$f);
	$f = str_replace('status','item_status AS status',$f);
	$f = str_replace('sort_order','item_sort_order AS sort_order',$f);
	$f = str_replace('meta','meta AS meta',$f);
	return $f;
}

/* Add New Catalog items */
function add_product_info($fields){		
	if(is_array($fields)){
		$fields['item_date_added'] = date('Y-m-d h:i:s A');
		$data = sanitize_product_info_data($fields);
		$id = jcms_db_insert_row('#_store_product_info',$data);
		if($id > 0){
			$product_info = get_product_info($id);
			return $product_info;
		}else{
			return false;
		}
	}else{
		return;
	}
}

/* Update Category */
function update_product_info($fields){
	if(is_array($fields)){
		$db_fields = _product_info_db_fields();
		
		if($fields['id']!=''){ // Update
			$field = '';
			$id = mysql_escape_string($fields['id']);
			$fields = sanitize_product_info_data($fields);
			foreach($fields as $key => $value){
				if(in_array($key,$db_fields)){
					$field.=(!empty($field) ? ',' : '').$key."='".$value."'";
				}				
			}
			$q = jcms_db_query("UPDATE #_store_product_info SET ".$field." WHERE ".(is_numeric($id) ? "ID='".$id."'" : "item_slug='".$id."'"));
			if($q){
				$product_info = get_product_info($id);
				return $product_info;
			}else{
				return false;
			}
		}else{ // Add
			$product_info = add_product_info($fields);
			return $product_info;
		}
	}else{
		return;
	}
}

/* Get Store Item */
function get_product_info($id,$fields = ''){	
	if(is_array($fields)){		
		$f = make_product_info_fields($fields);
	}else{
		$f = '*';
	}
	$product_info = jcms_db_get_row("SELECT ".$f." FROM #_store_product_info WHERE ".(is_numeric($id) ? "ID='".$id."'" : "item_slug='".$id."'"));
	return $product_info;
}


/* Get Store Items */
function get_product_infos($category = '0',$fields = ''){	
	if(is_array($fields) && $fields!= NULL){		
		$f = make_product_info_fields($fields);
	}else{
		$f = '*';
	}
	$product_infos = jcms_db_get_rows("SELECT ".$f." FROM #_store_product_info WHERE item_status='active' AND item_category='".$category."'");
	return $product_infos;
}

/* Get Best Seller Store Items */
function get_bestsellers($fields = ''){	
	if(is_array($fields) && $fields!= NULL){		
		$f = make_product_info_fields($fields);
	}else{
		$f = '*';
	}
	$bs = jcms_db_get_rows("SELECT ".$f." FROM #_store_product_info WHERE item_status='active' AND meta LIKE '%:\"bestseller\";%'");
	return $bs;
}

/* Get Related Store Items */
function get_related_product_infos($id,$category = '',$fields = '',$limit = 6){	
	if(is_array($fields) && $fields!= NULL){		
		$f = make_product_info_fields($fields);
	}else{
		$f = '*';
	}
	$product_infos = jcms_db_get_rows("SELECT ".$f." FROM #_store_product_info WHERE item_status='active' AND item_category='".($category > 0 ? $category : "0")."' AND ID!='".$id."'".($limit > 0 ? " LIMIT 0,".$limit : ""));
	return $product_infos;
}

/* Get Latest Store Items */
function get_latest_product_info($limit = 10,$fields = ''){
	if(is_array($fields)){		
		$f = make_product_info_fields($fields);
	}else{
		$f = '*';
	}
	$product_infos = jcms_db_get_rows("SELECT ".$f." FROM #_store_product_info WHERE item_status='active' ORDER BY item_date_created ASC LIMIT 0,".$limit);
	return $product_infos;
}

function sanitize_product_info_data($fields,$no_escape = ''){
	$db_fields = _product_info_db_fields(); 
	$data = array();
	foreach($fields as $key => $value){
		if(in_array($key,$db_fields)){
			if(is_array($no_escape) && in_array($key,$no_escape)){
				$data[$key] = $value;
			}else{
				$data[$key] = mysql_escape_string($value);
			}			
		}
	}
	return $data;
}

function is_product_info_exists($id){
	if(is_array($id)){
		$ids = "'".join("','",$id)."'";
		$product_info = jcms_db_get_row("SELECT COUNT(*) AS _count FROM #_store_product_info WHERE  ID IN (".$ids.") OR	item_slug IN (".$ids.")");
		
		if($product_info->_count > 0){
			return true;
		}else{
			return false;
		}

	}else{
		$product_info = jcms_db_get_row("SELECT TRUE AS isfound FROM #_store_product_info WHERE ".(is_numeric($id) ? "ID='".$id."'" : "item_slug='".$id."'"));
		if($product_info->isfound){
			return true;
		}else{
			return false;
		}
	}
}

function get_product_info_count($get = 'all',$active = true){
	$filter = '';
	if($get == 'featured'){
		$filter = ' AND meta LIKE \'%:\"featured_product\";%\'';
	}
	if($get == 'special'){
		$filter = ' AND meta LIKE \'%:\"special\";%\'';
	}
	$product_info_count = jcms_db_get_row("SELECT COUNT(*) AS _count FROM #_store_product_info WHERE item_status='".($active ? 'active' : 'in-active')."'".$filter)->_count;
	return $product_info_count;
}

/* Delete Category */
function delete_product_info($id){
	
	$ids = array();
	if(is_array($id)){
		$ids = $id;
	}else{
		$ids[] = $id;
	}
	
	$_ids = "'".join("','",$ids)."'";		
	
	jcms_db_query("DELETE FROM #_store_product_info WHERE ID IN (".$_ids.")");
	
	$product = jcms_db_get_row("SELECT COUNT(ID) AS _count FROM #_store_product_info WHERE ID IN (".$_ids.")")->_count;
	if($product > 0){
		return false;
	}else{
		return true;
	}
}

function item_url($id,$echo = true){
	if(is_product_info_exists($id)){
		$item = jcms_db_get_row("SELECT ID,item_slug,item_category FROM #_store_product_info WHERE ".(is_numeric($id) ? "ID='".$id."'" : "item_slug='".$id."'"));
		if($item->item_category > 0){
			$link = category_url($item->item_category,false).'/'.$item->item_slug.'.html';;
		}else{
			$link = get_option('site_url').'/'.$item->item_slug.'.html';
		}
		if($echo){
			echo $link;
		}else{
			return $link;
		}	
	}else{
		return '';
	}
}
/* Product Item */
if(!jcms_db_is_table_exists('#_store_product_info')){	
	$structure = "ID BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY, item_slug VARCHAR(100) NOT NULL, item_product_number VARCHAR(50) NOT NULL, item_name VARCHAR(100) NOT NULL, item_description LONGTEXT NOT NULL, item_excerpt LONGTEXT NOT NULL, item_category BIGINT(20) NOT NULL DEFAULT '0', item_product_image LONGTEXT NOT NULL, item_product_image_thumb LONGTEXT NOT NULL, item_date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, item_status VARCHAR(20) NOT NULL DEFAULT 'active', item_sort_order VARCHAR(20) NOT NULL, meta LONGTEXT NOT NULL, UNIQUE (item_slug)";
	jcms_db_create_table('#_store_product_info',$structure);
}

?>