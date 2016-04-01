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
function replacequote(text) {
    var newText = "";
    for (var i = 0; i < text.length; i++) {
        if (text[i] == "'") {
            newText += "\\'";
        }
        else
            newText += text[i];
    }
    return newText;
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

function showModal(newModal, oldModal){   

	if(oldModal != "") $("#"+oldModal).modal('hide');

	$("#"+newModal).modal('show');

}

function goToElement(eId, offset){

	$("html, body").animate({ scrollTop: $('#'+eId).offset().top-offset}, 1000);

}

$(document).ready(function(){
    $("#list-group a").click(function(e){

        $("#list-group").find('a').removeClass('active');

        $(this).addClass('active');

    	e.preventDefault();

    	$(this).tab('show');

    });
    moduleFormNew = $("#frmModule").html();
    menuFormNew = $("#frmMenu").html();
    menuGroupFormNew = $("#frmMenuGroup").html();
    menuItemFormNew = $("#frmMenuItem").html();     
    tinySetup();
    moduleListSetup();

});

function menuIconUploader(){
	new AjaxUpload($('#menu-icon-uploader'), {	   
		action: baseModuleUrl+"/VerticalMegaMenusUploadImage.php",
		name: 'uploadimage',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	alert('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){			
			$('#menu-icon').val(response);		
		}
	});
}
function menuImageUploader(){
	new AjaxUpload($('#menu-image-uploader'), {	   
		action: baseModuleUrl+"/VerticalMegaMenusUploadImage.php",
		name: 'uploadimage',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	alert('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){
			var langActive = $("#menuLangActive").val();			
			$('#menuImage-'+langActive).val(response);		
		}
	});
}
function menuItemIconUploader(){
	new AjaxUpload($('#menu-item-icon-uploader'), {	   
		action: baseModuleUrl+"/VerticalMegaMenusUploadImage.php",
		name: 'uploadimage',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	alert('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){			
			$('#menu-item-icon').val(response);		
		}
	});
}
function menuItemImageUploader(){
	new AjaxUpload($('#menu-item-image-uploader'), {	   
		action: baseModuleUrl+"/VerticalMegaMenusUploadImage.php",
		name: 'uploadimage',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	alert('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){
			var langActive = $("#menuItemLangActive").val();			
			$('#menuItemImage-'+langActive).val(response);		
		}
	});
}
jQuery(function($){    
	$('#modalModule').on('hidden.bs.modal', function (e) {
        $("#frmModule").html(moduleFormNew);
        $("p.ajax-loader").remove();
    });
    $('#modalMenu').on('hidden.bs.modal', function (e) {
    	tinyRemove();
        $("#frmMenu").html(menuFormNew);
        menuIconUploader();
        menuImageUploader();
        tinySetup();
        $("p.ajax-loader").remove();
    });

    $('#modalGroup').on('hidden.bs.modal', function (e) {
        $("#frmMenuGroup").html(menuGroupFormNew);
        $("p.ajax-loader").remove();             
    });
    $('#modalMenuItem').on('hidden.bs.modal', function (e) {
		tinyRemove();
		$("#frmMenuItem").html(menuItemFormNew);
        menuItemImageUploader();
        tinySetup();
        $("p.ajax-loader").remove();
    });
    new AjaxUpload($('#menu-image-uploader'), {	   
		action: baseModuleUrl+"/VerticalMegaMenusUploadImage.php",
		name: 'uploadimage',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	alert('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){
			var langActive = $("#menuLangActive").val();			
			$('#menuImage-'+langActive).val(response);		
		}
	});
    new AjaxUpload($('#menu-icon-uploader'), {	   
		action: baseModuleUrl+"/VerticalMegaMenusUploadImage.php",
		name: 'uploadimage',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	alert('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){			
			$('#menu-icon').val(response);		
		}
	});
    new AjaxUpload($('#menu-item-image-uploader'), {	   
		action: baseModuleUrl+"/VerticalMegaMenusUploadImage.php",
		name: 'uploadimage',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	alert('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){
			var langActive = $("#menuItemLangActive").val();			
			$('#menuItemImage-'+langActive).val(response);		
		}
	});
    $(document).on('click','.lik-module-status',function(){        
            var itemId = $(this).attr('item-id');
            var value = $(this).attr('value');        
            if(value == '1'){
            	$(this).attr('value', '0').removeClass('action-enabled').addClass('action-disabled');
            }else{
            	$(this).attr('value', '1').removeClass('action-disabled').addClass('action-enabled');
            }
    		var data={'action':'changModuleStatus', 'itemId':itemId, 'value':value, 'secure_key':secure_key};
            $.ajax({
        		type:'POST',
        		url: baseModuleUrl + '/ajax.php',
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
    $(document).on('click','.lik-menu-status',function(){        
            var itemId = $(this).attr('item-id');
            var value = $(this).attr('value');        
            if(value == '1'){
            	$(this).attr('value', '0').removeClass('action-enabled').addClass('action-disabled');
            }else{
            	$(this).attr('value', '1').removeClass('action-disabled').addClass('action-enabled');
            }
    		var data={'action':'changMenuStatus', 'itemId':itemId, 'value':value, 'secure_key':secure_key};
            $.ajax({
        		type:'POST',
        		url: baseModuleUrl + '/ajax.php',
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
    
    $(document).on('click','.lik-group-status',function(){        
            var itemId = $(this).attr('item-id');
            var value = $(this).attr('value');        
            if(value == '1'){
            	$(this).attr('value', '0').removeClass('action-enabled').addClass('action-disabled');
            }else{
            	$(this).attr('value', '1').removeClass('action-disabled').addClass('action-enabled');
            }
    		var data={'action':'changGroupStatus', 'itemId':itemId, 'value':value, 'secure_key':secure_key};
            $.ajax({
        		type:'POST',
        		url: baseModuleUrl + '/ajax.php',
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
    
    $(document).on('click','.lik-menu-item-status',function(){        
            var itemId = $(this).attr('item-id');
            var value = $(this).attr('value');        
            if(value == '1'){
            	$(this).attr('value', '0').removeClass('action-enabled').addClass('action-disabled');
            }else{
            	$(this).attr('value', '1').removeClass('action-disabled').addClass('action-enabled');
            }
    		var data={'action':'changMenuItemStatus', 'itemId':itemId, 'value':value, 'secure_key':secure_key};
            $.ajax({
        		type:'POST',
        		url: baseModuleUrl + '/ajax.php',
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
    $(document).on('click','.lik-module-edit',function(){
        var itemId = $(this).attr('item-id');  
        var data={'action':'getModuleItem', 'itemId':itemId, 'secure_key':secure_key};
        $.ajax({
    		type:'POST',
    		url: baseModuleUrl + '/ajax.php',
    		data: data,
    		dataType:'json',
    		cache:false,
    		async: true,
    		beforeSend: function(){
                showModal('modalModule', '');
    		},
    		complete: function(){},
    		success: function(response){                    
                if(response != null){
                    if(response.status == "1"){
                    	$("#frmModule").html(response.form);                    	
                    }else{
                        showSuccessMessage("Item not found!");
                    }

                }  								

    		}		

    	});         

	});
    $(document).on('click','.lik-module-delete',function(){
        if(confirm("Are you sure you want to delete module item?") == true){
            var itemId = $(this).attr('item-id');        
    		var data={'action':'deleteModule', 'itemId':itemId, 'secure_key':secure_key};
            $.ajax({
        		type:'POST',
        		url: baseModuleUrl + '/ajax.php',
        		data: data,
        		dataType:'json',
        		cache:false,
        		async: true,
        		beforeSend: function(){},
        		complete: function(){},
        		success: function(response){
                    if(response){                        
                        showSuccessMessage(response.msg);
                        if(response.status == '1') window.location.reload();
                    }                											
        		}
        	});    
        }		
	}); 
    $(document).on('click','.lik-module',function(){        
        verticalModuleId = $(this).attr('item-id');
        $("#header-module-name").html('['+$(this).attr('title')+']');
        loadAllMenu();        
	});

    $(document).on('click','.lik-menu-edit',function(){
        var itemId = $(this).attr('item-id');        
        var data={'action':'getMenu', 'itemId':itemId, 'secure_key':secure_key};
        $.ajax({
    		type:'POST',
    		url: baseModuleUrl + '/ajax.php',
    		data: data,
    		dataType:'json',
    		cache:false,
    		async: true,
    		beforeSend: function(){},
    		complete: function(){},
    		success: function(response){                    
                if(response != null){
                    if(response.status == "1"){
                    	tinyRemove();
						$("#frmMenu").html(response.form);
						menuIconUploader();
						menuImageUploader();						
                        showModal('modalMenu', '');
						tinySetup();
                    }else{
                        showSuccessMessage(response.msg);
                    }

                }  								

    		}		

    	});         

	});

    

    

    $(document).on('click','.lik-menu-delete',function(){

        if(confirm("Are you sure you want to delete menu item?") == true){

            var itemId = $(this).attr('item-id');        

    		var data={'action':'deleteMenu', 'itemId':itemId, 'secure_key':secure_key};

            $.ajax({

        		type:'POST',

        		url: baseModuleUrl + '/ajax.php',

        		data: data,

        		dataType:'json',

        		cache:false,

        		async: true,

        		beforeSend: function(){},

        		complete: function(){},

        		success: function(response){

                    if(response){                        

                        showSuccessMessage(response.msg);

                        loadAllMenu();

                    }                											

        		}		

        	});    

        }

				

	});

    

    $(document).on('click','.lik-menu',function(){
        if(verticalMenuId != '0'){
			$("#mn_"+verticalMenuId).removeClass('tr-selected');            
        }
        verticalMenuId = $(this).attr('item-id');
        $("#mn_"+verticalMenuId).addClass('tr-selected');        
        $("#panel-list-group").show();
        $("#header-menu-name").html('['+$(this).attr('title')+']');
        goToElement('panel-list-group', 100);
        loadAllMenuGroup();        

	});

    

    

    

    $(document).on('click','.lik-group-edit',function(){

        var itemId = $(this).attr('item-id');        

        var data={'action':'getMenuGroup', 'itemId':itemId, 'secure_key':secure_key};

        $.ajax({

    		type:'POST',

    		url: baseModuleUrl + '/ajax.php',

    		data: data,

    		dataType:'json',

    		cache:false,

    		async: true,

    		beforeSend: function(){

                

    		},

    		complete: function(){},

    		success: function(response){                    

                if(response != null){					
                    if(response.status == "1"){
                    	$("#frmMenuGroup").html(response.form);
                        showModal('modalGroup', '');
                    }else{
                        showSuccessMessage(response.msg);

                    }
                }  								

    		}		

    	});         

	});

    $(document).on('click','.lik-group-delete',function(){

        if(confirm("Are you sure you want to delete group?") == true){

            var itemId = $(this).attr('item-id');        

    		var data={'action':'deleteGroup', 'itemId':itemId, 'secure_key':secure_key};

            $.ajax({

        		type:'POST',

        		url: baseModuleUrl + '/ajax.php',

        		data: data,

        		dataType:'json',

        		cache:false,

        		async: true,

        		beforeSend: function(){},

        		complete: function(){},

        		success: function(response){

                    if(response){                        

                        showSuccessMessage(response.msg);

                        loadAllMenuGroup();

                    }                											

        		}		

        	});    

        }

				

	});

    $(document).on('click','.lik-group',function(){

        if(verticalGroupId != '0'){

            $("#gr_"+verticalGroupId).removeClass('tr-selected');            

        }

        verticalGroupId = $(this).attr('item-id');

        verticalGroupType = $(this).attr('item-type');

        $("#gr_"+verticalGroupId).addClass('tr-selected');

        if(verticalGroupType == 'custom' || verticalGroupType == 'link'){

            $("#panel-list-menu-item").show();

            $("#header-group-name").html('['+$(this).attr('title')+']');  

        } 

        else $("#panel-list-menu-item").hide();

        goToElement('panel-list-menu-item', 100);

        loadAllMenuItem();        

	});

    

    

    

    $(document).on('click','.lik-menu-item-edit',function(){

        var itemId = $(this).attr('item-id');        

        var data={'action':'getMenuItem', 'itemId':itemId, 'secure_key':secure_key};

        $.ajax({

    		type:'POST',

    		url: baseModuleUrl + '/ajax.php',

    		data: data,

    		dataType:'json',

    		cache:false,

    		async: true,

    		beforeSend: function(){

                

    		},

    		complete: function(){},

    		success: function(response){                    

                if(response != null){

                    if(response.status == "1"){
                    	tinyRemove();
                    	$("#frmMenuItem").html(response.content);
                    	menuItemImageUploader();
                    	tinySetup();
                        showModal('modalMenuItem', '');
                    }else{
                        showSuccessMessage(response.msg);

                    }

                }  								

    		}		

    	});         

	});

    $(document).on('click','.lik-menu-item-delete',function(){

        if(confirm("Are you sure you want to delete menu item?") == true){

            var itemId = $(this).attr('item-id');        

    		var data={'action':'deleteMenuItem', 'itemId':itemId, 'secure_key':secure_key};

            $.ajax({

        		type:'POST',

        		url: baseModuleUrl + '/ajax.php',

        		data: data,

        		dataType:'json',

        		cache:false,

        		async: true,

        		beforeSend: function(){},

        		complete: function(){},

        		success: function(response){

                    if(response){                        

                        showSuccessMessage(response.msg);

                        loadAllMenuItem();

                    }                											

        		}		

        	});    

        }

				

	});

    

    var lastValue;

    $("#categoryId").bind("click", function(e) {

        lastValue = $(this).val();

    }).bind("change", function(e) {

        var itemId = $("#group-id").val();

        if(itemId != "0"){            

            $(this).val(lastValue);

            showSuccessMessage('Not edit category of group');                

        }        

    });

});



function menuListSetup(){

    $("#listMenu").tableDnD({

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

                    ids[i] = tr.replace("mn_", ""); 

                }

    			var data={'action':'updateMenuOrdering', 'ids':ids, 'secure_key':secure_key};

                $.ajax({

            		type:'POST',

            		url: baseModuleUrl + '/ajax.php',

            		data: data,

            		dataType:'json',

            		cache:false,

            		async: true,

            		beforeSend: function(){

            		},

            		complete: function(){ 					

            		},

            		success: function(response){

                        showSuccessMessage(response.msg);

                        loadAllMenu();									

            		}		

            	});

            }              		         

		}        

	});

}

function menuItemListSetup(){

    $("#listMenuItem").tableDnD({

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

                    ids[i] = tr.replace("mni_", ""); 

                }

    			var data={'action':'updateMenuItemOrdering', 'ids':ids, 'secure_key':secure_key};

                $.ajax({

            		type:'POST',

            		url: baseModuleUrl + '/ajax.php',

            		data: data,

            		dataType:'json',

            		cache:false,

            		async: true,

            		beforeSend: function(){

            		},

            		complete: function(){ 					

            		},

            		success: function(response){

                        showSuccessMessage(response.msg);

                        loadAllMenuItem();									

            		}		

            	});

            }              		         

		}        

	});

}

function moduleListSetup(){

    $("#moduleList").tableDnD({

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

                    ids[i] = tr.replace("mo_", ""); 

                }

    			var data={'action':'updateModuleOrdering', 'ids':ids, 'secure_key':secure_key};

                $.ajax({

            		type:'POST',

            		url: baseModuleUrl + '/ajax.php',

            		data: data,

            		dataType:'json',

            		cache:false,

            		async: true,

            		beforeSend: function(){

            		},

            		complete: function(){ 					

            		},

            		success: function(response){

                        showSuccessMessage(response.msg);

                        if(response.status == '1') window.location.reload();										

            		}		

            	});

            }              		         

		}        

	});

}

function listGroupSetup(){

    $("#listGroup").tableDnD({

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

                    ids[i] = tr.replace("gr_", ""); 

                }

    			var data={'action':'updateGroupOrdering', 'ids':ids, 'secure_key':secure_key};

                $.ajax({

            		type:'POST',

            		url: baseModuleUrl + '/ajax.php',

            		data: data,

            		dataType:'json',

            		cache:false,

            		async: true,

            		beforeSend: function(){

            		},

            		complete: function(){ 					

            		},

            		success: function(response){

                        showSuccessMessage(response.msg);

                        if(response.status == '1') loadAllMenuGroup();										

            		}		

            	});

            }              		         

		}        

	});

}

function saveModule(){
    $("#modalModule .modal-footer").append('<p class="ajax-loader"><i class="fa fa-spinner fa-spin"></i></p>');
	var data = $('form#frmModule').serializeObject();
    $.ajax({

		type:'POST',

		url: baseModuleUrl + '/ajax.php',

		data: data,

		dataType:'json',

		cache:false,

		async: true,

		beforeSend: function(){},

		complete: function(){},

		success: function(response){
            $("p.ajax-loader").remove();
            showSuccessMessage(response.msg);

            if(response.status == '1') window.location.reload();

            										

		}

	});

}



function showItemContentByType(value){

    if(value == 'link'){

        $(".item-type-image").hide();

        $(".item-type-html").hide();

    }else if(value == 'image'){

        $(".item-type-html").hide();

        $(".item-type-image").show();

    }else if(value == 'html'){

        $(".item-type-image").hide();

        $(".item-type-html").show();

    }

}





function showContentByType(value){

    if(value == 'link'){

        $(".type-image").hide();

        $(".type-html").hide();

    }else if(value == 'image'){

        $(".type-html").hide();

        $(".type-image").show();

    }else if(value == 'html'){

        $(".type-image").hide();

        $(".type-html").show();

    }

}

function generationUrl(value, inputUrl){

    if(value == 'CUSTOMLINK-0'){

        $("#"+inputUrl).val('');

    }else if(value == 'PRODUCT-0'){

        showModal('modalProductId', '');

    }else{

        var data = {'action':'generationUrl', 'value':value, 'secure_key':secure_key};

        $.ajax({

    		type:'POST',

    		url: baseModuleUrl + '/ajax.php',

    		data: data,

    		dataType:'json',

    		cache:false,

    		async: true,

    		beforeSend: function(){},

    		complete: function(){},

    		success: function(response){

                $("#"+inputUrl).val(response);

                										

    		}

    	});

    }    

}

function addProductId(){

    if($('#modalMenu').hasClass('in') == true){

        var elUrl = 'menu-link';

    }else{

        if($('#modalMenuItem').hasClass('in') == true){

            var elUrl = 'menu-item-link';

        }else{

            $('#modalProductId').modal('hide');

            return false;

        }

    }

    productId = $("#product-id").val();

    if(productId){

        var value = 'PRD-'+productId;

        var data = {'action':'generationUrl', 'value':value, 'secure_key':secure_key};

        $.ajax({

    		type:'POST',

    		url: baseModuleUrl + '/ajax.php',

    		data: data,

    		dataType:'json',

    		cache:false,

    		async: true,

    		beforeSend: function(){},

    		complete: function(){},

    		success: function(response){

                $("#"+elUrl).val(response);

                $("#product-id").val('');

                $('#modalProductId').modal('hide');										

    		}

    	});        

    }

    

}



// load module by lang

function moduleChangeLanguage(langId){
	var oldLang = $("#moduleLangActive").val(); 
	$("#moduleLangActive").val(langId);	
	$(".module-lang").each(function() {
		$(this).val(langId);        
    });
    $(".module-lang-"+oldLang).hide();
    $(".module-lang-"+langId).show();
    
}

// load menu by lang

function menuChangeLanguage(langId){
	var oldLang = $("#menuLangActive").val(); 
	$("#menuLangActive").val(langId);	
	$(".menu-lang").each(function() {
		$(this).val(langId);        
    });
    $(".menu-lang-"+oldLang).hide();
    $(".menu-lang-"+langId).show();
}



function tinyRemove(elParent){
	
	  var i, t = tinyMCE.editors;
		for (i in t){
		    if (t.hasOwnProperty(i)){
		        t[i].remove();
		    }
		}
	 
}

function saveMenu(){
    $("#modalMenu .modal-footer").append('<p class="ajax-loader"><i class="fa fa-spinner fa-spin"></i></p>');
	tinymce.triggerSave();
	var data = $('form#frmMenu').serializeObject();
	data.moduleId = verticalModuleId;	
    $.ajax({
		type:'POST',

		url: baseModuleUrl + '/ajax.php',

		data: data,

		dataType:'json',

		cache:false,

		async: true,

		beforeSend: function(){},

		complete: function(){},

		success: function(response){
            $("p.ajax-loader").remove();
            showSuccessMessage(response.msg);

            if(response.status == '1'){

                

                $('#modalMenu').modal('hide');

                loadAllMenu();	

            }

            

            										

		}

	});

}

function loadAllMenu(){

    if(parseInt(verticalModuleId) >0){

        var data = {'action':'loadAllMenu', 'moduleId':verticalModuleId, 'secure_key':secure_key}; 

        $.ajax({

    		type:'POST',

    		url: baseModuleUrl + '/ajax.php',

    		data: data,

    		dataType:'json',

    		cache:false,

    		async: true,

    		beforeSend: function(){},

    		complete: function(){},

    		success: function(response){
				
                $("#listMenu >tbody").html(response.content);
				if(response.status == "1") menuListSetup();
				showSuccessMessage(response.msg);
                										

    		}

    	}); 

    }else{
		showSuccessMessage("You need to choose one module, please!");
    }

}

function showGroupType(value){

    if(value == 'product'){

        $("#group-type-custom").hide();

        $("#group-type-product").show();

    }else if(value == 'custom'){

        $("#group-type-product").hide();

        $("#group-type-custom").show();

    }else{

        $("#group-type-product").hide();

        $("#group-type-custom").hide();  

    } 

}

function showProductOption(value){

    if(value == 'manual'){
        $("#group-product-type-auto").hide();
        $("#group-product-type-manual").show();  
    }else{
        $("#group-product-type-manual").hide();
        $("#group-product-type-auto").show();        
    } 

}

function loadAllMenuGroup(){    
	$("#panel-list-menu-item").hide();
    if(parseInt(verticalMenuId) >0){
        var data = {'action':'loadAllMenuGroup', 'menuId':verticalMenuId, 'secure_key':secure_key}; 
        $.ajax({
    		type:'POST',
    		url: baseModuleUrl + '/ajax.php',
    		data: data,
    		dataType:'json',
    		cache:false,
    		async: true,
    		beforeSend: function(){},
    		complete: function(){},
    		success: function(response){
    			showSuccessMessage(response.msg);
                $("#listGroup >tbody").html(response.content);
                listGroupSetup();
    		}
    	}); 
    }else{
        showSuccessMessage("You need to choose one Menu, please!");
    }

}


function saveGroup(){
    $("#modalGroup .modal-footer").append('<p class="ajax-loader"><i class="fa fa-spinner fa-spin"></i></p>');
	var data = $('form#frmMenuGroup').serializeObject();
	data.menuId = verticalMenuId;	
    $.ajax({
		type:'POST',
		url: baseModuleUrl + '/ajax.php',
		data: data,
		dataType:'json',
		cache:false,
		async: true,
		beforeSend: function(){},
		complete: function(){},
		success: function(response){
		  $("p.ajax-loader").remove();
            showSuccessMessage(response.msg);
            if(response.status == '1'){
                $('#modalGroup').modal('hide');
                loadAllMenuGroup();	
            }										

		}

	}); 

}

function menuGroupChangeLanguage(langId){
	var oldLang = $("#menuGroupLangActive").val(); 
	$("#menuGroupLangActive").val(langId);	
	$(".menu-group-lang").each(function() {
		$(this).val(langId);        
    });
    $(".menu-group-lang-"+oldLang).hide();
    $(".menu-group-lang-"+langId).show();
   
    

}
function menuItemChangeLanguage(langId){
	var oldLang = $("#menuItemLangActive").val(); 
	$("#menuItemLangActive").val(langId);	
	$(".menu-item-lang").each(function() {
		$(this).val(langId);        
    });
    $(".menu-item-lang-"+oldLang).hide();
    $(".menu-item-lang-"+langId).show();
    

}



function saveMenuItem(){
    $("#modalMenuItem .modal-footer").append('<p class="ajax-loader"><i class="fa fa-spinner fa-spin"></i></p>');
	tinymce.triggerSave();
	var data = $('form#frmMenuItem').serializeObject();
	data.menuId = verticalMenuId;	
	data.groupId = verticalGroupId;   
    $.ajax({
		type:'POST',
		url: baseModuleUrl + '/ajax.php',
		data: data,
		dataType:'json',
		cache:false,
		async: true,
		beforeSend: function(){},
		complete: function(){},
		success: function(response){
		  $("p.ajax-loader").remove();
            showSuccessMessage(response.msg);
            if(response.status == '1'){
                $('#modalMenuItem').modal('hide');
                loadAllMenuItem();	
            }
		}
	});

}





function loadAllMenuItem(){

    if(parseInt(verticalGroupId) >0){

        var data = {'action':'loadAllMenuItem', 'groupId':verticalGroupId, 'secure_key':secure_key}; 

        $.ajax({

    		type:'POST',

    		url: baseModuleUrl + '/ajax.php',

    		data: data,

    		dataType:'json',

    		cache:false,

    		async: true,

    		beforeSend: function(){},

    		complete: function(){},

    		success: function(response){

                $("#listMenuItem >tbody").html(response);

                menuItemListSetup();									

    		}

    	}); 

    }else{

        alert("You need to choose one Group, please!");

    }

}