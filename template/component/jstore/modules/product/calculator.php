<?php if(!defined('IMPARENT')){exit();} // No direct access ?>
<?php
$cartid = strtoupper(md5('jstore@cart:'.$product->ID.'@'.stripslashes($product->item_name)));
$sub_total = '0.00';
$unit_price = '0.00';

if(array_key_exists('custom',$item_price->bulk_option) && $item_price->bulk_option['custom'] == 'yes'){
	$order_type = 'custom';
}else{
	$order_type = 'regular';
}


$product_color = '';
$imprint_color = '';
$print_location = '';

$incart = false;

if(array_key_exists($cartid,$_SESSION['_CART'])){
	$incart = true;		
	$cart = $_SESSION['_CART'][$cartid];
	$sub_total = $cart['sub_total'];
	$unit_price = $cart['unit_price'];
	$order_type = $cart['details']['order_type'];
	$product_color = $cart['details']['color']['product'];
	$imprint_color = $cart['details']['color']['imprint'];
	$print_location = $cart['details']['print_location'];
}	
?>
<?php if($incart):?><div class="msgbox">This item is already in your cart. Click <a href="<?php store_info('cart');?>">here</a> to view.</div><?php endif; ?>	
<div id="cart_calculator">
	<div class="jbox" title="<?php echo stripslashes($product->item_name);?>">
		<?php if(!is_user_logged()):?>
			<p class="login"><strong>Where's the price?</strong> Click <a href="<?php echo store_info('login');?>?redirect=<?php echo rawurlencode(item_url($product->ID,false));?>" rel="nofollow">here</a> to see. <a href="<?php store_info('register');?>">Not yet a member?</a></p>
		<?php else:?>            	
			<?php if($has_bulk_price):?>
			<div class="option">
				<p>The more you buy, the lower the price will go.</p>
				<ul id="radio_order_type">
					<?php if($item_price->bulk_option['regular'] == 'yes'):?>
					<li><a alt="regular" class="<?php echo ($cart['details']['order_type'] == 'regular' ? 'cart_radio_selected ' : ($cart['details']['order_type'] != '' ? '' : 'cart_radio_selected '));?>cart_radio" href="javascript:void(0);">Order them Blank</a></li>
					<?php endif;?>
					<?php if($item_price->bulk_option['custom'] == 'yes'):?>
					<li><a alt="custom" class="<?php echo ($cart['details']['order_type'] == 'custom' ? 'cart_radio_selected ' : (($cart['details']['order_type'] == '' && $item_price->bulk_option['regular']!='yes') ? 'cart_radio_selected ' : ''));?>cart_radio" href="javascript:void(0);">Order with Logo/Design</a></li>
					<?php endif;?>
				</ul>
			</div>
			<div class="form">
				<div class="arrow"></div>
				<div class="dialog">                    	
					<form class="calculator" id="calculator_form" action="<?php store_info('cart');?>" method="post">
						<input type="hidden" name="cart[cart_id]" class="field" value="<?php echo $cartid;?>" />
						<input type="hidden" name="cart[product_id]" class="field" id="product_id" value="<?php echo $product->ID;?>" />
						<input type="hidden" name="cart[name]" class="field" value="<?php echo stripslashes($product->item_name);?>" />
						<input type="hidden" name="cart[details][order_type]" class="field" id="order_type" value="<?php echo $order_type;?>" alt="regular" />
						<input type="hidden" name="cart[details][price][total]" class="field" id="total" value="<?php echo $sub_total;?>" />                        
						<input type="hidden" name="cart[details][price][unitprice]" class="field" id="unitprice" value="<?php echo $unit_price;?>" />
						<input type="hidden" name="action" id="cart_action" value="<?php echo ($incart ? 'update' : 'add');?>" />
						<input type="hidden" name="continue" value="<?php item_url($product->ID);?>" />
						<table cellpadding="0" cellspacing="0" style="width:100%;">
							<tr class="quantity">
								<td style="width:50%;vertical-align:middle;"><label for="quantity">Enter Quantity</label></td>
								<td style="width:50%;text-align:right;vertical-align:middle;"><input type="text" id="quantity" name="cart[quantity]" class="field" autocomplete="off" value="<?php echo $cart['quantity'];?>" /></td>
							</tr>
						</table>
						<div id="cart_fields">
							<div id="cart_option" class="<?php echo ($order_type == 'regular' ? 'hide' : '');?>">
								<table cellpadding="0" cellspacing="0" style="width:100%;padding-top:20px;">                                
								<?php
								$colors = array();
								for($i=0;$i <= count($meta->color['option']);$i++){
									$color = (object)$meta->color['option'][$i];
									if($color->hex !='' && $color->label!=''){
										$colors[$color->type][] = array('hex' => $color->hex,'label' => $color->label);
									}
								}
								$colors = (object)$colors;
								?>
								<?php if(count($colors->product) > 0): // Product Color ?>
									<tr>
										<td style="padding-bottom:10px;">
											<label>
												<span style="display:block;font-size:12px;font-weight:bold;">Product Color</span>
												<select id="cart_product_color" class="field" name="cart[details][color][product]" style="width:100%;font-size:12px;padding:2px;">';
													<option value="">&rsaquo; Select Product Color</option>';
													<?php for($i=0;$i <= count($colors->product);$i++): $color = $colors->product[$i]; ?>
														<?php if($color['hex']!='' && $color['label']!=''):?>
															<option value="<?php echo $color['hex'];?>" style="background-color:<?php echo $color['hex'];?>;"<?php echo ($product_color == $color['hex']  ? ' selected="selected"' : '');?>><?php echo $color['label'];?></option>
														<?php endif;?>
													<?php endfor;?>
												</select>
											</label>
										</td>
									</tr>
								<?php endif;?>
								<?php if(count($colors->imprint) > 0): // Product Imprint Color ?>
									<tr>
										<td style="padding-bottom:10px;">
											<label><span style="display:block;font-size:12px;font-weight:bold;">Imprint Color</span>
											<select id="cart_product_color" class="field" name="cart[details][color][imprint]" style="width:100%;font-size:12px;padding:2px;">
												<option value="">&rsaquo; Select Imprint Color</option>
												<?php for($i=0;$i <= count($colors->imprint);$i++): $color = $colors->imprint[$i];?>    
													<?php if($color['hex']!='' && $color['label']!=''):?>
														<option value="<?php echo $color['hex'];?>" style="background-color:<?php echo $color['hex'];?>;"<?php echo ($imprint_color == $color['hex']  ? ' selected="selected"' : '');?>><?php echo $color['label'];?></option>
													<?php endif;?>
												<?php endfor;?>
												</select>
											</label>
										</td>
									</tr>
								<?php endif;?>
								<?php if(count($meta->print_location) > 0):// Print Location ;?>
									<tr>
										<td style="padding-bottom:10px;">
											<label><span style="display:block;font-size:12px;font-weight:bold;">Print Location</span>
												<select onchange="quantity_key();" id="cart_product_color" class="field" name="cart[details][print_location]" style="width:100%;font-size:12px;padding:2px;">
													<option value="">&rsaquo; Select Print Location</option>
													<?php for($i=0;$i <= count($meta->print_location);$i++): $location = $meta->print_location[$i];?>                                                            
														<?php if($location['label']!=''):?>
															<option value="<?php echo rawurlencode($location['label']);?>"<?php echo ($print_location == rawurlencode($location['label'])  ? ' selected="selected"' : '');?>><?php echo $location['label'].($location['fee'] > 0 ? ' - $'.$location['fee']: '')?></option>
														<?php endif;?>
													<?php endfor;?>
												</select>
											</label>
										</td>
									</tr>
								<?php endif;?>
								</table>
							</div>
							<table cellpadding="0" cellspacing="0" style="width:100%;">
								<tr class="result">
									<td style="width:20%;">Total:</td>
									<td style="width:80%;color:#E47F12;">$<span id="cart_subtotal"><?php echo $sub_total;?></span></td>
								</tr>
								<tr class="unitprice">
									<td colspan="2" style="width:100%;text-align:right;font-size:12px;">Unit Price: <strong>$<span id="cart_unitprice"><?php echo $unit_price;?></span></strong></td>
								</tr>
							</table>
							<div class="cart_button">
								<input type="button" id="button_calculate" class="button" value="Calculate" />
								<input type="submit" id="button_add" class="button hide" value="<?php echo ($incart ? 'Update' : 'Add');?> to Cart" />
							</div>
						</div>
					</form>						
					<div id="cart_quote" class="hide">
						<?php if($meta->quote_message!=''):?>
							<?php echo $meta->quote_message;?>
						<?php else:?>
							<span style="font-size:12px;">CALL US FOR A QUOTE</span><br/>
							<span style="font-size:15px;font-weight:bold;">1 800 877 2824</span>
						<?php endif;?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<?php else:?>
			<p>Call us for a Quote</p>
			<?php endif;?>
		<?php endif;?>
	</div>
</div>