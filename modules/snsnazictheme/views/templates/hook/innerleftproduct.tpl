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
{if isset($products) AND $products}
	<div id="sns_viewedproduct" class="block sns-slider viewed ">
		<div class="block-title">
			<strong>{l s="Viewed Product" mod='snsnazictheme'}</strong>
			<div class="navslider">
				<a href="#" class="prev"></i></a>
				<a href="#" class="next"></a>
			</div>
		</div>
		<div class="container-block">
			<div class="products-grid">
			{foreach from=$products item=product name=homeFeaturedProducts}
			<div class="ajax_block_product item {if $smarty.foreach.homeFeaturedProducts.first}first_item{elseif $smarty.foreach.homeFeaturedProducts.last}last_item{/if}">
				{include file="$tpl_dir./product-blockgrid.tpl"}
			</div>
			{/foreach}
		</div>
		</div>
	</div>
	<script type="text/javascript">
	    jQuery(document).ready(function($) {
	        $('#sns_viewedproduct div.products-grid').owlCarousel({
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
			        $('#sns_viewedproduct .navslider').show();
			    }else{
			        $('#sns_viewedproduct .navslider').hide();
			    }
			}

			$('#sns_viewedproduct .navslider .prev').on('click', function(e){
				e.preventDefault();
			
				$('#sns_viewedproduct div.products-grid').trigger('prev.owl.carousel');
			});
			$('#sns_viewedproduct .navslider .next').on('click', function(e){
				e.preventDefault();
				$('#sns_viewedproduct div.products-grid').trigger('next.owl.carousel');
			});


	    });
	</script>
{else}
	<p class="alert alert-info">{l s="There are no products matching to show."}</p>
{/if}



