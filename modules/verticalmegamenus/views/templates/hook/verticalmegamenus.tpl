{if isset($verticalModules) && $verticalModules}
    {foreach from=$verticalModules item=module name=verticalModules}
        {if $module.layout == 'default'}
        	<div class="box-vertical-megamenus">
			    <div class="vertical-megamenus">			        
			        {$module.sections}
			    </div>
			</div>			
        {/if}
    {/foreach}    
{/if}
<script type="text/javascript">
    var verticalModuleUrl = "{$verticalModuleUrl}";    
</script>