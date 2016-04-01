<div id="htmlcontent" class="panel">
    <div class="panel-heading"><i class="icon-cog"></i>{l s=' Main menu setting' mod='advancetopmenu'}</div>
    {if isset($errors) && $errors}
        {foreach $errors item=error name=errors}
            {include file="{$admin_tpl_path|escape:'htmlall':'UTF-8'}messages.tpl" id="main{$smarty.foreach.errors.index}" text=$error class='error'}
        {/foreach}
    {/if}
    {if isset($confirmation) && $confirmation}
        {include file="{$admin_tpl_path|escape:'htmlall':'UTF-8'}messages.tpl" id="main" text=$confirmation class='conf'}
    {/if}
    <!-- main item -->
    {if isset($form)}
        {if $form == 'block'}
            {include file="{$admin_tpl_path|escape:'htmlall':'UTF-8'}block_form.tpl"}
        {else}
            {include file="{$admin_tpl_path|escape:'htmlall':'UTF-8'}main_form.tpl"}
        {/if}
    {/if}
</div>
<div id="htmlcontent" class="panel">
    <h3><i class="icon-list-ul"></i>{l s='Sub menu setting' mod='advancetopmenu'}
	<span class="panel-heading-action">
		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&submit_edit_sub">
			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add sub menu" data-html="true">
				<i class="process-icon-new "></i>
			</span>
		</a>
	</span>
	</h3>
    {if isset($supmenu) && count($supmenu)>0}
    {foreach $supmenu item=sub name=supmenu}
    <div class="panel">
        <h3><i class="icon-list-ul"></i>{$sub.title} {l s='\'s sub menu' mod='advancetopmenu'}
            <span class="panel-heading-action status">
        		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&changestatus&id_sub={$sub.id_sub}">
        			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Change status" data-html="true">
        				{if $sub.active}
                            <img src="{$smarty.const._PS_ADMIN_IMG_}ok.gif" alt="" />
                        {else}
                            <img src="{$smarty.const._PS_ADMIN_IMG_}forbbiden.gif" alt="" />
                        {/if}
        			</span>
        		</a>
        	</span>
        	<span class="panel-heading-action edit">
        		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&submit_edit_sub&id_sub={$sub.id_sub}">
        			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Edit sub menu" data-html="true">
        				<i class="process-icon-edit "></i>
        			</span>
        		</a>
        	</span>
            <span class="panel-heading-action">
        		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&submit_del_sub&id_sub={$sub.id_sub}" onclick="return confirm('Are you sure delete this submenu, including submenu\'s blocks and block\'s items?')">
        			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Delete sub menu" data-html="true">
        				<i class="process-icon-delete "></i>
        			</span>
        		</a>
        	</span>
       	</h3>
        <div class="form-group">
    		<a href="{$postAction|escape:'htmlall':'UTF-8'}&submit_new_block&id_sub={$sub.id_sub}" class="btn btn-default btn-lg button-new-item"><i class="icon-plus-sign"></i> Add new column</a>
    	</div>

        {if count($sub.blocks)>0}
        <div class="main-container container-fluid {if count($sub.blocks)>1} list-unstyled sub_sortable{/if}">
        {assign var='totalwidth' value=0}
        {foreach $sub.blocks item=block name=blocks}			
            {assign var='totalwidth' value=$totalwidth+$block.width}            
            {if $totalwidth>12 && !$smarty.foreach.blocks.last}				
                <div class="clearfix"></div>                
                {assign var='totalwidth' value=0}            
            {/if}
            <div class="col-sm-{$block.width} block-container block-{$sub.id_sub}">
            <span class="hidden block_id">{$block.id_block}</span>
            <h3 class="heading-panel">
                <span class="heading-action add">
            		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&submit_edit_item&block={$block.id_block}">
                        <span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new item" data-html="true">
            			<i class="process-icon-new "></i>
                        {l s='Add new item' mod='advancetopmenu'}
                        </span>

            		</a>
            	</span>
            	<span class="heading-action edit">
            		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&submit_new_block&id_sub={$sub.id_sub}&id_block={$block.id_block}">
            			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Edit this block setting" data-html="true">
            				<i class="process-icon-edit "></i>
            			</span>
            		</a>
            	</span>
                <span class="heading-action del">
            		<a class="list-toolbar-btn" href="{$postAction|escape:'htmlall':'UTF-8'}&submit_del_block&id_block={$block.id_block}" onclick="return confirm('Are you sure delete this block, including all items of this block?')">
            			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Delete this block" data-html="true">
            				<i class="process-icon-delete "></i>
            			</span>
            		</a>
            	</span>
           	</h3>
            {if count($block.items)>0}
            <ul class="block-items list-unstyled{if count($block.items)>1} sortable{/if}">
                {foreach $block.items item=menuitem name=menuitems}
                    <li class="block-items-list-{$block.id_block} {$menuitem['type']}-container clearfix">
                        <span class="hidden">{$menuitem['id_item']}</span>
                        {if $menuitem['type'] == 'img' && $menuitem['icon']}
                            <span class="imageContent"><img class="img-thumbnail img-responsive" src="{$imgpath}{$menuitem['icon']}" alt="" /></span>
                        {elseif $menuitem['type'] == 'html'}
                            <span class="htmlContent">
                                {$menuitem['text']}
                            </span>
                        {else}
                            <span class="item-name">{if $menuitem['icon']}<i class="{$menuitem['icon']}"></i>{/if}{$menuitem['title']}</span>
                        {/if}
                        <span class="action-container">
                            <a href="{$postAction}&changeactive&item_id={$menuitem['id_item']}" data-toggle="tooltip" class="label-tooltip" data-html="true"
                                    {if $menuitem['active']}
                                        data-original-title="Actived" >
                                        <img src="{$smarty.const._PS_ADMIN_IMG_}ok.gif" alt="" />
                                    {else}
                                        data-original-title="Deactived" >
                                        <img src="{$smarty.const._PS_ADMIN_IMG_}forbbiden.gif" alt="" />
                                    {/if}
                                </a>
                            <a href="{$postAction|escape:'htmlall':'UTF-8'}&submit_edit_item&block={$menuitem['id_block']}&item_id={$menuitem['id_item']}" data-toggle="tooltip" class="edit_btn label-tooltip" data-html="true" data-original-title="Edit item"><i class="icon-edit"></i></a>
                            <a href="{$postAction|escape:'htmlall':'UTF-8'}&submit_del_item&item_id={$menuitem['id_item']}" data-toggle="tooltip" class="edit_btn label-tooltip" data-html="true" data-original-title="Delete item" onclick="return confirm('Are you sure delete this Item?')"><i class="icon-remove "></i></a>
                        </span>

                    </li>
                {/foreach}
            </ul>
            {/if}
            </div>
            
        {/foreach}
        </div>
        {/if}
    </div>
    {/foreach}
    {/if}
</div>