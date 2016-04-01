function goToElement(eId, offset){
	$("html, body").animate({ scrollTop: $('#'+eId).offset().top-offset});
}
$(document).ready(function(){
	if($(".tbl-banners").length >0) tableBannerOrder();
    $("#list-group a").click(function(e){
        $("#list-group").find('a').removeClass('active');
        $(this).addClass('active');
    	e.preventDefault();
    	$(this).tab('show');
    });
    moduleFormNew = $("#frmModule").html();
    groupFormNew = $("#frmGroup").html();
    moduleListSetup();
});
function clearCache(){                
    var data={'action':'clearCache', 'secure_key':secure_key};
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
            showSuccessMessage(response);	
		}		
	});
}
function showModal(elModal){
	if(elModal != "") $("#"+elModal).modal('hide');
	$("#"+elModal).modal('show');
}
function moduleChangeLanguage(langId){
	var oldLang = $("#moduleLangActive").val(); 
	$("#moduleLangActive").val(langId);	
	$(".module-lang").each(function() {
		$(this).val(langId);        
    });
    $(".module-lang-"+oldLang).hide();
    $(".module-lang-"+langId).show();
    $(".tbl-banners-lang-"+oldLang).hide();
    $(".tbl-banners-lang-"+langId).show();
}
function groupChangeLanguage(langId){
	var oldLang = $("#groupLangActive").val(); 
	$("#groupLangActive").val(langId);	
	$(".group-lang").each(function() {
		$(this).val(langId);        
    });
    $(".group-lang-"+oldLang).hide();
    $(".group-lang-"+langId).show();    
}

