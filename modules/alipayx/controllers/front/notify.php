<?php
class AlipayxNotifyModuleFrontController extends ModuleFrontController
{
	public function initContent(){
		parent::initContent();
		
		require_once _PS_MODULE_DIR_.'alipayx/alipay.config.php';
		require_once _PS_MODULE_DIR_.'alipayx/lib/alipay_notify.class.php';
		
		PrestaShopLogger::addLog('flag-1',1);
		$aliNotify = new AlipayNotify($alipay_config);
		if(!$aliNotify->verifyNotify()){
			PrestaShopLogger::addLog('Veriy notify from alipay failed.',3);
			die('FAIL');
		}
		PrestaShopLogger::addLog('flag-2',1);
		$data = $_POST;
		$this->_storeNotifyResult($data);
		PrestaShopLogger::addLog('flag-5',1);
		$trade_status = $data['trade_status'];
		if($trade_status == 'TRADE_SUCCESS'){
			$id_order = $data['out_trade_no'];
			$order = new Order((int)$id_order);
			PrestaShopLogger::addLog('flag-6',1);
			if(is_object($order)){
				if($order->hasBeenPaid()){
					PrestaShopLogger::addLog('flag-7',1);
					die('success');
				}
				PrestaShopLogger::addLog('flag-8',1);
				$order->setCurrentState(Configuration::get('PS_OS_PREPARATION'));
				PrestaShopLogger::addLog('flag-9',1);
			}
		}
		PrestaShopLogger::addLog('flag-10',1);
		die('success');
	}
	
	private function _storeNotifyResult($data){
		PrestaShopLogger::addLog('flag-3',1);
		$ret = Db::getInstance()->insert('alipayx_notify', $data);
		PrestaShopLogger::addLog('flag-4',1);
		if(!$ret)
			PrestaShopLogger::addLog('Error to insert notify result:'.Db::getInstance()->getMsgError(),3);
		
		return $ret;
	}
}

