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
{if $blockCategTree && $blockCategTree.children|@count}
<!-- Block categories module -->
<div id="sns_navigation" class="block block_navigation" >
	<div class="block-title">
	
	<strong>{l s='Category' mod='snsnavigation'} </strong>
	{*	{if isset($currentCategory)}{$currentCategory->name|escape}{else}{l s='Categories' mod='snsnavigation'}{/if} *}

	</div>
	<div class="block_content">
		<ul id="sns_sidenav">
		{foreach from=$blockCategTree.children item=child name=blockCategTree}
			{if $smarty.foreach.blockCategTree.last}
				{include file="./category-tree-branch.tpl" node=$child last='true'}
			{else}
				{include file="./category-tree-branch.tpl" node=$child}
			{/if}
		{/foreach}
		</ul>
	</div>
</div>
<!-- /Block categories module -->
{/if}
<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#sns_navigation .title_block').toggle(function() {
				$(this).find('[class*="fa-caret-"]').removeClass('fa-caret-down').addClass('fa-caret-up');
				$('#sns_navigation .block_content').stop(true, true).slideDown( "400", function() {
				});
			}, function() {
				$(this).find('[class*="fa-caret-"]').removeClass('fa-caret-up').addClass('fa-caret-down');
				$('#sns_navigation .block_content').stop(true, true).slideUp( "400", function() {
				});
			});
			$('#sns_sidenav').SnsAccordion({
				btn_open: '<i class="fa fa-plus"></i>',
				btn_close: '<i class="fa fa-minus"></i>'
			});
		});
</script>