var SnsScript = {
    init: function() {
        var that = this;
        that.setMenuActive();
    },
	convertLinkToCompare: function (str) {
		str = str.split("?")[0];
		lastchar = str.substring(str.length - 1, str.length);
		if(lastchar === '/') {
			str = str.substring(0, str.length - 1);
		}
		if(str.search('index.php')) str = str.replace('/index.php', '');
		return str;
	},
	setMenuActive: function () {
		var that = this;
		var currentlink = jQuery(location).attr('href');
		currentlink = that.convertLinkToCompare(currentlink);
		
		var mainmenu = jQuery('#sns_mainnav');
		
		mainmenu.find('li').removeClass('active');
		menulinks = mainmenu.find('li a');
		
		menulinks.each(function(){
			var menulink = that.convertLinkToCompare(jQuery(this).attr('href'));
			if(menulink === currentlink) {
				jQuery(this).parents('li[class*="level"]').addClass('active');
				return false;
			}
		});
	}
};


$(document).ready( function(){
	SnsScript.init();
	if (typeof SNS_TOOLTIP != 'undefined' && SNS_TOOLTIP) {
		$("[data-toggle='tooltip']").tooltip({
			container: 'body'
		});
	}
	if (typeof KEEP_MENU != 'undefined' && KEEP_MENU) {
		if($('#sns_menu').length){
		    var previousScroll = 0,
		        headerOrgOffset = $('#sns_menu').offset().top;
		    
		    $(window).scroll(function() {
		        var currentScroll = $(this).scrollTop();
		        if(currentScroll > headerOrgOffset) {
		        	$('#sns_menu').addClass('keep-menu');
		        	if(!$('#sns_mene_clone').length) $('#sns_menu').after('<div id="sns_mene_clone" style="height: ' + $('#sns_menu').height() + 'px"></div>');
		            $('#sns_menu').stop(true, true).addClass('keep-menu-show').fadeIn();
		        } else {
		        	$('#sns_menu').removeClass('keep-menu');
					$('#sns_mene_clone').remove();
					$('#sns_menu').fadeIn(0);
		        }
		        previousScroll = currentScroll;
		    });
		}
	}
	if($('#sns_right').length) {
		$('#sns_mommenu .btn2.rightsidebar').css('display', 'inline-block').on('click', function(){
			if($('#sns_right').hasClass('active')){
				$(this).find('.overlay').fadeOut(250);
				$('#sns_right').removeClass('active');
				$('body').removeClass('show-sidebar');
			} else {
				$('#sns_right').addClass('active');
				$(this).find('.overlay').fadeIn(250);
				$('body').addClass('show-sidebar');
			}
		});
	}
	if($('#sns_left').length) {
		$('#sns_mommenu .btn2.leftsidebar').css('display', 'inline-block').on('click', function(){
			if($('#sns_left').hasClass('active')){
				$(this).find('.overlay').fadeOut(250);
				$('#sns_left').removeClass('active');
				$('body').removeClass('show-sidebar');
			} else {
				$('#sns_left').addClass('active');
				$(this).find('.overlay').fadeIn();
				$('body').addClass('show-sidebar');
			}
		});
	}
	$('.banner-slider').hide();
	$(window).load(function(){
		$('.banner-slider').owlCarousel({
			pagination: true,
			itemsScaleUp : true,
			slideSpeed : 800,
			autoPlay: true,
			addClassActive: true,
			singleItem: true,
			transitionStyle: 'fadeUp',
		});
	});
});

$.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
	if(!originalOptions.url.match(/snsquicksearch|blocklayered/i)) {
		$('.ajaxloading').fadeIn();
	}
});

$(document).on('ajaxStop', function(){
	$('.ajaxloading').fadeOut();
});


$(document).on('click', '.gallery .img img', function(){
	$(this).parents('.item-img').find('.image-main img').attr('src', $(this).attr('data-src'));
});


















