<?php
if(!defined('IMPARENT')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access

/* Items valid DB fields */
function _items_db_fields(){
	$db_fields = array(
		'item_slug',
		'item_name',
		'item_description',
		'item_category',
		'item_price',
		'item_quantity',
		'item_available_stock',
		'item_shipping_type',
		'item_image',
		'item_available_date',
		'item_date_added',
		'item_status',
		'item_sort_order',
		'meta'
	); 
	return $db_fields;
}

function make_items_fields($fields){
	$f = join(',',$fields);
	$f = str_replace('slug','item_slug AS slug',$f);
	$f = str_replace('name','item_name AS name',$f);
	$f = str_replace('description','item_description AS description',$f);
	$f = str_replace('category','item_category AS category',$f);
	$f = str_replace('price','item_price AS price',$f);
	$f = str_replace('stock','item_available_stock AS stock',$f);
	$f = str_replace('shipping_type','item_shipping_type AS shipping_type',$f);
	$f = str_replace('available_date','item_available_date AS available_date',$f);
	$f = str_replace('date_added','item_date_added AS date_added',$f);
	$f = str_replace('status','item_status AS status',$f);
	$f = str_replace('sort_order','item_sort_order AS sort_order',$f);
	return $f;
}

/* Add New Catalog items */
function add_item($items_data){		
	if(is_array($items_data)){
		$items_data['item_date_added'] = date('Y-m-d h:i:s A');
		$data = sanitize_item_data($items_data);
		$id = jcms_db_insert_row('#_store_catalog_items',$data);
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
function update_item($items_data){
	if(is_array($items_data)){
		$db_fields = _items_db_fields();
		
		if($items_data['id']!=''){ // Update
			$field = '';
			$id = mysql_escape_string($items_data['id']);
			$items_data = sanitize_item_data($items_data);
			foreach($items_data as $key => $value){
				if(in_array($key,$db_fields) && $value!=''){
					$field.=(!empty($field) ? ',' : '').$key."='".$value."'";
				}				
			}
			$q = jcms_db_query("UPDATE #_store_catalog_items SET ".$field." WHERE ".(is_numeric($id) ? "ID='".$id."'" : "item_slug='".$id."'"));
			if($q){
				$item = get_item($id);
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
		$f = make_items_fields($fields);
	}else{
		$f = '*';
	}
	$item = jcms_db_get_row("SELECT ".$f." FROM #_store_catalog_items WHERE ".(is_numeric($id) ? "ID='".$id."'" : "item_slug='".$id."'"));
	return $item;
}


/* Get Store Items */
function get_items($fields = NULL){	
	if(is_array($fields) && $fields!= NULL){		
		$f = make_items_fields($fields);
	}else{
		$f = '*';
	}
	$categories = jcms_db_get_rows("SELECT ".$f." FROM #_store_catalog_items WHERE item_status='active'");
	return $categories;
}

/* Get Latest Store Items */
function get_latest_items($limit = 10,$fields = ''){	
	if(is_array($fields)){		
		$f = make_items_fields($fields);
	}else{
		$f = '*';
	}
	$categories = jcms_db_get_rows("SELECT ".$f." FROM #_store_catalog_items WHERE item_status='active' ORDER BY item_date_added ASC LIMIT 0,".$limit);
	return $categories;
}

function sanitize_item_data($items_data){
	$db_fields = _items_db_fields(); 
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
		$item = jcms_db_get_row("SELECT COUNT(*) AS _count FROM #_store_catalog_items WHERE  ID IN (".$ids.") OR	item_slug IN (".$ids.")");
		
		if($item->_count > 0){
			return true;
		}else{
			return false;
		}

	}else{
		$item = jcms_db_get_row("SELECT TRUE AS isfound FROM #_store_catalog_items WHERE ".(is_numeric($id) ? "ID='".$id."'" : "item_slug='".$id."'"));
		if($item->isfound){
			return true;
		}else{
			return false;
		}
	}
}

function catalog_count($status = 'active'){
	$catalog_count = jcms_db_get_row("SELECT COUNT(*) AS _count FROM #_store_catalog_items WHERE item_status='".$status."'")->_count;
	return $catalog_count;
}

function catalog_url($id,$echo = true){
	global $config;
	$item = jcms_db_get_row("SELECT ID,item_slug,item_category FROM #_store_catalog_items WHERE ".(is_numeric($id) ? "ID='".$id."'" : "item_slug='".$id."'"));
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
if(!jcms_db_is_table_exists('#_store_catalog_items')){	
	$structure = "ID BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY, item_info_id BIGINT(20) NOT NULL,item_type VARCHAR(100) NOT NULL, item_price VARCHAR(50) NOT NULL, item_quantity VARCHAR(50) NOT NULL, item_available_stock BIGINT(20) NOT NULL, item_shipping_type VARCHAR(20) NOT NULL DEFAULT 'normal', item_image LONGTEXT NOT NULL, item_available_date DATETIME NOT NULL, item_date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, item_sort_order VARCHAR(20) NOT NULL, meta LONGTEXT NOT NULL";
	jcms_db_create_table('#_store_catalog_items',$structure);
}

if(!jcms_db_is_table_exists('#_store_catalog_info')){	
	$structure = "ID BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY, item_slug VARCHAR(100) NOT NULL, item_name VARCHAR(100) NOT NULL, item_description LONGTEXT NOT NULL, item_category BIGINT(20) NOT NULL DEFAULT '0', item_date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, item_status VARCHAR(20) NOT NULL DEFAULT 'active', item_sort_order VARCHAR(20) NOT NULL, meta LONGTEXT NOT NULL, UNIQUE (item_slug)";
	jcms_db_create_table('#_store_catalog_info',$structure);
}

?>