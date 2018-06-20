$(document).ready(function(){
	/* Password Strength Meter */
	 var passwordStrength = function(password){
        /*var desc = new Array();
        desc[0] = "Very Weak";
        desc[1] = "Weak";
        desc[2] = "Better";
        desc[3] = "Medium";
        desc[4] = "Strong";
        desc[5] = "Strongest";*/
        var score   = 0;
        if(password.length > 6) score++;
        if((password.match(/[a-z]/)) && (password.match(/[A-Z]/) ) ) score++;
        if(password.match(/\d+/)) score++;
        if(password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) ) score++;
        if(password.length > 12) score++;
		return score;
         /*document.getElementById("passwordDescription").innerHTML = desc[score];
         document.getElementById("passwordStrength").className = "strength" + score;*/
	}
	
	/* Pattern Checker */
	 var testPattern = function(value, pattern) {

            var regExp = new RegExp(pattern,"");
            return regExp.test(value);
     }
	/* Form Submission and Validation */
	var TheForm = $('form.validate');
	TheForm.submit(function(){
		$('#submit-error').remove();
		$required.blur();
		$email.blur();
		if($(this).find('.error').length > 0){						
			return false;
		}else{
			return true;
		}
	});
	
	/* Required */
	$required =  TheForm.find('.required');
	$required.blur(function(){
		if(($(this).is('input') || $(this).is('select')) && $(this).val() == ''){
			/*$(this).css('width',$(this).width() - 17 + 'px').addClass('error-field1 error');*/
			$(this).addClass('error');			

		}
	}).focus(function(){
		if(($(this).is('input') || $(this).is('select')) && $(this).val() == ''){
			/*$(this).removeClass('error-field1').removeClass('error').css('width',$(this).width() + 3 + 'px');*/
			$(this).removeClass('error');
		}
		$('#submit-error').remove();
	});
	
	/* Checkbox */
	$checkbox =  TheForm.find('.checkbox');
	$checkbox.click(function(){
		if($(this).attr('checked')){
			$(this).removeClass('error');
		}else{
			$(this).addClass('error');
		}
	});
	
	/* Email */
	$email =  TheForm.find('input[type=text].email');
	$email.blur(function(){
		$this = $(this); 
		/* PHP/AJAX Email Validation */
		$.ajax({
			type: 'POST',
			url: template_directory + '/ajax.php',
			data: 'action=validate&type=email&data=' + $this.val(),
			success: function(data){
				var obj = $.parseJSON(data);
				if(obj.status == 'VALID'){
					$this.removeClass('error');
				}else{
					$this.addClass('error');
				}
			}
		});	
		/*if(!testPattern($(this).val(),"[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])")){
			$(this).addClass('error');			
		}*/
	}).focus(function(){
		$(this).removeClass('error');
		$('#submit-error').remove();
	});
	
	/* Compare */
	$compare =  TheForm.find('input[type=password].compare');
	$compare.blur(function(){
		if($(this).val() != $('#' + $(this).attr('rel')).val()){
			$(this).addClass('error');			
		}
	}).focus(function(){
		$(this).removeClass('error');
		$('#submit-error').remove();
	});
	
	/* Password Strength*/
	$strength =  TheForm.find('input[type=password].strength');
	$strength.each(function(){
		$('<span id="' + $(this).attr('id') + '_strength" class="password-meter" />').insertAfter($(this)); 
	});	
	$strength.keypress(function(e){		
		if($(this).val()!=''){
			var desc = new Array();
			desc[0] = "Very Weak";
			desc[1] = "Weak";
			desc[2] = "Better";
			desc[3] = "Medium";
			desc[4] = "Strong";
			desc[5] = "Strongest";			
			var score = passwordStrength($(this).val());
			$p = $('#' + $(this).attr('id') + '_strength');
			$p.html(desc[score]);
		}
		
	}).blur(function(){
		if($(this).val()==''){
			$('#' + $(this).attr('id') + '_strength').html('');
		}else{
			$strength.keypress();
		}
	});	
});