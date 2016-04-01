$(document).ready(function(){
	
    
		$(".flexible-group-products").owlCarousel({
			loop:true,
            nav:true,
            responsive:{
                0:{
                    items:1,
                    margin:30
                },
                480:{
                    items:2,
                    margin:30
                },
                768:{
                    items:3,
                    margin:13
                },
                992:{
                    items:3,
                    margin:24
                },
                1200:{
                    items:4,
                    margin:30
                }
            }
		});    
});

jQuery(function($){
	$(".flexible-custom-list li a").click(function(e){		
		if($(this).parent().hasClass('active') == false){
			var moduleId = $(this).attr('data-module');
			var groupId = $(this).attr('data-group');			
			$("#flexible-custom-list-"+moduleId).find('li').removeClass('active');
			$(this).parent().addClass('active');	
			
			$("#flexible-custom-products-"+moduleId).find('div.flexible-group-products').removeClass('active');
			$("#flexible-group-products-"+groupId).addClass('active');
		}
        
    	//e.preventDefault();
    	//$(this).tab('show');
    });
});
