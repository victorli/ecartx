<div class="col-sm-12">
    <div class="panel">
        <div class="panel-heading">
            Flexible Custom Modules
    		<span class="panel-heading-action">
                <a href="javascript:void(0)" onclick="showModal('addModal', '')" class="list-toolbar-btn link-add"><span data-placement="left" data-html="true" data-original-title="Add New" class="label-tooltip" data-toggle="tooltip" title=""><i class="process-icon-new"></i></span></a>
    		</span>
        </div>
        <div class="panel-body" style="padding:0">
            <div class="table-responsive">
                <table class="table" id="modList">
        			<thead>
        				<tr class="nodrag nodrop">
                            <th class="">Title</th>                            
                            <th class="center">Layout</th> 
                            <th class="center">Position</th>
                            <th width="100" class="center">Ordering</th>
                            <th width="50" class="center">Status</th>                        
                            <th class="center" width="50" class="">#</th>
                        </tr>				
                    </thead>
                    <tbody>	
        	           {$content}
                    </tbody>
    
    	       </table>            
            </div>        
        </div> 
    </div>
       
</div>
<div class="col-sm-12" id="mainGroupList" style="display: none;">
    <div class="panel">
        <div class="panel-heading">
            Groups of Module [<span id="span-module-name"></span>]
    		<span class="panel-heading-action">
                <a href="javascript:void(0)" onclick="showModal('groupModal')" class="list-toolbar-btn"><span data-placement="left" data-html="true" data-original-title="Add category" class="label-tooltip" data-toggle="tooltip" title=""><i class="process-icon-new"></i></span></a>
    		</span>
        </div>
        <div class="panel-body" style="padding:0">
            <div class="table-responsive">
                <table class="table" id="groupList">
        			<thead>
        				<tr class="nodrag nodrop">                            
                            <th>Title</th>
                            <th>Category</th>
                            <th width="70" class="center">Type</th>
                            <th width="80" class="center">Max item</th>
                            <th width="100" class="center">Ordering</th>
                            <th width="50" class="center">Status</th>                          
                            <th class="center" width="50" class="">#</th>
                        </tr>				
                    </thead>    
                    <tbody></tbody>    
    	       </table>            
            </div>
        </div>
    </div>
</div>

<div class="col-sm-12" id="mainProductList" style="display: none;">
    <div class="panel">
        <div class="panel-heading">
            Products of Category
    		<span class="panel-heading-action">
                <a href="javascript:void(0)" onclick="openProductsModal();" class="list-toolbar-btn"><span data-placement="left" data-html="true" data-original-title="Add product" class="label-tooltip" data-toggle="tooltip" title=""><i class="process-icon-new"></i></span></a>
    		</span>
        </div>
        <div class="panel-body" style="padding:0">
            <div class="table-responsive">
                <table class="table" id="productList">
        			<thead>
        				<tr class="nodrag nodrop">                            
                            <th width="60">ID</th>
                            <th width="60">Img</th>
                            <th>Name</th>
                            <th>Reference</th>
                            <th>Category</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="center" width="80">{l s='Order' mod='flexiblecustom'}</th>          
                            <th class="center" width="30" class="">#</th>
                        </tr>				
                    </thead>    
                    <tbody></tbody>
    	       </table>
               <!-- <div id="manual-paginations"></div> -->            
            </div>
        </div>
    </div>
</div>
<div id="addModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <span class="modal-title"><i class="icon-cloud"></i>{l s=' Add or edit module' mod='flexiblecustom'}</span>
            </div>
            <div class="modal-body form-horizontal" id="forgotBody">                                                
                <form id="frmModule">
                    <div class="clearfix">                        
                        <div class="col-md-3">
                            <div class="list-group" id="list-group">
                                <a href="#module-info" class="list-group-item active">{l s='Module config' mod="flexiblecustom"}</a>           
                                <!-- <a href="#module-banners" class="list-group-item">{l s='Module banners' mod="flexiblecustom"}</a> -->                            
                            </div>
                        </div>  
                        <div class="col-md-9">
                            <div class="tab-content">                                
                                <div id="module-info" class="tab-pane fade in active">
                                    {$moduleForm.config}
                                </div>
                                <div id="module-banners" class="tab-pane fade">
                                    <div class="col-sm-12" id="form-banners">                                        
                                        {$moduleForm.banners}
                                    </div>
                                    <div class="col-lg-12" style="margin-top: 15px">
                                        <div class="col-sm-3">
                                            <button id="module-banner-uploader" class="btn btn-default" type="button"><i class="icon-upload"></i>Add new banner</button>                                                
                                        </div>
                                        <div class="col-sm-2">
                                            <select onchange="moduleChangeLanguage(this.value)" class="module-lang">{$langOptions}</select>
                                        </div>
                                    </div>
                                </div>                            
                            </div>
                        </div>    
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-off"></i> {l s='Cancel' mod='flexiblecustom'}</button>
                <button type="button" class="btn btn-primary btnForgot" onclick="saveModule()"><i class="icon-save"></i> {l s='Save' mod='flexiblecustom'}</button>
            </div>
        </div>
    </div>
</div>
<div id="groupModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <span class="modal-title"><i class="icon-cloud"></i>{l s=' Add or edit category' mod='flexiblecustom'}</span>
            </div>
            
            <div class="modal-body form-horizontal" id="forgotBody">
                <form id="frmGroup">{$groupForm}</form>
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-primary btnForgot" onclick="saveGroup()"><i class="icon-save"></i> {l s='Save' mod='flexiblecustom'}</button>
            </div>
        </div>
    </div>
</div>
<div id="productsModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog" style="width: 760px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <span class="modal-title"><i class="icon-cloud"></i>{l s=' List product of category' mod='flexiblecustom'}</span>
            </div>
            <div class="modal-body">
                <div class="table-responsive" >
                    <div class="clearfix">
                        <div class="form-inline text-right">
                            <input type="text" class="form-control" id="keyword" placeholder="{l s='ID or Name' mod='flexiblecustom'}" />
                            <button class="btn btn-default" type="button" onclick="loadProductsByCategory('1')"><i class="icon-search"></i> {l s='Search' mod='flexiblecustom'}</button>
                        </div>                        
                    </div>
                    <table class="table" id="allProductList" style="margin-top: 10px">
            			<thead>
            				<tr class="nodrag nodrop">                            
                                <th width="30" class="center">{l s='ID' mod='flexiblecustom'}</th>
                                <th width="50" class="center">#</th>
                                <th class="center">{l s='Name' mod='flexiblecustom'}</th>
                                <th class="center">{l s='Reference' mod='flexiblecustom'}</th>
                                <th class="center">{l s='Price' mod='flexiblecustom'}</th>
                                <th class="center">{l s='Quantity' mod='flexiblecustom'}</th>                                
                                <th width="30" class="">#</th>
                            </tr>				
                        </thead>    
                        <tbody></tbody>
        	       </table>
                   <div id="allProductList-pagination"></div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-off"></i> {l s='Cancel' mod='flexiblecustom'}</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var secure_key = "{$secure_key}";
    var baseModuleUrl = "{$baseModuleUrl}";
    var moduleFormNew = '';
    var groupFormNew = '';
    var moduleId = '0';
    var groupId = '0';
    var groupType = '';
    var reload = false;
    var loadCategory = false;
    var manualProductIds = [];
    var manualProductIndex = 0;
</script>