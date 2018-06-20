<?php
if(!defined('IMPARENT')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access
$userinfo = $_SESSION['__BACKEND_USER']['info'];
$title = 'View Order';
?>
<?php if($_REQUEST['id'] > 0):?>
	<?php
	$the_order = get_order($_REQUEST['id']);	
	$cart_items = unserialize(stripslashes($the_order->order_meta));
    ?>
    <form id="order-viewer" method="post" action="<?php echo BACKEND_DIRECTORY;?>/components.php"> 
        <table class="viewer" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="sidebar" style="vertical-align:top;">            	
                	<?php 
					$canedit = true;
					if($the_order->order_status == 'shipped'){
						if(in_array($userinfo->user_group,array('ghosts','administrators'))){
							$canedit = true;
						}else{
							$canedit = false;
						}
					}
					?>
                    <?php if($canedit):?>
                        <div class="box">
                            <div class="title">Order</div>
                            <div class="content">                        
                                <label>Status:<br />
                                    <select name="order_status" style="width:99%;font-size:12px;">
                                        <option value="pending"<?php echo ($the_order->order_status == 'pending' ? ' selected="selected"' : '');?>>Pending</option>
                                        <option value="ongoing"<?php echo ($the_order->order_status == 'ongoing' ? ' selected="selected"' : '');?>>On-Going</option>
                                        <option value="onhold"<?php echo ($the_order->order_status == 'onhold' ? ' selected="selected"' : '');?>>On-Hold</option>
                                        <option value="shipped"<?php echo ($the_order->order_status == 'shipped' ? ' selected="selected"' : '');?>>Shipped</option>
                                    </select>
                                </label>
                                <?php
                                switch($the_order->order_status){
                                    case 'ongoing':
                                        $type = '&opt=on-going';
                                        break;
                                    case 'onhold':
                                        $type = '&opt=on-hold';
                                        break;
                                    case 'shipped':
                                        $type = '&opt=shipped';
                                        break;
                                }
                                ?>
                                <input type="button" value="Back" style="padding-left:15px;padding-right:15px;margin-top:10px;" onclick="window.location='<?php echo BACKEND_DIRECTORY.'/components.php?comp='.JSTORE_ID.'&mod=order'.$type;?>';" />&nbsp;
                                <input type="submit" value="Update" style="padding-left:15px;padding-right:15px;margin-top:10px;" />
                                <input type="hidden" name="comp" value="<?php echo JSTORE_ID;?>" />
                                <input type="hidden" name="id" value="<?php echo $the_order->ID;?>" />
                                <input type="hidden" name="mod" value="order" />
                                <input type="hidden" name="opt" value="view" />
                                <input type="hidden" name="action" value="save" />
                            </div>
                        </div>
					<?php endif;?>
                    <div class="box">
                        <div class="title">Customer info</div>
                        <div class="content"  id="customer_info">
                            <?php
                            $usr = get_user($the_order->user_id);
                            $usr_meta = (object) unserialize(stripslashes($usr->meta));
                            ?>
                            <h4>Customer info</h4>
                            <ul>
                            	<?php if($usr_meta->first_name!='' && $usr_meta->last_name!=''):?>
	                                <li>Name:&nbsp;<span><?php echo $usr_meta->first_name.' ' .$usr_meta->last_name;?></span></li>
								<?php endif;?>
                                <?php if($usr_meta->street1!='' && $usr_meta->city!='' &&
										 	$usr_meta->country!='' && $usr_meta->zip!=''):?>
                                    <li>Address:
                                        <?php
                                        $add = $usr_meta->street1;
                                        $add.=($usr_meta->street2 !='' ? '<br/>'.$usr_meta->street : '');
                                        $add.='<br/>'.$usr_meta->city;
                                        $add.=($usr_meta->state == 'non-US' ? '' : $usr_meta->state);
                                        $add.='<br/>'.$usr_meta->country.' '.$usr_meta->zip;
                                        ?>
                                        <div><?php echo $add;?></div>
                                    </li>
                                <?php endif;?>
								<?php if($usr_meta->primary_phone!=''):?>
	                                <li>Phone:&nbsp;<span><?php echo $usr_meta->primary_phone;?></span></li>
                                <?php endif;?>
                                <?php if($usr->email_address!=''):?>
	                                <li>Email:&nbsp;<span><?php echo $usr->email_address;?></span></li>
                                <?php endif;?>
                            </ul>
                        </div>
                    </div>
                </td>
                <td class="content" style="vertical-align:top;">
                	<?php load_documentations(); ?>
                    <h2><?php echo $title;?></h2>
                    <?php 
                    if($the_order->order_status == 'onhold'){
                        _d('This order is currently put on hold.','<div class="messagebox messagebox-error">','</div>');
                    }
                    if($the_order->order_status == 'shipped' && in_array($userinfo->user_group,array('ghosts','administrators'))){
                        _d('This order is already shipped.'.(!in_array($userinfo->user_group,array('ghosts','administrators')) ? ' Only administrator can update this page.' : ''),'<div class="messagebox">','</div>');
                    }
                    ?>
                    <?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>
                   <div style="padding-bottom:10px;">Unique TransID: <span style="font-weight:bold;color:#084482;">#<?php echo strtoupper($the_order->trans_id);?></span></div>
                   <table cellpadding="0" cellspacing="0" border="0" id="order-list">
                   		<?php $total = 0;?>
                        <?php foreach($cart_items as $key => $value):?>
                            <?php
                            $thumbnail = get_product_info($value['product_id'],array('thumbnail'))->thumbnail;				
							$stotal = str_replace(',','',$value['sub_total']);
							$total = intval($total + $stotal);
                            ?>
                            <tr>
                                <td class="col1">
                                    <a href="<?php item_url($value['item_id']);?>" style="font-weight:bold;color:#084482;" target="_blank">
                                        <?php if($thumbnail!=''):?>
                                            <img src="<?php echo $thumbnail;?>" style="width:100px;height:100px;" />
                                        <?php else:?>
                                            <div style="width:100px;height:100px;font-weight:bold;background-color:#F0F0F0;border:1px solid #E5E5E5;"></div>
                                        <?php endif;?>
                                    </a>                   	
                                </td>
                                <td class="col2" style="padding-left:20px;">
                                    <div class="name"><a href="<?php item_url($value['product_id']);?>" style="font-weight:bold;color:#084482;" target="_blank"><?php echo $value['name'];?></a></div>
                                    <div class="order-info">Price: <span>$<?php echo number_format(trim(sprintf('%132lf',$value['unit_price'])),2,'.',',');?></span></div>
                                    <div class="order-info">Quantity: <span><?php echo $value['quantity'];?></span></div>
                                    <?php if($value['details']['color']['product']!=''):?>
                                    <div class="order-info">Product Color: <span><span style="margin-left:5px;background-color:<?php echo $value['details']['color']['product'];?>;padding:0 10px;"></span></span></div>
                                    <?php endif;?>
                                    <?php if($value['details']['color']['imprint']!=''):?>
                                    <div class="order-info">Imprint Color: <span><span style="margin-left:5px;background-color:<?php echo $value['details']['color']['imprint'];?>;padding:0 10px;"></span></span></div>
                                    <?php endif;?>
                                    <?php if($value['details']['print_location']!=''):?>
                                    <div class="order-info">Print Location: <span><?php echo rawurldecode($value['details']['print_location']);?></span></div>
                                    <?php endif;?>
									<?php /*foreach($details as $dkey => $dvalue):?>
                                        <div class="order-info">
                                            <?php echo $dvalue['text'];?>:&nbsp;
                                            <span><?php echo $dvalue['value'];?></span>
                                        </div>
                                    <?php endforeach; */?>                                    
                                </td>
                                <td class="col3">$<?php echo $value['unit_price'];?> x <?php echo $value['quantity'];?> = <span id="amount_<?php echo $key?>">$<?php echo $value['sub_total'];?> USD</span></td>
                            </tr>
                        <?php endforeach;?>
                        <?php
						$total = trim(sprintf('%132lf',$total));
						$total = number_format($total,2,'.',',');
                        ?>
                        <tr>
                            <td class="total col1" colspan="2" style="text-align:right;padding-right:100px;padding-top:10px;font-weight:bold;font-size:18px;">Total</td>
                            <td class="total col2" style="color:#FF7E00;font-size:25px;padding-top:10px;">$<span id="total_amount"><?php echo $total;?></span> USD</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
	</form>
<?php else:?>
<div class="message-box">Invalid access.</div>
<?php endif;?>