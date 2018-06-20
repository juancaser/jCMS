$(document).ready(function(){
	/* Modal Window */
	//select all the a tag with name equal to modal
	$('.modal').click(function(e) {
		//Cancel the link behavior
		e.preventDefault();

		if($(this).attr('type') == 'button'){
			var id = $(this).attr('rel');
		}else{
			var id = $(this).attr('href');
		}
		
	
		//Get the screen height and width
		var maskHeight = $(document).height();
		var maskWidth = $(window).width();
	
		//Set height and width to mask to fill up the whole screen
		$('#mask').css({'width':maskWidth,'height':maskHeight});
		
		//transition effect		
		$('#mask').fadeIn(1000);	
		$('#mask').fadeTo("slow",0.6);	
	
		//Get the window height and width
		var winH = $(window).height();
		var winW = $(window).width();
              
		//Set the popup window to center
		$('#' + id).css('top',  winH/2-$(id).height()/2);
		$('#' + id).css('left', winW/2-$(id).width()/2);
	
		//transition effect
		$('#' + id).fadeIn(2000); 
	
	});
	
	//if close button is clicked
	$('.window .close').click(function (e) {
		//Cancel the link behavior
		e.preventDefault();
		$('#mask, .window').hide();
	});	

	//if mask is clicked
	$('#mask').click(function () {
		$(this).hide();
		$('.window').hide();
	});
						   
						   
	/* Button Hover */
	$('input[type=button],input[type=submit],input[type=reset],button,submit,reset').hover(
		function(){	
			$(this).css('border-color','#666666');
		},function(){
			$(this).css('border-color','#BBBBBB');
		}
	);

	/* Checkbox*/
	$('.chk').click(function(){
		$('.check_all').removeAttr('checked');
		if($(this).attr('checked')){
			$('.action').removeAttr('disabled');
		}else{
			$('.action').attr('disabled','disabled');			
		}
	});		
	$('.check_all').click(function(){
		$this = $(this);
		$('.check_all').attr('checked',$this.attr('checked'));
		$('.chk').each(function(){
			$(this).attr('checked',$this.attr('checked'));
		});
		if($this.attr('checked')){
			$('.action').removeAttr('disabled');
		}else{
			$('.action').attr('disabled','disabled');			
		}		
	});

						   
	/* Top Navigaiton */
	$('ul.dd-menu > li').hover(
		function(){
			$(this).addClass('over');
		}, function () {
			$(this).removeClass('over');
		}
	);
	
	/* Textfield Info */
	if($('.textfield-info').val() == $('.textfield-info').attr('defaultValue')){
		$('.textfield-info').css('color','#CCCCCC');
	}
	$('.textfield-info').focus(function(){
		if($(this).val() == $('.textfield-info').attr('defaultValue')){
			$(this).css('color','#555555').val('');
		}
	});
	$('.textfield-info').blur(function(){	
		if($(this).val() == '' || $(this).val() == $(this).attr('defaultValue')){
			$(this).css('color','#CCCCCC').val($(this).attr('defaultValue'));
		}
	});
	
	
	/* Trash */
	var _restored_count = 0;
	$('#trashed-items').find('a.item').click(function(){
		if($(this).hasClass('selected') ==  true){
			$(this).removeClass('selected');
			$(this).find('.item_id').removeAttr('name');
		}else{
			$(this).addClass('selected');
			$(this).find('.item_id').attr('name','restore[]');
		}
	});
	
	$('#trash-form').submit(function(){		
		var numItems = $(this).find('a.selected').length;
		if(numItems > 0){
			return true;
		}else{
			return false;
		}
	});
	/* Page Viewer*/
	$('#page-viewer').find('.check_all').click(function(){
		var numItems = $('#page-viewer').find('.chk:checked').length;
		if(numItems > 0){
			$('#trashed_button').removeClass('hide');
		}else{
			$('#trashed_button').addClass('hide');
		}
	});
	
	$('#page-viewer').find(' .chk').click(function(){
		var numItems = $('#page-viewer').find('.chk:checked').length;
		if(numItems > 0){
			$('#trashed_button').removeClass('hide');
		}else{
			$('#trashed_button').addClass('hide');
		}
	});
	$('#page-viewer').submit(function(){		
		var numItems = $(this).find('.chk:checked').length;
		if(numItems > 0){
			return true;
		}else{
			return false;
		}
	});
	
	
	/* Paginated Lists*/
	$('.paginated-lists').find('.list').hover(function(){
		$(this).addClass('hover');
	},function(){
		$(this).removeClass('hover');
	});
	
	/* Form Validation */
	$('form').submit(function(){
		$this = $('form');
		var required_fields = $this.find('.required').length;
		var error_fields = $this.find('.error').length;
		var ajax_processing = $this.find('.ajax-processing').length;
		/*
		console.log('Required: ' + required_fields);
		console.log('Error: ' + error_fields);
		console.log('Ajax Process: ' + ajax_processing);
		*/
		if(error_fields > 0) {
			if($this.find('#form-messagebox').length > 0){
				$('#form-messagebox').html('<p>Validation error, please check if all required fields are filled</p>').addClass('messagebox messagebox-error').css('display','block');
			}
			return false;
		}else if(ajax_processing > 0) {
			return false;
		}else{
			if(required_fields > 0){
				// Do Validation
				if($this.find('#form-messagebox').length > 0){
					$('#form-messagebox').html('').removeClass('messagebox messagebox-error').css('display','none');
				}
				$this.find('.required').each(function(){
					if($(this).val() == '' || $(this).html()){
						$(this).addClass('error');
					}
				});
				var error_fields = $this.find('.error').length;
				if(error_fields > 0){
					if($this.find('#form-messagebox').length > 0){
						$('#form-messagebox').html('<p>Validation error, please check if all required fields are filled</p>').addClass('messagebox messagebox-error').css('display','block');
					}
					return false;
				}else{					
					return true;
				}			
			}else{
				// No required fileds, just submit it
					return true;
			}
		}
	});
	
	/* Page Editor*/
	$('#add_custom_fields').click(function(){
		var id = Math.floor(Math.random()*101);
		$('#custom_fields').append('<table id="' + id + '" cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td class="col1"><input type="text" name="custom_fields_data[field' + id + '][key]" /></td><td class="col2"><textarea name="custom_fields_data[field' + id + '][value]"></textarea><input type="button" class="button" value="Remove" onclick="remove_custom_field(\'' + id +'\');" /></td></tr></table>');
	});	

	$('.slugger').change(function(){
		if($(this).val() == $(this).attr('alt')){
			/* Do nothing */
		}else{
			if($(this).val()!=''){
				var dta = $(this).val();
				dta = dta.replace(/&/gi, 'and');
				$.ajax({
					type: 'POST',
					url: backend_directory + '/ajax.php',
					data: 'action=make_slug&data=' + dta,
					beforeSend: function(data){					
					},success: function(data){										
						$('#edit-slug').removeClass('hide');
						$('#page-slug').html(data);
						$('#slug').val(data);
					}
				});
			}else{
				$('#edit-slug').addClass('hide');
				$('#page-slug').html('');
				$('#slug').val('');
			}
		}
	});
	
	$('.box2').find('.header').click(function(){
	/*$('.box2').find('.minmax').click(function(){*/
		var this_box = $(this).parents('div.box2');
		if(this_box.hasClass('box2_closed') == true){
			this_box.find('.content').slideDown(1000,function(){
				this_box.removeClass('box2_closed');
			});			
		}else{
			this_box.find('.content').slideUp(1000,function(){
				this_box.addClass('box2_closed');
			});			
		}
	});


});
	
