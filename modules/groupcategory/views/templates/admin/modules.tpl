<div class="col-md-3 col-lg-2">
    <div class="list-group" id="list-group">
        <a href="#list-style" class="list-group-item active">{l s='List style' mod='groupcategory'}</a>
        <a href="#module-groups" class="list-group-item">{l s='List group' mod='groupcategory'}</a>        
    </div>
</div>
<div class="col-md-9 col-lg-10">
    <div class="tab-content">
        <div id="list-style" class="tab-pane fade in active">            
            {include file="$style_tpl"}            
        </div>
        <div id="module-groups" class="tab-pane fade">
            {include file="$group_tpl"}            
        </div>        
    </div>
</div>
{addJsDefL name=lab_delete}{l s='Delete' mod='groupcategory' js=1}{/addJsDefL}
{addJsDefL name=lab_disable}{l s='Disable' mod='groupcategory' js=1}{/addJsDefL}
{addJsDefL name=lab_enable}{l s='Enable' mod='groupcategory' js=1}{/addJsDefL}
<script type="text/javascript">
	$(document).on('focusin', function(e) {
        if ($(e.target).closest(".mce-window").length) {
    		e.stopImmediatePropagation();
    	}
    });
    
    var secure_key			= "{$secure_key}";
    var baseModuleUrl 		= "{$baseModuleUrl}";
    var currentUrl 			= "{$currentUrl}";
    var currentLanguage 	= "{$langId}";       
    var groupId 			= '0';    
    var iso 				= '{$iso}';    
    var ad 					= "{$ad}";    
    var formNewRow 			= '';
    var groupFormNew_config 		= '';    
    var groupFormNew_description 	= '';
    var groupFormNew_products 		= '';
    var itemFormNew	='';
    var catGroupId = '0';
</script>
{*}
<script type="text/javascript">
	var secure_key = "{$secure_key}";
    var baseModuleUrl = "{$baseModuleUrl}";
    var groupFormNew = '';
    var itemFormNew = '';
    var catGroupId = '0';
    var reload = false;
    var loadItems = false;    
</script>
{*}
