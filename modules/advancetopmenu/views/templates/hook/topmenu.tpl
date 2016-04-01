{if isset($MENU)}
<nav id="nav_topmenu" class="navbar navbar-default" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#topmenu">
            <span>{l s='Toggle navigation'}</span>
        </button>
        <a class="navbar-brand" href="#">Menu</a>
    </div>
    <div class="collapse navbar-collapse container" id="topmenu">
        <ul class="nav navbar-nav">
        {foreach $MENU item=mainitem name=mainmenu}
            <li class="level-1{if $mainitem.active == 1 } active{/if}{if isset($mainitem.submenu)} dropdown{/if}{if $mainitem.class|count_characters>0} {$mainitem.class}{/if}">
                <a href="{$mainitem.link}" {if isset($mainitem.submenu)} class="dropdown-toggle" {if $mainitem.active}data-toggle="dropdown"{/if}{/if}>{if $mainitem.icon|count_characters>0}<i class="{$mainitem.icon}"></i>{/if}{$mainitem.title}{if isset($mainitem.submenu)} <b class="caret"></b>{/if}</a>
                {if isset($mainitem.submenu)}
                    {assign var=sub value=$mainitem.submenu}
                        <ul class="container-fluid {if $sub.class|count_characters>0}{$sub.class} {/if}dropdown-menu" role="menu" {if $sub.width}style="width:{$sub.width}px"{/if}>
                        {if isset($sub.blocks) && count($sub.blocks)>0}
                            {foreach $sub.blocks item=block name=blocks}
                                {if isset($block.items) && count($block.items)>0}
                                    <li class="block-container col-sm-{$block.width}{if $block.class|count_characters>0} {$block.class}{/if}">
                                        <ul class="block">
                                        {foreach $block.items item=item name=items}
                                            <li class="level-2 {$item.type}_container {$item.class}">
                                                {if $item.type=='link'}
                                                    <a href="{$item.link}">{if $item.icon|count_characters>0}<i class="{$item.icon}"></i>{/if}{$item.title}</a>
                                                {elseif $item.type=='img' && $item.icon|count_characters>0}
                                                    <a class="{$item.class}" href="{$item.link}">
                                                        <img alt="" src="{$absoluteUrl}img/{$item.icon}" class="img-responsive" />
                                                    </a>
                                                {elseif $item.type=='html'}
                                                    {$item.text}
                                                {/if}
                                            </li>
                                        {/foreach}
                                        </ul>
                                    </li>
                                {/if}
                            {/foreach}
                        {/if}
                        </ul>
                {/if}
            </li>
        {/foreach}
        </ul>
    </div>
</nav>
	<!--/ Menu -->
{/if}