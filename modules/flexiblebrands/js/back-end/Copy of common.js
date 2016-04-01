$(document).ready(function(){
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
                    ids[i] = tr.replace("tr_", ""); 
                }
    			var data={'action':'updateOrdering', 'ids':ids}
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
                        window.location.reload();
                        //showSuccessMessage(response);
                        //alert(response);											
            		}		
            	});
            }              		         
		}        
	});
});
function goToElement(eId, offset){
	$("html, body").animate({ scrollTop: $('#'+eId).offset().top-offset}, 1000);
}
function categoriesListSetup(){
    $("#categoriesList").tableDnD({
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
                    ids[i] = tr.replace("ctr_", ""); 
                }
    			var data={'action':'updateCategoryOrdering', 'ids':ids}
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
                        showSuccessMessage(response);
                        //alert(response);
                        loadCategoriesByModule();											
            		}		
            	});
            }              		         
		}        
	});
}
function productListSetup(){
    $("#productList").tableDnD({
		onDragStart: function(table, row) {
			originalOrder = $.tableDnD.serialize();
		},
		dragHandle: 'dragHandle',
		onDragClass: 'myDragClass',
		onDrop: function(table, row) {
            if(moduleId != '0' && categoryId != '0' && categoryType == 'manual'){
                if (originalOrder != $.tableDnD.serialize()) {
                    var rows = table.tBodies[0].rows;
                    var ids = [];
                    for (var i=0; i<rows.length; i++) {
                        var tr = rows[i].id;                    
                        ids[i] = tr.replace("ptr_", ""); 
                    }
        			var data={'action':'updateProductOrdering', 'moduleId':moduleId, 'categoryId':categoryId, 'ids':ids}
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
                            
                            											
                		}		
                	});
                }
            }
                          		         
		}        
	});
}




