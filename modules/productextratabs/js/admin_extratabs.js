$( document ).ready(function() {
    $("#category_select").change(function(){
        $("#product_select option").remove();
        $("#product_select").append('<option value="all">All product</option>');
        $.ajax({
    		type: 'POST',
    		url:  $("#ajaxurl").val(),
            dataType : "json",
    		data: 'action=getproducts&id_category='+$(this).val(),
    		success:function(jsonData){
  		        if (jsonData){
  		            $(jsonData).each(function(){
                        $("#product_select").append('<option value="'+this.id_product+'">'+this.name+'</option>');
  		            });
  		        }
    		}
    	});
    });
});