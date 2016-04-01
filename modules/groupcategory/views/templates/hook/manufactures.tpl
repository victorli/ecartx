{if $ovicGroupCategoryManufactures && $ovicGroupCategoryManufactures|@count > 0}
<div class="group-manufacturer-list">
    <div id="owl-{$groupId}" class="manufacture-owl">            
        {foreach from=$ovicGroupCategoryManufactures item=manufacture name=ojb}
            <div class="item">
                <a title="{$manufacture.name}" href="{$manufacture.link}"><img src="{$manufacture.image}" alt="{$manufacture.name}" title="{$manufacture.name}"></a>
            </div>                
        {/foreach}            
    </div>
</div>
{/if}

