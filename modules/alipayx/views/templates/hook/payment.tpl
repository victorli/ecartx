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
<script type="text/javascript">
function alipay(){
	$.ajax({
		url:"{$link->getModuleLink('ali', 'redirect', ['order_no'=>$order_no], true)|escape:'htmlall':'UTF-8'}",
		data:{},
		type:'post',
		dataType:'json',
		success:function(data) {
			//$('#pay').click();
			document.getElementById("pay").click(); 
			//window.location.href="{$alipay_payment_url|escape:'htmlall':'UTF-8'}";
		},
		
	});
}
</script>
<div class="row">
	<div class="col-xs-12 col-md-12">
		<p class="payment_module" id="alipay_payment_button">
		<a href="{$alipay_payment_url|escape:'htmlall':'UTF-8'}" id="pay" style="display:none;"/>
            <!-- <a href="{$alipay_payment_url|escape:'htmlall':'UTF-8'}" title="{l s='Pay with Alipay' mod='alipay'}"> -->
            <a href="#" 
            title="{l s='Pay with Alipay' mod='ali'}" onclick="alipay()">
            
                <span class="label_en">
                    {l s='Pay with Alipay' mod='ali'}<br />
                </span>
                <span>
                    {l s='使用支付宝安全付款' mod='ali'}
                </span>
            </a>
		</p>
	</div>
</div>
