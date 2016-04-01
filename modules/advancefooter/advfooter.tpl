<!-- module advance footer by ovic-->
{if $footers}
    <div id="advancefooter" class=" clearBoth clearfix container-fluid">
    {if $footers|@count > 0}
        {foreach $footers item=row name=footers}
            <div id="footer_row{$row.id_row}" class="clearfix footer_row{if (isset($row.rclass))} {$row.rclass}{/if}">
                <div class="container">
                   <div class="row">
                    {if isset($row.blocks) && $row.blocks|@count > 0}
                        {foreach from=$row.blocks item=block}
                            <div id="block_{$row.id_row}_{$block@iteration}" class="{$block.bclass} advancefooter-block col-sm-{$block.width} col-sx-12 block_{$block@iteration}">
                                {if $block.display_title && $block.title}
                                    <h2 class="block_title">{$block.title}</h2>
                                {/if}
                                {if isset($block.items) && $block.items|@count>0}
                                    <ul>
                                        {foreach $block.items as $item}
                                            <li class="item {$item.type}">
                                                <div class="item_wrapper">
                                                    {$item.html}
                                                </div>
                                            </li>
                                        {/foreach}
                                    </ul>
                                {/if}
                            </div>
                        {/foreach}
                     {/if}
                    </div>
                </div>
            </div>
        {/foreach}
    {/if}
    </div>
{/if}
<!-- /advance footer by ovic-->