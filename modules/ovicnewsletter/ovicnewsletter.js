function closeDialog(){    
    if ($('#persistent').is(':checked')){
        var data={'task':'cancelRegisNewsletter'};        
        data.persistent = '1';
            $.ajax({
    		type: "POST",
    		cache: false,
    		url: ovicNewsletterUrl + '/front-end-ajax.php',
    		dataType : "json",
    		data: data,
            complete: function(){},
    		success: function (response) {
    
    		}
    	});
    }    
	$("#overlay").hide();
    $(".ovicnewsletter").hide();    
}
function check_email(email){
	emailRegExp = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.([a-z]){2,4})$/;	
	if(emailRegExp.test(email)){
		return true;
	}else{
		return false;
	}
}
function regisNewsletter(){
    var data={'task':'regisNewsletter', 'action':0};
    var email = $("#input-email").val();
    if(check_email(email) == true){
        data.email = email;
        $("#regisNewsletterMessage").html("");
    }else{
        $("#regisNewsletterMessage").html(enterEmail);
        return false;
    }
    
    if ($('#persistent').is(':checked')){
        data.persistent = '1';
    }else{
        data.persistent = '0';
    }
    $.ajax({
		type: "POST",
		cache: false,
		url: ovicNewsletterUrl + '/front-end-ajax.php',
		dataType : "json",
		data: data,
        complete: function(){},
		success: function (response) {
			$("#regisNewsletterMessage").html(response);
            setTimeout(function(){ $("#regisNewsletterMessage").html("").hide(); closeDialog();}, 3000);
		}
	});
}