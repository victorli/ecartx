/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
//global variables

var responsiveflag = false;

$(document).ready(function(){
    height_slider_option1();
   
	highdpiInit();
	responsiveResize();
	$(window).resize(responsiveResize);
    $(window).resize(height_slider_option1);
    //responsiveMobileHeader();
    //$(window).resize(responsiveMobileHeader);
    responsiveHeaderXs();
    $(window).resize(responsiveHeaderXs);

    //crumbProcess();
    
    
    /* Menuontop options */
    // Menuontop opton4
    $(window).scroll(function () {
        if($(window).width() >= 768){
          if ($(this).scrollTop() > 137) {
            if (!$('.option4 #container-home-top').hasClass('menuontop')){
                $('.option4 #container-home-top').addClass("menuontop");
            }
          }else {
            if ($('.option4 #container-home-top').hasClass('menuontop')){
                $('.option4 #container-home-top').removeClass('menuontop');
            }
          }
        }else{
            if ($('.option4 #container-home-top').hasClass('menuontop')){
                $('.option4 #container-home-top').removeClass('menuontop');
            }
        }
    });
    
    // Scroll option4
    $('.option4 .vertical-megamenus .icon-reorder').toggle(function(){
        $('.vertical-megamenus .megamenus-ul').toggleClass('ontop');                
    },function(){
        $('.vertical-megamenus .megamenus-ul').toggleClass('ontop');
    });
    
    myVertical = $(".option4 .vertical-megamenus .title");
    myVertical.on('click',function(){
        var myVerticalSlide = $('.option4 .vertical-megamenus .megamenus-ul');
         if (myVerticalSlide.hasClass('ontop'))
           myVerticalSlide.removeClass('ontop');
         else{
           myVerticalSlide.addClass('ontop');
         }
         return false;
    });
    $(document).on('click',function(e){
        if (e.target.className != '.option4 .vertical-megamenus .title' && e.target.className != '.option4 .vertical-megamenus .megamenus-ul'){
            $('.option4 .vertical-megamenus .megamenus-ul').removeClass('ontop');
        }
    });
    
    // Menuontop opton1
    $(window).scroll(function () {
        if($(window).width() >= 768){
          if ($(this).scrollTop() > 137) {
            if (!$('.option1 #nav_topmenu').hasClass('menuontop')){
                $('.option1 #nav_topmenu').addClass("menuontop");
                $('#index.option1 #container-home-top .home-top').addClass("ontop");
            }
          }else {
            if ($('.option1 #nav_topmenu').hasClass('menuontop')){
                $('.option1 #nav_topmenu').removeClass('menuontop');
                $('#index.option1 #container-home-top .home-top').removeClass("ontop");
            }
          }
        }else{
            if ($('.option1 #nav_topmenu').hasClass('menuontop')){
                $('.option1 #nav_topmenu').removeClass('menuontop');
                $('#index.option1 #container-home-top .home-top').removeClass("ontop");
            }
        }
    });
    
    // Menuontop opton2
    $(window).scroll(function () {
        if($(window).width() >= 768){
          if ($(this).scrollTop() > 137) {
            if (!$('.option2 #nav_topmenu').hasClass('menuontop')){
                $('.option2 #nav_topmenu').addClass("menuontop");
                $('#index.option2 #container-home-top .home-top').addClass("ontop");
            }
          }else {
            if ($('.option2 #nav_topmenu').hasClass('menuontop')){
                $('.option2 #nav_topmenu').removeClass('menuontop');
                $('#index.option2 #container-home-top .home-top').removeClass("ontop");
            }
          }
        }else{
            if ($('.option2 #nav_topmenu').hasClass('menuontop')){
                $('.option2 #nav_topmenu').removeClass('menuontop');
                $('#index.option2 #container-home-top .home-top').removeClass("ontop");
            }
        }
    });
    
    // Menuontop opton3
	
    $(window).scroll(function () {
        if($(window).width() >= 768){
          if ($(this).scrollTop() > 5) {
            if (!$('.option3 #header').hasClass('menuontop')){
                $('.option3 #header').addClass("menuontop");
            }
          }else {
            if ($('.option3 #header').hasClass('menuontop')){
                $('.option3 #header').removeClass('menuontop');
            }
          }
        }else{
            if ($('.option3 #header').hasClass('menuontop')){
                $('.option3 #header').removeClass('menuontop');
            }
        }
    });
    // Menuontop opton5
    $(window).scroll(function () {
        if($(window).width() >= 768){
          if ($(this).scrollTop() > 137) {
            if (!$('.option5 #nav_topmenu').hasClass('menuontop')){
                $('.option5 #nav_topmenu').addClass("menuontop");
                $('#index.option5 #container-home-top .home-top').addClass("ontop");
            }
          }else {
            if ($('.option5 #nav_topmenu').hasClass('menuontop')){
                $('.option5 #nav_topmenu').removeClass('menuontop');
                $('#index.option5 #container-home-top .home-top').removeClass("ontop");
            }
          }
        }else{
            if ($('.option5 #nav_topmenu').hasClass('menuontop')){
                $('.option5 #nav_topmenu').removeClass('menuontop');
                $('#index.option5 #container-home-top .home-top').removeClass("ontop");
            }
        }
    });
    
	if (navigator.userAgent.match(/Android/i))
	{
		var viewport = document.querySelector('meta[name="viewport"]');
		viewport.setAttribute('content', 'initial-scale=1.0,maximum-scale=1.0,user-scalable=0,width=device-width,height=device-height');
		window.scrollTo(0, 1);
	}
	blockHover();
	if (typeof quickView !== 'undefined' && quickView)
		quick_view();
	dropDown();

	if (typeof page_name != 'undefined' && !in_array(page_name, ['index', 'product']))
	{
		bindGrid();

 		$(document).on('change', '.selectProductSort', function(e){
			if (typeof request != 'undefined' && request)
				var requestSortProducts = request;
 			var splitData = $(this).val().split(':');
			if (typeof requestSortProducts != 'undefined' && requestSortProducts)
				document.location.href = requestSortProducts + ((requestSortProducts.indexOf('?') < 0) ? '?' : '&') + 'orderby=' + splitData[0] + '&orderway=' + splitData[1];
    	});

		$(document).on('change', 'select[name="n"]', function(){
			$(this.form).submit();
		});

		$(document).on('change', 'select[name="manufacturer_list"], select[name="supplier_list"]', function() {
			if (this.value != '')
				location.href = this.value;
		});

		$(document).on('change', 'select[name="currency_payement"]', function(){
			setCurrency($(this).val());
		});
	}

	$(document).on('click', '.back', function(e){
		e.preventDefault();
		history.back();
	});

	jQuery.curCSS = jQuery.css;
	if (!!$.prototype.cluetip)
		$('a.cluetip').cluetip({
			local:true,
			cursor: 'pointer',
			dropShadow: false,
			dropShadowSteps: 0,
			showTitle: false,
			tracking: true,
			sticky: false,
			mouseOutClose: true,
			fx: {
		    	open:       'fadeIn',
		    	openSpeed:  'fast'
			}
		}).css('opacity', 0.8);

	if (!!$.prototype.fancybox)
		$.extend($.fancybox.defaults.tpl, {
			closeBtn : '<a title="' + FancyboxI18nClose + '" class="fancybox-item fancybox-close" href="javascript:;"></a>',
			next     : '<a title="' + FancyboxI18nNext + '" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
			prev     : '<a title="' + FancyboxI18nPrev + '" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
		});

    /*************************************/
    /*         my account toggle         */
    /*************************************/
    myAccountClick = $(".header-toggle-call");
    myAccountClick.on('click',function(){
        var myAccountSlide = $(this).next('.header-toggle');
         if (myAccountSlide.is(':visible'))
            myAccountSlide.stop(true, true).slideUp(450);
         else{
            myAccountSlide.stop(true, true).slideDown(450)
         }
         $(myAccountClick).not(this).next('.header-toggle').slideUp();
         return false;
    });
    $(document).on('click',function(e){
        if (e.target.className.split(" ")[0] != 'header-toggle-call' && e.target.className != 'header-toggle'){
            $('.header-toggle').stop(true, true).slideUp(450);
        }
    });

    $('.scroll_top').click(function(){
      $("html, body").animate({ scrollTop: 0 }, 600);
       return false;
     });
     $(window).scroll(function(){
          if ($(this).scrollTop() > 500) {
           $('.scroll_top').fadeIn();
          } else {
           $('.scroll_top').fadeOut();
          }
     });
     
     if($("#home-popular-tabs").length >0){
     	$("#home-popular-tabs li:first-child").addClass('active');
		  
     }
     /* Option3 */
     $('.option3 #call_search_block').toggle(function(){
        $(this).parent().find('#search_block_top').css('width',250+'px');
     }, function(){
            $(this).parent().find('#search_block_top').css('width',0);
        }
     );
     
});


