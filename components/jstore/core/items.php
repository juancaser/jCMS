<?php
if(!defined('IMPARENT')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access

/**
 * PRODUCT INFORMATION
 */

ID 
/* Items valid DB fields */
function _product_info_db_fields(){
	$db_fields = array(
		'item_slug',
		'item_product_number',
		'item_name',
		'item_description',
		'item_price',
		'item_category',
		'item_product_image',
		'item_product_image_thumb',
		'item_date_added',
		'item_status',
		'item_sort_order',
		'meta'
	); 
	return $db_fields;
}

function make_product_info_fields($fields){
	$f = join(',',$fields);
	$f = str_replace('slug','item_slug AS slug',$f);
	$f = str_replace('product_no','item_product_number AS product_no',$f);
	$f = str_replace('name','item_name AS name',$f);
	$f = str_replace('description','item_description AS description',$f);
	$f = str_replace('price','item_price AS price',$f);
	$f = str_replace('category','item_category AS category',$f);
	$f = str_replace('product_image','item_product_image AS product_image',$f);	
	$f = str_replace('thumbnail','item_product_image_thumb AS thumbnail',$f);	
	$f = str_replace('date_added','item_date_added AS date_added',$f);
	$f = str_replace('status','item_status AS status',$f);
	$f = str_replace('sort_order','item_sort_order AS sort_order',$f);
	return $f;
}

/* Add New Catalog items */
function add_product_info($fields){		
	if(is_array($fields)){
		$fields['item_date_added'] = date('Y-m-d h:i:s A');
		$data = sanitize_item_data($fields);
		$id = jcms_db_insert_row('#_store_product_info',$data);
		if($id > 0){
			$item = get_item($id);
			return $item;
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
			$fields = sanitize_item_data($fields);
			foreach($fields as $key => $value){
				if(in_array($key,$db_fields) && $value!=''){
					$field.=(!empty($field) ? ',' : '').$key."='".$value."'";
				}				
			}
			$q = jcms_db_query("UPDATE #_store_product_info SET ".$field." WHERE ".(is_numeric($id) ? "ID='".$id."'" : "item_slug='".$id."' OR item_product_number='".$id."'"));
			if($q){
				$item = get_product_info($id);
				return $item;
			}else{
				return false;
			}
		}else{ // Add
			$item = add_item($items_data);
			return $item;
		}
	}else{
		return;
	}
}

/* Get Store Item */
function get_item($id,$fields = ''){	
	if(is_array($fields)){		
		$f = make_product_info_fields($fields);
	}else{
		$f = '*';
	}
	$item = jcms_db_get_row("SELECT ".$f." FROM #_store_product_info WHERE ".(is_numeric($id) ? "ID='".$id."'" : "item_slug='".$id."' OR item_product_number='".$id."'"));
	return $item;
}


/* Get Store Items */
function get_items($fields = NULL){	
	if(is_array($fields) && $fields!= NULL){		
		$f = make_product_info_fields($fields);
	}else{
		$f = '*';
	}
	$categories = jcms_db_get_rows("SELECT ".$f." FROM #_store_product_items WHERE item_status='active'");
	return $categories;
}

/* Get Latest Store Items */
function get_latest_items($limit = 10,$fields = ''){	
	if(is_array($fields)){		
		$f = make_product_info_fields($fields);
	}else{
		$f = '*';
	}
	$categories = jcms_db_get_rows("SELECT ".$f." FROM #_store_product_items WHERE item_status='active' ORDER BY item_date_added ASC LIMIT 0,".$limit);
	return $categories;
}

function sanitize_item_data($items_data){
	$db_fields = _product_info_db_fields(); 
	$data = array();
	foreach($items_data as $key => $value){
		if(in_array($key,$db_fields)){
		 
			$data[$key] = mysql_escape_string($value);
		}				
	}
	return $data;
}

function is_item_exists($id){
	if(is_array($id)){
		$ids = "'".join("','",$id)."'";
		$item = jcms_db_get_row("SELECT COUNT(*) AS _count FROM #_store_product_info WHERE  ID IN (".$ids.") OR	item_slug IN (".$ids.")");
		
		if($item->_count > 0){
			return true;
		}else{
			return false;
		}

	}else{
		$item = jcms_db_get_row("SELECT TRUE AS isfound FROM #_store_product_info WHERE ".(is_numeric($id) ? "ID='".$id."'" : "item_slug='".$id."' OR item_product_number='".$id."'"));
		if($item->isfound){
			return true;
		}else{
			return false;
		}
	}
}

function item_count($status = 'active'){
	$catalog_count = jcms_db_get_row("SELECT COUNT(*) AS _count FROM #_store_product_info WHERE item_status='".$status."'")->_count;
	return $catalog_count;
}

function item_url($id,$echo = true){
	global $config;
	$item = jcms_db_get_row("SELECT ID,item_slug,item_category FROM #_store_product_info WHERE ".(is_numeric($id) ? "ID='".$id."'" : "item_slug='".$id."' OR item_product_number='".$id."'"));
	if($item->item_category > 0){
		$link = category_url($item->item_category).'/'.$item->item_slug.'.html';;
	}else{
		$link = get_option('site_url').'/'.$item->item_slug.'.html';
	}
	if($echo){
		echo $link;
	}else{
		return $link;
	}	
}
/* Product Item */
if(!jcms_db_is_table_exists('#_store_product_info')){	
	$structure = "ID BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY, item_slug VARCHAR(100) NOT NULL, item_product_number VARCHAR(100) NOT NULL, item_name VARCHAR(100) NOT NULL, item_description LONGTEXT NOT NULL, item_price VARCHAR(50) NOT NULL, item_product_image LONGTEXT NOT NULL, item_product_image_thumb LONGTEXT NOT NULL, item_category BIGINT(20) NOT NULL DEFAULT '0', item_date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, item_status VARCHAR(20) NOT NULL DEFAULT 'active', item_sort_order VARCHAR(20) NOT NULL, meta LONGTEXT NOT NULL, UNIQUE (item_slug)";
	jcms_db_create_table('#_store_product_info',$structure);
	
}

?>