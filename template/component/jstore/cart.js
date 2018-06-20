$(document).ready(function(){
	var ajaxing = false;
	
	$('.bulk_price').click(function(){
		$('#quantity').val($(this).attr('href').replace(/#/g,''));
		$('#quantity').focus();
	});
	
	$('#calculator_form').submit(function(){
		if($('#total').val() != '0.00'){
			return true;
		}else{
			return false;
		}
	});
	$('.cart_radio').click(function(){
		if($(this).attr('alt') == 'custom'){
			if($('#cart_quote').hasClass('hide') == false){
				$('#cart_option').removeClass('hide');
				$('#cart_quote').slideUp('slow',function(){
					$(this).addClass('hide');
					$('#cart_fields').slideDown('slow',function(){$(this).removeClass('hide');});
				});				
			}else{
				$('#cart_option').slideDown('slow',function(){$(this).removeClass('hide');});
			}			
		}else{
			$('#cart_option').slideUp('slow',function(){$(this).addClass('hide');});
		}
		if(ajaxing == false){
			$('.cart_radio').removeClass('cart_radio_selected');
			$(this).addClass('cart_radio_selected');		
			$('#order_type').val($(this).attr('alt'));
			if($('#button_add').hasClass('hide') == false){
				$('#button_add').fadeOut('slow',function(){
					$('#button_add').addClass('hide');
				});
			}
			if($('#button_calculate').hasClass('hide')){
				$('#button_calculate').fadeIn('slow',function(){
					$(this).removeClass('hide');
				});
			}
		}
	});
	
	$('#quantity').focus(function(){
		if(ajaxing == false){
			if($('#order_type').attr('alt') == 'custom'){
				if($('#cart_quote').hasClass('hide') == false){
					$('#cart_option').removeClass('hide');
					$('#cart_quote').slideUp('slow',function(){
						$(this).addClass('hide');
						$('#cart_fields').slideDown('slow',function(){$(this).removeClass('hide');});
					});				
				}else{
					$('#cart_option').slideDown('slow',function(){$(this).removeClass('hide');});
				}			
			}else{
				$('#cart_option').slideUp('slow',function(){$(this).addClass('hide');});
			}
			
			if($('#button_add').hasClass('hide') == false){
				$('#button_add').fadeOut('slow',function(){
					$('#button_add').addClass('hide');
				});
			}
			if($('#button_calculate').hasClass('hide')){
				$('#button_calculate').fadeIn('slow',function(){
					$(this).removeClass('hide');
				});
			}
		}		
	});
	
	$('#button_calculate').click(function(){
		
						var qty = $('#quantity').val();
						var cart_parent = $('#cart_fields');
						
						if(qty > 0){		
							var param = '';
							$('#calculator_form').find('.field').filter(function(){if($(this).val()!=''){param+='&'+$(this).attr('name')+'='+$(this).val();return;}});
							$.ajax({
								type: 'POST',
								url: template_directory + '/component/jstore/ajax.php',
								data: 'action=estimate' + param,	
								beforeSend: function(jqXHR, settings){
									ajaxing = true;
									$('#button_calculate').fadeOut('slow',function(){
										$(this).addClass('hide');
									});
								},
								success: function(data){
									var cart = $.parseJSON(data);
									if($('#cart_fields').hasClass('hide')){
										$('#cart_quote').slideUp('slow',function(){
											$(this).addClass('hide');
											$('#cart_fields').slideDown('slow',function(){
												$(this).removeClass('hide');
											});
										});
									}
									if(cart.status == 0){ /* Invalid */
										$(this).removeClass('hide');
										$('#cart_subtotal').html('0.00');
										$('#cart_unitprice').html('0.00');
										$('#total').val('0.00');
										$('#unitprice').val('0.00');
										$('#button_calculate').fadeIn('slow',function(){
											$(this).removeClass('hide');
										});
									}else if(cart.status == 1){ /* Has price */	
										if(cart.total > '0.00'){
											if($('#order_type').val() == 'custom'){
												$('#cart_option').slideDown('slow',function(){$(this).removeClass('hide');});
											}else{
												$('#cart_option').slideUp('slow',function(){$(this).addClass('hide');});
											}
											$('#total').val(cart.total);
											$('#unitprice').val(cart.price);
											$('#cart_subtotal').html(cart.total);
											$('#cart_unitprice').html(cart.price);
											$('#button_add').fadeIn('slow',function(){
												$(this).removeClass('hide');
												if($('#cart_quote').hasClass('hide') == false){
													$('#cart_quote').addClass('hide');
												}
											});
										}else{
											$('#cart_subtotal').html('0.00');
											$('#cart_unitprice').html('0.00');
											$('#total').val('0.00');
											$('#unitprice').val('0.00');						
										}
									}else if(cart.status == 2){ /* Quote */
										$('#cart_fields').slideUp('slow',function(){
											$(this).addClass('hide');
											$('#cart_quote').slideDown('slow',function(){
												$(this).removeClass('hide');
											});							
										});
									}
									ajaxing = false;
								}
							});
						}else{
							$('#cart_option').slideUp('slow',function(){
								$(this).addClass('hide');
								cart_parent.find('.addtocart').fadeOut('slow',function(){
									$(this).addClass('hide');
									$('#cart_subtotal').html('0.00');					
									$('#cart_unitprice').html('0.00');
									$('#total').val('0.00');
									$('#unitprice').val('0.00');
								});
							});
						}						
		
	});	
});
function quantity_key(){
	if($('#button_add').hasClass('hide') == false){
		$('#button_add').fadeOut('slow',function(){
			$(this).addClass('hide');
		});
	}
	if($('#button_calculate').hasClass('hide')){
		$('#button_calculate').fadeIn('slow',function(){
			$(this).removeClass('hide');
		});
	}
}

function quantity_key2(id){
	update_cart(id);
}


function update_cart(id){
	var container = $('#' + id);
	
	if(container.find('.cart_quantity').val() > 0){
		var param = '';
		container.find('.field').filter(function(){if($(this).val()!=''){param+='&'+$(this).attr('name')+'='+$(this).val();return;}});
		$.ajax({
			type: 'POST',
			url: template_directory + '/component/jstore/ajax.php',
			data: 'action=estimate' + param,	
			success: function(data){
				var cart = $.parseJSON(data);
				if(cart.status == 1){
					container.find('.cart_subtotal').val(cart.total);
					container.find('.cart_unitprice').val(cart.price);
					container.submit();
				}else{
					container.find('.cart_quantity').val(container.find('.cart_quantity').attr('defaultValue'));
				}
			}
		});
	}else{
		container.find('.cart_quantity').val(container.find('.cart_quantity').attr('defaultValue'));
	}
}