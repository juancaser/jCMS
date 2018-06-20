<?php
define('IMPARENT',true);
define('JCMS',true);

session_start();

/* Load Config */
include('../../../configuration.php');
$config = new Configuration();

/** FUNCTIONS */
include(GBL_ROOT_CORE.'/mail/mail.php'); // PHPMailer

include(GBL_ROOT_CORE.'/general.php'); // General
include(GBL_ROOT_CORE.'/db.class.php');  // Database
include(GBL_ROOT_CORE.'/setup.php'); // Setup
include(GBL_ROOT_CORE.'/options.php'); // Options

include(GBL_ROOT_CORE.'/widgets.php'); // Widgets

/* Set Timezone */
if(function_exists('date_default_timezone_set')){	
	date_default_timezone_set((get_option('time_zone') !='' ? get_option('time_zone') : $config->timezone));
}


include(GBL_ROOT_CORE.'/users.php'); // User
include(GBL_ROOT_CORE.'/pages.php'); // Page
include(GBL_ROOT_CORE.'/components.php'); // Components

do_action('jcms_init'); // Initialization

$action = $_REQUEST['action'];

// Estimate
if($action == 'estimate'){	
	$cart = (object)$_REQUEST['cart'];
	$product = get_product_info($cart->product_id);
	$meta = (object)unserialize($product->meta);	
	$bulk = (object)unserialize($product->item_price);
	$bulk = (is_array($bulk->bulk) && count($bulk->bulk) > 0 ? $bulk->bulk : 'null');
	$quantity = $cart->quantity;
	$order_type = $cart->details['order_type'];
	
	if($bulk!='null' && $order_type!=''){
		$results = 'null';
		$price = array();
		$q = array();
		
		foreach($bulk as $qty => $details){
			/*if(isset($details['price'][$order_type]) && $details['price'][$order_type]!=''){
				$price[$order_type][$qty] = $details['price'][$order_type];
				$q[] = $qty;
			}*/
			if(!in_array($q,$qty))
				$q[] = $qty;
			
			if(isset($details['price']['regular']) && $details['price']['regular']!=''){
				$price['regular'][$qty] = $details['price']['regular'];				
			}else{
				$price['regular'][$qty] = 'quote';
			}

			if(isset($details['price']['custom']) && $details['price']['custom']!=''){
				$price['custom'][$qty] = $details['price']['custom'];				
			}else{
				$price['custom'][$qty] = 'quote';
			}
		}
		
		if(count($q) > 0 && $quantity >= $q[0]){			
			$selbulk = '';
			$found = false;
			for($i=0;$i<count($q);$i++){
				if(!$found){
					$selbulk = $q[$i];
					if(isset($q[$i + 1])){
						if($quantity >= $q[$i] && $quantity < $q[$i + 1]){
							$selbulk = $q[$i];
							$found = true;
						}
					}
				}
			}
			//print_r($cart);
			if($price[$order_type][$selbulk]!='quote'){
				$unit_price = $price[$order_type][$selbulk];
				$plfee = 0;
				if($cart->details['print_location']!=''){
					for($i=0;$i < count($meta->print_location) + 1;$i++){	
						$loc = $meta->print_location[$i];		
						if($loc['fee'] > 0 && str_replace('/','_',$loc['label']) == str_replace('/','_',rawurldecode($cart->details['print_location']))){							
							$plfee = $loc['fee'];
						}
					}
				}
				$raw_total = (($unit_price + $plfee) * $quantity);
				$total = trim(sprintf('%132lf',$raw_total));
				$total = number_format($total,2,'.',',');
				$results = array('status' => 1,'total' => $total,'price' => $unit_price,'raw_total' => $raw_total,'raw_price' => $unit_price);
			}else{
				$results = array('status' => 2);
			}	
		}else{
			$results = array('status' => 0);
		}
	}else{
		$results = array('status' => 2);
	}
	echo json_encode($results);	
}


// Option
if($action == 'option'){
	$meta = (object)unserialize(get_product_info($_REQUEST['product_id'],'meta')->meta);
	echo '<table cellpadding="0" cellspacing="0" style="width:100%;padding-top:20px;">';

	$colors = array();
	for($i=0;$i <= count($meta->color['option']);$i++){
		$color = (object)$meta->color['option'][$i];
		if($color->hex !='' && $color->label!=''){
			$colors[$color->type][] = array('hex' => $color->hex,'label' => $color->label);
		}
	}
	$colors = (object)$colors;
	if(count($colors->product) > 0){ // Product Color
		echo '<tr><td style="padding-bottom:10px;">';
		echo '	<label><span style="display:block;font-size:12px;font-weight:bold;">Product Color</span>';
		echo '		<select id="cart_product_color" class="field" name="cart[details][color][product]" style="width:100%;font-size:12px;padding:2px;">';
		echo '			<option value="">&rsaquo; Select Product Color</option>';
		for($i=0;$i <= count($colors->product);$i++){
			$color = $colors->product[$i];
			if($color['hex']!='' && $color['label']!=''){
				echo '			<option value="'.str_replace('#','',$color['hex']).'" style="background-color:'.$color['hex'].';">'.$color['label'].'</option>';
			}
		}
        echo '		</select>';
		echo '	</label>';
		echo '</td></tr>';
	}
	if(count($colors->imprint) > 0){ // Product Imprint Color
		echo '<tr><td style="padding-bottom:10px;">';
		echo '	<label><span style="display:block;font-size:12px;font-weight:bold;">Imprint Color</span>';
		echo '		<select id="cart_product_color" class="field" name="cart[details][color][imprint]" style="width:100%;font-size:12px;padding:2px;">';
		echo '			<option value="">&rsaquo; Select Imprint Color</option>';
		for($i=0;$i <= count($colors->imprint);$i++){
			$color = $colors->imprint[$i];
			if($color['hex']!='' && $color['label']!=''){
				echo '			<option value="'.str_replace('#','',$color['hex']).'" style="background-color:'.$color['hex'].';">'.$color['label'].'</option>';
			}
		}
        echo '		</select>';
		echo '	</label>';
		echo '</td></tr>';
	}
	if(count($meta->print_location) > 0){ // Print Location
		echo '<tr><td style="padding-bottom:10px;">';
		echo '	<label><span style="display:block;font-size:12px;font-weight:bold;">Print Location</span>';
		echo '		<select onchange="quantity_key();" id="cart_product_color" class="field" name="cart[details][print_location]" style="width:100%;font-size:12px;padding:2px;">';
		echo '			<option value="">&rsaquo; Select Print Location</option>';
		for($i=0;$i <= count($meta->print_location);$i++){
			$location = $meta->print_location[$i];
			if($location['label']!=''){
				echo '			<option value="'.rawurlencode($location['label']).'">'.$location['label'].($location['fee'] > 0 ? ' - $'.$location['fee']: '').'</option>';
			}
		}
        echo '		</select>';
		echo '	</label>';
		echo '</td></tr>';
	}
	echo '</table>';
}

do_action('jcms_close'); // Exiting
?>