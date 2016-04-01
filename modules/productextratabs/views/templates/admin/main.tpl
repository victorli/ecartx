<div class="panel">
        <h3><i class="icon-cog"></i>{l s='Tabs manager' mod='productextratabs'}
    	<span class="panel-heading-action">
    		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&itemsubmit&action=add">
    			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new tab" data-html="true">
    				<i class="process-icon-new "></i>
    			</span>
    		</a>
    	</span>
	</h3>
    <div class="main-container">
        {if $tabs && $tabs|count > 0}
            <table class="table">
                <thead>
                    <th width="20%">Title</th>
                    <th width="60%">Content</th>
                    <th width="10%">Acive</th>
                    <th width="10%">Action</th>
                </thead>
                <tbody>
                    {foreach $tabs as $tab}
                        <tr>
                            <td>{$tab.title}</td>
                            <td>{$tab.content}</td>
                            <td>
                                <a href="{$postAction}&changeactive&id_tab={$tab.id_tab}" data-toggle="tooltip" class="label-tooltip" data-html="true"
                                    {if $tab.active}
                                        data-original-title="Actived" >
                                        <img src="{$smarty.const._PS_ADMIN_IMG_}ok.gif" alt="" />
                                    {else}
                                        data-original-title="Deactived" >
                                        <img src="{$smarty.const._PS_ADMIN_IMG_}forbbiden.gif" alt="" />
                                    {/if}
                                </a>
                            </td>
                            <td>
                                <a href="{$postAction}&itemsubmit&id_tab={$tab.id_tab}" data-toggle="tooltip" class="edit_btn label-tooltip" data-html="true" data-original-title="Edit tab"><i class="icon-edit"></i></a>
                                <a href="{$postAction}&removetab&id_tab={$tab.id_tab}" data-toggle="tooltip" class="edit_btn label-tooltip" data-html="true" data-original-title="Delete tab" onclick="return confirm('Are you sure delete this tab?')"><i class="icon-remove "></i></a>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        {/if}
    </div>
</div>