<?php
if(!defined('IMPARENT')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access


/* Product Item */
if(!jcms_db_is_table_exists('#_store')){	
	$structure = "ID BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY, item_slug VARCHAR(100) NOT NULL, item_name VARCHAR(100) NOT NULL, item_description LONGTEXT NOT NULL, item_category BIGINT(20) NOT NULL DEFAULT '0', item_product_image LONGTEXT NOT NULL, item_date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, item_status VARCHAR(20) NOT NULL DEFAULT 'active', item_sort_order VARCHAR(20) NOT NULL, meta LONGTEXT NOT NULL, UNIQUE (item_slug)";
	jcms_db_create_table('#_store_product_info',$structure);
}

?>