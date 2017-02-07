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
{if isset($images) AND $images}
		
	{if $gallery_img}
	<div class="gallery">
		<div class="gallery-slide">		
			{foreach from=$listimg item=img}
			<div class="img">
				<img 
					class="replace-2x" 
					src="{$link->getImageLink($link_rewrite, $img ,'deals_default')|escape:'html':'UTF-8'}" 
					alt="" 
					itemprop="image" data-src="{$link->getImageLink($link_rewrite, $img ,'deals_default')|escape:'html':'UTF-8'}" />
			</div>
			{/foreach}				
		</div>
	</div> 	
	{/if}
		
{/if}