$(document).ready(function(){
    $('.select_position').on('change',function(){
        var id_position = $(this).val();
        $.ajax({
        	type: 'POST',
        	url: $('#ajaxUrl').val(),
        	data: 'id_position='+id_position,
        	dataType: 'json',
        	cache: false, 
        	success: function(result){
        	   $("#htmlcontent").html(result);
        	}
        });   
    });
});