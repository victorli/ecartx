<div id="newmodule_popup" class="bootstrap popup_form">
        <div class="item-field form-group">
    		<label class="control-label">Module</label>
            <div class="form-group">
                <select class="form-control" name="moduleHook" id="moduleHook" >
                    <option>--</option>
					{$moduleOption}
				</select>
             </div>
    	</div>
        <div class="item-field form-group">
    		<label class="control-label">Hook execute</label>
    		<div class="form-group">
                <select class="form-control" name="hookexec" id="hookexec" >
                    <option>--</option>
				</select>
    		</div>
    	</div>
        <div class="form-group">
    		<div class="center">
                <input type="hidden" name="hookname" id="hookname" value="{$hookname}"/>
                <input type="hidden" name="popupAjaxUrl" id="popupAjaxUrl" value="{$postUrl|escape:'htmlall':'UTF-8'}&ajax=1&hookcolumn={$hookcolumn}&pagemeta={$pagemeta}"/>
    			<button type="button" name="submitNewModuleHook" class="button-new-item-save btn btn-default" onclick="submitSidebarModule($(this));"><i class="icon-save"></i> Save</button>
    		</div>
    	</div>
</div>