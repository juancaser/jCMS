<?php
if(!defined('JCMS')){exit();} // No direct access
$userinfo = $_SESSION['__BACKEND_USER']['info'];
function _page_template($current){
	if($handle = opendir(GBL_ROOT_TEMPLATE.'/html')){
		while (false !== ($file = readdir($handle))){
			if($file != "." && $file != ".." && in_array($file,array('post','page'))){
				$l = '';
				if($handle2 = opendir(GBL_ROOT_TEMPLATE.'/html/'.$file)) {
					while (false !== ($file2 = readdir($handle2))){
						if ($file2 != "." && $file2 != ".."){
							$l.='<option '.($current == $file2 ? ' selected="selected"' : '').'value="'.GBL_ROOT_TEMPLATE.'/html/'.$file.'/'.$file2.'">'.$file2.'</option>'."\n";
						}
					}
					closedir($file2);
					if($l!=''){
						$content.= '<optgroup label="'.ucwords($file).'">'.$l.'</optgroup>'."\n";
					}
				}
			}
		}
		closedir($handle);
	}
	echo $content;
}
?>
<script type="text/javascript">
	$(document).ready(function(){
        $('#content').tinymce({
            script_url : '<?php get_siteinfo('url');?>/core/js/jquery-tiny_mce/tiny_mce.js',		
            theme : "advanced",
            plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
            content_css : "<?php echo BACKEND_DIRECTORY;?>/css/tinymce.css",
            theme_advanced_buttons1 : "bold,italic,underline,strikethrough,forecolor,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,pagebreak,|,image,media,|,outdent,indent,blockquote,|,charmap,|,fullscreen,code",
            theme_advanced_buttons2 : "formatselect,removeformat,|,pastetext,pasteword,|,undo,redo,|,filemanager",
			theme_advanced_buttons3 : "",
			theme_advanced_buttons4 : "",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : false,
			convert_urls : false,
			setup : function(ed){	
				ed.addButton('filemanager',{
					title : 'File Manager',
					image : '<?php get_siteinfo('url');?>/backend/images/fm.gif',
					onclick : function(){
						/*$('#filemanager').css('display','block');*/
						/*ed.focus();
						ed.selection.setContent('Hello world!');*/
						$('#filemanager').dialog('open');
						return false;
					}
				});
			}
        });
		
		$('#save-draft').click(function(){
			$('#page_status').val('draft');
			$('#page-form').submit();
		});
		$('#save-draft').click(function(){
			$('#page_status').val('draft');
			$('#page-editor').submit();
		});
		
		var maskHeight = $(document).height();
		var maskWidth = $(window).width();	
		$('#filemanager').css({'width':maskWidth,'height':maskHeight});
		$('#tabs2').tabs();
		$('#tabs').tabs({
			ajaxOptions: {
				error: function( xhr, status, index, anchor ) {
					$( anchor.hash ).html(
						"Couldn't load this tab. We'll try to fix this as soon as possible. " +
						"If this wouldn't be a demo." );
				}
			},
			select: function(event,ui){
				if(ui.index == 0){
					$('#uploading').html('');
				}
			}
		});
		
		
		$('#featured_page_image_browse').uploadify({
			'uploader' : '<?php get_siteinfo('url');?>/core/js/uploadify/uploadify.swf',
			'script' : '<?php get_siteinfo('url');?>/core/js/uploadify/uploadify.php',
			'cancelImg' : '<?php get_siteinfo('url');?>/core/js/uploadify/cancel.png',
			'multi' : false,
			'auto' : true,
			'fileExt' : '*.jpg;*.gif;*.png',
			'fileDesc' : 'Image Files (*.jpg,*.gif,*.png)',
			'removeCompleted' : true,
			'folder' : '/content/uploads/<?php echo date('Y');?>/<?php echo date('m');?>',
			'queueID' : 'uploading',
			'buttonText': 'Browse',
			'onComplete':function(event, queueID, fileObj, response){
				if(fileObj.filePath !=''){
					$('#featured_page_image').val('<?php get_siteinfo('url');?>' + fileObj.filePath);				
				}
			}
		});
		
		$('#file_upload').uploadify({
			'uploader' : '<?php get_siteinfo('url');?>/core/js/uploadify/uploadify.swf',
			'script' : '<?php get_siteinfo('url');?>/core/js/uploadify/uploadify.php',
			'cancelImg' : '<?php get_siteinfo('url');?>/core/js/uploadify/cancel.png',
			'multi' : true,
			'fileExt' : '*.jpg;*.gif;*.png',
			'fileDesc' : 'Image Files (.JPG, .GIF, .PNG)',
			'removeCompleted' : true,
			'folder' : '/content/uploads/<?php echo date('Y');?>/<?php echo date('m');?>',
			'queueID' : 'uploading',
			'buttonText': 'Browse',
			'onComplete':function(event, queueID, fileObj, response){
				if(fileObj.filePath !=''){
					$('#uploading > ul').prepend('<li class="gallery"><img src="<?php get_siteinfo('url');?>' +fileObj.filePath + '" /><div class="image-info"><div><label>Direct Link<br/><textarea readonly="readonly" style="width:420px;padding:5px;height:40px;"><?php get_siteinfo('url');?>' + fileObj.filePath + '</textarea></label></div><div style="padding-top:10px;"><input type="button" value="Insert to Post" onclick="insert_img_topost(\'<?php get_siteinfo('url');?>'+ fileObj.filePath +'\');" /></div></div><div class="clear"></div></li>');				
				}
			}
		});
		
		$('#filemanager').dialog({
			autoOpen: false,
			width: 700,
			height: 420,
			modal: true,
			resizable: false,
			buttons: {
				"Close": function() { 
					$(this).dialog("close"); 
				} 
			}
		});
		
		
    });
	function insert_img_topost(src){
		tinyMCE.activeEditor.focus();
        tinyMCE.activeEditor.execCommand('mceInsertContent', false, '<img src="' + src + '" />');
        tinyMCE.activeEditor.close();
	}
	function delete_img(li,src){
		$.ajax({
			type: 'POST',
			url: backend_directory + '/ajax.php',
			data: 'action=delete_image&image=' + src,
			beforeSend: function(data){					
			},success: function(data){				
				var li_count = $('#gallery-images > ul > li').length;
				if(li_count == 1){
					$('#gallery-images').html('<div id="gallery-images" class="dirs"><p class="messagebox">No images found on the gallery</p></div>');
				}else{
					if(data == '1'){
						$('#' + li).remove();
					}
				}
			}
		});
	}
