{**
* 2015 SNSTheme
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
*  @author    SNSTheme <contact@snstheme.com>
*  @copyright 2015 SNSTheme
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of SNSTheme
*}


<!-- index -->
{if $page_name == 'index'}
{hook h="displayBestsale"}

{if isset($SNS_NAZ_BANNER_HOME_LEFT) && $SNS_NAZ_BANNER_HOME_LEFT && $SNS_ALLOW_BANNER == 1}
<div class="block">
		<a href="#" class="banner">
			<img src="{$SNS_NAZ_BANNER_HOME_LEFT}" alt=""/>
		</a>
</div>
{/if}

{hook h="displaySpecialProduct"}
{/if}

 <!-- Category -->
{if $page_name == 'category'}
	{if isset($SNS_NAZ_BANNER_GVL_LEFT) && $SNS_NAZ_BANNER_GVL_LEFT && $SNS_ALLOW_BANNER == 1}
	<div class="block">
			<a href="#" class="banner">
				<img src="{$SNS_NAZ_BANNER_GVL_LEFT}" alt=""/>
			</a>
	</div>
	{/if}
{/if}

<!-- Product -->

{if $page_name == 'product'}
{hook h="displayBestsale"}

{if isset($SNS_ALLOW_BANNER_LEFT_PRD) && $SNS_ALLOW_BANNER_LEFT_PRD && $SNS_ALLOW_BANNER_LEFT_PRD == 1}
<div class="block">
		<a href="#" class="banner">
			<img src="{$SNS_NAZ_BANNER_PRD_LEFT}" alt=""/>
		</a>
</div>
{/if}

{/if}





