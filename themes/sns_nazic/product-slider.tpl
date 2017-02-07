{if isset($products) && $products}
	<div class="block_content">
		{assign var='nbItemsPerLine' value=4}
		{assign var='nbLi' value=$products|@count}

		<div {if isset($id) && $id} id="{$id}"{/if}  class="sns-slider-container">
			<div class="sns-producttabs-slider sns-productlist-grid">
				{foreach from=$products item=product name=homeFeaturedProducts}
				<div class="block-product ajax_block_product {if $smarty.foreach.homeFeaturedProducts.first}first_item{elseif $smarty.foreach.homeFeaturedProducts.last}last_item{else}item{/if} {if $smarty.foreach.homeFeaturedProducts.iteration%$nbItemsPerLine == 0}last_item_of_line{elseif $smarty.foreach.homeFeaturedProducts.iteration%$nbItemsPerLine == 1} {/if} {if $smarty.foreach.homeFeaturedProducts.iteration > ($smarty.foreach.homeFeaturedProducts.total - ($smarty.foreach.homeFeaturedProducts.total % $nbItemsPerLine))}last_line{/if}">
					<!-- product block -->
					<div class="block-product-inner">
						<div class="prd" itemtype="http://schema.org/Product" itemscope="">
							<div class="badges">
								{if isset($product.new) && $product.new == 1}
									<span class="ico-product ico-new">{l s='New'}</span>
								{/if}
								{if $product.specific_prices.reduction_type == 'percentage'}
									<span class="ico-product ico-sale">-{$product.specific_prices.reduction * 100}%</span>
								{/if}
							</div>
							<div class="item-img">
								<a class="product_img_link"	href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
									<img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image" />
								</a>
								<div class="actions">
					 				<div class="functional-buttons">
										{hook h='displayProductListFunctionalButtons' product=$product}
										{if isset($comparator_max_item) && $comparator_max_item}
											<div class="compare">
												<a class="add_to_compare" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product}">{l s='Add to Compare'}</a>
											</div>
										{/if}
									</div>
									{if isset($quick_view) && $quick_view}
										<a class="quick-view sns-btn-quickview" href="{$product.link|escape:'html':'UTF-8'}" rel="{$product.link|escape:'html':'UTF-8'}">
											<span>{l s='Quick view'}</span>
										</a>
									{/if}
								</div>
							</div>
							<div class="item-info">
								<div class="info-inner">
									<div class="item-title">
										{if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
										<a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url" >
											{$product.name|truncate:45:'...'|escape:'html':'UTF-8'}
										</a>
									</div>
									<div class="item-content clearfix">
										{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
										<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="item-price">
											{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
												<span itemprop="price" class="price product-price">
													{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
												</span>
												<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
												{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
													{hook h="displayProductPriceBlock" product=$product type="old_price"}
													<span class="old-price product-price">
														{displayWtPrice p=$product.price_without_reduction}
													</span>
													{hook h="displayProductPriceBlock" id_product=$product.id_product type="old_price"}
												{/if}
												{hook h="displayProductPriceBlock" product=$product type="price"}
												{hook h="displayProductPriceBlock" product=$product type="unit_price"}
											{/if}
										</div>
										{/if}
									</div>
									{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
										{if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
											{if isset($static_token)}
												<a class="btn-addtocart ajax_add_to_cart_button" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}">
													<i class="fa fa-shopping-cart"></i>
												</a>
											{else}
												<a class="btn-addtocart ajax_add_to_cart_button" href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$product.id_product|intval}', false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}">
													<i class="fa fa-shopping-cart"></i>
												</a>
											{/if}
										{else}
											<span class="btn-addtocart ajax_add_to_cart_button disabled" title="{l s='Add to cart'}">
												<i class="fa fa-shopping-cart"></i>
											</span>
										{/if}
									{/if}
								</div>
							</div>
						</div>
					</div>
					<!-- end product block -->
				</div>
				{/foreach}
			</div>
		</div>
	</div>
{/if}