<?php
class AlipayxRedirectModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
    	require_once _PS_MODULE_DIR_.'alipayx/alipay.config.php';
		require_once _PS_MODULE_DIR_.'alipayx/lib/alipay_notify.class.php';
		
		$isAliNotify = false;
		$buyer_email = Tools::getValue('buyer_email');
		$notify_id = Tools::getValue('notify_id');
		if(isset($buyer_email) && !empty($buyer_email) && isset($notify_id) && !empty($notify_id)){
			$isAliNotify = true;
		}
		
		if($isAliNotify){
			$aliNotify = new AlipayNotify($alipay_config);
			if($aliNotify->verifyReturn()){
				Tools::redirect('index.php?controller=history');
			}else{
				PrestaShopLogger::addLog('Bad request:'.$_SERVER['REQUEST_URI'],3);
				die('Bad request detected!');
			}
		}
		
		$flag = Tools::getValue('flag');
		$id_order = Tools::getValue('id_order');
		$order = new Order((int)$id_order);
		if(!isset($flag) || !isset($id_order) || empty($flag) || empty($id_order) ||!is_object($order)){
			PrestaShopLogger::addLog('unexpected request parameters:'.$_SERVER['REQUEST_URI'],2);
			Tools::redirect('index.php?controller=history');
		}
		if($flag == 'UTPS'){ //user think pay success
			if($order->hasBeenPaid()){
				Tools::redirect('index.php?controller=history');
			}
			
			if($this->_hasAlipayNotify($id_order)){
				$order->setCurrentState(Configuration::get('PS_OS_PREPARATION'));
				Tools::redirect('index.php?controller=history');
			}
			
			PrestaShopLogger::addLog('User think paid successfully,but system can not confirm it.',2);
			Tools::redirect('index.php?controller=history');
			
		}elseif($flag == 'UTPF'){ //user think pay failed
			Tools::redirect('index.php?controller=history');
		}
    }
    
    private function _hasAlipayNotify($id_order,$trade_status='TRADE_SUCCESS'){
    	$sql = "select * from "._DB_PREFIX_."alipayx_notify ";
    	$sql .= "where out_trade_no=".$id_order." and trade_status='".$trade_status."'";
    	
    	$result = Db::getInstance()->query($sql);
    	return $result;
    }
}
