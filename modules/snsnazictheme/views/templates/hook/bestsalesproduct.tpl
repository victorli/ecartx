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

{if isset($products) AND $products}
<div id="sns_productspecial_{$eq}" class="block sns-snsproductspecials sns-productlist ">
	<div class="slider-inner">
		<div class="block-title">
			<strong>{l s="Best Sales" mod="snsnazictheme"}</strong>
			<!-- <div class="navslider">
				<a href="#" class="prev"></a>
				<a href="#" class="next"></a>
			</div> -->
		</div>
		<div class="block-content">	
			<div class=" sns-bestsales">
			
			{counter start=0 skip=1 print=false name=i assign="i"}
			{assign var="total" value=$products|@count}
			{foreach from=$products item=product name=homeFeaturedProducts}
			{counter name=i}
			<div class="ajax_block_product item {if $smarty.foreach.homeFeaturedProducts.first}first_item{elseif $smarty.foreach.homeFeaturedProducts.last}last_item{/if}">			
				{include file="$tpl_dir./product-blockgridlistv5.tpl"}
			</div>
			{/foreach}
		</div>
		</div>
		<script type="text/javascript">
	        jQuery(document).ready(function($) {
				$('#sns_productspecial_{$eq} div.sns-bestsales ').owlCarousel({
					loop:true,
				    responsiveClass:true,
				    nav: false,
				    dots: true,
		        	items:1,
					
					onInitialized: callback
				});


				function callback(event) {
		   			if(this._items.length > this.options.items){
				        $('#sns_productspecial_{$eq} .navslider').show();
				    }else{
				        $('#sns_productspecial_{$eq} .navslider').hide();
				    }
				}


				$('#sns_productspecial_{$eq} .navslider .prev').on('click', function(e){
					e.preventDefault();
					$('#sns_productspecial_{$eq}  div.sns-bestsales').trigger('owl.prev');
				});
				$('#sns_productspecial_{$eq} .navslider .next').on('click', function(e){
					e.preventDefault();
					$('#sns_productspecial_{$eq} div.sns-bestsales').trigger('owl.next');
				});
	        });
	    </script>
	</div>
</div>
{/if}



