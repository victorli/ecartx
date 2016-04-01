{if $groupProducts && $groupProducts|@count > 0}    
    {foreach from=$groupProducts item=product name=ojb}
        <div itemtype="http://schema.org/Product" itemscope="" class="group-category-product" style="{$css}">
            {if $product.reduction != ""}
                <div class="saleoff-bg text-center"><div>{$product.reduction}</div><span>OFF</span></div>                
            {/if}
            <div class="group-category-product-avatar avatar">
                <a href="{$product.link}" title="{$product.name}" itemprop="url">
                    <img itemprop="image" title="{$product.name}" alt="{$product.name}" src="{$product.image}" />
                </a>
                <div class="main-quick-view">
                    <div class="div-quick-view">
                        <a onclick="javascript: WishlistCart('wishlist_block_list', 'add', '{$product.id_product}', false, 1); return false;" data-rel="{$product.id_product}" href="javascript:void(0)" class="addToWishlist" title="{l s='Add to Wishlist' mod='groupcategory'}"><i class="icon-heart-empty"></i></a>
                        {if $product.isCompare == '1'}
                        <a title="{l s='Add to Compare' mod='groupcategory'}" data-id-product="{$product.id_product}" href="{$product.link}" class="add_to_compare compare-checked"><i class="icon-toggle-on"></i></a>
                        {else}
                        <a title="{l s='Add to Compare' mod='groupcategory'}" data-id-product="{$product.id_product}" href="{$product.link}" class="add_to_compare"><i class="icon-toggle-on"></i></a>
                        {/if}
                        <a rel="{$product.link}" href="{$product.link}" class="quick-view item-quick-view"><i class="icon-search"></i></a>
                    </div>
                    <div class="add-to-cart">
                        {if $product.quantity >0}
                            <a data-id-product="{$product.id_product}" title="{l s='Add to cart' mod='groupcategory'}" rel="nofollow" href="javascript:void(0)" class="ajax_add_to_cart_button"><i class="icon-shopping-cart"></i>&nbsp;<span>{l s='Add to cart' mod='groupcategory'}</span></a>;
                        {else}
                            <a title="{l s='Add to cart' mod='groupcategory'}" rel="nofollow" href="javascript:void(0)" class="quantity-empty"><i class="icon-shopping-cart"></i>&nbsp;<span>{l s='Add to cart' mod='groupcategory'}</span></a>
                        {/if}
                    </div>
                </div>
            </div>
            <div class="mod-product-name">
                <a href="{$product.link}" title="{$product.name}">{$product.name}</a>
            </div>
            {if !$PS_CATALOG_MODE}
            <div class="product-price">
                <span class="product-price-new">{$currencySign}{$product.price_new}</span>
                {if $product.price_old}
                <span class="product-price-old">{$currencySign}{$product.price_old}</span>
                {/if}                
            </div>
            {/if}
            <div class="rates clearfix">
                <div class="star_content pull-left">
                    <div class="clearfix">{$product.rates}</div>
                </div>
                <div class="pull-left total">({$product.totalRates})</div>
            </div>
        </div>        
    {/foreach}
{else}
<div class="alert alert-info">{l s='Sorry! There are no products' mod='groupcategory'}</div>
{/if}
