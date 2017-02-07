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



<div id="sns_blockdeal" class="block_content wrap">
	<div class="row">
		<div class="col-xs-8 col-phone-12">
			{hook h="displayDeal"}	
		</div>
		<div class="col-xs-4 col-phone-12">
			{if isset($SNS_NAZ_BANNER_HOME_1) && $SNS_NAZ_BANNER_HOME_1 && $SNS_ALLOW_BANNER == 1}
				<a href="#" class="banner mrb30">
					<img src="{$SNS_NAZ_BANNER_HOME_1}" alt=""/>
				</a>
			{/if}
		</div>
	</div>
</div>

<div class="sns_producttab_slider">
	{hook h="displaySlideProductTab"}
</div>

{if isset($SNS_NAZ_BANNER_HOME_2) && $SNS_NAZ_BANNER_HOME_2 && $SNS_ALLOW_BANNER == 1}
<div class="block_content mrb30">
		<a href="#" class="banner">
			<img src="{$SNS_NAZ_BANNER_HOME_2}" alt=""/>
		</a>
</div>
{/if}


{if isset($HOOK_HOME) && $HOOK_HOME|trim}
	{$HOOK_HOME}
{/if}


<div class="block_newletter ">
	{hook h="displayNewletter"}
</div>


<div class="sns_producttab_slider">
	{hook h="displayProductslider"}
</div>

























