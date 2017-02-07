<?php
/**
* 2015 SNSTheme
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
*  @author    SNSTheme <contact@snstheme.com>
*  @copyright 2015 SNSTheme
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of SNSTheme
*/

if (!defined('_PS_VERSION_')) exit;

include_once(dirname(__FILE__) . '/snsproducttabssliderclass.php');

class SNSProductTabsSlider extends SNSProductTabsSliderClass {

	public function __construct()
	{
		$this->name = 'snsproducttabsslider';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'SNS Theme';
		$this->need_instance = 0;
		$this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.6');
		$this->bootstrap = true;
		$this->_directory = dirname(__FILE__);

		parent::__construct();

		$this->displayName = $this->l('SNS Product Tabs Slider');
		$this->description = $this->l('This is module display product on tab with slider.');

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
			
			|| !$this->registerHook('displaySlideProductTab')
		)
			return false;

		$this->updateValue('SNSTS_TITLE', 'SNS Product Tabs Slider', true);
		$this->updateValue('SNSTS_NUMDISPLAY', 6);
		$this->updateValue('SNSTS_XS', 2);
		$this->updateValue('SNSTS_SM', 3);
		$this->updateValue('SNSTS_MD', 4);
		$this->updateValue('SNSTS_LG', 4);
		$this->updateValue('SNSTS_PRDIDS', '');
		$this->updateValue('SNSTS_ORDERBY', 'name');
		$this->updateValue('SNSTS_ORDERWAY', 'ASC');
		
		$this->updateValue('SNSTS_CATEGORY_TABS_ID', 'special_product,new_product,top_sellers');
		$this->updateValue('SNSTS_CLASSSFX', '');
		$this->updateValue('SNSTS_PRETEXT', '', true);
		$this->updateValue('SNSTS_POSTEXT', '', true);

		$this->installFixtures();

