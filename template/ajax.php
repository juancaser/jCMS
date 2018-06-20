<?php
define('IMPARENT',true);
define('JCMS',true);

session_start();

/* Load Config */
include('../configuration.php');
$config = new Configuration();

/** FUNCTIONS */
include(GBL_ROOT_CORE.'/mail/mail.php'); // PHPMailer

include(GBL_ROOT_CORE.'/general.php'); // General
include(GBL_ROOT_CORE.'/db.class.php');  // Database
include(GBL_ROOT_CORE.'/setup.php'); // Setup
include(GBL_ROOT_CORE.'/options.php'); // Options

if(NET == 'ONLINE' && get_option('ga_email')!='' && get_option('ga_password')!='' && get_option('ga')=='yes'){
	include(GBL_ROOT_CORE.'/gapi.class.php'); // Google API
	$ga = new gapi(get_option('ga_email'),get_option('ga_password'));
}


include(GBL_ROOT_CORE.'/widgets.php'); // Widgets

/* Set Timezone */
if(function_exists('date_default_timezone_set')){	
	date_default_timezone_set((get_option('time_zone') !='' ? get_option('time_zone') : $config->timezone));
}


include(GBL_ROOT_CORE.'/users.php'); // User
include(GBL_ROOT_CORE.'/pages.php'); // Page
include(GBL_ROOT_CORE.'/components.php'); // Components

do_action('jcms_init'); // Initialization


function checkEmail($email){
	if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){
		return true;
	}else{
		return false;
	}
}


/* User Checking and Validation*/
if($_REQUEST['action'] == 'check' && $_REQUEST['data']!=''){
	if($_REQUEST['type'] == 'user_name'){
		$slug = make_slug($_REQUEST['data']);
		$user = jcms_db_get_row("SELECT TRUE AS isfound FROM ".$GLOBALS['users_tbl_name']." WHERE user_name='".mysql_escape_string(trim($slug))."'");		
		if($user->isfound || $user->isfound == '1'){
			$status = 1;
		}else{
			$status = 0;
		}
		echo '{"status":"'.$status.'"}';
	}
	
	if($_REQUEST['type'] == 'display_name'){
		$user = jcms_db_get_row("SELECT TRUE AS isfound FROM ".$GLOBALS['users_tbl_name']." WHERE display_name='".trim($_REQUEST['data'])."'");		
		if($user->isfound || $user->isfound == '1'){
			$status = 1;
		}else{
			$status = 0;
		}
		echo '{"status":"'.$status.'"}';
	}
	
	if($_REQUEST['type'] == 'email_address'){
		$user = jcms_db_get_row("SELECT TRUE AS isfound FROM ".$GLOBALS['users_tbl_name']." WHERE email_address='".mysql_escape_string(trim($_REQUEST['data']))."'");		
		if($user->isfound || $user->isfound == '1'){
			$status = 1;
		}else{
			$status = 0;
		}
		echo '{"status":"'.$status.'"}';
	}
}

/* General Validation */
if($_REQUEST['action'] == 'validate' && $_REQUEST['data']!=''){
	if($_REQUEST['type'] == 'email'){		
		if(checkEmail($_REQUEST['data'])){
			$status = 'VALID';
			$message = 'Email address is valid';
		}else{			
			$status = 'INVALID';
			$message = 'Email address is invalid';
		}
		echo '{"status":"'.$status.'","message":"'.$message.'"}';
	}
}

if($_REQUEST['action'] == 'product_info' && $_REQUEST['id'] > 0){
	$product = jcms_db_get_row("SELECT * FROM #_store_product_info WHERE ID='".mysql_escape_string(trim($_REQUEST['id']))."'");
	$meta = (object) unserialize($product->meta);
	$items = $meta->items;
	$itm = '';
    for($i=0;$i < count($items);$i++){
		$item = (object) $items[$i];
		if($item->id == $_REQUEST['item_id']){
			$itm = $item;
		}
	}
	echo '
		<div style="padding:0 10px;">
			<h4 style="font-weight:normal;font-size:16px;">'.$product->item_name.'</h4>
			<p style="padding-bottom:5px;">'.$itm->description.'<p>
			<p style="padding-bottom:5px;"><label>Quantity:<br /><input style="text-align:center;width:20px;" type="text" value="1" /></label></p>
			<p style="padding-bottom:5px;"><label>Notes/Special Instructions:<br /><textarea style="width:380px;height:50px;"></textarea></label></p>
		</div>
	';
}

