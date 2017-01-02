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
{capture name=path}

    {l s='Weixin payment.' mod='wxpay'}

{/capture}



<h1 class="page-heading">

    {l s='Scan qrcode to pay' mod='wxpay'}

</h1>



{assign var='current_step' value='payment'}

{include file="$tpl_dir./order-steps.tpl"}

<div class="panel panel-default">
	<div class="panel-body" id="payResultContainer">
	{if $err_msg}
		<div class="alert alert-warning">
			{l s='Error occured when accessing weixin payment gateway' mod='wxpay'}
			{$err_msg}
		</div>
	{else}
			<img src="{$qr_url|escape:'htmlall':'UTF-8'}" style="width:259px;height:259px;border:1px solid gray;"/>
			<img src="{$readme_img_url|escape:'htmlall':'UTF-8'}" style="display:block;"/>
	{/if}
	</div>
</div>

{if !$err_msg}
<script type="text/javascript">
var id_order = '{$id_order}';
var success_msg = '{$success_msg}';
var timer = null;

{literal}
function checkPaymentResult(){
	$.ajax({
		type : 'POST',
		url	 : baseDir + 'modules/wxpay/ajax.php',
		data : 'id_order='+id_order,
		dataType : 'json',
		success : function(json){
			if(json.flag == 'SUCCESS'){
				$('#payResultContainer').html(success_msg);
				window.clearInterval(timer);
			}else{
				
			}
		}
	});
}

timer = window.setInterval('checkPaymentResult()',2*1000);
{/literal}
</script>
{/if}
