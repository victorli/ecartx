{if !empty($display_header)}{include file="$tpl_dir./header.tpl" HOOK_HEADER=$HOOK_HEADER}{/if}
{if !empty($template)}{$template}{/if}
{if !empty($display_footer)}{include file="$tpl_dir./footer.tpl"}{/if}