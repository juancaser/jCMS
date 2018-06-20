<div style="padding-bottom:10px;">
    <label style="font-weight:normal;"><input type="checkbox" name="meta[crop_image]" value="yes"<?php echo ($meta->crop_image == 'yes' ? ' checked="checked"' : '');?> /> Cropped image if it exceed specified size?</label>
</div>
<div id="media">
    <input type="hidden" id="media_main" name="meta[media][main]" value="<?php echo $meta->media['main'];?>" />
    <input type="hidden" id="media_thumb" name="meta[media][thumb]" value="<?php echo $meta->media['thumb'];?>" />
    <div class="main-image">
        <div class="img-cont">
            <?php if($item->item_product_image!=''):?>
                <a id="item_product_image_display" target="_blank" href="<?php echo $item->item_product_image;?>">
                    <img src="<?php echo $item->item_product_image;?>"<?php echo ($meta->crop_image == 'yes' ? '': ' style="width:'.$main[0].'px;height:'.$main[1].'px;"');?> />
                </a>
            <?php else:?>
                <a id="item_product_image_display" target="_blank"></a>                                    
            <?php endif;?>
            <input type="hidden" id="item_product_image2" name="item_product_image" value="<?php echo $item->item_product_image;?>" />
        </div>
        <div id="item_product_image_button" class="empty">
            <span style="font-weight:bold;">Main</span> (<?php echo $main[0];?> x <?php echo $main[1];?>)<br />                                        
            <input id="item_product_image" type="file" name="Filedata" />
        </div>
    </div>
    
    <div class="thumb-image">
        <div class="img-cont">
            <?php if($item->item_product_image_thumb!=''):?>
                <a id="item_product_image_thumb_display" target="_blank" href="<?php echo $item->item_product_image_thumb;?>">
                    <img src="<?php echo $item->item_product_image_thumb;?>"<?php echo ($meta->crop_image == 'yes' ? '' : ' style="width:'.$thumb[0].'px;height:'.$thumb[1].'px;"');?> />
                </a>
            <?php else:?>
                <a id="item_product_image_thumb_display" target="_blank"></a>                                    
            <?php endif;?>	
            <input type="hidden" id="item_product_image_thumb2" name="item_product_image_thumb" value="<?php echo $item->item_product_image_thumb;?>" />
        </div>
        <div id="item_product_image_button" class="empty">
            <span style="font-weight:bold;">Thumb</span> (<?php echo $thumb[0];?> x <?php echo $thumb[1];?>)<br />
            <input id="item_product_image_thumb" type="file" name="Filedata" />
        </div>	
    </div>
    <div class="clear"></div>
</div>