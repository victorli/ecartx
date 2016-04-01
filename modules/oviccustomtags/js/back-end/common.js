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
    groupListSetup();
    groupFormNew = $("#frmGroup").html();
    tagFromNew = $("#frmTag").html();
    setStyle();
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
function goToElement(eId, offset){
	$("html, body").animate({ scrollTop: $('#'+eId).offset().top-offset}, 1000);
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
                    ids[i] = tr.replace("gr_", ""); 
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
            			showSuccessMessage(response.msg);
            			loadAllGroups();										
            		}		
            	});
            }              		         
		}        
	});
}
function tagListSetup(){
    $("#tagList").tableDnD({
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
                    ids[i] = tr.replace("tg_", ""); 
                }
    			var data={'action':'updateTagOrdering', 'ids':ids, 'secure_key':secure_key};
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
                        loadTagByGroup();
            		}		
            	});
            }              		         
		}        
	});
}


jQuery(function($){
    $('#modalGroup').on('hidden.bs.modal', function (e) {
    	$("#frmGroup").html(groupFormNew);    	        
		setStyle();
        $("p.ajax-loader").remove();
    });    
    $('#modalTag').on('hidden.bs.modal', function (e) {
    	$("#frmTag").html(tagFromNew);
        $("p.ajax-loader").remove();
	});  
    $(document).on('click','.lik-tag-status',function(){        
            var itemId = $(this).attr('item-id');
            var value = $(this).attr('value');        
            if(value == '1'){
            	$(this).attr('value', '0').removeClass('action-enabled').addClass('action-disabled');
            }else{
            	$(this).attr('value', '1').removeClass('action-disabled').addClass('action-enabled');
            }
    		var data={'action':'changTagStatus', 'itemId':itemId, 'value':value, 'secure_key':secure_key};
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
    		var data={'action':'changGroupStatus', 'itemId':itemId, 'value':value, 'secure_key':secure_key};
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
                    showModal('modalGroup', '');
                    setStyle();
                }else{
                	showSuccessMessage(response.msg);
                }								
    		}		
    	});	
	});
	$(document).on('click','.lik-tag-edit',function(){        
            var itemId = $(this).attr('item-id');            
    		var data={'action':'loadTag', 'itemId':itemId, 'secure_key':secure_key};
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
                        $("#frmTag").html(response.form);              
                        showModal('modalTag', '');
                        setStyle();
                    }else{
                    	showSuccessMessage(response.msg);
                    }
                    									
        		}		
        	});    
        
				
	});
    
    
    $(document).on('click','.lik-tag-delete',function(){
        if(confirm("Are you sure you want to delete item?") == true){
           var itemId = $(this).attr('item-id');        
    		var data={'action':'deleteTag', 'itemId':itemId, 'secure_key':secure_key};
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
                    if(response.status == '1') loadTagByGroup();                    								
        		}		
        	}); 
        }
	});
    // delete item
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
        		beforeSend: function(){
                    	
        		},
        		complete: function(){},
        		success: function(response){                                        
                    showSuccessMessage(response.msg);
                    if(response.status == '1') loadAllGroups();                    								
        		}		
        	}); 
        }
	});
    
    $(document).on('click','.lik-group',function(){                
        if(groupId != '0'){
            $("#gr_"+groupId).removeClass('tr-selected');            
        }
        groupId = $(this).attr('item-id');
        $("#gr_"+groupId).addClass('tr-selected');
        $("#span-group-name").html('['+($(this).html())+']');
        $("#panel-tag-list").show();
        loadTagByGroup();
        
	});
    
    
    
     	
});

function loadTagByGroup(){
    var data={'action':'loadTagsByGroup', 'groupId':groupId, 'secure_key':secure_key};
    $.ajax({
		type:'POST',
		url: baseModuleUrl + '/backend.ajax.php',
		data: data,
		dataType:'json',
		cache:false,
		async: true,
		beforeSend: function(){},
		complete: function(){},
		success: function(resopnse){
			if(resopnse.status == '0') showSuccessMessage(resopnse.msg);
			$("#tagList >tbody").html(resopnse.content);                        
		  	tagListSetup();    
            							
		}		
	});
}
function openAddItemModal(){
    if(groupId != '0'){
        showModal('addItemModal');
    }else{
        alert("You need to choose one group, please!");
    }
    return false;
}  
function showModal(newModal, oldModal){
	if(oldModal != "") $("#"+oldModal).modal('hide');
	$("#"+newModal).modal('show');
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








// load item by lang
function tagChangeLanguage(langId){
    var oldLang = $("#tagLangActive").val(); 
	$("#tagLangActive").val(langId);	
	$(".tag-lang").each(function() {
		$(this).val(langId);        
    });
    $(".tag-lang-"+oldLang).hide();
    $(".tag-lang-"+langId).show();
}
// Save group
function saveGroup(){    
    $("#frmGroup .modal-footer").append('<p class="ajax-loader"><i class="fa fa-spinner fa-spin"></i></p>');
    var data = $('form#frmGroup').serializeObject();
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
            	loadAllGroups();
            	$('#modalGroup').modal('hide');
            }
		}		
	});
}
function loadAllGroups(){
	var data={'action':'loadAllGroups', 'secure_key':secure_key};
	$.ajax({
		type:'POST',
		url: baseModuleUrl + '/backend.ajax.php',
		data: data,
		dataType:'json',
		cache:false,
		async: true,
		beforeSend: function(){
			$("#div-tag-list").hide();
		},
		complete: function(){},
		success: function(response){
            if(response.status = '1'){
            	$("#groupList >tbody").html(response.content);
		  		groupListSetup();    
            }
		}		
	});
}

// Save item
function saveTag(){
    if(groupId != '0'){
        $("#frmTag .modal-footer").append('<p class="ajax-loader"><i class="fa fa-spinner fa-spin"></i></p>');
        var data = $('form#frmTag').serializeObject();
        data.groupId = groupId;
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
                	loadTagByGroup();
                	$('#modalTag').modal('hide');	
                }                 
    		}
    	});
    }else{
    	showSuccessMessage('You need to choose one group, please!');
    }    
}
