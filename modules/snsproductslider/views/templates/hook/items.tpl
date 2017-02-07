{if isset($products) && $products}
	
	<div class="container-block">


		<div class="loading"></div>
		
		<div class="products-grid preload">
			{foreach from=$products item=product name=homeFeaturedProducts}
			<div class="ajax_block_product item {if $smarty.foreach.homeFeaturedProducts.first}first_item{elseif $smarty.foreach.homeFeaturedProducts.last}last_item{/if}">
				{include file="$tpl_dir./product-blockgrid.tpl"}
			</div>
			{/foreach}
		</div>
	</div>


{/if}