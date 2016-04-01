<div class="col-sm-12">
    <div class="panel">
        <div class="panel-heading">
        	{l s='Groups' mod='oviccustomtags'}
    		<span class="panel-heading-action">
                <a href="javascript:void(0)" onclick="showModal('modalGroup', '')" class="list-toolbar-btn link-add"><span data-placement="left" data-html="true" data-original-title="Add New" class="label-tooltip" data-toggle="tooltip" title=""><i class="process-icon-new"></i></span></a>
    		</span>
        </div>
        <div class="panel-body" style="padding:0">
            <div class="table-responsive">
                <table class="table" id="groupList">
        			<thead>
        				<tr class="nodrag nodrop">
                            <th class="">{l s='Group Name' mod='oviccustomtags'}</th>
                            <th class="center" width="150">{l s='Position' mod='ourcurrentsales'}</th>
                            <th class="">{l s='Params' mod='oviccustomtags'}</th>
                            <th class="center" width="120">{l s='Ordering' mod='ourcurrentsales'}</th> 
                            <th class="center" width="50">{l s='Status' mod='ourcurrentsales'}</th>                 
                            <th class="center" width="50">#</th>
                        </tr>				
                    </thead>
                    <tbody>{$content}</tbody>    
    	       </table>            
            </div>        
        </div> 
    </div>
</div>
<div class="col-sm-12" id="panel-tag-list" style="display: none;">
    <div class="panel">
        <div class="panel-heading">
            {l s='Tags in Group' mod='oviccustomtags'}&nbsp;<span id="span-group-name"></span>
    		<span class="panel-heading-action">
                <a href="javascript:void(0)" onclick="showModal('modalTag', '')" class="list-toolbar-btn"><span data-placement="left" data-html="true" data-original-title="Add tags" class="label-tooltip" data-toggle="tooltip" title=""><i class="process-icon-new"></i></span></a>
    		</span>
        </div>
        <div class="panel-body" style="padding:0">
            <div class="table-responsive">
                <table class="table" id="tagList">
        			<thead>
        				<tr class="nodrag nodrop">                        
                            <th>{l s='Title' mod='oviccustomtags'}</th>
                            <th>{l s='Link' mod='oviccustomtags'}</th>
                            <th width="120" class="center">{l s='Ordering' mod='oviccustomtags'}</th>
                            <th width="50" class="center">{l s='Status' mod='oviccustomtags'}</th>                          
                            <th width="50" class="center">#</th>
                        </tr>				
                    </thead>
                    <tbody>
                        
                    </tbody>
    	       </table>            
            </div>
        </div>
    </div>
</div>

<div id="modalGroup" class="modal fade" tabindex="-1">
    <div class="modal-dialog loginModal">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <span class="modal-title"><i class="icon-cloud"></i>{l s=' Add new Group' mod='oviccustomtags'}</span>
            </div>
            <div class="modal-body form-horizontal" id="forgotBody">
                <form id="frmGroup">{$groupForm}</form>
                          
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-off"></i> {l s='Cancel' mod='oviccustomtags'}</button>
                <button type="button" class="btn btn-primary btnForgot" onclick="saveGroup()"><i class="icon-save"></i> {l s='Save' mod='oviccustomtags'}</button>
            </div>
        </div>
    </div>
</div>
<div id="modalTag" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <span class="modal-title"><i class="icon-cloud"></i>{l s=' Add tag in Group' mod='oviccustomtags'}</span>
            </div>
            <div class="modal-body form-horizontal" id="forgotBody">
                <form id="frmTag">{$tagForm}</form>
                                
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-primary btnForgot" onclick="saveTag()"><i class="icon-save"></i> {l s='Save' mod='oviccustomtags'}</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var baseModuleUrl = "{$baseModuleUrl}";
    var secure_key = "{$secure_key}";
    var groupId = '0';       
    var groupFormNew = '';
    var tagFromNew = '';
</script>