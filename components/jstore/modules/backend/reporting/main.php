<?php
if(!defined('IMPARENT')){exit();} // No direct access
global $config;
$title = $config->site_name.' Store';
?>
<div id="store-dashboard">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
    	<tr>
        	<td width="30%">
            	<div class="sidebar">
                    <div class="box">
                        <div class="title">Product Status</div>
                        <div class="content">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td class="col1">Products Available</td>
                                    <td class="col2"><span id="stat_product_count"><?php echo product_info_count();?> / <?php echo product_info_count('inactive');?></span></span></td>
                                </tr>
                                <tr>
                                    <td class="col1">Regular</td>
                                    <td class="col2"><span id="products_active_sales">0</span> / <span id="products_expired_sales">0</span></td>
                                </tr>
                                <tr>
                                    <td class="col1">Featured Products</td>
                                    <td class="col2"><span id="products_active_featured">0</span> / <span id="products_expired_featured">0</span></td>
                                </tr>
                                <tr>
                                    <td class="col1">Specials</td>
                                    <td class="col2"><span id="products_active_specials">0</span> / <span id="products_expired_speacials">0</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="box">
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
                    </div>
                    
                    <div class="box">
                        <div class="title">Customer Status</div>
                        <div class="content">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td class="col1">Registered Customers</td>
                                    <td class="col2"><span id="stat_customer_count">0</span></span></td>
                                </tr>
                                <tr>
                                    <td class="col1">Latest Registered</td>
                                    <td class="col2"><span id="latest_registered_customer">0</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
				</div>                
                
            </td>
        	<td width="70%" style="padding-left:20px;">
                <table  id="store-quicklinks" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td class="col1">
                            <a href="<?php echo BACKEND_DIRECTORY;?>/components.php?comp=<?php echo JSTORE_ID;?>&mod=item&opt=edit">
                                <h4>New Product Catalog</h4>
                                <p>Add up your sales, add more product catalog to your store.</p>
                            </a>
                        </td>
                        <td class="col2">
                            <a href="<?php echo BACKEND_DIRECTORY;?>/components.php?comp=<?php echo JSTORE_ID;?>&mod=categories&opt=edit">
                                <h4>New Product Category</h4>
                                <p>Organize your product catalog by category for easy access and searching.</p>
                            </a>
                        </td>
                    </tr>
                </table>
                <div class="box">
                    <div class="title">Shopping Cart</div>
                    <div class="content" id="order">
                    	<ul>
                        </ul>
                        <p>Shopping Cart Empty</p>
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td class="col1">Pending</td>
                                <td class="col2"><span id="order_pending">0</span></td>
                                <td class="col3">Procesing</td>
                                <td class="col4"><span id="order_processing">0</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>
