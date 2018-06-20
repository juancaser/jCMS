<?php
if(!defined('JCMS')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access

enque_script('jquery-ui');
enque_style('jquery-ui-theme');
enque_script('uploadify');
enque_script('swfobject');
enque_style('uploadify-css');


$main = array('250','400');
$thumb = array('100','100');

$color_type = array(
					'product' => 'Product',
					'imprint' => 'Imprint'
				);

$price_category = array('regular' => 'Blank/Regular','custom' => 'Custom/Printed');

$userinfo = $_SESSION['__USER']['info'];


if($_REQUEST['id'] > 0){
	$item = get_product_info($_REQUEST['id']);
	$meta = trim($item->meta);	
	$meta = (object) unserialize($meta);
	/* Price */
	$p = (object) unserialize($item->item_price);
	$range = $p->range;
	
	$bulk_prices = $p->bulk;

}
$title = ($_REQUEST['id'] > 0 ? $item->item_name.' [Update]' : 'New Product');
?>
<style type="text/css">
/* jQuery UI Datepicker */
#ui-datepicker-div{display:none;}
.ui-datepicker th {
	font-size:1.2em;
}
#tabs{
	margin:10px 0 20px 0;
}
	.ui-state-default, .ui-widget-content .ui-state-default{
		/*font-weight: bold;*/
		text-align: center;
		font-size:12px;
	}
	#tabs ul.ui-tabs-nav{
		margin:0;
		list-style-type:none;
	}
	#tabs div.ui-tabs-panel{
		font-size:13px;
		padding:10px;
	}
	.ui-tabs .ui-tabs-nav li a {
		padding:5px 15px;
		text-decoration: none;
	}

