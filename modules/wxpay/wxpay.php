<?php
/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Wxpay extends PaymentModule
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'wxpay';
        $this->tab = 'payments_gateways';
        $this->version = '1.1.1';
        $this->author = 'ecartx';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Weixin Pay');
        $this->description = $this->l('WxPay is integrated in the Weixin payment capabilities.
The user can complete the rapid payment process through mobile. WxPay allows to bind the bank card\'s fast payment basis and to provide users with safe, fast and efficient payment services.');

        $this->limited_countries = array('CN','US');

        $this->limited_currencies = array('CNY','USD');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
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

        Configuration::updateValue('WXPAY_LIVE_MODE', true);
        Configuration::updateValue('WXPAY_APPID','wx2a77799a8e174cb3');
        Configuration::updateValue('WXPAY_MCHID','1341788101');
        Configuration::updateValue('WXPAY_KEY','e10blx3949ba59abbe56e057f20f883e');
        Configuration::updateValue('WXPAY_APPSECRET','ee41bca1e7be6e6466d6b8ee955027c4');
        Configuration::updateValue('WXPAY_GATEWAY_UNIFIED_ORDER','https://api.mch.weixin.qq.com/pay/unifiedorder');
        

        include(dirname(__FILE__).'/sql/install.php');

        $admin_order_hook = (_PS_VERSION_ < '1.6' ? 'displayAdminOrder' : 'displayAdminOrderLeft');
        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('payment') &&
            $this->registerHook('paymentReturn') &&
            $this->registerHook($admin_order_hook);
            //$this->registerHook('displayAdminOrder') &&
            //$this->registerHook('displayPayment') &&
            //$this->registerHook('displayPaymentReturn');
    }

    public function uninstall()
    {
        Configuration::deleteByName('WXPAY_LIVE_MODE');
        Configuration::deleteByName('WXPAY_APPID');
        Configuration::deleteByName('WXPAY_MCHID');
        Configuration::deleteByName('WXPAY_KEY');
        Configuration::deleteByName('WXPAY_APPSECRET');
        Configuration::deleteByName('WXPAY_GATEWAY_UNIFIED_ORDER');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitWxpayModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        //$output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $this->renderForm();//$output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitWxpayModule';
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

    /**
     * Create the structure of your form.
     */
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
                        'name' => 'WXPAY_LIVE_MODE',
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
                        'desc' => $this->l('Enter your APPID provided by Weixin'),
                        'name' => 'WXPAY_APPID',
                        'label' => $this->l('APPID'),
                    ),
                    array(
                        'col' => 4,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-user"></i>',
                        'desc' => $this->l('Enter your MCHID provided by Weixin'),
                        'name' => 'WXPAY_MCHID',
                        'label' => $this->l('MCHID'), 
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                	'class'	=>	'btn btn-default pull-right button',
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'WXPAY_LIVE_MODE' => Configuration::get('WXPAY_LIVE_MODE', true),
            'WXPAY_APPID' => Configuration::get('WXPAY_APPID', null),
            'WXPAY_MCHID' => Configuration::get('WXPAY_MCHID', null),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    /**
     * This method is used to render the payment button,
     * Take care if the button should be displayed or not.
     */
    public function hookPayment($params)
    {
        $currency_id = $params['cart']->id_currency;
        $currency = new Currency((int)$currency_id);

        if (in_array($currency->iso_code, $this->limited_currencies) == false)
            return false;

        $this->smarty->assign('module_dir', $this->_path);
        
        require_once 'lib/WxPay.NativePay.php';
        $cart = new Cart($params['cart']->id);
        
        do {
                $reference = Order::generateReference();
            } while (Order::getByReference($reference)->count());
        
        $order_id = $reference;
        $notify = new NativePay();
        $input = new WxPayUnifiedOrder();
        $input->SetAppid(Configuration::get('WXPAY_APPID'));
        $input->SetMch_id(Configuration::get('WXPAY_MCHID'));
        $input->SetDevice_info('WEB');
        $input->SetBody($this->getGoodsDescription());
		$input->SetAttach("test");//using for reference
		$input->SetOut_trade_no($order_id);
		$input->SetTotal_fee($this->formatTotalFee($cart->getOrderTotal()));
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 1800));
		//$input->SetGoods_tag("test");
		$input->SetNotify_url($this->context->link->getModuleLink($this->name,'notify'));
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id($order_id);
		$result = $notify->GetPayUrl($input);
		
		$this->logUnfiedOrder($input->GetValues(), $result);
		
		require_once 'lib/phpqrcode/phpqrcode.php';
		$url = urldecode($result['code_url']);
		
        $this->smarty->assign(
            array(
                
                'wxpay_payment_url' => $url
            )
        );

        return $this->display(__FILE__, 'views/templates/hook/payment.tpl');
    }

    /**
     * This hook is used to display the order confirmation page.
     */
    public function hookPaymentReturn($params)
    {
        if ($this->active == false)
            return;

        $order = $params['objOrder'];

        if ($order->getCurrentOrderState()->id != Configuration::get('PS_OS_ERROR'))
            $this->smarty->assign('status', 'ok');

        $this->smarty->assign(array(
            'id_order' => $order->id,
            'reference' => $order->reference,
            'params' => $params,
            'total' => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false),
        ));

        return $this->display(__FILE__, 'views/templates/hook/confirmation.tpl');
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
    
    public function formatTotalFee($total_fee){
    	return $total_fee*100;
    }
	
	public function logUnfiedOrder($input,$result){
		
		$data = $input;
		
		$data['return_code'] = pSQL($result['return_code']);
		$data['return_msg']  = pSQL($result['return_msg']);
		
		if ($result['return_code'] == 'SUCCESS'){
			$data['return_nonce_str'] = pSQL($result['nonce_str']);
			$data['result_code'] = pSQL($result['result_code']);
			if($data['result_code'] == 'SUCCESS'){
				$data['prepay_id'] = pSQL($result['prepay_id']);
				$data['code_url'] = pSQL($result['code_url']);
			}else{
				$data['err_code'] = pSQL($result['err_code']);
				$data['err_code_des'] = pSQL($result['err_code_des']);
			}
		}
		
		return Db::getInstance()->insert('wxpay_unifiedorder', $data);
	}
}
