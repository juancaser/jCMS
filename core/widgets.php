<?php
if(!defined('IMPARENT')){exit();} // No direct access
/* Page valid DB fields */
function _widgets_db_fields(){
	$db_fields = array(
		'ID',
		'widget_slug',
		'widget_name',
		'widget_code',
		'meta'
	); 
	return $db_fields;
}

function add_widget($data){
	if(is_array($data)){
		$id = jcms_db_add($GLOBALS['widgets_tbl_name'],$data);
		if($id > 0){
			return get_widget($id);
		}else{
			return false;
		}
	}else{
		return false;
	}
	/*$data = array(
			'widget_slug' => mysql_escape_string(make_slug($data['widget_name'],0)),
			'widget_name' => mysql_escape_string($data['widget_name']),
			'widget_code' => mysql_escape_string($data['widget_code']),
			'meta' => mysql_escape_string((is_arra($data['meta']) ? serialize($data['meta']) : $data['meta']))
		);
	$id = jcms_db_insert_row($GLOBALS['widgets_tbl_name'],$data);
	if($id > 0){
		$widget = get_widget($id);
		return $widget;
	}else{
		return false;
	}*/

}

function update_widget($data){
	if($data['id'] > 0){
		$opt = jcms_db_query("UPDATE ".$GLOBALS['option_tbl_name']." SET option_value='".$value."' WHERE ".(is_numeric($key) ? "ID='".$key."'" : "option_key='".$key."'"));		
		return $opt;
	}else{
		$opt = add_widget($key,$value,'startup');
		if($opt > 0){
			return true;
		}else{
			return false;
		}		
	}
}

function get_widget($id){
	$widget = jcms_db_get_row("SELECT * FROM ".$GLOBALS['widgets_tbl_name']." WHERE ".(is_numeric($id) ? " ID='".$id."'" : " widget_slug='".$id."'"));
	return $widget;
}

function get_widgets(){
	$widgets = jcms_db_get_row("SELECT * FROM ".$GLOBALS['widgets_tbl_name']);
	return $widgets;
}

function delete_widget($id){
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

function is_widget_exists($id){
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
$opts = get_widgets();
for($i=0;$i < count($opts);$i++){
	$opt = $opts[$i];
	$k = strtoupper(str_replace(array(' '),'_',$opt->option_key));
	if(!defined($k)){
		define($k,$opt->option_value);
	}
}
//print_r(get_defined_constants(true));
?>