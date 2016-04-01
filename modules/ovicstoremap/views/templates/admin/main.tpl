<div class="panel">
    <h3><i class="icon-cogs"></i>{l s=' Setting' mod='blocktestimonial'}
    </h3>
    <div class="main-container">
        <form method="post" action="{$postAction|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data" class="item-form defaultForm  form-horizontal">
    		<div class="well">
                <div class="html item-field form-group">
                	<label class="control-label col-lg-3">Description</label>
                	<div class="col-lg-9">
                        <div class="form-group">
                        {foreach from=$langguages.all item=lang}
                            <div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
                	            <div class="col-lg-9">
                	                <textarea class="rte" name="STORE_CONTACT_INFO_{$lang.id_lang}" style="margin-bottom:10px; height:300px;" >{if isset($STORE_CONTACT_INFO[$lang.id_lang])}{$STORE_CONTACT_INFO[$lang.id_lang]}{/if}</textarea>
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
                <div class="panel-footer">
				    <button type="submit" value="1" id="module_form_submit_btn" name="submitGlobal" class="btn btn-default pull-right">
						<i class="process-icon-save"></i> Save
				    </button>
				</div>
    		</div>
    	</form>
    </div>
</div>