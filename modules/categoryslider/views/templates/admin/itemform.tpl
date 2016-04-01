<div class="panel">
    <h3><i class="icon-cog"></i>{if isset($slide) && isset($slide->id)}{l s='Edit slide' mod='categoryslider'}{else}{l s='Add new slide' mod='categoryslider'}{/if}
    </h3>
    <div class="main-container">
        <form method="post" action="{$postAction|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data" class="item-form defaultForm  form-horizontal">
            {if isset($slide) && isset($slide->id)}
                <input type="hidden" id="id_slide" name="id_slide" value="{$slide->id}"/>
                <input type="hidden" name="position" value="{$slide->position}" id="position" />
            {else}
                <input type="hidden" name="position" value="{$newposition}" id="position" />
            {/if}
            {if isset($slide->id_category)}
                <input type="hidden" name="id_category" value="{$slide->id_category}"/>
            {elseif isset($id_category)}
                <input type="hidden" name="id_category" value="{$id_category}"/>
            {/if}
            <div class="title item-field form-group">
				<label for="image" class="control-label col-lg-3 ">Select a file</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        {foreach from=$langguages.all item=lang}
                            <div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
    				            <div class="col-lg-9">
                                    {if isset($slide->image[$lang.id_lang]) && $slide->image[$lang.id_lang]|count_characters > 0}
                                    <img class="img-thumbnail" src="{$imgpath}{$slide->image[$lang.id_lang]}" alt="" />
                                    <input type="hidden" name="image_old_{$lang.id_lang}" value="{$slide->image[$lang.id_lang]}" id="image_old_{$lang.id_lang}" />
                                    <input type="hidden" name="has_picture" value="1" />
                                    <br /><br />
                                    {/if}
                				    <input class="form-control" type="file" id="image_{$lang.id_lang}" name="image_{$lang.id_lang}" value="{if isset($slide->image[$lang.id_lang])}{$slide->image[$lang.id_lang]}{/if}"/>
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
            <div class="title item-field form-group">
				<label for="title_lb" class="control-label col-lg-3 ">Title</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        {foreach from=$langguages.all item=lang}
                            <div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
    				            <div class="col-lg-9">
    				                <input class="form-control" type="text" id="title_{$lang.id_lang}" name="title_{$lang.id_lang}" value="{if isset($slide->title[$lang.id_lang])}{$slide->title[$lang.id_lang]}{/if}"/>
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
            <div class="title item-field form-group">
				<label for="url" class="control-label col-lg-3 ">URL</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        {foreach from=$langguages.all item=lang}
                            <div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
    				            <div class="col-lg-9">
    				                <input class="form-control" type="text" id="url_{$lang.id_lang}" name="url_{$lang.id_lang}" value="{if isset($slide->url[$lang.id_lang])}{$slide->url[$lang.id_lang]}{/if}"/>
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
            <div class="title item-field form-group">
				<label for="legend" class="control-label col-lg-3 ">Legend</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        {foreach from=$langguages.all item=lang}
                            <div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
    				            <div class="col-lg-9">
    				                <input class="form-control" type="text" id="legend_{$lang.id_lang}" name="legend_{$lang.id_lang}" value="{if isset($slide->legend[$lang.id_lang])}{$slide->legend[$lang.id_lang]}{/if}"/>
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
                <label for="description" class="control-label col-lg-3">Description</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        {foreach from=$langguages.all item=lang}
							<div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
								<div class="col-lg-9">
									<textarea name="description_{$lang.id_lang}" class="rte autoload_rte" value="{if isset($slide->description[$lang.id_lang])}{$slide->description[$lang.id_lang]|escape:'html':'UTF-8'}{/if}" >{if isset($slide->description[$lang.id_lang])}{$slide->description[$lang.id_lang]|escape:'html':'UTF-8'}{/if}</textarea>
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
                <label for="active" class="control-label col-lg-3">Enable</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        <div class="col-lg-9">
                            <span class="switch prestashop-switch fixed-width-lg">
                                <input type="radio" name="active_slide" id="active_on" {if isset($slide->active)&&$slide->active == 1 }checked="checked"{/if} value="1"/>
                                <label for="active_on">Yes</label>
                                <input type="radio" name="active_slide" id="active_off" {if isset($slide->active)&&$slide->active == 0 || !isset($slide->active)}checked="checked"{/if} value="0" />
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
					<a href="{$postAction|escape:'htmlall':'UTF-8'}" class="btn btn-default button-new-item-cancel"><i class="icon-remove"></i> Cancel</a>
					<button type="submit" name="submitSlide" class="button-new-item-save btn btn-default" onclick="this.form.submit();"><i class="icon-save"></i> Save</button>
				</div>
			</div>
        </form>
    </div>
</div>