{if $groupTypes}
<ul class="group-types clearfix">
    {foreach from=$groupTypes item=type name=ojb}
        <li class="group-type" data-group="{$groupId}" data-item="0" data-type="{$type.value}">
            <div><i class="icon-20x15 {$type.value}-icon"></i><br /><span>{$type.name}</span></div>
        </li>                
    {/foreach}
</ul>
{/if}
