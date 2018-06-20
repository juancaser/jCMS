$(document).ready(function(){
	
	$('#item-viewer').submit(function(){
		var numItems = $(this).find('.chk:checked').length;
		if(numItems > 0){
			return true;
		}else{
			return false;
		}
	});
});
