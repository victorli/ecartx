<!-- block seach mobile -->
{if isset($hook_mobile)}
<div class="input_search" data-role="fieldcontain">
	<form method="get" action="{$link->getPageLink('search', true)|escape:'html'}" id="searchbox">
		<input type="hidden" name="controller" value="search" />
		<input type="hidden" name="orderby" value="position" />
		<input type="hidden" name="orderway" value="desc" />
		<input class="search_query" type="search" id="search_query_top" name="search_query" placeholder="{l s='Search' mod='categorysearch'}" value="{$search_query|escape:'html':'UTF-8'|stripslashes}" />
	</form>
</div>
{else}
<!-- Block search module TOP -->

<div id="search_block_top" class="clearfix">
<form id="searchbox" method="get" action="{$link->getModuleLink('categorysearch', 'catesearch', array(), true)|addslashes}" >
        <input type="hidden" name="fc" value="module" />
        <input type="hidden" name="module" value="categorysearch" />
		<input type="hidden" name="controller" value="catesearch" />
		<input type="hidden" name="orderby" value="position" />
		<input type="hidden" name="orderway" value="desc" />
        <select id="search_category" name="search_category" class="form-control">
            <option value="all">{l s='All Categories' mod='categorysearch'}</option>
            {$search_category}
        </select>
		<input class="search_query form-control" type="text" id="search_query_top" name="search_query" placeholder="{l s='Enter Your Keyword...' mod='categorysearch'}" value="{$search_query|escape:'htmlall':'UTF-8'|stripslashes}" />
		<button type="submit" name="submit_search" class="btn btn-default button-search">
			<span>{l s='Search' mod='categorysearch'}</span>
		</button>
	</form>
</div>
{include file="$self/categorysearch-instantsearch.tpl"}
{/if}
<!-- /Block search module TOP -->
