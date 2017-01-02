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

class WxpayValidationModuleFrontController extends ModuleFrontController
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
		foreach (Module::getPaymentModules() as $module)
			if ($module['name'] == 'wxpay')
			{
				$authorized = true;
				break;
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

		if($this->module->validateOrder($cart->id, Configuration::get('AWAITING_WEIXIN_PAYMENT'), $total, $this->module->displayName, NULL, $mailVars, (int)$currency->id, false, $customer->secure_key)){
			require_once _PS_MODULE_DIR_.'wxpay/lib/WxPay.NativePay.php';
			
			$notify = new NativePay();
			
	        $input = new WxPayUnifiedOrder();
	        $input->SetAppid(Configuration::get('WXPAY_APPID'));
	        $input->SetMch_id(Configuration::get('WXPAY_MCHID'));
	        $input->SetDevice_info('WEB');
	        $input->SetBody(Configuration::get('PS_SHOP_NAME'));
			$input->SetAttach("test");//using for reference
			$input->SetOut_trade_no($this->module->currentOrder);
			$input->SetTotal_fee($cart->getOrderTotal()*100);
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 1800));
			$input->SetGoods_tag("test");
			$input->SetNotify_url($this->context->link->getModuleLink('wxpay','notify'));
			$input->SetTrade_type("NATIVE");
			$input->SetProduct_id($this->module->currentOrder);
			$result = $notify->GetPayUrl($input);
			
			Wxpay::logUnifiedOrder($cart, $input->getValues(), $result);
			
			//if errors occured
			if($result['return_code'] !== 'SUCCESS'){
				$this->context->smarty->assign(array(
					'err_msg' => $result['return_msg']
				));
			}else{
				$url = "http://paysdk.weixin.qq.com/example/qrcode.php?data=";
				$url .= urldecode($result['code_url']);
				
				$this->context->smarty->assign(array(
					'qr_url' => $url,
					'readme_img_url' => Media::getMediaPath(_PS_MODULE_DIR_.'wxpay/views/img/readme.png'),
					'id_order' => $this->module->currentOrder,
					'success_msg' => '<span class="alert alert-success">'.$this->_l('Congratulations, you have paid the order successfully.').'</span>',
				));
			}
		}

		return $this->setTemplate('confirmation.tpl');
    }
}
