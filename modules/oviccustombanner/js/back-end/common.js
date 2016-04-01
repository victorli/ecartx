String.prototype.escapeSpecialChars = function() {
    return this.replace(/\\n/g, "\\n")
               .replace(/\\'/g, "\\'")
               .replace(/\\"/g, '\\"')
               .replace(/\\&/g, "\\&")
               .replace(/\\r/g, "\\r")
               .replace(/\\t/g, "\\t")
               .replace(/\\b/g, "\\b")
               .replace(/\\f/g, "\\f");
};
function handleEnterNumber(event){
	var keyCode = event.keyCode ? event.keyCode : event.charCode;	
	if((keyCode < 48 || keyCode > 58) && keyCode != 8 && keyCode != 13 && keyCode != 9 && keyCode != 35 && keyCode != 36 && keyCode != 99 && keyCode != 118 && keyCode != 46 && keyCode != 37 && keyCode != 39 && keyCode != 45){
		return false;
	}		
}
function handleEnterNumberInt(event){
	var keyCode = event.keyCode ? event.keyCode : event.charCode;
	if((keyCode < 48 || keyCode > 58) && keyCode != 8 && keyCode != 13 && keyCode != 9 && keyCode != 35 && keyCode != 36 && keyCode != 99 && keyCode != 118 && keyCode != 37 && keyCode != 39 && keyCode != 45){
		return false;
	}		
}
$(document).ready(function(){
    bannerListSetup();
    newForm = $("#frmBanner").html();
});
function goToElement(eId, offset){
	$("html, body").animate({ scrollTop: $('#'+eId).offset().top-offset}, 1000);
}
function bannerListSetup(){
    $("#bannerList").tableDnD({
		onDragStart: function(table, row) {
			originalOrder = $.tableDnD.serialize();
		},
		dragHandle: 'dragHandle',
		onDragClass: 'myDragClass',
		onDrop: function(table, row) {
            if (originalOrder != $.tableDnD.serialize()) {
                var rows = table.tBodies[0].rows;
                var ids = [];
                for (var i=0; i<rows.length; i++) {
                    var tr = rows[i].id;                    
                    ids[i] = tr.replace("bn_", ""); 
                }
    			var data={'action':'updateBannerOrdering', 'ids':ids, 'secure_key':secure_key};
                $.ajax({
            		type:'POST',
            		url: baseModuleUrl + '/backend.ajax.php',
            		data: data,
            		dataType:'json',
            		cache:false,
            		async: true,
            		beforeSend: function(){},
            		complete: function(){},
            		success: function(response){
            			showSuccessMessage(response.msg);
            			loadAllBanner();										
            		}		
            	});
            }              		         
		}        
	});
}
function setAjaxUpload(){
	new AjaxUpload($('#banner'), {
		action: baseModuleUrl+"/OvicCustomBannerUploader.php",
		name: 'uploadimage',
        data:{'secure_key':secure_key},
        //responseType: 'json',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			    showSuccessMessage('Bạn chỉ được upload file ảnh (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){
			var langActive = $("#langActive").val();
			$('#image-'+langActive).val(response);		    
		}

	});
}
jQuery(function($){
	
	
	new AjaxUpload($('#banner'), {
		action: baseModuleUrl+"/OvicCustomBannerUploader.php",
		name: 'uploadimage',
        data:{'secure_key':secure_key},
        //responseType: 'json',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			    showSuccessMessage('Bạn chỉ được upload file ảnh (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){
			var langActive = $("#langActive").val();
			$('#image-'+langActive).val(response);		    
		}

	});
	
     
    $(document).on('click','.lik-banner-status',function(){        
            var itemId = $(this).attr('item-id');
            var value = $(this).attr('value');        
            if(value == '1'){
            	$(this).attr('value', '0').removeClass('action-enabled').addClass('action-disabled');
            }else{
            	$(this).attr('value', '1').removeClass('action-disabled').addClass('action-enabled');
            }
    		var data={'action':'changeBannerStatus', 'itemId':itemId, 'value':value, 'secure_key':secure_key};
            $.ajax({
        		type:'POST',
        		url: baseModuleUrl + '/backend.ajax.php',
        		data: data,
        		dataType:'json',
        		cache:false,
        		async: true,
        		beforeSend: function(){},
        		complete: function(){},
        		success: function(response){
                    if(response){             
                        showSuccessMessage(response.msg);
                    }                											
        		}		
        	});
	});
    $(document).on('click','.lik-banner-edit',function(){        
            var itemId = $(this).attr('item-id');            
    		var data={'action':'loadBanner', 'itemId':itemId, 'secure_key':secure_key};
            $.ajax({
        		type:'POST',
        		url: baseModuleUrl + '/backend.ajax.php',
        		data: data,
        		dataType:'json',
        		cache:false,
        		async: true,
        		beforeSend: function(){},
        		complete: function(){},
        		success: function(response){
                    if(response.status == '1'){             
                        $("#frmBanner").html(response.form);              
                        showModal('modalBanner', '');
                        setAjaxUpload();
                    }else{
                    	showSuccessMessage(response.msg);
                    }									
        		}		
        	});    
        
				
	});
	
    // delete item
    $(document).on('click','.lik-banner-delete',function(){
        if(confirm("Are you sure you want to delete item?") == true){
           var itemId = $(this).attr('item-id');        
    		var data={'action':'deleteBanner', 'itemId':itemId, 'secure_key':secure_key};
            $.ajax({
        		type:'POST',
        		url: baseModuleUrl + '/backend.ajax.php',
        		data: data,
        		dataType:'json',
        		cache:false,
        		async: true,
        		beforeSend: function(){
                    	
        		},
        		complete: function(){},
        		success: function(response){                                        
                    showSuccessMessage(response.msg);
                    if(response.status == '1') loadAllBanner();                    								
        		}		
        	}); 
        }
	});
    
    $('#modalBanner').on('hidden.bs.modal', function (e) { 
    	$("#frmBanner").html(newForm);    	
		setAjaxUpload();
        $("p.ajax-loader").remove();
    });  	
});

