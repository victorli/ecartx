<!DOCTYPE HTML>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7 " lang="{$lang_iso}"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7" lang="{$lang_iso}"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8" lang="{$lang_iso}"><![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9" lang="{$lang_iso}"><![endif]-->
<html lang="{$lang_iso}">
	<head>
		<meta charset="utf-8" />
		<title>{$meta_title|escape:'html':'UTF-8'}</title>
{if isset($meta_description) AND $meta_description}
		<meta name="description" content="{$meta_description|escape:'html':'UTF-8'}" />
{/if}
{if isset($meta_keywords) AND $meta_keywords}
		<meta name="keywords" content="{$meta_keywords|escape:'html':'UTF-8'}" />
{/if}
		<meta name="generator" content="PrestaShop" />
		<meta name="robots" content="{if isset($nobots)}no{/if}index,{if isset($nofollow) && $nofollow}no{/if}follow" />
		<meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}" />
		<link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}" />
        <link rel="stylesheet" href="{$css_dir|escape:'html':'UTF-8'}globalmd.css" type="text/css" media="All" />
		<link rel="stylesheet" href="{$css_dir|escape:'html':'UTF-8'}jquery.mCustomScrollbar.css" type="text/css" media="All" />
		
        
{if isset($css_files)}
	{foreach from=$css_files key=css_uri item=media}
		<link rel="stylesheet" href="{$css_uri|escape:'html':'UTF-8'}" type="text/css" media="{$media|escape:'html':'UTF-8'}" />
	{/foreach}
{/if}
{if isset($js_defer) && !$js_defer && isset($js_files) && isset($js_def)}
	{$js_def}
	{foreach from=$js_files item=js_uri}
	<script type="text/javascript" src="{$js_uri|escape:'html':'UTF-8'}"></script>
	{/foreach}
{/if}
    
	
		{$HOOK_HEADER}
        
		<!--[if IE 8]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
		{if isset($codeCss) && $codeCss|@count > 0}
		<style type="text/css">
			{foreach from=$codeCss item=value}
				{$value}
			{/foreach}
		</style>
		{/if}
	</head>
   
	<body{if isset($page_name)} id="{$page_name|escape:'html':'UTF-8'}"{/if} class="fix-backgroundcolor {if isset($option_class)}{$option_class} {/if}{if isset($page_name)}{$page_name|escape:'html':'UTF-8'}{/if}{if isset($body_classes) && $body_classes|@count} {implode value=$body_classes separator=' '}{/if}{if $hide_left_column} hide-left-column{/if}{if $hide_right_column} hide-right-column{/if}{if isset($content_only) && $content_only} content_only{/if} lang_{$lang_iso}">
	{if !isset($content_only) || !$content_only}
		{if isset($restricted_country_mode) && $restricted_country_mode}
			<div id="restricted-country">
				<p>{l s='You cannot place a new order from your country.'} <span class="bold">{$geolocation_country|escape:'html':'UTF-8'}</span></p>
			</div>
		{/if}
        {if $page_name == 'index' || $page_name == 'product'}{addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}{/if}
        {if ($page_name == 'index' || $page_name == 'product') and isset($comparator_max_item)}
        {addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
        {addJsDef comparator_max_item=$comparator_max_item}
        {/if}
        {if ($page_name != 'category' && $page_name != 'product' && $page_name !='best-sales' && $page_name != 'search' && $page_name != 'manufacturer' && $page_name != 'supplier' && $page_name != 'prices-drop' && $page_name != 'new-products') and isset($compared_products)}{addJsDef comparedProductsIds=$compared_products}{/if}
		<div id="page">
                <div class="header-container">
    				<header id="header">
    					<div class="banner">
    						<div class="container">
    							<div class="row">
    								{hook h="displayBanner"}
    							</div>
    						</div>
    					</div>
    					<div class="nav">
    						<div class="container">
    							<div class="row">
    								<nav>
                                        <div class="div-display-nav">
    										{$HOOK_CMSPOS}
                                            {hook h="displayNav"}
    									</div>
                                    </nav>
    							</div>
    						</div>
    					</div>
    					<div id="top-header">
    						<div class="container">
    							<div class="row">
    								<div id="header_logo">
    									<a href="{$base_dir}" title="{$shop_name|escape:'html':'UTF-8'}">
    										<img class="logo img-responsive" src="{$logo_url}" alt="{$shop_name|escape:'html':'UTF-8'}"{if isset($logo_image_width) && $logo_image_width} width="{$logo_image_width}"{/if}{if isset($logo_image_height) && $logo_image_height} height="{$logo_image_height}"{/if}/>
    									</a>
    								</div>
                                    <div id="enable_mobile_header" class="visible-xs"></div>
    								{if isset($HOOK_TOP)}{$HOOK_TOP}{/if}
    							</div>
    						</div>
    					</div>
    				</header>
    			</div>

<div id="container-home-top">	
    {if $current_option == 4}
        <div class="main-top-menus">
            <div class="container clearfix">
                <div class="row"> 
				   {hook h="displayTopColumn"}                    
				</div>
            </div>
        </div>
        {if $page_name =='index'}
        <div class="container clearfix home-top">
            {hook h='displayHomeTopColumn'}
        </div>			
        {/if}
    {elseif $current_option == 1}
        <div class="clearfix home-top">				
    		<div class="container">
                <div class="row"> 
        		   {hook h="displayTopColumn"}                    
        		</div>
            </div>
            {if $page_name =='index'}
                <div id="displayHomeTopColumn">
                    <div class="container">
                        {hook h='displayHomeTopColumn'}
                    </div>    
                </div>
            {/if}
    	</div>
     {elseif $current_option == 5}
        <div class="clearfix home-top">				
    		<div class="container">
                <div class="row"> 
        		   {hook h="displayTopColumn"}                    
        		</div>
            </div>
            {if $page_name =='index'}
                <div id="displayHomeTopColumn">
                    <div class="container">
                        {hook h='displayHomeTopColumn'}
                    </div>    
                </div>
            {/if}
    	</div>
    {else}
        <div class="container clearfix home-top">				
			<div class="row"> 
			   {hook h="displayTopColumn"}                    
			</div>
            {if $page_name =='index'}
                {hook h='displayHomeTopColumn'}
            {/if}
		</div>
    {/if}
						
</div>					
			<div class="columns-container">
				<div id="columns" class="container">
                    <div id="slider_row" class="row .clearBoth">
						<div id="top_column" class="center_column col-xs-12 col-sm-12">{hook h="displayTopColumn"}</div>
					</div>
					<div class="row">
	{/if}