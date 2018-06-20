<?php
if(!defined('IMPARENT')){exit();} // No direct access
/* Page valid DB fields */
function _options_db_fields(){
	$db_fields = array(
		'ID',
		'option_key',
		'option_value',
		'load_on'
	); 
	return $db_fields;
}

function add_option($key,$value,$load_on = ''){
	$data = array(
			'option_key' => mysql_escape_string($key),
			'option_value' => mysql_escape_string($value),
			'load_on' => mysql_escape_string($load_on)
		);
	$id = jcms_db_insert_row($GLOBALS['option_tbl_name'],$data);
	if($id > 0){
		$option = get_option($id,false);
		return $option;
	}else{
		return false;
	}

}

function update_option($key,$value){
	if(is_option_exists($key)){
		$opt = jcms_db_query("UPDATE ".$GLOBALS['option_tbl_name']." SET option_value='".$value."' WHERE ".(is_numeric($key) ? "ID='".$key."'" : "option_key='".$key."'"));		
		return $opt;
	}else{
		$opt = add_option($key,$value,'startup');
		if($opt > 0){
			return true;
		}else{
			return false;
		}		
	}
}

function get_option($option_key,$return_value = true){
	$option_key = mysql_escape_string($option_key);
	$option = jcms_db_get_row("SELECT * FROM ".$GLOBALS['option_tbl_name']." WHERE option_key='".$option_key."' OR ID='".$option_key."'");
	if($return_value){
		return $option->option_value;
	}else{
		return $option;
	}	
}

function get_options($load_on = 'startup'){
	$options = jcms_db_get_rows("SELECT * FROM ".$GLOBALS['option_tbl_name']." WHERE load_on='".$load_on."'");
	return $options;
}

function delete_option($id){
	if(is_array($id)){
		$ids = "'".join("','",$id)."'";
		jcms_db_query("DELETE FROM ".$GLOBALS['option_tbl_name']." WHERE ID IN (".$ids.") OR option_key IN (".$ids.")");
	}else{
		jcms_db_query("DELETE FROM ".$GLOBALS['option_tbl_name']." WHERE ".(is_numeric($id) ? "ID='".$id."'" : "option_key='".$id."'"));
		if(is_option_exists($id)){
			return false;
		}else{
			return true;
		}
	}	
}

function is_option_exists($id){
	if(is_array($id)){
		$ids = "'".join("','",$id)."'";
		$options = jcms_db_get_row("SELECT COUNT(*) AS _count FROM ".$GLOBALS['option_tbl_name']." WHERE  ID IN (".$ids.") OR option_key IN (".$ids.")");
		if($options->_count > 0){
			return true;
		}else{
			return false;
		}

	}else{
		$option = jcms_db_get_row("SELECT TRUE AS isfound FROM ".$GLOBALS['option_tbl_name']." WHERE ".(is_numeric($id) ? "ID='".$id."'" : "option_key='".$id."'"));
		if($option->isfound){
			return true;
		}else{
			return false;
		}
	}
}

// Pre Load all Options
$opts = get_options();
for($i=0;$i < count($opts);$i++){
	$opt = $opts[$i];
	$k = strtoupper(str_replace(array(' '),'_',$opt->option_key));
	if(!defined($k)){
		define($k,$opt->option_value);
	}
}
//print_r(get_defined_constants(true));
?>