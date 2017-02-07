{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<!DOCTYPE HTML>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7 " lang="{$lang_iso}"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7" lang="{$lang_iso}"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8" lang="{$lang_iso}"><![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9" lang="{$lang_iso}"><![endif]-->
<html lang="{$lang_iso}">
	<head>
		{if isset($THEME_INFO)}<!-- {$THEME_INFO} -->{/if}
		
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
	<script type="text/javascript" src="{$js_dir}sns-script.js"></script>

		{$HOOK_HEADER}
			
		{$SNS_NAZ_STYLE}
		{$SNS_NAZ_SCRIPT}
		<!--[if IE 8]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
		{* 
		{literal}
		<script>
			$(document).ready(function(){
				$('#snscpl_themecolor').on('change', function(){
					$('style#snscpl_themecolor').remove();
					var _style = '<style id="snscpl_themecolor">#sns_header {background: '+$(this).val()+';}</style>';
					$('head').append(_style);
				});
			});
		</script>
		{/literal}
		 *}
	</head>
	<body{if isset($page_name)} id="{$page_name|escape:'html':'UTF-8'}"{/if} 
		class="sns-body 
		
		{if $SNS_NAZ_HOMEPAGE eq 1}home-default {/if}
		{if $SNS_NAZ_LAYOUTTYPE eq 2}boxed-layout {/if}

		{if isset($page_name)}{$page_name|escape:'html':'UTF-8'}{/if}{if isset($body_classes) && $body_classes|@count} {implode value=$body_classes separator=' '}{/if}{if $hide_left_column} hide-left-column{/if}{if $hide_right_column} hide-right-column{/if}{if isset($content_only) && $content_only} content_only{/if} lang_{$lang_iso}"
			
		{if isset($SNS_NAZ_CUSTOMBG) && {$SNS_NAZ_CUSTOMBG} == 1 && {$SNS_NAZ_LAYOUTTYPE} == 2} style="background:url('{$SNS_NAZ_BGBODY}') no-repeat fixed top center !important;" {/if} 
		>



	{if !isset($content_only) || !$content_only}
		{if isset($restricted_country_mode) && $restricted_country_mode}
			<div id="restricted-country">
				<p>{l s='You cannot place a new order from your country.'} <span class="bold">{$geolocation_country|escape:'html':'UTF-8'}</span></p>
			</div>
		{/if}
		<div id="overlay-bg">
			
		<div id="sns_wrapper">
			<div id="sns_header_top" class="wrap">
				<div class="container">
					{if isset($SNS_NAZ_WELCOMEMESS) && $SNS_NAZ_WELCOMEMESS}
						<div class="header-col-left pull-left col_left">
							<p class="welcome-msg"><span class="msg">{$SNS_NAZ_WELCOMEMESS}</span>

								{if $logged}
							        <a  href="{$link->getPageLink('index', true, NULL, "mylogout")}">{l s='Log Out'}</a>
							    {else}
							        <a  href="{$link->getPageLink('my-account', true)}">{l s='Login'}</a>
							    {/if}

							    {if $logged}
							
								{else}
									{l s='or'}
							        <a href="{$link->getPageLink('my-account', true)}">{l s='Signup'}</a>
							    {/if}

							</p>				
						</div>
					{/if}

					<div class="header-col-right pull-right col_right">
							<div class="header-tools setting header-block">
							
								<div class="tongle"><i data-icon="&#xe0de;"></i><span>{l s='Setting'}</span><i data-icon="&#x33;"></i></div>
								<div class="content">
									{hook h="displayNav"}
								</div>

							</div>

							<div class="myaccount header-block">
								<div class="tongle"><i data-icon="&#xe08a;"></i><span>{l s='My Account'}</span><i data-icon="&#x33;"></i></div>
								<div class="content">
								   <ul class="links">
								        <li class="first" ><a href="{$link->getPageLink('my-account', true)}" title="{l s='My Account'}" class="top-link-myaccount">{l s='My Account'}</a></li>
								        <li ><a href="{$WISHLIST_LINK}" title="{l s='Wishlist'}" class="top-link-wishlist">{l s='Wishlist'}</a></li>
								        <li ><a href="{$link->getPageLink($ORDER_PROCESS, true)}" title="{l s='My Cart'}" class="top-link-cart">{l s='My Cart'}</a></li>
								        <li ><a href="{$link->getPageLink($ORDER_PROCESS, true)}" title="{l s='Checkout'}" class="top-link-checkout">{l s='Checkout'}</a></li>
									    {if $logged}
									        <li class=" last" ><a href="{$link->getPageLink('index', true, NULL, "mylogout")}" title="{l s='Log me out'}" class="top-link-logout">{l s='Log out'}</a></li>
									    {else}
									        <li class=" last" ><a href="{$link->getPageLink('my-account', true)}" title="{l s='Log In'}" class="top-link-login">{l s='Log In'}</a></li>
									    {/if}
								    </ul>
								</div>
							</div>
							{if isset($HOOK_TOP)}{$HOOK_TOP}{/if}
					</div>
				</div>
			</div>

			<div id="sns_menu" class="wrap">
				<div class="container">
					<div class="inner">
						<div class="header-left">
							<h1 id="logo">
								

							 {if $SNS_NAZ_CUSTOMLOGO && $SNS_NAZ_CUSTOMLOGO_URL}
								<a href="{$base_dir}" title="{$shop_name|escape:'html':'UTF-8'}">
									<img class=" " src="{$SNS_NAZ_CUSTOMLOGO_URL}" alt="{$shop_name|escape:'html':'UTF-8'}" />
								</a>
								{else}
								<!-- <a href="{$base_dir}" title="{$shop_name|escape:'html':'UTF-8'}">
									<img class=" " src="{$logo_url}" alt="{$shop_name|escape:'html':'UTF-8'}" />
								</a> -->
							{/if} 	
												
							</h1>
						</div>

						<div class="header-right">
							<div id="sns_mainnav">
								{hook h="displayMainMenu"}
							</div>
							{hook h="displayHeaderSearchBlock"}
						</div>

					</div>
				</div>
			</div>
			{*

			<!-- {if isset($category) && $page_name == 'category'}
				{if $category->id AND $category->active}
					<div class="category-image"{if $category->id_image} style="background:url({$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category_default')|escape:'html':'UTF-8'}) right center no-repeat; background-size:cover; min-height:310px;"{/if}></div>
				{/if}
			{/if} -->
			*}
		
			{if $page_name !='index' && $page_name !='pagenotfound'}
				<div class="wrap" id="sns_breadcrumbs">
		            <div class="container">
						{include file="$tpl_dir./breadcrumb.tpl"}
		            </div>
		        </div>
			{/if}
			
			{if $page_name == 'index'}
				{include file="$tpl_dir./index-beforecontent.tpl"}
			{/if}
			<div id="sns_content" class="wrap">
				<div id="columns" class="container">
					<div class="row">

						{if isset($left_column_size) && !empty($left_column_size)}
						<div id="sns_left" class="column col-xs-12 col-md-{$left_column_size|intval}">
							<div class="wrap-in">
								{hook h="displaySNSNavigation"}
								{$HOOK_LEFT_COLUMN} 
							</div>
						</div>

						{elseif isset($SNS_NAZ_HOMEPAGE) && $SNS_NAZ_HOMEPAGE == 1 && $page_name =='index' }
						<div id="sns_left" class="column col-xs-12 col-md-3">
							<div class="wrap-in">
								{hook h="displaySNSNavigation"} 
								{hook h="leftColumn"} 
							</div>
						</div>

						{/if}



						{if isset($left_column_size) && isset($right_column_size)}{assign var='cols' value=(12 - $left_column_size - $right_column_size)}{else}{assign var='cols' value=12}{/if}


						{if isset($SNS_NAZ_HOMEPAGE) &&  $SNS_NAZ_HOMEPAGE == 1  && $page_name =='index'}

						<div id="sns_main" class="center_column col-xs-12 col-md-9">
		                        <div id="sns_maintop"></div>
		                        <div id="sns_mainmidle">
		                        	<div id="center_column">
                		{else}



						<div id="sns_main" class="center_column col-xs-12 col-md-{$cols|intval}">
	                        <div id="sns_maintop"></div>
	                        <div id="sns_mainmidle">
	                        	<div id="center_column">
						{/if}

						{/if}