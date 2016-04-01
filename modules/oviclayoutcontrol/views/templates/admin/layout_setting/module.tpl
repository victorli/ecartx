<div id="module_{$moduleObject->id}_{$id_hookexec}" class="moduleContainer" data-module-byname="{$moduleObject->name}-{$hookexec_name}" data-module-info="module-{$moduleObject->id}-{$id_hookexec}">
    <span class="module-position">{$modulePosition}</span>
    <span class="module-icon">
        <img src="{$moduleDir}/{$moduleObject->name}/logo.gif" alt="{$moduleObject->displayName}" />
    </span>
    <span class="module-name label-tooltip" data-html="true" data-toggle="tooltip" data-original-title="{$moduleObject->description}">{$moduleObject->displayName}</span>
    <div class="module-action">
        <a href="#" onclick="if (confirm('Are you sure remove this module?')){ldelim}
            return removeSideBarModule($(this));{rdelim}else{ldelim} return false;{rdelim};" title="Remove">
           <i class="icon-trash"></i>{l s=' Remove' mod='oviclayoutcontrol'}
        </a>
        <a class="changeHook" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayChangeHook&hookcolumn={$hookcolumn}&pagemeta={$pagemeta}" title="Edit" >
           <i class="icon-pencil"></i> {l s=' Ovride hook' mod='oviclayoutcontrol'}
        </a>
    </div>
</div>