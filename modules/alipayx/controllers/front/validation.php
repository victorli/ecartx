<?php
/**
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
*/

class AlipayxValidationModuleFrontController extends ModuleFrontController
{
    /**
     * This class should be use by your Instant Payment
     * Notification system to validate the order remotely
     */
    public function postProcess()
    {
       $cart = $this->context->cart;
       if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || !$this->module->active)
			Tools::redirect('index.php?controller=order&step=1');
			
		$authorized = false;
		foreach (Module::getPaymentModules() as $module){
			if ($module['name'] == 'alipayx')
			{
				$authorized = true;
				break;
			}
		}
		if (!$authorized)
			die($this->module->l('This payment method is not available.', 'validation'));
			
		$customer = new Customer($cart->id_customer);
		if (!Validate::isLoadedObject($customer))
			Tools::redirect('index.php?controller=order&step=1');
			
		$currency = $this->context->currency;
		$total = (float)$cart->getOrderTotal(true, Cart::BOTH);
		/*$mailVars = array(
			'{bankwire_owner}' => Configuration::get('BANK_WIRE_OWNER'),
			'{bankwire_details}' => nl2br(Configuration::get('BANK_WIRE_DETAILS')),
			'{bankwire_address}' => nl2br(Configuration::get('BANK_WIRE_ADDRESS'))
		);*/
		$mailVars = null;

		if($this->module->validateOrder($cart->id, Configuration::get('AWAITING_ALIPAY_PAYMENT'), $total, $this->module->displayName, NULL, $mailVars, (int)$currency->id, false, $customer->secure_key)){
			
	        //include_once(_PS_MODULE_DIR_.'alipayx/api/loader.php');
	        require_once(_PS_MODULE_DIR_."alipayx/alipay.config.php");
			require_once(_PS_MODULE_DIR_."alipayx/lib/alipay_submit.class.php");
	
	
	        $alipaySubmit = new AlipaySubmit($alipay_config);
	        
	        $lProduct = $cart->getLastProduct();
	        $parameter = array(
			"service"       => $alipay_config['service'],
			"partner"       => $alipay_config['partner'],
			"seller_id"  => $alipay_config['seller_id'],
			"payment_type"	=> $alipay_config['payment_type'],
			"notify_url"	=> $alipay_config['notify_url'],
			"return_url"	=> $alipay_config['return_url'],
			
			"anti_phishing_key"=>$alipay_config['anti_phishing_key'],
			"exter_invoke_ip"=>$alipay_config['exter_invoke_ip'],
			"out_trade_no"	=> $this->module->currentOrder,
			"subject"	=> Configuration::get('PS_SHOP_NAME'),
			"total_fee"	=> $cart->getOrderTotal(),
			"body"	=> $lProduct ? $lProduct['name'] : Configuration::get('PS_SHOP_NAME').$this->module->l('Products'),
			"_input_charset"	=> trim(strtolower($alipay_config['input_charset'])));
	        
	        $url = $alipaySubmit->createUrl($parameter);
			
	        die(Tools::jsonEncode(array('flag'=>'SUCCESS','msg'=>$url,'id_order'=>$parameter['out_trade_no'])));
		}else{
			die(Tools::jsonEncode(array('flag'=>'FAIL','msg'=>'Error to create order.')));
		}
    }
}
