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

<!-- MODULE Block specials -->
{assign var="current_option" value=Configuration::get('OVIC_CURRENT_OPTION')}
<div id="special_block_right" class="block{if isset($current_option) && $current_option == 2} products_block_option2{/if}{if isset($current_option) && $current_option == 5} products_block_option5{/if}">
    {if isset($current_option) && $current_option == 2}
        <h4 class="title_block_option2">
            <a href="{$link->getPageLink('prices-drop')|escape:'html':'UTF-8'}" title="{l s='Special Product' mod='blockspecials'}">
                {l s='Special Product' mod='blockspecials'}
            </a>
        </h4>
    {elseif ($current_option) && $current_option == 5}
        <h4 class="title_block_option5">
            <a href="{$link->getPageLink('prices-drop')|escape:'html':'UTF-8'}" title="{l s='Special Product' mod='blockspecials'}">
                {l s='Special Product' mod='blockspecials'}
            </a>
        </h4>
    {else}
	<p class="title_block">
        <a href="{$link->getPageLink('prices-drop')|escape:'html':'UTF-8'}" title="{l s='Specials' mod='blockspecials'}">
            {l s='Specials' mod='blockspecials'}
        </a>
    </p>
    {/if}
	<div class="products-block">
    {if $special}
        {if isset($current_option) && ($current_option == 2 || $current_option == 5)}
            <ul class="product_list grid">
        {else}
            <ul>
        {/if}
        	<li class="clearfix">
            	<div class="product-container" itemscope itemtype="http://schema.org/Product">
                    <div class="left-block">
    					<div class="product-image-container">
    						<a class="product_img_link"	href="{$special.link|escape:'html':'UTF-8'}" title="{$special.name|escape:'html':'UTF-8'}" itemprop="url">
                                <img class="replace-2x img-responsive" 
                                    src="{$link->getImageLink($special.link_rewrite, $special.id_image, 'home_default')|escape:'html':'UTF-8'}" 
                                    alt="{$special.legend|escape:'html':'UTF-8'}" 
                                    title="{$special.name|escape:'html':'UTF-8'}" />
                            </a>
                        </div>
                    </div>    
                    <div class="right-block">
                        <h5>
                            <a class="product-name" href="{$special.link|escape:'html':'UTF-8'}" title="{$special.name|escape:'html':'UTF-8'}">
                                {$special.name|escape:'html':'UTF-8'}
                            </a>
                        </h5>
                        <div class="content_price">
                        	{if !$PS_CATALOG_MODE}
                            	<span class="price product-price">
                                    {if !$priceDisplay}
                                        {displayWtPrice p=$special.price}{else}{displayWtPrice p=$special.price_tax_exc}
                                    {/if}
                                </span>
                                 {if $special.specific_prices}
                                    {assign var='specific_prices' value=$special.specific_prices}
                                    {if $specific_prices.reduction_type == 'percentage' && ($specific_prices.from == $specific_prices.to OR ($smarty.now|date_format:'%Y-%m-%d %H:%M:%S' <= $specific_prices.to && $smarty.now|date_format:'%Y-%m-%d %H:%M:%S' >= $specific_prices.from))}
                                        <span class="price-percent-reduction">-{$specific_prices.reduction*100|floatval}%<span>{l s='OFF' mod='blockspecials'}</span></span>
                                    {/if}
                                {/if}
                                 <span class="old-price product-price">
                                    {if !$priceDisplay}
                                        {displayWtPrice p=$special.price_without_reduction}{else}{displayWtPrice p=$priceWithoutReduction_tax_excl}
                                    {/if}
                                </span>
                            {/if}
                        </div>
                    </div>
                    </div>
            </li>
		</ul>
        {if isset($current_option) && ($current_option == 2 || $current_option == 5)}
        
        {else}
            <div>
    			<a 
                class="btn btn-default button button-small" 
                href="{$link->getPageLink('prices-drop')|escape:'html':'UTF-8'}" 
                title="{l s='All specials' mod='blockspecials'}">
                    <span>{l s='All specials' mod='blockspecials'}<i class="icon-chevron-right right"></i></span>
                </a>
    		</div>
        {/if}
		
        
    {else}
		<div>{l s='No specials at this time.' mod='blockspecials'}</div>
    {/if}
	</div>
</div>
<!-- /MODULE Block specials -->