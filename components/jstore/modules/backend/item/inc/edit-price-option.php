<div id="price_dlg" class="dialog" title="Add - Bulk Price Option">
    <table cellpadding="0" cellspacing="0" border="0" style="width:324px;margin-top:2px;">
        <tr>
            <td style="width:150px;padding-bottom:10px;"><label for="min_qty">Minimum Quantity</label></td>
            <td style="width:179px;"><input type="text" id="min_qty" style="width:159px;" /></td>
        </tr>
        <tr>
            <td style="padding-bottom:10px;"><label for="per_item">Price per Item</label></td>
            <td style="padding-bottom:10px;"><input type="text" id="per_item" style="width:159px;" /></td>
        </tr>
        <tr>
            <td style="padding-bottom:10px;"><label for="bulk_category">Category</label></td>
            <td style="padding-bottom:10px;"><span id="bulk_category_display"></span><input type="hidden" id="bulk_category" /></td>
        </tr>
        <tr>
            <td colspan="2" style="padding-bottom:10px;"><label for="bulk_description" style="display:block;">Description</label>
            	<textarea id="bulk_description" style="width:313px;"></textarea>
            </td>
        </tr>
    </table>
    <input type="hidden" id="bpid" value="" />
</div>

<table cellpadding="0" cellspacing="0" border="0" style="width:100%;margin-top:2px;">
    <tr>
    	<td style="width:20%;padding-bottom:10px;vertical-align:top;"><label for="get_quote">Quote Message</label></td>
        <td style="width:80%;padding-bottom:10px;vertical-align:top;">
        	<textarea id="get_quote" name="meta[quote_message]" style="width:99%;height:80px;margin:0;"><?php echo $meta->quote_message;?></textarea>
        </td>
    </tr>
    <tr>
    	<td style="width:10%;padding-bottom:10px;vertical-align:top;"><label>Bulk</label></td>
        <td style="width:90%;padding-bottom:10px;vertical-align:top;">
            <div id="bulk">
				<?php foreach($price_category as $key => $value): ?>
                	<div id="<?php echo $key;?>" class="category">
                		<h4><label><input type="checkbox" name="item_price[bulk_option][<?php echo $key;?>]" value="yes"<?php echo ($p->bulk_option[$key] == 'yes' ? ' checked="checked"' : '');?> /><?php echo $value;?></label></h4>
                		<div class="control">
                        <?php if($p->bulk_option[$key] == 'yes'):?>
	                        Click on the button to add more bulk pricing option <input type="button" class="add_bulk" value="ADD OPTION" alt="<?php echo $key;?>" /><div class="clear"></div>
						<?php else:?>
                        	Click on the checkbox to activate this option.
						<?php endif;?>
                        </div>
                        <?php if($p->bulk_option[$key] == 'yes'):?>
                            <ul>
                                <?php $oe = 'odd';?>
                                <?php $i = 1;?>
                                <?php foreach($bulk_prices as $key2 => $value):?>
                                    <?php if(isset($value['price'][$key]) && $value['price'][$key] > 0):?>
                                        <?php $id = $key.'_'.$i;?>
                                        <li id="prc_<?php echo $id;?>" class="<?php echo $oe;?>">
                                            <div class="quantity">                                        
                                                <span id="desc_<?php echo $id;?>"><?php echo ($value['description']!=''?$value['description']:$value['quantity'].'+');?></span>
                                                <div class="remedit"><a href="javascript:void(0);" onclick="edit_bulk_option('<?php echo $id;?>');" alt="<?php echo $value['quantity'];?>">Edit</a> | <a href="javascript:void(0);" onclick="remove_bulk_option('<?php echo $id;?>');"  alt="<?php echo $key.'_'.$value['quantity'];?>">Remove</a></div><div class="clear"></div>
                                            </div>
                                            <div class="price">Minimum Quantity &rsaquo; <strong><span id="qty_<?php echo $id;?>"><?php echo $value['quantity'];?></span></strong> | Price per Item &rsaquo; <strong>$<span id="price_<?php echo $id;?>"><?php echo $value['price'][$key];?></span></strong></div>
                                            <input type="hidden" id="data_<?php echo $id;?>" name="item_price[bulk][]" value="<?php echo $value['quantity'];?>|<?php echo $value['price'][$key];?>|<?php echo $key;?>|<?php echo $value['description'];?>" />
                                        </li>
                                        <?php $oe = ($oe == 'odd' ? 'even' : 'odd');?>
                                        <?php $i++;?>
                                    <?php endif;?>
                                <?php endforeach;?>
                            </ul>
						<?php else:?>
							<?php foreach($bulk_prices as $key2 => $value):?>
                                <?php if(isset($value['price'][$key]) && $value['price'][$key] > 0):?>
									<?php $id = $key.'_'.$i;?>
                                    <input type="hidden" id="data_<?php echo $id;?>" name="item_price[bulk][]" value="<?php echo $value['quantity'];?>|<?php echo $value['price'][$key];?>|<?php echo $key;?>|<?php echo $value['description'];?>" />
                                    <?php $i++;?>
                                <?php endif;?>
                            <?php endforeach;?>
						<?php endif;?>
                    </div>
                <?php endforeach; ?>
            </div>
        </td>
    </tr>
</table>