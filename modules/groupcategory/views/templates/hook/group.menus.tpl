{if $groupMenus}
<ul class="category-list test">
    {foreach from=$groupMenus item=menu name=ojb}
        <li class="category-list-item" data-group="{$groupId}" data-item="{$menu.id}" data-type="all"><a href="javascript:void(0)">{$menu.name}</a></li>
    {/foreach}
</ul>
{/if}
