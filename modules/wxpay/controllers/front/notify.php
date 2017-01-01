<?php

class WxpayNotifyModuleFrontController extends ModuleFrontController
{
	public function initContent(){
		parent::initContent();
		
		require_once _PS_MODULE_DIR_.'wxpay/lib/PayNotify.php';
		$pn = new PayNotify();
		$pn->handle(false);
		
		die();
	}
}

