<div class="panel">
    <h3><i class="icon-cog"></i>{if isset($tab) && isset($tab->id_tab)}{l s='Edit tab' mod='productextratabs'}{else}{l s='Add new tab' mod='productextratabs'}{/if}
	<span class="panel-heading-action">
		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&itemsubmit&action=add">
			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new tab" data-html="true">
				<i class="process-icon-new "></i>
			</span>
		</a>
	</span>
    </h3>
    <div class="main-container">
        <form method="post" action="{$postAction|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data" class="item-form defaultForm  form-horizontal">
            <input type="hidden" name="ajaxurl" id="ajaxurl" value="{$ajaxurl}"/>
            {if isset($tab->id_tab)}
                <input type="hidden" name="id_tab" value="{$tab->id_tab}"/>
            {/if}
            <div class="title item-field form-group">
				<label id="title_lb" class="control-label col-lg-3 ">Title</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        {foreach from=$langguages.all item=lang}
                            <div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
    				            <div class="col-lg-9">
    				                <input class="form-control" type="text" id="item_title" name="title_{$lang.id_lang}" value="{if isset($tab->title[$lang.id_lang])}{$tab->title[$lang.id_lang]}{/if}"/>
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
				<label class="control-label col-lg-3">Category</label>
				<div class="col-lg-9">
                    <div class="form-group">
			            <div class="col-lg-9">
			                <select class="form-control fixed-width-lg" name="category" id="category_select" >
                                <option value="all">All category</option>
        						{$category_option}
        					</select>
			            </div>
						<div class="col-lg-2">
						</div>	
                     </div>                     					
				</div>
			</div>
            <div class="item-field form-group">
				<label class="control-label col-lg-3">Product</label>
				<div class="col-lg-9">
                    <div class="form-group">
			            <div class="col-lg-9">
			                <select class="form-control fixed-width-lg" name="product" id="product_select" >
                                <option value="all">All Product</option>
        						{$product_option}
        					</select>
			            </div>
						<div class="col-lg-2">
						</div>	
                     </div>                     					
				</div>
			</div>
            <div class="item-field form-group ">
                <label for="active" class="control-label col-lg-3">Content</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        {foreach from=$langguages.all item=lang}
							<div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
								<div class="col-lg-9">
									<textarea name="tabcontent_{$lang.id_lang}" class="rte autoload_rte" value="{if isset($tab->content[$lang.id_lang])}{$tab->content[$lang.id_lang]|escape:'html':'UTF-8'}{/if}" >{if isset($tab->content[$lang.id_lang])}{$tab->content[$lang.id_lang]|escape:'html':'UTF-8'}{/if}</textarea>	
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
                <label for="active" class="control-label col-lg-3">Active</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        <div class="col-lg-9">
                            <span class="switch prestashop-switch fixed-width-lg">
                                <input type="radio" name="active" id="active_on" {if isset($tab->active)&&$tab->active == 1 }checked="checked"{/if} value="1"/>
                                <label for="active_on">Yes</label>
                                <input type="radio" name="active" id="active_off" {if isset($tab->active)&&$tab->active == 0 || !isset($tab->active)}checked="checked"{/if} value="0" />
                                <label for="active_off">No</label>
                                <a class="slide-button btn"></a>
                            </span>
                        </div>
                        <div class="col-lg-2">
						</div>	
                    </div>
                </div>
            </div>
            <div class="form-group">
				<div class="col-lg-9 col-lg-offset-3">
					<input type="hidden" name="updateItem" value=""/>
					<a href="{$postAction|escape:'htmlall':'UTF-8'}" class="btn btn-default button-new-item-cancel"><i class="icon-remove"></i> Cancel</a>
					<button type="submit" name="submitSaveTab" class="button-new-item-save btn btn-default" onclick="this.form.submit();"><i class="icon-save"></i> Save</button>
				</div>
			</div>
        </form>
    </div>
</div>