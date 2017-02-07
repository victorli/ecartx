<div id="search_block_top">
    <form method="get" action="{$link->getPageLink('search')}" id="searchbox" class="form-search">
        <div class="search_block_top_form">
            <input type="hidden" name="controller" value="search" />
            <input type="hidden" name="orderby" value="position" />
            <input type="hidden" name="orderway" value="desc" />
            <input class="search_query" type="text" id="search_query_top" name="search_query" placeholder="{l s='Search' mod='snsquicksearch'}" />
			{* {$cats_html} *}
			<button class="button btn btn-default button-search" value="search" name="submit_search" type="submit"></button>
        </div>
    </form>
    {include file="./snsquicksearch-instantsearch.tpl"}
</div>


