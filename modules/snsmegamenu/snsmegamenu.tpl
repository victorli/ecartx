{if $MENU != ''}
	<!-- Menu -->

	<div id="sns_custommenu" class="visible-md visible-lg">
		<ul class="mainnav">
			{$MENU}
		</ul>
	</div>
		<!-- {$SNSMM_STICKYMENU} -->
	
	{if $SNSMM_RESMENU eq 1}
		{assign var='momenuclass' value='menu-offcanvas'}
	{else}
		{assign var='momenuclass' value='menu-collapse'}
	{/if}
	<div id="sns_mommenu" class="{$momenuclass} hidden-md hidden-lg">
		<span class="btn2 btn-navbar leftsidebar">
			<i class="fa fa-align-left"></i>
		    <span class="overlay"></span>
		</span>
		{if $SNSMM_RESMENU eq 1}
			<span class="btn2 btn-navbar offcanvas">
				<i class="fa fa-align-justify"></i>
			    <span class="overlay"></span>
			</span>
		{else}
			<span class="btn2 btn-navbar menusidebar collapsed" data-toggle="collapse" data-target="#menu_collapse">
				<i class="fa fa-align-justify"></i>
			    <span class="overlay"></span>
			</span>
		{/if}
		<span class="btn2 btn-navbar rightsidebar">
			<i class="fa fa-align-right"></i>
		    <span class="overlay"></span>
		</span>
		{if $SNSMM_RESMENU eq 1}
			<div id="menu_offcanvas" class="offcanvas"></div>
		{else}
			<div class="collapse_wrap">
				<div id="menu_collapse" class="collapse"></div>
			</div>
		{/if}
	</div>
	<script>
		jQuery(document).ready(function($){
			{if $SNSMM_RESMENU eq 1}
				$('#menu_offcanvas').html($('#sns_custommenu').html());
				$('#sns_mommenu').find('.wrap_dropdown.fullwidth').remove();
				$('#sns_mommenu').find('li > .wrap_submenu > ul').unwrap();
				$('#menu_offcanvas').SnsAccordion({
					accordion: false,
					expand: false,
					el_content: 'ul, .wrap_submenu',
					btn_open: '<i class="fa fa-plus"></i>',
					btn_close: '<i class="fa fa-minus"></i>'
				});
				$('#sns_mommenu .btn2.offcanvas').on('click', function(){
					if($('#menu_offcanvas').hasClass('active')){
						$(this).find('.overlay').fadeOut(250);
						$('#menu_offcanvas').removeClass('active');
						$('body').removeClass('show-sidebar show-menumobile');
					} else {
						$('#menu_offcanvas').addClass('active');
						$(this).find('.overlay').fadeIn(250);
						$('body').addClass('show-sidebar show-menumobile');
					}
				});
			{else}
				$('#menu_collapse').html($('#sns_custommenu').html());
				$('#sns_mommenu').find('.wrap_dropdown.fullwidth').remove();
				$('#sns_mommenu').find('li > .wrap_submenu > ul').unwrap();
				$('#menu_collapse').SnsAccordion({
					btn_open: '<i class="fa fa-plus"></i>',
					btn_close: '<i class="fa fa-minus"></i>'
				});
				$('#sns_mommenu .btn2.menusidebar').on('click', function(){
					if($(this).hasClass('active')){
						$(this).find('.overlay').fadeOut(250);
						$(this).removeClass('active');
						$('body').removeClass('show-sidebar show-menumobile');
					} else {
						$(this).addClass('active');
						$(this).find('.overlay').fadeIn(250);
						$('body').addClass('show-sidebar show-menumobile');
					}
				});
			{/if}
		});
	</script>
	<!--/ Menu -->
{/if}