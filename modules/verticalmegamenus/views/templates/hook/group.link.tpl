{if isset($verticalLinks) && $verticalLinks}
    <ul class="group-link-default">
        {foreach from=$verticalLinks item=data name=ojb}
        
            {if $data.menuType == 'link'}
                <li><a href="{$data.link}">{$data.title}</a></li>
            {else}
                {if $data.menuType == 'image'}
                    {if $data.imageSrc != ''}
                        <li><a href="{$data.link}" title="{$data.title}"><img src="{$data.imageSrc}" alt="{$data.title}" /></a></li>
                    {/if}
                {else}
                    <li>
                        <!-- <div class="custom-link"><a itemprop="url" href="{$data.link}">{$data.title}</a></div> -->
                        <div class="custom-text">{$data.html}</div>
                    </li>
                {/if}    
            {/if}
        {/foreach}    
    </ul>
{/if}