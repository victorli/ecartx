<input type="hidden" id="ajaxUrl" value="{$ajaxUrl}" />
<div class="panel">
    <h3><i class="icon-cog"></i>{l s=' Block HTML configuration' mod='blockhtml'}</h3>
    <div class="main-container clearfix">
        <form method="post" action="{$postAction|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data" class="item-form defaultForm  form-horizontal">
            <div class="row">
                <div class="col-sm-3">
                    <div class="panel">
                        <h3><i class="icon-cog"></i>{l s=' Hook position' mod='blockhtml'}</h3>
                        <div class="main-container">
                            {foreach from=$hookArr item=position name=hookArr}
                                <div class="input-group">
                                  <span class="input-group-addon">
                                    <input id="position_{$smarty.foreach.hookArr.iteration}" class="select_position"{if $default_position == $smarty.foreach.hookArr.iteration} checked="checked"{/if} type="radio" name="id_position" value="{$smarty.foreach.hookArr.iteration}" />
                                  </span>
                                  <label class="form-control" for="position_{$smarty.foreach.hookArr.iteration}">{$position}</label>
                                </div><!-- /input-group -->
                                <br />
                            {/foreach}
                        </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="panel">
                        <h3><i class="icon-cog"></i>{l s=' Html content' mod='blockhtml'}</h3>
                        <div id="htmlcontent" class="main-container">
                            {include file="{$admin_templates|escape:'htmlall':'UTF-8'}html_content.tpl"}
                        </div>
                    </div>
                </div>
            </div>
             <div class="panel-footer">
			    <button type="submit" value="1" id="module_form_submit_btn" name="submitGlobal" class="btn btn-default pull-right">
					<i class="process-icon-save"></i> Save
			    </button>
			</div>
        </form>
    </div>
</div>