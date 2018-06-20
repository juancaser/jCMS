/* Shopping Cart */
$(document).ready(function(){
	$('#calc_quantity').keypress(function(e){
		if((e.which == 8 || e.which == 0) || (48 <= e.which && e.which <= 57)) {
        	var c = String.fromCharCode(e.which);			
			if($(this).val() == 0){
				return false;
			}
		}else{
			return false;
		}
	}).blur(function(){
		if($(this).val() == ''){
			$(this).val('1');
		}
	});
	$('#calculate').click(function(){
		$('.field_estimate').blur();
		$('.field_option ').blur();
	});

	$('.field_option').change(function(){
		/*product_quantity_current*/
		if($('#incart').val() == 'yes' && $('#' + $(this).attr('id') + '_current').val() !=''){
			if($(this).val() != $('#' + $(this).attr('id') + '_current').val()){
				$('#product_msgbox').css('display','none');
				$('#addtocart_btn').val('Add to Cart');
				$('#newproduct').val('yes');
				$('#calc_quantity').val('1');				
			}else{
				$('#product_msgbox').css('display','block');
				$('#addtocart_btn').val('Update Cart');
				$('#newproduct').val('no');
				$('#calc_quantity').val($('#product_quantity_current').val('1'));
			}
		}
	});
	$('.field_estimate').blur(function(){
		var field = $('.field_estimate');
		var param = '';
		$('.field_estimate').each(function(){
			param+='&' + $(this).attr('name') + '=' + $(this).val();
		});
		$.ajax({
			type: 'POST',
			url: template_directory + '/ajax.php',
			data: 'action=product_estimate' + param,
			success: function(msg){
				var obj = $.parseJSON(msg);
				if(obj.status == 'OK'){
					$('#res_quantity').html(obj.quantity);
					if(obj.price!=''){
						$('#res_quantity2').html('$' + obj.price);
					}
					if(obj.total!=''){
						$('#total_amount').html('$' + obj.total);
					}
				}				
				/*$('#res_quantity2').html('$' + msg);
				$('#calc_price').val(msg);
				$('#res_quantity').html(qty);
				$('#calc_price').val(msg);
				total_amount*/
			}
		});	
		/*var qty = $(this).val();
		if(qty == ''){
			$(this).val($(this).attr('defaultValue'));
		}else{
			$.ajax({
				type: 'POST',
				url: template_directory + '/ajax.php',
				data: 'action=cart_estimate&price=' + $('#item_price').val() + '&qty=' + qty,
				success: function(msg){
					$('#res_quantity2').html('$' + msg);
					$('#calc_price').val(msg);
					$('#res_quantity').html(qty);
					$('#calc_price').val(msg);
					total_amount
				}
			});	
		}*/	
	});
	
});
function add_to_cart(id,item_id){
	$('body').append('<div id="add_to_cart_box"></div>');
	$cart_box = $('#add_to_cart_box');	
	$cart_box.html('<div class="window"><div class="title">Add to Shopping Cart</div><div id="add_to_cart_box_content"></div></div>');
	$.ajax({
		type: 'POST',
		url: template_directory + '/ajax.php',
		data: 'action=product_info&id=' + id + '&item_id=' + item_id,
		success: function(msg){
			$('#add_to_cart_box_content').html(msg);
		}
	});
	

	/*$cart_box.html('<div class="modal-container"><div class="modal-title">'+ (cart_title !='' ? cart_title : 'Add to Cart / Checkout') +'</div><div class="modal-content" id="' + cart_id + '_content"><img src="' + site_url + '/template/images/ajax-loader.gif"></div></div>');
	$cart_box.css({'width':(width > 0 ? width + 'px' : '500px'),'height':(height > 0 ? height + 'px' : '500px'),'padding':'10px'});*/
	
}

function update_cart(id){
	var quantity = $('#quantity_' + id).val();
	var price = $('#price_' + id).val();
	$.ajax({
		type: 'POST',
		url: template_directory + '/ajax.php',
		data: 'action=update_cart&id=' + id + '&type=product&quantity=' + quantity + '&price=' + price,
		success: function(msg){
			var obj = $.parseJSON(msg);
			$('#amount_' + id).html(obj.amount);
			$('#total_amount').html(obj.total);
		}
	});
}
var cart = {
	estimate:function(frmid){
		var frm = $('#' + frmid);
	}
};

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
