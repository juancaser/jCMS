<p>Click on the checkbox to remove selection.</p>
<?php
$print_location = $meta->print_location;
?>
<table id="print_option" class="items" cellpadding="0" cellspacing="0" border="0" style="width:100%;margin-top:2px;">
    <tr>
        <th style="width:2%;"></th>
        <th style="width:78%;">Label</th>
        <th style="width:20%;">Setup Fee</th>
    </tr>
    <?php if(count($print_location) > 0 && is_array($print_location)):?>
        <?php for($i=1;$i <= count($print_location);$i++):$loc = $print_location[$i];?>
            <tr id="loc<?php echo $i;?>">
                <td><?php if($i > 1):?><input type="checkbox" class="chk" value="<?php echo $i;?>" /><?php endif;?></td>
                <td><input type="text" style="width:97%" name="meta[print_location][<?php echo $i;?>][label]" class="label field" value="<?php echo $loc['label'];?>" /></td>
                <td><input type="text" style="width:96%" name="meta[print_location][<?php echo $i;?>][fee]" class="fee field" value="<?php echo $loc['fee'];?>" /></td>
            </tr>
        <?php endfor;?>
    <?php else:?>
            <tr id="loc1">
                <td></td>
                <td><input type="text" style="width:97%" name="meta[print_location][1][label]" class="label field" value="" /></td>
                <td><input type="text" style="width:95%" name="meta[print_location][1][fee]" class="fee field" value="" /></td>
            </tr>
    <?php endif;?>
</table>
<div style="padding-bottom:10px;">
    <input type="button" id="add_print_location" value="ADD PRINT LOCATION" style="padding:2px 10px;font-size:10px;" />&nbsp;
    <input type="button" id="remove_print_location" value="REMOVE" style="padding:2px 10px;font-size:10px;" />
</div>