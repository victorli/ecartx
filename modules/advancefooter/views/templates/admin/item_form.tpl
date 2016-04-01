<div class="panel">
	<div class="panel-heading">
		{l s=' Add new Item' mod='advancefooter'}
    </div>
    <form method="post" action="{$postAction|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data" class="item-form defaultForm  form-horizontal">
        <input type="hidden" name="tab_index" value = "idTab2" />
        <input type="hidden" name="block_id" value="{$item->id_block}"/>
        {if isset($item->id)&&$item->id > 0}
            <input type="hidden" name="id_item" value="{$item->id}"/>
        {/if}
		<div class="well">
            <div class="title item-field form-group">
    			<label id="title_lb" class="control-label col-lg-3 ">Title</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        {foreach from=$langguages.all item=lang}
                            <div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
    				            <div class="col-lg-9">
    				                <input class="form-control" type="text" id="itemtitle_{$lang.id_lang}" name="itemtitle_{$lang.id_lang}" value="{if isset($item->title[$lang.id_lang])}{$item->title[$lang.id_lang]}{/if}"/>
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
           <div class="item-field form-group ">
                <label for="active" class="control-label col-lg-3">Display title</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        <div class="col-lg-9">
                            <span class="switch prestashop-switch fixed-width-lg">
                                <input type="radio" name="title_show" id="active_on" {if isset($item->display_title)&&$item->display_title == 1 }checked="checked"{/if} value="1"/>
                                <label for="active_on">Yes</label>
                                <input type="radio" name="title_show" id="active_off" {if isset($item->display_title)&&$item->display_title == 0 || !isset($item->display_title)}checked="checked"{/if} value="0" />
                                <label for="active_off">No</label>
                                <a class="slide-button btn"></a>
                            </span>
                        </div>
                        <div class="col-lg-2">
						</div>
                    </div>
                </div>
            </div>
            <div class="item-field form-group">
				<label class="control-label col-lg-3">Type</label>
				<div class="col-lg-9">
                    <div class="form-group">
			            <div class="col-lg-9">
			                <select class="form-control fixed-width-lg" name="item_type" id="item_type_selected" >
        						<option {if isset($item->itemtype) && $item->itemtype =='link'}selected="selected"{/if} value="link">Link</option>
        						<option {if isset($item->itemtype) && $item->itemtype =='html'}selected="selected"{/if} value="html">Custom HTML</option>
        						<option {if isset($item->itemtype) && $item->itemtype =='module'}selected="selected"{/if} value="module">Module</option>
        					</select>
			            </div>
						<div class="col-lg-2">
						</div>
                     </div>
				</div>
			</div>
            <div id="link_Container" class="item_type"><!-- link container -->
                <div class="item-field form-group">
    				<label class="control-label col-lg-3">Target</label>
    				<div class="col-lg-9">
                        <div class="form-group">
    			            <div class="col-lg-9">
    			                <select class="form-control fixed-width-lg" name="target" >
            						<option {if isset($item->target) && $item->target =='_self'}selected="selected"{/if} value="_self">Parent Window with Browser Navigation</option>
            						<option {if isset($item->target) && $item->target =='_blank'}selected="selected"{/if} value="_blank">New Window with Browser Navigation</option>
            						<option {if isset($item->target) && $item->target =='_newwithout'}selected="selected"{/if} value="_newwithout">New Window without Browser Navigation</option>
            					</select>
    			            </div>
    						<div class="col-lg-2">
    						</div>
                         </div>
    				</div>
			     </div>
                 <div class="item-field form-group">
    				<label class="control-label col-lg-3">Link type</label>
    				<div class="col-lg-9">
                        <div class="form-group">
    			            <div class="col-lg-9">
                                <select class="form-control fixed-width-lg" id ="linktype" name="linktype">
            						<option {if isset($item->content_key) && $item->content_key =='category'}selected="selected"{/if} value="category">Category</option>
            						<option {if isset($item->content_key) && $item->content_key =='cms'}selected="selected"{/if} value="cms">Cms</option>
            						<option {if isset($item->content_key) && $item->content_key =='page'}selected="selected"{/if} value="page">Page</option>
                                    <option {if isset($item->content_key) && $item->content_key =='manufacturer'}selected="selected"{/if} value="manufacturer">Manufacturer</option>
            						<option {if isset($item->content_key) && $item->content_key =='supplier'}selected="selected"{/if} value="supplier">Supplier</option>
            						<option {if isset($item->content_key) && $item->content_key =='other'}selected="selected"{/if} value="other">Other</option>
            					</select>
    			            </div>
    						<div class="col-lg-2">
    						</div>
                         </div>
    				</div>
			     </div>
                 <div id="link_category" class="item-field form-group link_detail">
    				<label class="control-label col-lg-3">Category</label>
    				<div class="col-lg-9">
                        <div class="form-group">
    			            <div class="col-lg-9">
                                <select class="form-control fixed-width-lg" id ="id_category" name="id_category">
            						{$categoryOption}
            					</select>
    			            </div>
    						<div class="col-lg-2">
    						</div>
                         </div>
    				</div>
			     </div>
                 <div id="link_cms" class="item-field form-group link_detail">
    				<label class="control-label col-lg-3">CMS</label>
    				<div class="col-lg-9">
                        <div class="form-group">
    			            <div class="col-lg-9">
                                <select class="form-control fixed-width-lg" id ="id_cms" name="id_cms">
            						{$cmsOption}
            					</select>
    			            </div>
    						<div class="col-lg-2">
    						</div>
                         </div>
    				</div>
			     </div>
                 <div id="link_supplier" class="item-field form-group link_detail">
    				<label class="control-label col-lg-3">Supplier</label>
    				<div class="col-lg-9">
                        <div class="form-group">
    			            <div class="col-lg-9">
                                <select class="form-control fixed-width-lg" id ="id_supplier" name="id_supplier">
            						{$supplierOption}
            					</select>
    			            </div>
    						<div class="col-lg-2">
    						</div>
                         </div>
    				</div>
			     </div>
                 <div id="link_manufacturer" class="item-field form-group link_detail">
    				<label class="control-label col-lg-3">Manufacturer</label>
    				<div class="col-lg-9">
                        <div class="form-group">
    			            <div class="col-lg-9">
                                <select class="form-control fixed-width-lg" id ="id_manufacturer" name="id_manufacturer">
            						{$manufacturerOption}
            					</select>
    			            </div>
    						<div class="col-lg-2">
    						</div>
                         </div>
    				</div>
			     </div>
                 <div id="link_page" class="item-field form-group link_detail">
    				<label class="control-label col-lg-3">Page</label>
    				<div class="col-lg-9">
                        <div class="form-group">
    			            <div class="col-lg-9">
                                <select class="form-control fixed-width-lg" id ="id_page" name="id_page">
            						{$pageOption}
            					</select>
    			            </div>
    						<div class="col-lg-2">
    						</div>
                         </div>
    				</div>
			     </div>
                 <div id="link_other" class="item-field form-group link_detail">
    				<label class="control-label col-lg-3">Page</label>
    				<div class="col-lg-9">
                        <div class="form-group">
    			            <div class="col-lg-9">
                                <input type="text" name="id_other" value=""/>
    			            </div>
    						<div class="col-lg-2">
    						</div>
                         </div>
    				</div>
			     </div>
            </div><!-- End link container -->
            <div id="html_Container" class="item_type">
                <div class="item-field form-group">
    				<label class="control-label col-lg-3">Link</label>
    				<div class="col-lg-9">
                        <div class="form-group">
                        {foreach from=$langguages.all item=lang}
                            <div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
    				            <div class="col-lg-9">
    				                <textarea class="rte" id="htmlbody_{$lang.id_lang}" name="htmlbody_{$lang.id_lang}" style="margin-bottom:10px; height:300px;" >{if isset($item->text[$lang.id_lang])}{$item->text[$lang.id_lang]}{/if}</textarea>
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
			</div>
            <div id="module_Container" class="item_type">
                <div class="item-field form-group">
                    <input type="hidden" id="ajaxurl" value="{$ajaxPath}"/>
    				<label class="control-label col-lg-3">Module</label>
    				<div class="col-lg-9">
                        <div class="form-group">
    			            <div class="col-lg-9">
    			                <select class="form-control fixed-width-lg" name="module" id="module_select" >
            						{$moduleOption}
            					</select>
    			            </div>
    						<div class="col-lg-2">
    						</div>
                         </div>
    				</div>
                </div>
                <div class="item-field form-group">
    				<label class="control-label col-lg-3">Hook</label>
    				<div class="col-lg-9">
                        <div class="form-group">
    			            <div class="col-lg-9">
    			                <select class="form-control fixed-width-lg" name="hook" id="hook_select" >
            						{$hookOption}
            					</select>
    			            </div>
    						<div class="col-lg-2">
    						</div>
                         </div>
    				</div>
                </div>
			</div>
			<div class="form-group">
				<div class="col-lg-7 col-lg-offset-3">
					<a href="{$postAction|escape:'htmlall':'UTF-8'}" class="btn btn-default button-new-item-cancel"><i class="icon-remove"></i> Cancel</a>
					<button type="submit" name="submitSaveItem" class="button-new-item-save btn btn-default" onclick="this.form.submit();"><i class="icon-save"></i> Save</button>
				</div>
			</div>
		</div>
	</form>
</div>