{if isset($manufacturers) && isset($manufacturers.items) && $manufacturers.items|@count > 0}
<div class="groupcategory-manufacturers" data-width="{$brandImageSize.width}" data-height="{$brandImageSize.height}">
    {if $manufacturers.items|@count >1}
    <div class="manufacture-carousel">            
        {foreach from=$manufacturers.items item=manufacture}
            <div class="item text-center">
                <a title="{$manufacture.name}" href="{$manufacture.link}">
                    <img class="img-responsive" width="{$brandImageSize.width}" height="{$brandImageSize.height}" src="{$manufacture.image}" alt="{$manufacture.name}" title="{$manufacture.name}" />
                </a>
            </div>                
        {/foreach}            
    </div>
    {else}
    <div class="manufacture-carousel">            
        {foreach from=$manufacturers.items item=manufacture}
            <div class="item text-center">
                <a title="{$manufacture.name}" href="{$manufacture.link}">
                    <img class="img-responsive" width="{$brandImageSize.width}" height="{$brandImageSize.height}" src="{$manufacture.image}" alt="{$manufacture.name}" title="{$manufacture.name}" />
                </a>
            </div>                
        {/foreach}            
    </div>
    {/if}
</div>
{/if}
<!-- End Menufacture List -->