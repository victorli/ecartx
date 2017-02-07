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
{if isset($products) && $products}
	{*define numbers of product per line in other page for desktop*}
	{if $page_name !='index' && $page_name !='product'}
		{assign var='nbItemsPerLine' value=3}
		{assign var='nbItemsPerLineTablet' value=2}
		{assign var='nbItemsPerLineMobile' value=3}
	{else}
		{assign var='nbItemsPerLine' value=4}
		{assign var='nbItemsPerLineTablet' value=3}
		{assign var='nbItemsPerLineMobile' value=2}
	{/if}
	{*define numbers of product per line in other page for tablet*}
	{assign var='nbLi' value=$products|@count}
	{math equation="nbLi/nbItemsPerLine" nbLi=$nbLi nbItemsPerLine=$nbItemsPerLine assign=nbLines}
	{math equation="nbLi/nbItemsPerLineTablet" nbLi=$nbLi nbItemsPerLineTablet=$nbItemsPerLineTablet assign=nbLinesTablet}
	<!-- Products list -->
	<ul{if isset($id) && $id} id="{$id}"{/if} class="product_list grid row{if isset($class) && $class} {$class}{/if}">
	{foreach from=$products item=product name=products}
		{math equation="(total%perLine)" total=$smarty.foreach.products.total perLine=$nbItemsPerLine assign=totModulo}
		{math equation="(total%perLineT)" total=$smarty.foreach.products.total perLineT=$nbItemsPerLineTablet assign=totModuloTablet}
		{math equation="(total%perLineT)" total=$smarty.foreach.products.total perLineT=$nbItemsPerLineMobile assign=totModuloMobile}
		{if $totModulo == 0}{assign var='totModulo' value=$nbItemsPerLine}{/if}
		{if $totModuloTablet == 0}{assign var='totModuloTablet' value=$nbItemsPerLineTablet}{/if}
		{if $totModuloMobile == 0}{assign var='totModuloMobile' value=$nbItemsPerLineMobile}{/if}
		<li class="ajax_block_product col-phone-12 col-xs-6 col-sm-4">
			<!-- product block -->
			<!-- List view -->
			<div class="box-list">
				<div class="row list-view">
				<div class="col-left col-xs-4 col-phone-12">
					
					<div class="badges">
						{if isset($product.new) && $product.new == 1}
							<span class="ico-product ico-new">{l s='New'}</span>
						{/if}
						{if $product.specific_prices.reduction_type == 'percentage'}
							<span class="ico-product ico-sale">-{$product.specific_prices.reduction * 100}%</span>
						{/if}
					</div>

					<div class="item-img">
						<a class="product-image" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
							<span class="image-main">
								<img 
									class="replace-2x " 
									src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'deals_default')|escape:'html':'UTF-8'}" 
									alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" 
									title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}"/>
							</span>
						</a>

					
						{hook h="displaySecondImage" id_product=$product.id_product link_rewrite=$product.link_rewrite}
							
					</div>


				</div>
				<div class="col-right col-xs-8 col-phone-12">
					<div class="item-title">
						{if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
						<a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url" >
							{$product.name|truncate:45:'...'|escape:'html':'UTF-8'}
						</a>
					</div>

					<div class="sns_rating">{hook h='displayProductListReviews' product=$product}</div>

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
					
					<div class="item-desc">
						{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}
					</div>

					<div class="actions">
						{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
							{if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
								{if isset($static_token)}
									<a class="btn-addtocart btnsns btn-cart ajax_add_to_cart_button" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}">
									
										<span class="gfont">{l s='Add to cart'}</span>
									</a>
								{else}
									<a class="btn-addtocart btnsns btn-cart ajax_add_to_cart_button" href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$product.id_product|intval}', false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}">
										
										<span class="gfont">{l s='Add to cart'}</span>
									</a>
								{/if}
							{else}
								<span class="btn-addtocart btnsns btn-cart ajax_add_to_cart_button disabled" title="{l s='Add to cart'}">
									<i class="fa fa-shopping-cart"></i>
									<span class="gfont">{l s='Out of Stock'}</span>
								</span>
							{/if}
						{/if}
		 				<div class="functional-buttons">
							{hook h='displayProductListFunctionalButtons' product=$product}
							{if isset($comparator_max_item) && $comparator_max_item}
								<div class="compare link-compare">
									<a class="add_to_compare" data-original-title="{l s='Add to Compare'}" data-toggle="tooltip"  href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product}">{l s='Add to Compare'}</a>
								</div>
							{/if}
						</div>
						{if isset($quick_view) && $quick_view}
							<a class="quick-view sns-btn-quickview" data-original-title="{l s='Quick view'}" data-toggle="tooltip" href="{$product.link|escape:'html':'UTF-8'}" rel="{$product.link|escape:'html':'UTF-8'}">
								<span>{l s='Quick view'}</span>
							</a>
						{/if}
					</div>
				</div>
			</div>
			</div>
			<!-- Grid view -->
			<div class="block-product-inner grid-view">
				{include file="$tpl_dir./product-blockgrid.tpl"}
			</div>
			<!-- end product block -->
		</li>
		{if $smarty.foreach.products.iteration%(12/4) == 0} 
			<li class="clearfix clear hidden-xs"></li>
		{/if}
		{if $smarty.foreach.products.iteration%(12/6) == 0} 
			<li class="clearfix clear visible-xs"></li>
		{/if}
	{/foreach}
	</ul>
{addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}
{addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
{addJsDef comparator_max_item=$comparator_max_item}
{addJsDef comparedProductsIds=$compared_products}
{/if}
