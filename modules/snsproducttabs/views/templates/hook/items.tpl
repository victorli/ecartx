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
	<div class="{if isset($SNSPRT_EFFECT)}{$SNSPRT_EFFECT}{/if} product_list products-grid grid {if isset($class) && $class} {$class}{/if}">
	{if isset($ajax_start) && $ajax_start}
		{assign var='nbstart' value=$ajax_start}
	{else}
		{assign var='nbstart' value=0}
	{/if}
	{counter start=$nbstart skip=1 print=false name=i assign="i"}
	{foreach from=$products item=product name=products}
		<div class="ajax_block_product item item-animate{if isset($item_class) && $item_class} {$item_class}{/if}">
			{counter name=i}
			{include file="$tpl_dir./product-blockgrid.tpl"}
		</div>
		{if $i % $SNSPRT_XS == 0}<div class="clearfix visible-xs"></div>{/if}
		{if $i % $SNSPRT_SM == 0}<div class="clearfix visible-sm"></div>{/if}
		{if $i % $SNSPRT_MD == 0}<div class="clearfix visible-md"></div>{/if}
		{if $i % $SNSPRT_LG == 0}<div class="clearfix visible-lg"></div>{/if}
	{/foreach}
	</div>
{addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}
{addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
{addJsDef comparator_max_item=$comparator_max_item}
{addJsDef comparedProductsIds=$compared_products}
{/if}
