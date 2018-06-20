<?php
define('IMPARENT',true);
define('JCMS',true);
/* Start Session */
session_start();

/* Load Config */
include('../configuration.php');
$config = new Configuration();


/* Set Timezone */
if(function_exists('date_default_timezone_set') && isset($config->timezone))
	date_default_timezone_set($config->timezone);
/** FUNCTIONS */
include(GBL_ROOT_CORE.'/general.php'); // General
include(GBL_ROOT_CORE.'/db.class.php');  // Database
include(GBL_ROOT_CORE.'/setup.php'); // Setup
include(GBL_ROOT_CORE.'/options.php'); // Options
include(GBL_ROOT_CORE.'/widgets.php'); // Widgets
include(GBL_ROOT_CORE.'/pages.php'); // Page
include(GBL_ROOT_CORE.'/users.php'); // User

/* Set Timezone */
if(function_exists('date_default_timezone_set')){	
	date_default_timezone_set((get_option('time_zone') !='' ? get_option('time_zone') : $config->timezone));
}

// Registering a script to make it built-in
register_script('jquery',get_option('site_url').'/core/js/jquery/jquery.js','','1.4.4'); // jQuery Core Library
register_script('jquery-ui',$config->site_url.'/core/js/jquery/jquery-ui.js','jquery','1.8.6'); // jQuery UI Core Library

register_script('mootools',get_option('site_url').'/core/js/mootools/mootools.js','','1.2.4'); // mootools Core Library
register_script('prototype',get_option('site_url').'/core/js/prototype/prototype.js','','1.6.1'); // prototype Core Library

register_script('jquery-tiny-mce',get_option('site_url').'/core/js/jquery-tiny_mce/jquery.tinymce.js','jquery','3.3.3'); // Jquery TinyMCE

register_style('jquery-ui-theme',get_option('site_url').'/core/js/jquery/ui-theme/theme.css','jquery','1.7.2'); // jQuery UI Theme

register_script('jquery-table-sorter',get_option('site_url').'/core/js/jquery-tablesorter/jquery.tablesorter.js','jquery','2.0.0'); // Jquery Table Sorter

define('GBL_ROOT_BACKEND',GBL_ROOT.'/backend');
define('BACKEND_DIRECTORY',get_option('site_url').'/backend'); //  Backend Directory

include(GBL_ROOT_CORE.'/components.php'); // Components
include(GBL_ROOT_CORE.'/media.php'); // Media


register_script('swfobject',get_option('site_url').'/core/js/swfobject.js','','2.2.0'); // jQuery Ajax Uploader
register_script('uploadify',get_option('site_url').'/core/js/uploadify/jquery.uploadify.js','jquery','2.1.4'); // jQuery Uploadify
register_style('uploadify-css',get_option('site_url').'/core/js/uploadify/uploadify.css','uploadify','1.6.0'); // jQuery Uploadify CSS


/* Load Backend Functions */
include('backend-functions.php');

do_action('jcms_backend_init'); // Initialization

/* Backend Form Action */
include('backend-action.php'); // Should be loaded last
?>