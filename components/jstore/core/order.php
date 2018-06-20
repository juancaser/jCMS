<?php
if(!defined('IMPARENT')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access



/* Items valid DB fields */
function _order_db_fields(){
	$db_fields = array(
		'user_id',
		'trans_id',
		'order_meta',
		'order_date',
		'order_status'
	); 
	return $db_fields;
}

function make_order_fields($fields){
	$f = join(',',$fields);
	$f = str_replace('transaction_id','trans_id AS transaction_id',$f);
	$f = str_replace('details','order_meta AS details',$f);
	$f = str_replace('meta','order_meta AS meta',$f);
	$f = str_replace('status','order_status AS status',$f);
	$f = str_replace('date_ordered','order_date AS date_ordered',$f);
	return $f;
}

function add_order($fields = NULL){
	if(is_array($fields)){
		$fields['order_date'] = date('Y-m-d h:i:s A');
		$data = sanitize_order_data($fields);
		$id = jcms_db_insert_row('#_order',$data);
		if($id > 0){
			$order = get_order($id);
			return $order;
		}else{
			return false;
		}
	}else{
		return;
	}
}

function update_order($fields){
	if(is_array($fields)){
		$db_fields = _order_db_fields();
		
		if($fields['id']!=''){ // Update
			$field = '';
			$id = mysql_escape_string($fields['id']);
			$fields = sanitize_order_data($fields);
			foreach($fields as $key => $value){
				if(in_array($key,$db_fields)){
					$field.=(!empty($field) ? ',' : '').$key."='".$value."'";
				}				
			}
			$q = jcms_db_query("UPDATE #_order SET ".$field." WHERE ID='".$id."'");
			if($q){
				$order = get_order($id);
				return $order;
			}else{
				return false;
			}
		}else{ // Add
			$order = add_order($fields);
			return $order;
		}
	}else{
		return;
	}
}
function get_order($id){
	$order = jcms_db_get_row("SELECT * FROM #_order WHERE ID='".$id."'");
	return $order;
}

function get_orders($status = 'pending'){
	$s = 'pending';
	if($status == 'onhold'){
		$s = 'onhold';
	}
	if($status == 'ongoing'){
		$s = 'ongoing';
	}
	if($status == 'shipped'){
		$s = 'shipped';
	}
	
	$orders = jcms_db_get_rows("SELECT * FROM #_order WHERE order_status='".$s."'");
	return $orders;
}

function get_order_count($status = 'pending'){
	$s = 'pending';
	if($status == 'onhold'){
		$s = 'onhold';
	}
	if($status == 'ongoing'){
		$s = 'ongoing';
	}
	if($status == 'shipped'){
		$s = 'shipped';
	}
	$orders = jcms_db_get_row("SELECT COUNT(*) AS _count FROM #_order WHERE order_status='".$s."'");
	return $orders->_count;
}

function delete_order($id){	
	$ids = array();
	if(is_array($id)){
		$ids = $id;
	}else{
		$ids[] = $id;
	}	
	$_ids = "'".join("','",$ids)."'";	
	jcms_db_query("DELETE FROM #_order WHERE ID IN (".$_ids.")");	
	$orders = jcms_db_get_row("SELECT COUNT(ID) AS _count FROM #_order WHERE ID IN (".$_ids.")")->_count;
	if($orders > 0){
		return false;
	}else{
		return true;
	}
}

function sanitize_order_data($fields,$no_escape = ''){
	$db_fields = _order_db_fields(); 
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



/* Order */
if(!jcms_db_is_table_exists('#_order')){	
	$structure = "ID BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,trans_id VARCHAR(100) NOT NULL,user_id BIGINT(20) NOT NULL, order_meta LONGTEXT NOT NULL, order_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, order_status VARCHAR(20) NOT NULL DEFAULT 'pending'";
	jcms_db_create_table('#_order',$structure);
}

?>