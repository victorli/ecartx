{if $customTags && $customTags|@count > 0}
    <div id="tags_block_footer">
        {foreach from=$customTags item=group name=groupLoop}
            <p class="category-tags">
                <span class="tags-title bluemess" style="background: {$group.background}; color: {$group.color}">
                    {$group.name}
                </span>
                <span class="corner-icon bluemess" style="border-left-color:{$group.background}"></span>
                {if $group.tags && $group.tags|@count >0}
					<span class="inner-tags">
                    {foreach from=$group.tags item=tag name=tagLoop}
                        <a href="{$tag.link}">{$tag.title}</a>
                    {/foreach}
					</span>
                {/if}                
            </p>    
        {/foreach}
    </div>
{/if}