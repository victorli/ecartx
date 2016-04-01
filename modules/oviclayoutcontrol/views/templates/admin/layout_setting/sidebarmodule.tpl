<div id="sidebarModule" class="panel clearfix" >
    <h3><i class="icon-list-ul"></i>&nbsp;{$pagename}</h3>
    <input id="ajaxUrl" type="hidden" name="ajaxUrl" value="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&pagemeta={$pagemeta}" />
    <div class="panel col-sm-6">
        <h3>{l s=' displayLeftColumn' mod='oviclayoutcontrol'}
            <span class="panel-heading-action">
        		<a class="list-toolbar-btn newmodulehook" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayModulesHook&hookname=left&pagemeta={$pagemeta}">
        			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new module" data-html="true">
        				<i class="process-icon-new "></i> {l s=' Add new module' mod='oviclayoutcontrol'}
        			</span>
        		</a>
        	</span>
        </h3>
        <div id="displayLeftColumn" class="hookContainer ui-sortable" data-hook="left">
            {if $leftModule && $leftModule|@count > 0}
                {include file="{$templatePath}layout_setting/modules.tpl" hookname=left pagemeta=$pagemeta modules=$leftModule dataname="displayLeftColumnModules"}
            {/if}
        </div>
    </div>
    <div class="panel col-sm-6{if isset($displayRight) && !$displayRight} disabled{/if}">
        <h3>{l s=' displayRightColumn' mod='oviclayoutcontrol'}
            <span class="panel-heading-action">
                <a class="list-toolbar-btn newmodulehook {if isset($displayRight) && !$displayRight} btn disabled{/if}" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayModulesHook&hookname=right&pagemeta={$pagemeta}">
        			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new module" data-html="true">
        				<i class="process-icon-new "></i> {l s=' Add new module' mod='oviclayoutcontrol'}
        			</span>
        		</a>
        	</span>
        </h3>
        <div id="displayRightColumn" class="hookContainer ui-sortable" data-hook="right">
            {if $rightModule && $rightModule|@count > 0}
                {include file="{$templatePath}layout_setting/modules.tpl" hookname=right pagemeta=$pagemeta modules=$rightModule dataname="displayRightColumnModules"}
            {/if}
        </div>
    </div>
    <div class="clearBoth"></div>
    <div class="panel-footer">
			<a href="{$postUrl|escape:'htmlall':'UTF-8'}&id_tab=2" class="btn btn-default">
				<i class="process-icon-back"></i> Back
			</a>
		</div>
</div>
