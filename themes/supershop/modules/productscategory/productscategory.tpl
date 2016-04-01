{if count($categoryProducts) > 0 && $categoryProducts !== false}
<section class="page-product-box blockproductscategory">
	{*}<h3 class="productscategory_h3 page-product-heading">{$categoryProducts|@count} {l s='Other products in the same category:' mod='ovicproductscategory'}</h3>{*}
    <h3 class="productscategory_h3 page-product-heading">{l s='Products In The Same Category' mod='productscategory'}</h3>        
	<div id="productscategory_list" class="clearfix">
	   {include file="$tpl_dir./product-list.tpl" products=$categoryProducts id='productscategory_list_ul'}
 	</div>
    {if count($categoryProducts) > 4}<a id="productscategory_scroll_right" class="next_slide navigation_btn" title="{l s='Next' mod='ovicproductscategory'}" href="javascript:{ldelim}{rdelim}">{l s='Next' mod='ovicproductscategory'}</a>{/if}
    {if count($categoryProducts) > 4}<a id="productscategory_scroll_left" class="prev_slide navigation_btn" title="{l s='Previous' mod='ovicproductscategory'}" href="javascript:{ldelim}{rdelim}">{l s='Previous' mod='ovicproductscategory'}</a>{/if}
</section>
{/if}