<?php
if(!defined('IMPARENT')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access

function _emailer_db_fields(){
	$db_fields = array(
		'etpl_title',
		'etpl_content',
		'etpl_type',
		'etpl_date_added',
		'etpl_meta'
	); 
	return $db_fields;
}
function make_emailer_fields($fields){
	$f = join(',',$fields);
	$f = str_replace('title','etpl_title AS title',$f);
	$f = str_replace('content','etpl_content AS content',$f);
	$f = str_replace('tpl_type','etpl_type AS tpl_type',$f);
	$f = str_replace('date_added','etpl_date_added AS date_added',$f);
	$f = str_replace('meta','etpl_meta AS meta',$f);
	return $f;
}


/* Add new email tempalte*/
function add_emailtpl($etpl_data = NULL){
	if($etpl_data != NULL){		
	
		$db_fields = _emailer_db_fields();
		$etpl_data['etpl_date_added'] = date('Y-m-d h:i:s A');		
		foreach($etpl_data as $key => $value){
			if(in_array($key,$db_fields)){
				$data[$key] = $value;
			}				
		}
		$id = jcms_db_insert_row('#_store_email_template',$data);
		if($id > 0){
			$tpl = get_etpl($id);
			return $tpl;
		}else{
			return false;
		}
	}else{
		return;
	}
}

/* Update Category */
function update_emailtpl($etpl_data = NULL){
	
	if($etpl_data != NULL){
		$db_fields = _emailer_db_fields();
		if($etpl_data['id']!='' && $etpl_data['id'] > 0){ // Update
			$field = '';
			$id = mysql_escape_string($etpl_data['id']);
	
			foreach($etpl_data as $key => $value){
				if(in_array($key,$db_fields)){
					$field.=(!empty($field) ? ',' : '').$key."='".mysql_escape_string($value)."'";
				}				
			}
			$q = jcms_db_query("UPDATE #_store_email_template SET ".$field." WHERE ID='".$id."'");
			if($q){
				$tpl = get_etpl($id);
				return $tpl;
			}else{
				return false;
			}
		}else{ // Add
			$tpl = add_emailtpl($etpl_data);
			return $tpl;			
		}
	}else{
		return;
	}
}

/* Get Email template */
function get_etpl($id,$fields = NULL){
	if(is_array($fields)){		
		$f = make_emailer_fields($fields);
	}else{
		$f = '*';
	}	
	$tpl = jcms_db_get_row("SELECT ".$f." FROM #_store_email_template WHERE ID='".$id."'");
	return $tpl;
}

if(!jcms_db_is_table_exists('#_store_email_template')){	
	$structure = "ID BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,etpl_title VARCHAR(100) NOT NULL,etpl_content LONGTEXT NOT NULL,etpl_type VARCHAR(50) NOT NULL,etpl_date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,etpl_meta LONGTEXT NOT NULL, UNIQUE (etpl_slug)";
	jcms_db_create_table('#_store_email_template',$structure);	
}
?>