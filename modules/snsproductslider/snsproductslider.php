<?php
/*
* 2015 SNS Theme
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @author SNS Theme <contact@snstheme.com>
*  @copyright  2015 SNS Theme
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) exit;
include_once(dirname(__FILE__) . '/snsproductsliderclass.php');

class SNSProductSlider extends Module {

	public function __construct()
	{
		$this->name = 'snsproductslider';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'SNS Theme';
		$this->need_instance = 0;
		$this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.6');
		$this->bootstrap = true;
		$this->_directory = dirname(__FILE__);

		parent::__construct();

		$this->displayName = $this->l('SNS Product Slider');
		$this->description = $this->l('This is moulde display products as slider');

		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
	}

	public function install()
	{
		$this->_clearCache('*');

		if (!parent::install()
			|| !$this->registerHook('actionOrderStatusPostUpdate')
			|| !$this->registerHook('addproduct')
			|| !$this->registerHook('updateproduct')
			|| !$this->registerHook('deleteproduct')
			|| !$this->registerHook('displayProductslider')
		)
			return false;

		// Activate every option by default
		Configuration::updateValue('SNSPRDS_STATUS', '1');
		Configuration::updateValue('SNSPRDS_TITLE', '', true);
		Configuration::updateValue('SNSPRDS_DESC', '', true);
		Configuration::updateValue('SNSPRDS_NBPRD', '10');
		Configuration::updateValue('SNSPRDS_SOURCE', 'all');


		$this->installFixtures();
		$this->snsCreateTab();
		$this->_clearCache('*');

		return true;
	}

	protected function installFixtures()
	{
		$languages = Language::getLanguages(false);
		foreach ($languages as $lang)
			$this->installFixture((int)$lang['id_lang']);
		return true;
	}

	protected function installFixture($id_lang)
	{
		$val = array();
		$val['SNSPRDS_TITLE'][(int)$id_lang] = 'The best digital devices';
		$val['SNSPRDS_DESC'][(int)$id_lang] = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco';
		Configuration::updateValue('SNSPRDS_TITLE', $val['SNSPRDS_TITLE'], true);
		Configuration::updateValue('SNSPRDS_DESC', $val['SNSPRDS_DESC'], true);
	}
	public function uninstall()
	{
		Configuration::deleteByName('SNSPRDS_STATUS');
		Configuration::deleteByName('SNSPRDS_TITLE');
		Configuration::deleteByName('SNSPRDS_DESC');
		Configuration::deleteByName('SNSPRDS_NBPRD');
		Configuration::deleteByName('SNSPRDS_SOURCE');

		$this->snsDeleteTab();
		return parent::uninstall();
	}
	private function snsCreateTab()
	{
		$response = true;
		$parent_tab_id = Tab::getIdFromClassName('AdminSNS');
		if ($parent_tab_id)
			$parent_tab = new Tab($parent_tab_id);
		else
		{
			$parent_tab = new Tab();
			$parent_tab->active = 1;
			$parent_tab->name = array();
			$parent_tab->class_name = 'AdminSNS';
			foreach (Language::getLanguages() as $lang)
				$parent_tab->name[$lang['id_lang']] = 'SNS Theme';

			$parent_tab->id_parent = 0;
			$parent_tab->module = $this->name;
			$response &= $parent_tab->add();
		}

		$tab = new Tab();
		$tab->active = 1;
		$tab->class_name = 'AdminSNSProductSlider';
		$tab->name = array();
		foreach (Language::getLanguages() as $lang)
			$tab->name[$lang['id_lang']] = 'SNS Product Slider';

		$tab->id_parent = $parent_tab->id;
		$tab->module = $this->name;
		$response &= $tab->add();

		return $response;
	}
	private function snsDeleteTab()
	{
		$id_tab = Tab::getIdFromClassName('AdminSNSProductSlider');
		$parent_tab_id = Tab::getIdFromClassName('AdminSNS');

		$tab = new Tab($id_tab);
		$tab->delete();

		$tab_count = Tab::getNbTabs($parent_tab_id);
		if ($tab_count == 0)
		{
			$parent_tab = new Tab($parent_tab_id);
			$parent_tab->delete();
		}
		return true;
	}
	public function getConfigFieldsValues()
	{
		$val = array();

		$languages = Language::getLanguages(false);

		foreach ($languages as $lang)
		{
			$lang_id = $lang['id_lang'];
			$val['SNSPRDS_TITLE'][$lang_id] = Tools::getValue('SNSPRDS_TITLE_'.$lang_id, Configuration::get('SNSPRDS_TITLE', $lang_id));
			$val['SNSPRDS_DESC'][$lang_id] = Tools::getValue('SNSPRDS_DESC_'.$lang_id, Configuration::get('SNSPRDS_DESC', $lang_id));
		}
		$val['SNSPRDS_STATUS'] = Tools::getValue('SNSPRDS_STATUS', Configuration::get('SNSPRDS_STATUS'));
		$val['SNSPRDS_NBPRD'] = Tools::getValue('SNSPRDS_NBPRD', Configuration::get('SNSPRDS_NBPRD'));
		$val['SNSPRDS_SOURCE'] = Tools::getValue('SNSPRDS_SOURCE', Configuration::get('SNSPRDS_SOURCE'));
		return $val;
	}

	public function getContent()
	{
		$output = '';
		if (Tools::isSubmit('submitSNSProductSlider'))
		{
			$languages = Language::getLanguages(false);
			$val = array();
			foreach ($languages as $lang)
			{
				$val['SNSPRDS_TITLE'][$lang['id_lang']] = Tools::getValue('SNSPRDS_TITLE_'.$lang['id_lang']);
				$val['SNSPRDS_DESC'][$lang['id_lang']] = Tools::getValue('SNSPRDS_DESC_'.$lang['id_lang']);
			}
			Configuration::updateValue('SNSPRDS_TITLE', $val['SNSPRDS_TITLE']);
			Configuration::updateValue('SNSPRDS_DESC', $val['SNSPRDS_DESC']);
			Configuration::updateValue('SNSPRDS_STATUS', Tools::getValue('SNSPRDS_STATUS'));
			Configuration::updateValue('SNSPRDS_NBPRD', Tools::getValue('SNSPRDS_NBPRD'));
			Configuration::updateValue('SNSPRDS_SOURCE', Tools::getValue('SNSPRDS_SOURCE'));

			$output .= $this->displayConfirmation($this->l('Settings updated'));
			
			$this->_clearCache("*");
			
			Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('snsproductslider.tpl'));
			Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true)
									.'&conf=6&configure='.$this->name
									.'&tab_module='.$this->tab
									.'&module_name='.$this->name);
		}
		$output .= $this->getFormHTML();
		return $output;
	}
	public function getFormHTML()
	{
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->module = $this;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitSNSProductSlider';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
									.'&configure='.$this->name
									.'&tab_module='.$this->tab
									.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'uri' => $this->getPathUri(),
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		$fields_form = array();
		$fields_form[0] = array(
			array(
				'type' => 'text',
				'label' => $this->l('Title'),
				'name' => 'SNSPRDS_TITLE',
				'class' => 'fixed-width-xl',
				'lang' => true
			),
			array(
				'type' => 'text',
				'label' => $this->l('Desc'),
				'name' => 'SNSPRDS_DESC',
				'lang' => true
			),
			array(
				'type' => 'switch',
				'label' => $this->l('Status'),
				'name' => 'SNSPRDS_STATUS',
				'values' => array(
					array(
						'id' => 'SNSPRDS_STATUS_ON',
						'value' => 1,
						'label' => $this->l('Enabled')
					),
					array(
						'id' => 'SNSPRDS_STATUS_OFF',
						'value' => 0,
						'label' => $this->l('Disabled')
					)
				)
			),
			array(
				'type' => 'text',
				'label' => $this->l('Products to display'),
				'name' => 'SNSPRDS_NBPRD',
				'class' => 'fixed-width-sm',
				'desc' => $this->l('Determine the number of product to display in this block'),
			),
			array(
				'type' => 'select',
				'label' => 'Source',
				'name' => 'SNSPRDS_SOURCE',
				'options' => array(
					'query' => array(
						array('id' => 'all', 'name' => 'All Products'),
					//	array('id' => 'deals', 'name' => 'Deals Products'),
						array('id' => 'specials', 'name' => 'Specials Products'),
						array('id' => 'viewed', 'name' => 'Viewed Products'),
						array('id' => 'topsale', 'name' => 'Topsale Products'),
						array('id' => 'new', 'name' => 'New Products'),
					),
					'id' => 'id',
					'name' => 'name'
				)
			),
		);
		return $helper->generateForm(array(
			array(
				'form' => array(
					'legend' => array(
						'title' => $this->l('Settings'),
						'icon' => 'icon-cogs'
					),
					'input' => $fields_form[0],
					'submit' => array(
						'title' => $this->l('Save')
					)
				)
			)
		));
	}
	public function getConfigLang($field)
	{
		$lang = $this->context->language->id;
		if (is_bool(Configuration::get($field, $this->context->language->id)))
			$lang = Configuration::get('PS_LANG_DEFAULT');
		else
			$lang = $this->context->language->id;
		return Configuration::get($field, $lang);
	}
	protected function displaySNSProductSlider()
	{
		$source = Configuration::get('SNSPRDS_SOURCE');
		$id_lang = $this->context->language->id;
		
		if (!Configuration::get('SNSPRDS_STATUS')) return;
		if (!$this->isCached('snsproductslider.tpl', $this->getCacheId('snsproductslider')))
		{
			$nb = (int)Configuration::get('SNSPRDS_NBPRD');
			if (!Configuration::get('PS_CATALOG_MODE')) {
				$products = array();
				if($source == 'deals') {
					$products = SNSProductSliderClass::getDealsProducts($id_lang, 0, $nb);
				} elseif ($source == 'specials') {
					$products = Product::getPricesDrop($id_lang, 0, $nb);
				} elseif ($source == 'viewed') {
					$products = SNSProductSliderClass::getViewedProduct($params, $id_lang, 0, $nb);
				} elseif ($source == 'topsale') {
					$products = ProductSale::getBestSalesLight($id_lang, 0, $nb);
				} elseif ($source == 'new') {
					$products = SNSProductSliderClass::getNewProducts(Configuration::get('PS_NB_DAYS_NEW_PRODUCT'), $id_lang, 0, $nb);
				} else {
					$products = Product::getProductsProperties($id_lang, Product::getProducts($id_lang, 0, $nb, 'date_add', 'ASC'));
				}
				
				$list = array();
				if (count($products)) {
					foreach ($products as $product)
					{
						$obj     = new Product((int) ($product['id_product']), false, $this->context->language->id);
						$images  = $obj->getImages($this->context->language->id);
						$_images = array();
						$id_image = '';
						if (!empty($images)) {
							foreach ($images as $k => $image) {
								if($image['cover']) $id_image = $obj->id . '-' . $image['id_image'];
								$_images[] = $obj->id . '-' . $image['id_image'];
							}
						}
						$id_image = ($id_image != '') ? $id_image : $_images[0];
						$product['id_image'] = $id_image;
						$list[] = $product;
					}
				}
				$this->context->smarty->assign(array('homeSize' => Image::getSize(ImageType::getFormatedName('home'))));
				$this->smarty->assign(array('products' => $list));
			}
			$this->context->smarty->assign(array(
				'SNSPRDS_TITLE' => $this->getConfigLang('SNSPRDS_TITLE'),
				'SNSPRDS_DESC' => $this->getConfigLang('SNSPRDS_DESC'),
			));
		}
		return $this->display(__FILE__, 'snsproductslider.tpl', $this->getCacheId('snsproductslider'));
	}
	public function hookDisplayProductslider()
	{
		return $this->displaySNSProductSlider();
	}
	public function hookAddProduct($params)
	{
		$this->_clearCache('*');
	}
	public function hookUpdateProduct($params)
	{
		$this->_clearCache('*');
	}
	public function hookDeleteProduct($params)
	{
		$this->_clearCache('*');
	}
	public function hookActionOrderStatusPostUpdate($params)
	{
		$this->_clearCache('*');
	}
	public function _clearCache($template, $cache_id = NULL, $compile_id = NULL)
	{
		parent::_clearCache('snsproductslider.tpl');
	}
}
