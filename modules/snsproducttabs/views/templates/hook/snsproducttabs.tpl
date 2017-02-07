{assign var="moduleclass_sfx" value=( isset( $SNSPRT_CLASSSFX ) ) ?  $SNSPRT_CLASSSFX : ''}
{assign var="lb_allready"  value="{l s='All ready' mod='snsproducttabs'}"}
{assign var="lb_more"  value="{l s='View more item' mod='snsproducttabs'}"}
{assign var="lb_loading"  value="{l s='Loading...' mod='snsproducttabs'}"}
{if $SNSPRT_PRETEXT}
	<div class="pretext">
		{$SNSPRT_PRETEXT}
	</div>
{/if}
<div id="sns_producttabs" class="tabajax sns-producttabs mrb30 {$moduleclass_sfx}">
	{*
	{if $SNSPRT_TITLE}
		<h3 class="block-title">{$SNSPRT_TITLE|escape:'htmlall':'UTF-8'}</h3>
	{/if}
	*}
	 {include file="./tabs.tpl"}
	 <div class="sns-pdt-container tab-content">
		{foreach $tabs as $item}
			 {assign var="tab_content_active" value=( isset( $item.first_select ) ) ?  $item.first_select : ''}
			 {assign var="products" value=(isset($item.child))?$item.child:''}
			<div class="tab-content-inner tab-content-{$item.tab_unique} {$tab_content_active}">
				{if !empty($products)}
					{if $products == 'emptyproduct'}
						<p class="alert alert-info">{l s="There are no products matching to show."}</p>
					{else}
						{include file="./items.tpl"}
					{/if}
				{else}
					<div class="process-loading"></div>
				{/if}
				{assign var="cls_loaded"  value=($item['count'] < $SNSPRT_NUMDISPLAY) ? " loaded " : ""}
				{assign var="label"  value=($item['count'] < $SNSPRT_NUMDISPLAY) ? $lb_allready : $lb_more}
				<div class="button-load">
					<div class="tab-loadmore pdt-loadmore"
						 data-id="{$item.tab_unique}" data-catid="{$item.tab_catid}" data-type="{$item.tab_type}"
						 data-rl_start="{$SNSPRT_NUMDISPLAY}"
						 data-rl_total="{$item['count']}"
						 data-rl_allready="All Ready"
						 data-rl_load="{$SNSPRT_NUMLOAD}">
						 <div class="btn-loadmore">
							<div class="ltabs-loadmore-btn {$cls_loaded}" data-label-load="{$label}"></div>
						 </div>
					</div>
				</div>
			</div>
		{/foreach}
	 </div>
	<script type="text/javascript">
		jQuery(document).ready(function ($) {
			function setAnimate(el){
				$_items = $('.item-animate',el);
				$('.tab-loadmore',el).fadeOut('fast');
				$_items.each(function(i){
					$(this).attr("style", "-webkit-animation-delay:" + i * 300 + "ms;"
			                + "-moz-animation-delay:" + i * 300 + "ms;"
			                + "-o-animation-delay:" + i * 300 + "ms;"
			                + "animation-delay:" + i * 300 + "ms;");
			        if (i == $_items.size() -1) {
			            $(".product_list", el).addClass("play");
			            $('.tab-loadmore', el).fadeIn(i*0.3);
			        }
				});
			}
			function setAnimated(el){
			    $(".product_list", el).removeClass("play");
			    $('.item',el).removeAttr('style');
				$('.item',el).addClass('animated').removeClass('item-animate');
			}
			function removeAnimate(el){
			    $(".product_list", el).removeClass("play");
			    $('.item',el).removeClass('animated').addClass('item-animate').removeAttr('style');
			}
			setAnimate($('.tab-content-inner.active'));
		    ;(function (el) {
				var $el = $(el), $tab = $('.tab', $el),
					$tab_content = $('.tab-content',$el),
					$tab_content_inner = $('.tab-content-inner', $tab_content);
					$tab.on('click.tab_cat', function () {
						var $this = $(this);
						if ($this.hasClass('active') ) return;
						
						removeAnimate($tab_content);
						
						$tab.removeClass('active');
						$this.addClass('active');
						var id_tab = $this.attr('data-id'), $tab_content_active = $('.tab-content-'+id_tab, $el);
						$tab_content_inner.removeClass('active');
						$tab_content_active.addClass('active');
						var $loading = $('.process-loading', $tab_content_active);
						var loaded = $tab_content_active.hasClass('ltabs-items-loaded');
						if (!$this.hasClass('tab-loaded') && !$this.hasClass('tab-process')) {
							$this.addClass('tab-process');
							$loading.show();
							ajax_url = baseDir + 'modules/snsproducttabs/snsproducttabs-ajax.php';
							$.ajax({
								type: 'POST',
								url: ajax_url,
								data: {
									module_name: 'snsproducttabs',
									is_ajax: 1,
									ajax_start: 0,
									categoryid: $this.attr('data-catid'),
									data_type: $this.attr('data-type')
								},
								success: function (data) {
									if (data.productList != '') {
										$(data.productList).insertBefore($('.tab-loadmore',$tab_content_active)); 
										$this.addClass('tab-loaded').removeClass('tab-process');
										$loading.remove();
										setAnimate($tab_content_active);
									} else {
										$('<p class="alert alert-info">{l s="There are no products matching to show."}</p>').insertBefore($('.button-load',$tab_content_active)); 
										$('.button-load', $tab_content_active).remove();
										$loading.remove();
									}
								},
								dataType: 'json'
							});
						}else{
							setAnimate($('.tab-content-inner.active', $el));
						}
					});
					var $btn_loadmore = $('.ltabs-loadmore-btn ', $el);
					$btn_loadmore.on('click.loadmore', function () {
						var $this = $(this);
						if ($this.hasClass('loaded') || $this.hasClass('loading')) {
							return false;
						} else {
							$this.addClass('loading');
							$this.attr('data-label-load', '{$lb_loading}');
							var tab_content_active = $this.parents('.tab-content-inner').filter('.active');		
							$('.item', tab_content_active).addClass('animated').removeClass('item-animate');

							ajax_url = baseDir + 'modules/snsproducttabs/snsproducttabs-ajax.php';
							$.ajax({
								type: 'POST',
								url: ajax_url,
								data: {
									module_name: 'snsproducttabs',
									is_ajax: 1,
									ajax_start: $this.parents('.pdt-loadmore').attr('data-rl_start'),
									categoryid: $this.parents('.pdt-loadmore').attr('data-catid'),
									data_type: $this.parents('.pdt-loadmore').attr('data-type'),
									nbload: $this.parents('.pdt-loadmore').attr('data-rl_load')
								},
								success: function (data) {
									if (data.productList != '') {
										$(data.productList).children().insertAfter($('.product_list > .item', tab_content_active).nextAll().last());
										updateStatus(tab_content_active);
										setAnimate(tab_content_active);
									}
								}, dataType: 'json'
							});
						}
						return false;
					});
					
					function updateStatus($el) {
						var $btn_loadmore = $('.ltabs-loadmore-btn ', $el);
						$btn_loadmore.removeClass('loading');
						var countitem = $('.product_list > .item', $el).length;
						$btn_loadmore.parents('.pdt-loadmore').attr('data-rl_start', countitem);
						var rl_total = $btn_loadmore.parents('.pdt-loadmore').attr('data-rl_total');
						var rl_load = $btn_loadmore.parents('.pdt-loadmore').attr('data-rl_load');
						var rl_allready = $btn_loadmore.parents('.pdt-loadmore').attr('data-rl_allready');

						if (countitem >= rl_total) {
							$btn_loadmore.addClass('loaded');
							$btn_loadmore.attr('data-label-load', '{$lb_allready}');
						}else{
							$btn_loadmore.attr('data-label-load', '{$lb_more}');
						}
					}
			})('#sns_producttabs');	
		});
	</script>
</div>
{if $SNSPRT_POSTEXT}
	<div class="posttext">
		{$SNSPRT_POSTEXT}
	</div>
{/if}
