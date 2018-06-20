<?php if(!defined('IMPARENT')){exit();} // No direct access ?>
<?php
$pos = 'even';
/*$lists = array();
foreach($prices as $category => $cat_value){
	foreach($cat_value as $key => $value){
		if($value['quantity'] > 1){
			$lists[$key]['quantity'] = $value['quantity'];
			$lists[$key]['description'] = $value['description'];
			$lists[$key]['price'][$category] = $value['price'];
		}
	}
}
ksort($lists);*/
?>
<?php if(is_user_logged() && count($bulk_price) > 1 && ($item_price->bulk_option['regular'] == 'yes' || $item_price->bulk_option['custom'] == 'yes')):?>
	<div id="price_lists">
		<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
        	<?php if($item_price->bulk_option['regular'] == 'yes' && $item_price->bulk_option['custom'] != 'yes'):?>
                <tr><th style="width:40%;">Qty</th><th style="width:60%;">Regular</th></tr>
                <?php foreach($bulk_price as $key => $value):?>
                    <tr class="<?php echo $pos;?>">
                        <td class="qty"><a class="bulk_price" href="#<?php echo $value['quantity'];?>" title="Click this to add to calculator"><?php echo $value['description'];?></a></td>
                        <td><?php echo ($value['price']['regular']!=''?'$'.$value['price']['regular']:'Call');?></td>
                    </tr>
                    <?php $pos = ($pos == 'even' ? 'odd' : 'even'); ?>
                <?php endforeach; ?>
        	<?php elseif($item_price->bulk_option['custom'] == 'yes' && $item_price->bulk_option['regular'] != 'yes'):?>
                <tr><th style="width:40%;">Qty</th><th style="width:60%;">Custom</th></tr>
                <?php foreach($bulk_price as $key => $value):?>
                    <tr class="<?php echo $pos;?>">
                        <td class="qty"><a class="bulk_price" href="#<?php echo $value['quantity'];?>" title="Click this to add to calculator"><?php echo $value['description'];?></a></td>
                        <td><?php echo ($value['price']['custom']!=''?'$'.$value['price']['custom']:'Call');?></td>
                    </tr>
                    <?php $pos = ($pos == 'even' ? 'odd' : 'even'); ?>
                <?php endforeach; ?>
        	<?php elseif($item_price->bulk_option['regular'] == 'yes' && $item_price->bulk_option['custom'] == 'yes'):?>
                <tr><th style="width:30%;">Qty</th><th style="width:35%;">Regular</th><th style="width:35%;">Custom</th></tr>
                <?php foreach($bulk_price as $key => $value):?>
                    <tr class="<?php echo $pos;?>">
                        <td class="qty"><a class="bulk_price" href="#<?php echo $value['quantity'];?>" title="Click this to add to calculator"><?php echo $value['description'];?></a></td>
                        <td><?php echo ($value['price']['regular']!=''?'$'.$value['price']['regular']:'Call');?></td>
                        <td><?php echo ($value['price']['custom']!=''?'$'.$value['price']['custom']:'Call');?></td>
                    </tr>
                    <?php $pos = ($pos == 'even' ? 'odd' : 'even'); ?>
                <?php endforeach; ?>
        	<?php endif;?>
		</table>
	</div>
<?php endif;?>
