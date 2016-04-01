<div class="col-sm-12">
    <div class="panel">
        <div class="panel-heading">
        	{l s='Banners' mod='oviccustombanner'}
    		<span class="panel-heading-action">
                <a href="javascript:void(0)" onclick="showModal('modalBanner', '')" class="list-toolbar-btn link-add"><span data-placement="left" data-html="true" data-original-title="Add New" class="label-tooltip" data-toggle="tooltip" title=""><i class="process-icon-new"></i></span></a>
    		</span>
        </div>
        <div class="panel-body" style="padding:0">
            <div class="table-responsive">
                <table class="table" id="bannerList">
        			<thead>
        				<tr class="nodrag nodrop">
                            <th class="">{l s='Banner' mod='oviccustombanner'}</th>
                            <th class="center" width="150">{l s='Position' mod='oviccustombanner'}</th>
                            <th class="center" width="120">{l s='Ordering' mod='oviccustombanner'}</th> 
                            <th class="center" width="50">{l s='Status' mod='oviccustombanner'}</th>                 
                            <th class="center" width="50">#</th>
                        </tr>				
                    </thead>
                    <tbody>{$content}</tbody>    
    	       </table>            
            </div>        
        </div> 
    </div>
</div>

<div id="modalBanner" class="modal fade" tabindex="-1">
    <div class="modal-dialog loginModal">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <span class="modal-title"><i class="icon-cloud"></i>{l s=' Add new Banner' mod='oviccustombanner'}</span>
            </div>
            <div class="modal-body form-horizontal" id="forgotBody">
                <form id="frmBanner">
                    {$form}
                </form>
                               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-off"></i> {l s='Cancel' mod='oviccustombanner'}</button>
                <button type="button" class="btn btn-primary btnForgot" onclick="saveBanner()"><i class="icon-save"></i> {l s='Save' mod='oviccustombanner'}</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var baseModuleUrl = "{$baseModuleUrl}";
    var secure_key = "{$secure_key}";
    var newForm = '';//$("#frmBanner").html();
</script>