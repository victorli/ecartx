{if isset($items) && $items|@count >0}
	<ul class="category-list list">
	{foreach from=$items item=sub name=items}		
	    <li class="category-list-item check-active"><a role="tab" data-toggle="tab" data-id="{$module_id}" href=".tab-content-{$module_id}-0-{$sub.item_id}" class="tab-link">{$sub.item_name}</a></li>
	{/foreach}
	</ul>
{/if}
