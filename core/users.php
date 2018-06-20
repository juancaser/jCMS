<?php
if(!defined('IMPARENT')){exit();} // No direct access
$users_group = array();
$users_group['ghosts'] = 'Chuck Norris';
$users_group['administrators'] = 'Administrators';
$users_group['power_users'] = 'Power Users';
$users_group['guests'] = 'Guests';
$users_group['subscribers'] = 'Subscribers';
$users_group['clients'] = 'Clients';
$users_group['users'] = 'Users';


/* Items valid DB fields */
function _user_info_db_fields(){
	$db_fields = array(	
		'user_name',
		'user_password',
		'display_name',
		'email_address',
		'user_group',
		'user_sex',
		'backend_access',
		'status',
		'activation_code',
		'meta',
		'date_registered',
		'ip_address'
	); 
	return $db_fields;
}

function make_user_fields($fields){
	$f = join(',',$fields);
	return $f;
}

/* Page Constructor
 * Usually used if you want to create a page on-the-fly without storing
 * it on database
 **/
function _users_constructor($args){
	global $page;
	$default = array(
			'ID' => '-1', // Let's keep it out of the valid loop
			'slug' => '',
			'component' => '', // Only works if the page_type is component
			'title' => '',
			'content' => '',
			'page_type' => 'page',
			'author' => '0',
			'parent_page' => '0',
			'status' => 'published',
			'date_created' => date('Y-m-d h:i:s A'),
			'date_modified' => date('Y-m-d h:i:s A'),
			'menu_order' => '0',
			'meta' => ''
		);
	$args = fill_arguments($default,$args);
	$page = (object) $args;
}

function generate_password($password,$salt){
	return md5('jcms:'.$password.'!@!'.md5($salt));
}

/* User Authentication */
function user_auth($user_name,$user_password){
	$user_name = mysql_escape_string($user_name);
	$user_password = mysql_escape_string(generate_password($user_password,$user_name));
	
	$user = jcms_db_get_row("SELECT TRUE AS valid FROM ".$GLOBALS['users_tbl_name']." WHERE user_name='".$user_name."' OR  email_address='".$user_name."'  AND user_password='".$user_password."' AND status='1' AND backend_access='yes'");
	if($user->valid || $user->valid == 1 || $user->valid == '1'){
		return true;
	}else{
		return false;
	}
}

/* User Authentication with Session*/
function user_login($user_name,$user_password,$type = 'frontend'){
	$user_name = mysql_escape_string($user_name);	
	$user_password = mysql_escape_string(generate_password($user_password,$user_name));
	
	$user = jcms_db_get_row("SELECT TRUE AS valid,ID FROM ".$GLOBALS['users_tbl_name']." WHERE user_name='".$user_name."' OR email_address='".$user_name."' AND user_password='".$user_password."' AND status='1'".($type == "backend" ? " AND backend_access='yes'" : ''));
	if($user->valid || $user->valid == 1 || $user->valid == '1'){
		set_user_session(jcms_db_get_row("SELECT * FROM ".$GLOBALS['users_tbl_name']." WHERE ID='".$user->ID."'"),($type == 'backend' ? 'backend' : 'frontend'));
		return true;		
	}else{
		return false;
	}
}

/* Set User Session*/
function set_user_session($data,$type = 'frontend'){
	$sess_name = '__'.($type == 'backend' ? 'BACKEND' : 'FRONTEND').'_USER';
	$_SESSION[$sess_name] = array(
							'session' => (object) array(
											'session_id' => md5('jcms_session_id:'.date('Y-m-d h:i:s A')),
											'date_created' => date('Y-m-d h:i:s A'),
											'ip_address' => get_ipaddress()
										),
							'info' => $data
						);
}

/* Get User Info */
function get_user($id){
	$user = jcms_db_get_row("SELECT * FROM ".$GLOBALS['users_tbl_name']." WHERE ID='".$id."' OR user_name='".$id."' OR email_address='".$id."' OR activation_code='".$id."'");
	if(count($user) > 0){
		return $user;
	}else{
		return;
	}
}

/* Check if user is logged */
function is_user_logged($type = 'frontend'){
	$sess_name = '__'.($type == 'backend' ? 'BACKEND' : 'FRONTEND').'_USER';
	if(isset($_SESSION[$sess_name])){
		return true;
	}else{
		return false;
	}
}

/* Add New Catalog items */
function add_user($fields){		
	if(is_array($fields)){
		$usr = explode('@' ,$fields['user_name']);
		if(count($usr) > 1){
			$usr = $usr[0];
		}else{
			$usr = $fields['user_name'];
		}
		$fields['user_name'] = $usr;
	
		if($fields['user_password']!=''){
			$fields['user_password'] = generate_password($fields['user_password'],$fields['email_address']);
		}
		$fields['activation_code'] = md5('jcms_activation_code@'.$fields['email_address']);
		
		$fields['date_registered'] = date('Y-m-d h:i:s A');
		$data = sanitize_user_data($fields);
		$id = jcms_db_insert_row($GLOBALS['users_tbl_name'],$data);
		if($id > 0){
			$user = get_user($id);
			return $user;
		}else{
			return false;
		}
	}else{
		return;
	}
}

