<div class="col-md-3 col-lg-2">
    <div class="list-group" id="list-group">
        <a href="#list-module" class="list-group-item active">{l s='Module list' mod='mengamenus'}</a>
        {$listLeftModule}        
    </div>
</div>
<div class="col-md-9 col-lg-10">
    <div class="tab-content">
        <div id="list-module" class="tab-pane fade in active">            
            {include file="$list_module_tpl"}            
        </div>
        <div id="item-module" class="tab-pane fade">
            {include file="$list_menu_tpl"}            
        </div>        
    </div>
</div>
<script type="text/javascript">
	$(document).on('focusin', function(e) {
        if ($(e.target).closest(".mce-window").length) {
    		e.stopImmediatePropagation();
    	}
    });
    var secure_key = "{$secure_key}";
    var baseModuleUrl = "{$baseModuleUrl}";
    var verticalModuleId = '0';
    var verticalMenuId = '0';
    var verticalGroupId = '0';
    var verticalGroupType = '';
    var iso = 'en';    
    var ad = "{$ad}";
    var moduleFormNew = '';
    var menuFormNew = '';
    var menuGroupFormNew = '';
    var menuItemFormNew = '';
</script>