function crumbProcess() {
    if ($('#responsive_slides').length) {
        $('.breadcrumb').appendTo('#responsive_slides');
    }
}
function highdpiInit()
{
	if($('.replace-2x').css('font-size') == "1px")
	{
		var els = $("img.replace-2x").get();
		for(var i = 0; i < els.length; i++)
		{
			src = els[i].src;
			extension = src.substr( (src.lastIndexOf('.') +1) );
			src = src.replace("." + extension, "2x." + extension);

			var img = new Image();
			img.src = src;
			img.height != 0 ? els[i].src = src : els[i].src = els[i].src;
		}
	}
}


// Used to compensante Chrome/Safari bug (they don't care about scroll bar for width)
function scrollCompensate()
{
    var inner = document.createElement('p');
    inner.style.width = "100%";
    inner.style.height = "200px";

    var outer = document.createElement('div');
    outer.style.position = "absolute";
    outer.style.top = "0px";
    outer.style.left = "0px";
    outer.style.visibility = "hidden";
    outer.style.width = "200px";
    outer.style.height = "150px";
    outer.style.overflow = "hidden";
    outer.appendChild(inner);

    document.body.appendChild(outer);
    var w1 = inner.offsetWidth;
    outer.style.overflow = 'scroll';
    var w2 = inner.offsetWidth;
    if (w1 == w2) w2 = outer.clientWidth;

    document.body.removeChild(outer);

    return (w1 - w2);
}

