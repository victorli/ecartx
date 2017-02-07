{if isset($tabs) AND $tabs}
<div class="sns-pdt-head">
	<div class="sns-pdt-nav">

		<h3>{l s = 'Featured Products' mod='snsproducttabs'}</h3>


		<div class="tab-title">
			<h3 class="nav-tabs pdt-nav">
				{foreach from=$tabs item=tab}
					{assign var="tab_active" value=( isset( $tab.first_select ) ) ?  $tab.first_select : ''}
					<span class="tab {$tab_active}" data-id="{$tab.tab_unique}" data-catid="{$tab.tab_catid}" data-type="{$tab.tab_type}">
						<span class="title-navi"><a href="#" data-toggle="tab">{$tab.tab_name}</a></span>
					</span>
				{/foreach}
			</h3>			
		</div>
	</div>
</div>
{/if}
