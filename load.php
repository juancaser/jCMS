<?php
if(!defined('IMPARENT')){exit();} // No direct access
/* Error Reporting */
//error_reporting(0);

/* Start Session */
session_start();

/* Load Config */
include('./configuration.php');
$config = new Configuration();

/** FUNCTIONS */
include('./core/mail/mail.php'); // PHPMailer

include('./core/general.php'); // General
include('./core/db.class.php');  // Database
include('./core/setup.php'); // Setup
include('./core/options.php'); // Options

if(NET == 'ONLINE' && get_option('ga_email')!='' && get_option('ga_password')!='' && get_option('ga')=='yes'){
	include('./core/gapi.class.php'); // Google API
	$ga = new gapi(get_option('ga_email'),get_option('ga_password'));
}


include('./core/widgets.php'); // Widgets
/* Set Timezone */
if(function_exists('date_default_timezone_set')){	
	date_default_timezone_set((get_option('time_zone') !='' ? get_option('time_zone') : $config->timezone));
}


// Registering a script to make it built-in
register_script('jquery',get_option('site_url').'/core/js/jquery/jquery.js','','1.4.4'); // jQuery Core Library
register_script('jquery-ui',$config->site_url.'/core/js/jquery/jquery-ui.js','jquery','1.8.7'); // jQuery UI Core Library

register_script('mootools',get_option('site_url').'/core/js/mootools/mootools.js','','1.2.4'); // mootools Core Library
register_script('prototype',get_option('site_url').'/core/js/prototype/prototype.js','','1.6.1'); // prototype Core Library

register_script('jquery-tiny-mce',get_option('site_url').'/core/js/jquery-tiny_mce/jquery.tinymce.js','jquery','3.3.3'); // Jquery TinyMCE

register_style('jquery-ui-theme',get_option('site_url').'/core/js/jquery/ui-theme/theme.css','jquery','1.7.2'); // jQuery UI Theme

include('./core/users.php'); // User
include('./core/pages.php'); // Page
include('./core/components.php'); // Components

include('./core/template.php'); // Template

do_action('jcms_init'); // Initialization
do_action('jcms_close'); // Exiting

?>