</script>
<div id="filemanager" title="File Manager">
    <div id="tabs" class="tabs">
        <ul>
            <li><a href="#tabs1">Upload</a></li>
            <li><a href="<?php get_siteinfo('url');?>/backend/ajax.php?action=uploaded_images">Gallery</a></li>
        </ul>
        <div id="tabs1">
            <div style="padding:10px;">
                <div class="left">
                    <p style="padding:0 0 10px 0;margin:0 0 10px 0;border-bottom:1px dotted #AAAAAA;"><input id="file_upload" type="file" name="Filedata" /></p>
                    <p style="padding:0 0 5px 0;margin:0;"><input type="button" value="Upload Files" onclick="$('#file_upload').uploadifyUpload();" style="width:125px;" /></p>
                    <p style="padding:0 0 5px 0;margin:0;"><input type="button" value="Clear Queue" onclick="$('#file_upload').uploadifyClearQueue();" style="width:125px;" /></p>
                    <div style="padding:10px 0 0 0;margin:10px 0 0 0;border-top:1px dotted #AAAAAA;width:130px;">Click on <strong>Browse</strong> to upload images.</div>
                </div>
                <div id="uploading" class="right dirs" style="width:465px;height:215px;border:1px solid #E5E5E5;"><ul></ul></div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php /*
<div id="filemanager">
    <div class="box">
        <div class="inner">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs1">Upload</a></li>
                    <li><a href="<?php get_siteinfo('url');?>/backend/ajax.php?action=uploaded_images">Gallery</a></li>
                </ul>
                <div id="tabs1">
                	<div style="padding:10px;">
                    	<div class="left">
                        	<p style="padding:0 0 10px 0;margin:0 0 10px 0;border-bottom:1px dotted #AAAAAA;"><input id="file_upload" type="file" name="Filedata" /></p>
                            <p style="padding:0 0 5px 0;margin:0;"><input type="button" value="Upload Files" onclick="$('#file_upload').uploadifyUpload();" style="width:125px;" /></p>
                            <p style="padding:0 0 5px 0;margin:0;"><input type="button" value="Clear Queue" onclick="$('#file_upload').uploadifyClearQueue();" style="width:125px;" /></p>
                            <div style="padding:10px 0 0 0;margin:10px 0 0 0;border-top:1px dotted #AAAAAA;width:130px;">Click on <strong>Browse</strong> to upload images.</div>
                        </div>
                        <div id="uploading" class="right dirs" style="width:680px	;height:350px;border:1px solid #AAAAAA;"><ul></ul></div>
                        <div class="clear"></div>
					</div>
                </div>
            </div>
            <input type="button" value=" Close " onclick="$('#filemanager').css('display','none');$('#file_upload').uploadifyClearQueue();" class="right" />
            <div class="clear"></div>
        </div>
    </div>
</div>
*/ ?>
<form id="page-editor" method="post" action="<?php echo BACKEND_DIRECTORY;?>/pages.php"> 
    <table class="editor" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td class="sidebar">
                <div class="box">
                    <div class="title">Publish</div>
                    <div class="content">
                    	<?php
						$back = BACKEND_DIRECTORY.'/pages.php'.(($page->page_type == 'post' || $_REQUEST['type'] == 'post') ? '?mod=post' : '');
						if($page->parent_page > 0 || $_REQUEST['parent'] > 0){
							$back = $back.'&id='.($_REQUEST['parent'] > 0 ? $_REQUEST['parent'] : $page->parent_page);
						}
                        ?>
                        <?php if($_REQUEST['id']!=''): ?>
                            <?php if($page->status == 'draft'): ?>
                                <p><input class="button" type="submit" value="Published" /></p>
								<p><input class="button" type="button" id="save-draft" value="Save as Draft" /></p>
                            <?php else: ?>
                                <p><input class="button" type="submit" value="Update" /></p>
                                <p><input class="button" type="button" id="save-draft" value="Save as Draft" /></p>
                            <?php endif; ?>
                            <p><input class="button" style="margin-top:10px;" type="button" value="Trashed" onclick="window.location='<?php echo BACKEND_DIRECTORY;?>/pages.php?form=page&action=trashed&chk[]=<?php echo $page->ID;?>';" /></p>
                        <?php else:?>
                            <p><input class="button" type="submit" value="Published" /></p>
                            <p><input class="button" type="button" id="save-draft" value="Save as Draft" /></p>
                        <?php endif; ?>                        
                        <p><input class="button" type="button" value="Back" onclick="window.location='<?php echo $back;?>';" /></p>
                    </div>
                </div>                
                <div class="box">
                    <div class="title"><?php echo ($_REQUEST['type']=='post' ? 'Post' : 'Page'); ?> Option</div>
                    <div class="content">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
								<?php if(in_array($userinfo->user_group,array('ghosts','administrators'))):?>
                                <td width="45%" style=""><label for="page_type">Type:</label></td>
                                <td width="55%" class="text-right">
                                    <select name="page_type" id="page_type" style="width:100%;">
                                    	<?php
											$is_page = true;$is_post = false;
											if($page->page_type == 'post' || $_REQUEST['type'] ==  'post'){$is_page = false;$is_post = true;}
                                        ?>
                                        <option value="page"<?php echo ($is_page ? ' selected="selected"' : '');?>>Page</option>
                                        <option value="post"<?php echo ($is_post ? ' selected="selected"' : '');?>>Post</option>
                                    </select>                                        
                                </td>
								<?php else:?>
                                <td width="35%"  style="padding-bottom:5px;"><label for="page_type">Type:</label></td>
                                <td width="65%" style="padding-bottom:5px;">                                    
                                    <?php								
									$type = 'page';
									if($page->page_type == 'post' || $_REQUEST['type'] ==  'post'){$type = 'post';}
                                    ?>
                                    <span class="page_type" style="font-weight:bold;color:#D54E21;"><?php echo ucwords($type); ?></span>
                                	<input type="hidden" name="page_type" id="page_type" value="<?php echo $type; ?>" />                                      
                                </td>
								<?php endif;?>
                            </tr>
                            <tr>
                                <td colspan="2"><label class="block" for="parent_page">Parent Page:</label>
                                    <select name="parent_page" id="parent_page" style="width:100%;display:block;">
                                        <option value="0">(no parent)</option>
                                        <?php make_select_lists_page(10,0,($page->parent_page > 0 ? $page->parent_page : $_REQUEST['parent']));?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><label class="block" for="page_template">Template:</label>
                                    <select name="meta[page_template]" id="page_template" style="width:100%;display:block;">
                                        <option value="">(default template)</option>
                                        <?php _page_template(basename($meta->page_template)); ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td width="70%"><label for="menu_order">Menu Order:</label></td>
                                <td width="30%" class="text-right"><input type="text" name="menu_order" id="menu_order" value="<?php echo $page->menu_order;?>" style="width:50px;" /></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
        	<td class="content">
	            <?php load_documentations(); ?>            	
            	<h2><?php echo $title;?></h2>
                <?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>
                <p><input type="text" name="title" id="title" class="<?php  echo ($page->title !='' ? '' : 'textfield-info '); ?>editor-title <?php echo ($page->slug!='' ? '' : 'slugger');?>" value="<?php echo $page->title;?>" alt="Enter title here" autocomplete="off" /></p>
                <p><span class="slug-caption">Slug: </span>
                    <span id="page-slug"><?php echo ($page->slug !='' ? $page->slug : 'Type on the title to generate slug');?></span>
                    <span id="edit-slug" class="<?php echo ($page->slug!='' ? '' : 'hide');?>"> <input type="button" value="Edit" onclick="update_slug();"  /></span>
                    <?php if($page->ID > 0):?><span id="view-page"> <input type="button" value="View" onclick="window.open('<?php echo _generate_page_permalink($page->ID);?>');"  /></span><?php endif;?>
                <input type="hidden" name="slug" id="slug" value="<?php echo $page->slug;?>" /></p>
				<?php
                $content = $page->content;
                $content = html_entity_decode($content);
                $content = stripslashes($content);
                ?>
              	<p><textarea id="content" name="content" style="width:99%;"><?php echo ($page->content!=''?$content:'');?></textarea></p>
                
                <div id="tabs2" class="tabs" style="margin-top:10px;">                
                    <ul>
                        <li><a href="#tabs2_1">Custom Fields</a></li>
                        <li><a href="#tabs2_2">Meta Information</a></li>
                        <li><a href="#tabs2_3">Settings</a></li>
                    </ul>
                    <div id="tabs2_1">
                        <?php $custom_fields_data = $meta->custom_fields_data;?>                        
                        <div id="custom_fields">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td class="col1"><input type="text" name="custom_fields_data[field1][key]" value="<?php echo $custom_fields_data['field1']['key'];?>"/></td>
                                    <td class="col2"><textarea name="custom_fields_data[field1][value]"><?php echo $custom_fields_data['field1']['value'];?></textarea></td>
                                </tr>
                            </table>
                            <?php for($i=2;$i <= count($custom_fields_data);$i++):?>
                            <table id="field<?php echo $i;?>" cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td class="col1"><input type="text" name="custom_fields_data[field<?php echo $i;?>][key]" value="<?php echo $custom_fields_data['field'.$i]['key'];?>"/></td>
                                    <td class="col2">
                                        <textarea name="custom_fields_data[field<?php echo $i;?>][value]"><?php echo $custom_fields_data['field'.$i]['value'];?></textarea>
                                        <input type="button" class="button" value="Remove" onclick="remove_custom_field('field<?php echo $i;?>');" />
                                    </td>
                                </tr>
                            </table>
                            <?php endfor;?>
                        </div>                        
                        <input type="button" value="Add" id="add_custom_fields"/>
                    </div>
                    <div id="tabs2_2">
                        <table class="page-settings" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td class="col1" style="vertical-align:top;"><label for="meta_description">Description</label></td>
                                <td class="col2" style="vertical-align:top;"><textarea style="height:100px;" id="meta_description" name="meta[meta_description]"><?php echo $meta->meta_description;?></textarea></td>
                            </tr>
                            <tr>
                                <td class="col1" style="vertical-align:top;"><label for="meta_keywords">Keyword(s)</label></td>
                                <td class="col2" style="vertical-align:top;">
                                	<textarea style="height:80px;" id="meta_keywords" name="meta[meta_keywords]"><?php echo $meta->meta_keywords;?></textarea>
                                    <p style="font-size:11px;font-style:italic;padding:0;">Type keywords, separated by a comma e.g. Keyword1, Keyword2</p>
                                </td>
                            </tr>
                        </table>
                    </div>  
                    <div id="tabs2_3">
                        <table class="page-settings" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td class="col1"><label>Featured Page</label></td>
                                <td class="col2">
                                    <label style="display:inline;"><input type="radio" name="meta[featured_page][active]" value="featured_page_yes"<?php echo ($meta->featured_page['active'] == 'featured_page_yes' ? ' checked="checked"' : '');?> /> Yes</label>
                                    <label style="display:inline;"><input type="radio" name="meta[featured_page][active]" value="featured_page_no"<?php echo ($meta->featured_page['active'] == 'featured_page_no' ? ' checked="checked"' : '');?> /> No</label>
                                    <label for="featured_page_order">Order</label>
                                    <input type="text" id="featured_page_order" name="meta[featured_page][order]" value="<?php echo ($meta->featured_page['order'] > 0 ? $meta->featured_page['order'] : '0');?>" style="width:100px;" /><br/>
                                    <label for="featured_page_custom">Custom</label>
                                    <textarea id="featured_page_custom" name="meta[featured_page][custom]" style="width:99%;height:100px;"><?php echo html_entity_decode(stripslashes($meta->featured_page['custom']));?></textarea>
                                    <label for="featured_page_image">Main Image</label>
                                    <input type="text" id="featured_page_image" name="meta[featured_page][image]" value="<?php echo $meta->featured_page['image'];?>" style="width:500px;" /><br/>
                                    <p>Click <strong>Browse</strong> or <strong>Paste</strong> the link to add main featured image</p>
                                    <input id="featured_page_image_browse" type="file" name="Filedata" />
                                </td>
                            </tr>
                        </table>
                    </div>                    
                </div>                
            </td>            
        </tr>        
    </table>
    <input type="hidden" name="author" value="<?php echo ($page->author > 0 ? $page->author : $userinfo->ID);?>" />
    <?php if($_REQUEST['id'] > 0):?><input type="hidden" name="id" value="<?php echo $page->ID;?>" /><?php endif;?>
    <input type="hidden" id="slug_check" value="<?php echo ($page->ID > 0 ? 'no' : 'yes');?>" />
    <input type="hidden" name="status" id="page_status" value="published" />    
    <input type="hidden" name="action" value="save" />    
    <input type="hidden" name="mod" value="edit" />    
</form>