$(document).ready(function(){
    $('input[name="id-category"]').click(function(){
       var id_cate = $(this).val();
       location.href = $('#mainUrl').val()+'&id_category='+id_cate;
    });
});