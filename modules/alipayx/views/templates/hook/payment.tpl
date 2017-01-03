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
		url:"{$link->getModuleLink('alipayx', 'redirect', ['order_no'=>$order_no], true)|escape:'htmlall':'UTF-8'}",
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
			
				<a href="{$link->getModuleLink('alipayx', 'payment', [], true)|escape:'htmlall':'UTF-8'}" title="{l s='Pay with alipay payment module' mod='alipay'}">
					<img src="{$module_dir|escape:'htmlall':'UTF-8'}/views/img/logo.png" alt="{l s='Pay with alipay payment module' mod='alipayx'}" />
					{l s='Pay with Alipay' mod='alipayx'}
				</a>
			
		</p>
	</div>
</div>
