<div id="ovicLayoutSeting">
{if $view == 'displaylist'}
    <div class="panel clearfix" >
        <h3><i class="icon-list-ul"></i>{l s=' Options list' mod='oviclayoutbuilder'}
            <span class="panel-heading-action">
        		<a class="list-toolbar-btn newoption" href="{$postUrl|escape:'htmlall':'UTF-8'}&view=setting">
        			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new option" data-html="true">
        				<i class="process-icon-new "></i> {l s=' Add new option' mod='oviclayoutbuilder'}
        			</span>
        		</a>
        	</span>
        </h3>
        <div class="main-container">
        {if isset($options) && $options|count > 0}
            <table class="table">
                <thead>
                    <th width="5%">id</th>
                    <th width="20%">name</th>
                    <th width="35%">Support Layout</th>
                    <th width="20%">Thumbnail</th>
                    <th width="10%">Acive</th>
                    <th width="10%">Action</th>
                </thead>
                <tbody>
                    {foreach $options as $option}
                        <tr>
                            <td>{$option.id_option}</td>
                            <td>{$option.name}</td>
                            <td class="colsetting-container">
                                {if $option.column|strpos:"0" !== false}
                                    <label class="colactive">
                                        <img src="{$absoluteUrl}/img/3col.png" alt=""/>
                                    </label>
                                {/if}
                                {if $option.column|strpos:"1" !== false}
                                    <label class="colactive">
                                        <img src="{$absoluteUrl}/img/leftonly.png" alt=""/>
                                    </label>
                                {/if}
                                {if $option.column|strpos:"2" !== false}
                                    <label class="colactive">
                                        <img src="{$absoluteUrl}/img/rightonly.png" alt=""/>
                                    </label>
                                {/if}
                                {if $option.column|strpos:"3" !== false}
                                    <label class="colactive">
                                        <img src="{$absoluteUrl}/img/full.png" alt=""/>
                                    </label>
                                {/if}
                            </td>
                            <td>{if $option.image}<img class="img-thumbnail" src="{$thumbnails_dir}/thumbnails/{$option.image}" alt="" />{/if}</td>
                            <td>
                                    {if $option.active}
                                        <a href="{$postUrl}&changeactive&id_option={$option.id_option}" data-toggle="tooltip" class="label-tooltip list-action-enable action-enabled" data-html="true" data-original-title="Actived" >
                                        {*}<img src="{$smarty.const._PS_ADMIN_IMG_}ok.gif" alt="" />{*}
                                            <i class="icon-check"></i>
                                        </a>
                                    {else}
                                        <a href="{$postUrl}&changeactive&id_option={$option.id_option}" data-toggle="tooltip" class="label-tooltip list-action-enable action-disabled" data-html="true" data-original-title="Deactived" >
                                        {*}<img src="{$smarty.const._PS_ADMIN_IMG_}forbbiden.gif" alt="" />{*}
                                            <i class="icon-remove"></i>
                                        </a>
                                    {/if}
                            </td>
                            <td>
                                <div class="btn-group-action">
                                    <div class="btn-group pull-right">
                                        {if $option.active}
                					       <a href="{$postUrl}&view=detail&id_option={$option.id_option}" title="Edit" class="btn btn-default">
                    	                       <i class="icon-wrench"></i> Configure
                                            </a>
                                        {else}
                                            <a href="{$postUrl}&view=setting&id_option={$option.id_option}" title="Edit" class="btn btn-default">
                    	                       <i class="icon-pencil"></i> Edit
                                            </a>
                                        {/if}
                                        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                						  <i class="icon-caret-down"></i>&nbsp;
        					            </button>
                						<ul class="dropdown-menu">
                                            {if $option.active}
                                            <li>
                                                <a href="{$postUrl}&view=setting&id_option={$option.id_option}" title="Edit" >
                        	                       <i class="icon-pencil"></i> Edit
                                                </a>
                                            </li>
                                            <li class="divider"></li>
                                            {/if}
                                            <li>
                								<a href="{$postUrl|escape:'htmlall':'UTF-8'}&view=setting&submitCopyOption&id_copy_option={$option.id_option}" title="Duplicate" onclick="return confirm('Are you sure copy this option.')">
                	                               <i class="icon-copy"></i> Duplicate
                                                </a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a href="{$postUrl}&removeoption&id_option={$option.id_option}" onclick="return confirm('Are you sure delete this option, including option\'s modules Positions?')" title="Delete" class="delete">
                	                               <i class="icon-trash"></i> Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                				</div>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        {/if}
        </div>
    </div>
{elseif $view == 'setting'}
    <div class="panel clearfix" >
        <h3><i class="icon-list-ul"></i>{l s=' Options Setting' mod='oviclayoutbuilder'}
        </h3>
        <form id="layoutconfigure" method="post" action="{$postUrl|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data" class="item-form defaultForm  form-horizontal">
            <input type="hidden" name="id_option" value="{if isset($option->id_option)}{$option->id_option}{/if}"/>
            {if isset($id_copy_option)}
                <input type="hidden" name="id_copy_option" value="{$id_copy_option}"/>
            {/if}
            <input type="hidden" name="view" value="setting"/>
            <div class="title item-field form-group">
				<label id="title_lb" class="control-label col-lg-3 ">{l s='Name' mod='oviclayoutbuilder'}</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        {foreach from=$langguages.all item=lang}
                            <div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
    				            <div class="col-lg-9">
    				                <input class="form-control" type="text" id="option_name" name="option_name_{$lang.id_lang}" value="{if isset($option->name[$lang.id_lang])}{$option->name[$lang.id_lang]}{/if}"/>
    				            </div>
    							<div class="col-lg-2">
    								<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
    									{$lang.iso_code|escape:'htmlall':'UTF-8'}
    									<i class="icon-caret-down"></i>
    								</button>
    								{$lang_ul}
    							</div>
    						</div>
						  {/foreach}
                     </div>
				</div>
			</div>
            <div class="item-field form-group">
                <label for="colsetting" class="control-label col-lg-3">{l s='Support Layout' mod='oviclayoutbuilder'}</label>
                <div class="col-lg-9 colsetting-container">
                    <div class="form-group">
                        <div class="col-lg-9">
                            <span class="fixed-width-lg{if isset($option->id_option) && $option->id_option} not-editable{else} editable{/if}">
                                {*}3 column{*}
                                <input type="hidden" name="colselected" id="colselected" value="{if isset($option->column)}{$option->column}{else}0{/if}" />
                                <input class="colsetting multiselect" type="checkbox" name="colsetting" id="3column" {if isset($option->column)&&$option->column|strpos:"0" !== false || !isset($colsetting)}checked="checked"{/if} value="0"/>
                                <label {if (isset($option->column)&&$option->column|strpos:"0" !== false) || !isset($option->column)}class="colactive"{/if} for="3column"><img src="{$absoluteUrl}/img/3col.png" alt=""/></label>
                                {*}left column only {*}
                                <input class="colsetting multiselect" type="checkbox" name="colsetting" id="leftonly"{if isset($option->column)&&$option->column|strpos:"1" !== false}checked="checked"{/if} value="1" />
                                <label {if isset($option->column)&&$option->column|strpos:"1" !== false}class="colactive"{/if} for="leftonly"><img src="{$absoluteUrl}/img/leftonly.png" alt=""/></label>
                                {*}right column only {*}
                                <input class="colsetting multiselect" type="checkbox" name="colsetting" id="rightonly" {if isset($option->column)&&$option->column|strpos:"2" !== false}checked="checked"{/if} value="2" />
                                <label {if isset($option->column)&&$option->column|strpos:"2" !== false}class="colactive"{/if} for="rightonly"><img src="{$absoluteUrl}/img/rightonly.png" alt=""/></label>
                                {*}no column{*}
                                <input class="colsetting multiselect" type="checkbox" name="colsetting" id="nocolumn" {if isset($option->column)&&$option->column|strpos:"3" !== false}checked="checked"{/if} value="3" />
                                <label {if isset($option->column)&& substr_count($option->column, "3")>0}class="colactive"{/if} for="nocolumn"><img src="{$absoluteUrl}/img/full.png" alt=""/></label>
                            </span>
                        </div>
                        <div class="col-lg-2">
        				</div>
                    </div>
                </div>
            </div>
            <div class="image item-field form-group">
				<label class="control-label col-lg-3">Thumbnails</label>
				<div class="col-lg-9">
                    <div class="form-group">
                        <div class="col-lg-9">
                            {if isset($option->image)}
                                <img class="img-thumbnail" src="{$thumbnails_dir}/thumbnails/{$option->image}" alt="" />
                            {/if}
        					<input type="file" name="option_img" />
                            <input type="hidden" name="old_img" value="{if isset($option->image)}{$option->image}{/if}"/>
                        </div>
                        <div class="col-lg-2">
                        </div>
                    </div>
				</div>
			</div>
            <div class="item-field form-group ">
                <label for="active" class="control-label col-lg-3">Active</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        <div class="col-lg-9">
                            <span class="switch prestashop-switch fixed-width-lg">
                                <input type="radio" name="active" id="active_on" {if isset($option->active)&&$option->active == 1 }checked="checked"{/if} value="1"/>
                                <label for="active_on">Yes</label>
                                <input type="radio" name="active" id="active_off" {if isset($option->active)&&$option->active == 0 || !isset($option->active)}checked="checked"{/if} value="0" />
                                <label for="active_off">No</label>
                                <a class="slide-button btn"></a>
                            </span>
                        </div>
                        <div class="col-lg-2">
						</div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" value="1" name="submitnewOption" class="btn btn-default pull-right" onclick="this.form.submit();">
                <i class="process-icon-save"></i> Save
				</button>
				<a href="{$postUrl|escape:'htmlall':'UTF-8'}" class="btn btn-default" onclick="window.history.back();">
					<i class="process-icon-cancel"></i> Cancel
				</a>
			</div>
        </form>
    </div>
{elseif $view == 'detail'}
    <div class="panel clearfix" >
        <h3><i class="icon-list-ul"></i>{l s=' Layout Configure' mod='oviclayoutbuilder'}</h3>
        <input id="ajaxUrl" type="hidden" name="ajaxUrl" value="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1" />
        <input id="id_option" type="hidden" name="id_option" value="{$id_option}" />
        <div class="panel">
            <h3>
                {l s=' displayNav' mod='oviclayoutbuilder'}
                <span class="panel-heading-action">
            		<a class="list-toolbar-btn newmodulehook" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayModulesHook&id_hook={$optionModulesHook.displayNav.id_hook}&id_option={$id_option}">
            			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new module" data-html="true">
            				<i class="process-icon-new "></i> {l s=' Add new module' mod='oviclayoutbuilder'}
            			</span>
            		</a>
            	</span>
            </h3>
            <div id="displayNav" class="hookContainer ui-sortable">
            {if $optionModulesHook.displayNav.modules && $optionModulesHook.displayNav.modules|@count > 0}
                {include file="{$templatePath}layout_builder/modules.tpl" id_hook={$optionModulesHook.displayNav.id_hook} id_option={$id_option} modules=$optionModulesHook.displayNav.modules dataname="displayNavModules"}
            {/if}
            </div>
        </div>
        <div class="clearfix">
            <div class="panel col-sm-4">
                <h3>{l s=' displayBeforeLogo' mod='oviclayoutbuilder'}
                    <span class="panel-heading-action">
                		<a class="list-toolbar-btn newmodulehook" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayModulesHook&id_hook={$optionModulesHook.displayBeforeLogo.id_hook}&id_option={$id_option}">
                			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new module" data-html="true">
                				<i class="process-icon-new "></i> {l s=' Add new module' mod='oviclayoutbuilder'}
                			</span>
                		</a>
                	</span>
                </h3>
                <div id="displayBeforeLogo" class="hookContainer ui-sortable">
                    {if $optionModulesHook.displayBeforeLogo.modules && $optionModulesHook.displayBeforeLogo.modules|@count > 0}
                        {include file="{$templatePath}layout_builder/modules.tpl" id_hook={$optionModulesHook.displayBeforeLogo.id_hook} id_option={$id_option} modules=$optionModulesHook.displayBeforeLogo.modules dataname="displayBeforeLogoModules"}
                    {/if}
                </div>
            </div>
            <div class="panel col-sm-4">
                <img class="logo img-responsive" src="{$logo_url}" alt=""/>
            </div>
            <div class="panel col-sm-4">
                <h3>{l s=' displayTop' mod='oviclayoutbuilder'}
                    <span class="panel-heading-action">
                		<a class="list-toolbar-btn newmodulehook" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayModulesHook&id_hook={$optionModulesHook.displayTop.id_hook}&id_option={$id_option}">
                			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new module" data-html="true">
                				<i class="process-icon-new "></i> {l s=' Add new module' mod='oviclayoutbuilder'}
                			</span>
                		</a>
                	</span>
                </h3>
                <div id="displayTop" class="hookContainer ui-sortable">
                    {if $optionModulesHook.displayTop.modules && $optionModulesHook.displayTop.modules|@count > 0}
                        {include file="{$templatePath}layout_builder/modules.tpl" id_hook={$optionModulesHook.displayTop.id_hook} id_option={$id_option} modules=$optionModulesHook.displayTop.modules dataname="displayTopModules"}
                    {/if}
                </div>
            </div>
        </div>
        <div class="panel">
            <h3>{l s=' displayTopColumn' mod='oviclayoutbuilder'}
                <span class="panel-heading-action">
            		<a class="list-toolbar-btn newmodulehook" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayModulesHook&id_hook={$optionModulesHook.displayTopColumn.id_hook}&id_option={$id_option}">
            			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new module" data-html="true">
            				<i class="process-icon-new "></i> {l s=' Add new module' mod='oviclayoutbuilder'}
            			</span>
            		</a>
            	</span>
            </h3>
            <div id="displayTopColumn" class="hookContainer ui-sortable">
                {if $optionModulesHook.displayTopColumn.modules && $optionModulesHook.displayTopColumn.modules|@count > 0}
                    {include file="{$templatePath}layout_builder/modules.tpl" id_hook={$optionModulesHook.displayTopColumn.id_hook} id_option={$id_option} modules=$optionModulesHook.displayTopColumn.modules dataname="displayTopColumnModules"}
                {/if}
            </div>
        </div>
        <hr class="homeline"/>
        <div class="panel">
            <h3>{l s=' displayHomeTopColumn' mod='oviclayoutbuilder'}
                <span class="panel-heading-action">
            		<a class="list-toolbar-btn newmodulehook" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayModulesHook&id_hook={$optionModulesHook.displayHomeTopColumn.id_hook}&id_option={$id_option}">
            			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new module" data-html="true">
            				<i class="process-icon-new "></i> {l s=' Add new module' mod='oviclayoutbuilder'}
            			</span>
            		</a>
            	</span>
            </h3>
            <div id="displayHomeTopColumn" class="hookContainer ui-sortable">
                {if $optionModulesHook.displayHomeTopColumn.modules && $optionModulesHook.displayHomeTopColumn.modules|@count > 0}
                    {include file="{$templatePath}layout_builder/modules.tpl" id_hook={$optionModulesHook.displayHomeTopColumn.id_hook} id_option={$id_option} modules=$optionModulesHook.displayHomeTopColumn.modules dataname="displayHomeTopColumnModules"}
                {/if}
            </div>
        </div>
        <div class="clearfix">
            {if $displayLeft}
                <div class="panel col-sm-3">
                    <h3>{l s=' displayLeftColumn' mod='oviclayoutbuilder'}
                        <span class="panel-heading-action">
                    		<a class="list-toolbar-btn newmodulehook" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayModulesHook&id_hook={$optionModulesHook.displayLeftColumn.id_hook}&id_option={$id_option}">
                    			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new module" data-html="true">
                    				<i class="process-icon-new "></i> {l s=' Add new module' mod='oviclayoutbuilder'}
                    			</span>
                    		</a>
                    	</span>
                    </h3>
                    <div id="displayLeftColumn" class="hookContainer ui-sortable">
                        {if $optionModulesHook.displayLeftColumn.modules && $optionModulesHook.displayLeftColumn.modules|@count > 0}
                            {include file="{$templatePath}layout_builder/modules.tpl" id_hook={$optionModulesHook.displayLeftColumn.id_hook} id_option={$id_option} modules=$optionModulesHook.displayLeftColumn.modules dataname="displayLeftColumnModules"}
                        {/if}
                    </div>
                </div>
            {/if}
            <div id="center_column" class="col-sm-{$homeWidth|intval}{if !$displayLeft} hide_left{/if}{if !$displayRight} hide_right{/if}">
                <div class="panel">
                    <h3>{l s=' displayHomeTopContent' mod='oviclayoutbuilder'}
                        <span class="panel-heading-action">
                    		<a class="list-toolbar-btn newmodulehook" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayModulesHook&id_hook={$optionModulesHook.displayHomeTopContent.id_hook}&id_option={$id_option}">
                    			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new module" data-html="true">
                    				<i class="process-icon-new "></i> {l s=' Add new module' mod='oviclayoutbuilder'}
                    			</span>
                    		</a>
                    	</span>
                    </h3>
                    <div id="displayHomeTopContent" class="hookContainer ui-sortable">
                        {if $optionModulesHook.displayHomeTopContent.modules && $optionModulesHook.displayHomeTopContent.modules|@count > 0}
                            {include file="{$templatePath}layout_builder/modules.tpl" id_hook={$optionModulesHook.displayHomeTopContent.id_hook} id_option={$id_option} modules=$optionModulesHook.displayHomeTopContent.modules dataname="displayHomeTopContentModules"}
                        {/if}
                    </div>
                </div>
                <div class="panel">
                    <h3>{l s=' displayHomeTab' mod='oviclayoutbuilder'}
                        <span class="panel-heading-action">
                		<a class="list-toolbar-btn newmodulehook" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayModulesHook&id_hook={$optionModulesHook.displayHomeTab.id_hook}&id_option={$id_option}">
                			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new module" data-html="true">
                				<i class="process-icon-new "></i> {l s=' Add new module' mod='oviclayoutbuilder'}
                			</span>
                		</a>
                	</span>
                    </h3>
                    <div id="displayHomeTab" class="hookTabContainer ui-sortable">
                        {if $optionModulesHook.displayHomeTab.modules && $optionModulesHook.displayHomeTab.modules|@count > 0}
                            {include file="{$templatePath}layout_builder/modules.tpl" id_hook={$optionModulesHook.displayHomeTab.id_hook} id_option={$id_option} modules=$optionModulesHook.displayHomeTab.modules dataname="displayHomeTabModules"}
                        {/if}
                    </div>
                </div>
                <div class="panel">
                    <h3>{l s=' displayHomeTabContent' mod='oviclayoutbuilder'}
                        <span class="panel-heading-action">
                    		<a class="list-toolbar-btn newmodulehook" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayModulesHook&id_hook={$optionModulesHook.displayHomeTabContent.id_hook}&id_option={$id_option}">
                    			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new module" data-html="true">
                    				<i class="process-icon-new "></i> {l s=' Add new module' mod='oviclayoutbuilder'}
                    			</span>
                    		</a>
                    	</span>
                    </h3>
                    <div id="displayHomeTabContent" class="hookTabContentContainer ui-sortable">
                        {if $optionModulesHook.displayHomeTabContent.modules && $optionModulesHook.displayHomeTabContent.modules|@count > 0}
                            {include file="{$templatePath}layout_builder/modules.tpl" id_hook={$optionModulesHook.displayHomeTabContent.id_hook} id_option={$id_option} modules=$optionModulesHook.displayHomeTabContent.modules dataname="displayHomeTabContentModules"}
                        {/if}
                    </div>
                </div>
                <div class="panel">
                    <h3>{l s=' displayHome' mod='oviclayoutbuilder'}
                        <span class="panel-heading-action">
                    		<a class="list-toolbar-btn newmodulehook" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayModulesHook&id_hook={$optionModulesHook.displayHome.id_hook}&id_option={$id_option}">
                    			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new module" data-html="true">
                    				<i class="process-icon-new "></i> {l s=' Add new module' mod='oviclayoutbuilder'}
                    			</span>
                    		</a>
                    	</span>
                    </h3>
                    <div id="displayHome" class="hookContainer ui-sortable">
                        {if $optionModulesHook.displayHome.modules && $optionModulesHook.displayHome.modules|@count > 0}
                            {include file="{$templatePath}layout_builder/modules.tpl" id_hook={$optionModulesHook.displayHome.id_hook} id_option={$id_option} modules=$optionModulesHook.displayHome.modules dataname="displayHomeModules"}
                        {/if}
                    </div>
                </div>
                <div class="panel">
                    <h3>{l s=' displayHomeBottomContent' mod='oviclayoutbuilder'}
                        <span class="panel-heading-action">
                    		<a class="list-toolbar-btn newmodulehook" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayModulesHook&id_hook={$optionModulesHook.displayHomeBottomContent.id_hook}&id_option={$id_option}">
                    			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new module" data-html="true">
                    				<i class="process-icon-new "></i> {l s=' Add new module' mod='oviclayoutbuilder'}
                    			</span>
                    		</a>
                    	</span>
                    </h3>
                    <div id="displayHomeBottomContent" class="hookContainer ui-sortable">
                        {if $optionModulesHook.displayHomeBottomContent.modules && $optionModulesHook.displayHomeBottomContent.modules|@count > 0}
                            {include file="{$templatePath}layout_builder/modules.tpl" id_hook={$optionModulesHook.displayHomeBottomContent.id_hook} id_option={$id_option} modules=$optionModulesHook.displayHomeBottomContent.modules dataname="displayHomeBottomContentModules"}
                        {/if}
                    </div>
                </div>
            </div>
            {if $displayRight}
            <div class="panel col-sm-3">
                <h3>{l s=' displayRightColumn' mod='oviclayoutbuilder'}
                    <span class="panel-heading-action">
                		<a class="list-toolbar-btn newmodulehook" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayModulesHook&id_hook={$optionModulesHook.displayRightColumn.id_hook}&id_option={$id_option}">
                			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new module" data-html="true">
                				<i class="process-icon-new "></i> {l s=' Add new module' mod='oviclayoutbuilder'}
                			</span>
                		</a>
                	</span>
                </h3>
                <div id="displayRightColumn" class="hookContainer ui-sortable">
                    {if $optionModulesHook.displayRightColumn.modules && $optionModulesHook.displayRightColumn.modules|@count > 0}
                        {include file="{$templatePath}layout_builder/modules.tpl" id_hook={$optionModulesHook.displayRightColumn.id_hook} id_option={$id_option} modules=$optionModulesHook.displayRightColumn.modules dataname="displayRightColumnModules"}
                    {/if}
                </div>
            </div>
            {/if}
        </div>
        <div class="panel">
            <h3>{l s=' displayHomeBottomColumn' mod='oviclayoutbuilder'}
                <span class="panel-heading-action">
            		<a class="list-toolbar-btn newmodulehook" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayModulesHook&id_hook={$optionModulesHook.displayHomeBottomColumn.id_hook}&id_option={$id_option}">
            			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new module" data-html="true">
            				<i class="process-icon-new "></i> {l s=' Add new module' mod='oviclayoutbuilder'}
            			</span>
            		</a>
            	</span>
            </h3>
            <div id="displayHomeBottomColumn" class="hookContainer ui-sortable">
                {if $optionModulesHook.displayHomeBottomColumn.modules && $optionModulesHook.displayHomeBottomColumn.modules|@count > 0}
                    {include file="{$templatePath}layout_builder/modules.tpl" id_hook={$optionModulesHook.displayHomeBottomColumn.id_hook} id_option={$id_option} modules=$optionModulesHook.displayHomeBottomColumn.modules dataname="displayHomeBottomColumnModules"}
                {/if}
            </div>
        </div>
        <hr class="homeline"/>
        <div class="panel">
            <h3>{l s=' displayBottomColumn' mod='oviclayoutbuilder'}
                <span class="panel-heading-action">
            		<a class="list-toolbar-btn newmodulehook" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayModulesHook&id_hook={$optionModulesHook.displayBottomColumn.id_hook}&id_option={$id_option}">
            			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new module" data-html="true">
            				<i class="process-icon-new "></i> {l s=' Add new module' mod='oviclayoutbuilder'}
            			</span>
            		</a>
            	</span>
            </h3>
            <div id="displayBottomColumn" class="hookContainer ui-sortable">
                {if $optionModulesHook.displayBottomColumn.modules && $optionModulesHook.displayBottomColumn.modules|@count > 0}
                    {include file="{$templatePath}layout_builder/modules.tpl" id_hook={$optionModulesHook.displayBottomColumn.id_hook} id_option={$id_option} modules=$optionModulesHook.displayBottomColumn.modules dataname="displayBottomColumnModules"}
                {/if}
            </div>
        </div>
        <div class="panel">
            <h3>{l s=' displayFooter' mod='oviclayoutbuilder'}
                <span class="panel-heading-action">
            		<a class="list-toolbar-btn newmodulehook" href="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&action=displayModulesHook&id_hook={$optionModulesHook.displayFooter.id_hook}&id_option={$id_option}">
            			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new module" data-html="true">
            				<i class="process-icon-new "></i> {l s=' Add new module' mod='oviclayoutbuilder'}
            			</span>
            		</a>
            	</span>
            </h3>
            <div id="displayFooter" class="hookContainer ui-sortable">
                {if $optionModulesHook.displayFooter.modules && $optionModulesHook.displayFooter.modules|@count > 0}
                    {include file="{$templatePath}layout_builder/modules.tpl" id_hook={$optionModulesHook.displayFooter.id_hook} id_option={$id_option} modules=$optionModulesHook.displayFooter.modules dataname="displayFooterModules"}
                {/if}
            </div>
        </div>
        <div class="panel-footer">
			<a href="{$postUrl|escape:'htmlall':'UTF-8'}" class="btn btn-default" onclick="window.history.back();">
				<i class="process-icon-back"></i> Back
			</a>
		</div>
    </div>
{/if}
</div>