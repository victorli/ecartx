<div class="panel">

    <div class="panel-heading">

    	{l s='List Modules' mod='verticalmegamenus'}

		<span class="panel-heading-action">

            <a href="javascript:void(0)" onclick="showModal('modalModule', '')" class="list-toolbar-btn link-add"><span data-placement="left" data-html="true" data-original-title="Add New" class="label-tooltip" data-toggle="tooltip" title=""><i class="process-icon-new"></i></span></a>

		</span>

    </div>

    <div class="panel-body" style="padding:0">

        <div class="table-responsive">

            <table class="table" id="moduleList">

    			<thead>

    				<tr class="nodrag nodrop">

                        <th width="50" class="center">{l s='ID' mod='verticalmegamenus'}</th>

                        <th>{l s='Name' mod='verticalmegamenus'}</th>

                        <th width="150" class="center">{l s='Position' mod='verticalmegamenus'}</th>

                        <th width="150" class="center">{l s='Layout' mod='verticalmegamenus'}</th>

                        <th width="100" class="center">{l s='Ordering' mod='verticalmegamenus'}</th>

                        <th width="50" class="center">{l s='Status' mod='verticalmegamenus'}</th>

                        <th class="center" width="50">#</th>

                    </tr>				

                </thead>

                <tbody>{$listModule}</tbody>    

	       </table>            

        </div>        

    </div> 

</div>



<div id="modalModule" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <span class="modal-title"><i class="icon-cloud"></i>{l s=' Add or edit category' mod='verticalmegamenus'}</span>
            </div>
            <div class="modal-body form-horizontal" id="forgotBody">
                <form id="frmModule">{$moduleForm}</form>           
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-primary btnForgot" onclick="saveModule()"><i class="icon-save"></i> {l s='Save' mod='verticalmegamenus'}</button>
            </div>
        </div>
    </div>
</div>