function tableBannerOrder(){
	$(".tbl-banners").tableDnD({
		onDragStart: function(table, row) {},
		dragHandle: 'dragHandle',
		onDragClass: 'myDragClass',
		onDrop: function(table, row) {} 
	});
}
jQuery(function($){        
    $('#addModal').on('hidden.bs.modal', function (e) {
        $("#frmModule").html(moduleFormNew);
        moduleBannerUploadConfig();
        $("#list-group a").click(function(e){
	        $("#list-group").find('a').removeClass('active');
	        $(this).addClass('active');
	    	e.preventDefault();
	    	$(this).tab('show');
	    });
        $("p.ajax-loader").remove();        
    });
    $('#groupModal').on('hidden.bs.modal', function (e) {        
        $("#frmGroup").html(groupFormNew);
        groupIconUploadConfig();
        $("p.ajax-loader").remove();
        //groupIconActiveUploadConfig();
    });
    $('#productsModal').on('hidden.bs.modal', function (e) {
        loadProductByGroup();
    });
    $(document).on('click','.lik-module-edit',function(){        
        var itemId = $(this).attr('item-id');                
        var data={'action':'loadModuleItem', 'itemId':itemId, 'secure_key':secure_key};
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
    				$("#module-info").html(response.config);
    				$("#form-banners").html(response.banners);
    				showModal('addModal');
    				moduleBannerUploadConfig();
    				tableBannerOrder();
    			}else{
    				showSuccessMessage(response.message);
    			}	
    		}		
    	});
	});
    $(document).on('click','.lik-banner-del',function(){        
    	$(this).parent().parent().parent().remove();
	});
    $(document).on('click','.module-item',function(){        
        var itemId = $(this).attr('item-id');                
        if(moduleId != '0'){
            $("#mod_"+moduleId).removeClass('tr-selected');            
        }
        moduleId = itemId;
        $("#mod_"+moduleId).addClass('tr-selected');
        $("#span-module-name").html($(this).html());
        goToElement('groupList', 100);
        $("#mainGroupList").show();
        loadGroupByModule();     
	});
	
	
	
	$(document).on('click','.lik-module-status',function(){        
        var itemId = $(this).attr('item-id');
        var value = $(this).attr('value');        
        if(value == '1'){
        	$(this).attr('value', '0').removeClass('action-enabled').addClass('action-disabled');
        }else{
        	$(this).attr('value', '1').removeClass('action-disabled').addClass('action-enabled');
        }
		var data={'action':'changeModuleStatus', 'itemId':itemId, 'value':value, 'secure_key':secure_key};
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
	$(document).on('click','.lik-group-status',function(){        
        var itemId = $(this).attr('item-id');
        var value = $(this).attr('value');        
        if(value == '1'){
        	$(this).attr('value', '0').removeClass('action-enabled').addClass('action-disabled');
        }else{
        	$(this).attr('value', '1').removeClass('action-disabled').addClass('action-enabled');
        }
		var data={'action':'changeGroupStatus', 'itemId':itemId, 'value':value, 'secure_key':secure_key};
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

	
    //link-add-manual-product
    $(document).on('click','.link-add-manual-product',function(){        
        var itemId = $(this).attr('item-id');        
        if(moduleId != '0' && groupId != '0' && groupType == 'manual'){
        	$(this).parent().html('<i class="icon-check-square-o"></i>');
            var data={'action':'addManualProductItem', 'itemId':itemId, 'groupId':groupId, 'moduleId':moduleId, 'secure_key':secure_key};
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
                    if(response.status == '1'){
                    	$(this).parent().html('-');                                  
                    }							
        		}		
        	}); 
        }
	});
    
    //link-delete-product
    $(document).on('click','.link-delete-product',function(){
        if(confirm('Are you sure you want to delete item?') == true){
            var itemId = $(this).attr('item-id');
            if(moduleId != '0' && groupId != '0' && groupType == 'manual'){                                
                var data={'action':'deleteManualProductItem', 'itemId':itemId, 'groupId':groupId, 'moduleId':moduleId, 'secure_key':secure_key};
                $.ajax({
            		type:'POST',
            		url: baseModuleUrl + '/backend.ajax.php',
            		data: data,
            		dataType:'json',
            		cache:false,
            		async: true,
            		beforeSend: function(){},
            		complete: function(){},
            		success: function(data){
                        if(data == 'ok'){
                            showSuccessMessage('Delete product success!');
                            loadProductByGroup();
                        }else {
                            showSuccessMessage(data);
                        }								
            		}		
            	}); 
            }  
        }
	});
    //category-item
    $(document).on('click','.group-item',function(){        
        var itemId = $(this).attr('item-id');
        var itemType = $(this).attr('item-type');                
        if(groupId != '0'){
            $("#gro_"+groupId).removeClass('tr-selected');            
        }
        groupId = itemId;
        groupType = itemType;
        $("#gro_"+groupId).addClass('tr-selected');
        $("#span-group-name").html($(this).html());
        $("#mainProductList").show();
        loadProductByGroup();     
	});
    //link-delete-category
    $(document).on('click','.lik-group-delete',function(){
        if(confirm("Are you sure you want to delete item?") == true){
           var itemId = $(this).attr('item-id');        
    		var data={'action':'deleteGroup', 'itemId':itemId, 'secure_key':secure_key};
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
                    if(response.status == '1'){
                        loadGroupByModule();
                    }								
        		}		
        	}); 
        }
	});
    
    $(document).on('click','.lik-module-delete',function(){
        if(confirm("Are you sure you want to delete item?") == true){
           var itemId = $(this).attr('item-id');        
    		var data={'action':'deleteModule', 'itemId':itemId, 'secure_key':secure_key};
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
                    if(response.status == '1'){
                        window.location.reload();
                    }								
        		}		
        	}); 
        }
	});    
    // link-edit-category
    $(document).on('click','.lik-group-edit',function(){
		var itemId = $(this).attr('item-id');        
		var data={'action':'loadGroup', 'itemId':itemId, 'secure_key':secure_key};
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
    				$("#frmGroup").html(response.form);
    				showModal('groupModal');
    				groupIconUploadConfig();
    				//groupIconActiveUploadConfig();    				
    			}else{
    				showSuccessMessage(data.message);
    			}
    			            											
    		}		
    	});		
	});
    // upload image
	new AjaxUpload($('#module-banner-uploader'), {
		action: baseModuleUrl+"/FlexibleBrandUploader.php",
		name: 'uploadimage',
		data:{'secure_key':secure_key, 'typeUpload':'banner', 'langId':$("#moduleLangActive").val()},
		responseType: 'json',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	showSuccessMessage('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){			
			if(response.status == '1'){
				var langId = $("#moduleLangActive").val();
				var html = '<tr><td><div style="width: 100px"><img class="img-responsive" src="'+response.livePath+'temps/'+response.fileName+'" /></div></td>';
				html += '<td><input type="text" name="bannerNames'+langId+'[]" value="'+response.fileName+'" class="form-control" /></td>';
				html += '<td><input type="text" name="bannerLink'+langId+'[]" value="" class="form-control"  /></td>';
				html += '<td><input type="text" name="bannerAlt'+langId+'[]" value="" class="form-control"  /></td>';
				html += '<td class="pointer dragHandle center" ><div class="dragGroup"><a href="javascript:void(0)" class="lik-banner-del color-red" title="Delete banner">Del</a></div></td></tr>';
				$('#tbl-banners-lang-'+langId+" >tbody").append(html);
				tableBannerOrder();	
			}else{
				showSuccessMessage(response.msg);
			}
		}
	});
	// upload image
	new AjaxUpload($('#group-icon'), {
		action: baseModuleUrl+"/FlexibleBrandUploader.php",
		name: 'uploadimage',
		data:{'secure_key':secure_key, 'typeUpload':'icon', 'langId':$("#groupLangActive").val()},
		responseType: 'json',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	showSuccessMessage('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){			
			if(response.status == '1'){
				$("#category-icon").val(response.fileName);
			}else{
				showSuccessMessage(response.msg);
			}
		}
	});
	new AjaxUpload($('#group-iconActive'), {
		action: baseModuleUrl+"/FlexibleBrandUploader.php",
		name: 'uploadimage',
		data:{'secure_key':secure_key, 'typeUpload':'icon', 'langId':$("#groupLangActive").val()},
		responseType: 'json',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	showSuccessMessage('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){			
			if(response.status == '1'){
				$("#category-iconActive").val(response.fileName);
			}else{
				showSuccessMessage(response.msg);
			}
		}
	});		
});
function groupChangeType(value){
	if(value == 'auto'){
		$(".type-auto").show();
	}else{
		$(".type-auto").hide();
	}
}
function loadProductByGroup(){
    if(moduleId != '0' && groupId != '0'){
        var data={'action':'loadProductByGroup', 'moduleId':moduleId, 'groupId':groupId, 'secure_key':secure_key};
        $.ajax({
    		type:'POST',
    		url: baseModuleUrl + '/backend.ajax.php',
    		data: data,
    		dataType:'json',
    		cache:false,
    		async: true,
    		beforeSend: function(){},
    		complete: function(){},
    		success: function(data){
                $("#productList >tbody").html(data.content);
                productListSetup();                   
                //$("#manual-paginations").html(data.pagination);
                //if(categoryType =='manual') productListSetup();								
    		}		
    	});   
    }    
}
function moduleListSetup(){
    $("#modList").tableDnD({
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
                    ids[i] = tr.replace("mod_", ""); 
                }
    			var data={'action':'updateModuleOrdering', 'ids':ids, 'secure_key':secure_key};
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
                        showSuccessMessage(response);
                        window.location.reload();											
            		}		
            	});
            }              		         
		}        
	});
}
function groupListSetup(){
    $("#groupList").tableDnD({
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
                    ids[i] = tr.replace("gro_", ""); 
                }
    			var data={'action':'updateGroupOrdering', 'ids':ids, 'secure_key':secure_key};
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
                        showSuccessMessage(response);
                        loadGroupByModule();											
            		}		
            	});
            }              		         
		}        
	});
}
function productListSetup(){
	if(moduleId !='0' && groupId != '0' && groupType == 'manual'){
		$("#productList").tableDnD({
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
	                    ids[i] = tr.replace("ptr_", ""); 
	                }
	    			var data={'action':'updateProductOrdering', 'moduleId':moduleId, 'groupId':groupId, 'ids':ids, 'secure_key':secure_key};
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
	                        showSuccessMessage(response);
	                        loadProductByGroup();									
	            		}		
	            	});
	            }              		         
			}        
		});	
	}
    
}
function loadGroupByModule(){
    var data={'action':'loadGroupByModule', 'moduleId':moduleId, 'secure_key':secure_key};
    $.ajax({
		type:'POST',
		url: baseModuleUrl + '/backend.ajax.php',
		data: data,
		dataType:'json',
		cache:false,
		async: true,
		beforeSend: function(){$("#mainProductList").hide();},
		complete: function(){},
		success: function(response){
		  $("#groupList >tbody").html(response.content);		  
            if(response.status = '1'){                
                groupListSetup();
            }else {
                showSuccessMessage(response.msg);                
            }								
		}		
	});
}
function moduleBannerUploadConfig(){
	new AjaxUpload($('#module-banner-uploader'), {
		action: baseModuleUrl+"/FlexibleBrandUploader.php",
		name: 'uploadimage',
		data:{'secure_key':secure_key, 'typeUpload':'banner', 'langId':$("#moduleLangActive").val()},
		responseType: 'json',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	showSuccessMessage('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){			
			if(response.status == '1'){
				var langId = $("#moduleLangActive").val();
				var html = '<tr><td><div style="width: 100px"><img class="img-responsive" src="'+response.livePath+'temps/'+response.fileName+'" /></div></td>';
				html += '<td><input type="text" name="bannerNames'+langId+'[]" value="'+response.fileName+'" class="form-control" /></td>';
				html += '<td><input type="text" name="bannerLink'+langId+'[]" value="" class="form-control"  /></td>';
				html += '<td><input type="text" name="bannerAlt'+langId+'[]" value="" class="form-control"  /></td>';
				html += '<td class="pointer dragHandle center" ><div class="dragGroup"><a href="javascript:void(0)" class="lik-banner-del color-red" title="Delete banner">Del</a></div></td></tr>';
				$('#tbl-banners-lang-'+langId+" >tbody").append(html);	
				tableBannerOrder();
			}else{
				showSuccessMessage(response.msg);
			}
		}
	});
}
function groupIconUploadConfig(){
	new AjaxUpload($('#group-icon'), {
		action: baseModuleUrl+"/FlexibleBrandUploader.php",
		name: 'uploadimage',
		data:{'secure_key':secure_key, 'typeUpload':'icon', 'langId':$("#groupLangActive").val()},
		responseType: 'json',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	showSuccessMessage('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){			
			if(response.status == '1'){
				$("#category-icon").val(response.fileName);
			}else{
				showSuccessMessage(response.msg);
			}
		}
	});
	new AjaxUpload($('#group-iconActive'), {
		action: baseModuleUrl+"/FlexibleBrandUploader.php",
		name: 'uploadimage',
		data:{'secure_key':secure_key, 'typeUpload':'icon', 'langId':$("#groupLangActive").val()},
		responseType: 'json',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	showSuccessMessage('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){			
			if(response.status == '1'){
				$("#category-iconActive").val(response.fileName);
			}else{
				showSuccessMessage(response.msg);
			}
		}
	});
}

