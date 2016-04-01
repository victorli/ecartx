{if isset($verticalProducts) && $verticalProducts}
    <div class="mega-products clearfix">
        {foreach from=$verticalProducts item=product name=products}
            <div class="mega-product {$productWidth}" itemtype="http://schema.org/Product" itemscope="">
                <div class="product-avatar">
                    <a itemprop="url" title="{$product.name}" href="{$product.link}" class="product_img_link">
                        <img itemprop="image" title="{$product.name}" alt="{$product.name}" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" />
                    </a>
                </div>
                <div class="product-name"><a itemprop="url" href="{$product.link}" title="{$product.name}">{$product.name}</a></div>
                {if !$PS_CATALOG_MODE}
                <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="product-price">
					{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
						<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
						  <span itemprop="price" class="new-price">
								{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
						  </span>
						{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
							{hook h="displayProductPriceBlock" product=$product type="old_price"}
							<span class="old-price">
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
        {/foreach}    
    </div>
{/if}