#item_slug{
	border:0;
	background-color:#FFFFFF;
	font-style:italic;
}
</style>
<script type="text/javascript">
	var bulk_option = Array();
	<?php
	foreach($price_category as $key => $value){ ?>
		bulk_option['<?php echo $key;?>'] = '<?php echo htmlentities($value);?>';
	<?php }	?>
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
		$('#order_option').dialog({
			autoOpen: false,
			width: 230,
			height: 140,
			modal: true,
			resizable: false,
			buttons: {
				"Ok": function(){				
					var d = new Date();
					var rand_id = d.getMonth() + '_' + d.getDate() + '_' + d.getFullYear() + '_' + d.getHours() + '_' + d.getMinutes() + '_' + d.getSeconds();
					$('#cart_option').append('<span id="' + rand_id + '"><a href="javascript:ord_remove(\'' + rand_id + '\');"></a>' + $('#opt_label').val() + '<input type="hidden" name="meta[order_option][' + rand_id + '][id]" id="opt_' + $('#optid').val() + '_id" value="' + rand_id + '" /><input type="hidden" name="meta[order_option][' + rand_id + '][label]" id="opt_' + rand_id + '_label" value="' + $('#opt_label').val() + '" /></span>');
					$('#opt_label').val('');
					$(this).dialog("close");
				},
				"Close": function() { 
					$(this).dialog("close"); 
				} 
			}
		});
		
		$('#price_dlg').dialog({
			autoOpen: false,
			width: 337,
			height: 260,
			modal: true,
			resizable: false,
			buttons: {
				"Ok": function() { 					
					var d = new Date();
					var rand_id = d.getMonth() + '_' + d.getDate() + '_' + d.getFullYear() + '_' + d.getHours() + '_' + d.getMinutes() + '_' + d.getSeconds();
					
					var min_qty = $('#min_qty');
					var per_item = $('#per_item');
					var bulk_category = $('#bulk_category');
					var bulk_description = $('#bulk_description');
					
					if(min_qty.val() > 0){
						if($('#bpid').val()!=''){	
							var the_id = $('#bpid').val();
							$('#desc_' + the_id).html(bulk_description.val());
							$('#qty_' + the_id).html(min_qty.val());
							$('#price_' + the_id).html(per_item.val());
							$('#data_' + the_id).val(min_qty.val() + '|' + per_item.val() + '|' + bulk_category.val() + '|' + bulk_description.val());		
						}else{
							var the_id = bulk_category.val() + '_' + rand_id;
							$('#' + bulk_category.val()).find('ul').append('<li id="prc_' + the_id + '"><div class="quantity"><span id="desc_' + the_id + '">' + bulk_description.val() + '</span><div class="remedit"><a href="javascript:void(0);" onclick="edit_bulk_option(\'' + the_id + '\');" alt="' + bulk_category.val() + '">Edit</a> | <a href="javascript:void(0);" onclick="remove_bulk_option(\'' + the_id + '\');"  alt="' + bulk_category.val() + '">Remove</a></div><div class="clear"></div></div><div class="price">Minimum Quantity &rsaquo; <strong><span id="qty_' + the_id + '">' + min_qty.val() + '</span></strong> | Price per Item &rsaquo; <strong>$<span id="price_' + the_id + '">' + per_item.val() + '</span></strong></div><input type="hidden" id="data_' + the_id + '" name="item_price[bulk][]" value="' + min_qty.val() + '|' + per_item.val() + '|' + bulk_category.val() + '|' + bulk_description.val() + '" /></li>');
						}
					}
					$('#min_qty').val('');
					$('#per_item').val('');
					$('#bulk_category').val('');
					$('#bulk_description').val('');
					$('#bpid').val('');
					$('#ui-dialog-title-price_dlg').html('Add - Bulk Price Option');
					$(this).dialog("close");
				}, 
				"Close": function() { 
					$('#min_qty').val('');
					$('#per_item').val('');
					$('#bulk_category').val('');
					$('#bulk_description').val('');
					$('#bpid').val('');
					$('#ui-dialog-title-price_dlg').html('Add - Bulk Price Option');
					$(this).dialog("close"); 
				} 
			}
		});
		
		$('.add_order_option').click(function(){ 
			$('#order_option').dialog('open');
			return false;
		});

		$('.add_bulk').click(function(){			
			var this_href = $(this).attr('alt').replace(/#/g,'');
			$('#bulk_category_display').html(bulk_option[this_href]);
			$('#bulk_category').val(this_href);         
			$('#price_dlg').dialog('open');
			return false;
		});
		
		$('.autocomplete-off').attr('autocomplete','off');
		$('.required').click(function(){
			$('#form-messagebox').html('').removeClass('messagebox messagebox-error').css('display','none');
		});
		
		/*$('#add-items').click(function(){
			var id = Math.floor(Math.random()*101)			
			$('.first').clone().appendTo('#product-items').removeClass('first').attr('id','item-'+ id);
			$('#item-'+ id).find('input[type=text]').each(function(){
				$(this).val('');
				var _name = $(this).attr('name');
				var _name = _name.replace(/[0]/gi,id);
				$(this).attr('name',_name);
			});
			
			$('#item-'+ id).find('.col1').append('<input class="chk" type="checkbox" value="' + id + '"/>');			
			$('#item-'+ id).find('.date').attr('id','dp' + id).removeClass('hasDatepicker').val('<?php echo date('Y-m-d');?>');
			$('#dp' + id).datepicker({inline: true,dateFormat: 'yy-mm-dd'});
		});
		
		$('#remove-items').click(function(){
			$('#product-items').find('.chk:checked').each(function(){				
				$('#product-items').find('#item-' + $(this).val()).remove();
			});
			$('.check_all').attr('checked','');
		});
		
		$('.image-gallery').find('a').click(function(){			
			$('.image-gallery').find('a.main-image').removeClass('main-image');
			$(this).addClass('main-image');
			$('#item_product_image').val($(this).attr('href'));
			return false;
		});*/
			
		
		
        $('#item_description').tinymce({
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
            theme_advanced_resizing : false ,
			convert_urls : false,
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
		
		/*var maskHeight = $(document).height();
		var maskWidth = $(window).width();	
		
		var co = '';
		$('#color_picker').find('a').click(function(){
			var cp = $(this);
			if(cp.hasClass('selected')){
				cp.removeClass('selected');
				$('a#selected' + cp.attr('id')).remove();
			}else{
				cp.addClass('selected');
				$('#color').prepend('<a href="javascript:void(0);" title="' + cp.attr('title') + '" style="background-color:' + cp.css('background-color') + ';" id="selected' + cp.attr('id') + '" class="selected_color"></a>');
			}
			var co1 = '';
			var co2 = '';
			$('#color_picker').find('a').each(function(){
				if($(this).hasClass('selected')){
					co2 = co2 + (co2!='' ? ',' : '') + rgb2hex($(this).css('background-color'));					
				}
			});
			$('#item_color').val(co2);
		});
		
		$('#clear_color').click(function(){
			if($(this).hasClass('locked') == false){
				$('#color_picker').find('a').removeClass('selected');
				$('#item_color').val('');
				$('#color').html('<div style="clear: both;"></div>');
			}
		});*/	
		
		$('#item_product_image').uploadify({
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
					<?php if($meta->crop_image == 'yes'):?>
						$('#item_product_image_display').html('<img src="<?php get_siteinfo('url');?>/core/timthumb.php?src=<?php get_siteinfo('url');?>' +fileObj.filePath + '&w=<?php echo $main[0];?>&h=<?php echo $main[0];?>&zc=1" />').attr('href','<?php get_siteinfo('url');?>' + fileObj.filePath);
						$('#item_product_image2').val('<?php get_siteinfo('url');?>/core/timthumb.php?src=<?php get_siteinfo('url');?>' +fileObj.filePath + '&w=<?php echo $main[0];?>&h=<?php echo $main[0];?>&zc=1');
					<?php else:?>
						$('#item_product_image_display').html('<img src="<?php get_siteinfo('url');?>' +fileObj.filePath + '" style="width:<?php echo $main[0];?>px;height:<?php echo $main[1];?>px;" />').attr('href','<?php get_siteinfo('url');?>' + fileObj.filePath);
						$('#item_product_image2').val('<?php get_siteinfo('url');?>' + fileObj.filePath);
					<?php endif;?>					
					$('#media_main').val('<?php get_siteinfo('url');?>' + fileObj.filePath);					
				}
			}
		});		
		$('#item_product_image_thumb').uploadify({
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
					<?php if($meta->crop_image == 'yes'):?>
						$('#item_product_image_thumb_display').html('<img src="<?php get_siteinfo('url');?>/core/timthumb.php?src=<?php get_siteinfo('url');?>' + fileObj.filePath + '&w=<?php echo $thumb[0];?>&h=<?php echo $thumb[0];?>&zc=1" />').attr('href','<?php get_siteinfo('url');?>' + fileObj.filePath);					
						$('#item_product_image_thumb2').val('<?php get_siteinfo('url');?>/core/timthumb.php?src=<?php get_siteinfo('url');?>' +fileObj.filePath + '&w=<?php echo $thumb[0];?>&h=<?php echo $thumb[0];?>&zc=1');
					<?php else:?>
						$('#item_product_image_thumb_display').html('<img src="<?php get_siteinfo('url');?>' +fileObj.filePath + '" style="width:<?php echo $thumb[0];?>px;height:<?php echo $thumb[1];?>px;" />').attr('href','<?php get_siteinfo('url');?>' + fileObj.filePath);					
						$('#item_product_image_thumb2').val('<?php get_siteinfo('url');?>' + fileObj.filePath);
					<?php endif;?>					
					$('#media_thumb').val('<?php get_siteinfo('url');?>' + fileObj.filePath);					
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
		
		$('#add_color').click(function(){
			var d = new Date();
			var rand_id = d.getMonth() + '_' + d.getDate() + '_' + d.getFullYear() + '_' + d.getHours() + '_' + d.getMinutes() + '_' + d.getSeconds();
			
			$('#color_option').find('tr:last').clone(true).appendTo('#color_option').css({backgroundColor:'#FFFFAA'}).animate({backgroundColor: "#FFFFFF"},'fast').attr('id','color' + rand_id);
			$('#color_option').find('tr:last').find('.field').val('');
			$('#color_option').find('tr:last').find('td:first').html('<input type="checkbox" class="chk" value="' + rand_id + '" />');
			$('#color_option').find('tr:last').find('.hex').attr('name','meta[color][option][' + rand_id + '][hex]');
			$('#color_option').find('tr:last').find('.type').attr('name','meta[color][option][' + rand_id + '][type]');
			$('#color_option').find('tr:last').find('.label').attr('name','meta[color][option][' + rand_id + '][label]');
			
			/*$('#color_option').find('tr:last').find('.description').attr('name','"meta[product_color][color][' + rand_id + '][description]').removeAttr('readonly');*/
		});
		
		$('#remove_color').click(function(){
			$('#color_option').find('.chk:checked').each(function(){
				var _this = $(this);
				$('#color' + _this.val()).css({backgroundColor:'#FFFFAA'}).fadeOut('fast', function(){
					$('#color' + _this.val()).remove();
				});
			});
		});
		

		$('#add_print_location').click(function(){
			var d = new Date();
			var rand_id = d.getMonth() + '_' + d.getDate() + '_' + d.getFullYear() + '_' + d.getHours() + '_' + d.getMinutes() + '_' + d.getSeconds();
			
			$('#print_option').find('tr:last').clone(true).appendTo('#print_option').css({backgroundColor:'#FFFFAA'}).animate({backgroundColor: "#FFFFFF"},'fast').attr('id','loc' + rand_id);
			$('#print_option').find('tr:last').find('.field').val('');
			$('#print_option').find('tr:last').find('td:first').html('<input type="checkbox" class="chk" value="' + rand_id + '" />');
			$('#print_option').find('tr:last').find('.label').attr('name','meta[print_location][' + rand_id + '][label]');
			$('#print_option').find('tr:last').find('.fee').attr('name','meta[print_location][' + rand_id + '][fee]');
		});
		
		$('#remove_print_location').click(function(){
			$('#print_option').find('.chk:checked').each(function(){
				var _this = $(this);
				$('#loc' + _this.val()).css({backgroundColor:'#FFFFAA'}).fadeOut('fast', function(){
					$('#loc' + _this.val()).remove();
				});
			});
		});
		
    });
	/*function show_gallery_box(){
		$('#filemanager').css('display','block');
	}*/
	function ord_remove(id){
		$('#' + id).fadeOut('slow',function(){remove()});
	}
	/*function insert_img_topost(src){
		$('#item_product_image').val(src);
		$('#item_product_image_display').attr('href',src);
		$('#item_product_image_display > img').attr('src','<?php get_siteinfo('url');?>/core/timthumb.php?src=' + src + '&h=100&w=100&zc=1');
		<?php /*if($category->cat_image==''):?>
			$('#item_product_image_display').removeClass('hide');
		<?php endif;*/?>
		$('#filemanager').css('display','none');
	}*/
	
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
	
	function remove_bulk_option(id){
		$('#prc_' + id).fadeOut('slow',function(){
			$(this).remove();
		});
	}
	function edit_bulk_option(id){
		var data = $('#data_' + id).val();
		var data = data.split('|');
		$('#ui-dialog-title-price_dlg').html('Edit - Bulk Price Option');
		$('#bpid').val(id);
		$('#min_qty').val(data[0]);
		$('#per_item').val(data[1]);;
		$('#bulk_category').val(data[2]);
		$('#bulk_category_display').html(bulk_option[data[2]]);		
		$('#bulk_description').val(data[3]);
		$('#price_dlg').dialog('open');
		return false;
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
                    <div class="title">Product</div>
                    <div class="content">
						<?php if($_REQUEST['id'] > 0):?>
                        <p><input type="submit" class="button" value="Update" /></p>
                        <?php else:?>                        
                        <p><input type="submit" class="button" value="Add" /></p>                    
                        <?php endif;?>
                        <p><input type="button" class="button" value="Back" onclick="window.location='<?php echo CURRENT_PAGE;?>?comp=<?php echo $_REQUEST['comp'];?>&mod=<?php echo $_REQUEST['mod'];?><?php echo ($item->item_category > 0 ? '&id='.$item->item_category : ($_REQUEST['parent'] > 0 ? '&id='.$_REQUEST['parent'] : ''));?>';" /></p>
                        <div class="option">
                            <p><label for="item_status">Status</label>
                            <select name="item_status" id="item_status">
                                    <option value="active"<?php echo ($item->item_status == 'active' ? ' selected="selected"' : '');?>>Active</option>
                                    <option value="in-active"<?php echo ($item->item_status == 'in-active' ? ' selected="selected"' : '');?>>In-Active</option>
                            </select></p>
                            <p><label for="item_category">Category</label>
                            <?php $categories = get_categories(array('ID','title','parent')); ?>
                            <select name="item_category" id="item_category">
                                <option value="0">(un-categorized)</option>
                                <?php make_category_select_lists(5,0,($item->item_category > 0 ? $item->item_category : $_REQUEST['parent'])); ?>
                            </select></p>
                            <p><label for="item_product_number">Product No.</label>
                            <input class="" type="text" name="item_product_number" id="item_product_number" value="<?php echo $item->item_product_number;?>" style="width:145px;" />
                            </p>
                            <p><label class="left" for="item_sort_order">Sort Order</label>
                               <input class="right" type="text" name="item_sort_order" id="item_sort_order" style="width:50px;" value="<?php echo $item->item_sort_order;?>" />
                               <div class="clear"></div>
                            </p>
                        </div>
                    </div>
                </div>
            </td>
        	<td class="content">
				<?php load_documentations(); ?>
                <?php 
				if($item->item_status == 'in-active'){
					_d('Item status "In-Active"','<div class="messagebox messagebox-error">','</div>');
				}
				?>
                <?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>
                <div id="form-messagebox"></div>
                <div class="item-title">
                    <input style="width:100%;" class="<?php echo ($item->item_slug!='' ? '' : 'slugger');?> autocomplete-off required check-slug textfield-info" type="text" name="item_name" id="item_name" value="<?php echo ($item->item_name!=''?$item->item_name:'Enter product name here');?>" alt="Enter product name here" autocomplete="off" />
                </div>                    
                
                <p><span class="slug-caption">Slug: </span>
                    <span id="page-slug"><?php echo ($item->item_slug !='' ? $item->item_slug : 'Type on the title to generate slug');?></span>
                    <span id="edit-slug" class="<?php echo ($item->item_slug!='' ? '' : 'hide');?>"> <input type="button" value="Edit" onclick="update_slug();"  /></span>
                    <?php if($item->ID > 0):?><span id="view-page"> <input type="button" value="View" onclick="window.open('<?php echo item_url($item->ID);?>');"  /></span><?php endif;?>
                <input type="hidden" name="item_slug" id="slug" value="<?php echo $item->item_slug;?>" /></p>
                
                <p>
                	<label for="item_description">Description</label>
                	<textarea name="item_description" id="item_description" style="width:100%;"><?php echo stripslashes(html_entity_decode($item->item_description));?></textarea>
				</p>
                
                
                
                
                
                <div id="tabs">
                    <ul>
                        <li><a href="#tabs1">General</a></li>
                        <li><a href="#tabs2">Meta Information</a></li>
                        <li><a href="#tabs3">Settings</a></li>
                        <li><a href="#tabs4">Shopping Cart</a></li>
                        <li><a href="#tabs5">Media</a></li>
                    </ul>
                    <div id="tabs1">
                        <label style="font-weight:normal;"><input<?php echo ($meta->featured_product == 'yes' ? ' checked="checked"' : '');?> type="checkbox" name="meta[featured_product]" value="yes" />&nbsp;Feature</label>
                        <label style="font-weight:normal;"><input<?php echo ($meta->bestseller == 'yes' ? ' checked="checked"' : '');?> type="checkbox" name="meta[bestseller]" value="yes" />&nbsp;Best Seller</label>
                        <label style="font-weight:normal;"><input<?php echo ($meta->special == 'yes' ? ' checked="checked"' : '');?> type="checkbox" name="meta[special]" value="yes" />&nbsp;Specials and Promos&nbsp;&nbsp;<input type="text" name="meta[special_date_end]" value="<?php echo ($meta->special == 'yes' ? $meta->special_date_end : '');?>" class="date text-center" readonly="readonly" style="width:100px;" /></label>
                    </div>
                    <div id="tabs2">
                    	<?php include('inc/edit-meta-option.php');?>
                    </div>
                    <div id="tabs3">
                    	<?php include('inc/edit-settings-option.php');?>
                    </div>
                    <div id="tabs4">
                        <div id="order_option" class="dialog" title="Add - Order Option">
                            <label  style="display:block;padding-bottom:5px;" for="opt_label">Option Label</label>
                            <input type="text" id="opt_label" />
                            <input type="hidden" id="optid" value="<?php echo strtoupper(md5(strtotime('now')));?>" />
                        </div>                
                    	<div class="box2" style="margin-bottom:10px;">
                        	<div class="header">
                            	<span class="title" style="font-size:12px;">Price</span>
                                <div class="minmax" title="Click to toggle"></div>
                                <div class="clear"></div>
                            </div>
                            <div class="content">
								<?php include('inc/edit-price-option.php');?>
                            </div>
                        </div>
                        
                    	<div class="box2" style="margin-bottom:10px;">
                        	<div class="header">
                            	<span class="title" style="font-size:12px;">Color</span>
                                <div class="minmax" title="Click to toggle"></div>
                                <div class="clear"></div>
                            </div>
                            <div class="content hide">
								<?php include('inc/edit-color-option.php');?>
                            </div>
                        </div>
                    	<div class="box2">
                        	<div class="header">
                            	<span class="title" style="font-size:12px;">Print</span>
                                <div class="minmax" title="Click to toggle"></div>
                                <div class="clear"></div>
                            </div>
                            <div class="content hide">
								<?php include('inc/edit-print-option.php');?>
                            </div>
                        </div>
                        
                    </div>
                    <div id="tabs5">
						<?php include('inc/edit-media-option.php');?>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <?php if($_REQUEST['id'] > 0):?>
    <input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>" />
    <?php endif;?>    
    <input type="hidden" name="action" value="save" />
    <input type="hidden" name="form" value="components" />
    <input type="hidden" name="comp" value="<?php echo JSTORE_ID;?>" />
    <input type="hidden" name="mod" value="<?php echo $_REQUEST['mod'];?>" />
    <input type="hidden" name="opt" value="edit" />
</form>