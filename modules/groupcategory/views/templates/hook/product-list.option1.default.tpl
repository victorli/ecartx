{if isset($products) && $products|@count >0}
	<div id="product-list-{$module_id}-{$feature}-{$item_id}" class="lazy-carousel check-active {if isset($active) && $active == '1'}active{/if} tab-content-{$module_id}-{$feature}-{$item_id}">
	{foreach from=$products item=product name=products}		
	    <div itemtype="http://schema.org/Product" itemscope="" class="group-category-product">
	        {if isset($product.specific_prices.reduction) && $product.specific_prices.reduction}
	            {if $product.specific_prices.reduction_type == 'percentage'}
	                <div class="saleoff-bg text-center"><div>{$product.specific_prices.reduction * 100}%</div><span>{l s='OFF' mod='groupcategory'}</span></div>
	            {else}
	                <div class="saleoff-bg text-center"><div>-{$product.specific_prices.reduction|intval}</div><span>{$currency->sign}</span></div>
	            
	            {/if}
	        {/if}
	        <div class="group-category-product-avatar avatar">
	            <a href="{$product.link}" title="{$product.name}" itemprop="url">
	                <img class="owl-lazy img-responsive" src="http://placehold.it/{if isset($homeSize)}{$homeSize.width}x{$homeSize.height}{/if}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image" title="{$product.name}" alt="{$product.name}" data-src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" />
	            </a>
	            <div class="main-quick-view">
	                <div class="div-quick-view">
	                    <a onclick="javascript: WishlistCart('wishlist_block_list', 'add', '{$product.id_product}', false, 1); return false;" data-rel="{$product.id_product}" href="javascript:void(0)" class="addToWishlist" title="{l s='Add to Wishlist' mod='groupcategory'}"><i class="icon-heart-empty"></i></a>
	                    {if isset($product.isCompare) && $product.isCompare == '1'}
	                    <a title="{l s='Add to Compare' mod='groupcategory'}" data-id-product="{$product.id_product}" href="{$product.link}" class="add_to_compare compare-checked"><i class="icon-toggle-on"></i></a>
	                    {else}
	                    <a title="{l s='Add to Compare' mod='groupcategory'}" data-id-product="{$product.id_product}" href="{$product.link}" class="add_to_compare"><i class="icon-toggle-on"></i></a>
	                    {/if}
	                    <a rel="{$product.link}" title="{l s='Quick view'}" href="{$product.link}" class="quick-view item-quick-view"><i class="icon-search"></i></a>
	                </div>
	                {if !$PS_CATALOG_MODE}
	                <div class="add-to-cart">
	                    {if $product.quantity >0}
	                        <a data-id-product="{$product.id_product}" title="{l s='Add to cart' mod='groupcategory'}" rel="nofollow" href="javascript:void(0)" class="ajax_add_to_cart_button"><i class="icon-shopping-cart"></i>&nbsp;<span>{l s='Add to cart' mod='groupcategory'}</span></a>
	                    {else}
	                        <a title="{l s='Add to cart' mod='groupcategory'}" rel="nofollow" href="javascript:void(0)" class="quantity-empty"><i class="icon-shopping-cart"></i>&nbsp;<span>{l s='Add to cart' mod='groupcategory'}</span></a>
	                    {/if}
	                </div>
	                {/if}
	            </div>
	        </div>
	        <div class="mod-product-name">
	            <a href="{$product.link}" title="{$product.name}">{$product.name}</a>
	        </div>
	        {if !$PS_CATALOG_MODE}
	        <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="product-price">
				{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
					<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
					  <span itemprop="price" class="product-price-new">
							{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
					  </span>
					{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
						{hook h="displayProductPriceBlock" product=$product type="old_price"}
						<span class="product-price-old">
							{displayWtPrice p=$product.price_without_reduction}
						</span>
						{hook h="displayProductPriceBlock" id_product=$product.id_product type="old_price"}										
					{/if}
	
					{hook h="displayProductPriceBlock" product=$product type="price"}
					{hook h="displayProductPriceBlock" product=$product type="unit_price"}
				{/if}
			</div>
	        {/if}
	        {hook h='displayProductListReviews' product=$product}        
    	</div>
	{/foreach}
	</div>
{else}
	<div id="product-list-{$module_id}-{$feature}-{$item_id}" class="lazy-carousel check-active {if isset($active) && $active == '1'}active{/if} tab-content-{$module_id}-{$feature}-{$item_id}">
		<div style="padding: 60px 0">{l s='Sorry! There are no products' mod='groupcategory'}</div>		
	</div>
{/if}