jQuery(function($){        
    $('#addModal').on('hidden.bs.modal', function (e) {
        if(reload == true) window.location.reload(true);
        $("#itemId").val("");
        $("#module-title").val("");
        $("#banner").val("");
        $("#module_banner_description").val("");
        $("#module_banner_link").val("");
    })
    $('#categoryModal').on('hidden.bs.modal', function (e) {        
        if(loadCategory == true) loadCategoriesByModule();
        $("#catId").val('0');
        $("#category-title").val("");        
    })
    $('#productsModal').on('hidden.bs.modal', function (e) {
        loadProductsByModuleCategory(1);     
    })    
    
    $(document).on('click','.module-item',function(){        
        var itemId = $(this).attr('item-id');                
        if(moduleId != '0'){
            $("#tr_"+moduleId).removeClass('tr-selected');            
        }
        moduleId = itemId;
        $("#tr_"+moduleId).addClass('tr-selected');
        goToElement('categoriesList', 100);
        loadCategoriesByModule();     
	});
    //link-add-manual-product
    $(document).on('click','.link-add-manual-product',function(){        
        var itemId = $(this).attr('item-id');
        if(moduleId != '0' && categoryId != '0' && categoryType == 'manual'){
            var data={'action':'addManualProductItem', 'itemId':itemId, 'categoryId':categoryId, 'moduleId':moduleId}
            $.ajax({
        		type:'POST',
        		url: baseModuleUrl + '/ajax.php',
        		data: data,
        		dataType:'json',
        		cache:false,
        		async: true,
        		beforeSend: function(){},
        		complete: function(){},
        		success: function(data){
                    if(data.status == '1'){                        
                        //$("#pListTr_"+itemId).remove();                        
                        showSuccessMessage(data.message);
                        loadProductsByCategory(productPage);
                    }else {
                        showSuccessMessage(data.message);
                    }								
        		}		
        	}); 
        }
        /*
        
        
        
        
        manualProductIds[manualProductIndex] = itemId;
        manualProductIndex++;             
        $("#pListTr_"+itemId).remove();
        */
	});
    
    //link-delete-product
    $(document).on('click','.link-delete-product',function(){
        if(confirm('Are you sure you want to delete item?') == true){
            var itemId = $(this).attr('item-id');
            if(moduleId != '0' && categoryId != '0' && categoryType == 'manual'){                                
                var data={'action':'deleteManualProductItem', 'itemId':itemId, 'categoryId':categoryId, 'moduleId':moduleId}
                $.ajax({
            		type:'POST',
            		url: baseModuleUrl + '/ajax.php',
            		data: data,
            		dataType:'json',
            		cache:false,
            		async: true,
            		beforeSend: function(){},
            		complete: function(){},
            		success: function(data){
                        if(data == 'ok'){
                            showSuccessMessage('Delete product success!');
                            loadProductsByModuleCategory(manualProductPage);
                        }else {
                            showSuccessMessage(data);
                        }								
            		}		
            	}); 
            }  
        }
	});
    //category-item
    $(document).on('click','.category-item',function(){        
        var itemId = $(this).attr('item-id');
        var itemType = $(this).attr('item-type');                
        if(categoryId != '0'){
            $("#ctr_"+categoryId).removeClass('tr-selected');            
        }
        categoryId = itemId;
        categoryType = itemType;
        $("#ctr_"+categoryId).addClass('tr-selected');
        loadProductsByModuleCategory('1');     
	});
    //link-delete-category
    $(document).on('click','.link-delete-category',function(){
        if(confirm("Are you sure you want to delete item?") == true){
           var itemId = $(this).attr('item-id');        
    		var data={'action':'deleteModuleCategoryItem', 'itemId':itemId}
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
        		success: function(data){
                    if(data == 'ok'){
                        loadCategoriesByModule();
                    }else {
                        showSuccessMessage(data);
                        //alert(data);
                    }								
        		}		
        	}); 
        }
	});
    
    $(document).on('click','.link-delete',function(){
        if(confirm("Are you sure you want to delete item?") == true){
           var itemId = $(this).attr('item-id');        
    		var data={'action':'deleteModuleItem', 'itemId':itemId}
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
        		success: function(data){
                    if(data == 'ok'){
                        window.location.reload();
                    }else {
                        showSuccessMessage(data);
                    }								
        		}		
        	}); 
        }
	});
    $(document).on('click','.link-edit',function(){
        var moduleId = $("#moduleId").val();
		var itemId = $(this).attr('item-id');        
		var data={'action':'getModuleItem', 'itemId':itemId}
        $.ajax({
    		type:'POST',
    		url: baseModuleUrl + '/ajax.php',
    		data: data,
    		dataType:'json',
    		cache:false,
    		async: true,
    		beforeSend: function(){
                showModal('addModal', '');
                $("#module-position option").remove();
                $("#module-only option").remove();
                $("#module-order-value option").remove();
                $("#module-order-type option").remove();
                $("#module-lang option").remove();
                $("#module-layout option").remove();	
    		},
    		complete: function(){},
    		success: function(data){
                if(data){
                    $("#itemId").val(data.id);
                    $("#module-title").val(data.module_title);
                    $("#banner").val(data.module_banner);
                    $("#module_banner_description").val(data.module_banner_description);
                    $("#module_banner_link").val(data.module_banner_link);
                    
                    $('#module-position').append(data.positionOptions);
                    $('#module-only').append(data.displayOptions);
                    $('#module-order-value').append(data.orderValueOptions);
                    $('#module-order-type').append(data.orderTypeOptions);
                    $('#module-lang').append(data.langOptions);
                    $('#module-layout').append(data.moduleLayout);
                    
                    $("#div-status").html(data.status);
                    $("#module-ordering").val(data.ordering)
                    $("#note").val(data.note)
                }                											
    		}		
    	});
		
	});	
    
    // link-edit-category
    $(document).on('click','.link-edit-category',function(){
		var itemId = $(this).attr('item-id');        
		var data={'action':'getModuleCategoryItem', 'itemId':itemId}
        $.ajax({
    		type:'POST',
    		url: baseModuleUrl + '/ajax.php',
    		data: data,
    		dataType:'json',
    		cache:false,
    		async: true,
    		beforeSend: function(){
                showModal('categoryModal', '');
                $("#category-lang option").remove();
                $("#category-type option").remove();
                $("#category-id option").remove();	
    		},
    		complete: function(){},
    		success: function(data){
                if(data){
                    $("#catId").val(data.id);
                    $("#category-title").val(data.title);
                    $('#category-lang').append(data.langOptions);
                    $('#category-type').append(data.categoryTypeOptions);
                    $('#category-id').append(data.categoryOptions);                    
                    $("#count-product").val(data.productCount);
                    $("#category-ordering").val(data.ordering);
                    $("#category-icon").val(data.icon);
                    $("#category-status").html(data.status);
                    
                }                											
    		}		
    	});
		
	});
    // upload image
    //var btnImageUpload=$('#image-uploader');
	new AjaxUpload($('#image-uploader'), {
	   
		action: baseModuleUrl+"/uploadUserAvatar.php",
		name: 'uploadimage',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	alert('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){			
			$('#banner').val(response);					
						
		}
	});
    
    new AjaxUpload($('#category-icon-uploader'), {	   
		action: baseModuleUrl+"/uploadUserAvatar.php",
		name: 'uploadimage',
		onChange: function(file, ext){},
		onSubmit: function(file, ext){					
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			 	alert('You just upload files (jpg, png,jpeg,gif)');
				return false;
			}
		},
		onComplete: function(file, response){			
			$('#category-icon').val(response);					
						
		}
	});
    	 	
});
var manualProductPage = 1;
function loadProductsByModuleCategory(page){
    if(moduleId != '0' && categoryId != '0'){
        manualProductPage = page;
        var data={'action':'loadProductsByModuleCategory', 'moduleId':moduleId, 'categoryId':categoryId, 'page':page}
        $.ajax({
    		type:'POST',
    		url: baseModuleUrl + '/ajax.php',
    		data: data,
    		dataType:'json',
    		cache:false,
    		async: true,
    		beforeSend: function(){},
    		complete: function(){},
    		success: function(data){
                $("#productList >tbody").html(data.content);                   
                $("#manual-paginations").html(data.pagination);
                //if(categoryType =='manual') productListSetup();								
    		}		
    	});   
    }
    
}
function saveManualOrdering(){
    if(moduleId != '0' && categoryId != '0' && categoryType == 'manual'){
        if($(".manual-ordering").length >0){
            var values = [];
            var productIds = [];
            var i= 0;
            $(".manual-ordering").each(function() {
                values[i] = $(this).val();
                productIds[i] = $(this).attr('product-id');
                i++;
    		});
            if(i == 0) return false;
            var data={'action':'saveManualOrdering', 'moduleId':moduleId, 'categoryId':categoryId, 'productIds':productIds, 'values':values}
            $.ajax({
        		type:'POST',
        		url: baseModuleUrl + '/ajax.php',
        		data: data,
        		dataType:'json',
        		cache:false,
        		async: true,
        		beforeSend: function(){},
        		complete: function(){},
        		success: function(data){
                    showSuccessMessage(data);
                    loadProductsByModuleCategory(manualProductPage);							
        		}		
        	});
    		
    	}    
    }
    
}
function loadCategoriesByModule(){
    var data={'action':'loadCategoriesByModule', 'moduleId':moduleId}
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
		success: function(data){
		  $("#categoriesList >tbody").html(data.content);
          $("#productList >tbody").html('');
            if(data.status = '1'){                
                categoriesListSetup();
            }else {
                showSuccessMessage(data.msg);
                
            }								
		}		
	});
}
function openCategoryModal(){
    if(moduleId != '0'){
        showModal('categoryModal');
    }else{
        alert("You need to choose one module, please!");
    }
    return false;
}  
function showModal(newModal, oldModal){
	if(oldModal != "") $("#"+oldModal).modal('hide');
	$("#"+newModal).modal('show');
}
/*
function loadModuleTitle(langId){
    var itemId = $("#itemId").val();
    if(itemId != '0'){
        var data={'action':'loadModuleTitle', 'itemId':itemId, 'langId':langId}
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
                $("#module-title").val(response);											
    		}		
    	});
    }
}
*/
function loadModuleByLang(langValue){
    $(".module-lang").each(function() {
        $(this).val(langValue);        
    });
    var itemId = $("#itemId").val();
    if(itemId != '0'){
        var data={'action':'loadModuleByLang', 'itemId':itemId, 'langId':langValue}
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
                $("#module-title").val(response.module_title);
                $("#banner").val(response.module_banner);
                $("#module_banner_description").val(response.module_banner_link);
                $("#module_banner_link").val(response.module_banner_description);
                $("#note").val(response.note);										
    		}
    	});
    }
}
function loadModuleCategoryTitle(langId){
    var catId = $("#catId").val();
    if(catId != '0'){
        var data={'action':'loadModuleCategoryTitle', 'catId':catId, 'langId':langId}
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
                $("#category-title").val(response);											
    		}		
    	});
    }
}
function saveModule(){    
    var title = $("#module-title").val();
    if(title == ""){
        $("#module-title").focus().parent().addClass('has-error');
        return false;
    }else{
        $("#module-title").parent().removeClass('has-error').addClass('has-success');
    }
    var position = $("#module-position :selected").val();
    var displayOnly = $("#module-only :selected").val();
    var orderValue = $("#module-order-value :selected").val();
    var orderType = $("#module-order-type :selected").val();
    var ordering = $("#module-ordering").val();
    var langId = $("#module-lang :selected").val();
    var moduleLayout = $("#module-layout :selected").val();
    var itemId = $("#itemId").val();
    var display = $('.module-display:checked').val();
    var banner = $("#banner").val();
    var bannerDescription = $("#module_banner_description").val();
    var bannerLink = $("#module_banner_link").val();
    var note = $("#note").val();    
    var data={'action':'saveModule', 'bannerLink':bannerLink, 'bannerDescription':bannerDescription, 'banner':banner, 'title':title, 'position':position, 'displayOnly':displayOnly, 'orderValue':orderValue, 'orderType':orderType, 'ordering':ordering, 'display':display, 'langId':langId, 'moduleLayout':moduleLayout, 'note':note, 'itemId':itemId}
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
            window.location.reload();
            //alert(response);
            //reload = true;										
		}		
	});
}


