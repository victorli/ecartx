<!-- Block user information module NAV  -->
<div class="header_user_info">
    {if $is_logged}
    		<a class="header-toggle-call"  href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='View my customer account' mod='blockuserinfo'}" class="account" rel="nofollow"><span>{*$cookie->customer_firstname*} {$cookie->customer_lastname|truncate:10:''|escape:'html':'UTF-8'}</span></a>
    {else}
            <a class="header-toggle-call login" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Log in to your customer account' mod='blockuserinfo'}">
            {l s='My account' mod='blockuserinfo'}
        </a>
    {/if}

    <div class="header-toggle">
        {if $is_logged}
        		<a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='View my customer account' mod='blockuserinfo'}" class="account" rel="nofollow">{l s='My account' mod='blockuserinfo'}</a>
        {else}
                <a class="login" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Log in to your customer account' mod='blockuserinfo'}">
                    {l s='Login' mod='blockuserinfo'}
        	    </a>
        {/if}
        <a href="{$link->getPageLink('products-comparison', true)|escape:'html':'UTF-8'}" title="{l s='My compare' mod='blockuserinfo'}">
            {l s='Compare' mod='blockuserinfo'}
        </a>
        <a href="{$link->getModuleLink('blockwishlist', 'mywishlist', array(), true)|escape:'html':'UTF-8'}"  rel="nofollow" title="{l s='My wishlists' mod='blockuserinfo'}">
            {l s='Wishlists' mod='blockuserinfo'}
        </a>
        {if $is_logged}
            <a href="{$link->getPageLink('index', true, NULL, "mylogout")|escape:'html'}" title="{l s='Sign out' mod='blockuserinfo'}">{l s='Sign out' mod='blockuserinfo'}</a>
        {/if}
    </div>
</div>

<!-- /Block usmodule NAV -->