function responsiveResize()
{
	compensante = scrollCompensate();
	if (($(window).width()+scrollCompensate()) <= 767 && responsiveflag == false)
	{
		accordion('enable');
	    accordionFooter('enable');
		responsiveflag = true;
        //mobileHeader('enable');
	}
	else if (($(window).width()+scrollCompensate()) >= 768)
	{
		accordion('disable');
		accordionFooter('disable');
       // mobileHeader('disable');
	    responsiveflag = false;
	}
	if (typeof page_name != 'undefined' && in_array(page_name, ['category']))
		resizeCatimg();
}

function blockHover(status)
{
	$(document).off('mouseenter').on('mouseenter', '.product_list.grid li.ajax_block_product .product-container', function(e){

		if ($('body').find('.container').width() == 1170)
		{
			//var pcHeight = $(this).parent().outerHeight();
			//var pcPHeight = $(this).parent().find('.button-container').outerHeight() + $(this).parent().find('.comments_note').outerHeight() + $(this).parent().find('.functional-buttons').outerHeight();
			//$(this).parent().addClass('hovered').css({'height':pcHeight + pcPHeight, 'margin-bottom':pcPHeight * (-1)});
            $(this).parent().addClass('hovered');
		}
	});

	$(document).off('mouseleave').on('mouseleave', '.product_list.grid li.ajax_block_product .product-container', function(e){
		if ($('body').find('.container').width() == 1170)
			//$(this).parent().removeClass('hovered').css({'height':'auto', 'margin-bottom':'0'});
            $(this).parent().removeClass('hovered');
	});
}

function quick_view()
{
	$(document).on('click', '.quick-view:visible, .quick-view-mobile:visible', function(e)
	{
		e.preventDefault();
		var url = this.rel;
		if (url.indexOf('?') != -1)
			url += '&';
		else
			url += '?';

		if (!!$.prototype.fancybox)
			$.fancybox({
				'padding':  0,
				'width':    1087,
				'height':   610,
				'type':     'iframe',
				'href':     url + 'content_only=1'
			});
	});
}

function bindGrid()
{
	var view = $.totalStorage('display');

	if (!view && (typeof displayList != 'undefined') && displayList)
		view = 'list';

	if (view && view != 'grid')
		display(view);
	else
		$('.display').find('li.view_as_grid').addClass('selected');

	$(document).on('click', '.view_as_grid', function(e){
		e.preventDefault();
		display('grid');
	});

	$(document).on('click', '.view_as_list', function(e){
		e.preventDefault();
		display('list');
	});
}

