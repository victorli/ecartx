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
{include file="$tpl_dir./errors.tpl"}
{if isset($category)}
	{if $category->id AND $category->active}
        {$HOOK_OVIC_CATEGORYSLIDER}
        {if $category->id_image}
            {*}<img class="category-img img-responsive" alt="" src="{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category_default')|escape:'html':'UTF-8'}" />{*}
        {/if}
        {if isset($subcategories)}
        {*if (isset($display_subcategories) && $display_subcategories eq 1) || !isset($display_subcategories) *}
		<!-- Subcategories -->
		<div id="subcategories">
			<ul class="clearfix">
			{foreach from=$subcategories item=subcategory}
				<li>
					<a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}" title="{$subcategory.name|escape:'html':'UTF-8'}" >{$subcategory.name}</a>
				</li>
			{/foreach}
			</ul>
            {if $category->description}
                <div id="category_description_full" class="unvisible rte">{$category->description}</div>            
            {/if}
		</div>
        {*/if*}
        {else}
            {if $category->description}
            <div id="subcategories">                
                <div id="category_description_full" class="unvisible rte">{$category->description}</div>                            
    		</div>
            {/if}
		{/if}
        
        
        <div class="view-product-list">
            <h1 class="page-heading{if (isset($subcategories) && !$products) || (isset($subcategories) && $products) || !isset($subcategories) && $products} product-listing{/if}"><span class="cat-name">{$category->name|escape:'html':'UTF-8'}{if isset($categoryNameComplement)}&nbsp;{$categoryNameComplement|escape:'html':'UTF-8'}{/if}</span></h1>
            {include file="./product-sort-view.tpl"}
        </div>
		{if $products}
			{include file="./product-list.tpl" products=$products}
			<div class="content_sortPagiBar">
                <div class="sortPagiBar clearfix">
                    {include file="./product-compare.tpl"}
                    {include file="./pagination.tpl" paginationId='bottom'}
                    {include file="./nbr-product-page.tpl"}
                    {include file="./product-sort.tpl" sortID='bottom'}
                    
                </div>
			</div>
		{else}
            <div class="warning">{l s='Sorry! There are no products in this category.'}</div>
        {/if}
	{elseif $category->id}
		<p class="alert alert-warning">{l s='This category is currently unavailable.'}</p>
	{/if}
{/if}
