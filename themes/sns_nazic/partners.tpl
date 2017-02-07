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


{if isset($SNS_NAZ_OURBRANDS) && $SNS_NAZ_OURBRANDS && $SNS_NAZ_OURBRAND_STATUS}
	<div id="sns_partners">
		<div class="container">
			<div class="block-partner">
			{*
				<!-- {if $SNS_NAZ_OURBRAND_TITLE}
					<h3 class="block-title">{$SNS_NAZ_OURBRAND_TITLE}</h3>
				{/if} -->
			*}


				
				<div class="partners_slider slider-wrap block-content">
					<div class="navslider">
						<a class="prev" title="Prev" href="#"></a>
						<a class="next" title="Next" href="#"></a>
					</div>

					<div class="partners_slider_in">
						<div class="loading"></div>

						<div class="our_partners preload" >
							{counter start=0 skip=1 print=false name=i assign="i"}
							{assign  var="total" value=$SNS_NAZ_OURBRANDS|@count}
							{foreach from=$SNS_NAZ_OURBRANDS item=brand name=brands}
							{*	{if ($i % 2) == 0}<div class="row-item">{/if} *}
									<div class="wrap">
										<div class="wrap_in">
											<a href="{$brand.link}"{if $brand.target} target="{$brand.target}"{/if} title="{$brand.title}">
												<img src="{$brand.logo}" alt="{$brand.title}">
											</a>
										</div>
									</div>
							{*
								{counter name=i}
								{if ($i % 2) == 0 || $i == $total}</div>{/if}
							*}
							{/foreach}
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>
	<script type="text/javascript">
		$(window).load(function(){

			 $('#sns_partners .our_partners').owlCarousel({
				    loop:true,
				    responsiveClass:true,
				    nav: false,
				    dots: true,
		        	items: 4,
					responsive : {
					    0 : { items: 2 },
					    480 : { items: 2 },
					    768 : { items: 4 },
					    992 : { items: 5 },
					    1200 : { items: 6 }
					}
		        });
		

			$('#sns_partners .navslider .prev').on('click', function(e){
				e.preventDefault();
				$('#sns_partners .our_partners').trigger('prev.owl.carousel');
			});
			$('#sns_partners .navslider .next').on('click', function(e){
				e.preventDefault();
				$('#sns_partners .our_partners').trigger('next.owl.carousel');
			});

			$('#sns_partners .loading').fadeOut();

			$('#sns_partners .our_partners').removeClass('preload');

		});
	</script>
{/if}
