<ul class="nav nav-tabs" id="idTabs">
	<li {if $id_tab == 0} class="active"{/if}><a data-toggle="tab" href="#idTab1" class="tab-pane" title="{l s='Layout setting'}" rel="nofollow">{l s='Layout setting'}</a></li>
	{*}<li {if $id_tab == 1} class="active"{/if}><a data-toggle="tab" href="#idTab2" class="tab-pane" title="{l s='Theme Color & Font setting'}" rel="nofollow">{l s='Theme Color & Font setting'}</a></li>{*}
	<li {if $id_tab == 2} class="active"{/if}><a data-toggle="tab" href="#idTab3" class="tab-pane" title="{l s='Sidebar seting'}" rel="nofollow">{l s='Sidebar seting'}</a></li>
</ul>
<div id="LayoutControl" class="panel tab-content clearfix">
	<div id="idTab1" class="tab-pane {if $id_tab == 0} active{/if}">
        {if !isset($emptyOption)}
            <div id="curent_option" class="panel">
    			<div class="panel-heading"><i class="icon-html5"></i>{l s=' Your current option'}</div>
                <form id="column_popup" method="post" action="{$postUrl|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data" class="item-form defaultForm">
                <input type="hidden" name="id_option" value="{if isset($current_option)}{$current_option->id_option}{/if}"/>
                <div class="row row-padding-top">
        			<div class="col-md-3">
      					<img class="center-block img-thumbnail" src="{$absoluteUrl}/thumbnails/{if $current_option->image|count_characters>0}{$current_option->image}{else}en.jpg{/if}" alt="{$current_option->name}" />
        			</div>
    	            <div class="col-md-9">
    			        <h2>{$current_option->name}</h2>
                        <hr />
    			        <h4>{l s='Select layout'}</h4>
        				<div class="row  colsetting-container">
                            <span class="fixed-width-lg radio_button">
                                {*}3 column{*}
                                {if (isset($current_option->column)&&$current_option->column|strpos:"0" !== false)}
                                    <input class="colsetting" type="radio" name="colsetting" id="3column" {if (isset($selected_layout)&& $selected_layout==0)||(!$selected_layout)}checked="checked"{/if} value="0"/>
                                    <label {if (isset($selected_layout)&& $selected_layout == 0)||(!$selected_layout)}class="colactive"{/if} for="3column"><img src="{$absoluteUrl}/img/3col.png" alt=""/></label>
                                {/if}
                                {*}left column only {*}
                                {if isset($current_option->column)&&$current_option->column|strpos:"1" !== false}
                                    <input class="colsetting" type="radio" name="colsetting" id="leftonly" {if (isset($selected_layout)&& $selected_layout==1)}checked="checked"{/if} value="1" />
                                    <label {if (isset($selected_layout)&& $selected_layout==1)}class="colactive"{/if} for="leftonly"><img src="{$absoluteUrl}/img/leftonly.png" alt=""/></label>
                                {/if}
                                {*}right column only {*}
                                {if isset($current_option->column)&&$current_option->column|strpos:"2" !== false}
                                    <input class="colsetting" type="radio" name="colsetting" id="rightonly" {if (isset($selected_layout)&& $selected_layout==2)}checked="checked"{/if} value="2" />
                                    <label {if (isset($selected_layout)&& $selected_layout==2)}class="colactive"{/if} for="rightonly"><img src="{$absoluteUrl}/img/rightonly.png" alt=""/></label>
                                {/if}
                                {*}no column{*}
                                {if isset($current_option->column)&& substr_count($current_option->column, "3")>0}
                                    <input class="colsetting" type="radio" name="colsetting" id="nocolumn" {if (isset($selected_layout)&& $selected_layout==3)}checked="checked"{/if} value="3" />
                                    <label {if (isset($selected_layout)&& $selected_layout==3)}class="colactive"{/if} for="nocolumn"><img src="{$absoluteUrl}/img/full.png" alt=""/></label>
                                {/if}
                            </span>
        				</div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-default pull-right" name="submitChangeLayout">
                        <i class="process-icon-save"></i> Save</button>
                </div>
                </form>
    		</div> {*}panel{*}
            {if isset($options) && $options|@count>1}
                <div class="panel">
                    <div class="panel-heading">
        				<i class="icon-cogs"></i>{l s=' Select an option'}
                    </div>
                    <div class="clearfix">
                    {foreach from=$options item=option name=list_option}
                        {if $option.id_option != $current_option->id_option}
                        <div class="col-sm-4 col-lg-3 option_container">
        						<div class="theme-container">
        							<h4 class="theme-title">{$option.name}</h4>
        							<div class="thumbnail-wrapper">
                                        <div class="action-wrapper">
        								    <div class="action-overlay"></div>
                                            <div class="action-buttons">
                                                <div class="btn-group">
            										<a href="{$postUrl|escape:'htmlall':'UTF-8'}&amp;submitSelectOption&amp;&amp;id_option={$option.id_option}" class="btn btn-default">
            											<i class="icon-check"></i> {l s='Use this option'}
            										</a>
            									</div>
                                            </div>
                                        </div>
                                        <img class="center-block img-thumbnail" src="{$absoluteUrl}/thumbnails/{if $option.image|count_characters>0}{$option.image}{else}en.jpg{/if}" alt="{$option.name}" />
        							</div>
                                    <div class="colsetting-container">
                                        <div class="colsetting-wrapper">
                                            <h4 class="theme-title">Support Layout</h4>
                                            <label {if $option.column|strpos:"0" !== false}class="colactive"{/if}>
                                                <img src="{$absoluteUrl}/img/3col.png" alt=""/>
                                            </label>
                                            <label {if $option.column|strpos:"1" !== false}class="colactive"{/if}>
                                                <img src="{$absoluteUrl}/img/leftonly.png" alt=""/>
                                            </label>
                                            <label {if $option.column|strpos:"2" !== false}class="colactive"{/if}>
                                                <img src="{$absoluteUrl}/img/rightonly.png" alt=""/>
                                            </label>
                                            <label {if $option.column|strpos:"3" !== false}class="colactive"{/if}>
                                                <img src="{$absoluteUrl}/img/full.png" alt=""/>
                                            </label>
                                        </div>
                                    </div>
        						</div>
        					</div>
                            {/if}
                    {/foreach}
                    </div>
                </div>
            {/if}
        {else}
            <div class="alert alert-danger" role="alert">
                {$emptyOption}
            </div>
        {/if}
    </div>
    {*}<div id="idTab2" class="tab-pane {if $id_tab == 1} active{/if}">
        {$multistyle}
    </div>{*}
    <div id="idTab3" class="tab-pane {if $id_tab == 2} active{/if}">
        <div class="panel">
            <div class="panel-heading">
	           <i class="icon-columns"></i> {l s='Appearance of columns'}
            </div>
            <table class="table">
                <thead>
                    <th>{l s='Meta'}</th>
                    <th>{l s='Left column'}</th>
                    <th>{l s='Right column'}</th>
                    <th>{l s='Action'}</th>
                </thead>
                <tbody>
                    {if isset($sidebarPages) && $sidebarPages && $sidebarPages|@count>0}
                        {foreach from=$sidebarPages item=sidebarPage name=sidebarPages}
                            {if $sidebarPage.page_name !== 'index'}
                            <tr>
                                <td>{$sidebarPage.title}</td>
                                <td>
                                    {if $sidebarPage.displayLeft}
                                        <a href="{$postUrl}&changeleftactive&pagemeta={$sidebarPage.page_name}&id_tab=2" data-toggle="tooltip" class="label-tooltip list-action-enable action-enabled" data-html="true" data-original-title="Actived" >
                                            <i class="icon-check"></i>
                                        </a>
                                    {else}
                                        <a href="{$postUrl}&changeleftactive&pagemeta={$sidebarPage.page_name}&id_tab=2" data-toggle="tooltip" class="label-tooltip list-action-enable action-disabled" data-html="true" data-original-title="Deactived" >
                                            <i class="icon-remove"></i>
                                        </a>
                                    {/if}
                                </td>
                                <td>
                                    {if $sidebarPage.displayRight}
                                        <a href="{$postUrl}&changerightactive&pagemeta={$sidebarPage.page_name}&id_tab=2" data-toggle="tooltip" class="label-tooltip list-action-enable action-enabled" data-html="true" data-original-title="Actived" >
                                            <i class="icon-check"></i>
                                        </a>
                                    {else}
                                        <a href="{$postUrl}&changerightactive&pagemeta={$sidebarPage.page_name}&id_tab=2" data-toggle="tooltip" class="label-tooltip list-action-enable action-disabled" data-html="true" data-original-title="Deactived" >
                                            <i class="icon-remove"></i>
                                        </a>
                                    {/if}
                                </td>
                                <td>
                                <div class="btn-group-action">
                                    <div class="btn-group pull-right">

                                        {if $sidebarPage.displayLeft || $sidebarPage.displayRight}
                					       <a href="{$postUrl}&view=detail&pagemeta={$sidebarPage.page_name}" title="Configure" class="btn btn-default">
                    	                       <i class="icon-wrench"></i> Configure
                                            </a>
                                        {else}
                                            <a href="{$postUrl}&view=detail&pagemeta={$sidebarPage.page_name}" title="Configure" class="btn btn-default disabled">
                    	                       <i class="icon-wrench"></i> Configure
                                            </a>
                                        {/if}
                                    </div>
                				</div>
                            </td>
                            </tr>
                            {/if}
                        {/foreach}
                    {/if}
                </tbody>
            </table>
        </div>
    </div>
</div>