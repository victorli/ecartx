{if $customBanners && $customBanners|@count > 0}
    <div class="custom-banners clearfix row">
        {foreach from=$customBanners item=banner name=bannerLoop}
            <div class="custom-banner {$banner.width} {$banner.className}">
                <a href="{$banner.banner_link}" target="_blank"><img class="img-responsive" src="{$banner.banner_image_src}" alt="{$banner.banner_title}" /></a>
            </div>               
        {/foreach}
    </div>
{/if}