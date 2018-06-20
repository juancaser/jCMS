<?php
$price_category = array('regular' => 'Blank/Regular','custom' => 'Custom/Printed');
?>
<table cellpadding="0" cellspacing="0" border="0" style="width:100%;margin-top:2px;">
    <tr>
    	<td style="width:20%;padding-bottom:10px;"><label>Regular</label></td>
        <td style="width:80%;padding-bottom:10px;"><input type="text" id="item_price_ea" name="item_price[ea]" style="width:80px;margin:0;" value="<?php echo $p->ea;?>"  />
	        <span class="info">Item's regular price, if no bulk or special pricing set this will be used in cart calculation.</span>
        </td>
    </tr>
    <tr>
    	<td style="vertical-align:top;padding-bottom:10px;"><label>Sample</label></td>
        <td style="vertical-align:top;padding-bottom:10px;">
            <table cellpadding="0" cellspacing="0" border="0" style="width:100%;margin-top:2px;">
                <tr>
                    <td style="width:30%;padding-bottom:10px;"><label for="special_max_order">Max Order</label></td>
                    <td style="width:70%;padding-bottom:10px;"><input type="text" id="specia_max_order" name="item_price[special][max_order]" style="width:80px;margin:0;" value="0"  />
	                    <span class="info">Maximum order of sample item. '0' is Unlimited</span>
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom:10px;"><label for="special_price">Price per Item</label></td>
                    <td style="padding-bottom:10px;"><input type="text" name="item_price[special][price]" style="width:80px;margin:0;" value=""  />
	                    <span class="info">Price per sample item.</span>
                    </td>
                </tr>
			</table>
        </td>
    </tr>
    <tr>
    	<td style="padding-bottom:10px;"><label>Bulk</label></td>
        <td style="padding-bottom:10px;">
            <span class="info" style="margin-bottom:5px;">Click on the checkbox to remove selection.</span>
            <table id="price_option" class="items" cellpadding="0" cellspacing="0" border="0" style="width:100%;margin-top:2px;">
                <tr>
                    <th style="width:2%;"></th>
                    <th style="width:25%;">Min Order Qty</th>
                    <th style="width:25%;">Price</th>
                    <th style="width:38%;">Category</th>
                </tr>
                <tr>
				<?php foreach($price_category as $key => $value): ?>
                	<tr id="<?php echo $key;?>"><td><?php echo $value;?></td></tr>
                <?php endforeach; ?>
                    <td></td>
                    <td><input type="text" style="width:99%" name="item_price[bulk][1][quantity]" class="quantity field" value="<?php echo $bulk[1]['quantity'];?>" /></td>
                    <td><input type="text" style="width:97%" name="item_price[bulk][1][price]" class="price field" value="<?php echo $bulk[1]['price'];?>" /></td>
                    <td>
                    	<select style="width:97%" name="item_price[bulk][1][category]" class="category field">
						<?php foreach($price_category as $key => $value): ?>
                        	<option value="<?php echo $key;?>"<?php echo ($bulk[1]['category'] == $key ? ' selected="selected"' : '');?>><?php echo $value;?></option>
                        <?php endforeach; ?>
                        </select>
					</td>
                </tr>
                <?php for($i=1;$i <= count($bulk);$i++):?>
                    <tr id="prc<?php echo $i;?>">
                        <td><input type="checkbox" class="chk" value="<?php echo $i;?>" /></td>
                        <td><input type="text" style="width:99%" name="item_price[bulk][<?php echo $i;?>][quantity]" class="quantity field" value="<?php echo $bulk[$i]['quantity'];?>" /></td>
                        <td><input type="text" style="width:97%" name="item_price[bulk][<?php echo $i;?>][price]" class="price field" value="<?php echo $bulk[$i]['price'];?>" /></td>
                        <td>
                            <select style="width:97%" name="item_price[bulk][<?php echo $i;?>][category]" class="category field">
                            <?php foreach($price_category as $key => $value): ?>
                                <option value="<?php echo $key;?>"<?php echo ($bulk[$i]['category'] == $key ? ' selected="selected"' : '');?>><?php echo $value;?></option>
                            <?php endforeach; ?>
                            </select>
                        </td>                                    
                    </tr>
                <?php endfor;?>
            </table>
            <div style="padding-bottom:10px;">
                <input type="button" id="add_price_option" value="ADD PRICE" style="padding:2px 10px;font-size:10px;" />&nbsp;
                <input type="button" id="remove_price_option" value="REMOVE" style="padding:2px 10px;font-size:10px;" />
            </div>
        </td>
    </tr>
</table>