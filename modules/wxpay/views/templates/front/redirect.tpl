{*
* 2007-2015 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div>
	<h3>{l s='微信扫描支付' mod='wxpay'}:</h3>
	<ul class="alert alert-info">
			<li>{l s='This action should be used to redirect your customer to the website of your payment processor' mod='wxpay'}.</li>
	</ul>
<!-- 	
	<div class="alert alert-warning">
		{l s='You can redirect your customer with an error message' mod='wxpay'}:
		<a href="{$link->getModuleLink('wxpay', 'redirect', ['action' => 'error'], true)|escape:'htmlall':'UTF-8'}" title="{l s='Look at the error' mod='wxpay'}">
			<strong>{l s='Look at the error message' mod='wxpay'}</strong>
		</a>
	</div>
 -->	
	<div class="alert alert-success">
		{l s='扫描支付' mod='wxpay'}:
		<img alt="模式一扫码支付" src="{$url1|escape:'htmlall':'UTF-8'}" style="width:150px;height:150px;"/>
		
	</div>
</div>
