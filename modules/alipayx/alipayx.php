<?php
header("Content-Type: text/html; charset=utf-8");
if (!defined('_PS_VERSION_')) {
    exit;
}

class Alipayx extends PaymentModule
{
	protected $config_form = false;

    public $confirmation_message = '';
    
public function __construct()
    {
        $this->name = 'alipayx';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.1';
        $this->author = 'ecartx';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('AlipayX');
        $this->description = $this->l('ALIPAY IS THE WORLDS LEADING E-PAYMENT PROVIDER WITH 400 MILLION ACTIVE USERS IN CHINA. It processes 50% of the total online transactions and is the most preferred payment method by Chinese consumers. Configure Alipay and start selling to China now.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall AlipayX?');
		$this->limited_countries = array('CN');
        $this->limited_currencies = array(
        	'CNY'
        );

        $this->orderStatus = array(
			//'BLX_OS_CREATED'=>array('color'=>'Darkred','unremovable'=>1,'name'=>$this->l('Waiting to pay'),'send_email'=>true),
			'AWAITING_ALIPAY_PAYMENT'=>array('color'=>'#4169E1','unremovable'=>1,'name'=>$this->l('Waiting to pay by Alipay'),'send_email'=>true),
			//'BLX_OS_TRADE_CLOSED'=>array('color'=>'LightSalmon','unremovable'=>1,'name'=>$this->l('Trade closed')),
			//'BLX_OS_TRADE_SUCCESS'=>array('color'=>'LimeGreen','unremovable'=>1,'name'=>$this->l('Pay successful'),'invoice'=>true,'paid'=>true),
			//'BLX_OS_TRADE_PENDING'=>array('color'=>'Olive','unremovable'=>1,'name'=>$this->l('Waiting saler to deposit')),
			//'BLX_OS_TRADE_FINISHED'=>array('color'=>'Lime','unremovable'=>1,'name'=>$this->l('Trade finished'),'invoice'=>true,'send_email'=>true,'paid'=>true)
		);
        
    }
    
    public function install()
    {
    	if (extension_loaded('curl') == false)
        {
            $this->_errors[] = $this->l('You have to enable the cURL extension on your server to install this module');
            return false;
        }

        $iso_code = Country::getIsoById(Configuration::get('PS_COUNTRY_DEFAULT'));

        if (in_array($iso_code, $this->limited_countries) == false)
        {
            $this->_errors[] = $this->l('This module is not available in your country');
            return false;
        }

        Configuration::updateValue('ALIPAY_LIVE_MODE', 0);
        Configuration::updateValue('ALIPAY_PARTNER_ID', '2088011173572766');//pid 2088911995662983
        Configuration::updateValue('ALIPAY_SECRETE_KEY', 'xwjrglietxdntfwye41p610rd36ae0yc');//
        Configuration::updateValue('ALIPAY_GATEWAY', 'https://mapi.alipay.com/gateway.do?');
        Configuration::updateValue('ALIPAY_GATEWAY_PROD', 'https://mapi.alipay.com/gateway.do?');
        
        
        

        //include(dirname(__FILE__).'/sql/install.php');

        $admin_order_hook = (_PS_VERSION_ < '1.6' ? 'displayAdminOrder' : 'displayAdminOrderLeft');
        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('payment') &&
            $this->registerHook('paymentReturn') &&
            $this->registerHook($admin_order_hook) &&
            $this->_addOrderStatus() && 
        	$this->_installDb();
    }
    
	public function uninstall()
    {
        Configuration::deleteByName('ALIPAY_LIVE_MODE');
        Configuration::deleteByName('ALIPAY_PARTNER_ID');
        Configuration::deleteByName('ALIPAY_SECRETE_KEY');
        Configuration::deleteByName('ALIPAY_GATEWAY');
        Configuration::deleteByName('ALIPAY_GATEWAY_PROD');

        return parent::uninstall() && $this->_removeOrderStatus() && $this->_uninstallDb();
    }
    
	public function getContent()
    {
        //require_once(dirname(__FILE__).'/AlipayBackEndForm.php');
        /**
         * If values have been submitted in the form, process.
         */
    	if (((bool)Tools::isSubmit('submitAliModule')) == true) {
            $this->postProcess();
        }
        
        $this->context->smarty->assign('module_dir', $this->_path);

        //$output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $this->renderForm(); //$output.$this->renderForm();
    }
    
	protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitAliModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }
    
	protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'ALIPAY_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 4,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-user"></i>',
                        'desc' => $this->l('Enter your SELLERID provided by Alipay'),
                        'name' => 'SELLER_ID',
                        'label' => $this->l('SELLER_ID'),//商户号
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                	'class'	=>	'btn btn-default pull-right button',
                ),
            ),
        );
    }
    
	protected function getConfigFormValues()
    {
        return array(
            'ALIPAY_LIVE_MODE' => Configuration::get('WXPAY_LIVE_MODE'),
            //'WXPAY_APPID' => Configuration::get('WXPAY_APPID', null),
            'SELLER_ID' => Configuration::get('SELLER_ID', null),
        );
    }
    
	protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }
    
	public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }
    
	public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }
    
	public function hookPayment($params)
    {
		
        $this->smarty->assign(
            array(
                'module_dir' => $this->_path,
                //'alipay_payment_url' => $url,
            	//'order_no' => $order_id
            )
        );

        return $this->display(__FILE__, 'views/templates/hook/payment.tpl');
    }
    
	public function build_order_no(){
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
    
	public function getGoodsName($id_cart)
    {
        $cart = new Cart($id_cart);
        $products = $cart->getProducts();
        $goods_name = '';
        foreach ($products as $product) {
            $goods_name .= $product['name'].', ';
        }
        if ($goods_name) {
            return Tools::substr($goods_name, 0, -2);
        }
        return $goods_name;
    }
    
	public function getGoodsDescription()
    {
        return $this->context->shop->name;
    }
    
    private function _installDb(){
    	$sql[] = 'create table if not exists `'._DB_PREFIX_.'alipayx_notify`(
    			`id` int not null auto_increment,
    			`notify_time` datetime,
    			`notify_type` varchar(32),
    			`notify_id` varchar(128),
    			`sign_type` varchar(16),
    			`sign` varchar(256),
    			`out_trade_no` varchar(64),
    			`subject` varchar(256),
    			`payment_type` varchar(4),
    			`trade_no` varchar(64),
    			`trade_status` varchar(32),
    			`gmt_create` datetime,
    			`gmt_payment` datetime,
    			`gmt_close` datetime,
    			`refund_status` varchar(32),
    			`gmt_refund` datetime,
    			`seller_email` varchar(100),
    			`buyer_email` varchar(100),
    			`seller_id` varchar(30),
    			`buyer_id` varchar(30),
    			`price` decimal(10,2),
    			`total_fee` decimal(10,2),
    			`quantity` smallint,
    			`body` varchar(1000),
    			`discount` decimal(10,2),
    			`is_total_fee_adjust` char(1),
    			`use_coupon` char(1),
    			`extra_common_param` varchar(256),
    			`business_scene` varchar(32),
    			primary key(`id`)
    			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';
    	
    	foreach ($sql as $query) {
    		if (Db::getInstance()->execute($query) == false) {
    			PrestaShopLogger::addLog(Db::getInstance()->getMsgError());
    			$this->_errors[] = $this->l('Error to install SQL:'.$query);
    			return false;
    		}
    	}
    	
    	return true;
    }
    
    private function _uninstallDb(){
    	return true;
    }
    
	private function _addOrderStatus() {
		foreach ( $this->orderStatus as $state => $param ) {
			$orderState = new OrderState ( ( int ) Configuration::get ( $state ) );
			if (! Validate::isLoadedObject ( $orderState )) {
				$orderState->color = $param ['color'];
				$orderState->unremovable = isset ( $param ['unremovable'] ) ? $param ['unremovable'] : true;
				$orderState->send_email = isset ( $param ['send_email'] ) ? $param ['send_email'] : false;
				$orderState->invoice = isset ( $param ['invoice'] ) ? $param ['invoice'] : false;
				$orderState->paid = isset ( $param ['paid'] ) ? $param ['paid'] : false;
				$orderState->name = array ();
				foreach ( Language::getLanguages () as $lang )
					$orderState->name [$lang ['id_lang']] = $param ['name'];
				if (! $orderState->add ())
					return false;
				
				if (! Configuration::updateValue ( $state, $orderState->id ))
					return false;
			}
		}
		
		return true;
	}
	private function _removeOrderStatus() {
		foreach ( $this->orderStatus as $state => $param ) {
			if (! Configuration::deleteByName ( $state ))
				return false;
		}
		return true;
	}
}
