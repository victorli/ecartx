{if isset($banners) && $banners|@count > 0}
<div class="group-banners">
    {foreach from=$banners item=banner name=ojb}
        {if $smarty.foreach.ojb.first} 
            <div  class="banner-item {$banner.key} tab-pane fade in active">
                <div class="inner">
                    <div class="banner-img">
                        <a href="{$banner.link}" title="{$banner.title}">
                            <img alt="{$banner.title}" src="{$banner.img}" />
                        </a>
                    </div>
                </div>
            </div>
        {else}
        	<div class="banner-item {$banner.key} tab-pane fade">
                <div class="inner">
                    <div class="banner-img">
                        <a href="{$banner.link}" title="{$banner.title}">
                            <img alt="{$banner.title}" src="{$banner.img}" />
                        </a>
                    </div>
                </div>
            </div>
        {/if}        
    {/foreach}
</div>
{/if}