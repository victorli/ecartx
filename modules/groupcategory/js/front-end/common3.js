//767
var groupCategoryWindowWidth = $(window).width();
$(document).ready(function(){	
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
				items:2,
				nav:true
			},
			768:{
				items:3,
				nav:true
			},
			1200:{
				items:5,
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
