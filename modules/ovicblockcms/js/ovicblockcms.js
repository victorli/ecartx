$(document).ready(function(){
    if ('ontouchstart' in document.documentElement)
	{
		$('#cms_pos .cms_title').on('click', function(e){
			e.preventDefault();
		});
	}

	$(document).on('touchstart', '#cms_pos .cms_title', function(){
		if ($(this).next('.cms-toggle:visible').length)
			$(this).next('.cms-toggle').stop(true, true).slideUp(450);
		else
			$(this).next('.cms-toggle').stop(true, true).slideDown(450);
		e.preventDefault();
		e.stopPropagation();
	});
	//$("#cms_pos .cms_title").hover(
//		function(){
//		      $(this).next('.cms-toggle').stop(true, true).slideDown(450);
//		},
//		function(){
//            var toggle_obj = $(this).next('.cms-toggle');
//            setTimeout(function(){
//                if (!toggle_obj.is(":hover"))
//					toggle_obj.stop(true, true).slideUp(450);
//			}, 200);
//		}
//	);
//    $("#cms_pos .cms-toggle").hover(
//		function(){
//		},
//		function(){
//            var toggle_obj = $(this);
//            var title_obj = $(this).prev('.cms_title');
//			setTimeout(function(){
//				if (!title_obj.is(":hover"))
//					toggle_obj.stop(true, true).slideUp(450);
//			}, 200);
//		}
//	);
    //cmsClick = $("#cms_pos .cms_title");
//    cmsClick.on('click',function(){
//        var cmsSlide = $(this).next('.cms-toggle');
//         if (cmsSlide.is(':visible'))
//            cmsSlide.stop(true, true).slideUp(450);
//         else{
//            cmsSlide.stop(true, true).slideDown(450)
//         }
//         $(cmsClick).not(this).next('.cms-toggle').slideUp();
//    });
//    $(document).on('click',function(e){
//        if (e.target.className != 'cms_title' && e.target.className != 'cms-toggle'){
//            $('.cms-toggle').stop(true, true).slideUp(450);
//        }
//    });

})