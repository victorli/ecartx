<div class="panel">
    <h3><i class="icon-cogs"></i>{l s=' Setting' mod='popularcategories'}
    </h3>
    <div class="main-container">
        <form method="post" action="{$postAction|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data" class="item-form defaultForm  form-horizontal">
    		<div class="well">
                <div class="form-group">
    				<label class="control-label col-lg-2" for="DEALS">{l s='Deals of the day'}</label>
    				<div class="col-lg-9">
						<div class="input-group col-lg-4">
							<input class="datepicker" type="text" name="DEALS" value="{if isset($DEALS)}{$DEALS}{/if}" style="text-align: center" id="DEALS" />
							<span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
						</div>
    				</div>
    			</div>
                <div class="panel-footer">
				    <button type="submit" value="1" id="module_form_submit_btn" name="submitGlobal" class="btn btn-default pull-right">
						<i class="process-icon-save"></i> Save
				    </button>
				</div>
    		</div>
    	</form>
    </div>
</div>
<script type="text/javascript">
		$(document).ready(function(){
			$('.datepicker').datetimepicker({
				prevText: '',
				nextText: '',
				dateFormat: 'yy-mm-dd',
				currentText: '{l s='Now'}',
				closeText: '{l s='Done'}',
				ampm: false,
				amNames: ['AM', 'A'],
				pmNames: ['PM', 'P'],
				timeFormat: 'hh:mm:ss tt',
				timeSuffix: '',
				timeOnlyTitle: '{l s='Choose Time'}',
				timeText: '{l s='Time'}',
				hourText: '{l s='Hour'}',
				minuteText: '{l s='Minute'}',
			});
		});
	</script>