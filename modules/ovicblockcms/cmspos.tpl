<!-- Block CMS top -->
{if $cms_titles && count($cms_titles)>0}
<div id="cms_pos">
    {foreach from=$cms_titles key=cms_key item=cms_title}
        <div class="list-block">
            <p class="header-toggle-call cms_title">{$cms_title.name|escape:'html':'UTF-8'}</p>
    		<ul class="header-toggle">
    			{foreach from=$cms_title.cms item=cms_page}
    				{if isset($cms_page.link)}
    					<li>
    						<a href="{$cms_page.link|escape:'html':'UTF-8'}" title="{$cms_page.meta_title|escape:'html':'UTF-8'}">
    							{$cms_page.meta_title|escape:'html':'UTF-8'}
    						</a>
    					</li>
    				{/if}
    			{/foreach}
    		</ul>
        </div>
    {/foreach}
</div>
{/if}

<!-- /Block CMS top -->