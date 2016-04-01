function setLanguage(language_id, language_code) {
	$('#lang-id').val(language_id);
	$('#selected-language').html(language_code);
}
$(document).ready(function() {
	var lt = $("#linktype").val();
	$(".link_detail").hide();
	$("#" + lt + "Container").show();
	if (lt != "html") $("#link_field").show();
	if (lt == "img") $("#title_lb").text("Alt text");
	else
	$("#title_lb").text("Title");
    /*********************************/
	$("#linktype").change(function() {
		var type = $(this).val();
		$(".link_detail").hide();
		$("#" + type + "Container").show();
		if (type != "html") $("#link_field").show();
		if (type == "img") $("#title_lb").text("Alt text");
		else
		$("#title_lb").text("Title");
	});
    /*********************************/
	$(".link_select").change(function() {
        var ids = $(this).attr('id'); 
		$("#link_text").val($('#'+ids+' option:selected').text().trim());
		if (!$('#item_title').val().length > 0) {
			$('#item_title').val($('#'+ids+' option:selected').text().trim());
		}
		$("#link_value").val($(this).val());
	});
    /*********************************/
	$("#custom_class_select, #custom_class_text").change(function() {
		$("#custom_class").val($(this).val());
	});
    /*********************************/
	$("#link_text").change(function() {
		$("#link_value").val($(this).val());
	});
    /*********************************/
     $( ".sortable" ).sortable({
        update: function (e, ui) {
            var list_class = $(this).children().attr('class').split(" ")[0];
			var menu_order="";
            if($("."+list_class).length >1){
                $("."+list_class).each(function(){
                menu_order += $(this).find(".hidden").text()+'::';
                });
                menu_order = (menu_order.substr(0, menu_order.length - 2));
                $.ajax({
            		type: 'POST',
                    dataType: 'json',
            		url: $('#ajaxUrl').val(),
            		data: 'action=updateposition&menu_order='+menu_order,
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
     $( ".sub_sortable" ).sortable({      
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
});