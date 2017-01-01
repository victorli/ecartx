<?php

class WxpayNotifyModuleFrontController extends ModuleFrontController
{
	public function initContent(){
		parent::initContent();
		
		$pn = new PayNotify();
		$pn->handle(false);
		
		die();
	}
}

