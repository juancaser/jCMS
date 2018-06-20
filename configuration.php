<?php
if(!defined('IMPARENT')){exit();} // No direct access
define('VERSION','2.2'); // System Version
session_start();

class Configuration{
	var $site_name = 'jCMS 2.2';
	var $site_description = 'As Simple as it can Gets!';
	var $site_url = 'http://www.yourwebsite.com';
	//var $domain = 'jcms.dev';
	var $admin_email = 'sales@yourwebsite.com';
	
	var $mod_rewrite = true; // Set this to false to disable mod_rewrite
	var $timezone = 'Asia/Manila'; // Local timezone comment this setting to set to UTC
	var $http_api = true; // Set this to true to enable http-api
	var $content_type = 'text/html';
	var $charset = 'utf-8';
	var $locale = 'en-US';
	var $maintenance = false;
	
	/* Database Settings */
	var $dbhost = 'localhost';
	var $dbname = '';
	var $dbuser = '';
	var $dbpassword = '';
	var $dbprefix = 'jcms_';
	
	/* User Pages */
	var $user_pages_on = true;
	var $user_pages_type = 'html';
	
	var $components = array('jstore','jcomments');	
}

// SMTP
define('MAIL_ADMIN','admin@yourwebsite.com');
define('MAIL_SMTP_HOST','smtp.gmail.com');
define('MAIL_SMTP_AUTH',true);
define('MAIL_SMTP_SECURE','ssl');
define('MAIL_SMTP_PORT',25);
define('MAIL_SMTP_USERNAME','your_gmail@gmail.com');
define('MAIL_SMTP_PASSWORD','you_password');


// ReCAPTCHA Account
define("RECAPTCHA_DOMAIN", "");
define("RECAPTCHA_PUBLIC_KEY", "");
define("RECAPTCHA_PRIVATE_KEY", "");

// jStore settings
define('JS_STORE_NAME', 'You Store');
define('JS_STORE_CONTACT_EMAIL', 'sales@yourwebsite.com');
define('JS_STORE_CONTACT_EMAIL_NAME', 'Sales');
define('JS_EMAIL_FROM','no-reply@yourwebsite.com');
define('JS_EMAIL_FROM_NAME', 'No-Reply');
define('JS_EMAIL_CC', JS_STORE_CONTACT_EMAIL);
define('JS_EMAIL_CC_NAME', JS_STORE_CONTACT_EMAIL_NAME);
define('JS_EMAIL_SUBJECT_ORDER_PROCESSED', 'Your Store - Your order is being processed');
define('JS_EMAIL_SUBJECT_REGISTRATION', 'Your Store - Your Registration is Pending Activation');
define('JS_CONTACT_MESSAGE', 'Thank for contacting '.JS_STORE_NAME.' our representative will contact you as soon as possible.');

// Set global path
define('GBL_ROOT',dirname(__FILE__));
define('GBL_ROOT_CORE',GBL_ROOT.'/core');
define('GBL_ROOT_CONTENT',GBL_ROOT.'/content');
define('GBL_ROOT_TEMPLATE',GBL_ROOT.'/template');
?>