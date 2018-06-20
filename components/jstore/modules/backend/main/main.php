<?php
if(!defined('IMPARENT')){exit();} // No direct access
global $config;
$title = $config->site_name.' Store';
?>
<div id="store-dashboard">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
    	<tr>
        	<td width="30%">
            	<div class="sidebar" style="padding-top:20px;">
                    <div class="box">
                        <div class="title">Product Status</div>
                        <div class="content">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td class="col1">Products Available</td>
                                    <td class="col2"><span id="stat_product_count"><span style="color:#3A8000;" title="Active"><?php echo get_product_info_count();?></span> / <span style="color:#FF0000;" title="In-Active"><?php echo get_product_info_count('all',false);?></span></span></td>
                                </tr>
                                <tr>
                                    <td class="col1">Featured Products</td>
                                    <td class="col2"><span id="featured_products"><span style="color:#3A8000;" title="Active"><?php echo get_product_info_count('featured');?></span> / <span style="color:#FF0000;" title="In-Active"><?php echo get_product_info_count('featured',false);?></span></span></td>
                                </tr>
                                <tr>
                                    <td class="col1">Specials</td>
                                    <td class="col2"><span id="special_products"><span style="color:#3A8000;" title="Active"><?php echo get_product_info_count('special');?></span> / <span style="color:#FF0000;" title="In-Active"><?php echo get_product_info_count('special',false);?></span></span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!--div class="box">
                        <div class="title">Shipping</div>
                        <div class="content">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td class="col1">Pending</td>
                                    <td class="col2"><span id="pendinbg_for_shipping">0</span></span></td>
                                </tr>
                                <tr>
                                    <td class="col1">Ongoing</td>
                                    <td class="col2"><span id="pendinbg_for_shipping">0</span></span></td>
                                </tr>
                                <tr>
                                    <td class="col1">Done</td>
                                    <td class="col2"><span id="pendinbg_for_shipping">0</span></span></td>
                                </tr>
                            </table>
                        </div>
                    </div-->
                    
                    <div class="box">
                        <div class="title">Customer Status</div>
                        <div class="content">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td class="col1">Registered Customers</td>
                                    <td class="col2"><span id="stat_customer_count"><span style="color:#3A8000;" title="Active"><?php echo get_user_count('user');?></span> / <span style="color:#FF0000;" title="In-Active"><?php echo get_user_count('user',false,false);?></span></span></td>
                                </tr>
								<?php $new_registered = latest_registered_users(10); ?>
                                <?php if(count($new_registered) > 0):?>
                                <tr>
                                    <td colspan="2">Last Registered</td>
                                </tr>
                                <tr>
                                    <td colspan="2"><?php
									$lst = '';
									for($i=0;$i > count($new_registered);$i++){
										$usr = $new_registered[$i];
										$lst = ($lst!=''?',':'').$user->display_name;
									}
									echo $lst;
                                    ?></td>
                                </tr>
                                <?php endif;?>
                            </table>

                        </div>
                    </div>
				</div>                
                
            </td>
        	<td width="70%" style="padding-left:20px;padding-top:0;">
				<?php load_documentations(); ?>
                <table  id="store-quicklinks" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td class="col1">
                            <a href="<?php echo BACKEND_DIRECTORY;?>/components.php?comp=<?php echo JSTORE_ID;?>&mod=item&opt=category-edit">
                                <h4>New Product Category</h4>
                                <p>Organize your product catalog by category for easy access and searching.</p>
                            </a>
                        </td>
                        <td class="col2">
                            <a href="<?php echo BACKEND_DIRECTORY;?>/components.php?comp=<?php echo JSTORE_ID;?>&mod=item&opt=edit">
                                <h4>New Product Item</h4>
                                <p>Add up your sales, add more product to your store.</p>
                            </a>
                        </td>
                    </tr>
                </table>
                <div class="box">
                    <div class="title">Order</div>
                    <div class="content" id="order">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td class="col1">New</td>
                                <td class="col2"><span id="order_pending" style="color:red;"><?php echo get_order_count();?></span></td>
                                <td class="col3">Ongoing</td>
                                <td class="col4"><span id="order_ongoin"><?php echo get_order_count('ongoing');?></span></td>
                            </tr>
                            <tr>
                                <td class="col1">On-Hold</td>
                                <td class="col2"><span id="order_onhold"><?php echo get_order_count('onhold');?></span></td>
                                <td class="col3">Shipped</td>
                                <td class="col4"><span id="order_shipped"><?php echo get_order_count('shipped');?></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>
