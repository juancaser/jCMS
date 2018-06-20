<?php
if(!defined('JCMS')){exit();} // No direct access
if($_REQUEST['action'] == 'save-widget'){
	$_REQUEST['date_format'] = ($_REQUEST['date_format'] == 'custom' ? $_REQUEST['custom_date_format'] : $_REQUEST['date_format']);
	$_REQUEST['time_format'] = ($_REQUEST['time_format'] == 'custom' ? $_REQUEST['custom_time_format'] : $_REQUEST['time_format']);
	$options = array(				 
		'site_name' => $_REQUEST['site_name'],
		'site_description' => $_REQUEST['site_description'],
		'site_url' => $_REQUEST['site_url'],
		'admin_email' => $_REQUEST['admin_email'],
		'time_zone' => $_REQUEST['time_zone'],
		'date_format' => $_REQUEST['date_format'],
		'time_format' => $_REQUEST['time_format'],
		'weeks_starts_on' => $_REQUEST['weeks_starts_on']
	);
	$has_error = false;
	foreach($options as $key => $value){
		if(!update_option($key,$value)){
			$has_error  = true;
		}
	}
	if(!$has_error){
		$msg = 'Settings successfully saved.';
		$class = 'success';
	}else{
		$msg = 'Error occured while saving. Please try again.';
		$class = 'error';
	}
	set_global_mesage('system_action_message',$msg,$class);
	redirect(SYSTEM_URL,'js');
}
?>