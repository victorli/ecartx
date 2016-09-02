<?php




class AliNotifyModuleFrontController extends ModuleFrontController
{
	public function postProcess()
	{

		
         
        $payment_status = Configuration::get('PS_OS_PAYMENT'); // Default value for a payment that succeed.
        
        
		require_once(dirname(__FILE__).'/../../lib/log.php');
		require_once(_PS_MODULE_DIR_."ali/alipay.config.php");
		require_once(dirname(__FILE__)."/../../lib/alipay_notify.class.php");
		//初始化日志
		$logHandler= new CLogFileHandler("/var/www/wx/modules/wxpay/logs/".date('Y-m-d').'.log');
		$log = Log::Init($logHandler, 15);
		//require_once _PS_MODULE_DIR_.'wxpay/api/paynotify.api.php';
		
		
		
		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		
		if($verify_result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代
		
			
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			
		    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
			
			//商户订单号
		
			$out_trade_no = $_POST['out_trade_no'];
		
			//支付宝交易号
		
			$trade_no = $_POST['trade_no'];
		
			//交易状态
			$trade_status = $_POST['trade_status'];
			
			$ref = $out_trade_no;
			$this->module->setRef($ref);//获取alipay成功后返回的订单号
			//$this->module->$ref = $ref;
			//修改订单状态
			$payment_status = Configuration::get('PS_OS_PAYMENT');
			$sql = "UPDATE "._DB_PREFIX_."orders SET current_state=".$payment_status." WHERE reference='".$ref."'";
			$res = Db::getInstance()->execute($sql);
			
			$sql = "UPDATE "._DB_PREFIX_."order_history SET id_order_state=".$payment_status." 
			WHERE id_order=(SELECT id_order FROM "._DB_PREFIX_."orders WHERE reference='".$ref."')";
			$res = Db::getInstance()->execute($sql);
		
		
		    if($_POST['trade_status'] == 'TRADE_FINISHED') {
				//判断该笔订单是否在商户网站中已经做过处理
					//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
					//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
					//如果有做过处理，不执行商户的业务程序
						
				//注意：
				//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
		
		        //调试用，写文本函数记录程序运行情况是否正常
		        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		    }
		    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
				//判断该笔订单是否在商户网站中已经做过处理
					//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
					//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
					//如果有做过处理，不执行商户的业务程序
						
				//注意：
				//付款完成后，支付宝系统发送该交易状态通知
		
		        //调试用，写文本函数记录程序运行情况是否正常
		        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		    }
		
			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
		        
			echo "success";		//请不要修改或删除
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else {
			Log::DEBUG("【通信出错】:\n".$verify_result."\n");
		    //验证失败
		    echo "fail";
		
		    //调试用，写文本函数记录程序运行情况是否正常
		    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}

        
       
	}
	
	
	
}

