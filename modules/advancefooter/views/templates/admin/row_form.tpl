<div class="panel">
	<div class="panel-heading">
		{l s='Row setting' mod='advancefooter'}
    </div>
    <form method="post" action="{$postAction|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data" class="item-form defaultForm  form-horizontal">
        <input type="hidden" name="id_row" value="{if isset($footer_row->id_row)}{$footer_row->id_row}{/if}"/>
		<div class="well">
           <div class="item-field form-group">
				<label class="control-label col-lg-3 ">class</label>
				<div class="col-lg-7">
					<input type="text" name="row_class" value="{if isset($footer_row->rclass)}{$footer_row->rclass}{/if}"/>
				</div>
			</div>
            <div class="item-field form-group ">
                <label for="active" class="control-label col-lg-3">Active</label>
                <div class="col-lg-9">
                    <div class="form-group">
                        <div class="col-lg-9">
                            <span class="switch prestashop-switch fixed-width-lg">
                                <input type="radio" name="active" id="active_on" {if isset($footer_row->active)&&$footer_row->active == 1 }checked="checked"{/if} value="1"/>
                                <label for="active_on">Yes</label>
                                <input type="radio" name="active" id="active_off" {if isset($footer_row->active)&&$footer_row->active == 0 || !isset($footer_row->active)}checked="checked"{/if} value="0" />
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
					<button type="submit" name="submitnewrow" class="button-new-item-save btn btn-default pull-right" onclick="this.form.submit();"><i class="icon-save"></i> Save</button>
				</div>
			</div>
		</div>
	</form>
</div>