function display(view)
{
    // process column
    var case_width = 'normal-width';
    case_width = $('.case-width').val();
	if (view == 'list')
	{
		$('#center_column ul.product_list').removeClass('grid').addClass('list row');
		$('#center_column .product_list > li').removeClass('col-xs-12 col-sm-6 col-md-4').addClass('col-xs-12');
		$('#center_column .product_list > li').each(function(index, element) {
			html = '';
			html = '<div class="product-container"><div class="row">';
				html += '<div class="left-block col-xs-4 col-xs-5 col-md-4">';
                    html += '<div class="product-image-container">'+ $(element).find('.product-image-container').html() + '</div>';
                html += '</div>';
				html += '<div class="center-block col-xs-4 col-xs-7 col-md-4">';
                    html += '<div class="center-block-wrap">';
					html += '<div class="product-flags">'+ $(element).find('.product-flags').html() + '</div>';
					html += '<h5 itemprop="name">'+ $(element).find('h5').html() + '</h5>';
                    html += '<div class="functional-buttons pull-right">' + $(element).find('.functional-buttons').html() + '</div>';
                    var price = $(element).find('.right-block .content_price').html();       // check : catalog mode is enabled
					if (price != null) {
						html += '<div class="content_price col-xs-7 col-md-8">'+ price + '</div>';
					}
                    var rating = $(element).find('.comments_note').html(); // check : rating
					if (rating != null) {
						html += '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="comments_note col-xs-7 col-md-8">'+ rating + '</div>';
					}
                    var itemcode = $(element).find('.itemcode').html();	// check : catalog mode is enabled
                    if (itemcode != null) {
						html += '<span class="itemcode col-xs-7 col-md-8">'+ itemcode +'</span>';
					}
                    var availability = $(element).find('.availability').html();	// check : catalog mode is enabled
                    if (availability != null) {
						html += '<span class="availability col-xs-7 col-md-8">'+ availability +'</span>';
					}
                    html += '<p class="product-desc">'+ $(element).find('.product-desc').html() + '</p>';
					html += '<p class="product-desc-list">'+ $(element).find('.product-desc-list').html() + '</p>';
					var colorList = $(element).find('.color-list-container').html();
					if (colorList != null) {
						html += '<div class="color-list-container">'+ colorList +'</div>';
					}
                    html += '</div>';
				html += '</div>';
			html += '</div></div>';
		$(element).html(html);
		});
		$('.display').find('li.view_as_list').addClass('selected');
		$('.display').find('li.view_as_grid').removeClass('selected');
		$.totalStorage('display', 'list');
	}
	else
	{
	    // process column
		$('#center_column ul.product_list').removeClass('list').addClass('grid row');
        if (case_width == 'full-width'){
            $('#center_column .product_list > li').removeClass('col-xs-12').addClass('col-xs-12 col-sm-4 col-md-3');
        }else if (case_width == 'both-width'){
            $('#center_column .product_list > li').removeClass('col-xs-12').addClass('col-xs-12 col-sm-12 col-md-6');
        }else {
            $('#center_column .product_list > li').removeClass('col-xs-12').addClass('col-xs-12 col-sm-6 col-md-4');
        }

		$('#center_column .product_list > li').each(function(index, element) {
		html = '';
		html += '<div class="product-container">';
			html += '<div class="left-block">' + $(element).find('.left-block').html() + '</div>';
			html += '<div class="right-block">';
				html += '<div class="product-flags">'+ $(element).find('.product-flags').html() + '</div>';
				html += '<h5 itemprop="name">'+ $(element).find('h5').html() + '</h5>';
				
				html += '<p itemprop="description" class="product-desc">'+ $(element).find('.product-desc').html() + '</p>';
                html += '<p class="product-desc-list">'+ $(element).find('.product-desc-list').html() + '</p>';
				var price = $(element).find('.center-block .content_price').html(); // check : catalog mode is enabled
				if (price != null) {
					html += '<div class="content_price">'+ price + '</div>';
				}
                var rating = $(element).find('.comments_note').html(); // check : rating
				if (rating != null) {
					html += '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="comments_note">'+ rating + '</div>';
				}
				var colorList = $(element).find('.color-list-container').html();
				if (colorList != null) {
					html += '<div class="color-list-container">'+ colorList +'</div>';
				}
                var itemcode = $(element).find('.itemcode').html();	// check : catalog mode is enabled
                if (itemcode != null) {
					html += '<span class="itemcode col-md-8">'+ itemcode +'</span>';
				}
				var availability = $(element).find('.availability').html(); // check : catalog mode is enabled
				if (availability != null) {
					html += '<span class="availability">'+ availability +'</span>';
				}
			html += '</div>';
		html += '</div>';
		$(element).html(html);
		});
		$('.display').find('li.view_as_grid').addClass('selected');
		$('.display').find('li.view_as_list').removeClass('selected');
		$.totalStorage('display', 'grid');
	}
}

