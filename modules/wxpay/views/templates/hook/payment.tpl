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

<div class="row">
	<div class="col-xs-12 col-md-6">
		<p class="payment_module" id="wxpay_payment_button">
			
				<a href="{$link->getModuleLink('wxpay', 'redirect', ['url1'=>($wxpay_payment_url|escape:'htmlall':'UTF-8')], true)|escape:'htmlall':'UTF-8'}" title="{l s='Pay with my payment module' mod='wxpay'}">
					<img src="{$module_dir|escape:'htmlall':'UTF-8'}/logo.png" alt="{l s='Pay with my payment module' mod='wxpay'}" width="32" height="32" />
					{l s='Pay with WeiXin' mod='wxpay'}
				</a>
			
		</p>
	</div>
</div>
<!-- 
<div class="row">
	<div class="col-xs-12 col-md-12">
		<p class="payment_module" id="wxpay_payment_button">
            <a href="{$wxpay_payment_url|escape:'htmlall':'UTF-8'}" title="{l s='Pay with weixinpay' mod='wxpay'}">
                <span class="label_en">
                    {l s='Pay with WeiXin' mod='wxpay'}<br />
                </span>
                <span>
                    {l s='使用微信安全付款' mod='wxpay'}
                </span>
            </a>
		</p>
	</div>
</div>
 -->