function showModal(newModal, oldModal){
	if(oldModal != "") $("#"+oldModal).modal('hide');
	$("#"+newModal).modal('show');
}
// load group by lang
function loadBannerByLang(langValue){
    $(".lang").each(function() {
        $(this).val(langValue);        
    });
    var itemId = $("#bannerId").val();
    if(itemId != '0'){
        var data={'action':'loadBannerByLang', 'itemId':itemId, 'langId':langValue, 'secure_key':secure_key};
        $.ajax({
    		type:'POST',
    		url: baseModuleUrl + '/backend.ajax.php',
    		data: data,
    		dataType:'json',
    		cache:false,
    		async: true,
    		beforeSend: function(){
    		},
    		complete: function(){ 					
    		},
    		success: function(response){				$("#banner-title").val(response.title);                $("#banner-image").val(response.image);				$("#banner-link").val(response.link);
    		}
    	});
    }
}










// Save group
function saveBanner(){    
	
	
    $("#modalBanner .modal-footer").append('<p class="ajax-loader"><i class="fa fa-spinner fa-spin"></i></p>');    
    var data = $('form#frmBanner').serializeObject();
    
    $.ajax({
		type:'POST',
		url: baseModuleUrl + '/backend.ajax.php',
		data: data,
		dataType:'json',
		cache:false,
		async: true,
		beforeSend: function(){
		},
		complete: function(){ 					
		},
		success: function(response){
		  $("p.ajax-loader").remove();
            showSuccessMessage(response.msg);
            if(response.status == '1'){
            	loadAllBanner();
            	$('#modalBanner').modal('hide');
            }
		}		
	});
}
function loadAllBanner(){
	var data={'action':'loadAllBanner', 'secure_key':secure_key};
	$.ajax({
		type:'POST',
		url: baseModuleUrl + '/backend.ajax.php',
		data: data,
		dataType:'json',
		cache:false,
		async: true,
		beforeSend: function(){
			
		},
		complete: function(){},
		success: function(response){
            if(response.status = '1'){
            	$("#bannerList >tbody").html(response.content);
		  		bannerListSetup();    
            }
		}		
	});
}


function changeLanguage(langId){
	var oldLang = $("#langActive").val(); 
	$("#langActive").val(langId);	
	$(".lang").each(function() {
		$(this).val(langId);        
    });
    $(".lang-"+oldLang).hide();
    $(".lang-"+langId).show();
    
}
