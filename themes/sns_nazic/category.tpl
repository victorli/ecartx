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
		{if $products}

		{if isset($category) && $page_name == 'category'}
			{if $category->id AND $category->active}
				<div class="category-image block"{if $category->id_image} style="background:url({$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category_default')|escape:'html':'UTF-8'}) center center no-repeat; background-size:cover; min-height:216px;"{/if}></div>
			{/if}
		{/if}


			<div class="content_sortPagiBar clearfix">
            	<div class="sortPagiBar clearfix gfont">
            		{include file="./product-sort.tpl"}
                	{include file="./nbr-product-page.tpl"}
                	{include file="./product-compare.tpl"}
				</div>
			</div>
			{include file="./product-list.tpl" products=$products}
			<div class="content_sortPagiBar">
				<div class="bottom-pagination-content sortPagiBar  bottom clearfix">
            		{include file="./product-sort.tpl" display='viewtype'}
                    {include file="./pagination.tpl" paginationId='bottom'}
				</div>
			</div>
		{else}
			<p class="alert alert-warning">{l s='There are no products in this category.'}</p>
		{/if}
	{elseif $category->id}
		<p class="alert alert-warning">{l s='This category is currently unavailable.'}</p>
	{/if}
{/if}