function saveModule(){    
    $("#addModal .modal-footer").append('<p class="ajax-loader"><i class="fa fa-spinner fa-spin"></i></p>');
    var data = $('form#frmModule').serializeObject();    
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
            if(response.status == '1') window.location.reload();
		}		
	});
}
function saveGroup(){
    if(moduleId != '0'){
        $("#groupModal .modal-footer").append('<p class="ajax-loader"><i class="fa fa-spinner fa-spin"></i></p>');
    	var data = $('form#frmGroup').serializeObject();
    	data.moduleId = moduleId;    	
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
    		      $("p.ajax-loader").remove();
                showSuccessMessage(response.msg);
                if(response.status == '1'){                    
                	loadGroupByModule();
                	$('#groupModal').modal('hide');
                }
    		}		
    	});
    }else{
        showSuccessMessage("You need to choose one module, please!");
    }    
}
function openProductsModal(){
    if(groupType == 'manual'){
        showModal('productsModal');
        loadListProducts('1');
    }else return false;
}
function loadListProducts(page){
    productPage = page;
    var keyword = $("#keyword").val();
    var data={'action':'loadListProducts', 'groupId':groupId, 'moduleId':moduleId, 'page':page, 'keyword':keyword, 'secure_key':secure_key};
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
            $("#allProductList-pagination").html(response.pagination);
            $("#allProductList >tbody").html(response.list);										
		}		
	});
}