$(document).ready( function(){
	$("#sns_cpanel").each( function(){
		var wrap = this;
		$("#sns_config_btn",this).click( function(){
			if( $(wrap).hasClass("active") ){
				$(wrap).removeClass("active");
				$(wrap).stop().animate({
					'left': - $(wrap).outerWidth()
				}, 600); 
			}else{
			  	$(wrap).addClass("active");
			  	$(wrap).stop().animate({
					'left':'0px',
				}, 600);
			}
		} );
	} );

		// bg color
		$('[name$="BODYCOLOR"]').on('change', function (){
			$('body').css({
				'background-color' : $(this).val()
			});
		});
		
		// bg image
		$('.sns-patterns .radio_img_group label').on('click', function(){
			$('body').css('background-image', 'url("'+ img_dir + 'patterns/' + $(this).find('input').val() + '")');
		});
		// layout type
		$('[name*="LAYOUTTYPE"]').on('change', function (){
			if($(this).val() == 2) $('body').addClass('boxed-layout');
			else $('body').removeClass('boxed-layout');
		});
		// fontsize
		$('[name*="FONTSIZE"]').on('change', function (){
			$('body').css('font-size', $(this).val());
		});
		
});