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
<div class="item-inner" itemtype="http://schema.org/Product" itemscope="">
	<div class="prd">

		<div class="ico-product">
			{if $product.specific_prices.reduction_type == 'percentage'}
				<span class="ico-sale">-{$product.specific_prices.reduction * 100}%</span>
			{elseif $product.specific_prices}
				<span class="ico-sale">{l s='Sale'}</span>
			{/if}
			{if isset($product.new) && $product.new == 1}
				<span class="ico-new">{l s='New'}</span>
			{/if}
		</div>
		
		<div class="item-img clearfix">
			
			<a class="product-image" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
				<span class="image-main">
					<img 
						class="replace-2x" 
						src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'deals_default')|escape:'html':'UTF-8'}" 
						alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" 
						title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}"
						itemprop="image" />
				</span>				
			</a>
			

			{hook h="displaySecondImage" id_product=$product.id_product link_rewrite=$product.link_rewrite}


		
			<div class="actions">
				{hook h='displayProductListFunctionalButtons' product=$product}
				
				{if isset($quick_view) && $quick_view}
					<div class="wrap-quickview">
						<div class="quickview-wrap">
							<a class="quick-view sns-btn-quickview" href="{$product.link|escape:'html':'UTF-8'}" rel="{$product.link|escape:'html':'UTF-8'}" data-toggle="tooltip" data-original-title="{l s='Quick view'}">
								<span>{l s='Quick view'}</span>
							</a>
						</div>
					</div>
				{/if}

				{if isset($comparator_max_item) && $comparator_max_item}
					<div class="compare">
						<a class="add_to_compare" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product}" data-toggle="tooltip" data-original-title="{l s='Add to Compare'}">{l s='Add to Compare'}</a>
					</div>
				{/if}

			</div>

			



		{*	<!-- {if $product.specific_prices.to}
			<div data-endtime="{$product.specific_prices.to|date_format:'%Y, %m, %d, %H, %M, %S'}" class="countdown"></div>
			{/if} -->
		*}
		</div>
		<div class="item-info">
			<div class="info-inner">
				<div class="item-title">
					{if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
					<a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url" >
						{$product.name|truncate:45:'...'|escape:'html':'UTF-8'}
					</a>
				</div>
				{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
				<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="item-price">
					{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
						
						{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
							{hook h="displayProductPriceBlock" product=$product type="old_price"}
							<span class="old-price product-price">
								{displayWtPrice p=$product.price_without_reduction}
							</span>
							{hook h="displayProductPriceBlock" id_product=$product.id_product type="old_price"}
						{/if}


						<span itemprop="price" class="price product-price">
							{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
						</span>
						<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
						
						{hook h="displayProductPriceBlock" product=$product type="price"}
						{hook h="displayProductPriceBlock" product=$product type="unit_price"}
					{/if}
				</div>
				{/if}
			</div>
			{* 
			<div class="sns_rating">
				This is list reviews
				{hook h='displayProductListReviews' product=$product}
			</div>
			 *}
			<div class="action-bot">
				
				<div class="wrap-addtocart">
					{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
						{if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
							{if isset($static_token)}
								<a class="btn-addtocart button ajax_add_to_cart_button" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}">
									{l s='Add to cart'}
								</a>
							{else}
								<a class="btn-addtocart button ajax_add_to_cart_button" href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$product.id_product|intval}', false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}">
									{l s='Add to cart'}
								</a>
							{/if}
						{else}
							<span class="btn-addtocart button ajax_add_to_cart_button disabled" title="{l s='Add to cart'}">
								{l s='Out of stock'}
							</span>
						{/if}
					{/if}
				</div>
			</div>
		</div>
	</div>
</div>
