<?php 
//modules/wxpay/ajax.php
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');

$id_order = Tools::getValue('id_order');

if(!isset($id_order) || empty($id_order))
	die(Tools::jsonEncode(array('flag'=>'FAIL','msg'=>'order id is not valid')));

$order = new Order((int)$id_order);
if(!is_object($order))
	die(Tools::jsonEncode(array('flag'=>'FAIL','msg'=>'Order does not exist')));

if($order->hasBeenPaid()){
	die(Tools::jsonEncode(array('flag'=>'SUCCESS','msg'=>'Paid successfully')));
}else{
	die(Tools::jsonEncode(array('flag'=>'FAIL','msg'=>'Waiting for the notify result')));
}

die();
?>