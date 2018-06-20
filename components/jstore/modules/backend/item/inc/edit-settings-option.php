<table cellpadding="0" cellspacing="0" border="0" style="width:100%;margin-top:2px;">
    <tr>
    	<td style="width:20%;padding-bottom:10px;vertical-align:top;"><label>Featured Page</label></td>
        <td style="width:80%;padding-bottom:10px;vertical-align:top;">
            <label style="display:inline;"><input type="radio" name="meta[featured_page][active]" value="featured_page_yes"<?php echo ($meta->featured_page['active'] == 'featured_page_yes' ? ' checked="checked"' : '');?> /> Yes</label>
            <label style="display:inline;"><input type="radio" name="meta[featured_page][active]" value="featured_page_no"<?php echo ($meta->featured_page['active'] == 'featured_page_no' ? ' checked="checked"' : '');?> /> No</label>
            <label for="featured_page_order">Order</label>
            <input type="text" id="featured_page_order" name="meta[featured_page][order]" value="<?php echo ($meta->featured_page['order'] > 0 ? $meta->featured_page['order'] : '0');?>" style="width:100px;" /><br/>
            <label for="featured_page_custom">Custom</label>
            <textarea id="featured_page_custom" name="meta[featured_page][custom]" style="width:97%;height:100px;"><?php echo html_entity_decode(stripslashes($meta->featured_page['custom']));?></textarea>
            <label for="featured_page_image">Main Image</label>
            <input type="text" id="featured_page_image" name="meta[featured_page][image]" value="<?php echo $meta->featured_page['image'];?>" style="width:97%;" /><br/>
            <p>Click <strong>Browse</strong> or <strong>Paste</strong> the link to add main featured image</p>
            <input id="featured_page_image_browse" type="file" name="Filedata" />
        </td>
    </tr>
</table>