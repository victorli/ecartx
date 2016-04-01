{if $groupBanners && $groupBanners|@count > 0}
<div class="group-banners" data-banner-width="{$groupBannerSize.width}" data-banner-height="{$groupBannerSize.height}" data-w-per-h="{$groupBannerSize.w_per_h}" id="group-banner-{$groupBannerSize.groupId}">
    {foreach from=$groupBanners item=banner name=ojb}
        {if $smarty.foreach.ojb.first} 
            <div class="banner-item" id="banner-{$banner.groupId}-{$banner.itemId}">
                <div class="inner">
                    <div class="banner-img">
                        <a href="{$banner.link}" title="{$banner.title}">
                            <img title="{$banner.title}" alt="{$banner.title}" src="{$banner.image}" />
                        </a>
                    </div>
                </div>
            </div>
        {else}
            <div class="banner-item" id="banner-{$banner.groupId}-{$banner.itemId}" style="display: none">
                <div class="inner">
                    <div class="banner-img">
                        <a href="{$banner.link}" title="{$banner.title}">
                            <img title="{$banner.title}" alt="{$banner.title}" src="{$banner.image}" />
                        </a>
                    </div>
                </div>
            </div>
        {/if}        
    {/foreach}
</div>
{/if}
