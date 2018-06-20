<?php
if(!defined('IMPARENT')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access

//echo generate_password('juan123','jan');

include(JSTORE_ROOT.'/core/emailer.php'); // Emailer
include(JSTORE_ROOT.'/core/currency.php'); // Currencies
include(JSTORE_ROOT.'/core/categories.php'); // Store Categories
include(JSTORE_ROOT.'/core/product_info.php'); // Product Info
include(JSTORE_ROOT.'/core/order.php'); // Shopping Cart /Order

define('STORE_URL',BACKEND_DIRECTORY.'/components.php?comp=jstore');
define('STORE_DIRECTORY',get_siteinfo('url',false).'/components/'.JSTORE_ID);

define('STORE_ROOT',dirname(__FILE__));
$_REQUEST['action'] = (isset($_REQUEST['action']) ? $_REQUEST['action'] : '');

/* Create Bread Crumb */
function category_breadcrumb_backend($id,$seperator = '&raquo;', $echo = true){
	$crumb = '';	
	$category = get_category($id,array('ID','slug','title','parent'));	
	$crumb = $category->title;
	if($category->parent > 0){
		$cat_parent = $category->parent;
		$cr = array();
		do{
			$parent = get_category($cat_parent,array('ID','slug','title','parent'));
			$cr[] = '<a href="'.CURRENT_PAGE.'?comp='.JSTORE_ID.'&mod='.$_REQUEST['mod'].'&id='.$parent->ID.'">'.$parent->title.'</a>';
			$cat_parent = $parent->parent;
		} while ($cat_parent > 0);
		krsort($cr);
		$crumb = implode(' '.trim($seperator).' ',$cr).' '.trim($seperator).' '.$crumb;
	}
	$crumb = '<a href="'.CURRENT_PAGE.'?comp='.JSTORE_ID.'&mod='.$_REQUEST['mod'].'">Main</a> '.trim($seperator).' '.$crumb;
	if($echo){
		echo $crumb;
	}else{
		return $crumb;
	}
}
function store_info($key,$echo = true){
	$d = '';
	$username = '';
	if(isset($_SESSION['__FRONTEND_USER'])){
		$username = '/'.$_SESSION['__FRONTEND_USER']['info']->user_name;
	}
	
	if(is_array($key)){
		$arr = $key;
		$key = $arr['key'];
	}
	switch($key){
		case 'login':
			$d = get_option('site_url').'/login';
			break;
		case 'register':
			$d = get_option('site_url').'/register';
			break;
		case 'logout':
			$d = get_option('site_url').'/logout';
			break;
		case 'cart':
			$d = get_option('site_url').'/cart';
			break;
		case 'activate':
			$d = get_option('site_url').'/activate';
			break;
		case 'user':
			$d = get_option('site_url').'/user'.$username;
			break;
		case 'user-settings':
			$d = get_option('site_url').'/user'.$username.'/settings';
			break;
		case 'checkout':
			$d = get_option('site_url').'/cart?action=checkout';
			break;
		case 'welcome':
			$d = get_option('site_url').'/welcome.html';
			break;
		case 'recover':
			$d = get_option('site_url').'/recover?type='.($arr['type']!=''?$arr['type']:'password');
			break;
		case 'best-sellers':
			$d = get_option('site_url').'/best-sellers';
			break;
		case 'top-10':
			$d = get_option('site_url').'/top10';
			break;
		case 'most-recommended':
			$d = get_option('site_url').'/most-recommended';
			break;
		default:
			$d = '';
			break;
	}
	if($echo){
		echo $d;
	}else{
		return $d;
	}
}

function make_taskbar(){?>
	<?php if(is_user_logged() && get_option('store_taskbar') == 'yes'):?>
		<script type="text/javascript">
            $(document).ready(function(){
                $('#taskbar').find('.menu').hover(
                    function(){
                        $(this).addClass('active')
                            .find('ul').removeClass('hide');
                        $(this).find('.dialog').removeClass('hide');
							
                    },
                    function(){
                        $(this).removeClass('active')
                            .find('ul').addClass('hide');
						$(this).find('.dialog').addClass('hide');
                    }
                );
            });
			function ts_empty_cart(){
				if(confirm('Are you sure you want to empty your cart?') == true){
					window.location='<?php store_info('cart');?>?action=clear';
				}			
			}
        </script>
        <div id="taskbar">
            <div class="taskbar-wrapper">
                <div class="menus">
                    <ul>
                        <li class="menu"><a href="<?php get_siteinfo('url');?>"><span>Home</span></a></li>
                        <li class="menu">
                            <a href="<?php store_info('user');?>"><span>My Dashboard</span></a>
                            <ul class="hide">
                                <li><a href="<?php store_info('user-settings');?>">&nbsp;&nbsp;Profile Settings</a></li>
                                <li><a href="<?php store_info('logout');?>">&nbsp;&nbsp;Sign Out</a></li>
                            </ul>
                            <!--div class="dialog hide">
                                <div class="arrow"></div>
                                <div class="content2">
                                    Click this to update your profile and check the status of your order.
                                </div>
                            </div-->
                        </li>
                        <li class="menu">
                            <a class="text" href="<?php store_info('cart');?>"><span>My Shopping Cart</span>
							<?php echo (count($_SESSION['_CART']) > 0 ? '  <span class="count"><div class="sarrow"></div><div class="value">'.count($_SESSION['_CART']).'</div><div class="clear"></div></span>' : '');?></a>
                            <?php if(count($_SESSION['_CART']) > 0):?>
                            <ul class="hide">
                                <li><a href="<?php store_info('cart');?>?action=checkout">&nbsp;&nbsp;Checkout my Cart</a></li>
                                <li><a href="javascript:ts_empty_cart();">&nbsp;&nbsp;Clear my Cart</a></li>                                
                            </ul>
                            <?php endif;?>
                            <!--div class="dialog hide">
                                <div class="arrow"></div>
                                <div class="content2">Click here to view your shopping cart.</div>
                            </div-->
                        </li>
                    </ul>
                    
                    
                    <ul class="right">
                        <li class="menu"><a href="<?php get_siteinfo('url');?>/help"><span>Help</span></a>
                        </li>
                        
                    </ul>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
	<?php endif;?>
<?php }
add_action('taskbar','make_taskbar');

function getCartID($product_id){	
	foreach($_SESSION['_CART'] as $key => $value){
		//echo $value['product_id'].' == '.$product_id.'<br/>';
		if($value['product_id'] == $product_id){
			return $value;
		}
	}
}

// Login
if($pathinfo->script == 'login'){
	if($_REQUEST['action'] == 'user_auth'){
		$login_message = '';
		$_REQUEST['user_name'] = (isset($_REQUEST['user_name']) ? $_REQUEST['user_name'] : '');
		$_REQUEST['user_password'] = (isset($_REQUEST['user_password']) ? $_REQUEST['user_password'] : '');
		$_REQUEST['redirect'] = (isset($_REQUEST['redirect']) ? $_REQUEST['redirect'] : '');
		if(user_login($_REQUEST['user_name'],$_REQUEST['user_password'])){			
			redirect(($_REQUEST['redirect']!='' ? rawurldecode($_REQUEST['redirect']) : get_option('site_url')),'js');
		}else{
			$login_message = 'Invalid Email/Password. Please try again.';
		}
	}
}

/* Recovery Pages */
if($pathinfo->script == 'recover'){	
	if($_REQUEST['type'] == 'password'){ // Password Recovery
		// Request verification
		if($_REQUEST['action'] == 'password_reset_verify'){
			$recover_message = '';	
			if(is_user_exists($_REQUEST['email'])){
				// Create activation code, good for 72 hours
				//$nextday = date('Y-m-d', (time() + (2 * 24 * 60 * 60)));
				$code =  md5('jcms_activation_code@'.$_REQUEST['email']);
				jcms_db_update($GLOBALS['users_tbl_name'],array('activation_code' => $code),"email_address='".$_REQUEST['email']."'");
			
				$user = get_user($_REQUEST['email']);
				$body = file_get_contents(GBL_ROOT_CONTENT.'/mail/user/recovery-password.html');
				$body = eregi_replace("[\]",'',$body);
				$smarty = array(
						'site_name' => get_siteinfo('name',false),
						'site_url' => get_siteinfo('url',false),
						'email_address' => $user->email_address,
						'user_name' => $user->display_name,
						'user_id' => $user->user_name,
						'activation_link' => store_info(array('key'=>'recover','type'=>'password'),false).'&action=reset&code='.$code,
						'activation_code' => $code
					);
				foreach($smarty as $key => $value){$body = str_replace('{'.$key.'}',$value,$body);}
				
				setFrom('no-reply@yourwebsite.com',JS_EMAIL_FROM_NAME);
				setTo($user->email_address,$user->display_name);
				setSubject('Your Website - Password Reset');		
				setContent($body);
				__send_mail('smtp'); // Send email now					
				redirect(store_info(array('key'=>'recover','type'=>'password'),false).'&action=reset','js');
			}else{
				$recover_message = 'Email address invalid or it doesn\'t exists. Please try again.';
			}
		}
		
		// Request verified change password		
		if($_REQUEST['action'] == 'password_reset_verified'){
			$recover_message = '';	
			if(is_user_exists($_REQUEST['activation_code'])){
				$user = get_user($_REQUEST['activation_code']);	
				jcms_db_update($GLOBALS['users_tbl_name'],array('activation_code'=> md5('jcms_activation_code@'.$user->email_address),'user_password' => generate_password($_REQUEST['password'],$user->email_address)),"ID='".$user->ID."'");
				redirect(store_info('login',false),'js');
			}else{
				$recover_message = 'Invalid/Expired code. Please try again.';
			}
		}
	}
}

if($pathinfo->script=='cart'){ // Shopping cart
	// Add new/Update item to cart
	if($_REQUEST['action'] == 'add' || $_REQUEST['action'] == 'update'){
		$req_cart = $_REQUEST['cart'];
		
		// Cart ID		
		$cart_id = $req_cart['cart_id'];
		// Details
		$details = array();
		$details = $req_cart['details'];		
		// Calculations
		$unit_price = $details['price']['unitprice'];
		$quantity = $req_cart['quantity'];
		$total = $details['price']['total'];
		
		$new_item = array(
						'cart_id' => $cart_id,
						'product_id' => $req_cart['product_id'],
						'name' => $req_cart['name'],
						'cart_id' => $cart_id,
						'unit_price' => strval($unit_price),
						'quantity' => $quantity,
						'sub_total' => strval($total),
						'details' => $details,
						'raw' => array(
									'item_price' => $unit_price,
									'sub_total' => strval($total)
								)
						);
		if($_REQUEST['action'] == 'update'){
			unset($_SESSION['_CART'][$cart_id]);
		}
		$_SESSION['_CART'][$cart_id] = $new_item;		
		
		redirect(store_info('cart',false).($_REQUEST['continue']!=''?'?continue='.rawurlencode($_REQUEST['continue']):''),'js');
	}

	// Clear or remove item from cart
	if($_REQUEST['action'] == 'clear'){ 
		if(array_key_exists($_REQUEST['id'],$_SESSION['_CART'])){
			unset($_SESSION['_CART'][$_REQUEST['id']]);
		}else{
			unset($_SESSION['_CART']);
		}
		redirect(store_info('cart',false),'js');
	}
	
	// Checkout
	if($_REQUEST['action'] == 'checkout' && is_user_logged() && (count($_SESSION['_CART']) > 0 || isset($_SESSION['_CART']))){ 
		$user = $_SESSION['__FRONTEND_USER']['info'];
		$user_meta = (object) unserialize(stripslashes($user->meta));
		if($user->email_address!=''){		
			$trans_id = md5('jstore_transid@'.$user->ID.'@'.date('mdYHis'));
			$_order = update_order(array('trans_id'=> $trans_id,'user_id' => $user->ID,'order_meta' => serialize($_SESSION['_CART'])));
	
			if($_order->ID > 0){			
				$total_amount = '0.00';
				$items = '';
				$phone = '';
				$cart_items = $_SESSION['_CART'];
				
				foreach($cart_items as $key => $value){
					$quantity = $value['quantity'];
					$item_price = $value['unit_price'];
					$total = number_format(trim(sprintf('%132lf',(intval($quantity) * intval($item_price)))),2,'.',',');	
					$total_amount = $total_amount + ($quantity * $item_price);
					
					$items.= '<tr><td width="10%" style="padding:3px 0 3px 5px;text-align:center;color:#333333;">'.$quantity.'</td><td width="70%" style="padding:3px 0 3px 5px;text-align:left;color:#333333;">'.$value['name'].'</td><td width="20%" style="padding:3px 0 3px 5px;text-align:center;color:#333333;">$'.$total.'</td></tr>';
				}
				if($user_meta->primary_phone!=''){
					$phone ='<tr><td style="text-align:right;width:140px;font-weight:bold;vertical-align:top;padding-bottom:5px;padding-right:10px;color:#333333;">Phone:</td><td style="width:500px;vertical-align:top;padding-bottom:5px;color:#084482;">'.$user_meta->primary_phone.($user_meta->phone_extension_no!=''?' Ext. '.$user_meta->phone_extension_no:'').'</td></tr>';
				}
				
				$body = file_get_contents(GBL_ROOT_CONTENT.'/mail/cart/checkout.html');
				$body = eregi_replace("[\]",'',$body);
				$smarty = array(
					'transaction_id' => $trans_id,
					'ship_name' => $user_meta->first_name.' '.$user_meta->last_name,
					'ship_address' => $user_meta->street1.($user_meta->street2!=''?', '.$user_meta->street2:''),
					'ship_city' => $user_meta->city,
					'ship_state' => ($user_meta->state == 'non-US' ? '': $user_meta->state),
					'ship_zipcode' => $user_meta->zip,
					'ship_country' => $user_meta->country,
					'ship_phone' => $phone,
					'ship_email' => $user->email_address,
					'items' => $items,
					'admin' => '',
					'shipping' => '',
					'tax' => '',
					'total_payable' => number_format(trim(sprintf('%132lf',$total_amount)),2,'.',','),
					'site_name' => get_siteinfo('name',false),
					'site_url' => get_siteinfo('url',false),
					'contact_us' => get_siteinfo('url',false).'/contact-us.html'
				);
				foreach($smarty as $key => $value){$body = str_replace('{'.$key.'}',$value,$body);}				
				
				setFrom(JS_EMAIL_FROM,JS_EMAIL_FROM_NAME);
				setTo($user->email_address,$user->display_name);
				setCC(JS_EMAIL_CC, JS_EMAIL_CC_NAME);
				setSubject(JS_EMAIL_SUBJECT_ORDER_PROCESSED);
				setContent($body);
				__send_mail('smtp'); // Send email now
				
				// Clear cart
				unset($_SESSION['_CART']);
				redirect(store_info('cart',false).'?action=thankyou','js');
			}else{
				// redirect to error page if email address is not set
				redirect(store_info('cart',false).'?action=error','js');
			}
		}else{
			redirect(store_info('cart',false).'?action=error','js');
		}
	}
}



add_filter('meta_description','product_description');
add_filter('meta_keywords','product_keyword');

function product_description(){
}
function product_keyword(){
}
/* Register Component */
register_component('jstore','jStore',JSTORE_VERSION,'My Store','Store components of jCMS, jStore v'.JSTORE_VERSION);
add_component_module('jstore','item','My Products');
add_component_module('jstore','order','Order');
/*add_component_module('jstore','shipping-tax','Shipping &amp; Tax');
add_component_module('jstore','emailer','Emailer');*/
add_component_module('jstore','settings','Settings');
?>