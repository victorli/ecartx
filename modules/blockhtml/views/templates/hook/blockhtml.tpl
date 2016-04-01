{if isset($item) && !empty($item)}
<div id="blockhtml_{$hook_position}" class="clearfix clearBoth">
    {if isset($item.title) && $item.title|count_characters >0}
        <h1 class="block-title">{$item.title}</h1>
    {/if}
    {if isset($item.content)}
        {$item.content}
    {/if}
</div>
{/if}