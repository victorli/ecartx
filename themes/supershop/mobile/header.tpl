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
   
	<body>
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
    				</header>
    			</div>

		<div id="container-home-top">	
        <div class="container clearfix home-top">				
			<div class="row"> 
			   {hook h="displayTopColumn"}                    
			</div>
            {if $page_name =='index'}
                {hook h='displayHomeTopColumn'}
            {/if}
		</div>
						
		</div>					