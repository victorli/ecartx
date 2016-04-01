<?php
class DiscountProducts extends Module
{
	public function __construct()
	{
		$this->name = 'discountproducts';
		$this->tab = 'front_office_features';
		$this->version = '1.1';
		$this->author = 'OvicSoft';

		parent::__construct();

		$this->displayName = $this->l('Supershop - Discount products');
		$this->description = $this->l('Discount products on home page.');
        $this->bootstrap = true;

		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
	}
    public function install()
	{
	   $success = (
			parent::install()
            && Configuration::updateValue('DEALS', '0000-00-00 00:00:00')
			&& $this->registerHook('header')
			&& $this->registerHook('addproduct')
			&& $this->registerHook('updateproduct')
			&& $this->registerHook('deleteproduct')
		);

		if (!$success || !$this->registerHook('displayHome'))
			return false;
		return true;
	}
    public function uninstall()
	{
		if (!parent::uninstall())
			return false;
		return true;
	}

    public function getContent()
    {
        $output = '';
		$errors = array();
        $languages = Language::getLanguages();
        $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
        $id_lang = $this->context->language->id;
		if (Tools::isSubmit('submitGlobal'))
		{
            Configuration::updateValue('DEALS', Tools::getValue('DEALS'));
            Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('discountproducts_home.tpl'));
            $output .= $this->displayConfirmation($this->l('Your settings have been updated.'));
		}
        $output .= $this->displayForm();
        return $output;
    }

    public function displayForm()
	{
		$this->context->smarty->assign(array(
            'DEALS' => Tools::getValue('DEALS',Configuration::get('DEALS')),
            'postAction' => AdminController::$currentIndex .'&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
        ));

		return $this->display(__FILE__, 'views/templates/admin/main.tpl');
	}

    public function hookdisplayHome($params)
	{
	   
	    $finish =  Configuration::get('DEALS');
        $deals_day = new DateTime($finish);
		if (!$this->isCached('discountproducts_home.tpl', $this->getCacheId()))
		{		  
		    if (new DateTime(date("Y-m-d H:i:s")) < new DateTime($finish)){
		      
                $id_lang = $this->context->language->id;
                $nbProducts = Product::getPricesDrop($this->context->language->id, null, null, true);
                $products = Product::getPricesDrop($this->context->language->id, 0, $nbProducts, false, 'date_upd', 'asc');
                $results = array();
                foreach ($products as $product){
                    if (isset($product['specific_prices']['to']) && $product['specific_prices']['to'] != '0000-00-00 00:00:00'){
                        $end_day = new DateTime($product['specific_prices']['to']);
                        if ($end_day == $deals_day){
                            $results[] = $product;
                        }
                    }
                }
                $this->context->smarty->assign('products', $results);
            }else{
                
                $this->context->smarty->assign(array('expired_warning'=>$this->l('Sorry! it\'s expired!'), 'products'=>array()));
            }

            $end_day = array();
            $str_date = explode(' ',$finish);
            $str_day = explode('-',$str_date[0]);
            $str_time = explode(':',$str_date[1]);
            $end_day['y'] = $str_day[0];
            $end_day['m'] = $str_day[1];
            $end_day['d'] = $str_day[2];
            $end_day['h'] = $str_time[0];
            $end_day['i'] = $str_time[1];
            $end_day['s'] = $str_time[2];
            $this->context->smarty->assign(array(
                'deals_day' => $end_day,
                'expiryText' => 'End in '.$finish
    		));
		}
		return $this->display(__FILE__, 'discountproducts_home.tpl', $this->getCacheId());
	}

    public function hookAddProduct($params)
	{
		$this->_clearCache('discountproducts_home.tpl');
	}

	public function hookUpdateProduct($params)
	{
		$this->_clearCache('discountproducts_home.tpl');
	}

	public function hookDeleteProduct($params)
	{
		$this->_clearCache('discountproducts_home.tpl');
	}

    public function hookHeader()
	{
	    //$this->context->controller->addCSS($this->_path.'discountproducts.css');
        $this->context->controller->addJS(($this->_path).'js/jquery.plugin.min.js');
        $this->context->controller->addJS(($this->_path).'js/jquery.countdown.js');
		//$this->context->controller->addJS(($this->_path).'js/discountproducts.js');
	}
}