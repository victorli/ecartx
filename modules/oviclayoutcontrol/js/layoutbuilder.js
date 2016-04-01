$(document).ready(function(){
    $('#ovicLayoutSeting .colsetting').click(function(){
        if ($(this).parent().hasClass('editable')){
            var colselected="";
            $(".colsetting:checked").each(function(){
                colselected += $(this).val()+":";
            });
            $("#colselected").val(colselected.substr(0, colselected.length - 1));
            var elementId = $(this).attr('id');
            if ($(this).hasClass('multiselect')){
               if ($(this).is(':checked')){
                 $('.colsetting-container label[for='+elementId+']').addClass('colactive');
               }else{
                 $('.colsetting-container label[for='+elementId+']').removeAttr('class');
               }
            }else{
               $('.colsetting-container label').removeAttr('class');
               $('.colsetting-container label[for='+elementId+']').addClass('colactive');
            }
        }
    });

    $('#ovicLayoutSeting .newmodulehook').on('click', function (e) {
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
    $(document).on('click','#ovicLayoutSeting .changeHook',function(e){
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

    $(document).on('change','#changehook_popup #hookexec',function(){
        $('#changehook_popup').find('#id_hookexec').val($(this).val());
    });

    var updateModule;
    $('#ovicLayoutSeting .hookContainer').sortable({
			connectWith: '#ovicLayoutSeting .hookContainer',
			containment: '#ovicLayoutSeting',
            items:  '.moduleContainer:not(.disable_sort)',
			forceHelperSize: true,
			forcePlaceholderSize: true,
			placeholder: 'placeholder',
            start: function(){
                updateModule = new Array();
            },
            update: function(e, ui) {
                var hookName = $(this).attr("id");
                var drag_id = $(ui.item).attr('id');
                var moduleHooked = new Array();
                $("#"+hookName+" .moduleContainer").each(function(index){
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
            		data: 'action=updateHook&id_option='+$('#id_option').val()+'&datahook='+ JSON.stringify(updateModule),
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

function submitModuleHook(Obj){
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
		  //var $result = $('<div>').append(utf8_decode(data));
            if (data.status){
                $('#'+hookname).append(data.html);
                $.fancybox.close();
                showSuccessMessage(data.msg);
            }
		}
	});
}
function submitChangeHook(Obj){
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
    		  //var $result = $('<div>').append(utf8_decode(data));
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
function removeModuleHook(Obj){
    var moduleContainer = Obj.closest('.moduleContainer');
    var data_module = moduleContainer.attr("data-module-info").replace('module-','').split('-');
    var id_module = data_module[0];
    var id_hookexec = data_module[1];
    var hookName = Obj.closest('.ui-sortable').attr('id');
    var id_option = $('#id_option').val();
    var ajaxurl =  $("#ajaxUrl").val();
    $.ajax({
		type: 'POST',
		url:  ajaxurl,
        dataType : "json",
		data: 'action=removeModuleHook&id_option='+id_option+'&hookname='+hookName+'&id_module='+ id_module + "&id_hookexec="+id_hookexec,
		success: function (data) {
		  //var $result = $('<div>').append(utf8_decode(data));
            if (data.status){
                Obj.closest('.moduleContainer').remove();
                $('#'+hookName+' .module-position').each(function(index){
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