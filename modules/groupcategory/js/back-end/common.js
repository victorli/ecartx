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
function clearCache(){                
    var data={'action':'clearCache', 'secure_key':secure_key};
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
            showSuccessMessage(response);	
		}		
	});
}
$(document).ready(function(){
    $("#list-group a").click(function(e){
        $("#list-group").find('a').removeClass('active');
        $(this).addClass('active');
    	e.preventDefault();
    	$(this).tab('show');
    });
    $("#tab-groups a").click(function(e){
        $("#tab-groups").find('a').removeClass('active');
        $(this).addClass('active');
    	e.preventDefault();
    	$(this).tab('show');
    });
    groupListSetup();
    groupFormNew_config = $("#group-config").html();
    groupFormNew_products = $("#group-products-config").html();
    //groupFormNew = $("#frmGroup").html();
    itemFormNew = $("#frmItem").html();
});
function setStyle(){
	if($(".mColorPicker").length >0){
		$(".mColorPicker").each(function(index) {
		  var value = $(this).val();
		  	if(value != "") $(this).css({'background':value});
		  	else $(this).css({'background':'#ffffff'});
		});
	}
}
jQuery(function($){    
	if($(".ul-sortable").length >0 ) $(".ul-sortable" ).sortable({
		placeholder: "ui-state-highlight"
	});
	
    $('#modalGroup').on('hidden.bs.modal', function (e) {
        $("#group-config").html(groupFormNew_config);
        $("#group-products-config").html(groupFormNew_products);        
    
        $("p.ajax-loader").remove();
        groupIconUploader();
        groupImageUploader();
        if($(".ul-sortable").length >0 ) $(".ul-sortable" ).sortable({
			placeholder: "ui-state-highlight"
		});        
    });
    $('#modalGroup').on('shown.bs.modal', function () {
	  ext = 'group';
	});
    $('#modalItem').on('hidden.bs.modal', function (e) {       	 	
    	$("#frmItem").html(itemFormNew);
        $("p.ajax-loader").remove();
        itemImageUploader();  
        if($(".ul-sortable").length >0 ) $(".ul-sortable" ).sortable({
			placeholder: "ui-state-highlight"
		});       
    });
    $('#modalItem').on('shown.bs.modal', function () {
	  ext = 'item';
	});
    $('#modalStyle').on('hidden.bs.modal', function (e) {    	
    	$("#style-id").val("0");
        $("#style-name").val("");
        $("#color_background_header").val("");
        $("#color_background_type").val("");
        $("#color_list").val("");
        $("#color_banner_from").val("");
        $("#color_banner_to").val("");  
		setStyle();
    }); 
    // edit style
    $(document).on('click','.lik-style-edit',function(){
        var itemId = $(this).attr('item-id');        
        var data={'action':'styleEdit', 'itemId':itemId, 'secure_key':secure_key};
        $.ajax({
    		type:'POST',
    		url: currentUrl,
    		data: data,
    		dataType:'json',
    		cache:false,
    		async: true,
    		beforeSend: function(){
                showModal('modalStyle', '');
    		},
    		complete: function(){},
    		success: function(res){                    
                if(res != null){
                    if(res.status == "1"){
                        if(res.id) $("#style-id").val(res.id);
                        if(res.name) $("#style-name").val(res.name);
                        if(res.backgroundColorHeader) $("#color_background_header").val(res.backgroundColorHeader);
                        if(res.colorBackgroundType) $("#color_background_type").val(res.colorBackgroundType);
                        if(res.colorList) $("#color_list").val(res.colorList);
                        if(res.bannerColorFrom) $("#color_banner_from").val(res.bannerColorFrom);
                        if(res.bannerColorTo) $("#color_banner_to").val(res.bannerColorTo);
                        setStyle();                        
                    }else{
                        showSuccessMessage("Item not found!");
                    }
                }  								
    		}		
    	});         
	});
    // delete style
    $(document).on('click','.lik-style-delete',function(){
        if(confirm("Are you sure you want to delete item?") == true){
           var itemId = $(this).attr('item-id');        
    		var data={'action':'deleteStyle', 'itemId':itemId, 'secure_key':secure_key};
            $.ajax({
        		type:'POST',
        		url: currentUrl,
        		data: data,
        		dataType:'json',
        		cache:false,
        		async: true,
        		beforeSend: function(){},
        		complete: function(){},
        		success: function(response){
                    showSuccessMessage(response.msg);
                    loadAllStyle();		
        		}		
        	}); 
        }
	});
    
    // edit group
    $(document).on('click','.lik-group-edit',function(){
        var itemId = $(this).attr('item-id');        
        var data={'action':'loadGroup', 'itemId':itemId, 'secure_key':secure_key};
        $.ajax({
    		type:'POST',
    		url: currentUrl,
    		data: data,
    		dataType:'json',
    		cache:false,
    		async: true,
    		beforeSend: function(){
                showModal('modalGroup', '');
    		},
    		complete: function(){},
    		success: function(response){ 
    			if(response != null){
                    if(response.status == "1"){
						$("#group-config").html(response.group_config);
						$("#group-products-config").html(response.product_config);
						groupIconUploader();
						groupImageUploader();												                        	
                        if($(".ul-sortable").length >0 ) $(".ul-sortable" ).sortable({
							placeholder: "ui-state-highlight"
						});
						showModal('modalGroup', '');					
                    }else{
                        showSuccessMessage(response.msg);
                    }
                } 							
    		}		
    	});         
	});
    $(document).on('click','.lik-group-delete',function(){
        if(confirm("Are you sure you want to delete item?") == true){
            var itemId = $(this).attr('item-id');        
    		var data={'action':'deleteGroup', 'itemId':itemId, 'secure_key':secure_key};
            $.ajax({
        		type:'POST',
        		url: currentUrl,
        		data: data,
        		dataType:'json',
        		cache:false,
        		async: true,
        		beforeSend: function(){},
        		complete: function(){},
        		success: function(response){
                    if(response){             
                        showSuccessMessage(response.msg);
                        if(response.status == '1') loadAllGroup();
                    }                											
        		}		
        	});    
        }
				
	}); 
    
    $(document).on('click','.cat-group',function(){        
        var itemId = $(this).attr('item-id');
        if(catGroupId != itemId){
	        if(catGroupId != '0'){
	            $("#gr_"+catGroupId).removeClass('tr-selected');            
	        }
	        catGroupId = itemId;
	        $("#gr_"+catGroupId).addClass('tr-selected');
	        $("#span-group-name").html('['+($(this).html())+']');
	        $("#panel-list-item").show();
	        //goToElement('categoriesList', 100);
	        loadItemsByGroup();	
        }                
	});
    
    // get item
    $(document).on('click','.lik-item-edit',function(){
		var itemId = $(this).attr('item-id');        
		var data={'action':'loadItem', 'itemId':itemId, 'groupId':catGroupId, 'secure_key':secure_key};
        $.ajax({
    		type:'POST',
    		url: currentUrl,
    		data: data,
    		dataType:'json',
    		cache:false,
    		async: true,
    		beforeSend: function(){
                showModal('modalItem', '');                	
    		},
    		complete: function(){},
    		success: function(response){
                if(response){                	
                	$("#frmItem").html(response.form);
                	showModal('modalItem', '');
                	itemImageUploader();
                }else{
                	showSuccessMessage(response.msg);
                }              											
    		}		
    	});		
	});
    $(document).on('click','.lik-item-delete',function(){
        if(confirm("Are you sure you want to delete item?") == true){
            var itemId = $(this).attr('item-id');        
    		var data={'action':'deleteItem', 'itemId':itemId, 'secure_key':secure_key};
            $.ajax({
        		type:'POST',
        		url: currentUrl,
        		data: data,
        		dataType:'json',
        		cache:false,
        		async: true,
        		beforeSend: function(){},
        		complete: function(){},
        		success: function(response){
                    if(response){             
                        showSuccessMessage(response.msg);
                        if(response.status == '1') loadItemsByGroup();
                    }                											
        		}		
        	});    
        }
				
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
        		url: currentUrl,
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
	$(document).on('click','.lik-item-status',function(){        
            var itemId = $(this).attr('item-id');
            var value = $(this).attr('value');        
            if(value == '1'){
            	$(this).attr('value', '0').removeClass('action-enabled').addClass('action-disabled');
            }else{
            	$(this).attr('value', '1').removeClass('action-disabled').addClass('action-enabled');
            }
    		var data={'action':'changItemStatus', 'itemId':itemId, 'value':value, 'secure_key':secure_key};
            $.ajax({
        		type:'POST',
        		url: currentUrl,
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
	  
    // upload image
    //var btnImageUpload=$('#image-uploader');
	
    new AjaxUpload($('#image-uploader'), {	   
		action: baseModuleUrl+"/GroupCategoryUploadImage.php",
		name: 'uploadimage',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	alert('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){	
					
			var activeLang = $("#groupLangActive").val();
			$('#groupBanner-'+activeLang).val(response);	
		}
	});
    new AjaxUpload($('#icon-uploader'), {	   
		action: baseModuleUrl+"/GroupCategoryUploadImage.php",
		name: 'uploadimage',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	alert('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){			
			$('#group-icon').val(response);		
		}
	});	
    new AjaxUpload($('#item-image-uploader'), {	   
		action: baseModuleUrl+"/GroupCategoryUploadImage.php",
		name: 'uploadimage',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	alert('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){			
			var activeLang = $("#itemLangActive").val();		
			$('#itemBanner-'+activeLang).val(response);		
		}
	});
	/*
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
    */
});

function groupImageUploader(){
	new AjaxUpload($('#image-uploader'), {	   
		action: baseModuleUrl+"/GroupCategoryUploadImage.php",
		name: 'uploadimage',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	alert('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){
			var activeLang = $("#groupLangActive").val();
			$('#groupBanner-'+activeLang).val(response);		
		}
	});
}
function groupIconUploader(){
	new AjaxUpload($('#icon-uploader'), {	   
		action: baseModuleUrl+"/GroupCategoryUploadImage.php",
		name: 'uploadimage',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	alert('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){			
			$('#group-icon').val(response);		
		}
	});
}

function itemImageUploader(){
	new AjaxUpload($('#item-image-uploader'), {	   
		action: baseModuleUrl+"/GroupCategoryUploadImage.php",
		name: 'uploadimage',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	alert('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){	
			var activeLang = $("#itemLangActive").val();		
			$('#itemBanner-'+activeLang).val(response);	
		}
	});
}
// save style
function saveStyle(){
    var styleId = $("#style-id").val();
    var styleName = $("#style-name").val();
    if(styleName == ""){
        alert("Enter the item title, please!");
        return false;
    }    
    var backgroundColorHeader = $("#color_background_header").val();
    var colorBackgroundType = $("#color_background_type").val();
    var colorList = $("#color_list").val();
    var bannerColorFrom = $("#color_banner_from").val();
    var bannerColorTo = $("#color_banner_to").val();
    var data={'action':'saveStyle', 'styleId':styleId, 'styleName':styleName.escapeSpecialChars(), 'backgroundColorHeader':backgroundColorHeader, 'colorBackgroundType':colorBackgroundType, 'colorList':colorList, 'bannerColorFrom':bannerColorFrom, 'bannerColorTo':bannerColorTo,'secure_key':secure_key};
    $.ajax({
		type:'POST',
		url: currentUrl,
		data: data,
		dataType:'json',
		cache:false,
		async: true,
		beforeSend: function(){
		},
		complete: function(){ 					
		},
		success: function(response){
            if(styleId == "0"){
                $("#style-name").val("");                    
            }
            showSuccessMessage(response.msg);
            loadAllStyle();
            if(response.status == "1") $('#modalStyle').modal('hide');									
		}
	});
}
// Load all style
function loadAllStyle(){
    var data={'action':'loadAllStyle', 'secure_key':secure_key};
    $.ajax({
		type:'POST',
		url: currentUrl,
		data: data,
		dataType:'json',
		cache:false,
		async: true,
		beforeSend: function(){
		},
		complete: function(){ 					
		},
		success: function(response){
            $("#styleList > tbody").html(response.data);
            $("#styleId").html(response.styleOptions);									
		}
	});
}

// save group
function saveGroup(){
    $("#modalGroup .modal-footer").append('<p class="ajax-loader"><i class="fa fa-spinner fa-spin"></i></p>');
	var data = $('form#frmGroup').serializeObject();	
    $.ajax({
		type:'POST',
		url: currentUrl,
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
            loadAllGroup();
            $('#modalGroup').modal('hide');
            									
		}
	});
}
// load group by lang
function groupChangeLanguage(langId){
	var oldLang = $("#groupLangActive").val(); 
	$("#groupLangActive").val(langId);	
	$(".group-lang").each(function() {
		$(this).val(langId);        
    });
    $(".group-lang-"+oldLang).hide();
    $(".group-lang-"+langId).show();
    
}
// Load all group
function loadAllGroup(){
    var data={'action':'loadAllGroup', 'secure_key':secure_key};
    $.ajax({
		type:'POST',
		url: currentUrl,
		data: data,
		dataType:'json',
		cache:false,
		async: true,
		beforeSend: function(){
			$("#panel-list-item").hide();
		},
		complete: function(){ 					
		},
		success: function(response){
            $("#groupList > tbody").html(response.data);
            groupListSetup();	
            								
		}
	});
}
// set table order
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
                    ids[i] = tr.replace("gr_", ""); 
                }
    			var data={'action':'updateGroupOrdering', 'ids':ids, 'secure_key':secure_key};
                $.ajax({
            		type:'POST',
            		url: currentUrl,
            		data: data,
            		dataType:'json',
            		cache:false,
            		async: true,
            		beforeSend: function(){
            		},
            		complete: function(){ 					
            		},
            		success: function(response){
                        showSuccessMessage(response);
                        loadAllGroup();											
            		}		
            	});
            }              		         
		}        
	});
}
// Save item
function saveItem(){
    if(catGroupId != '0'){
        $("#modalItem .modal-footer").append('<p class="ajax-loader"><i class="fa fa-spinner fa-spin"></i></p>');
    	var data = $('form#frmItem').serializeObject();
    	data.groupId = catGroupId;    	
        $.ajax({
    		type:'POST',
    		url: currentUrl,
    		data: data,
    		dataType:'json',
    		cache:false,
    		async: true,
    		beforeSend: function(){},
    		complete: function(){},
    		success: function(response){  
    		  $("p.ajax-loader").remove();
                showSuccessMessage(response.msg);                
                
                if(response.status == "1"){                	
                	$('#modalItem').modal('hide');
                	loadItemsByGroup();
                } 
    		}
    	});
    }else{
        showSuccessMessage("You need to choose one group, please!");
    }    
}
function goToElement(eId, offset){
	$("html, body").animate({ scrollTop: $('#'+eId).offset().top-offset}, 1000);
}
function itemListSetup(){
    $("#itemList").tableDnD({
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
                    ids[i] = tr.replace("it_", ""); 
                }
    			var data={'action':'updateItemOrdering', 'ids':ids, 'secure_key':secure_key};
                $.ajax({
            		type:'POST',
            		url: currentUrl,
            		data: data,
            		dataType:'json',
            		cache:false,
            		async: true,
            		beforeSend: function(){
            		},
            		complete: function(){ 					
            		},
            		success: function(response){
                        showSuccessMessage(response);
                        loadItemsByGroup();                        										
            		}		
            	});
            }              		         
		}        
	});
}
function loadItemsByGroup(){
    var data={'action':'loadItemsByGroup', 'groupId':catGroupId, 'secure_key':secure_key};
    $.ajax({
		type:'POST',
		url: currentUrl,
		data: data,
		dataType:'json',
		cache:false,
		async: true,
		beforeSend: function(){
            	
		},
		complete: function(){},
		success: function(response){
		  $("#itemList >tbody").html(response.content);
          $("#itemCategoryId").html(response.categoryOptions);          
            if(response.status = '1'){
                itemListSetup();
                if(response.msg != "") showSuccessMessage(response.msg);
            }else {
                showSuccessMessage(response.msg);                
            }								
		}		
	});
}
function openAddItemModal(){
    if(catGroupId != '0'){
    	var data={'action':'loadItem', 'itemId':'0', 'groupId':catGroupId, 'secure_key':secure_key};
        $.ajax({
    		type:'POST',
    		url: currentUrl,
    		data: data,
    		dataType:'json',
    		cache:false,
    		async: true,
    		beforeSend: function(){
                                	
    		},
    		complete: function(){},
    		success: function(response){
                if(response){                	
                	$("#frmItem").html(response.form);
                	showModal('modalItem', '');
                	itemImageUploader();
                	if($(".ul-sortable").length >0 ) $(".ul-sortable" ).sortable({
						placeholder: "ui-state-highlight"
					});
                }else{
                	showSuccessMessage(response.msg);
                }									
    		}		
    	});	
        
    }else{
    	showSuccessMessage("You need to choose one group, please!");
    }
    return false;
}  
function showModal(newModal, oldModal){
    
	if(oldModal != "") $("#"+oldModal).modal('hide');
	$("#"+newModal).modal('show');
}
// load item by lang
function itemChangeLanguage(langId){
    var oldLang = $("#itemLangActive").val(); 
	$("#itemLangActive").val(langId);	
	$(".item-lang").each(function() {
		$(this).val(langId);        
    });
    $(".item-lang-"+oldLang).hide();
    $(".item-lang-"+langId).show();
}
function groupChangeType(value){
	$(".group-type").hide();
	$(".group-type-"+value).show();	
}
function itemChangeType(value){
	$(".item-type").hide();
	$(".item-type-"+value).show();	
}



function force_authentication(){
    var data={'action':'force_authentication', 'secure_key':secure_key};   
    $.ajax({
		type:'POST',
		url: baseModuleUrl + '/ajax.php',
		data: data,
		dataType:'json',
		cache:false,
		async: false,
		beforeSend: function(){},
		complete: function(){},
		success: function(response){
            if(response == 'force_authentication') window.location.reload();                
		}
	});
}

