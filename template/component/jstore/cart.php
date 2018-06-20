<?php if(!defined('IMPARENT')){exit();} // No direct access ?>
<?php
$userinfo = get_user($_SESSION['__FRONTEND_USER']['info']->ID);
$user_meta = (object)unserialize($userinfo->meta);
?>
<?php get_header(); ?>
<?php breadcrumb('Home','<span class="sep"></span>');?>
<div id="page" class="inner-wrapper" style="padding:0 0 20px 20px;">
	<?php if($_REQUEST['action'] == 'thankyou'):?>
    	<h3 style="font-size:20px;margin-bottom:10px;font-weight:normal;">Thank you, we have submitted your order request.</h3>
        <p>For more question please contact our live support for assistance.</p>
        <div style="padding-top:10px;">
        	<input type="button" value="&larr; Back to Homepage" onclick="window.location='<?php get_siteinfo('url');?>';" />
        </div>
	<?php elseif($_REQUEST['action'] == 'error'):?>
    	<h3 style="font-size:20px;margin-bottom:10px;font-weight:normal;">We have encountered an error while submitting your order request.</h3>
        <p>If you encountered this more than once please contact our live support for assistance.</p>
        <div style="padding-top:10px;">
        	<input type="button" value="&larr; Back to Homepage" onclick="window.location='<?php get_siteinfo('url');?>';" />
            <input type="button" id="submit_order"  value="Go to my Shopping Cart" style="padding:2px 10px;text-transform:uppercase;" onclick="window.location='<?php store_info('cart');?>';" />&nbsp;
        </div>
	<?php else:?>
    	<script type="text/javascript">
			function empty_cart(){
				if(confirm('Are you sure you want to empty your cart?') == true){
					window.location='<?php store_info('cart');?>?action=clear';
				}			
			}
        </script>
        <style type="text/css">
		/*.cartoption{
			background-color:#EDEDED;
		}
			.cartoption .jbox-ui-header{
				background:url('<?php get_siteinfo('template_directory');?>/images/navbg.gif') repeat-x top left;
			}
			.cartoption .jbox-ui-header .jbox-ui-title{
				font-size:13px;
			}
			.cartoption .jbox-ui-tooltip {
				border-color:#131313 transparent -moz-use-text-color;
			}
			.cartoption .jbox-ui-header .jbox-ui-toggle a {
				border-color:#4e4e4e transparent -moz-use-text-color;
			}		
		*/
        </style>
        <?php
		$cart_items = $_SESSION['_CART'];
		?>
        <div id="cart">
        	<div class="your-cart">
			<?php if(count($cart_items) > 0):?>
                <h3 class="filled">Your shopping cart</h3>
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <th class="col1">&nbsp;</th>
                        <th class="col2">Product</th>
                        <th class="col3">Unit Price</th>
                        
                        <th class="col4">Quantity</th>
                        <th class="col5">Subtotal</th>
                    </tr>
                    <?php foreach($cart_items as $cart_id => $cart_info):?>                    
                    <?php
					$this_product = get_product_info($cart_info['product_id']);
					$total = $total + ($cart_info['quantity'] * $cart_info['unit_price']);
					$details = $cart_info['details'];
                    $meta = (object)unserialize($this_product->meta);
					?>
                    <tr>
                    	<td colspan="5" style="padding:0;">
                        	<form id="<?php echo $cart_id;?>" action="<?php store_info('cart');?>" method="post">
                                <input type="hidden" name="action" value="update" />
                                <input class="field" type="hidden" name="cart[cart_id]" value="<?php echo $cart_id;?>" />
                                <input class="field" type="hidden" name="cart[product_id]" value="<?php echo $this_product->ID;?>" />
                                <input class="field cart_subtotal" type="hidden" name="cart[details][price][total]" value="<?php echo $cart_info['sub_total'];?>" />
                                <input class="field cart_unitprice" type="hidden" name="cart[details][price][unitprice]" value="<?php echo $cart_info['unit_price'];?>" />
                                <input class="field" type="hidden" name="cart[details][order_type]" value="<?php echo $details['order_type'];?>" />
                                <input class="field" type="hidden" name="cart[name]" value="<?php echo stripslashes($this_product->item_name);?>" />
                                <table cellpadding="0" cellspacing="0" border="0" style="margin:0;">
                                    <tr>
                                        <td class="col1">                            
                                            <img src="<?php timthumb($meta->media['main'],150,150); ?>" style="display:block;" />
                                        </td>
                                        <td class="col2">
                                            <h4 style="font-size:15px;font-weight:bold;"><?php echo $cart_info['name'];?></h4>
                                            <?php if($details['order_type'] == 'custom'):?>
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
                                                <?php if(count($colors->product) > 0 || count($colors->imprint) > 0 || count($meta->print_location) > 0):?>
                                                    <div class="cartoption jbox" style="width:100%;margin-top:5px;margin-bottom:7px;" title="Custom Order Option">
                                                        <table class="details" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                            <?php if(count($colors->product) > 0):?>
                                                            <tr>
                                                                <td style="width:40%;padding:0 0 3px 0;vertical-align:middle;"><label for="">Item Color</label></td>
                                                                <td style="width:60%;padding:0 0 3px 0;vertical-align:middle;">
                                                                    <select class="field" name="cart[details][color][product]" style="width:100%;font-size:13px;padding:2px;">';
                                                                        <option value="">&rsaquo; Select Product Color</option>';
                                                                        <?php for($i=0;$i <= count($colors->product);$i++): $color = $colors->product[$i]; ?>
                                                                            <?php if($color['hex']!='' && $color['label']!=''):?>
                                                                                <option value="<?php echo $color['hex'];?>" style="background-color:<?php echo $color['hex'];?>;"<?php echo ($color['hex'] == $details['color']['product'] ? ' selected="selected"' : '');?>><?php echo $color['label'];?></option>
                                                                            <?php endif;?>
                                                                        <?php endfor;?>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <?php endif;?>
                                                            <?php if(count($colors->imprint) > 0):?>
                                                            <tr>
                                                                <td style="width:40%;padding:0 0 3px 0;vertical-align:middle;"><label for="">Imprint Print Color</label></td>
                                                                <td style="width:60%;padding:0 0 3px 0;vertical-align:middle;">
                                                                    <select class="field" name="cart[details][color][imprint]" style="width:100%;font-size:13px;padding:2px;">';
                                                                        <option value="">&rsaquo; Select Product Color</option>';
                                                                        <?php for($i=0;$i <= count($colors->imprint);$i++): $color = $colors->imprint[$i]; ?>
                                                                            <?php if($color['hex']!='' && $color['label']!=''):?>
                                                                                <option value="<?php echo $color['hex'];?>" style="background-color:<?php echo $color['hex'];?>;"<?php echo ($color['hex'] == $details['color']['imprint'] ? ' selected="selected"' : '');?>><?php echo $color['label'];?></option>
                                                                            <?php endif;?>
                                                                        <?php endfor;?>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <?php endif;?>
                                                            <?php if(count($meta->print_location) > 0):?>
                                                            <tr>
                                                                <td style="width:40%;padding:0 0 3px 0;vertical-align:middle;"><label for="">Print Location</label></td>
                                                                <td style="width:60%;padding:0 0 3px 0;vertical-align:middle;">
                                                                    <select class="field" name="cart[details][print_location]" style="width:100%;font-size:13px;padding:2px;">
                                                                        <option value="">&rsaquo; Select Print Location</option>
                                                                        <?php for($i=0;$i <= count($meta->print_location);$i++): $location = $meta->print_location[$i];?>                                                            
                                                                            <?php if($location['label']!=''):?>
                                                                                <option value="<?php echo rawurlencode($location['label']);?>"<?php echo (rawurlencode($location['label']) == $details['print_location'] ? ' selected="selected"': '');?>><?php echo $location['label'].($location['fee'] > 0 ? ' - $'.$location['fee']: '')?></option>
                                                                            <?php endif;?>
                                                                        <?php endfor;?>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <?php endif;?>
                                                        </table>
                                                    </div>
                                                <?php endif;?>
                                            <?php endif;?>	
                                            <div style="padding:5px 0;">
                                                <input class="btnremove" type="button" value="UPDATE" onclick="update_cart('<?php echo $cart_id;?>');" />
                                                <input class="btnremove" type="button" value="REMOVE" onclick="window.location='<?php store_info('cart');?>?action=clear&id=<?php echo $cart_id;?>';" />
                                            </div>
                                        </td>
                                        <td class="col3">$<?php echo $cart_info['unit_price'];?></td>
                                        <td class="col4">
                                            <input style="width:50px;text-align:center;" class="cart_quantity field" type="text" name="cart[quantity]" value="<?php echo $cart_info['quantity'];?>" />
                                        </td>
                                        <td class="col5">$<?php echo $cart_info['sub_total'];?></td>
                                    </tr>
                                </table>                                
							</form>
                        </td>
					</tr>
                    <?php endforeach;?>
                    <tr>
                    	<td class="total" colspan="2">
                        	<?php if($_REQUEST['continue']!=''):?>
                        	<input type="button" value="Continue Shopping" class="left cart_button" onclick="window.location='<?php echo $_REQUEST['continue'];?>';" />
                            <?php endif;?>
                            <input type="button" value="Empty Cart" class="left cart_button" onclick="window.location='<?php store_info('cart');?>?action=clear';" style="margin-left:5px;" />
                            <input type="button" value="Proceed to Checkout" class="right cart_button cart_button_default" onclick="window.location='<?php store_info('cart');?>?action=checkout';" />&nbsp;                            
                            <div class="clear"></div>
                        </td>
                        <td colspan="3" class="total">Total &rsaquo; <span style="color:#FF7E00;">$<?php echo number_format(trim(sprintf('%132lf',$total)),2,'.',',');?></span></td>
                    </tr>
                </table>
                <!--div id="shipping-details" style="width:470px;">
                    <div class="jbox" style="width:470px;" title="Shipping Information">
                    	<div style="padding-bottom:15px;">
                            <p style="font-size:15px;line-height:1.2em;">Please update your shipping information below. All fields required.</p>
                            <p><label><span>Name:</span>
                            <input class="field" type="text" name="shipping[name]" style="width:300px;" value="<?php echo $user_meta->first_name.' '.$user_meta->last_name;?>" /></label></p>
                            <p><label><span>Address: <span>(Street, City, State, Country Zipcode)</span></span>
                            <textarea name="shipping[address]" class="field" style="width:430px;height:80px;">
                            <?php
                            if($user_meta->street1!='' && $user_meta->street2!='' && $user_meta->city!='' && $user_meta->state && $user_meta->country!='' && 
                                $user_meta->zip!=''){
                                $address = '';
                                $address.= $user_meta->street1;
                                $address.= ($address!=''?'\r\n' : '').$user_meta->street2;
                                $address.= ($address!=''?'\r\n' : '').$user_meta->city;
                                $address.= ($address!=''?' ' : '').$user_meta->state;
                                $address.= ($address!=''?'\r\n' : '').$user_meta->country;
                                $address.= ($address!=''?' ' : '').$user_meta->zip;
                                echo $addres;
                            }
                            ?>
                            </textarea></p>
                            <p><label><span>Email:</span>
                            <input name="shipping[email]" class="field" type="text" style="width:200px;" value="<?php echo $userinfo->email_address;?>" /></label></p>
                            <p><label><span>Phone:</span>
                            <input name="shipping[phone]" class="field" type="text" style="width:200px;" value="<?php echo $user_meta->primary_phone.($user_meta->phone_extension_no!='' ? ' Ext. '.phone_extension_no:'');?>" /></label></p>
                            <div style="font-style:italic;color:#878787;font-size:11px;">Just in-case we need to contact you regarding your order.</div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div-->
			<?php else:?>
                <h3 class="empty">You have no item in your shopping cart.</h3>
			<?php endif;?>
            </div>
        </div>   
	<?php endif;?>
</div>
<div id="quicklinks-2" class="inner-wrapper">
	<?php do_action('product_footer_ad_1');?>
    <div class="left">
        <a href="<?php get_siteinfo('url');?>"><img src="<?php get_siteinfo('template_directory');?>/images/logo.png" /></a>
    </div>
    <div class="right">
        <a href="<?php the_permalink('free');?>"><img src="<?php get_siteinfo('template_directory');?>/images/free-button.png" /></a>
        <a href="<?php the_permalink('stockdesigns');?>#vectorize"><img src="<?php get_siteinfo('template_directory');?>/images/vectorize-art.png" /></a>
        <a href="<?php the_permalink('stockdesigns');?>#stock-arts"><img src="<?php get_siteinfo('template_directory');?>/images/our-stock-art.png" /></a>
        <a href="<?php the_permalink('stockdesigns');?>#personalize"><img src="<?php get_siteinfo('template_directory');?>/images/your-design.png" /></a>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<?php get_footer(); ?>