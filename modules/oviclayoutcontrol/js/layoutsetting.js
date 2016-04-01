$(document).ready(function(){
    $(document).on('click','#curent_option .colsetting',function(){
        if ($(this).parent().hasClass('radio_button')){
            var elementId = $(this).attr('id');
           $('#curent_option .colsetting-container label').removeAttr('class');
           $('#curent_option .colsetting-container label[for='+elementId+']').addClass('colactive');
        }
    });
     $('#sidebarModule .newmodulehook').on('click', function (e) {
        e.preventDefault(); // avoids calling preview.php
        $.ajax({
          type: "POST",
          cache: false,
          url: this.href, // preview.php
          dataType : "json",
          success: function (data) {
            // on success, post (preview) returned data in fancybox
            $.fancybox(data, {
              // fancybox API options
              fitToView: false,
              width: 300,
              height: 180,
              autoSize: false,
              closeClick: false,
              openEffect: 'none',
              closeEffect: 'none'
            }); // fancybox
          } // success
        }); // ajax
    });

    //overide hook button
    $(document).on('click','#sidebarModule .changeHook',function(e){
        e.preventDefault(); // avoids calling preview.php
        var moduleContainer = $(this).closest('.moduleContainer');
        var data_module = moduleContainer.attr("data-module-info").replace('module-','').split('-');
        var id_module = data_module[0];
        var id_hookexec = data_module[1];
        $.ajax({
          type: "POST",
          cache: false,
          url: this.href, // preview.php
          dataType : "json",
          data: '&id_module='+id_module+'&id_hookexec='+id_hookexec,
          success: function (data) {
            // on success, post (preview) returned data in fancybox
            $.fancybox(data, {
              // fancybox API options
              fitToView: false,
              width: 300,
              height: 180,
              autoSize: false,
              closeClick: false,
              openEffect: 'none',
              closeEffect: 'none'
            }); // fancybox
          } // success
        }); // ajax
    });

    $(document).on('change','#changehook_popup #hookexec',function(){
        $('#changehook_popup').find('#id_hookexec').val($(this).val());
    });

    //get hook option on change module
    $(document).on('change','#newmodule_popup #moduleHook',function(){
        $("#hookexec option").remove();
        $.ajax({
    		type: 'POST',
    		url:  $("#popupAjaxUrl").val(),
            dataType : "html",
    		data: '&action=getModuleHookOption&id_module='+$(this).val(),
    		success:function(html){
  		        if (html){
                    $("#hookexec").append(html);
  		        }
    		},
    		error: function(XMLHttpRequest, textStatus, errorThrown) {
    			alert("TECHNICAL ERROR: \n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
    		}
    	});
    });

    //sortable
    $('#sidebarModule .hookContainer').sortable( {
			connectWith: '#sidebarModule .hookContainer',
			containment: '#sidebarModule',
            items:  '.moduleContainer:not(.disable_sort)',
			forceHelperSize: true,
			forcePlaceholderSize: true,
			placeholder: 'placeholder',
            start: function(){
                updateModule = new Array();
            },
            update: function(e, ui) {
                var hookName = $(this).attr("data-hook");
                var hook_id = $(this).attr("id");
                var drag_id = $(ui.item).attr('id');
                var moduleHooked = new Array();
                $("#"+hook_id+" .moduleContainer").each(function(index){
                    $(this).find('.module-position').text(index+1);
                    var data_module = $(this).attr("data-module-byname").split('-');
                    moduleHooked.push(data_module);
                });
                var hookdata = {};
                //hookdata.push(hookName,moduleHooked);
                hookdata[hookName] = moduleHooked;
                updateModule.push(hookdata);
            },
            stop: function(e, ui) {
                if (updateModule != null && parseInt(updateModule.length) > 0){
                    $.ajax({
                		type: 'POST',
                		url:  $('#ajaxUrl').val(),
                        dataType : "json",
                		data: 'action=updateHook&datahook='+ JSON.stringify(updateModule),
                		success: function (data) {
          		            $return = true;
                            $.each(data, function(i, item) {
                                if (!item.status){
                                    $return = false;
                                    showErrorMessage('Hook '+item.hookname+' update error');
                                }
                            })
                            if ($return){
                                showSuccessMessage(update_success_msg);
                            }
                		}
                	});
                }
            }
		});
});
//remove module
function removeSideBarModule(Obj){
    var moduleContainer = Obj.closest('.moduleContainer');
    var data_module = moduleContainer.attr("data-module-info").replace('module-','').split('-');
    var id_module = data_module[0];
    var id_hookexec = data_module[1];
    var hookName = Obj.closest('.hookContainer').attr('data-hook');
    var ajaxurl =  $("#ajaxUrl").val();
    $.ajax({
		type: 'POST',
		url:  ajaxurl,
        dataType : "json",
		data: 'action=removeSideBarModule&hookname='+hookName+'&id_module='+ id_module + "&id_hookexec="+id_hookexec,
		success: function (data) {
            if (data.status){
                $id_container = Obj.closest('.hookContainer').attr('id');
                Obj.closest('.moduleContainer').remove();
                $('#'+$id_container+' .module-position').each(function(index){
                    $(this).html(index+1);
                });
                showSuccessMessage(data.msg);
            }else{
                showErrorMessage('Delete error');
            }
            return data.status;
		}
	});
}
//add new module
function submitSidebarModule(Obj){
    var newmodule_popup = Obj.closest("#newmodule_popup");
    var id_module = parseInt(newmodule_popup.find("#moduleHook").val());
    var id_hookexec = parseInt(newmodule_popup.find("#hookexec").val());
    var hookname = newmodule_popup.find("#hookname").val();
    var ajaxurl =  newmodule_popup.find("#popupAjaxUrl").val();
    $.ajax({
		type: 'POST',
		url:  ajaxurl,
        dataType : "json",
		data: 'action=addModuleHook&id_module='+ id_module + "&id_hookexec="+id_hookexec,
		success: function (data) {
            if (data.status){
                $('#'+hookname).append(data.html);
                $.fancybox.close();
                showSuccessMessage(data.msg);
            }
		}
	});
}
//overide hook
function submitSidebarChangeHook(Obj){
    var changehook_popup = Obj.closest("#changehook_popup");
    var id_hookexec = parseInt(changehook_popup.find("#id_hookexec").val());
    var old_hook = parseInt(changehook_popup.find("#old_hook").val());
    if (id_hookexec != old_hook){
        var id_module = parseInt(changehook_popup.find("#moduleHook").val());
        var hookname = changehook_popup.find("#hookname").val();
        var ajaxurl =  changehook_popup.find("#popupAjaxUrl").val();
        $.ajax({
    		type: 'POST',
    		url:  ajaxurl,
            dataType : "json",
    		data: 'action=ChangeModuleHook&id_module='+ id_module + "&id_hookexec="+id_hookexec+"&old_hook="+old_hook,
    		success: function (data) {
                if (data.status){
                    object_id = "#module_"+id_module+"_"+old_hook;
                    $(object_id).attr("data-module-info","module-"+id_module+"-"+id_hookexec);
                    $(object_id).attr("data-module-byname",data.moduleinfo);
                    $(object_id).attr("id","module_"+id_module+"_"+id_hookexec);
                    $.fancybox.close();
                    showSuccessMessage(update_success_msg);
                }
    		}
    	});
    }else{
        $.fancybox.close();
    }
}