/* Update Category */
function update_user($fields){
	if(is_array($fields)){	
		if($fields['id'] > 0){ // Update
			$field = '';
			$usr = explode('@' ,$fields['user_name']);
			if(count($usr) > 1){
				$usr = $usr[0];
			}else{
				$usr = $fields['user_name'];
			}
			$fields['user_name'] = $usr;
			if($fields['user_password']!=''){
				$fields['user_password'] = generate_password($fields['user_password'],$fields['email_address']);
			}
			
			$id = mysql_escape_string($fields['id']);
			unset($fields['id']);
			$fields = sanitize_user_data($fields);
			foreach($fields as $key => $value){
				if($value!=''){
					$field.=($field!='' ? ',' : '').$key."='".$value."'";
				}				
			}
			$sql = "UPDATE ".$GLOBALS['users_tbl_name']." SET ".$field." WHERE ".(is_numeric($id) ? "ID='".$id."'" : "user_name='".$id."' OR email_address='".$id."' OR activation_code='".$id."'");
			$q = jcms_db_query($sql);
			if($q){
				$user = get_user($id);
				return $user;
			}else{
				return false;
			}
		}else{ // Add
			$user = add_user($fields);
			return $user;
		}
	}else{
		return;
	}
}

/* Activate User*/
function activate_user($id){
	if(is_user_exists($id,false)){
		$q = jcms_db_query("UPDATE ".$GLOBALS['users_tbl_name']." SET status='1' WHERE ".(is_numeric($id) ? "ID='".$id."'" : "user_name='".$id."' OR email_address='".$id."' OR activation_code='".$id."'"));	
		if($q){
			return true;
		}else{
			return false;
		}		
	}else{
		return false;
	}
}

/* De-Activate User*/
function deactivate_user($id){
	if(is_user_exists($id)){
		$q = jcms_db_query("UPDATE ".$GLOBALS['users_tbl_name']." SET status='0' WHERE ".(is_numeric($id) ? "ID='".$id."'" : "user_name='".$id."' OR email_address='".$id."' OR activation_code='".$id."'"));	
		if($q){
			return get_user($id);
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function sanitize_user_data($fields,$no_escape = ''){
	$db_fields = _user_info_db_fields(); 
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

/* Delete Category */
function delete_user($id){
	
	$ids = array();
	if(is_array($id)){
		$ids = $id;
	}else{
		$ids[] = $id;
	}
	
	$_ids = "'".join("','",$ids)."'";		
	
	jcms_db_query("DELETE FROM ".$GLOBALS['users_tbl_name']." WHERE ID IN (".$_ids.")");
	
	$user = jcms_db_get_row("SELECT COUNT(ID) AS _count FROM ".$GLOBALS['users_tbl_name']." WHERE ID IN (".$_ids.")")->_count;
	if($user > 0){
		return false;
	}else{
		return true;
	}
}

/* Check if user exists */
function is_user_exists($user_name,$status = true,$type = 'frontend'){
	$user_name = mysql_escape_string($user_name);	
	$user = jcms_db_get_row("SELECT TRUE AS valid FROM ".$GLOBALS['users_tbl_name']." WHERE user_name='".$user_name."' OR  ID='".$user_name."' OR  email_address='".$user_name."' OR  activation_code='".$user_name."' AND status='".($status ? "1" : "0")."'".($type == "backend" ? " AND backend_access='yes'" : ''));
	if($user->valid || $user->valid == 1 || $user->valid == '1'){
		return true;		
	}else{
		return false;
	}
}


function get_user_count($group = 'user',$backend_access = false,$active = true){
	global $users_group;
	$filter = '';
	if(array_key_exists($group,$users_group)){
		$filter = " AND user_group='".$group."'";
	}
	$user_count = jcms_db_get_row("SELECT COUNT(*) AS _count FROM #_users WHERE status='".($active ? '1' : '0')."' AND backend_access='".($backend_access ? "yes" : 'no')."'".$filter)->_count;
	return $user_count;
}

/* Get this months newly registered user*/
function latest_registered_users($limit = 5,$sort = 'ASC'){
	$sql = "SELECT * FROM #_users WHERE status='1' AND backend_access='no' AND DATE_FORMAT(date_registered,'%d%m%Y%') BETWEEN '01".date('mY')."' AND '".date('dmY')."' ORDER BY date_registered".($sort == 'ASC' ? ' ASC' : 'DESC')." LIMIT 0,".$limit;
	$latest_registered_users = jcms_db_get_rows($sql);
	return $latest_registered_users;
}

?>