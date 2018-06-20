<?php
if(!defined('JCMS')){exit();} // No direct access
$userinfo = $_SESSION['__USER']['info'];
$title = 'File Upload';
?>
<script type="text/javascript">
	$(document).ready(function(){		
		<?php
		$path = '/content/uploads/'.date('Y').'/'.date('m'); // Default
		if($_REQUEST['path']!=''){
			$path = rawurldecode($_REQUEST['path']);
		}
		?>
		$('#file_upload').uploadify({
			'uploader' : '<?php get_siteinfo('url');?>/core/js/uploadify/uploadify.swf',
			'script' : '<?php get_siteinfo('url');?>/core/js/uploadify/uploadify.php',
			'cancelImg' : '<?php get_siteinfo('url');?>/core/js/uploadify/cancel.png',
			'multi' : true,
			'fileExt' : '*.jpg;*.gif;*.png',
			'fileDesc' : 'Image Files (.JPG, .GIF, .PNG)',
			'removeCompleted' : true,
			'folder' : '<?php echo $path;?>',
			'queueID' : 'uploading',
			'buttonText': 'Browse',
			'onComplete':function(event, queueID, fileObj, response){
				if(fileObj.filePath !=''){
					$('#uploading > ul').prepend('<li class="gallery"><img src="<?php get_siteinfo('url');?>' + fileObj.filePath + '" /><div class="image-info"><div><label>Direct Link<br/><textarea readonly="readonly" style="width:530px;padding:5px;height:40px;"><?php get_siteinfo('url');?>' + fileObj.filePath + '</textarea></label></div></div><div class="clear"></div></li>');				
				}
			}
		});
    });
</script>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td width="100%" style="vertical-align:top;">
        	<?php load_documentations(); ?>
        	<h2><?php echo $title;?></h2>
            <?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>
			<table id="file-uploader" cellpadding="0" cellspacing="0" border="0">
            	<tr>
                	<td width="15%">
                        <p><input id="file_upload" type="file" name="Filedata" /></p>
                        <p><input type="button" value="Upload Files" onclick="$('#file_upload').uploadifyUpload();" style="width:125px;" /></p>
                        <p><input type="button" value="Clear Queue" onclick="$('#file_upload').uploadifyClearQueue();" style="width:125px;" /></p>
                        <div>Click on <strong>Browse</strong> to upload images.</div>
                    </td>
                	<td width="85%">
	                    <div id="uploading" class="dirs" style="height:350px;border:1px solid #D2D2D2;"><ul></ul></div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>