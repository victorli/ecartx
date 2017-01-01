<?php
require_once 'WxPay.Notify.php';

class PayNotify extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		if($data['return_code'] !== 'SUCCESS')
			return false;
		
		$order_id = $data['out_trade_no'];
		$order = new Order((int)$order_id);
		if(is_object($order) && $order->id_order == $data['out_trade_no']){
			if($order->hasBeenPaid())
				return true;
			
			$oh = new OrderHistory();
			$oh->changeIdOrderState(OrderState::FLAG_PAID,$order_id);
			
			Wxpay::logNotify(Order::getCartIdStatic($order_id), $data);
		}
		return true;
	}
}