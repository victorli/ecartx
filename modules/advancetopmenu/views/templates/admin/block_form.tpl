<div class="panel">
	<div class="panel-heading">
		{$form} {l s=' setting' mod='advancetopmenu'}
    </div>
    <form method="post" action="{$postAction|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data" class="item-form defaultForm  form-horizontal">
        <input type="hidden" name="id_sub" value="{if isset($block->id_sub)}{$block->id_sub}{/if}"/>
        <input type="hidden" name="id_block" value="{if isset($block->id_block)}{$block->id_block}{/if}"/>
		<div class="well">
           <div class="item-field form-group">
				<label class="control-label col-lg-3 ">Block width</label>
				<div class="col-lg-7">
                    <select class="form-control fixed-width-lg" name="block_widh" id="block_widh" >
                    {for $foo=3 to 9}
                        <option value="{$foo}" {if isset($block->width) && $block->width == $foo}selected="selected"{/if}>col-sm-{$foo}</option>
                    {/for}
                    <option value="12" {if isset($block->width) && $block->width == 12}selected="selected"{/if}>col-sm-12</option>
                    </select>
				</div>
			</div>
            <div class="item-field form-group">
				<label class="control-label col-lg-3 ">class</label>
				<div class="col-lg-7">
					<input type="text" name="block_class" value="{if isset($block->class)}{$block->class}{/if}"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-7 col-lg-offset-3">
					<input type="hidden" name="updateItem" value=""/>
					<a href="{$postAction|escape:'htmlall':'UTF-8'}" class="btn btn-default button-new-item-cancel"><i class="icon-remove"></i> Cancel</a>
					<button type="submit" name="submitnewblock" class="button-new-item-save btn btn-default pull-right" onclick="this.form.submit();"><i class="icon-save"></i> Save</button>
				</div>
			</div>
		</div>
	</form>
</div>