function dropDown()
{
	elementClick = '#header .current';
	elementSlide =  'ul.toogle_content';
	activeClass = 'active';

	$(elementClick).on('click', function(e){
		e.stopPropagation();
		var subUl = $(this).next(elementSlide);
		if(subUl.is(':hidden'))
		{
			subUl.slideDown();
			$(this).addClass(activeClass);
		}
		else
		{
			subUl.slideUp();
			$(this).removeClass(activeClass);
		}
		$(elementClick).not(this).next(elementSlide).slideUp();
		$(elementClick).not(this).removeClass(activeClass);
		e.preventDefault();
	});

	$(elementSlide).on('click', function(e){
		e.stopPropagation();
	});

	$(document).on('click', function(e){
		e.stopPropagation();
		var elementHide = $(elementClick).next(elementSlide);
		$(elementHide).slideUp();
		$(elementClick).removeClass('active');
	});
}

function accordionFooter(status)
{
	if(status == 'enable')
	{
		$('#footer .footer-block h4').on('click', function(){
			$(this).toggleClass('active').parent().find('.toggle-footer').stop().slideToggle('medium');
		})
		$('#footer').addClass('accordion').find('.toggle-footer').slideUp('fast');
	}
	else
	{
		$('.footer-block h4').removeClass('active').off().parent().find('.toggle-footer').removeAttr('style').slideDown('fast');
		$('#footer').removeClass('accordion');
	}
}

function accordion(status)
{
	leftColumnBlocks = $('#left_column');
	if(status == 'enable')
	{
		$('#right_column .block .title_block, #left_column .block .title_block, #left_column #newsletter_block_left h4').on('click', function(){
			$(this).toggleClass('active').parent().find('.block_content').stop().slideToggle('medium');
		});
        // option2
        $('.title_block_option2').on('click', function(){
			$(this).toggleClass('active').parent().find('.block_content').stop().slideToggle('medium');
            
		});
        $('#special_block_right .title_block_option2').on('click', function(){
			$(this).toggleClass('active').parent().find('.products-block').stop().slideToggle('medium');
		});
        // option5
        $('.title_block_option5').on('click', function(){
			$(this).toggleClass('active').parent().find('.block_content').stop().slideToggle('medium');
            
		});
        $('#special_block_right .title_block_option5').on('click', function(){
			$(this).toggleClass('active').parent().find('.products-block').stop().slideToggle('medium');
		});
        
		$('#right_column, #left_column').addClass('accordion');//.find('.block .block_content').slideUp('medium');
        $('#tags_block_left').find('.block_content').slideUp('fast');
        $('#best-sellers_block_right').find('.block_content').slideUp('slow');
	}
	else
	{
		$('#right_column .block .title_block, #left_column .block .title_block, #left_column #newsletter_block_left h4').removeClass('active').off().parent().find('.block_content').removeAttr('style').slideDown('fast');
        // option2
        $('.title_block_option2').removeClass('active').off().parent().find('.block_content').removeAttr('style').slideDown('fast');
        $('.title_block_option5').removeClass('active').off().parent().find('.block_content').removeAttr('style').slideDown('fast');
		$('#left_column, #right_column').removeClass('accordion');
	}
}

function resizeCatimg()
{
	var div = $('.cat_desc').parent('div');
	var image = new Image;
	$(image).load(function(){
	    var width  = image.width;
	    var height = image.height;
		var ratio = parseFloat(height / width);
		var calc = Math.round(ratio * parseInt(div.outerWidth(false)));
		div.css('min-height', calc);
	});
	if (div.length)
		image.src = div.css('background-image').replace(/url\("?|"?\)$/ig, '');
}


/* Ovic - Process mobile header */

