{if isset($verticalCustoms) && $verticalCustoms}
    <div class="mega-custom-html">
        {foreach from=$verticalCustoms item=data name=ojb}
        
            {if $data.menuType == 'link'}
                <div class="item item-link {$verticalCustomWidth}"><div class="row"><a href="{$data.link}">{$data.title}</a></div></div>
            {else}
                {if $data.menuType == 'image'}
                    {if $data.imageSrc != ''}
                        <div class="item item-image {$verticalCustomWidth}"><div class="row"><a href="{$data.link}"><img src="{$data.imageSrc}" alt="{$data.title}" /></a></div></div>
                    {/if}
                {else}
                    <div class="item item-html {$verticalCustomWidth}"><div class="row custom-text">{$data.html}</div></div>
                {/if}    
            {/if}
        {/foreach}    
    </div>
{/if}