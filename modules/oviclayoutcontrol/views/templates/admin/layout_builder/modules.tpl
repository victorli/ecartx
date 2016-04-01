{foreach $modules item=module name=$dataname}
    <div id="module_{$module.id}_{$module.id_hookexecute}" class="moduleContainer label-tooltip{if $module.tab == "analytics_stats"} disable_sort{/if}" data-module-byname="{$module.name}-{$module.hookexec_name}" data-module-info="module-{$module.id}-{$module.id_hookexecute}" data-html="true" data-toggle="tooltip" data-original-title="{$module.description}">
        <span class="module-position">{$module@iteration}</span>
        <span class="module-icon">
            <img src="{$moduleDir}/{$module.name}/logo.gif" alt="{$module.displayName}" />
        </span>
        <span class="module-name">{$module.displayName}</span>
        {if $module.tab != "analytics_stats"}
        <div class="module-action">
            <a href="javascript:void(0)" onclick="if (confirm('Are you sure remove this module?')){ldelim}
                return removeModuleHook($(this));{rdelim}else{ldelim} return false;{rdelim};" title="Remove">
               <i class="icon-trash"></i>{l s=' Remove' mod='oviclayoutcontrol'}
            </a>
            <a class="changeHook" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayChangeHook&id_hook={$id_hook}&id_option={$id_option}" title="Edit" >
               <i class="icon-pencil"></i> {l s=' Ovride hook' mod='oviclayoutcontrol'}
            </a>
        </div>
        {/if}
    </div>
{/foreach}