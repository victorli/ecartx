<div class="panel">
    <div class="panel-heading">
    	{l s='List Style' mod='groupcategory'}
		<span class="panel-heading-action">
            <a href="javascript:void(0)" onclick="showModal('modalStyle', '')" class="list-toolbar-btn link-add"><i class="process-icon-new"></i></a>
		</span>
    </div>
    <div class="panel-body" style="padding:0">
        <div class="table-responsive">
            <table class="table" id="styleList">
    			<thead>
    				<tr class="nodrag nodrop">
                        <th width="50" class="center">{l s='ID' mod='groupcategory'}</th>
                        <th width="120" class="center">{l s='Name' mod='groupcategory'}</th>
                        <th class="center">{l s='Params' mod='groupcategory'}</th>
                        <th class="center" width="50">#</th>
                    </tr>				
                </thead>
                <tbody>{$listStyles}</tbody>    
	       </table>            
        </div>        
    </div> 
</div>

<!-- Modal -->
<div id="modalStyle" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <span class="modal-title"><i class="icon-cloud"></i>{l s=' Add or Edit Style' mod='groupcategory'}</span>
            </div>
            <div class="modal-body form-horizontal" id="forgotBody">
                <input type="hidden" id="style-id" value="0" />
                <div class="form-group">
                    <label class="control-label col-lg-3 required">{l s='Style Name' mod='groupcategory'}</label>
				    <div class="col-lg-9">       
                        <div class="col-sm-12">                            
                            <input type="text" id="style-name" value="" class="form-control" />
                        </div>                        
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3">{l s='Background Color' mod='groupcategory'}</label>
        		    <div class="col-lg-9">
                        <div class="col-sm-6">
                            <div class="input-group">
                                <input type="text" class="mColorPicker form-control" id="color_background_header" value="" data-hex="true" />
                                <span id="icp_color_background_header" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true">
                                    <img src="../img/admin/color.png" />
                                </span>                                
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3">{l s='Background Type' mod='groupcategory'}</label>
        		    <div class="col-lg-9">
                        <div class="col-sm-6">                        
                            <div class="input-group">
                                <input type="text" class="mColorPicker form-control" id="color_background_type" value="" data-hex="true" />
                                <span id="icp_color_background_type" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true">
                                    <img src="../img/admin/color.png" />
                                </span>
                           </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-lg-3">{l s='Color List Active' mod='groupcategory'}</label>
        		    <div class="col-lg-9">
                        <div class="col-sm-6">
                            <div class="input-group">
                                <input type="text" class="mColorPicker form-control" id="color_list" value="" data-hex="true" />
                                <span id="icp_color_list" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true">
                                    <img src="../img/admin/color.png" />
                                </span>
                            </div>
                        </div>                                                                    
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3">{l s='Banner Gradient' mod='groupcategory'}</label>
        		    <div class="col-lg-9">
                        <div class="col-sm-6">
                            <div class="input-group">
                                <input type="text" class="mColorPicker form-control" id="color_banner_from" value="" data-hex="true" />
                                <span id="icp_color_banner_from" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true">
                                    <img src="../img/admin/color.png" />
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <input type="text" class="mColorPicker form-control" id="color_banner_to" value="" data-hex="true" />
                                <span id="icp_color_banner_to" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true">
                                    <img src="../img/admin/color.png" />
                                </span>                            
                            </div>
                        </div>                    
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-primary btnForgot" onclick="saveStyle()"><i class="icon-save"></i> {l s='Save' mod='groupcategory'}</button>
            </div>
        </div>
    </div>
</div>