function change_album(_this){
	$.ajax({
		type: 'POST',
		url: backend_directory + '/ajax.php',
		data: 'action=uploaded_images&dir=' + _this.value,
		beforeSend: function(data){				
			$('#gallery-images').html('<div style="text-align:center;padding-top:50px;"><img src="' + backend_directory + '/images/ajax-loader2.gif" /></div>');
		},success: function(data){	
			$('#gallery-images').html(data);
		}
	});
}

function update_slug(){
	var new_slug = prompt('Type to update slug',$('#slug').val());
	if(new_slug != null && new_slug !='' && new_slug != $('#slug').attr('defaultValue')){
		$.ajax({
			type: 'POST',
			url: backend_directory + '/ajax.php',
			data: 'action=make_slug&data=' + new_slug,
			beforeSend: function(data){
			},success: function(data){					
				$('#edit-slug').removeClass('hide');
				$('#page-slug').html(data);
				$('#slug').val(data);
			}
		});
		$('#page-slug').val(new_slug);
	}	
}
function do_random_num(){
	return Math.floor(Math.random()*101)
}

function remove_custom_field(id){
	$('#' + id).remove();
}

var hexDigits = new Array ("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"); 
function rgb2hex(rgb) {
	rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
	return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}
function hex(x) {
	return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
}

(function($) {
	$.fn.currencyFormat = function() {
		this.each( function( i ) {
			$(this).change( function( e ){
				if( isNaN( parseFloat( this.value ) ) ) return;
				this.value = parseFloat(this.value).toFixed(2);
			});
		});
		return this; //for chaining
	}
})( jQuery );
