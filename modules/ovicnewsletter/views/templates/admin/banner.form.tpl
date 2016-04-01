<form id="frmBanner">
    <input type="hidden" name="bannerId" id="groupId" value="0" />
    <div class="form-group">
        <label class="control-label col-lg-3">{l s='Title' mod='oviccustombanner'}</label>
        <div class="col-lg-9 ">
            <div class="col-sm-10">
                
                <input type="text"  id="group-name" value="" class="form-control" />
            </div>
            <div class="col-sm-2">
                <select id="group-lang" class="group-lang form-control" onchange="loadGroupByLang(this.value)">{$langOptions}</select>
            </div>
        </div>
    </div>
    <div class="form-group">                    
        <label class="control-label col-lg-3 required">{l s='Position' mod='oviccustomtags'}</label>
        <div class="col-lg-9 ">
            <div class="col-lg-12">
                <select id="group-position" class="form-control">{$positionOptions}</select>
            </div>
        </div>
    </div>          
    <div class="form-group">
        <label class="control-label col-lg-3">{l s='Background Color' mod='oviccustomtags'}</label>
        <div class="col-lg-9">
            <div class="col-sm-6">
                <div class="input-group">
                    <input type="text" class="mColorPicker form-control" id="color_background" value="" data-hex="true" />
                    <span id="icp_color_background" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true">
                        <img src="../img/admin/color.png" />
                    </span>                                
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="control-label col-lg-3">{l s='Title Color' mod='oviccustomtags'}</label>
        <div class="col-lg-9">
            <div class="col-sm-6">
                <div class="input-group">
                    <input type="text" class="mColorPicker form-control" id="color_title" value="" data-hex="true" />
                    <span id="icp_color_title" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true">
                        <img src="../img/admin/color.png" />
                    </span>                                
                </div>
                
            </div>
        </div>
    </div>
          
    <div class="form-group">
        <label class="control-label col-sm-3">{l s='Display group' mod='oviccustomtags'}</label>
        <div class="col-sm-9">
            <div class="col-sm-12">
                <span class="switch prestashop-switch fixed-width-lg" id="group_display">
                    <input type="radio" value="1" class="group_display" checked="checked" id="group_display_on" name="group_display" />
                    <label for="group_display_on">Yes</label>
                    <input type="radio" value="0" class="group_display" id="group_display_off" name="group_display" />
                    <label for="group_display_off">No</label>
                    <a class="slide-button btn"></a>
                </span>
            </div>
        </div>
    </div>    
</form>