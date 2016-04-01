<div class="panel">
    <div class="panel-heading">
    	{l s='List Menu' mod='verticalmegamenus'}&nbsp;<span id="header-module-name"></span>
		<span class="panel-heading-action">
            <a href="javascript:void(0)" onclick="showModal('modalMenu', '')" class="list-toolbar-btn link-add"><span data-placement="left" data-html="true" data-original-title="Add New" class="label-tooltip" data-toggle="tooltip" title=""><i class="process-icon-new"></i></span></a>
		</span>
    </div>
    <div class="panel-body" style="padding:0">
        <div class="table-responsive">
            <table class="table" id="listMenu">
    			<thead>
    				<tr class="nodrag nodrop">
                        <th width="30" class="center">{l s='ID' mod='verticalmegamenus'}</th>
                        <th width="40" class="center">{l s='Icon' mod='verticalmegamenus'}</th>                        
                        <th width="120">{l s='Title' mod='verticalmegamenus'}</th>
                        <th width="100" class="center">{l s='Width' mod='verticalmegamenus'}</th>
                        <th width="100" class="center">{l s='Type' mod='verticalmegamenus'}</th>
                        <th>{l s='Link' mod='verticalmegamenus'}</th>
                        <th width="100" class="center">{l s='Ordering' mod='verticalmegamenus'}</th>
                        <th width="50" class="center">{l s='Status' mod='verticalmegamenus'}</th>
                        <th class="center" width="50">#</th>
                    </tr>				
                </thead>
                <tbody></tbody>    
	       </table>            
        </div>        
    </div> 
</div>

<div class="panel" id="panel-list-group" style="display:none">
    <div class="panel-heading">
    	{l s='List Group in Menu' mod='verticalmegamenus'}&nbsp;<span id="header-menu-name"></span>
		<span class="panel-heading-action">
            <a href="javascript:void(0)" onclick="showModal('modalGroup', '')" class="list-toolbar-btn link-add"><span data-placement="left" data-html="true" data-original-title="Add New" class="label-tooltip" data-toggle="tooltip" title=""><i class="process-icon-new"></i></span></a>
		</span>
    </div>
    <div class="panel-body" style="padding:0">
        <div class="table-responsive">
            <table class="table" id="listGroup">
    			<thead>
    				<tr class="nodrag nodrop">
                        <th width="50" class="center">{l s='ID' mod='verticalmegamenus'}</th>
                        <th>{l s='Title' mod='verticalmegamenus'}</th>
                        <th class="center" width="100">{l s='Width' mod='verticalmegamenus'}</th>
                        <th class="center" width="100">{l s='Type' mod='verticalmegamenus'}</th>
                        <th class="center" width="200">{l s='Params' mod='verticalmegamenus'}</th>
                        <th width="100" class="center">{l s='Ordering' mod='verticalmegamenus'}</th>
                        <th width="50" class="center">{l s='Status' mod='verticalmegamenus'}</th>
                        <th class="center" width="50">#</th>
                    </tr>				
                </thead>
                <tbody></tbody>    
	       </table>            
        </div>        
    </div> 
</div>

<div class="panel" id="panel-list-menu-item" style="display:none">
    <div class="panel-heading">
    	{l s='Menu Item' mod='verticalmegamenus'}&nbsp;<span id="header-group-name"></span>
		<span class="panel-heading-action">
            <a href="javascript:void(0)" onclick="showModal('modalMenuItem', '')" class="list-toolbar-btn link-add"><span data-placement="left" data-html="true" data-original-title="Add New" class="label-tooltip" data-toggle="tooltip" title=""><i class="process-icon-new"></i></span></a>
		</span>
    </div>
    <div class="panel-body" style="padding:0">
        <div class="table-responsive">
            <table class="table" id="listMenuItem">
    			<thead>
    				<tr class="nodrag nodrop">
                        <th width="30" class="center">{l s='ID' mod='verticalmegamenus'}</th>                      
                        <th width="120">{l s='Title' mod='verticalmegamenus'}</th>
                        <th width="100" class="center">{l s='Type' mod='verticalmegamenus'}</th>
                        <th>{l s='Link' mod='verticalmegamenus'}</th>
                        <th width="50" class="center">{l s='Banner' mod='verticalmegamenus'}</th>
                        <th width="100" class="center">{l s='Ordering' mod='verticalmegamenus'}</th>
                        <th width="50" class="center">{l s='Status' mod='verticalmegamenus'}</th>
                        <th class="center" width="50">#</th>
                    </tr>				
                </thead>
                <tbody></tbody>    
	       </table>            
        </div>        
    </div> 
</div>

<!-- Modal -->
<div id="modalMenu" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <span class="modal-title"><i class="icon-cloud"></i>{l s=' Add or Edit Menu' mod='verticalmegamenus'}</span>
            </div>
            <div class="modal-body form-horizontal">
                
                <form id="frmMenu">{$menuForm}</form>
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-primary btnForgot" onclick="saveMenu()"><i class="icon-save"></i> {l s='Save' mod='verticalmegamenus'}</button>
            </div>
        </div>
    </div>
</div>





<div id="modalGroup" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <span class="modal-title"><i class="icon-cloud"></i>{l s=' Add or Edit Group' mod='groupcategory'}</span>
            </div>
            <div class="modal-body form-horizontal">                
                <form id="frmMenuGroup">{$menuGroupForm}</form>
                
                                               
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-primary btnForgot" onclick="saveGroup()"><i class="icon-save"></i> {l s='Save' mod='groupcategory'}</button>
            </div>
        </div>
    </div>
</div>


<div id="modalMenuItem" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <span class="modal-title"><i class="icon-cloud"></i>{l s=' Add or Edit Menu Item' mod='verticalmegamenus'}</span>
            </div>
            <div class="modal-body form-horizontal">                
                <form id="frmMenuItem">{$menuItemForm}</form>
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-primary btnForgot" onclick="saveMenuItem()"><i class="icon-save"></i> {l s='Save' mod='verticalmegamenus'}</button>
            </div>
        </div>
    </div>
</div>


<div id="modalProductId" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <span class="modal-title"><i class="icon-cloud"></i>{l s=' Add or Edit Item' mod='groupcategory'}</span>
            </div>
            <div class="modal-body form-horizontal">
                <label>{l s='Enter Product ID' mod='verticalmegamenus'}</label>
                <input type="text" class="form-control" id="product-id" />                
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-primary btnForgot" onclick="addProductId()"></i> {l s='OK' mod='verticalmegamenus'}</button>
            </div>
        </div>
    </div>
</div>