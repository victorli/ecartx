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
//        $this->module_key = '4661b6e2596bc617243143e7878f17e2';

        parent::__construct();

        $this->displayName = $this->l('AlipayX');
        $this->description = $this->l('ALIPAY IS THE WORLDS LEADING E-PAYMENT PROVIDER WITH 400 MILLION ACTIVE USERS IN CHINA. It processes 50% of the total online transactions and is the most preferred payment method by Chinese consumers. Configure Alipay and start selling to China now.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall AlipayX?');
		$this->limited_countries = array('CN');
        $this->limited_currencies = array(
        	'CNY'
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
            $this->registerHook($admin_order_hook);
    }
    
	public function uninstall()
    {
        Configuration::deleteByName('ALIPAY_LIVE_MODE');
        Configuration::deleteByName('ALIPAY_PARTNER_ID');
        Configuration::deleteByName('ALIPAY_SECRETE_KEY');
        Configuration::deleteByName('ALIPAY_GATEWAY');
        Configuration::deleteByName('ALIPAY_GATEWAY_PROD');

        //include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
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
	header("Content-type:text/html;charset=utf-8");
        include_once(_PS_MODULE_DIR_.'alipayx/api/loader.php');
        require_once(_PS_MODULE_DIR_."alipayx/alipay.config.php");
		require_once(_PS_MODULE_DIR_."alipayx/lib/alipay_submit.class.php");
		
		//$cart = $params['cart'];


        $currency_id = $params['cart']->id_currency;
        $currency = new Currency((int)$currency_id);
        $cart = new Cart($params['cart']->id);
        if (!ValidateCore::isLoadedObject($cart)) {
            return false;
        }
        if (in_array($currency->iso_code, $this->limited_currencies) == false) {
            return false;
        }
        $service = Configuration::get('ALIPAY_SERVICE_PAYMENT');
        $credentials = AlipayTools::getCredentials($service, false);

        //$alipayapi = new AlipayApi($credentials);
        $alipaySubmit = new AlipaySubmit($alipay_config);
		//$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
		//echo $html_text;//
		
//        $alipayapi->setReturnUrl($this->getReturnUrl($cart->secure_key, $cart->id));
//        $alipayapi->setNotifyUrl($this->getNotifyUrl($cart->secure_key, $cart->id));
//        $alipayapi->setCharset('UTF-8');
//        date_default_timezone_set('Asia/Shanghai');//Asia/Hong_Kong
		do {
                $reference = Order::generateReference();
            } while (Order::getByReference($reference)->count());
        
        $order_id = $reference;
        $payment_request = new PaymentRequest();
        $payment_request->setCurrency($currency->iso_code);
        $payment_request->setPartnerTransactionId(date('YmdHis').$cart->id);
        $payment_request->setGoodsDescription($this->getGoodsDescription());
        $payment_request->setGoodsName($this->getGoodsName($cart->id));
        //$payment_request->setOrderGmtCreate(date('Y-m-d H:i:s'));
        $payment_request->setOrderGmtCreate($order_id);
        $payment_request->setOrderValidTime(21600);
        $payment_request->setTotalFee($cart->getOrderTotal());
        
        $parameter = array(
		"service"       => $alipay_config['service'],
		"partner"       => $alipay_config['partner'],
		"seller_id"  => $alipay_config['seller_id'],
		"payment_type"	=> $alipay_config['payment_type'],
		"notify_url"	=> $alipay_config['notify_url'],
		"return_url"	=> $alipay_config['return_url'],
		
		"anti_phishing_key"=>$alipay_config['anti_phishing_key'],
		"exter_invoke_ip"=>$alipay_config['exter_invoke_ip'],
		"out_trade_no"	=> $order_id,
		"subject"	=> '订单号：'.$order_id,
		"total_fee"	=> $payment_request->getTotalFee(),
		"body"	=> $payment_request->getGoodsDescription(),
		"_input_charset"	=> trim(strtolower($alipay_config['input_charset'])));
        
      //$alipayapi->paramSort($payment_request);
      //$alipaySubmit->buildRequestForm($parameter, "get", "ok");
        $url = $alipaySubmit->createUrl($parameter);
        $this->smarty->assign(
            array(
                'module_dir' => $this->_path,
                'alipay_payment_url' => $url,
            	'order_no' => $order_id
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
}
