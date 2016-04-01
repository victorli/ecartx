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
{assign var="current_option" value=Configuration::get('OVIC_CURRENT_OPTION')}
<!-- MODULE Block best sellers -->
<div id="best-sellers_block_right" class="block products_block {if isset($current_option) && $current_option == 2}products_block_option2{/if}{if isset($current_option) && $current_option == 5}products_block_option5{/if}">
	{if isset($current_option) && $current_option == 2}
        <h4 class="title_block_option2">
            <a href="{$link->getPageLink('best-sales')|escape:'html'}" title="{l s='View a recently bought products' mod='blockbestsellers'}">{l s='RECENTLY BOUGHT' mod='blockbestsellers'}</a>    
        </h4>
    {elseif isset($current_option) && $current_option == 5}
        <h4 class="title_block_option5">
            <a href="{$link->getPageLink('best-sales')|escape:'html'}" title="{l s='View a recently bought products' mod='blockbestsellers'}">{l s='RECENTLY BOUGHT' mod='blockbestsellers'}</a>    
        </h4>
    {else}
    <h4 class="title_block">
    	<a href="{$link->getPageLink('best-sales')|escape:'html'}" title="{l s='View a top sellers products' mod='blockbestsellers'}">{l s='Top sellers' mod='blockbestsellers'}</a>
    </h4>
    {/if}
	<div class="block_content">
	{if $best_sellers && $best_sellers|@count > 0}
        {include file="$tpl_dir./product-list-home.tpl" products=$best_sellers id="best_sellers_block"}        
        <script type="text/javascript">
            $(document).ready(function(){
                $("#best_sellers_block").owlCarousel({
                    loop:true,
                    margin:10,
                    nav:true,
                    responsive:{
                        0:{
                            items:1
                        }
                    }
                })
            });
        </script>
		<div class="lnk">
        	<a href="{$link->getPageLink('best-sales')|escape:'html'}" title="{l s='All best sellers' mod='blockbestsellers'}"  class="btn btn-default button button-small"><span>{l s='All best sellers' mod='blockbestsellers'}<i class="icon-chevron-right right"></i></span></a>
        </div>
	{else}
		<p>{l s='No best sellers at this time' mod='blockbestsellers'}</p>
	{/if}
	</div>
</div>
<!-- /MODULE Block best sellers -->