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
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{assign var="current_option" value=Configuration::get('OVIC_CURRENT_OPTION')}
{if $page_name =='index'}
<!-- Module HomeSlider -->
    {if isset($homeslider_slides)}
        {if $current_option == 3}
            <div class="row home-slider">
                <div id="homepage-slider" class="col-sm-12 displayHomeSlider">
                    {if isset($homeslider_slides.0) && isset($homeslider_slides.0.sizes.1)}{capture name='height'}{$homeslider_slides.0.sizes.1}{/capture}{/if}
        			<ul id="homeslider"{if isset($smarty.capture.height) && $smarty.capture.height} style="max-height:{$smarty.capture.height}px;"{/if}>
        				{foreach from=$homeslider_slides item=slide}
        					{if $slide.active}
        						<li class="homeslider-container">
        							<a href="{$slide.url|escape:'html':'UTF-8'}" title="{$slide.legend|escape:'html':'UTF-8'}">
        								<img src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`homeslider/images/`$slide.image|escape:'htmlall':'UTF-8'`")}" alt="{$slide.legend|escape:'htmlall':'UTF-8'}" />
        							</a>
        							{if isset($slide.description) && trim($slide.description) != ''}
        								<div class="homeslider-description">{$slide.description}</div>
        							{/if}
        						</li>
        					{/if}
        				{/foreach}
        			</ul>
                </div>
            </div>
        {elseif $current_option == 1}
             <div class="row home-slider">
                <div class="col-lg-3 col-md-3 home-slider-left">
                    <div class="home-slider-left-inner"></div>
                </div>
                <div  id="homepage-slider"  class="col-lg-9 col-md-9 col-sm-12 displayHomeSlider">
                    {if isset($homeslider_slides.0) && isset($homeslider_slides.0.sizes.1)}{capture name='height'}{$homeslider_slides.0.sizes.1}{/capture}{/if}
        			<ul id="homeslider"{if isset($smarty.capture.height) && $smarty.capture.height} style="max-height:{$smarty.capture.height}px;"{/if}>
        				{foreach from=$homeslider_slides item=slide}
        					{if $slide.active}
        						<li class="homeslider-container">
        							<a href="{$slide.url|escape:'html':'UTF-8'}" title="{$slide.legend|escape:'html':'UTF-8'}">
        								<img src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`homeslider/images/`$slide.image|escape:'htmlall':'UTF-8'`")}"{if isset($slide.size) && $slide.size} {$slide.size}{else} width="100%" height="100%"{/if} alt="{$slide.legend|escape:'htmlall':'UTF-8'}" />
        							</a>
        							{if isset($slide.description) && trim($slide.description) != ''}
        								<div class="homeslider-description">{$slide.description}</div>
        							{/if}
        						</li>
        					{/if}
        				{/foreach}
        			</ul>
                </div>
            </div>
            {elseif $current_option == 2}
                 <div class="row home-slider">
                    <div class="col-lg-3 col-md-3 home-slider-left"></div>
                    <div  id="homepage-slider"  class="col-lg-9 col-md-9 col-sm-12 displayHomeSlider">
                        {if isset($homeslider_slides.0) && isset($homeslider_slides.0.sizes.1)}{capture name='height'}{$homeslider_slides.0.sizes.1}{/capture}{/if}
            			<ul id="homeslider"{if isset($smarty.capture.height) && $smarty.capture.height} style="max-height:{$smarty.capture.height}px;"{/if}>
            				{foreach from=$homeslider_slides item=slide}
            					{if $slide.active}
            						<li class="homeslider-container">
            							<a href="{$slide.url|escape:'html':'UTF-8'}" title="{$slide.legend|escape:'html':'UTF-8'}">
            								<img src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`homeslider/images/`$slide.image|escape:'htmlall':'UTF-8'`")}"{if isset($slide.size) && $slide.size} {$slide.size}{else} width="100%" height="100%"{/if} alt="{$slide.legend|escape:'htmlall':'UTF-8'}" />
            							</a>
            							{if isset($slide.description) && trim($slide.description) != ''}
            								<div class="homeslider-description">{$slide.description}</div>
            							{/if}
            						</li>
            					{/if}
            				{/foreach}
            			</ul>
                    </div>
                </div>
               {elseif $current_option == 5}
                 <div class="row home-slider">
                    <div class="col-lg-3 col-md-3 home-slider-left">
                        <div class="home-slider-left-inner"></div>
                    </div>
                    <div  id="homepage-slider"  class="col-lg-9 col-md-9 col-sm-12 displayHomeSlider">
                        {if isset($homeslider_slides.0) && isset($homeslider_slides.0.sizes.1)}{capture name='height'}{$homeslider_slides.0.sizes.1}{/capture}{/if}
            			<ul id="homeslider"{if isset($smarty.capture.height) && $smarty.capture.height} style="max-height:{$smarty.capture.height}px;"{/if}>
            				{foreach from=$homeslider_slides item=slide}
            					{if $slide.active}
            						<li class="homeslider-container">
            							<a href="{$slide.url|escape:'html':'UTF-8'}" title="{$slide.legend|escape:'html':'UTF-8'}">
            								<img src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`homeslider/images/`$slide.image|escape:'htmlall':'UTF-8'`")}"{if isset($slide.size) && $slide.size} {$slide.size}{else} width="100%" height="100%"{/if} alt="{$slide.legend|escape:'htmlall':'UTF-8'}" />
            							</a>
            							{if isset($slide.description) && trim($slide.description) != ''}
            								<div class="homeslider-description">{$slide.description}</div>
            							{/if}
            						</li>
            					{/if}
            				{/foreach}
            			</ul>
                    </div>
                </div>
        {else}
    		<div id="homepage-slider" class="clearfix {if $current_option == 4}col-xs-12 col-sm-9{/if}">
    			{if isset($homeslider_slides.0) && isset($homeslider_slides.0.sizes.1)}{capture name='height'}{$homeslider_slides.0.sizes.1}{/capture}{/if}
    			<ul id="homeslider"{if isset($smarty.capture.height) && $smarty.capture.height} style="max-height:{$smarty.capture.height}px;"{/if}>
    				{foreach from=$homeslider_slides item=slide}
    					{if $slide.active}
    						<li class="homeslider-container">
    							<a href="{$slide.url|escape:'html':'UTF-8'}" title="{$slide.legend|escape:'html':'UTF-8'}">
    								<img src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`homeslider/images/`$slide.image|escape:'htmlall':'UTF-8'`")}"{if isset($slide.size) && $slide.size} {$slide.size}{else} width="100%" height="100%"{/if} alt="{$slide.legend|escape:'htmlall':'UTF-8'}" />
    							</a>
    							{if isset($slide.description) && trim($slide.description) != ''}
    								<div class="homeslider-description">{$slide.description}</div>
    							{/if}
    						</li>
    					{/if}
    				{/foreach}
    			</ul>
    		</div>
        {/if}
	{/if}
<!-- /Module HomeSlider -->
{/if}