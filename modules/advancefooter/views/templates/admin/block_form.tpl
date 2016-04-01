<div class="panel">
	<div class="panel-heading">
        {if isset($block_obj->id)}
            {l s=' Edit block' mod='advancefooter'}
        {else}
            {l s=' Add new block' mod='advancefooter'}
        {/if}
    </div>
    <form method="post" action="{$postAction|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data" class="item-form defaultForm  form-horizontal">
        <input type="hidden" name="block_row" value="{$block_obj->id_row}"/>
        {if isset($block_obj->id)}<input type="hidden" name="id_block" value="{$block_obj->id}"/>{/if}
		<div class="well">
            <div class="title item-field form-group">
    			<label id="title_lb" class="control-label col-lg-3 ">Title</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        {foreach from=$langguages.all item=lang}
                            <div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
    				            <div class="col-lg-9">
    				                <input class="form-control" type="text" id="blocktitle_{$lang.id_lang}" name="blocktitle_{$lang.id_lang}" value="{if isset($block_obj->title[$lang.id_lang])}{$block_obj->title[$lang.id_lang]}{/if}"/>
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
                                <input type="radio" name="title_show" id="active_on" {if isset($block_obj->display_title)&&$block_obj->display_title == 1 }checked="checked"{/if} value="1"/>
                                <label for="active_on">Yes</label>
                                <input type="radio" name="title_show" id="active_off" {if isset($block_obj->display_title)&&$block_obj->display_title == 0 || !isset($block_obj->display_title)}checked="checked"{/if} value="0" />
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
        		<label class="control-label col-lg-3 ">Block width</label>
        		<div class="col-lg-7">
                    <select class="form-control fixed-width-lg" name="block_width" id="block_width" >
                    {for $i=3 to 9}
                        <option value="{$i}" {if isset($block_obj->width) && $block_obj->width == $i}selected="selected"{/if}>col-sm-{$i}</option>
                    {/for}
                    <option value="12" {if isset($block_obj->width) && $block_obj->width == 12}selected="selected"{/if}>col-sm-12</option>
                    </select>
        		</div>
        	</div>
            <div class="item-field form-group">
				<label class="control-label col-lg-3 ">class</label>
				<div class="col-lg-7">
					<input type="text" name="block_class" value="{if isset($block_obj->bclass)}{$block_obj->bclass}{/if}"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-7 col-lg-offset-3">
					<a href="{$postAction|escape:'htmlall':'UTF-8'}" class="btn btn-default button-new-item-cancel"><i class="icon-remove"></i> Cancel</a>
					<button type="submit" name="submitSaveBlock" class="button-new-item-save btn btn-default" onclick="this.form.submit();"><i class="icon-save"></i> Save</button>
				</div>
			</div>
		</div>
	</form>
</div>