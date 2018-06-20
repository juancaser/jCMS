<?php
if(!defined('IMPARENT')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access

if($_REQUEST['opt'] == 'tax'){
	$title = 'Tax';
}else{
	$title = 'Shipping';	
}
?>
<div id="tax-shipping">
	<ul class="tab">
    	<li<?php echo ($_REQUEST['opt'] == '' ? ' class="active"' : '');?>><a href="<?php echo COMPONENTS_URL;?>&mod=shipping-tax">Shipping</a></li>
    	<li<?php echo ($_REQUEST['opt'] == 'tax' ? ' class="active"' : '');?>><a href="<?php echo COMPONENTS_URL;?>&mod=shipping-tax&opt=tax">Tax</a></li>
    </ul>
    <div class="clear"></div>
    <div class="tab-content">
		<?php if($_REQUEST['opt'] == 'tax'):?>
            
        <?php else:?>
        <div class="shipping ts-content">
            <div class="ts-header">
            	<input type="button" class="button1" value="Add Shipping Settings" />
            </div>
        </div>
        <?php endif;?>
    </div>
</div>
