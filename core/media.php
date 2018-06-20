<?php
if(!defined('IMPARENT')){exit();} // No direct access

/* Album */
/* Album valid DB fields */
function _album_db_fields(){
	$db_fields = array(
		'album_slug',
		'album_name',
		'album_description',
		'date_added',
		'private',
		'meta'
	); 
	return $db_fields;
}

/* Add New Album */
function add_album($album_data){		
	if(is_array($album_data)){		
		$album_data['item_date_added'] = date('Y-m-d h:i:s A');
		$data = sanitize_album_data($album_data);
		$id = jcms_db_insert_row($GLOBALS['media_album_tbl_name'],$data);
		if($id > 0){
			$album = get_album($id);
			return $item;
		}else{
			return false;
		}
	}else{
		return;
	}
}


function get_albums(){
	$albums = jcms_db_get_rows("SELECT * FROM ".$GLOBALS['media_album_tbl_name']);
	return $albums;
}

function get_album($id){
	$album = jcms_db_get_row("SELECT * FROM ".$GLOBALS['media_album_tbl_name']." WHERE ".(is_numeric($id) ? "ID='".$id."'" : "album_slug='".$id."'"));
	return $album;
}

function is_album_exists($id){
	$album = jcms_db_get_row("SELECT TRUE AS isfound FROM ".$GLOBALS['media_album_tbl_name']." WHERE ".(is_numeric($id) ? "ID='".$id."'" : "album_slug='".$id."'"));
	if($album->isfound){
		return true;
	}else{
		return false;
	}
}

function sanitize_album_data($album_data){
	$db_fields = _album_db_fields(); 
	$data = array();
	foreach($album_data as $key => $value){
		if(in_array($key,$db_fields)){
			$data[$key] = mysql_escape_string($value);
		}				
	}
	return $data;
}
?>