<?php
if(!defined('IMPARENT')){exit();} // No direct access

/* JCMS Setup */
/** This script will create and preset some values on the database if is run for the
 *  first time.
 */

$page_tbl_name = '#_pages'; // Pages Table
$option_tbl_name = '#_options'; // Option Table
$users_tbl_name = '#_users'; // Option Table
$media_tbl_name = '#_media'; // Media Table
$media_album_tbl_name = '#_media_album'; // Media Album Table
$widgets_tbl_name = '#_widgets'; // Widgets Table

/* DB Table constructor */
function _db_table_construct(){
	global $config;
	/* Page */
	if(!jcms_db_is_table_exists($GLOBALS['page_tbl_name'])){
		$structure = "ID BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,slug VARCHAR(140) NOT NULL, title VARCHAR(100) NOT NULL, content LONGTEXT NOT NULL, page_type VARCHAR(10) NOT NULL DEFAULT 'page', author BIGINT(20) NOT NULL,parent_page BIGINT(20) NOT NULL DEFAULT '0',status VARCHAR(10) NOT NULL DEFAULT 'published', date_created DATETIME NOT NULL, date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, menu_order VARCHAR(50) NOT NULL, meta LONGTEXT NOT NULL, page_key VARCHAR(50) NOT NULL, UNIQUE (slug)";
		if(jcms_db_create_table($GLOBALS['page_tbl_name'],$structure)){
			$data = array(
				'slug' => 'about',
				'title' => 'About this Page',
				'content' => 'This is a sample About this page test.',
				'page_type' => 'page',
				'author' => '1',
				'date_created' => date('Y-m-d h:i:s A')	
			);			
			jcms_db_insert_row($GLOBALS['page_tbl_name'],$data);
			
			$data = array(
				'slug' => 'hello-world',
				'title' => 'Hello World',
				'content' => 'Hello World',
				'page_type' => 'post',
				'author' => '1',
				'date_created' => date('Y-m-d h:i:s A')	
			);			
			jcms_db_insert_row($GLOBALS['page_tbl_name'],$data);
		}
	}
	
	/* Option */
	if(!jcms_db_is_table_exists($GLOBALS['option_tbl_name'])){
		$structure = "ID BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY, option_key VARCHAR(100) NOT NULL, option_value LONGTEXT NOT NULL, load_on VARCHAR(20) NOT NULL DEFAULT 'startup', UNIQUE (option_key)";
		if(jcms_db_create_table($GLOBALS['option_tbl_name'],$structure)){
			$data = array('option_key' => 'banned_ip','option_value' => '');			
			jcms_db_insert_row($GLOBALS['option_tbl_name'],$data);
			
			$data = array('option_key' => 'banned_username','option_value' => '');
			jcms_db_insert_row($GLOBALS['option_tbl_name'],$data);
			
			$data = array('option_key' => 'site_name','option_value' => $config->site_name);
			jcms_db_insert_row($GLOBALS['option_tbl_name'],$data);			
			
			$data = array('option_key' => 'site_description','option_value' => $config->site_description);
			jcms_db_insert_row($GLOBALS['option_tbl_name'],$data);
			
			$data = array('option_key' => 'site_url','option_value' => $config->site_url);
			jcms_db_insert_row($GLOBALS['option_tbl_name'],$data);
			
			$data = array('option_key' => 'admin_email','option_value' => $config->admin_email);
			jcms_db_insert_row($GLOBALS['option_tbl_name'],$data);

			if(function_exists('date_default_timezone_set')){
				$data = array('option_key' => 'time_zone','option_value' => $config->timezone);
				jcms_db_insert_row($GLOBALS['option_tbl_name'],$data);
			}
			
			$data = array('option_key' => 'date_format','option_value' => 'd/m/Y');
			jcms_db_insert_row($GLOBALS['option_tbl_name'],$data);
			
			$data = array('option_key' => 'time_format','option_value' => 'F j, Y');
			jcms_db_insert_row($GLOBALS['option_tbl_name'],$data);
			
			$data = array('option_key' => 'weeks_starts_on','option_value' => '01');
			jcms_db_insert_row($GLOBALS['option_tbl_name'],$data);
		}
	}
	
	
	/* Users */
	if(!jcms_db_is_table_exists($GLOBALS['users_tbl_name'])){
		$structure = "ID BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY, user_name VARCHAR(20) NOT NULL, user_password VARCHAR(100) NOT NULL, display_name VARCHAR(50) NOT NULL, email_address VARCHAR(100) NOT NULL, user_group VARCHAR(100) NOT NULL, user_sex VARCHAR(100) NOT NULL, backend_access VARCHAR(10) NOT NULL DEFAULT 'no', status VARCHAR(10) NOT NULL DEFAULT '0', activation_code VARCHAR(100) NOT NULL, meta LONGTEXT NOT NULL, date_registered DATETIME NOT NULL, ip_address VARCHAR(100) NOT NULL, UNIQUE (user_name)";
		if(jcms_db_create_table($GLOBALS['users_tbl_name'],$structure)){
			$data = array(
				'user_name' => 'admin',
				'user_password' => md5('jcms:ambotlang!@!'.md5('admin')),
				'display_name' => 'Administrator',
				'email_address' => $config->admin_email,
				'user_group' => 'ghosts',
				'user_sex' => '',
				'backend_access' => 'yes',
				'status' => '1',
				'activation_code' => md5('jcms_activation_code@'.$config->admin_email),
				'meta' => '',
				'date_registered' => date('Y-m-d h:i:s A'),
				'ip_address' => get_ipaddress()
			);			
			jcms_db_insert_row($GLOBALS['users_tbl_name'],$data);			
		}
	}
	
	/* Media Album */
	/*if(!jcms_db_is_table_exists($GLOBALS['media_album_tbl_name'])){
		$structure = "ID BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY, album_slug VARCHAR(100) NOT NULL, album_name VARCHAR(100) NOT NULL, album_description LONGTEXT NOT NULL, date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, private VARCHAR(10) NOT NULL DEFAULT 'no', meta LONGTEXT NOT NULL, UNIQUE (album_slug)";

		if(jcms_db_create_table($GLOBALS['media_album_tbl_name'],$structure)){
			$data = array(
				'album_slug' => 'images',
				'album_name' => 'Images',
				'album_description' => 'All images',
				'date_added' => date('Y-m-d h:i:s A'),
				'private' => 'no',
				'meta' => ''
			);			
			jcms_db_insert_row($GLOBALS['media_album_tbl_name'],$data);	
			$data = array(
				'album_slug' => 'video',
				'album_name' => 'Video',
				'album_description' => 'All Videos',
				'date_added' => date('Y-m-d h:i:s A'),
				'private' => 'no',
				'meta' => ''
			);			
			jcms_db_insert_row($GLOBALS['media_album_tbl_name'],$data);	
		}
	}
	if(!jcms_db_is_table_exists($GLOBALS['media_tbl_name'])){
		$structure = "ID BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY, media_slug VARCHAR(100) NOT NULL, media_name VARCHAR(100) NOT NULL, media_description LONGTEXT NOT NULL, date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, private VARCHAR(10) NOT NULL DEFAULT 'no', meta LONGTEXT NOT NULL, UNIQUE (media_slug)";
		jcms_db_create_table($GLOBALS['media_tbl_name'],$structure);
	}*/
	
	/* Widgets */
	if(!jcms_db_is_table_exists($GLOBALS['widgets_tbl_name'])){
		$structure = "ID BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY, widget_slug VARCHAR(100) NOT NULL, widget_name VARCHAR(100) NOT NULL, widget_code LONGTEXT NOT NULL, date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, meta LONGTEXT NOT NULL, UNIQUE (widget_slug)";
		jcms_db_create_table($GLOBALS['widgets_tbl_name'],$structure);
	}	
}

add_action('jcms_init','_db_table_construct','high');
?>