{if isset($orderby) AND isset($orderway)}
<ul class="display hidden-xs">
	{*}<li class="display-title">{l s='View as:'}</li>{*}
    <li class="view_as_grid"><a rel="nofollow" href="#" title="{l s='Grid'}"><i class="icon-th-large"></i>{l s='Grid'}</a></li>
    <li class="view_as_list"><a rel="nofollow" href="#" title="{l s='List'}"><i class="icon-th-list"></i>{l s='List'}</a></li>
</ul>
{/if}