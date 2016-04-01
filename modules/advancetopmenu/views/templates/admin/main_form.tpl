<div class="panel">
    <h3><i class="icon-list-ul"></i>{$form} {l s='Item' mod='advancetopmenu'}
	<span class="panel-heading-action">
		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&submit_edit_item&block=0">
			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add {$form} item" data-html="true">
				<i class="process-icon-new "></i>
			</span>
		</a>
	</span>
	</h3>
	{*}<div class="panel-heading">

    </div>{*}
    <div class="main-container">
        <input type="hidden" id="ajaxUrl" name="ajaxUrl" value="{$ajaxUrl}"/>
        <form method="post" action="{$postAction|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data" class="item-form defaultForm  form-horizontal">
            <div class="table-responsive clearfix main_list">
                <ul class="list-unstyled{if $list_items|count>1} sortable{/if}">
                {foreach $list_items item=menu_item name=list_items}
                    <li class="main-item list-item">
                        <span class="hidden">{$menu_item.id_item}</span>
                        <span class="item-name">{if $menu_item.icon}<i class="{$menu_item.icon}"></i>{/if}{$menu_item.title}</span>
                        <span class="action-container">
                            <a href="{$postAction}&changeactive&item_id={$menu_item.id_item}" data-toggle="tooltip" class="label-tooltip" data-html="true"
                                {if $menu_item.active}
                                    data-original-title="Active" >
                                    <img src="{$smarty.const._PS_ADMIN_IMG_}ok.gif" alt="" /> 
                                {else}
                                    data-original-title="Dective" >
                                    <img src="{$smarty.const._PS_ADMIN_IMG_}forbbiden.gif" alt="" />
                                {/if}
                            </a>
                            <a href="{$postAction|escape:'htmlall':'UTF-8'}&submit_edit_item&block={$menu_item.id_block}&item_id={$menu_item.id_item}" class="edit_btn label-tooltip" data-toggle="tooltip" data-html="true" data-original-title="Edit"><i class="icon-edit"></i></a>
                            <a href="{$postAction|escape:'htmlall':'UTF-8'}&submit_del_item&item_id={$menu_item.id_item}" class="edit_btn label-tooltip" data-toggle="tooltip" data-html="true" data-original-title="Delete" onclick="return confirm('Are you sure delete this main item (including it\'s submenu, submenu\'s blocks and block\'s items)')"><i class="icon-remove "></i></a>
                        </span>
                    </li>
                {/foreach}
                </ul>
            </div>
        </form>
    </div>
</div>