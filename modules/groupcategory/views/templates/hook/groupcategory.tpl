{if isset($groupCategoryModules) && $groupCategoryModules}
    {foreach from=$groupCategoryModules item=module}
        {$module.content}
    {/foreach}    
{/if}
{addJsDef comparator_max_item=$comparator_max_item}
{addJsDef comparedProductsIds=$compared_products}