{if $groups && $groups|@count > 0}
<div class="flexible-custom-groups">
    <h4 class="module-title">{$moduleName}</h4>
    <ul class="clearfix flexible-custom-list" id="flexible-custom-list-{$moduleId}">            
    {foreach from=$groups item=group name=ojb}
        {if $smarty.foreach.ojb.first}
        <li class="item active">            
            <a href="javascript:void(0)" data-module="{$moduleId}" data-group="{$group.id}" class="active">
                {if $group.icon}<img class="cat-img" src="{$livePath}icons/{$group.icon}" alt="{$group.title}" />{/if}
                {if $group.iconActive}<img class="cat-img-active" src="{$livePath}icons/{$group.iconActive}" alt="{$group.title}" />{/if}
                <span>{$group.title}</span>
            </a>
        </li>
        {else}
        <li class="item">            
            <a href="javascript:void(0)" data-module="{$moduleId}" data-group="{$group.id}">
                {if $group.icon}<img class="cat-img" src="{$livePath}icons/{$group.icon}" alt="{$group.title}" />{/if}
                {if $group.iconActive}<img class="cat-img-active" src="{$livePath}icons/{$group.iconActive}" alt="{$group.title}" />{/if}
                <span>{$group.title}</span>
            </a>
        </li>
        {/if}        
    {/foreach}
    </ul>
