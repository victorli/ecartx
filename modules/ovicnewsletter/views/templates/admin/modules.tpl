<form method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="panel">
        <div class="panel-heading">
        	{l s='Register newsletter form setting'}    		
        </div>
        <div class="panel-body">
            {$form}
        </div>
        <div class="panel-footer">
            <button class="btn btn-default pull-right" id="configuration_form_submit_btn" value="1" type="submit">
                <i class="process-icon-save"></i> Save
            </button>
        </div>
    </div>
</form>
<script type="text/javascript">
    var baseModuleUrl = "{$baseModuleUrl}";
    var secure_key = "{$secure_key}";
    var ad = "{$ad}";
    var iso = "{$iso}";
    var currentLang = "{$langId}";
</script>