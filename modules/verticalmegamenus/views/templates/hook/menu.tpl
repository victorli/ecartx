<h4 class="title">{$moduleName}<span data-target="#navbarCollapse-{$moduleId}" data-toggle="collapse" class="icon-reorder pull-right"></span></h4>
<div id="navbarCollapse-{$moduleId}" class="collapse vertical-menu-content">
    <ul class="megamenus-ul ">
        {if isset($verticalMenus) && $verticalMenus}            
            {foreach from=$verticalMenus item=data name=ojb}
                {if $data.group_content}
                    {if $data.iconPath}
                        <li class="parent dropdown">
                            <i class="icon-angle-down dropdown-toggle hidden-lg hidden-md hidden-sm pull-right" data-toggle="dropdown"></i>
                            <a class="parent vertical-parent " title="{$data.title}" href="{$data.link}" data-link="{$data.link}" >
                                <img class="parent-icon" alt="{$data.title}" src="{$data.iconPath}" /><span>{$data.title}</span>
                            </a>
                            {$data.group_content}
                        </li>
                    {else}
                        <li class="parent dropdown">
                            <i class="icon-angle-down dropdown-toggle hidden-lg hidden-md hidden-sm pull-right" data-toggle="dropdown"></i>
                            <a class="parent vertical-parent no-icon" title="{$data.title}" href="{$data.link}" data-link="{$data.link}" >
                                <span>{$data.title}</span>
                            </a>                        
                            {$data.group_content}
                        </li>
                    {/if}
                {else}
                    {if $data.iconPath}
                        <li class="dropdown">
                            <i class="icon-angle-down dropdown-toggle hidden-lg hidden-md hidden-sm pull-right" data-toggle="dropdown"></i>
                            <a class="parent" title="{$data.title}" href="{$data.link}" >
                                <img class="parent-icon" alt="{$data.title}" src="{$data.iconPath}" />
                                <span>{$data.title}</span>
                            </a>
                        </li>
                    {else}
                        <li class="dropdown">
                            <i class="icon-angle-down dropdown-toggle hidden-lg hidden-md hidden-sm pull-right" data-toggle="dropdown"></i>
                            <a class="parent" title="{$data.title}" href="{$data.link}" >
                                <span>{$data.title}</span>
                            </a>
                        </li>
                    {/if}                    
                {/if}                    
            {/foreach}    
               
        {/if}    
    </ul>
</div>
