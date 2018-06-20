$(document).ready(function(){
	/* jBox */
	$('.jbox').filter(function(){
		var _this = $(this);
		var title = _this.attr('title');
		var content = _this.html();
		_this.removeAttr('title');
		
		_this.html('<div class="jbox-ui-header"><div class="jbox-ui-title">' + title + '</div><div class="jbox-ui-toggle"><a class="show" href="javascript:void(0);"></a></div></div><div class="jbox-ui-tooltip"></div><div class="jbox-ui-content">' + content + '</div>');
		return true;
	});	
	$('.jbox').find('.jbox-ui-toggle > a').click(function(){
		var tog = $(this);
		var parent = $(this).parents('.jbox:parent');
		parent.find('.jbox-ui-content').slideToggle('slow',function(){
			if(tog.hasClass('show')){
				$(this).addClass('hide');
				tog.removeClass('show');
			}else{
				$(this).removeClass('hide');				
				tog.addClass('show');
			}
		});
	});
	/* Tabs */					   
	$('.tabs').find('.tab > a').click(function(){
		if($(this).hasClass('ajax') == false){
			var tab = $(this).attr('href').replace(/#/g,'')
			$('.tabs').find('.tab').removeClass('active');
			$('.tab-content').addClass('hide');
			$('#' + tab + '-tab').removeClass('hide');
		}else{
			$('#ajax-tab-content').html('<div class="ajax-loader"></div>').load($(this).attr('href'));
		}
		$('.tabs').find('.tab').removeClass('active');	
		$(this).parent('.tab').addClass('active');
		return false;
	});
						   
	/* Slideshow */
	$('#slideshow .slide').hover(function(){
		$(this).addClass('mover');
	},function(){
		$(this).removeClass('mover');
	});
	if(animate_ss == true){
		setInterval('run_slideshow()',animation_speed);
	}
						   
	/* Modal Dialog */
	var maskHeight = $(document).height();
	var maskWidth = $(window).width();	
	$('.modalbox').css({'width':maskWidth,'height':maskHeight});
	
	$('#login-dd').find('span').click(function(){
		$('#login-dd').addClass('login-dd-active');
	});
	$('#login-dd').find('.cancel').click(function(){
		$('#login-dd').removeClass('login-dd-active');
	});
	
	
	/* Category Sidebar */
	$('#product-categories').find('.level-1 > li').hover(
		function(){
			if(animatesbcat == true){
				$('#sub-' + $(this).attr('id')).slideDown('slow');
			}
		},function(){
			if(animatesbcat == true){
				$('#sub-' + $(this).attr('id')).slideUp('slow');
			}
		}
	);
	
	$('.view-image').wrap('<a class="lightbox" href="' + $('.view-image').attr('rel') + '"/>').removeAttr('rel');
	/*$('.view-image').wrap('<a class="lightbox" href="' + $('.view-image').attr('rel') + '"/>').removeAttr('rel');*/
	$('a.lightbox').lightBox({
		imageLoading: template_directory + '/js/lightbox/images/loading.gif',
		imageBtnClose: template_directory + '/js/lightbox/images/close.gif',
		imageBtnPrev: template_directory + '/js/lightbox/images/prev.gif',
		imageBtnNext: template_directory + '/js/lightbox/images/next.gif'
	});
	
	/* Comment */
});

function comment_reply(id){
	/*var comment_id = $(this).attr('href').replace(/#/g,'')
	console.log(comment_id);*/
	
	$.ajax({
		type: 'POST',
		url: template_directory + '/ajax.php',
		data: 'action=reply_comment&id=' + id,
		success: function(msg){
			var old = $('#comment_message').val();
			$('#comment_message').val(old + msg).focus();
		}
	});
}

function run_slideshow() {
    var $active = $('#slideshow .slide_active');
	if($active.hasClass('mover') == false){
		if ( $active.length == 0 ) $active = $('#slideshow .slide:last');
	
		var $next =  $active.next().length ? $active.next()
			: $('#slideshow .slide:first');
	
		$active.addClass('slide_last_active');
	
		$next.css({opacity: 0.0})
			.addClass('slide_active')
			.animate({opacity: 1.0}, 1000, function() {
				$active.removeClass('slide_active slide_last_active');
			});
	}
}



function view_image(id){
	/* Modal Window */

}
function show_image(img){
	$('#view-image').find('.window').html('<img src="' + img + '" />');
	$('#view-image').css('display','block');
	$('#view-image').find('.window').css({'height':$('#view-image').find('img').height(),'width':$('#view-image').find('img').width()});
}