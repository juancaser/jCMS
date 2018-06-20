<p>Click on the checkbox to remove selection.</p>
<?php
$c = (object) $meta->color;
$item_color = $c->option;
?>
<table id="color_option" class="items" cellpadding="0" cellspacing="0" border="0" style="width:100%;margin-top:2px;">
    <tr>
        <th style="width:2%;"></th>
        <th style="width:27%;">Color</th>
        <th style="width:30%;">Type</th>
        <th style="width:40%;">Label</th>
    </tr>
    <?php if(is_array($item_color) && count($item_color) > 0):?>
        <?php for($i=1;$i <= count($item_color);$i++):?>
            <tr id="color<?php echo $i;?>">
                <td><?php if($i > 1):?><input type="checkbox" class="chk" value="<?php echo $i;?>" /><?php endif;?></td>
                <td><input type="text" style="width:97%" name="meta[color][option][<?php echo $i;?>][hex]" class="hex field" value="<?php echo $item_color[$i]['hex'];?>" /></td>
                <td>
                    <select style="width:96%" name="meta[color][option][<?php echo $i;?>][type]" class="type field">
                        <option value=""></option>
                        <?php foreach($color_type as $key => $value):?>
                            <option value="<?php echo $key;?>"<?php echo ($item_color[$i]['type'] == $key ? ' selected="selected"' : '');?>><?php echo $value;?></option>
                        <?php endforeach;?>
                    </select>
                </td>
                <td><input type="text" style="width:97%" name="meta[color][option][<?php echo $i;?>][label]" class="label field" value="<?php echo $item_color[$i]['label'];?>" /></td>
            </tr>
        <?php endfor;?>
    <?php else: ?>
            <tr id="color1">
                <td></td>
                <td><input type="text" style="width:97%" name="meta[color][option][1][hex]" class="hex field" value="" /></td>
                <td>
                    <select style="width:96%" name="meta[color][option][1][type]" class="type field">
                        <option value=""></option>
                        <?php foreach($color_type as $key => $value):?>
                            <option value="<?php echo $key;?>"><?php echo $value;?></option>
                        <?php endforeach;?>
                    </select>
                </td>
                <td><input type="text" style="width:96%" name="meta[color][option][1][label]" class="label field" value="" /></td>
            </tr>
    <?php endif; ?>
</table>
<div style="padding-bottom:10px;">
    <input type="button" id="add_color" value="ADD COLOR" style="padding:2px 10px;font-size:10px;" />&nbsp;
    <input type="button" id="remove_color" value="REMOVE" style="padding:2px 10px;font-size:10px;" />
</div>