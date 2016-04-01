$(document).ready(function(){ 
    $(window).scroll(function(){
		$('#footer_paralax .prlx').each(function(r){
			var pos = $(this).offset().top;
			var scrolled = $(window).scrollTop();
	    	$('#footer_paralax .prlx').css('top', -(scrolled * 0.5) + 'px');			
	    });
	});
});
