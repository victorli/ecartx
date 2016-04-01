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
                    <input type="hidden" name="bannerId" id="bannerId" value="0" />
                    <div class="form-group">                    
                        <label class="control-label col-lg-3">{l s='Position' mod='oviccustomtags'}</label>
                        <div class="col-lg-9 ">
                            <div class="col-lg-12">
                                <select name="position" id="position" class="form-control">{$positionOptions}</select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">{l s='Title' mod='oviccustombanner'}</label>
                        <div class="col-lg-9 ">
                            <div class="col-sm-10">
                                <input type="text"  name="title[]" id="banner-title" value="" class="form-control" />
                            </div>
                            <div class="col-sm-2">
                                <select name="lang" id="lang" class="lang form-control" onchange="loadBannerByLang(this.value)">{$langOptions}</select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label class="control-label col-sm-3">{l s='Banner' mod='oviccustombanner'}</label>
                        <div class="col-sm-9">
                            <div class="col-sm-10">                        
                                <div class="input-group">
                                    <input type="text" id="banner-image" class="form-control" />                                    
                                    <span class="input-group-btn">
                                        <button id="banner" type="button" class="btn btn-default"><i class="icon-folder-open"></i></button>
                                    </span>
                                </div>                        
                            </div>
                            <div class="col-sm-2">
                                <select class="lang form-control" onchange="loadBannerByLang(this.value)">{$langOptions}</select>
                            </div>
                        </div>  
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-lg-3">{l s='Link' mod='oviccustombanner'}</label>
                        <div class="col-lg-9 ">
                            <div class="col-sm-10">
                                <input type="text" id="banner-link"  name="link[]" value="" class="form-control" />
                            </div>
                            <div class="col-sm-2">
                                <select  class="lang form-control" onchange="loadBannerByLang(this.value)">{$langOptions}</select>
                            </div>
                        </div>
                    </div>
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
</script>