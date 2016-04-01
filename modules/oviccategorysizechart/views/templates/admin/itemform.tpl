<div class="panel">
    <h3><i class="icon-cog"></i>{if isset($sizechart) && isset($sizechart->id)}{l s='Edit sizechart' mod='oviccategorysizechart'}{else}{l s='Add new sizechart' mod='oviccategorysizechart'}{/if}
    </h3>
    <div class="main-container">
        <form method="post" action="{$postAction|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data" class="item-form defaultForm  form-horizontal">
            {if isset($sizechart) && isset($sizechart->id)}
                <input type="hidden" id="id_sizechart" name="id_sizechart" value="{$sizechart->id}"/>
                <input type="hidden" name="position" value="{$sizechart->position}" id="position" />
            {else}
                <input type="hidden" name="position" value="{$newposition}" id="position" />
            {/if}
            {if isset($sizechart->id_category)}
                <input type="hidden" name="id_category" value="{$sizechart->id_category}"/>
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
                                    {if isset($sizechart->image[$lang.id_lang]) && $sizechart->image[$lang.id_lang]|count_characters > 0}
                                    <img class="img-thumbnail" src="{$imgpath}{$sizechart->image[$lang.id_lang]}" alt="" />
                                    <input type="hidden" name="image_old_{$lang.id_lang}" value="{$sizechart->image[$lang.id_lang]}" id="image_old_{$lang.id_lang}" />
                                    <input type="hidden" name="has_picture" value="1" />
                                    <br /><br />
                                    {/if}
                				    <input class="form-control" type="file" id="image_{$lang.id_lang}" name="image_{$lang.id_lang}" value="{if isset($sizechart->image[$lang.id_lang])}{$sizechart->image[$lang.id_lang]}{/if}"/>
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
           {*}
            <div class="title item-field form-group">
				<label for="url" class="control-label col-lg-3 ">URL</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        {foreach from=$langguages.all item=lang}
                            <div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
    				            <div class="col-lg-9">
    				                <input class="form-control" type="text" id="url_{$lang.id_lang}" name="url_{$lang.id_lang}" value="{if isset($sizechart->url[$lang.id_lang])}{$sizechart->url[$lang.id_lang]}{/if}"/>
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
            {*}
            <div class="item-field form-group ">
                <label for="active" class="control-label col-lg-3">Enable</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        <div class="col-lg-9">
                            <span class="switch prestashop-switch fixed-width-lg">
                                <input type="radio" name="active_sizechart" id="active_on" {if isset($sizechart->active)&&$sizechart->active == 1 }checked="checked"{/if} value="1"/>
                                <label for="active_on">Yes</label>
                                <input type="radio" name="active_sizechart" id="active_off" {if isset($sizechart->active)&&$sizechart->active == 0 || !isset($sizechart->active)}checked="checked"{/if} value="0" />
                                <label for="active_off">No</label>
                                <a class="sizechart-button btn"></a>
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
					<button type="submit" name="submitSizechart" class="button-new-item-save btn btn-default" onclick="this.form.submit();"><i class="icon-save"></i> Save</button>
				</div>
			</div>
        </form>
    </div>
</div>