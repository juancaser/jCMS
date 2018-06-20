<?php
if(!defined('JCMS')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access
enque_script('jquery-ui');
enque_style('jquery-ui-theme');
enque_script('uploadify');
enque_script('swfobject');
enque_style('uploadify-css');


$userinfo = $_SESSION['__USER']['info'];

$main = array('239','114');
$thumb = array('100','100');


if($_REQUEST['id'] > 0){
	$category = get_category($_REQUEST['id']);
		
	$meta = (object) unserialize($category->cat_meta);
}
$title = ($_REQUEST['id'] > 0 ? $category->cat_title.' [Update]' : 'New Category');
?>
<style type="text/css">
/* jQuery UI Datepicker */
#ui-datepicker-div{display:none;}
.ui-datepicker th {
	font-size:1.2em;
}

#cat_slug{
	border:0;
	background-color:#FFFFFF;
	font-style:italic;
}
/*.item-title{
	width:400px;
}
	.item-title #item_name{
		width:395px;
	}
	.item-title #ajax_loader-1{
		float:right;
		padding:5px 0;
		margin-top:-33px;
		filter:alpha(opacity=60);opacity:0.6;
	}*/
</style>
<script type="text/javascript">
	$(document).ready(function(){
		$('.date').datepicker({
			inline: true,
			dateFormat: 'yy-mm-dd'
		});
		$('#tabs').tabs();
		$('#tabs_fm').tabs();
		
		$('#fm').dialog({
			autoOpen: false,
			width: 700,
			height: 500,
			modal: true,
			resizable: false,
			buttons: {
				"Close": function() { 
					$(this).dialog("close"); 
				} 
			}
		});
		$('.autocomplete-off').attr('autocomplete','off');
		/*$('.check-slug').wrap('<div id="ajax_cat_name"/>');*/
		/*$('#cat_title').blur(function(){			
			var defaultValue = $(this).attr('defaultValue');
			$this = $(this);
			if($this.val() != defaultValue){
				var dta = $(this).val();
				dta = dta.replace(/&/gi, 'and');
				$.ajax({
					type: 'POST',
					url: '<?php get_siteinfo('url');?>/components/<?php echo $_REQUEST['comp'];?>/ajax.php',
					data: 'action=check_slug&req=product_category&data=' + dta,
					beforeSend: function(data){
						$('#ajax_cat_name > .overlay').remove();
						$('#ajax_cat_name > .msg').remove();
						$('#ajax_cat_name > .loader').remove();						
						$('#ajax_cat_name').prepend('<div class="overlay"></div>');
						$('#ajax_cat_name').append('<img class="loader" src="<?php get_siteinfo('url');?>/backend/images/loader-1.gif" />');
						$('#cat_title').addClass('ajax-processing');
						$('#cat_slug').val('');
					},success: function(data){
						$('#ajax_cat_name > .overlay').remove();
						$('#ajax_cat_name > .msg').remove();
						var obj = $.parseJSON(data);
						if(obj.status == '1'){							
							$('#ajax_cat_name > .loader').attr('src','<?php get_siteinfo('url');?>/backend/images/icon-error-16.png');
							$('#ajax_cat_name').append('<span class="msg">NAME ALREADY EXISTS</span>');
							$('#cat_slug').val('');
							$('#cat_title').addClass('error');
						}else{
							$('#ajax_cat_name > .loader').attr('src','<?php get_siteinfo('url');?>/backend/images/icon-check-16.png');
							$('#ajax_cat_name').append('<span class="msg">OK</span>');
							$('#cat_slug').val(obj.slug);
							$('#cat_title').removeClass('error');
							$('#cat_title').removeClass('ajax-processing');
						}
					}
				});
			}									  
		});*/
		
		$('.required').click(function(){
			$('#form-messagebox').html('').removeClass('messagebox messagebox-error').css('display','none');
		});
		
		$('.image-gallery').find('a').click(function(){			
			$('.image-gallery').find('a.main-image').removeClass('main-image');
			$(this).addClass('main-image');
			$('#item_product_image').val($(this).attr('href'));
			return false;
		});

        $('#cat_description').tinymce({
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
			setup : function(ed){	
				ed.addButton('filemanager',{
					title : 'File Manager',
					image : '<?php get_siteinfo('url');?>/backend/images/fm.gif',
					onclick : function(){
						/*$('#filemanager').css('display','block');*/
						/*ed.focus();
						ed.selection.setContent('Hello world!');*/
						$('#fm').dialog('open');
						return false;
					}
				});
			}   
        });
		
		$('#btn_category_image_main').uploadify({
			'uploader' : '<?php get_siteinfo('url');?>/core/js/uploadify/uploadify.swf',
			'script' : '<?php get_siteinfo('url');?>/core/js/uploadify/uploadify.php',
			'cancelImg' : '<?php get_siteinfo('url');?>/core/js/uploadify/cancel.png',
			'multi' : false,
			'auto' : true,
			'fileExt' : '*.jpg;*.gif;*.png',
			'fileDesc' : 'Image Files (.JPG, .GIF, .PNG)',
			'removeCompleted' : true,
			'folder' : '/content/uploads/<?php echo date('Y');?>/<?php echo date('m');?>',
			'queueID' : 'uploading',
			'buttonText': 'Browse',
			'onComplete':function(event, queueID, fileObj, response){
				if(fileObj.filePath !=''){				
					$('#main_image').html('<img src="<?php get_siteinfo('url');?>/core/timthumb.php?src=<?php get_siteinfo('url');?>' + fileObj.filePath + '&w=<?php echo $main[0];?>&h=<?php echo $main[1];?>&zc=1" />').attr('href','<?php get_siteinfo('url');?>' + fileObj.filePath);
					$('#category_image_main').val('<?php get_siteinfo('url');?>/core/timthumb.php?src=<?php get_siteinfo('url');?>' + fileObj.filePath + '&w=<?php echo $main[0];?>&h=<?php echo $main[1];?>&zc=1');
				}
			}
		});	
		
		$('#btn_category_image_thumb').uploadify({
			'uploader' : '<?php get_siteinfo('url');?>/core/js/uploadify/uploadify.swf',
			'script' : '<?php get_siteinfo('url');?>/core/js/uploadify/uploadify.php',
			'cancelImg' : '<?php get_siteinfo('url');?>/core/js/uploadify/cancel.png',
			'multi' : false,
			'auto' : true,
			'fileExt' : '*.jpg;*.gif;*.png',
			'fileDesc' : 'Image Files (.JPG, .GIF, .PNG)',
			'removeCompleted' : true,
			'folder' : '/content/uploads/<?php echo date('Y');?>/<?php echo date('m');?>',
			'queueID' : 'uploading',
			'buttonText': 'Browse',
			'onComplete':function(event, queueID, fileObj, response){
				if(fileObj.filePath !=''){
					$('#thumb_image').html('<img src="<?php get_siteinfo('url');?>/core/timthumb.php?src=<?php get_siteinfo('url');?>' + fileObj.filePath + '&w=<?php echo $thumb[0];?>&h=<?php echo $thumb[1];?>&zc=1" />').attr('href','<?php get_siteinfo('url');?>' + fileObj.filePath);
					$('#category_image_thumb').val('<?php get_siteinfo('url');?>/core/timthumb.php?src=<?php get_siteinfo('url');?>' + fileObj.filePath + '&w=<?php echo $thumb[0];?>&h=<?php echo $thumb[1];?>&zc=1');
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
		
		$('#fm').find('#file_upload').uploadify({
			'uploader' : '<?php get_siteinfo('url');?>/core/js/uploadify/uploadify.swf',
			'script' : '<?php get_siteinfo('url');?>/core/js/uploadify/uploadify.php',
			'cancelImg' : '<?php get_siteinfo('url');?>/core/js/uploadify/cancel.png',
			'multi' : true,
			'auto' : false,
			'fileExt' : '*.jpg;*.gif;*.png',
			'fileDesc' : 'Image Files (.JPG, .GIF, .PNG)',
			'removeCompleted' : true,
			'folder' : '/content/uploads/<?php echo date('Y');?>/<?php echo date('m');?>',
			'queueID' : 'uploading',
			'buttonText': 'Browse',
			'onComplete':function(event, queueID, fileObj, response){
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
<div id="fm" title="File Manager">
    <div id="tabs_fm" class="tabs">
        <ul>
            <li><a href="#tabs1_1">Upload</a></li>
            <li><a href="<?php get_siteinfo('url');?>/backend/ajax.php?action=uploaded_images">Gallery</a></li>
        </ul>
        <div id="tabs1_1">
            <div style="padding:10px;height:320px;">
                <div class="left">
                    <p style="padding:0 0 10px 0;margin:0 0 10px 0;border-bottom:1px dotted #AAAAAA;"><input id="file_upload" type="file" name="Filedata" /></p>
                    <p style="padding:0 0 5px 0;margin:0;"><input type="button" value="Upload Files" onclick="$('#file_upload').uploadifyUpload();" style="width:125px;" /></p>
                    <p style="padding:0 0 5px 0;margin:0;"><input type="button" value="Clear Queue" onclick="$('#file_upload').uploadifyClearQueue();" style="width:125px;" /></p>
                    <div style="padding:10px 0 0 0;margin:10px 0 0 0;border-top:1px dotted #AAAAAA;width:130px;">Click on <strong>Browse</strong> to upload images.</div>
                </div>
                <div id="uploading" class="right dirs" style="width:465px;height:320px;border:1px solid #E5E5E5;"><ul></ul></div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<form id="item-editor" method="post" action="<?php echo BACKEND_DIRECTORY;?>/components.php" enctype="multipart/form-data"> 
    <table class="editor" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td class="sidebar">
                <div class="box">
                    <div class="title">Category</div>
                    <div class="content">
						<?php if($_REQUEST['id'] > 0):?>
                        <p><input type="submit" class="button" value="Update" /></p>
                        <?php else:?>                        
                        <p><input type="submit" class="button" value="Add" /></p>                    
                        <?php endif;?>
                        <?php
						$back = CURRENT_PAGE.'?comp='.$_REQUEST['comp'].'&mod='.$_REQUEST['mod'].($category->cat_parent > 0 ? '&id='.$category->cat_parent : ($_REQUEST['parent'] > 0 ? '&id='.$_REQUEST['parent'] : ''));
                        ?>
                        <p><input type="button" class="button" value="Back" onclick="window.location='<?php echo $back;?>';" /></p>
                        <div class="option">
                            <p><label for="cat_status">Status</label>
                            <select name="cat_status" id="cat_status">
                                    <option value="active"<?php echo ($category->cat_status == 'active' ? ' selected="selected"' : '');?>>Active</option>
                                    <option value="in-active"<?php echo ($category->cat_status == 'in-active' ? ' selected="selected"' : '');?>>In-Active</option>
                            </select></p>
                            <p><label for="cat_parent">Category</label>
                            <?php $categories = get_categories(array('ID','title','parent')); ?>
                            <select name="cat_parent" id="cat_parent">
                                <option value="0">(un-categorized)</option>
                                <?php make_category_select_lists(5,0,($category->cat_parent > 0 ? $category->cat_parent : $_REQUEST['parent'])); ?>
                            </select></p>
                            <p><label class="left" for="cat_sort_order">Sort Order</label>
                               <input class="right" type="text" name="cat_sort_order" id="cat_sort_order" style="width:50px;" value="<?php echo $category->cat_sort_order;?>" />
                               <div class="clear"></div>
                            </p>
                        </div>
                    </div>
                </div>
            </td>
        	<td class="content">
				<?php load_documentations(); ?>
                <?php 
				if($category->cat_status == 'in-active'){
					_d('Product category status "In-Active"','<div class="messagebox messagebox-error">','</div>');
				}
				?>
                <?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>
                <div id="form-messagebox"></div>
                <div class="cat-title">
                    <input style="width:100%;" class="<?php echo ($category->cat_title!='' ? '' : 'slugger textfield-info');?> autocomplete-off required check-slug" type="text" name="cat_title" id="cat_title" value="<?php echo ($category->cat_title!=''?$category->cat_title:'Enter category name here');?>" alt="Enter category here" autocomplete="off" />
                </div>
                
                <p><span class="slug-caption">Slug: </span>
                    <span id="page-slug"><?php echo ($category->cat_slug !='' ? $category->cat_slug : 'Type on the title to generate slug');?></span>
                    <span id="edit-slug" class="<?php echo ($category->cat_slug!='' ? '' : 'hide');?>"> <input type="button" value="Edit" onclick="update_slug();"  /></span>
                    <?php if($category->ID > 0):?><span id="view-page"> <input type="button" value="View" onclick="window.open('<?php echo category_url($category->ID);?>');"  /></span><?php endif;?>
                <input type="hidden" name="cat_slug" id="slug" value="<?php echo $category->cat_slug;?>" /></p>
                
                
				<?php /*
                <p><label for="cat_title">Name</label>
                    <div class="cat-title">
                        <input class="autocomplete-off required check-slug" type="text" name="cat_title" id="cat_title" value="<?php echo $category->cat_title;?>" autocomplete="off" />
                    </div>                    
                </p>
                <p><label for="cat_slug" style="display:inline;">Slug: </label><input type="text" name="cat_slug" class="required" id="cat_slug" value="<?php echo $category->cat_slug;?>" /></p>
				*/ ?>
                <p><label for="cat_description">Description</label>
                <textarea name="cat_description" id="cat_description"><?php
				$content = $category->cat_description;
				$content = stripslashes($content);
				$content = html_entity_decode($content);
				echo  $content;
				?></textarea></p>
                
                <div id="tabs" class="tabs">
                    <ul>
                        <li><a href="#tabs1">General</a></li>
                        <li><a href="#tabs2">Meta Information</a></li>
                        <li><a href="#tabs3">Settings</a></li>
                        <li><a href="#tabs4">Media</a></li>
                    </ul>
                    <div id="tabs1" class="tab">
                        <label style="display:inline;"><input<?php echo ($meta->featured_category == 'yes' ? ' checked="checked"' : '');?> type="checkbox" name="cat_meta[featured_category]" value="yes" />&nbsp;Feature</label> <input type="text" name="cat_meta[featured_category_order]" value="<?php echo $meta->featured_category_order;?>" style="width:50px;" />
                        <label><input<?php echo ($meta->special == 'yes' ? ' checked="checked"' : '');?> type="checkbox" name="cat_meta[special]" value="yes" />&nbsp;Specials and Promos&nbsp;&nbsp;<input type="text" name="meta[special_date_end]" value="<?php echo ($meta->special == 'yes' ? $meta->special_date_end : '');?>" class="date text-center" readonly="readonly" style="width:100px;" /></label>
                        <label><input<?php echo ($meta->new == 'yes' ? ' checked="checked"' : '');?> type="checkbox" name="cat_meta[new]" value="yes" />&nbsp;New</label>
                        <label><input<?php echo ($meta->show_excerpt == 'yes' ? ' checked="checked"' : '');?> type="checkbox" name="cat_meta[show_excerpt]" value="yes" />&nbsp;Show description on category page</label>
                        <label for="cat_excerpt">Excerpt</label>
                        <textarea name="cat_excerpt" id="cat_excerpt"  style="height:100px;width:98%;"><?php echo $category->cat_excerpt;?></textarea>
                        <label style="padding-bottom:5px;">Tag</label>
                        <input type="text" name="cat_meta[tagline]" id="tagline" value="<?php echo $meta->tagline;?>" style="width:380px;" />&nbsp;<input type="text" name="cat_meta[tagline2]" id="tagline2" value="<?php echo $meta->tagline2;?>" style="width:380px;" />
                    </div>
                    <div id="tabs2" class="tab">
                        <table cellpadding="0" cellspacing="0" border="0" style="width:100%;margin-top:2px;">
                            <tr>
                                <td style="width:20%;padding-bottom:10px;vertical-align:top;"><label for="meta_description">Description</label></td>
                                <td style="width:80%;padding-bottom:10px;vertical-align:top;">
                                    <textarea style="height:100px;width:90%;" id="meta_description" name="cat_meta[meta_description]"><?php echo $meta->meta_description;?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:20%;padding-bottom:10px;vertical-align:top;"><label for="meta_keywords">Keyword(s)</label></td>
                                <td style="width:80%;padding-bottom:10px;vertical-align:top;">
                                        <textarea style="height:80px;width:90%;" id="meta_keywords" name="cat_meta[meta_keywords]"><?php echo $meta->meta_keywords;?></textarea>
                                        <p style="font-size:11px;font-style:italic;padding:0;">Type keywords, separated by a comma e.g. Keyword1, Keyword2</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="tabs3" class="tab">
                    	<?php
						$fp = (object) $meta->featured_page;
                        ?>
                        <table cellpadding="0" cellspacing="0" border="0" style="width:100%;margin-top:2px;">
                            <tr>
                                <td style="width:20%;padding-bottom:10px;vertical-align:top;"><label>Featured Page</label></td>
                                <td style="width:80%;padding-bottom:10px;vertical-align:top;">
                                    <label style="display:inline;"><input type="radio" name="cat_meta[featured_page][active]" value="featured_page_yes"<?php echo ($fp->active == 'featured_page_yes' ? ' checked="checked"' : '');?> /> Yes</label>
                                    <label style="display:inline;"><input type="radio" name="cat_meta[featured_page][active]" value="featured_page_no"<?php echo ($fp->active == 'featured_page_no' ? ' checked="checked"' : '');?> /> No</label>
                                    <label for="featured_page_order">Order</label>
                                    <input type="text" id="featured_page_order" name="cat_meta[featured_page][order]" value="<?php echo ($fp->order > 0 ? $fp->order : '0');?>" style="width:100px;" /><br/>
                                    <label for="featured_page_custom">Custom</label>
                                    <textarea id="featured_page_custom" name="cat_meta[featured_page][custom]" style="width:97%;height:100px;"><?php echo html_entity_decode(stripslashes($fp->custom));?></textarea>
                                    <label for="featured_page_image">Main Image</label>
                                    <input type="text" id="featured_page_image" name="cat_meta[featured_page][image]" value="<?php echo $fp->image;?>" style="width:97%;" /><br/>
                                    <p>Click <strong>Browse</strong> or <strong>Paste</strong> the link to add main featured image</p>
                                    <input id="featured_page_image_browse" type="file" name="Filedata" />
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="tabs4" class="tab">
                    	<p>Click on <strong>Browse</strong> to select your category main/thumbnail image.</p>
                        <div id="media" style="margin-top:5px;">
                            <div class="main-image">
                                <div class="img-cont" style="width:<?php echo $main[0];?>px;height:<?php echo $main[1];?>px;">
                                    <?php if($category->cat_image_main!=''):?>
                                        <a id="main_image" target="_blank" href="<?php echo $category->cat_image_main;?>">
                                            <img src="<?php echo $category->cat_image_main;?>"  style="width:<?php echo $main[0];?>px;height:<?php echo $main[1];?>px;" />
                                        </a>
                                    <?php else:?>
                                        <a id="main_image" target="_blank"></a>                                    
                                    <?php endif;?>
                                    <input type="hidden" id="category_image_main" name="cat_image_main" value="<?php echo $category->cat_image_main;?>" />
                                </div>
                                <div class="empty">
                                    <span style="font-weight:bold;">Main</span> (<?php echo $main[0];?> x <?php echo $main[1];?>)<br />                                        
                                    <input id="btn_category_image_main" type="file" name="Filedata" />
                                </div>
                            </div>
        
                            <div class="thumb-image">
                                <div class="img-cont" style="margin-left:15px;">
                                    <?php if($category->cat_image_thumb!=''):?>
                                        <a id="thumb_image" target="_blank" href="<?php echo $category->cat_image_thumb;?>">
                                            <img src="<?php echo $category->cat_image_thumb;?>"  style="width:<?php echo $thumb[0];?>px;height:<?php echo $thumb[1];?>px;" />
                                        </a>
                                    <?php else:?>
                                        <a id="thumb_image" target="_blank"></a>                                    
                                    <?php endif;?>	
                                    <input type="hidden" id="category_image_thumb" name="cat_image_thumb" value="<?php echo $category->cat_image_thumb;?>" />
                                </div>
                                <div class="empty">
                                    <span style="font-weight:bold;">Thumb</span> (<?php echo $thumb[0];?> x <?php echo $thumb[1];?>)<br />
                                    <input id="btn_category_image_thumb" type="file" name="Filedata" />
                                </div>	
                            </div>
                            <div class="clear"></div>
                        </div>						
                    </div>
				</div>
            </td>
        </tr>
    </table>
    <?php if($_REQUEST['id'] > 0):?>
    <input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>" />
    <?php endif;?>   
    
    <input type="hidden" name="action" value="save-category" />
    <input type="hidden" name="comp" value="<?php echo JSTORE_ID;?>" />
    <input type="hidden" name="mod" value="<?php echo $_REQUEST['mod'];?>" />
    <input type="hidden" name="opt" value="category-edit" />
</form>