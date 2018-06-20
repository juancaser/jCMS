<?php
define('IMPARENT',true);
define('JCMS',true);
define('JSTORE',true);

/* Start Session */
session_start();

/* Load Config */
include('../../configuration.php');
$config = new Configuration();

/** FUNCTIONS */
include(GBL_ROOT_CORE.'/general.php');  // Database
include(GBL_ROOT_CORE.'/db.class.php');  // Database
include(GBL_ROOT_CORE.'/setup.php'); // Setup
include(GBL_ROOT_CORE.'/options.php'); // Setup

/* Set Timezone */
if(function_exists('date_default_timezone_set')){	
	date_default_timezone_set((get_option('time_zone') !='' ? get_option('time_zone') : $config->timezone));
}

define('BACKEND_DIRECTORY',get_option('site_url').'/backend'); //  Backend Directory

include('core/categories.php'); // Categories
include('core/product_info.php'); // Catalogs


_db_construct();
_db_table_construct();

/* AJAX */
if($_REQUEST['action'] == 'check_slug'){
	$slug = make_slug($_REQUEST['data'],0);
	switch($_REQUEST['req']){
		case 'product_info':
			if(is_product_info_exists($slug)){
				$status =  1;				
			}else{
				$status =  0;
			}
			break;
		case 'product_category':			
			if(is_category_exists($slug)){
				$status =  1;				
			}else{
				$status =  0;
			}			
			break;
	}
	echo '{"status":"'.$status.'","slug":"'.$slug.'"}';
}


jcms_db_close();
?>