<?php


//use wxpay\api\PayNotify;

class WxpayNotifyModuleFrontController extends ModuleFrontController
{
	public function postProcess()
	{

		$cart = $this->context->cart;
        $cart_id = $cart->id;//Tools::getValue('cart_id');
        $secure_key = $cart->secure_key;

        //$cart = new Cart((int)$cart_id);
        $customer = new Customer((int)$cart->id_customer);

        /**
         * Since it's an example we are validating the order right here,
         * You should not do it this way in your own module.
         */
        $payment_status = Configuration::get('PS_OS_PAYMENT'); // Default value for a payment that succeed.
        $message = null; // You can add a comment directly into the order so the merchant will see it in the BO.

        /**
         * Converting cart into a valid order
         */

        $module_name = $this->module->displayName;
        $currency_id = (int)Context::getContext()->currency->id;

        
		require_once(dirname(__FILE__).'/../../lib/loader.php');
		//require_once _PS_MODULE_DIR_.'wxpay/api/paynotify.api.php';
		$notify = new PayNotify();
		$notify->Handle(false);
//		$this->module->setRef('QSKTXHGRT');
//		$this->module->validateOrder($cart_id, $payment_status, $cart->getOrderTotal(), $module_name, $message, array(), $currency_id, false, $secure_key);
//				$module_id = $this->module->id;
//            	Tools::redirect('index.php?controller=order-confirmation&id_cart='.$cart_id.'&id_module='.$module_id.'&id_order='.$order_id.'&key='.$secure_key);
		/**  **/
//		if($notify->checkSign() == TRUE)
//		{
			if ($notify->GetReturn_code() == "FAIL") {
				//此处应该更新一下订单状态，商户自行增删操作
				//$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
				$this->errors[] = $this->module->l($notify->GetReturn_msg());
            	return $this->setTemplate('error.tpl');
			}
			elseif($notify->GetResult_code() == "FAIL"){
				//此处应该更新一下订单状态，商户自行增删操作
				//$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
				$this->errors[] = $this->module->l($notify->GetReturn_msg());
            	return $this->setTemplate('error.tpl');
			}
			else{
				$this->module->setRef($notify->GetOut_trade_no());//获取微信支付成功后返回的订单号
				//$this->module->$ref = $ref;
				//修改订单状态
				$this->module->validateOrder($cart_id, $payment_status, $cart->getOrderTotal(), $module_name, $message, array(), $currency_id, false, $secure_key);
				$module_id = $this->module->id;
            	Tools::redirect('index.php?controller=order-confirmation&id_cart='.$cart_id.'&id_module='.$module_id.'&id_order='.$order_id.'&key='.$secure_key);
				//此处应该更新一下订单状态，商户自行增删操作
				//$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
			}
			
			
//		}
        
       
	}
	
//	public function getReference(){
//		$sql = 'SELECT FROM '._DB_PREFIX_.'wxpay WHERE order_no = 0';
//		return Db::getInstance()->execute($sql);
//
//	}
	
	public function notifyHandler()
	{
//		require_once "../lib/WxPay.Api.php";
//		require_once '../lib/WxPay.Notify.php';
		$notify = new PayNotify();
		$notify->Handle(false);
		
		if($notify->checkSign() == TRUE)
		{
			if ($notify->data["return_code"] == "FAIL") {
				//此处应该更新一下订单状态，商户自行增删操作
				//$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
			}
			elseif($notify->data["result_code"] == "FAIL"){
				//此处应该更新一下订单状态，商户自行增删操作
				//$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
			}
			else{
				//此处应该更新一下订单状态，商户自行增删操作
				//$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
			}
			
			//商户自行增加处理流程,
			//例如：更新订单状态
			//例如：数据库操作
			//例如：推送支付完成信息
		}
	}
}

