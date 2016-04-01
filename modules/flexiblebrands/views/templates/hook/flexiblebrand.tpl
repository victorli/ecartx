{if isset($modules) && $modules}
    {foreach from=$modules item=module name=obj}
        <div class="flexible-brand-box {$moduleLayout} clearfix">
            {$module.groups}
        </div>        
    {/foreach}    
{/if}