</div>
<div class="clearfix"></div>
<div id="flexible-custom-products-{$moduleId}" class="flexible-custom-products tab-content">
    {foreach from=$groups item=group name=ojb}
     
    {if $smarty.foreach.ojb.first}
    
    
    
    <div class="flexible-group-products active" id="flexible-group-products-{$group.id}">
                    
        {foreach from=$group.products item=product name=products}        
            <div class="item">
                <div class="product-container" itemscope itemtype="http://schema.org/Product">
                    <div class="left-block">
                        <div class="product-image-container">
                            <a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
                                <img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image" />                                
                            </a>                            
                            <div class="functional-buttons clearfix">
                            {hook h='displayProductListFunctionalButtons' product=$product}                    
                            {if isset($comparator_max_item) && $comparator_max_item}
                                <div class="compare">
                                    <a class="add_to_compare" title="{l s="Add to compare"}" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product}"><i class="fa-compress"></i></a>
                                </div>
                            {/if}
                            {if isset($quick_view) && $quick_view}
                                <a class="quick-view" title="{l s='Quick view'}" href="{$product.link|escape:'html':'UTF-8'}" rel="{$product.link|escape:'html':'UTF-8'}">
                                    <i class="fa fa-search"></i>
                                </a>
                            {/if}
                            {if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
                                {if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
                                    {if isset($static_token)}
                                        <a class="button ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}">
                                            <span>{l s='Add to cart'}</span>
                                        </a>
                                    {else}
                                        <a class="button ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$product.id_product|intval}', false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}">
                                            <span>{l s='Add to cart'}</span>
                                        </a>
                                    {/if}
                                {else}
                                    <span class="button ajax_add_to_cart_button btn btn-default disabled">
                                        <span>{l s='Add to cart'}</span>
                                    </span>
                                {/if}
                            {/if}     
                            </div>
                        </div>
                        {hook h="displayProductDeliveryTime" product=$product}
                        {hook h="displayProductPriceBlock" product=$product type="weight"}
                        
                                          
                    </div>
                    <div class="right-block">
                        <h5 itemprop="name">
                            {if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
                            <a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url" >
                                {$product.name|truncate:20:''|escape:'html':'UTF-8'}
                            </a>
                        </h5>
                        
                        
                        {if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
                        <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="content_price">
                            {if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
                                <meta itemprop="priceCurrency" content="{$currency->iso_code}" />
                                  <span itemprop="price" class="price product-price">
                                        {if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
                                  </span>
                                {if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
                                    {hook h="displayProductPriceBlock" product=$product type="old_price"}
                                    <span class="old-price product-price">
                                        {displayWtPrice p=$product.price_without_reduction}
                                    </span>
                                    {hook h="displayProductPriceBlock" id_product=$product.id_product type="old_price"}
                                    {if $product.specific_prices.reduction_type == 'percentage'}
                                        <span class="price-percent-reduction">{$product.specific_prices.reduction * 100}%<span>{l s ='OFF'}</span></span>
                                    {/if}
                                {/if}
    
                                {hook h="displayProductPriceBlock" product=$product type="price"}
                                {hook h="displayProductPriceBlock" product=$product type="unit_price"}
                            {/if}
                        </div>
                        {/if}
                        {hook h='displayProductListReviews' product=$product}
                        
                       
                    </div>
                </div>
                
                
                
                
                
                
                
                
                
                
                
                
    
            </div>                
        {/foreach}            
        
    </div>
    
    
    
    {else}
    
    
    
    <div class="flexible-group-products" id="flexible-group-products-{$group.id}">
                    
        {foreach from=$group.products item=product name=products}        
            <div class="item">
                
                
                
                
                
                
                
                
                
                <div class="product-container" itemscope itemtype="http://schema.org/Product">
                    <div class="left-block">
                        <div class="product-image-container">
                            <a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
                                <img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image" />                                
                            </a>                            
                            <div class="functional-buttons clearfix">
                            {hook h='displayProductListFunctionalButtons' product=$product}                    
                            {if isset($comparator_max_item) && $comparator_max_item}
                                <div class="compare">
                                    <a class="add_to_compare" title="{l s="Add to compare"}" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product}"><i class="fa-compress"></i></a>
                                </div>
                            {/if}
                            {if isset($quick_view) && $quick_view}
                                <a class="quick-view" title="{l s='Quick view'}" href="{$product.link|escape:'html':'UTF-8'}" rel="{$product.link|escape:'html':'UTF-8'}">
                                    <i class="fa fa-search"></i>
                                </a>
                            {/if}
                            {if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
                                {if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
                                    {if isset($static_token)}
                                        <a class="button ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}">
                                            <span>{l s='Add to cart'}</span>
                                        </a>
                                    {else}
                                        <a class="button ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$product.id_product|intval}', false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}">
                                            <span>{l s='Add to cart'}</span>
                                        </a>
                                    {/if}
                                {else}
                                    <span class="button ajax_add_to_cart_button btn btn-default disabled">
                                        <span>{l s='Add to cart'}</span>
                                    </span>
                                {/if}
                            {/if}     
                            </div>
                        </div>
                        {hook h="displayProductDeliveryTime" product=$product}
                        {hook h="displayProductPriceBlock" product=$product type="weight"}
                        
                                          
                    </div>
                    <div class="right-block">
                        <h5 itemprop="name">
                            {if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
                            <a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url" >
                                {$product.name|truncate:20:''|escape:'html':'UTF-8'}
                            </a>
                        </h5>
                        
                        
                        {if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
                        <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="content_price">
                            {if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
                                <meta itemprop="priceCurrency" content="{$currency->iso_code}" />
                                  <span itemprop="price" class="price product-price">
                                        {if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
                                  </span>
                                {if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
                                    {hook h="displayProductPriceBlock" product=$product type="old_price"}
                                    <span class="old-price product-price">
                                        {displayWtPrice p=$product.price_without_reduction}
                                    </span>
                                    {hook h="displayProductPriceBlock" id_product=$product.id_product type="old_price"}
                                    {if $product.specific_prices.reduction_type == 'percentage'}
                                        <span class="price-percent-reduction">{$product.specific_prices.reduction * 100}%<span>{l s ='OFF'}</span></span>
                                    {/if}
                                {/if}
    
                                {hook h="displayProductPriceBlock" product=$product type="price"}
                                {hook h="displayProductPriceBlock" product=$product type="unit_price"}
                            {/if}
                        </div>
                        {/if}
                        {hook h='displayProductListReviews' product=$product}
                        
                       
                    </div>
                </div>
                
                
                
                
                
                
                
                
                
                
                
                
    
            </div>                
        {/foreach}            
        
    </div>
    
    
    
    {/if}
    
    
    
    
    
   
     
    {/foreach}
</div>
{/if}