jQuery(document).ready(function(){
	if($('#snsp-tabs').length) {
		$('#snsp-tabs').show();
		if($.cookie('snsp_tab_active') != undefined && $('[id^="'+$.cookie('snsp_tab_active')+'"]').length)
			var tab = $('#snsp-tabs .tab[data-tab="'+$.cookie('snsp_tab_active')+'"]');
		else 
			var tab = $('#snsp-tabs .tab:first');
		
		var tabId = tab.attr('data-tab');
		tab.addClass('active');
		$('[id^="'+tabId+'"]').addClass('active');
		
		
		$('#snsp-tabs').find('.tab').on('click', function() {
			hidetabs();
			$(this).addClass('active');
			$.cookie('snsp_tab_active', $(this).attr('data-tab'));
			var tabId = $(this).data('tab');
			$('[id^="'+tabId+'"]').addClass('active');
		});
	}
	$('.list-item').sortable({
		handle: '.handle'
	});
	$(document).on('click', '.remove-additem', function(){
		$(this).parents('.additem-row').remove();
	});
});
function hidetabs() {
	$('#snsp-tabs').find('.tab').each(function(index){
		var tabId = $(this).data('tab');
		$(this).removeClass('active');
		$('[id^="'+tabId+'"]').removeClass('active');
	});
}

// img field
$(document).on('click', '.sns-imgfield-open', function(e){
	e.preventDefault();
	var _btn = $(this);
	var href = baseUri;
	href += 'modules/snsnazictheme/filemanager/dialog.php';
	href += '?ad_basename='+ad_basename+'&type=1';
	href += '&field_id='+_btn.attr('data-field_id');
	$.fancybox({
		'width'		: 900,
		'height'	: 600,
		'minHeight'	: 600,
		'href'		: href,
		'autoHeight'	: true,
		'type'		: 'iframe',
		'autoScale'    	: false
    });
});
$(document).on('click', '.sns-imgfield-remove', function(){
	var _btn = $(this);
	var _wrap = _btn.parents('.sns-imgfield');
	var _input = _wrap.find('.sns-imgfield-input');
	_input.val('');
});
$(document).on('click', '.img-preview', function(e){
	var _this = $(this);
	var href = $('#'+_this.attr('data-field_id')).val();
	
	$("<img>", {
	    src: href,
	    error: function() {
			e.preventDefault();
			alert('Image does not exist !!');
	    },
	    load: function() {
			$.fancybox({
				'href' : href
			});
	    }
	});
});
// end img field