if($_REQUEST['action'] == 'cart_estimate' && $_REQUEST['price'] !=''){
	$total = '';
	if(intval($_REQUEST['qty']) > 0){
		$total = trim(sprintf('%132lf',(intval($_REQUEST['qty']) > 0 ? (intval($_REQUEST['qty']) * $_REQUEST['price']) : $_REQUEST['price'])));
		$total = number_format($total,2,'.',',');
	}else{
		$total = trim($_REQUEST['price']);
	}	
	echo $total;
}

if($_REQUEST['action'] == 'product_estimate'){
	$estimate = array();
	$total = '';
	$ranged = false;

	$sql = "SELECT item_price FROM #_store_product_info WHERE ID='".mysql_escape_string(trim($_REQUEST['product']['item_id']))."'";
	$product = jcms_db_get_row($sql);
	$price_data = (object) unserialize($product->item_price);
	$quantity = intval(trim($_REQUEST['product']['quantity']));
	if(is_numeric($quantity)){
		$estimate['status'] = 'OK';	
		$estimate['bulk'] = 'no';
		if($quantity > 0){
			// Bulk prices
			if(count($price_data->range) > 0){
				for($i=1;$i <= count($price_data->range);$i++){
					if($price_data->range[$i]['quantity'] == $quantity && !$ranged){
						$total = sprintf('%132lf',$price_data->range[$i]['price']);
						$price = $price_data->range[$i]['price'];
						$ranged = true;
						$estimate['bulk'] = 'yes';
					}
                }
				if(!$ranged){
					$price = trim($_REQUEST['product']['price']);					
					$total = sprintf('%132lf',(($quantity) > 0 ? ($quantity * $price) : $price));
				}
			}else{
				$total = sprintf('%132lf',(($quantity) > 0 ? ($quantity * $price) : $price));
			}
			
			$estimate['price'] = number_format($total,2,'.',',');
		}else{
			$estimate['price'] = trim($price);
		}
		$estimate['quantity'] = $quantity;
		$estimate['total'] = $estimate['price'];	
	}else{
		$estimate['status'] = 'NO';
	}
	
	echo json_encode($estimate);
}

if($_REQUEST['action'] == 'update_cart'){
	$estimate = array();
	$total = '';
	$estimate['status'] = 'OK';
	if(intval($_REQUEST['quantity']) > 0){
		$total = trim(sprintf('%132lf',(intval($_REQUEST['quantity']) > 0 ? (intval($_REQUEST['quantity']) * $_REQUEST['price']) : $_REQUEST['price'])));
		$estimate['amount'] = number_format($total,2,'.',',');
		$_SESSION['_CART'][$_REQUEST['id']]['quantity'] = intval($_REQUEST['quantity']);
	}else{
		$estimate['amount'] = trim($_REQUEST['price']);		
	}
	$t = 0;
	foreach($_SESSION['_CART'] as $key => $value){
		$t = $t + ($value['price'] * $value['quantity']);
	}
	$estimate['total'] = number_format(trim(sprintf('%132lf',$t)),2,'.',',');
	
	echo json_encode($estimate);
}



