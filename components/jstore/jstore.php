<?php
if(!defined('IMPARENT')){exit();} // No direct access

define('JSTORE',true);
define('JSTORE_ROOT',dirname(__FILE__));
define('JSTORE_ID','jstore');
define('JSTORE_VERSION','1.0');

global $config,$pathinfo,$page;
include(JSTORE_ROOT.'/functions.php');
$uri = parse_url($_SERVER['REQUEST_URI']);
$uri['path'] = str_replace('//','/',$uri['path']);

if(substr($uri['path'],strlen($uri['path'])-1,1) == '/'){
	$uri['path'] = substr($uri['path'],0,strlen($uri['path'])-1);
}
$current_page = trim(get_option('site_url').$uri['path']);

// Initialize Cart Session
if(get_option('store_shopping_cart') =='yes' && is_user_logged() && !isset($_SESSION['_CART'])){
	$_SESSION['_CART'] = array();
}

$username = '';
if(isset($_SESSION['__FRONTEND_USER'])){
	$userinfo = $_SESSION['__FRONTEND_USER']['info'];
	$username = '/'.$userinfo->user_name;
}
if($pathinfo->dirs[0] != 'backend'){
	if($current_page == category_url($pathinfo->filename,false)){ // Category
		$category = get_category($pathinfo->filename,array('ID','title','description','meta'));
		$meta = unserialize($category->meta);
		_page_constructor(array(
			'ID' => $category->ID,
			'slug' => 'category',
			'title' => stripslashes($category->title),
			'content' => html_entity_decode($category->description),
			'page_type' => 'component',
			'component' => JSTORE_ID,
			'meta' => serialize(array(
						'meta_description' => ($meta['meta_description']!=''?$meta['meta_description']:''),
						'meta_keywords' => ($meta['meta_keywords']!=''?$meta['meta_keywords']:'')
					))
		));
	}elseif($current_page == item_url($pathinfo->filename,false)){ // Product
		$product = get_product_info($pathinfo->filename);
		$meta = unserialize($product->meta);

		$new_meta = array_merge((array)$product,array(
						'meta_description' => ($meta['meta_description']!=''?$meta['meta_description']:''),
						'meta_keywords' => ($meta['meta_keywords']!=''?$meta['meta_keywords']:'')
					));
		_page_constructor(array(
			'ID' => $product->ID,
			'slug' => 'product',
			'title' => stripslashes($product->item_name),
			'content' => html_entity_decode($product->item_description),
			'page_type' => 'component',
			'component' => JSTORE_ID,
			'meta' => serialize($new_meta)

		));
	}elseif($current_page == get_option('site_url').'/cart' && get_option('store_shopping_cart') =='yes'){ // Shopping Cart
		if(is_user_logged()){
			_page_constructor(array(
				'ID' => '-1',
				'slug' => 'cart',
				'title' => 'Shopping Cart',
				'page_type' => 'component',
				'component' => JSTORE_ID,
				'meta' => ''
			));
		}else{
			redirect(get_option('site_url').'/login?redirect='.rawurlencode(get_option('site_url').$_SERVER['REQUEST_URI']),'js');
		}
	}elseif($current_page == get_option('site_url').'/login'){ // Login
		if(!isset($_SESSION['__FRONTEND_USER'])){
			_page_constructor(array(
				'ID' => '-1',
				'slug' => 'login',
				'title' => 'Login',
				'page_type' => 'component',
				'component' => JSTORE_ID,
				'meta' => ''
			));
		}else{
			redirect(get_option('site_url'),'js');
		}
	}elseif($current_page == get_option('site_url').'/register' && get_option('store_user_registration') =='on'){ // Register		
		if(!isset($_SESSION['__FRONTEND_USER'])){
			_page_constructor(array(
				'ID' => '-1',
				'slug' => 'register',
				'title' => 'Register',
				'page_type' => 'component',
				'component' => JSTORE_ID,
				'meta' => ''
			));
		}else{
			redirect(get_option('site_url'),'js');
		}
	}elseif($current_page == get_option('site_url').'/logout'){ // Logout			
		unset($_SESSION['__FRONTEND_USER']);
		unset($_SESSION['_CART']);
		unset($_SESSION['_CONTINUE_URL']);
		redirect(get_option('site_url'),'js');
	}elseif($current_page == get_option('site_url').'/user'.$username){ // User Dashboard
		_page_constructor(array(
			'ID' => '-1',
			'slug' => 'user',
			'title' => 'My Dashboard',
			'page_type' => 'component',
			'component' => JSTORE_ID,
			'meta' => serialize($userinfo)
		));
		if($_REQUEST['action'] == 'update'){
			$user_meta = unserialize(stripslashes($_SESSION['__FRONTEND_USER']['info']->meta));
			$data = array();
			$data['id'] = $_SESSION['__FRONTEND_USER']['info']->ID;
			$data['display_name'] = $_REQUEST['user']['display_name'];
			$data['user_name'] = $_REQUEST['user']['email_address'];			
			if($_REQUEST['user']['user_password']!=''){
				$data['user_password'] = $_REQUEST['user']['user_password'];
			}
			$data['email_address'] = $_REQUEST['user']['email_address'];
			$data['ip_address'] = get_ipaddress();
			$meta = array();
			foreach($user_meta as $key => $value){
				if(array_key_exists($key,$_REQUEST['user']['meta'])){
					$meta[$key] = $_REQUEST['user']['meta'][$key];
				}else{
					$meta[$key] = $value;
				}
			}
			$data['meta'] = serialize($_REQUEST['user']['meta']);

			if(update_user($data)){
				$_SESSION['__FRONTEND_USER']['info'] = jcms_db_get_row("SELECT * FROM ".$GLOBALS['users_tbl_name']." WHERE ID='".$data['id']."'");
				$_SESSION['USER_UPDATE'] = 'Profile Settings successfully updated.';
			}else{
				$_SESSION['USER_UPDATE'] = 'Error occured while saving. Please try again.';
			}
			$_SESSION['USER_UPDATE_COUNT'] = 1;
			redirect(store_info('user',false),'js');
			
		}
	}elseif($current_page == get_option('site_url').'/user'.$username.'/settings'){ // User settings
		_page_constructor(array(
			'ID' => '-1',
			'slug' => 'user',
			'title' => 'Profile Settings',
			'page_type' => 'component',
			'component' => JSTORE_ID,
			'meta' => serialize($userinfo)
		));
	}elseif($pathinfo->dirs[0] == 'user'){ // If not log, redirect to login
		redirect(store_info('login',false),'js');
	}elseif($current_page == get_option('site_url').'/welcome.html'){ // Welcome
		if($_SESSION['REGISTER'] == 'SUCCESS'){
			_page_constructor(array(
				'ID' => '-1',
				'slug' => 'welcome',
				'title' => 'Welcome to '.get_option('site_name'),
				'page_type' => 'component',
				'component' => JSTORE_ID,
				'meta' => ''
			));
		}else{
			redirect(store_info('register',false),'js');
		}
	}elseif($current_page == get_option('site_url').'/activate'){ // Activate
		$content = '';
		if(is_user_exists($_REQUEST['code'],false)){
			if(activate_user($_REQUEST['code'])){
				$user = get_user($_REQUEST['code']);				
				$body = file_get_contents(GBL_ROOT_CONTENT.'/mail/user/registration-welcome.html');
				$body = eregi_replace("[\]",'',$body);
				$smarty = array(
						'site_name' => get_siteinfo('name',false),
						'site_url' => get_siteinfo('url',false),
						'email_address' => $user->email_address,
						'user_name' => $user->display_name
					);
				foreach($smarty as $key => $value){$body = str_replace('{'.$key.'}',$value,$body);}
				
				setFrom(JS_EMAIL_FROM,JS_EMAIL_FROM_NAME);
				setTo($user->email_address,$user->display_name);
				setCC(JS_EMAIL_CC, JS_EMAIL_CC_NAME);
				setSubject(JS_EMAIL_SUBJECT_ORDER_PROCESSED);
				setContent($body);
				__send_mail('smtp'); // Send email now
				

					
				$_SESSION['REGISTER'] = 'SUCCESS';
				redirect(store_info('welcome',false),'js');
			}else{
				$_SESSION['REGISTER'] = 'FAILED';
				$content = 'Error occcured while activating. Please try again.';
			}
		}else{
			if($_REQUEST['action'] == 'activate'){
				if($_REQUEST['code'] == ''){
					$content = 'Activation code field empty.';
				}else{
					$content = 'Invalid Activation code! please try again.';
				}				
			}			
		}
		_page_constructor(array(
			'ID' => '-1',
			'slug' => 'activate',
			'title' => 'Activate your account ',
			'page_type' => 'component',
			'content' => $content,
			'component' => JSTORE_ID,
			'meta' => ''
		));
	}elseif($current_page == get_option('site_url').'/best-sellers'){ // Best Sellers Page
		_page_constructor(array(
			'ID' => '-1',
			'slug' => 'best-sellers',
			'title' => 'Best Sellers ',
			'page_type' => 'component',
			'content' => '',
			'component' => JSTORE_ID,
			'meta' => ''
		));
	}elseif($current_page == get_option('site_url').'/recover'){ // Recovery Page
		$content = '';
		$meta = array();
		$title = '';
		
		// Action
		/*if($_REQUEST['recover'] == 'userid'){

		}elseif($_REQUEST['recover'] == 'reset-password'){
			if(update_user(array('user_password' => generate_password($_REQUEST['email'],$_REQUEST['userid'])))){
				redirect(store_info('login',false),'js');
			}else{
				$meta['error'] = 'Error occured while resetting your password. Please try again.';
			}
		}*/
		
		if($_REQUEST['type']!=''){
			switch($_REQUEST['type']){
				case 'userid':
					$title = 'Forgot your User ID?';
					break;
				case 'password':
					$title = 'Forgot your Password?';
					break;
				case 'password-reset':
					// Check code if valid
					if(is_user_exists(rawurldecode($_REQUEST['email'])) && is_user_exists($_REQUEST['code']) && is_user_exists(rawurldecode($_REQUEST['userid']))){
						// Do nothing

					}else{
						// Redirect to login
						redirect(store_info('login',false),'js');
					}
					$title = 'Reset your password ';
					break;
				case 'password-reset-success':
					$title = 'Password reset successful ';
					break;
				case 'userid-retrieve-success':
					$title = 'User ID retrieval successful ';
					break;
			}
			if(file_exists(GBL_ROOT_TEMPLATE.'/component/jstore/modules/recovery/'.$_REQUEST['type'].'.php')){
				ob_start();
				include(GBL_ROOT_TEMPLATE.'/component/jstore/modules/recovery/'.$_REQUEST['type'].'.php');
				$content = ob_get_clean();
			}else{
				$content = "'".$_REQUEST['type']."' module not found!. Please contact the web administrator immediately.";
			}
			_page_constructor(array(
				'ID' => '-1',
				'slug' => 'recover',
				'title' => $title,
				'content' => $content,
				'page_type' => 'component',
				'component' => JSTORE_ID,
				'meta' => serialize($meta)
			));
		}else{
			redirect(store_info('login',false),'js');
		}
		/*if($_REQUEST['recover'] == 'password' && $_REQUEST['user_name']!='' && $_REQUEST['email']!=''){
		}else{
			$content = 'Email/Username field empty. Please try again.';
		}
		if($_REQUEST['type']!=''){

			_page_constructor(array(
				'ID' => '-1',
				'slug' => 'recover',
				'title' => 'Forgot your '.($_REQUEST['type'] == 'userid' ? 'User ID' : 'Password').'? &#8212; ',
				'page_type' => 'component',
				'component' => JSTORE_ID,
				'meta' => ''
			));
		}else{
			redirect(store_info('login',false),'js');
		}*/
	}
	/* redirection */
	if(isset($_SESSION['__FRONTEND_USER'])){ // Logged
	}else{
		if($pathinfo->dirs[0] == 'users'){ /* My Account*/
			redirect(store_info('login',false),'js');
		}
	}
}
?>