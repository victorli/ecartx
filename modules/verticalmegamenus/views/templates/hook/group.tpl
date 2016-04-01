{if isset($verticalGroups) && $verticalGroups}
    <div class="dropdown-menu vertical-dropdown-menu">
        <div class="vertical-groups {$groupWidth}">
            <div class="clearfix">
                {foreach from=$verticalGroups item=data name=ojb}
                    <div class="mega-group {$data.width}">
                        {if $data.display_title}<h4 class="mega-group-header"><span>{$data.title}</span></h4>{/if}
                        {$data.group_content}
                    </div>                    
                {/foreach}    
            </div>
        </div>
    </div>
{/if}