if($_REQUEST['action'] == 'cart'){
	if($_REQUEST['req'] == 'calculate' && $_REQUEST['id'] > 0 && ($_REQUEST['quantity'] > 0 && !empty($_REQUEST['quantity']))){ // Calculate Cart
		$quantity = intval($_REQUEST['quantity']);
		
		$product_item = jcms_db_get_row("SELECT item_price,meta FROM #_store_product_info WHERE ID='".$_REQUEST['id']."'");
		
		$product_item->item_description =  html_entity_decode($product_item->item_description);
		$product_item->item_price = unserialize($product_item->item_price);
		$product_item->meta = unserialize($product_item->meta);
		
		// Get Quantities
		$quantities =  array();
		foreach($product_item->item_price['bulk'] as $key => $value){
			$quantities[] = $key;
		}
		//FB::log($quantities[count($quantities) - 1]);
		if($quantity >= $quantities[count($quantities) - 1]){
			//FB::log($quantities[count($quantities) - 1]);
		}else{
			//FB::log('Here2');
			for($i=0;$i <= count($quantities);$i++){
				$q1 = $quantities[$i];
				$q2 = $quantities[$i];
			}
		}
	}
}
if($_REQUEST['action'] == 'post_comment'){
	if(get_option('comment_moderation') == 'yes'){
		$_REQUEST['comment']['comment_status'] = 'pending';
	}else{
		$_REQUEST['comment']['comment_status'] = 'published';
	}
	$_REQUEST['comment']['ipaddress'] = get_ipaddress();
	
	$author_website = $_REQUEST['comment']['meta']['website'];
	if(!isset($author_website) || $author_website == ''){
		$_REQUEST['comment']['meta']['website'] = 'NA';
		$author_website = 'NA';
	}
	$_REQUEST['comment']['meta'] = serialize($_REQUEST['comment']['meta']);
	$_REQUEST['comment']['comment_message'] = htmlentities(nl2br($_REQUEST['comment']['comment_message']));
	$_REQUEST['comment']['commented_date'] = date('Y-m-d H:i:s A');
	
	$author_name = false;
	if($_REQUEST['comment']['comment_author_name'] != ''){
		$_REQUEST['comment']['comment_author'] = $_REQUEST['comment']['comment_author_name'];
		$author_name = true;
	}
	$fields = array(
		'ID',
		'comment_type',
		'page_id',
		'comment_message',
		'comment_status',
		'comment_author',
		'commented_date',
		'meta',
		'ipaddress'
	);
	$data = array();
	foreach($_REQUEST['comment'] as $key => $value){
		if(in_array($key,$fields)){
			$data[$key] = $value;
		}
	}
	$id = jcms_db_insert_row('#_comments',$data);
	if($id > 0){
		if(get_option('comment_moderation') == 'yes'){
			echo json_encode(array('status' => '2'));
		}else{
			if($author_name){
				$comment = jcms_db_get_row("SELECT *,DATE_FORMAT(commented_date,'%M %e, %Y at %h:%i:%s %p') AS comment_date,comment_author AS author FROM #_comments WHERE ID='".$id."' AND comment_status='published'");
			}else{
				$comment = jcms_db_get_row("SELECT c.*,DATE_FORMAT(c.commented_date,'%M %e, %Y at %h:%i:%s %p') AS comment_date,u.display_name AS author FROM #_comments AS c LEFT JOIN #_users AS u ON u.ID=c.comment_author WHERE c.ID='".$id."' AND c.comment_status='published'");
			}
		
			// Comment date
			$comment_date = sanitize_comment_date($comment->commented_date,false);
								  
			// Author
			$author = ($comment->author!='' ? $comment->author : ($comment->comment_author !='' ? $comment->comment_author : 'Anonymous'));
            $website = (trim($author_website)=='NA' ? '' : trim($author_website));								
			$author = sanitize_author_name($author,$website,false);
			
			// Comment Message
			$message = sanitize_comment_message(html_entity_decode(nl2br($comment->comment_message)),false);
			
			echo json_encode(
							array(
								  'status' => '1',
								  'id' => $comment->ID,
								  'author' => $author,
								  'message' => $message,
								  'date' => $comment_date
							)
						);
		}
	}else{
		echo json_encode(array('status' => '0'));
	}
}
if($_REQUEST['action'] == 'reply_comment' && $_REQUEST['id'] > 0){
	$commented = jcms_db_get_row("SELECT ID,comment_message FROM #_comments WHERE ID='".$_REQUEST['id']."' AND comment_status='published'");
	$message = html_entity_decode(nl2br($commented->comment_message));
	$message = strip_tags($message,'<span>');
	$message = preg_replace('|\[comment id="(.*)"\](.*)\[/comment\]|U','',$message);
	//
	echo '[comment id="'.$commented->ID.'"]'.$message.'[/comment]';

}

if($_REQUEST['action'] == 'product-tab'){	
	if(file_exists(GBL_ROOT_TEMPLATE.'/component/jstore/modules/product/'.$_REQUEST['mod'].'.php')){
		include(GBL_ROOT_TEMPLATE.'/component/jstore/modules/product/'.$_REQUEST['mod'].'.php');
	}else{
		echo 'Module not found.';
	}
}
do_action('jcms_close'); // Exiting
?>