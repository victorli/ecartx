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

    {l s='Alipay payment.' mod='alipayx'}

{/capture}



<h1 class="page-heading">

    {l s='Order summary' mod='alipayx'}

</h1>



{assign var='current_step' value='payment'}

{include file="$tpl_dir./order-steps.tpl"}



{if $nbProducts <= 0}

	<p class="alert alert-warning">

        {l s='Your shopping cart is empty.' mod='alipayx'}

    </p>

{else}
        <div class="box cheque-box">

            <h3 class="page-subheading">

                {l s='Alipay payment.' mod='alipayx'}

            </h3>
			
            <p class="cheque-indent">

                <strong class="dark">

                    {l s='You have chosen to pay by alipay payment.' mod='alipayx'} {l s='Here is a short summary of your order:' mod='alipayx'}

                </strong>

            </p>

            <p>
            	 - {l s='The total amount of your order is:' mod='alipayx'}

                <span id="amount" class="price">{displayPrice price=$total}</span><br/>

                - {l s='The number of products:' mod='alipayx'}

                <span id="nbproducts" class="nbproducts">{$nbProducts}</span>
            </p>

            <p>

                - {l s='Please confirm your order by clicking "I confirm my order."' mod='alipayx'}.

            </p>

        </div><!-- .cheque-box -->

        <p class="cart_navigation clearfix" id="cart_navigation">

        	<a 

            class="button-exclusive btn btn-default" 

            href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}">

                <i class="icon-chevron-left"></i>{l s='Other payment methods' mod='alipayx'}

            </a>

            <button 

            class="button btn btn-default button-medium" 

            type="button" onclick="getAlipayRequestUrl();">

                <span>{l s='I confirm my order' mod='alipayx'}<i class="icon-chevron-right right"></i></span>

            </button>

        </p>
        
        <div class="panel panel-primary" id="tipDlgContainer" style="display:none;">
  			<div class="panel-heading">{l s="Pay on the web" mod="alipayx"}</div>
  			<div class="panel-body">
  				<span class="alert alert-warning">{l s="Please finish the paying process on the new open window." mod="alipayx"}</span>
  			</div>
  			<div class="panel-footer">
  				<a class="btn btn-primary" href="./order-history">{l s="Finished" mod="alipayx"}</a>
  				<a class="btn btn-default">{l s="Error to pay" mod="alipayx"}</a>
  			</div>
		</div>

{/if}

<script type="text/javascript">

function getAlipayRequestUrl(){
	$.ajax({
		type : 'POST',
		url	 : baseDir + 'module/alipayx/validation',
		dataType : 'json',
		success : function(json){
			if(json.flag == 'SUCCESS'){
				var newPage = window.open('about:blank');
				newPage.location.href = msg;
				$.fancybox('#tipDlgContainer');
			}else{
				alert(json.msg);
			}
		}
	});
}
</script>

