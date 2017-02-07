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

{assign var="eq" value=0|rand:100000|cat:$smarty.now}
<div id="sns_prd_slider_{$eq}" class="block sns-slider sns-snsproductslider sns-slider-content mrb-0">
	<div class="slider-inner">





		{if isset($SNSPRDS_TITLE) && $SNSPRDS_TITLE}
		<div class="block-title">

			<strong>{$SNSPRDS_TITLE|escape:'htmlall':'UTF-8'}</strong>
			<div class="navslider">
				<a href="#" class="prev"></a>
				<a href="#" class="next"></a>
			</div>
		</div>
		{/if}
	
	{*
		<!-- {if isset($SNSPRDS_DESC) && $SNSPRDS_DESC}
			<div class="pretext"><div>{$SNSPRDS_DESC|escape:'htmlall':'UTF-8'}</div></div>
		{/if} -->
	*}

		{if isset($products) AND $products}
			{include file="./items.tpl" products=$products}
			<script type="text/javascript">

				jQuery(document).ready(function($) {
			        $('#sns_prd_slider_{$eq} div.products-grid').owlCarousel({
					    loop:true,
					    responsiveClass:true,
					    nav: false,
					    dots: true,
			        	items: 4,
			        	slideSpeed : 800,
            			addClassActive: true,
						responsive : {
						    0 : { items: 1 },
						    480 : { items: 2 },
						    768 : { items: 3 },
						    992 : { items: 3 },
						    1200 : { items: 3 }
						},
						onInitialized: callback
			        });


			         function callback(event) {
			   			if(this._items.length > this.options.items){
					        $('#sns_prd_slider_{$eq} .navslider').show();
					    }else{
					        $('#sns_prd_slider_{$eq} .navslider').hide();
					    }
					}

					
			        $('#sns_prd_slider_{$eq} .navslider .prev').on('click', function(e){
						e.preventDefault();
					
						$('#sns_prd_slider_{$eq} div.products-grid').trigger('prev.owl.carousel');
					});
					$('#sns_prd_slider_{$eq} .navslider .next').on('click', function(e){
						e.preventDefault();
						$('#sns_prd_slider_{$eq} div.products-grid').trigger('next.owl.carousel');
					});

					$('#sns_prd_slider_{$eq} .loading').fadeOut();
					$('#sns_prd_slider_{$eq} div.products-grid').removeClass('preload');

			    });


			</script>
		{else}
			<p class="alert alert-info">{l s="There are no products matching to show."}</p>
		{/if}
	</div>
</div>



