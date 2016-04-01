var verticleWindowWidth = $(window).width();
$(document).ready(function(){
    //setTimeout('verticalMegamenusSetup()', 1); 
    if(verticleWindowWidth <1199){
    	removeLink();	
    }
           
});
$(window).resize(function() {
	if($(window).width()!=verticleWindowWidth){
		$(".vertical-dropdown-menu").removeAttr('style');
		setTimeout('verticalMegamenusSetup()', 1);
		verticleWindowWidth = $(window).width();
		removeLink();
	}    
});
$(window).bind('load', function(){
	verticalMegamenusSetup();
	
});
jQuery(function($){
	$(document).on('click','.vertical-parent',function(){
		if($(this).parent().hasClass('active') == true){			
			$(this).parent().removeClass('active');			
		}else{
			$(".megamenus-ul >li.parent").removeClass('active');
			$(this).parent().addClass('active');	
		}		
		        
	});
	
	/*
	$(document).click(function(event) {
	  var target = $(event.target);
	  alert(target);
	  return false;
	  if (!target.attr('id').match(/^mydiv/) && target.parents('#mydiv').length == 0) {
	    $('#mydiv').hide();
	  }
	});       
	*/
});
function removeLink(){
	$(".megamenus-ul >li.parent").removeClass('active');
	if(verticleWindowWidth <1199){
		$("a.vertical-parent").attr('href', 'javascript:void(0)');	
	}else{
		$("a.vertical-parent").each(function(index) {
			//var link = $(this).attr('data-link');
			//alert(link);
			$(this).attr('href', $(this).attr('data-link'));
		});
	}
	
}
function verticalMegamenusSetup(){    
    if($(".box-vertical-megamenus").length >0){
        $(".box-vertical-megamenus").each(function(){			
            var mainWidth = $(this).parent().parent().actual('width');            
			if(verticleWindowWidth >= 992){
				var parentWidth = $(this).parent().actual('width');
				var verticalDropdownMenuWidth = parseInt(mainWidth - parentWidth - 30);
			}else if(verticleWindowWidth <992 && verticleWindowWidth >=768){
				var verticalDropdownMenuWidth = parseInt(mainWidth - 260);
			}
            if(verticalDropdownMenuWidth < 100) verticalDropdownMenuWidth = parentWidth;			
            $(this).find('.vertical-dropdown-menu').each(function(index){				
                $(this).css({'width':verticalDropdownMenuWidth});                
            });
        });
    }   
}
