<?php
?>
<table cellpadding="0" cellspacing="0" border="0" style="width:100%;margin-top:2px;">
    <tr>
    	<td style="width:20%;padding-bottom:10px;vertical-align:top;"><label for="meta_description">Description</label></td>
        <td style="width:80%;padding-bottom:10px;vertical-align:top;">
	        <textarea style="height:100px;width:90%;" id="meta_description" name="meta[meta_description]"><?php echo $meta->meta_description;?></textarea>
        </td>
    </tr>
    <tr>
    	<td style="width:20%;padding-bottom:10px;vertical-align:top;"><label for="meta_keywords">Keyword(s)</label></td>
        <td style="width:80%;padding-bottom:10px;vertical-align:top;">
                <textarea style="height:80px;width:90%;" id="meta_keywords" name="meta[meta_keywords]"><?php echo $meta->meta_keywords;?></textarea>
                <p style="font-size:11px;font-style:italic;padding:0;">Type keywords, separated by a comma e.g. Keyword1, Keyword2</p>
        </td>
    </tr>
</table>