// Category
function saveCategory(){
    if(moduleId != '0'){
        var categoryTitle = $("#category-title").val();
        if(categoryTitle == ""){
            alert("Enter the category title, please!");
            return false;
        }
        var langId = $("#category-lang :selected").val();
        var type = $("#category-type :selected").val();
        var categoryId = $("#category-id :selected").val();
        var countProduct = $("#count-product").val();
        var ordering = $("#category-ordering").val();
        var itemId = $("#catId").val();
        var icon = $("#category-icon").val();
        var status = $('.category-display:checked').val();
        var data={'action':'saveCategory', 'icon':icon, 'status':status, 'moduleId':moduleId, 'categoryTitle':categoryTitle, 'langId':langId, 'type':type, 'categoryId':categoryId, 'countProduct':countProduct, 'ordering':ordering, 'ordering':ordering, 'itemId':itemId}
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
                //alert(response);   
                showSuccessMessage(response);             
                //loadCategory = true;
                if(itemId == '0'){
                    $("#category-title").val("");
                    $("#count-product").val('4');                        
                }
                loadCategoriesByModule();										
    		}		
    	});
    }else{
        showSuccessMessage("You need to choose one module, please!");
    }    
}


function openProductsModal(){
    if(categoryType == 'manual'){
        showModal('productsModal');
        loadProductsByCategory('1');
        
    }else return false;
}
var productPage = 1;
function loadProductsByCategory(page){
    productPage = page;
    var keyword = $("#keyword").val();
    var data={'action':'loadProductsByCategory', 'categoryId':categoryId, 'moduleId':moduleId, 'page':page, 'keyword':keyword}
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
		success: function(data){
            $("#allProductList-pagination").html(data.pagination);
            $("#allProductList >tbody").html(data.list);										
		}		
	});
}
function saveManualProduct(){
    var data={'action':'saveManualProduct', 'manualProductIds':manualProductIds, 'categoryId':categoryId}
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
		success: function(data){
		  	loadProductsByModuleCategory();								
		}		
	});
}