<div class="panel">
	<div class="panel-heading">
		{$form} {l s='Menu setting' mod='advancetopmenu'}
    </div>
    <form method="post" action="{$postAction|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data" class="item-form defaultForm  form-horizontal">
        <input type="hidden" name="id_sub" value="{if isset($submenu->id_sub)}{$submenu->id_sub}{/if}"/>
		<div class="well">
            <div class="item-field form-group">
				<label class="control-label col-lg-3">Parent menu</label>
				<div class="col-lg-7">
					<select class="form-control fixed-width-lg" name="id_parent" id="id_parent" >
                        {if count($main_items)>0}
                            {foreach $main_items item=menu_item name=main_items}
        						<option {if isset($submenu->id_parent) && $submenu->id_parent == $menu_item.id_item}selected="selected"{/if} value="{$menu_item.id_item}">{$menu_item.title}</option>
                            {/foreach}
                        {/if}
					</select>
				</div>
			</div>
           <div class="item-field form-group">
				<label class="control-label col-lg-3 ">Width</label>
				<div class="col-lg-7">
					<input class="form-control" type="text" name="subwidth" value="{if isset($submenu->width)}{$submenu->width}{/if}"/>
                    <p class="help-block newline">{l s='(If empty, the global styles are used)' mod='advancetopmenu'}</p>
				</div>
                <p class="help-block">{l s='px' mod='advancetopmenu'}</p>

			</div>

            <div class="item-field form-group">
				<label class="control-label col-lg-3 ">class</label>
				<div class="col-lg-7">
					<input type="text" name="sub_class" value="{if isset($submenu->class)}{$submenu->class}{/if}"/>
				</div>
			</div>
            <div class="item-field form-group ">
                <label for="active" class="control-label col-lg-3">Active</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        <div class="col-lg-9">
                            <span class="switch prestashop-switch fixed-width-lg">
                                <input type="radio" name="active" id="active_on" {if isset($submenu->active)&&$submenu->active == 1 }checked="checked"{/if} value="1"/>
                                <label for="active_on">Yes</label>
                                <input type="radio" name="active" id="active_off" {if isset($submenu->active)&&$submenu->active == 0 || !isset($submenu->active)}checked="checked"{/if} value="0" />
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
				<div class="col-lg-7 col-lg-offset-3">
					<input type="hidden" name="updateItem" value=""/>
					<a href="{$postAction|escape:'htmlall':'UTF-8'}" class="btn btn-default button-new-item-cancel"><i class="icon-remove"></i> Cancel</a>
					<button type="submit" name="submitnewsub" class="button-new-item-save btn btn-default pull-right" onclick="this.form.submit();"><i class="icon-save"></i> Save</button>
				</div>
			</div>
		</div>
	</form>
</div>