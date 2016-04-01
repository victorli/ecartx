<a id="call_search_block" href="javascript:void(0);"><i class="fa fa-search"></i></a>
<div id="search_block_top" >
	<form method="get" action="{$link->getPageLink('search')}" id="searchbox">
		<div class="search_block_top_form">
			<label for="search_query_top"><!-- image on background --></label>
			<input type="hidden" name="controller" value="search" />
			<input type="hidden" name="orderby" value="position" />
			<input type="hidden" name="orderway" value="desc" />
			<input class="search_query light axanBgHoverColor" type="text" id="search_query_top" name="search_query" value="{if isset($smarty.get.search_query)}{$smarty.get.search_query|htmlentities:$ENT_QUOTES:'utf-8'|stripslashes}{else}{l s='Search this site' mod='imagesearchblock'}{/if}" onfocus="if(this.value=='{l s='Search this site' mod='imagesearchblock'}')this.value='';" onblur="if (this.value=='')this.value='{l s='Search this site' mod='imagesearchblock'}'" />
			<input type="submit" name="submit_search" value="" class="search_button" />
                </div>
	</form>
</div>
{include file="$self/imagesearchblock-instantsearch.tpl"}

