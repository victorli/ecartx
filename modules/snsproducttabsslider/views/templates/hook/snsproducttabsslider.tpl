{**
* 2015 SNSTheme
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
*  @author    SNSTheme <contact@snstheme.com>
*  @copyright 2015 SNSTheme
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of SNSTheme
*}
<div class="wrap">
		{assign var="moduleclass_sfx" value=( isset( $SNSTS_CLASSSFX ) ) ?  $SNSTS_CLASSSFX : ''}
		{if $SNSTS_PRETEXT}
			<div class="pretext">
				{$SNSTS_PRETEXT}
			</div>
		{/if}
		<div id="sns_producttabsslider" class="sns-snsproducttabsslider sns-slider  tabajax sns-producttabs {$moduleclass_sfx|escape:'html':'UTF-8'}">
			<div class="block block-snsproducttabsslider">
				{* <div class="block-title">{$SNSTS_TITLE|escape:'htmlall':'UTF-8'}</div> *}
				{if isset($tabs) AND $tabs}
				<div class="sns-pdt-head custom-tab-head">
					<div class="sns-pdt-nav">
						
						<h3>{l s = 'New Product' mod='snsproducttabsslider'}</h3>
						
						<div class="tab-title">	
							<h3 class="nav-tabs pdt-nav">
								{foreach from=$tabs item=tab}
									{assign var="tab_active" value=( isset( $tab.first_select ) ) ?  $tab.first_select : ''}
									<span class="tab {$tab_active}" data-id="{$tab.tab_unique}" data-catid="{$tab.tab_catid}" data-type="{$tab.tab_type}">
										<span class="title-navi"><a href="#" data-toggle="tab">{$tab.tab_name}</a></span>
									</span>
								{/foreach}
							</h3>

					{*	<!-- 	<ul>
								<li class="dropdown pull-left tabdrop" style="display:none;" >
									<a href="#" data-toggle="dropdown" class="dropdown-toggle">
										<span class="display-tab">{l s ='Tabs Product'}</span>
									</a>
									<div class="dropdown-menu">
										{foreach from=$tabs item=tab}
											{assign var="tab_active" value=( isset( $tab.first_select ) ) ?  $tab.first_select : ''}
											<span class="tab {$tab_active}" data-id="{$tab.tab_unique}" data-catid="{$tab.tab_catid}" data-type="{$tab.tab_type}">
												<span class="title-navi"><a href="#" data-toggle="tab">{$tab.tab_name}</a></span>
											</span>
										{/foreach}
									</div>
								</li>
							</ul> -->
					*}
						
					
						</div>
					</div>
				</div>
				{/if}
				 <div class="sns-pdt-container tab-content ">

				 	<div class="loading"></div>
				 	
				 	<div class="navslider">
						<a class="prev" href="#"></a>
						<a class="next" href="#"></a>
					
					</div>

					{foreach $tabs as $item}
						 {assign var="tab_content_active" value=( isset( $item.first_select ) ) ?  $item.first_select : ''}
						 {assign var="products" value=(isset($item.child))?$item.child:''}
						<div class="tab-content-inner tab-content-{$item.tab_unique} {$tab_content_active}" {if $tab_content_active == ''} style="overflow:hidden; height: 0;"{/if}>
							{if !empty($products)}
								{if $products == 'emptyproduct'}
									<p class="alert alert-info">{l s="There are no products matching to show."}</p>
								{else}
									{include file="./items.tpl"}
								{/if}
							{else}
								<div class="process-loading"></div>
							{/if}
						</div>
					{/foreach}
				 </div>
			</div>
			<script type="text/javascript">
				jQuery(document).ready(function ($) {
				    ;(function (el) {
						var $el = $(el), $tab = $('.tab', $el),
						$tab_content = $('.tab-content',$el),
						$tab_content_inner = $('.tab-content-inner', $tab_content);
						$tab.on('click.tab_cat', function () {
							var $this = $(this);
							if ($this.hasClass('active') ) return;
							
							$tab.removeClass('active');
							$this.addClass('active');
							var id_tab = $this.attr('data-id'), $tab_content_active = $('.tab-content-'+id_tab, $el);
							{literal}
							$tab_content_inner.removeClass('active').css({'overflow':'hidden', 'height':'0'});
							$tab_content_active.addClass('active').css({'overflow':'', 'height':''});
							{/literal}
							var $loading = $('.process-loading', $tab_content_active);
							var loaded = $tab_content_active.hasClass('ltabs-items-loaded');
							if (!$this.hasClass('tab-loaded') && !$this.hasClass('tab-process')) {
								$this.addClass('tab-process');
								$loading.show();
								ajax_url = baseDir + 'modules/snsproducttabsslider/snsproducttabsslider-ajax.php';
								$.ajax({
									type: 'POST',
									url: ajax_url,
									data: {
										module_name: 'snsproducttabsslider',
										is_ajax: 1,
										ajax_start: 0,
										categoryid: $this.attr('data-catid'),
										data_type: $this.attr('data-type')
									},
									success: function (data) {
										if (data.productList != '') {
											$(data.productList).insertBefore($loading); 
											$this.addClass('tab-loaded').removeClass('tab-process');
											$loading.remove();
										} else {
											$('<p class="alert alert-info">{l s="There are no products matching to show."}</p>').insertBefore($loading); 
											$loading.remove();
										}
									},
									dataType: 'json'
								});
							}
						});
					})('#sns_producttabsslider');
				});
			</script>
		</div>
		{if $SNSTS_POSTEXT}
			<div class="posttext">
				{$SNSTS_POSTEXT}
			</div>
		{/if}
	
</div>

