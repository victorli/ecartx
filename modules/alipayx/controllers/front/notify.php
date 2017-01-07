<?php
class AlipayxNotifyModuleFrontController extends ModuleFrontController
{
	public function initContent(){
		require_once _PS_MODULE_DIR.'alipayx/lib/alipay_notify.class.php';
		
		$aliNotify = new AlipayNotify();
		if(!$aliNotify->verifyNotify()){
			PrestaShopLogger::addLog('Veriy notify from alipay failed.',1);
			die('FAIL');
		}
		
		$data = $_POST;
		$trade_status = $data['trade_status'];
		if($trade_status === 'TRADE_SUCCESS'){
			$id_order = $data['out_trade_no'];
			$order = new Order((int)$id_order);
			if(is_object($order)){
				if($order->hasBeenPaid())
					return true;
				
				$order->setCurrentState(Configuration::get('PS_OS_PREPARATION'));
			}
		}
	}
	
	private function _storeNotifyResult($data){
		
		$ret = Db::getInstance()->insert(_DB_PREFIX_.'alipayx_notify', $data);
		
		if(!$ret)
			PrestaShopLogger::addLog('Error to insert notify result',1);
		
		return $ret;
	}
}

