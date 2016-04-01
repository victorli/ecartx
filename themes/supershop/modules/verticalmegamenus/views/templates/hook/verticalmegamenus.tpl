{assign var="current_option" value=Configuration::get('OVIC_CURRENT_OPTION')}
{if $current_option == 4}
    {assign var="colvertical" value='col-sm-1'}
{else}
    {assign var="colvertical" value='col-sm-3'}
{/if}
{if isset($verticalModules) && $verticalModules}
    {foreach from=$verticalModules item=module name=verticalModules}
        {if $module.layout == 'default'}
            {if $page_name == 'index'}
			    <div class="{$colvertical} hidden-xs home-page">
                	<div class="box-vertical-megamenus">
            		    <div class="vertical-megamenus">			        
            		        {$module.sections}
            		    </div>
            		</div>
                </div>		
            {else}
    		    <div class="{$colvertical} other-pages hidden-xs">
                    <div class="box-vertical-megamenus">
            		    <div class="vertical-megamenus">			        
            		        {$module.sections}
            		    </div>
            		</div>
                </div>
            {/if}
        {/if}
    {/foreach}    
{/if}