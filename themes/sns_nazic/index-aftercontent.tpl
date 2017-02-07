{*
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
*}




<div id="sns_botsl" class="wrap">
	<div class="container">
		<div class="botsl_in">
			<div class="col-sm-4">
				
				{hook h="displayBootsSL2"}

			</div>
			<div class="col-sm-4">
				<div class="block">
					{if isset($SNS_NAZ_CONTACT_STATUS) && $SNS_NAZ_CONTACT_STATUS && {$SNS_NAZ_CONTACT_STATUS} == '1'}
						<div class="block-title"><strong>{l s = "Find us"}</strong></div>
						<div id="contact_gmap">
							<div id="google_map" style="height: 230px"></div>
							{include file="$tpl_dir./block-findus.tpl"}
							
							<div class="contact-info">
								<ul class="fa-ul">
									<li><i class="fa-li" data-icon="&#xe01d;"></i>{$SNS_NAZ_STORE_ADDRESS}</li>
									<li><i class="fa-li" data-icon="&#xe00b;"></i>{$SNS_NAZ_STORE_PHONE}</li>
									<li><i class="fa-li" data-icon="&#xe010;"></i>
									<a href="mailto:{$SNS_NAZ_STORE_EMAIL}">{$SNS_NAZ_STORE_EMAIL}</a>	
									</li>
								</ul>
							</div>
							
						</div>
					{/if}
				</div>

			</div>
			<div class="col-sm-4">
				<div class="block">
				{if isset($SNS_NAZ_STATICBLOCK) && $SNS_NAZ_STATICBLOCK }
					<div class="block-title"><strong>{l s = "Custom Block"}</strong></div>
					{$SNS_NAZ_STATICBLOCK}
				{/if}
				</div>
			</div>
		</div>
	</div>
</div>

{*

<!-- {if isset($SNS_NAZ_CATSLIDE) && $SNS_NAZ_CATSLIDE && $SNS_NAZ_CATSLIDE_STATUS}
	<div id="sns_category" class="sns-slidercategory" style="background: url('{$SNS_NAZ_CATSLIDE_BGIMG}') no-repeat scroll 0 0 / cover  rgba(0, 0, 0, 0);">
		<div class="wap-slidercategory">
			<div class="container">
				{if $SNS_NAZ_CATSLIDE_TITLE}
				<div class="block_head_center">
					<div class="block-title"><span><span>{$SNS_NAZ_CATSLIDE_TITLE|escape:'html':'UTF-8'}</span></span></div>
				</div>
				{/if}
				<div class="category-slider row">
					<div class="inner">
					{foreach from=$SNS_NAZ_CATSLIDE item=cat name=cats}
						<div class="item">
							<a href="{$cat.link}" class="image">
								<i class="fa fa-plus"></i>
								<img src="{$cat.image}" alt="{$cat.name|escape:'html':'UTF-8'}">
							</a>
							<a href="{$cat.link}" target="_blank" class="title">{$cat.name|escape:'html':'UTF-8'}</a>
						</div>
					{/foreach}
					</div>
				</div>
			</div>
		</div>
		<script>
			jQuery(document).ready(function($) {
				$(window).load(function(){
					$('#sns_category .category-slider .inner').owlCarousel({
					    loop:true,
					    responsiveClass:true,
					    nav: true,
					    dots: true,
			        	items: 4,
						responsive : {
						    0 : { items: 2 },
						    480 : { items: 3 },
						    768 : { items: 4 }
						}
					});
				});
			});
		</script>
	</div>
{/if}

{hook h="displayBotsl"}
{if isset($SNS_NAZ_BANNER_BOTTOM) && $SNS_NAZ_BANNER_BOTTOM}
<div class="sns-snsbannerbottom banner2-bottom">
	{$SNS_NAZ_BANNER_BOTTOM}
</div>
{/if}
<div class="wrap" id="sns_botsl2">
	<div class="container">
		<div class="row">
			{if isset($HOOK_BOTSL2) && $HOOK_BOTSL2}
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				{$HOOK_BOTSL2}
			</div>
			{/if}
			
			{if isset($SNS_NAZ_OURBRANDS) && $SNS_NAZ_OURBRANDS && $SNS_NAZ_OURBRAND_STATUS}
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div id="sns_partners">
					{if $SNS_NAZ_OURBRAND_TITLE}
					<div class="block_head">
						<div class="block-title">
							<span><span>{$SNS_NAZ_OURBRAND_TITLE}</span></span>
						</div>
					</div>
					{/if}
					<div class="partners_slider slider-wrap">
						<div class="partners_slider_in">
							<div class="our_partners">
								{counter start=0 skip=1 print=false name=i assign="i"}
								{assign  var="total" value=$SNS_NAZ_OURBRANDS|@count}
								{foreach from=$SNS_NAZ_OURBRANDS item=brand name=brands}
									{if ($i % 2) == 0}<div class="row-item">{/if}
										<div class="wrap">
											<div class="wrap_in">
												<a href="{$brand.link}"{if $brand.target} target="{$brand.target}"{/if} title="{$brand.title}">
													<img src="{$brand.logo}" alt="{$brand.title}">
												</a>
											</div>
										</div>
									{counter name=i}
									{if ($i % 2) == 0 || $i == $total}</div>{/if}
								{/foreach}
							</div>
						</div>
					</div>
				</div>
				<script type="text/javascript">
					$(window).load(function(){
						$('#sns_partners .our_partners').owlCarousel({
						    loop:true,
						    responsiveClass:true,
						    nav: true,
						    dots: true,
				        	items: 3,
							responsive : {
							    0 : { items: 2 },
							    480 : { items: 3 },
							    768 : { items: 2 },
							    992 : { items: 3 }
							}
						});
					});
				</script>
			</div>
			{/if}
		</div>
	</div>
</div> -->

*}





















