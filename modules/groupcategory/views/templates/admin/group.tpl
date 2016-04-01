<div class="panel">
    <div class="panel-heading">
    	{l s='List Group' mod='groupcategory'}{l s='Best Saller' mod='groupcategory'}
		<span class="panel-heading-action">
            <a href="javascript:void(0)" onclick="showModal('modalGroup', '')" class="list-toolbar-btn link-add"><i class="process-icon-new"></i></a>
		</span>
    </div>
    <div class="panel-body" style="padding:0">
        <div class="table-responsive">
            <table class="table" id="groupList">
    			<thead>
    				<tr class="nodrag nodrop">
                        <th width="50" class="center">{l s='ID' mod='groupcategory'}</th>
                        <th>{l s='Name' mod='groupcategory'}</th>
                        <th>{l s='Category' mod='groupcategory'}</th>
                        <th>{l s='Position ' mod='groupcategory'}</th>
                        <th width="120" class="center">{l s='Style' mod='groupcategory'}</th>
                        <th width="100" class="center">{l s='Ordering' mod='groupcategory'}</th>
                        <th width="50" class="center">{l s='Status' mod='groupcategory'}</th>
                        <th class="center" width="50">#</th>
                    </tr>				
                </thead>
                <tbody>{$listGroup}</tbody>    
	       </table>            
        </div>        
    </div> 
</div>

<div class="panel" id="panel-list-item" style="display: none">
    <div class="panel-heading">
    	{l s='Item in Group' mod='groupcategory'}&nbsp<span id="span-group-name"></span>
		<span class="panel-heading-action">
            <a href="javascript:void(0)" onclick="openAddItemModal()" class="list-toolbar-btn link-add"><i class="process-icon-new"></i></a>
		</span>
    </div>
    <div class="panel-body" style="padding:0">
        <div class="table-responsive">
            <table class="table" id="itemList">
    			<thead>
    				<tr class="nodrag nodrop">
                        <th width="50" class="center">{l s='ID' mod='groupcategory'}</th>
                        <th>{l s='Name' mod='groupcategory'}</th>
                        <th width="250">{l s='Category' mod='groupcategory'}</th>
                        <th width="100" class="center">{l s='Ordering' mod='groupcategory'}</th>
                        <th width="50" class="center">{l s='Status' mod='groupcategory'}</th>
                        <th class="center" width="50">#</th>
                    </tr>				
                </thead>
                <tbody>
                    
                </tbody>    
	       </table>            
        </div>        
    </div> 
</div>

<!-- Modal -->
<div id="modalGroup" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <span class="modal-title"><i class="icon-cloud"></i>{l s=' Add or Edit Group' mod='groupcategory'}</span>
            </div>
            <div class="modal-body form-horizontal" id="forgotBody">
                <form id="frmGroup">
                	<div class="clearfix">
                        <div class="groupcategory-tab-links text-center" id="tab-groups">
                            <a href="#group-config" class="tab-item active">{l s='Group config' mod="groupcategory"}</a>
                            <a href="#group-products-config" class="tab-item">{l s='Products config' mod="groupcategory"}</a>                                       
                        </div>
                    </div>     
                    <div class="tab-content">                                
                        <div id="group-config" class="tab-pane fade in active">
                            {$groupForm.config}
                        </div>
                        <div id="group-products-config" class="tab-pane fade">
                            {$groupForm.product_config}                           
                        </div>
                        
                    </div> 	
                </form>
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-primary btnForgot" onclick="saveGroup()"><i class="icon-save"></i> {l s='Save' mod='groupcategory'}</button>
            </div>
        </div>
    </div>
</div>

<div id="modalItem" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <span class="modal-title"><i class="icon-cloud"></i>{l s=' Add or Edit Item' mod='groupcategory'}</span>
            </div>
            <div class="modal-body form-horizontal" id="forgotBody">
                <form id="frmItem">{$itemForm}</form>                             
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-primary btnForgot" onclick="saveItem()"><i class="icon-save"></i> {l s='Save' mod='groupcategory'}</button>
            </div>
        </div>
    </div>
</div>
{include file="$dialog_product"}
{include file="$dialog_feature"}
{include file="$dialog_manufacturer"}