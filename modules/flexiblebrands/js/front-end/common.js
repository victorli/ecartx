var flexibleBrandWindowWidth = $(window).width();
$(document).ready(function(){
    $(".flexible-brand-group-products").owlCarousel({
        loop:true,
        nav:true,
        responsive:{
            0:{
                items:1,
                margin:30
            },
            380:{
                items:1,
                margin:30
            },
            480:{
                items:2,
                margin:30
            },
            768:{
                items:2,
                margin:30
            },
            1200:{
                items:3,
                margin:30
            }
        }
    });    
});
$(window).bind('load', function(){
	setHeight_LayoutDefault();
	
});
jQuery(function($){
	$(".flexible-brand-list li a").click(function(e){		
		if($(this).parent().hasClass('active') == false){
			var moduleId = $(this).attr('data-module');
			var groupId = $(this).attr('data-group');			
			$("#flexible-brand-list-"+moduleId).find('li').removeClass('active');
			$(this).parent().addClass('active');			
			$("#flexible-brand-products-"+moduleId).find('div.flexible-brand-group-products').removeClass('active');
			$("#flexible-brand-group-products-"+groupId).addClass('active');			
			$("#manufacture-avatars-"+moduleId).find('div.manufacture-avatar').removeClass('active');
			$("#manufacture-avatar-"+groupId).addClass('active');
		}
        
    	//e.preventDefault();
    	//$(this).tab('show');
    });
	
});
$(window).resize(function() {    
    if($(window).width()!=flexibleBrandWindowWidth){    
        setHeight_LayoutDefault();
        groupCategoryWindowWidth = $(window).width();
    }
});
function setHeight_LayoutDefault(){
    if(flexibleBrandWindowWidth >767){
        if($(".flexible-brand-box").length >0){
    		$(".flexible-brand-box").each(function(index) {
    		  var thisHeight = $(this).find('.flexible-brand-products').height();
    		  $(this).find('.flexible-brand-group-inner').css({'height':thisHeight});
    		});
    	}    
    }
	
}