function mobileHeader(flag_mobile) {
    if (flag_mobile == 'enable') {
        // Enable mobile header
        $('.option1 #currencies-block-top, .option1 #languages-block-top, .option1 .shopping_cart_container').appendTo('.option1 #enable_mobile_header');
        $('.option5 #currencies-block-top, .option5 #languages-block-top, .option5 .shopping_cart_container').appendTo('.option5 #enable_mobile_header');
        $('.option2 #currencies-block-top, .option2 #languages-block-top, .option2 .shopping_cart_container').appendTo('.option1 #enable_mobile_header');
        $('#enable_mobile_header').show();
    }else if (flag_mobile == 'disable') {
        // Disable mobile header
        $('.option1 #currencies-block-top, .option1 #languages-block-top').insertAfter('.option1 header .nav .header_user_info');
        $('.option1 .shopping_cart_container').insertAfter('.option1 #search_block_top');
        $('.option1 #enable_mobile_header').hide();
        $('.option5 #currencies-block-top, .option5 #languages-block-top').insertAfter('.option5 header .nav .header_user_info');
        $('.option5 .shopping_cart_container').insertAfter('.option5 #search_block_top');
        $('.option5 #enable_mobile_header').hide();
        $('.option4 #currencies-block-top, .option4 #languages-block-top').insertAfter('.option4 header .nav .header_user_info');
        $('.option4 .shopping_cart_container').insertAfter('.option4 #search_block_top');
        $('.option4 #enable_mobile_header').hide();
        
    }
}
function xsHeader(flag_mobile) {
    if (flag_mobile == 'enable') {
        // Enable mobile header
        $('.option1 #currencies-block-top, .option1 #languages-block-top').insertAfter('.option1 #search_block_top');
        $('.option5 #currencies-block-top, .option5 #languages-block-top').insertAfter('.option5 #search_block_top');
        $('.option4 #currencies-block-top, .option4 #languages-block-top').insertAfter('.option4 #search_block_top');
    }else if (flag_mobile == 'disable') {
        // Disable mobile header
        $('.option1 #currencies-block-top, .option1 #languages-block-top').insertAfter('.option1 header .nav #blockhtml_displayNav');
        $('.option4 #currencies-block-top, .option4 #languages-block-top').insertAfter('.option4 header .nav #blockhtml_displayNav');
        $('.option5 #currencies-block-top, .option5 #languages-block-top').insertAfter('.option5 header .nav #blockhtml_displayNav');
    }
}
function xsHeaderOption3(flag_mobile) {
    if (flag_mobile == 'enable') {
        // Enable mobile header
        $('.option3 #nav_option3').insertBefore('.option3 #top-header');
    }else if (flag_mobile == 'disable') {
        // Disable mobile header
        $('.option3 #nav_option3').insertAfter('.option3 #top-header');
    }
}
function responsiveMobileHeader()
{
	compensante = scrollCompensate();
	if (($(window).width()+scrollCompensate()) <= 480)
	{
        mobileHeader('enable');
	}
	else if (($(window).width()+scrollCompensate()) > 480)
	{
        mobileHeader('disable');
	}
}
function responsiveHeaderXs()
{
	compensante = scrollCompensate();
	if (($(window).width()+scrollCompensate()) <= 767)
	{
        xsHeader('enable');
        xsHeaderOption3('enable');
	}
	else if (($(window).width()+scrollCompensate()) > 767)
	{
        xsHeader('disable');
        xsHeaderOption3('disable');
        //mobileHeaderOption2('ipadv');
	}
}
function mobileHeaderOption2(flag_mobile_option2)
{
    var header_selector = $('.option2 #right_header');
    var cur_selector = $('.option2 #right_header #currencies-block-top');
    var lang_selector = $('.option2 #right_header #languages-block-top');
    var userinf_selector = $('.option2 #right_header .header_user_info');
    var cart_selector =  $('.option2 #right_header .shopping_cart_container');
    var search_selector = $('.option2 #right_header #search_block_top');
    
    if (flag_mobile_option2 == 'ipadv') {
        userinf_selector.insertAfter(header_selector);
        lang_selector.insertAfter(header_selector);
        cur_selector.insertAfter(header_selector);
        cart_selector.insertAfter(header_selector);
        search_selector.insertAfter(header_selector);
    }else if (flag_mobile_option2 == 'mobile') {
        
    }
    
}
function height_slider_option1 (){
    var wwidth = $(window).width();
    if (wwidth >= 1200) {
        var slider_height = $('#homepage-slider').height();
        $('.option1 .home-slider-left-inner').height(slider_height);
        $('.option5 .home-slider-left-inner').height(slider_height);
    }else {
        $('.option1 .home-slider-left-inner').removeAttr('style');
        $('.option5 .home-slider-left-inner').removeAttr('style');
    }
}