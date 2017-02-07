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
{if isset($categoryProducts) && count($categoryProducts) > 0 && $categoryProducts !== false}
<div id="sns_productspecials" class="block sns-slider sns-specials sns-snsproductspecials block-related">
	<div class="slider-inner">
		<div class="block-title">
			<strong>{l s="Related Products" mod='snsnazictheme'}</strong>
			<div class="navslider">
				<a href="#" class="prev"></a>
				<a href="#" class="next"></a>
			</div>
		</div>
		<div class="container-block">

			<div class="loading"></div>

			<div class="products-grid preload">
			{foreach from=$categoryProducts item=product name=homeFeaturedProducts}
			<div class="ajax_block_product item {if $smarty.foreach.homeFeaturedProducts.first}first_item{elseif $smarty.foreach.homeFeaturedProducts.last}last_item{/if}">
				{include file="$tpl_dir./product-blockgrid.tpl"}
			</div>
			{/foreach}
		</div>
		</div>
		<script type="text/javascript">
	        jQuery(document).ready(function($) {
				$('#sns_productspecials div.products-grid').owlCarousel({
				    loop:true,
				    responsiveClass:true,
				    nav: false,
				    dots: true,
		        	items: 4,
					responsive : {
					    0 : { items: 1 },
					    480 : { items: 2 },
					    768 : { items: 3 },
					    992 : { items: 3 }
					},
					onInitialized: callback

				});

				function callback(event) {
		   			if(this._items.length > this.options.items){
					        $('#sns_productspecials .navslider').show();
					    }else{
					        $('#sns_productspecials .navslider').hide();
					    }
				}

				$('#sns_productspecials .navslider .prev').on('click', function(e){
					e.preventDefault();
				
					$('#sns_productspecials div.products-grid').trigger('prev.owl.carousel');
				});
				$('#sns_productspecials .navslider .next').on('click', function(e){
					e.preventDefault();
					$('#sns_productspecials div.products-grid').trigger('next.owl.carousel');
				});

				$('#sns_productspecials .loading').fadeOut();
				$('#sns_productspecials div.products-grid').removeClass('preload');

	        });
	    </script>
	</div>
</div>
{/if}
