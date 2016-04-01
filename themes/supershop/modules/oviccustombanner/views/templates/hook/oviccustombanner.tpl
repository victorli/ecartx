{assign var="current_option" value=Configuration::get('OVIC_CURRENT_OPTION')}
{if $customBanners && $customBanners|@count > 0}
    {if $current_option == 3}
        <div class="custom-banners row">
            {foreach from=$customBanners item=banner name=bannerLoop}
                <div class="custom-banner {$banner.width} {$banner.className}">           
                    <a href="{$banner.banner_link}" target="_blank"><img class="img-responsive" src="{$banner.banner_image_src}" alt="{$banner.banner_title}" /></a>
                </div>               
            {/foreach}
            <div class="clearfix"></div>
        </div>
    {elseif $current_option == 2}
        <div class="custom-banners clearfix row">
            {foreach from=$customBanners item=banner name=bannerLoop}            
                <div class="custom-banner col-sm-12">                
                    <a href="{$banner.banner_link}" target="_blank"><img class="img-responsive" src="{$banner.banner_image_src}" alt="{$banner.banner_title}" /></a>
                </div>               
            {/foreach}
        </div>
    {elseif $current_option == 5}
        <div class="custom-banners clearfix row">
            {foreach from=$customBanners item=banner name=bannerLoop}            
                <div class="custom-banner col-sm-12">                
                    <a href="{$banner.banner_link}" target="_blank"><img class="img-responsive" src="{$banner.banner_image_src}" alt="{$banner.banner_title}" /></a>
                </div>               
            {/foreach}
        </div>
    {elseif $current_option == 1}
        <div class="custom-banners clearfix row">
            {foreach from=$customBanners item=banner name=bannerLoop}
                <div class="custom-banner col-sm-6">
                    <a href="{$banner.banner_link}" target="_blank"><img class="img-responsive" src="{$banner.banner_image_src}" alt="{$banner.banner_title}" /></a>
                </div>               
            {/foreach}
        </div>    
    {elseif $current_option == 4}
        <div class="custom-banners clearfix row">
            {foreach from=$customBanners item=banner name=bannerLoop}
                <div class="custom-banner {$banner.width} {$banner.className}">
                    <a href="{$banner.banner_link}" target="_blank"><img class="img-responsive" src="{$banner.banner_image_src}" alt="{$banner.banner_title}" /></a>
                </div>               
            {/foreach}
        </div>    
    {/if}
{/if}