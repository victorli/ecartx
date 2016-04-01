<div id="changehook_popup" class="bootstrap popup_form">
        <div class="item-field form-group">
    		<label class="control-label">Hook execute</label>
            <div class="form-group">
                <select class="form-control" name="hookexec" id="hookexec" >
                    {$hookOptions}
				</select>
             </div>
    	</div>
        <div class="form-group">
    		<div class="center">
                <input type="hidden" name="moduleHook" id="moduleHook" value="{$id_module}"/>
                <input type="hidden" name="id_hookexec" id="id_hookexec" value="{$old_hook}"/>
                <input type="hidden" name="old_hook" id="old_hook" value="{$old_hook}"/>
                <input type="hidden" name="popupAjaxUrl" id="popupAjaxUrl" value="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&hookcolumn={$hookcolumn}&pagemeta={$pagemeta}"/>
    			<button type="button" name="submitChangeHook" class="button-new-item-save btn btn-default" onclick="submitSidebarChangeHook($(this));"><i class="icon-save"></i> Save</button>
    		</div>
    	</div>
</div>