//767
var groupCategoryWindowWidth = $(window).width();
$(document).ready(function(){
	$(".groupcategory-manufacturers").width($(".groupcategory-manufacturers").parent().parent().parent().width()+15);	
	$('a.tab-link').click(function (e) {	  
	  var moduleId = $(this).data('id');
	  $("#groupcategory-"+moduleId+" .check-active").removeClass('active');
	  e.preventDefault();
	  $(this).tab('show');	  
	});	
	$(".lazy-carousel").owlCarousel({
		lazyLoad:true,
		loop: false,		
		responsive: {
			0:{
				items:1,
				nav:true				
			},
			320:{
				items:1,
				nav:true
			},
			768:{
				items:2,
				nav:true
			},
			1200:{
				items:3,
				nav:true
			}
		}
	});	
	$(".manufacture-carousel").owlCarousel({
		loop: false,		
		auto: true,
		responsive: {
			0:{
				items:1,
				nav:true				
			}
		}
	});	
    if($(".compare-checked").length >0){
        $(".compare-checked").each(function() {
            $(this).addClass('checked');		
        });	
    }
});
$(window).bind('load', function(){	
	setHeightGroup();	
});
$(window).resize(function() {    
	$(".groupcategory-manufacturers").width($(".groupcategory-manufacturers").parent().parent().parent().width()+15);
    if($(window).width()!=groupCategoryWindowWidth){    
        $(".groupcategory-cell").removeAttr('style');
        $(".box-group-category").removeAttr('style');        
		setTimeout('setHeightGroup()', 2000);		
        groupCategoryWindowWidth = $(window).width();
    }
});
function setHeightGroup(){
	if(groupCategoryWindowWidth > 767){
		if($(".box-group-category").length >0){
	        $(".box-group-category").each(function(){            
	            var maxHeight = 0;
	            $(this).find('.groupcategory-cell').each(function(index) {
	            	var offset = 0;
	            	if($(this).find('.groupcategory-manufacturers').length >0){
	            		offset = $(this).find('.groupcategory-manufacturers').height();
	            	} 
	            	
	            	if(($(this).height() + offset) > maxHeight) maxHeight = $(this).height() + offset;  
	            });                
	            $(this).find('.groupcategory-cell').height(maxHeight);
	            $(this).height(maxHeight);                
	        });
	    }	
	}
	
}