		$this->snsCreateTab();

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
		$values['SNSTS_TITLE'][(int)$id_lang] = 'SNS Product Tabs Slider';
		$values['SNSTS_PRETEXT'][(int)$id_lang] = '';
		$values['SNSTS_POSTEXT'][(int)$id_lang] = '';
		$this->updateValue('SNSTS_TITLE', $values['SNSTS_TITLE'], true);
		$this->updateValue('SNSTS_PRETEXT', $values['SNSTS_PRETEXT'], true);
		$this->updateValue('SNSTS_POSTEXT', $values['SNSTS_POSTEXT'], true);
	}
	public function uninstall()
	{
		Configuration::deleteByName('SNSTS_TITLE');
		Configuration::deleteByName('SNSTS_NUMDISPLAY');
		Configuration::deleteByName('SNSTS_XS');
		Configuration::deleteByName('SNSTS_SM');
		Configuration::deleteByName('SNSTS_MD');
		Configuration::deleteByName('SNSTS_LG');
		Configuration::deleteByName('SNSTS_PRDIDS');
		Configuration::deleteByName('SNSTS_ORDERBY');
		Configuration::deleteByName('SNSTS_ORDERWAY');
		Configuration::deleteByName('SNSTS_CATEGORY_TABS_ID');
		Configuration::deleteByName('SNSTS_CLASSSFX');
		Configuration::deleteByName('SNSTS_PRETEXT');
		Configuration::deleteByName('SNSTS_POSTEXT');

		$this->snsDeleteTab();
		return parent::uninstall();
	}

	public function getConfigFieldsValues() {
		$values = array();
		
		$languages = Language::getLanguages(false);
		
		foreach ($languages as $lang) {
			$values['SNSTS_TITLE'][$lang['id_lang']] = Tools::getValue('SNSTS_TITLE_'.$lang['id_lang'], Configuration::get('SNSTS_TITLE', $lang['id_lang']));
			$values['SNSTS_PRETEXT'][$lang['id_lang']] = Tools::getValue('SNSTS_PRETEXT_'.$lang['id_lang'], Configuration::get('SNSTS_PRETEXT', $lang['id_lang']));
			$values['SNSTS_POSTEXT'][$lang['id_lang']] = Tools::getValue('SNSTS_POSTEXT_'.$lang['id_lang'], Configuration::get('SNSTS_POSTEXT', $lang['id_lang']));
		}

		$values['SNSTS_NUMDISPLAY'] = Configuration::get('SNSTS_NUMDISPLAY');
		$values['SNSTS_PRDIDS'] = Configuration::get('SNSTS_PRDIDS');
		$values['SNSTS_ORDERBY'] = Configuration::get('SNSTS_ORDERBY');
		$values['SNSTS_ORDERWAY'] = Configuration::get('SNSTS_ORDERWAY');
		$values['SNSTS_XS'] = Configuration::get('SNSTS_XS');
		$values['SNSTS_SM'] = Configuration::get('SNSTS_SM');
		$values['SNSTS_MD'] = Configuration::get('SNSTS_MD');
		$values['SNSTS_LG'] = Configuration::get('SNSTS_LG');
		$values['SNSTS_CLASSSFX'] = Configuration::get('SNSTS_CLASSSFX');

		return $values;
	}

	public function getContent()
	{
		$this->context->controller->addjQueryPlugin(array('tagify'));
		$languages = Language::getLanguages(false);
		$output = '';
		if (Tools::isSubmit('submitSNSProductTabsSlider'))
		{
			if (!Tools::getValue('SNSTS_NUMDISPLAY') || Tools::getValue('SNSTS_NUMDISPLAY') <= 0 || !Validate::isInt(Tools::getValue('SNSTS_NUMDISPLAY')))
				$errors[] = $this->l('Invalid number of products');
			
			if (!Tools::getValue('SNSTS_XS')  || !Validate::isInt(Tools::getValue('SNSTS_XS')) )
				$errors[] = $this->l('Invalid number pre row.');
			if (!Tools::getValue('SNSTS_SM')  || !Validate::isInt(Tools::getValue('SNSTS_SM')) )
				$errors[] = $this->l('Invalid number pre row.');
			if (!Tools::getValue('SNSTS_MD')  || !Validate::isInt(Tools::getValue('SNSTS_MD')) )
				$errors[] = $this->l('Invalid number pre row.');
			if (!Tools::getValue('SNSTS_LG')  || !Validate::isInt(Tools::getValue('SNSTS_LG')) )
				$errors[] = $this->l('Invalid number pre row.');
			
			$items = Tools::getValue('items');
			if (!(is_array($items) && count($items) && $this->updateValue('SNSTS_CATEGORY_TABS_ID', (string)implode(',', $items))))
				$errors[] =$this->l('Unable to update settings.');

			if (isset($errors) AND sizeof($errors)) {
				$output .= $this->displayError(implode('<br />', $errors));
			} else {
				$values = array();
				foreach ($languages as $lang) {
					$values['SNSTS_TITLE'][$lang['id_lang']] = Tools::getValue('SNSTS_TITLE_'.$lang['id_lang']);
					$values['SNSTS_PRETEXT'][$lang['id_lang']] = Tools::getValue('SNSTS_PRETEXT_'.$lang['id_lang']);
					$values['SNSTS_POSTEXT'][$lang['id_lang']] = Tools::getValue('SNSTS_POSTEXT_'.$lang['id_lang']);
				}
				$this->updateValue('SNSTS_TITLE', $values['SNSTS_TITLE'], true);
				$this->updateValue('SNSTS_PRETEXT', $values['SNSTS_PRETEXT'], true);
				$this->updateValue('SNSTS_POSTEXT', $values['SNSTS_POSTEXT'], true);

				$prd_ids = Tools::getValue('SNSTS_PRDIDS');
				$prd_ids_arr = explode(',', $prd_ids);
				foreach ($prd_ids_arr as $k => &$prd_id) {
					$prd_id = (int)$prd_id;
					if(!$prd_id || $prd_id < 0) unset($prd_ids_arr[$k]);
				}
				$prd_ids = implode(',', array_unique($prd_ids_arr));
				
				$this->updateValue('SNSTS_PRDIDS', $prd_ids);
				$this->updateValue('SNSTS_ORDERBY', Tools::getValue('SNSTS_ORDERBY'));
				$this->updateValue('SNSTS_ORDERWAY', Tools::getValue('SNSTS_ORDERWAY'));
				$this->updateValue('SNSTS_NUMDISPLAY', (int)Tools::getValue('SNSTS_NUMDISPLAY'));
				$this->updateValue('SNSTS_XS', (int)Tools::getValue('SNSTS_XS'));
				$this->updateValue('SNSTS_SM', (int)Tools::getValue('SNSTS_SM'));
				$this->updateValue('SNSTS_MD', (int)Tools::getValue('SNSTS_MD'));
				$this->updateValue('SNSTS_LG', (int)Tools::getValue('SNSTS_LG'));
				$this->updateValue('SNSTS_CLASSSFX', Tools::getValue('SNSTS_CLASSSFX'));
				
				$output .= $this->displayConfirmation($this->l('Settings updated'));
			}
			$this->_clearCache("*");
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
		$helper->submit_action = 'submitSNSProductTabsSlider';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
									.'&configure='.$this->name
									.'&tab_module='.$this->tab
									.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'uri' => $this->getPathUri(),
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
			'choices' => $this->renderChoicesSelect(),
			'selected_links' => $this->makeMenuOption()
		);

		$fields_form = array();
		$fields_form[0] = array(
            array(
                'type' => 'text',
                'label' => $this->l('Module Title'),
                'name' => 'SNSTS_TITLE',
                'class' => 'fixed-width-xl',
            	'lang' => true
            ),
			array(
				'type' => 'text',
				'label' => $this->l('Max. product count per tab'),
				'name' => 'SNSTS_NUMDISPLAY',
				'class' => 'fixed-width-xs',
				'suffix' => $this->l('products per tab')
			),
			array(
				'type' => 'link_choice',
				'label' => '',
				'name' => 'link',
				'lang' => true,
			),	
			array(
				'type' => 'prd_ids',
				'label' => $this->l('Product ids'),
				'name' => 'SNSTS_PRDIDS',
				'hint' => $this->l('To add "Product Ids," click in the field, write product id, and then press "Enter."'),
				'desc' => $this->l('Product ids only apply for Featured Product tab.')
				),
			array(
				'type' => 'select',
				'label' => 'Order by',
				'name' => 'SNSTS_ORDERBY',
				'desc' => $this->l('This field only apply for categories.'),
				'options' => array(
					'query' => array(
									array('id' => 'name', 'name' => 'Name'),
									array('id' => 'price', 'name' => 'Price'),
									array('id' => 'date_add', 'name' => 'Date Added'),
									array('id' => 'date_upd', 'name' => 'Date Updated'),
									array('id' => 'sales', 'name' => 'Sales'),
									array('id' => 'position', 'name' => 'Position'),
									array('id' => 'id_product', 'name' => 'ID Product'),
									array('id' => 'rand', 'name' => 'Random')
								),
					'id' => 'id',
					'name' => 'name'
				)
			),
			array(
				'type' => 'select',
				'label' => 'Order way',
				'name' => 'SNSTS_ORDERWAY',
				'desc' => $this->l('This field only apply for categories.'),
				'options' => array(
					'query' => array(
								array('id' => 'ASC', 'name' => 'ASC'),
								array('id' => 'DESC', 'name' => 'DESC')
							),
					'id' => 'id',
					'name' => 'name'
				)
			),
			array(
				'label' => $this->l('Screen width > 480px'),
				'name' => 'SNSTS_XS',
				'type' => 'text', 'class' => 'fixed-width-xs', 'suffix' => $this->l('products per row')
			),
			array(
				'label' => $this->l('Screen width > 768px'),
				'name' => 'SNSTS_SM',
				'type' => 'text', 'class' => 'fixed-width-xs', 'suffix' => $this->l('products per row')
			),
			array(
				'label' => $this->l('Screen width > 992px'),
				'name' => 'SNSTS_MD',
				'type' => 'text', 'class' => 'fixed-width-xs', 'suffix' => $this->l('products per row')
			),
			array(
				'label' => $this->l('Screen width > 1200px'),
				'name' => 'SNSTS_LG',
				'type' => 'text', 'class' => 'fixed-width-xs', 'suffix' => $this->l('products per row')
			),
		);
		$fields_form[1] = array(
            array(
                'type' => 'text',
                'label' => $this->l('Module Class Suffix'),
                'name' => 'SNSTS_CLASSSFX',
                'hint' => $this->l('A suffix to be applied to the CSS class of the module. This allows for individual module styling.'),
                'class' => 'fixed-width-xl'
            ),
            array(
                'type' => 'textarea',
                'label' => $this->l('Pre-text'),
                'name' => 'SNSTS_PRETEXT',
                'hint' => $this->l('Intro text of module.'),
            	'lang' => true,
            	'autoload_rte' => true
            ),
            array(
                'type' => 'textarea',
                'label' => $this->l('Post-text'),
                'name' => 'SNSTS_POSTEXT',
                'hint' => $this->l('Footer text of module.'),
            	'lang' => true,
            	'autoload_rte' => true
            )
		);
		return $helper->generateForm(array(
			array(
				'form' => array(
					'legend' => array(
						'title' => $this->l('General Options'),
						'icon' => 'icon-cogs'
					),
					'input' => $fields_form[0],
					'submit' => array(
						'title' => $this->l('Save')
					)
				)
			),
			array(
				'form' => array(
					'legend' => array(
						'title' => $this->l('Advanced Options'),
						'icon' => 'icon-cogs'
					),
					'input' => $fields_form[1],
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
	public function getVarAssign() {
		$context = Context::getContext();
		$id_lang = (int) $context->language->id;
		$vars = array();
		
		$lang_fields = array('SNSTS_TITLE', 'SNSTS_PRETEXT', 'SNSTS_POSTEXT');
		$fields = array('SNSTS_NUMDISPLAY', 'SNSTS_NUMLOAD', 'SNSTS_EFFECT', 'SNSTS_XS', 'SNSTS_SM', 'SNSTS_MD', 'SNSTS_LG', 'SNSTS_CLASSSFX');

		foreach($fields as $field)
			$vars[$field] = Configuration::get($field);
		
		foreach($lang_fields as $field) 
		{
			if(is_bool(Configuration::get($field, $id_lang)))
				$vars[$field] = Configuration::get($field, Configuration::get('PS_LANG_DEFAULT'));
			else
				$vars[$field] = Configuration::get($field, $id_lang);
		}

		return $vars;
	}
	protected function displaySNSProductTabsSlider()
	{
		$context = Context::getContext();
		$id_lang = (int) $context->language->id;
		
		$cache_id = $this->getCacheId('snsproducttabsslider');
		if (!$this->isCached('snsproducttabsslider.tpl', $cache_id))
		{
			$tabs = $this->_getData();
			if (empty($tabs))
				return;
			$context->smarty->assign(array('tabs' => $tabs));
			$context->smarty->assign($this->getVarAssign());
			$context->smarty->assign(array('homeSize' => Image::getSize(ImageType::getFormatedName('home'))));
		}
		
		return $this->display(__FILE__, 'snsproducttabsslider.tpl', $cache_id);
	}
	public function _processAjaxCall()
	{
		$context = Context::getContext();
		$is_ajax =	Tools::getValue('is_ajax');
		$module_name = Tools::getValue('module_name');
		if($is_ajax && $module_name == $this->name ) {
			$ajax_start = Tools::getValue('ajax_start');
			$categoryid	 = Tools::getValue('categoryid');
			$data_type = Tools::getValue('data_type');
			$products = $this->_getProductInfor($categoryid,$data_type);
			
			$context->smarty->assign($this->getVarAssign());
			$context->smarty->assign(
				array(
					'ajax_start' => Tools::getValue('ajax_start'),
					'products' => $products,
					'homeSize' => Image::getSize(ImageType::getFormatedName('home'))
				)
			);
			
            $product_list = $this->display(__FILE__, 'items.tpl');
			
			//$product_list = $context->smarty->fetch(__FILE__, 'items.tpl');
			$vars = array(
				'productList' => $product_list
			);
			die( Tools::jsonEncode($vars));
		}
	}
	

	public function hookDisplaySlideProductTab() 
	{
		return $this->displaySNSProductTabsSlider();
	}


	public function getLabelField($key){
		$name_label = '';
		switch($key){
			default:
			case 'featured_product':
				$name_label = $this->l('Featured Products');
				break;
			case 'special_product':
				$name_label = $this->l('Special Products');
				break;
			case 'new_product':
				$name_label = $this->l('New Products');
				break;
			case 'top_sellers':
				$name_label = $this->l('Best Sellers');
				break;			
		}
		return $name_label;
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
	public static function updateValue($key, $values, $html = false, $id_shop_group = null, $id_shop = null)
	{
		if (!Validate::isConfigName($key))
			die(sprintf(Tools::displayError('[%s] is not a valid configuration key'), $key));

		if ($id_shop === null || !Shop::isFeatureActive())
			$id_shop = Shop::getContextShopID(true);
		if ($id_shop_group === null || !Shop::isFeatureActive())
			$id_shop_group = Shop::getContextShopGroupID(true);

		if (!is_array($values))
			$values = array($values);

		if ($html)
			foreach ($values as $lang => $value)
				$values[$lang] = Tools::purifyHTML($value);

		$result = true;
		foreach ($values as $lang => $value)
		{
			$stored_value = Configuration::get($key, $lang, $id_shop_group, $id_shop);
			// if there isn't a $stored_value, we must insert $value
			if ((!is_numeric($value) && $value === $stored_value) || (is_numeric($value) && $value == $stored_value && Configuration::hasKey($key, $lang)))
				continue;

			// If key already exists, update value
			if (Configuration::hasKey($key, $lang, $id_shop_group, $id_shop))
			{
				if (!$lang)
				{
					// Update config not linked to lang
					$result &= Db::getInstance()->update('configuration', array(
						'value' => pSQL($value, $html),
						'date_upd' => date('Y-m-d H:i:s'),
					), '`name` = \''.pSQL($key).'\''.SNSProductTabsSlider::sqlRestriction($id_shop_group, $id_shop), 1, true);
				}
				else
				{
					// Update multi lang
					$sql = 'UPDATE `'._DB_PREFIX_.bqSQL('configuration').'_lang` cl
							SET cl.value = \''.pSQL($value, $html).'\',
								cl.date_upd = NOW()
							WHERE cl.id_lang = '.(int)$lang.'
								AND cl.`'.bqSQL('id_configuration').'` = (
									SELECT c.`'.bqSQL('id_configuration').'`
									FROM `'._DB_PREFIX_.bqSQL('configuration').'` c
									WHERE c.name = \''.pSQL($key).'\''
										.SNSProductTabsSlider::sqlRestriction($id_shop_group, $id_shop)
								.')';
					$result &= Db::getInstance()->execute($sql);
				}
			}
			// If key does not exists, create it
			else
			{
				if (!$configID = Configuration::getIdByName($key, $id_shop_group, $id_shop))
				{
					$newConfig = new Configuration();
					$newConfig->name = $key;
					if ($id_shop)
						$newConfig->id_shop = (int)$id_shop;
					if ($id_shop_group)
						$newConfig->id_shop_group = (int)$id_shop_group;
					if (!$lang)
						$newConfig->value = $value;
					$result &= $newConfig->add(true, true);
					$configID = $newConfig->id;
				}

				if ($lang)
				{
					$result &= Db::getInstance()->insert('configuration_lang', array(
						'id_configuration' => $configID,
						'id_lang' => (int)$lang,
						'value' => pSQL($value, $html),
						'date_upd' => date('Y-m-d H:i:s'),
					));
				}
			}
		}

		Configuration::set($key, $values, $id_shop_group, $id_shop);

		return $result;
	}
	protected static function sqlRestriction($id_shop_group, $id_shop)
	{
		if ($id_shop)
			return ' AND id_shop = '.(int)$id_shop;
		elseif ($id_shop_group)
			return ' AND id_shop_group = '.(int)$id_shop_group.' AND (id_shop IS NULL OR id_shop = 0)';
		else
			return ' AND (id_shop_group IS NULL OR id_shop_group = 0) AND (id_shop IS NULL OR id_shop = 0)';
	}
}
