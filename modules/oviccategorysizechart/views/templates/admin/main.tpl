{$categoryTree}
<input type="hidden" id="mainUrl" class="hidden" value="{$postAction|escape:'htmlall':'UTF-8'}" />
{if $displayList}
    <div class="panel">
        <h3><i class="icon-cog"></i>{l s='Sizechart list' mod='oviccategorysizechart'}
        	<span class="panel-heading-action">
        		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&addSizechart&id_category={$current_cate->id_category}">
        			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Add' mod='oviccategorysizechart'}" data-html="true">
        				<i class="process-icon-new "></i>
        			</span>
        		</a>
        	</span>
    	</h3>
        <div class="main-container">
            {if $sizecharts && $sizecharts|count > 0}
                {foreach $sizecharts as $sizechart}
                <div id="sizecharts_{$sizechart.id_sizechart}" class="panel">
					<div class="row">
						<div class="col-md-4">
							<img src="{$imgpath}{$sizechart.image}" alt="" class="img-thumbnail"/>
						</div>
						<div class="col-md-8">
							<div class="btn-group-action pull-right">
                                {if $sizechart.active == 1}
								    <a class="btn btn-success" href="{$postAction|escape:'htmlall':'UTF-8'}&changeStatus&&id_sizechart={$sizechart.id_sizechart}&id_category={$sizechart.id_category}" title="Enabled"><i class="icon-check"></i> {l s='Enabled' mod='oviccategorysizechart'}</a>
                                {else}
                                    <a class="btn btn-danger" href="{$postAction|escape:'htmlall':'UTF-8'}&changeStatus&&id_sizechart={$sizechart.id_sizechart}&id_category={$sizechart.id_category}" title="Disabled"><i class="icon-remove"></i> {l s='Disabled' mod='oviccategorysizechart'}</a>
                                {/if}
								<a class="btn btn-default" href="{$postAction|escape:'htmlall':'UTF-8'}&id_sizechart={$sizechart.id_sizechart}&id_category={$sizechart.id_category}">
									<i class="icon-edit"></i>{l s='Edit' mod='oviccategorysizechart'}
								</a>
								<a class="btn btn-default" href="{$postAction|escape:'htmlall':'UTF-8'}&delete_id_sizechart={$sizechart.id_sizechart}&id_category={$sizechart.id_category}">
									<i class="icon-trash"></i>{l s='Delete' mod='oviccategorysizechart'}
								</a>
							</div>
						</div>
					</div>
				</div>
                {/foreach}
            {/if}
         </div>
     </div>
 {/if}
 