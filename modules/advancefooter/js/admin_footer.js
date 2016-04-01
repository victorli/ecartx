$(document).ready(function(){
    var it = $("#item_type_selected").val();
    $("#"+it+"_Container").show();
    $("#item_type_selected").change(function(){
        var it = $(this).val();
        $(".item_type").hide();
        $("#"+it+"_Container").show();
    });
    var lt = $("#linktype").val();
    $("#link_"+lt).show();
    $("#linktype").change(function(){
        var type = $(this).val();
        $(".link_detail").hide();
        $("#link_"+type).show();
    });

    $("#module_select").change(function(){
        $("#hook_select option").remove();
        $.ajax({
    		type: 'POST',
    		url:  $("#ajaxurl").val(),
            dataType : "html",
    		data: 'action=getModuleHook&module_name='+$(this).val(),
    		success:function(html){
  		        if (html){
                    $("#hook_select").append(html);
  		        }
    		},
    		error: function(XMLHttpRequest, textStatus, errorThrown) {
    			alert("TECHNICAL ERROR: \n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
    		}
    	});
    });

    /****************************************/
     $( ".row_sortable" ).sortable({
        update: function(e, ui){
            var row_order = "";
            if($(".row_container").length >1){
                $(".row_container").each(function(index){
                row_order += $(this).find(".row_id").text()+'::';
                $(this).find('.row_postition').text(index+1);
                });
                row_order = (row_order.substr(0, row_order.length - 2));
                $.ajax({
            		type: 'POST',
                    dataType: 'json',
            		url:  $("#ajaxUrl").val(),
            		data: 'action=updaterowposition&row_order='+row_order,
                    success:function(jsonData){
          		        if (jsonData){
                           showSuccessMessage(update_success_msg);
          		        }else{
          		            showErrorMessage('Update error');
          		        }
            		}
            	});
            }
        }
     });
     /****************************************/
     $( ".blocksortable" ).sortable({
        update: function(e, ui){
            var list_class = $(this).children().attr('class').split(" ")[2];
            var block_order = "";
            if($("."+list_class).length >1){
                $("."+list_class).each(function(){
                    block_order += $(this).find(".block_id").text()+'::';
                });
                block_order = (block_order.substr(0, block_order.length - 2));
                $.ajax({
            		type: 'POST',
                    dataType: 'json',
            		url: $('#ajaxUrl').val(),
            		data: 'action=updateblockposition&block_order='+block_order,
                    success:function(jsonData){
          		        if (jsonData){
                           showSuccessMessage(update_success_msg);
          		        }else{
          		            showErrorMessage('Update error');
          		        }
            		}
            	});
            }
        }
     });
     /****************************************/
     $( ".item_sortable" ).sortable({
        update: function(e, ui){
            var list_class = $(this).children().attr('class');
            var item_order = "";
            if($("."+list_class).length >1){
                $("."+list_class).each(function(){
                    item_order += $(this).find(".item_id").text()+'::';
                });
                item_order = (item_order.substr(0, item_order.length - 2));
                $.ajax({
            		type: 'POST',
                    dataType: 'json',
            		url: $('#ajaxUrl').val(),
            		data: 'action=updateitemposition&item_order='+item_order,
                    success:function(jsonData){
          		        if (jsonData){
                           showSuccessMessage(update_success_msg);
          		        }else{
          		            showErrorMessage('Update error');
          		        }
            		}
            	});
            }
        }
     });
});