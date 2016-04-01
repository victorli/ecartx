<input type="hidden" id="ajaxUrl" name="ajaxUrl" value="{$ajaxUrl}"/>
<div class="panel" >
    <h3><i class="icon-list-ul"></i>{l s=' Footer Configuration' mod='advancefooter'}
	<span class="panel-heading-action">
		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&submitRow">
			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new row" data-html="true">
				<i class="process-icon-new "></i>
			</span>
		</a>
	</span>
	</h3>
    {if isset($footer_data) && $footer_data|count > 0}
        <div class="row_sortable">
        {foreach $footer_data item=row name=footer}
            <div class="panel row_container">
                <span class="hidden row_id">{$row.id_row}</span>
                <h3><i class="icon-list-ul"></i>{l s=' Footer row ' mod='advancefooter'}<span class="row_postition">{$smarty.foreach.footer.iteration}</span>
                    <span class="panel-heading-action status">
                		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&changestatus&id_row={$row.id_row}">
                			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Change status" data-html="true">
                				{if $row.active}
                                    <img src="{$smarty.const._PS_ADMIN_IMG_}ok.gif" alt="" />
                                {else}
                                    <img src="{$smarty.const._PS_ADMIN_IMG_}forbbiden.gif" alt="" />
                                {/if}
                			</span>
                		</a>
                	</span>
                    <span class="panel-heading-action edit">
                		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&submitRow&id_row={$row.id_row}">
                			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Edit row" data-html="true">
                				<i class="process-icon-edit "></i>
                			</span>
                		</a>
                	</span>
                    <span class="panel-heading-action">
                		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&submit_del_row&id_row={$row.id_row}" onclick="return confirm('Are you sure delete this row, including row\'s blocks and block\'s items?')">
                			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Delete row" data-html="true">
                				<i class="process-icon-delete "></i>
                			</span>
                		</a>
                	</span>
                </h3>
                <div class="form-group">
            		<a href="{$postAction|escape:'htmlall':'UTF-8'}&submitBlock&block_row={$row.id_row}&addblock=1" class="btn btn-default btn-lg button-new-item"><i class="icon-plus-sign"></i> Add new block</a>
            	</div>
                {if $row.blocks|count > 0}
                    <div class="main-container container-fluid blocksortable">
                    {foreach $row.blocks as $block_obj}
                       <div class="col-sm-{$block_obj.width} block-container blocksort_{$row.id_row}">
                       <span class="hidden block_id">{$block_obj.id}</span>
                        <h3 class="heading-panel">
                            <span class="heading-action add">
                        		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&submitItem&block_id={$block_obj.id}">
                                    <span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new item" data-html="true">
                        			<i class="process-icon-new "></i>
                                    {l s='Add new item' mod='advancetopmenu'}
                                    </span>

                        		</a>
                        	</span>
                            <span class="heading-action edit">
                        		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&submitBlock&&block_row={$row.id_row}&id_block={$block_obj.id}">
                        			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Edit this block setting" data-html="true">
                        				<i class="process-icon-edit "></i>
                        			</span>
                        		</a>
                        	</span>
                            <span class="heading-action del">
                        		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&submitRemoveBlock&id_block={$block_obj.id}&block_row={$block_obj.id_row}" onclick="return confirm('Are you sure delete this block (including all items of this block)')">
                        			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Delete this block" data-html="true">
                        				<i class="process-icon-delete "></i>
                        			</span>
                        		</a>
                        	</span>
                       	</h3>

                        {if count($block_obj.items)>0}
                            {foreach from=$langguages.all item=lang}
                                <div class="translatable-field lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}" {if $langguages.default_lang != $lang.id_lang}style="display:none"{/if}>
                                    <table class="table">
                                        <thead>
                                            <th width="40%">Title</th>
                                            <th width="40%">Type</th>
                                            <th width="20%">Action</th>
                                        </thead>
                                        <tbody class="item_sortable">
                                            {foreach $block_obj.items item=item name=items}
                                                <tr class="item_{$block_obj.id}_{$lang.id_lang}">
                                                    <td>
                                                        <span class="hidden item_id">{$item->id}</span>
                                                        {if isset($item->title[$lang.id_lang])}{$item->title[$lang.id_lang]}{/if}
                                                    </td>
                                                    <td>{$item->itemtype}</td>
                                                    <td style="text-align:right;">
                                                        <a href="{$postAction|escape:'htmlall':'UTF-8'}&submitItem&block_id={$block_obj.id}&id_item={$item->id}" class="edit_btn" title="Edit" ><i class="icon-edit"></i></a>
                                                        <a href="{$postAction|escape:'htmlall':'UTF-8'}&removeitem=1&id_item={$item->id}" class="edit_btn" title="Delete" onclick="return confirm('Are you sure delete this Item?')"><i class="icon-remove "></i></a>
                                                    </td>
                                                </tr>
                                            {/foreach}
                                        </tbody>
                                    </table>
        						</div>
    	                    {/foreach}
                        {/if}
                        </div>
                    {/foreach}
                    </div>
                {/if}
            </div>
        {/foreach}
        </div>
    {/if}