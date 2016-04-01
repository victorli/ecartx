{if isset($manufacturers) && isset($manufacturers.items) && $manufacturers.items|@count > 0}
<div class="group-manufacturer-list">
    {if $manufacturers.items|@count >1}
    <div id="owl-{$module.id}" class="manufacture-owl">            
        {foreach from=$manufacturers.items item=manufacture}
            <div class="item">
                <a title="{$manufacture.name}" href="{$manufacture.link}">
                    <img src="{$manufacture.image}" alt="{$manufacture.name}" title="{$manufacture.name}" />
                </a>
            </div>                
        {/foreach}            
    </div>
    {else}
    <div id="owl-{$module.id}" class="manufacture">            
        {foreach from=$manufacturers.items item=manufacture}
            <div class="item text-center">
                <a title="{$manufacture.name}" href="{$manufacture.link}">
                    <img src="{$manufacture.image}" alt="{$manufacture.name}" title="{$manufacture.name}" />
                </a>
            </div>                
        {/foreach}            
    </div>
    {/if}
</div>
{/if}
<!-- End Menufacture List -->