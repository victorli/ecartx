<?php
/**
 * @package SNS Product Slider
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 SNS Group. All Rights Reserved.
 * @author SNSGroup http://snstheme.com
 */
if (!defined('_PS_VERSION_'))
	exit ;

include_once(dirname(__FILE__) . '/snsproducttabsclass.php');

class SNSProductTabs extends SNSProductTabsClass {
	public function __construct() {
		$this->name = 'snsproducttabs';
		$this->tab = 'front_office_features';
		$this->version = '1.0';
		$this->author = 'SNS Theme';
		$this->need_instance = 0;
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('SNS Product Tabs');
		$this->description = $this->l('Display products as tabs');
	}
	public function install() {
		if (!parent::install()) return false;
		
		$this->clearCache();
		
		// Activate every option by default
		$this->updateValue('SNSPRT_TITLE', 'SNS Product Tabs', true);
		$this->updateValue('SNSPRT_NUMDISPLAY', 8);
		$this->updateValue('SNSPRT_NUMLOAD', 4);
		$this->updateValue('SNSPRT_EFFECT', 'slideRight');
		$this->updateValue('SNSPRT_ORDERBY', 'name');
		$this->updateValue('SNSPRT_ORDERWAY', 'ASC');
		$this->updateValue('SNSPRT_PRDIDS', '');
		$this->updateValue('SNSPRT_XS', 2);
		$this->updateValue('SNSPRT_SM', 3);
		$this->updateValue('SNSPRT_MD', 4);
		$this->updateValue('SNSPRT_LG', 4);

		$this->updateValue('SNSPRT_CATEGORY_TABS_ID', 'featured_product,special_product,new_product,top_sellers');
		$this->updateValue('SNSPRT_CLASSSFX', '');
		$this->updateValue('SNSPRT_PRETEXT', '', true);
		$this->updateValue('SNSPRT_POSTEXT', '', true);
		
		$this->registerHook('displayHome');
		$this->registerHook('displayHeader');
		$this->registerHook('addproduct');
		$this->registerHook('updateproduct');
		$this->registerHook('deleteproduct');
		
		$this->installFixtures();
		$this->_createTab();
		
		return true;
	}
	protected function installFixtures() {
		$languages = Language::getLanguages(false);
		foreach ($languages as $lang)
			$this->installFixture((int)$lang['id_lang']);
		return true;
	}
	protected function installFixture($id_lang) {
		$values['SNSPRT_TITLE'][(int)$id_lang] = 'SNS Product Tabs';
		$values['SNSPRT_PRETEXT'][(int)$id_lang] = '';
		$values['SNSPRT_POSTEXT'][(int)$id_lang] = '';
		$this->updateValue('SNSPRT_TITLE', $values['SNSPRT_TITLE'], true);
		$this->updateValue('SNSPRT_PRETEXT', $values['SNSPRT_PRETEXT'], true);
		$this->updateValue('SNSPRT_POSTEXT', $values['SNSPRT_POSTEXT'], true);
	}
	public function uninstall() {
		Configuration::deleteByName('SNSPRT_TITLE');
		Configuration::deleteByName('SNSPRT_NUMDISPLAY');
		Configuration::deleteByName('SNSPRT_NUMLOAD');
		Configuration::deleteByName('SNSPRT_EFFECT');
		Configuration::deleteByName('SNSPRT_ORDERBY');
		Configuration::deleteByName('SNSPRT_ORDERWAY');
		Configuration::deleteByName('SNSPRT_PRDIDS');
		Configuration::deleteByName('SNSPRT_XS');
		Configuration::deleteByName('SNSPRT_SM');
		Configuration::deleteByName('SNSPRT_MD');
		Configuration::deleteByName('SNSPRT_LG');
		Configuration::deleteByName('SNSPRT_CATEGORY_TABS_ID');
		Configuration::deleteByName('SNSPRT_CLASSSFX');
		Configuration::deleteByName('SNSPRT_PRETEXT');
		Configuration::deleteByName('SNSPRT_POSTEXT');
		$this->_deleteTab();
		return parent::uninstall();
	}
	public function getContent() {
		$languages = Language::getLanguages(false);
		$this->context->controller->addjQueryPlugin(array('tagify'));
		if (Tools::isSubmit('submitModule')) {
			
			if (!Tools::getValue('SNSPRT_NUMDISPLAY') || Tools::getValue('SNSPRT_NUMDISPLAY') <= 0 || !Validate::isInt(Tools::getValue('SNSPRT_NUMDISPLAY')))
				$errors[] = $this->l('Invalid number of products');
			if (!Tools::getValue('SNSPRT_NUMLOAD') || Tools::getValue('SNSPRT_NUMLOAD') <= 0 || !Validate::isInt(Tools::getValue('SNSPRT_NUMLOAD')))
				$errors[] = $this->l('Invalid number of load');
			
			if (!Tools::getValue('SNSPRT_XS')  || ( Tools::getValue('SNSPRT_XS') != 1 && Tools::getValue('SNSPRT_XS') != 2 && Tools::getValue('SNSPRT_XS') != 3 && Tools::getValue('SNSPRT_XS') != 4 && Tools::getValue('SNSPRT_XS') != 6 ) )
				$errors[] = $this->l('Invalid number pre row. You can only set is 1,2,3,4 or 6');
			if (!Tools::getValue('SNSPRT_SM')  || ( Tools::getValue('SNSPRT_SM') != 1 && Tools::getValue('SNSPRT_SM') != 2 && Tools::getValue('SNSPRT_SM') != 3 && Tools::getValue('SNSPRT_SM') != 4 && Tools::getValue('SNSPRT_SM') != 6 ) )
				$errors[] = $this->l('Invalid number pre row. You can only set is 1,2,3,4 or 6');
			if (!Tools::getValue('SNSPRT_MD')  || ( Tools::getValue('SNSPRT_MD') != 1 && Tools::getValue('SNSPRT_MD') != 2 && Tools::getValue('SNSPRT_MD') != 3 && Tools::getValue('SNSPRT_MD') != 4 && Tools::getValue('SNSPRT_MD') != 6 ) )
				$errors[] = $this->l('Invalid number pre row. You can only set is 1,2,3,4 or 6');
			if (!Tools::getValue('SNSPRT_LG')  || ( Tools::getValue('SNSPRT_LG') != 1 && Tools::getValue('SNSPRT_LG') != 2 && Tools::getValue('SNSPRT_LG') != 3 && Tools::getValue('SNSPRT_LG') != 4 && Tools::getValue('SNSPRT_LG') != 6 ) )
				$errors[] = $this->l('Invalid number pre row. You can only set is 1,2,3,4 or 6');
			
			$items = Tools::getValue('items');
			if (!(is_array($items) && count($items) && $this->updateValue('SNSPRT_CATEGORY_TABS_ID', (string)implode(',', $items))))
				$errors[] =$this->l('Unable to update settings.');

			if (isset($errors) AND sizeof($errors)) {
				$this->_html .= $this->displayError(implode('<br />', $errors));
			} else {
				$values = array();
				foreach ($languages as $lang) {
					$values['SNSPRT_TITLE'][$lang['id_lang']] = Tools::getValue('SNSPRT_TITLE_'.$lang['id_lang']);
					$values['SNSPRT_PRETEXT'][$lang['id_lang']] = Tools::getValue('SNSPRT_PRETEXT_'.$lang['id_lang']);
					$values['SNSPRT_POSTEXT'][$lang['id_lang']] = Tools::getValue('SNSPRT_POSTEXT_'.$lang['id_lang']);
				}
				$this->updateValue('SNSPRT_TITLE', $values['SNSPRT_TITLE'], true);
				$this->updateValue('SNSPRT_PRETEXT', $values['SNSPRT_PRETEXT'], true);
				$this->updateValue('SNSPRT_POSTEXT', $values['SNSPRT_POSTEXT'], true);

				$this->updateValue('SNSPRT_NUMDISPLAY', (int)Tools::getValue('SNSPRT_NUMDISPLAY'));
				$this->updateValue('SNSPRT_NUMLOAD', (int)Tools::getValue('SNSPRT_NUMLOAD'));
				$this->updateValue('SNSPRT_EFFECT', Tools::getValue('SNSPRT_EFFECT'));
				$this->updateValue('SNSPRT_ORDERBY', Tools::getValue('SNSPRT_ORDERBY'));
				$this->updateValue('SNSPRT_ORDERWAY', Tools::getValue('SNSPRT_ORDERWAY'));
				
				$prd_ids = Tools::getValue('SNSPRT_PRDIDS');
				$prd_ids_arr = explode(',', $prd_ids);
				foreach ($prd_ids_arr as $k => &$prd_id) {
					$prd_id = (int)$prd_id;
					if(!$prd_id || $prd_id < 0) unset($prd_ids_arr[$k]);
				}
				$prd_ids = implode(',', array_unique($prd_ids_arr));
				
				$this->updateValue('SNSPRT_PRDIDS', $prd_ids);
				
				$this->updateValue('SNSPRT_XS', (int)Tools::getValue('SNSPRT_XS'));
				$this->updateValue('SNSPRT_SM', (int)Tools::getValue('SNSPRT_SM'));
				$this->updateValue('SNSPRT_MD', (int)Tools::getValue('SNSPRT_MD'));
				$this->updateValue('SNSPRT_LG', (int)Tools::getValue('SNSPRT_LG'));
				$this->updateValue('SNSPRT_CLASSSFX', Tools::getValue('SNSPRT_CLASSSFX'));
				
				$this->_html .= $this->displayConfirmation($this->l('Settings updated'));
			}
			$this->clearCache();
		}
		$this->_html .= $this->renderForm();
		return $this->_html;
	}
	public function getListEffect()
	{
		return array(
			array('id' => 'slideBottom', 'name' => 'slideBottom'),
			array('id' => 'slideLeft', 'name' => 'slideLeft'),
			array('id' => 'slideRight', 'name' => 'slideRight'),
			array('id' => 'bounceIn', 'name' => 'bounceIn'),
			array('id' => 'bounceInRight', 'name' => 'bounceInRight'),
			array('id' => 'zoomIn', 'name' => 'zoomIn'),
			array('id' => 'zoomOut', 'name' => 'zoomOut'),
			array('id' => 'pageTop', 'name' => 'pageTop'),
			array('id' => 'pageBottom', 'name' => 'pageBottom'),
			array('id' => 'pageLeft', 'name' => 'pageLeft'),
			array('id' => 'pageRight', 'name' => 'pageRight'),
			array('id' => 'starwars', 'name' => 'starwars')
		);
	}
	public function getForm() {
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('General Options'),
					'icon' => 'icon-cogs'
					),

				'input' => array(
		            array(
		                'type' => 'text',
		                'label' => $this->l('Module Title'),
		                'name' => 'SNSPRT_TITLE',
		                'class' => 'fixed-width-xl',
		            	'lang' => true
		            ),
					array(
						'type' => 'text',
						'label' => $this->l('Max. product count per tab'),
						'name' => 'SNSPRT_NUMDISPLAY',
						'class' => 'fixed-width-xs',
						'suffix' => $this->l('products per tab')
						),
					array(
						'type' => 'text',
						'label' => $this->l('Max. product count per load'),
						'name' => 'SNSPRT_NUMLOAD',
						'class' => 'fixed-width-xs',
						'suffix' => $this->l('products per load')
						),
					array(
						'type' => 'select',
						'label' => 'Layout type',
						'name' => 'SNSPRT_EFFECT',
						'options' => array(
							'query' => $this->getListEffect(),
							'id' => 'id',
							'name' => 'name'
						)
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
						'name' => 'SNSPRT_PRDIDS',
						'hint' => $this->l('To add "Product Ids," click in the field, write product id, and then press "Enter."'),
						'desc' => $this->l('Product ids only apply for Featured Product tab.')
						),
					array(
						'type' => 'select',
						'label' => 'Order by',
						'name' => 'SNSPRT_ORDERBY',
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
						'name' => 'SNSPRT_ORDERWAY',
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
						'name' => 'SNSPRT_XS',
						'type' => 'text', 'class' => 'fixed-width-xs', 'suffix' => $this->l('products per row')
						),
					array(
						'label' => $this->l('Screen width > 768px'),
						'name' => 'SNSPRT_SM',
						'type' => 'text', 'class' => 'fixed-width-xs', 'suffix' => $this->l('products per row')
						),
					array(
						'label' => $this->l('Screen width > 992px'),
						'name' => 'SNSPRT_MD',
						'type' => 'text', 'class' => 'fixed-width-xs', 'suffix' => $this->l('products per row')
						),
					array(
						'label' => $this->l('Screen width > 1200px'),
						'name' => 'SNSPRT_LG',
						'type' => 'text', 'class' => 'fixed-width-xs', 'suffix' => $this->l('products per row')
						),
					),
				'submit' => array(
					'name' => 'submitModule',
					'title' => $this->l('Save')
					)
				),
			);
		$advanceSection = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Advanced Options'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
		            array(
		                'type' => 'text',
		                'label' => $this->l('Module Class Suffix'),
		                'name' => 'SNSPRT_CLASSSFX',
		                'hint' => $this->l('A suffix to be applied to the CSS class of the module. This allows for individual module styling.'),
		                'class' => 'fixed-width-xl'
		            ),
		            array(
		                'type' => 'textarea',
		                'label' => $this->l('Pre-text'),
		                'name' => 'SNSPRT_PRETEXT',
		                'hint' => $this->l('Intro text of module.'),
		            	'lang' => true,
		            	'autoload_rte' => true
		            ),
		            array(
		                'type' => 'textarea',
		                'label' => $this->l('Post-text'),
		                'name' => 'SNSPRT_POSTEXT',
		                'hint' => $this->l('Footer text of module.'),
		            	'lang' => true,
		            	'autoload_rte' => true
		            )
				),
				'submit' => array(
					'name' => 'submitModule',
					'title' => $this->l('Save')
				)
			)
		);
		if (Shop::isFeatureActive()) {
			$fields_form['form']['description'] = $this->l('The modifications will be applied to').' '.(Shop::getContext() == Shop::CONTEXT_SHOP ? $this->l('shop').' '.$this->context->shop->name : $this->l('all shops'));
			$advanceSection['form']['description'] = $this->l('The modifications will be applied to').' '.(Shop::getContext() == Shop::CONTEXT_SHOP ? $this->l('shop').' '.$this->context->shop->name : $this->l('all shops'));
		}
		return array($fields_form, $advanceSection);
	}
	public function renderForm() {
		
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->module = $this;
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->module = $this;
		$helper->identifier = $this->identifier;	
		$helper->submit_action = 'submitSNSFacebook';	
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'uri' => $this->getPathUri(),
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
			'choices' => $this->renderChoicesSelect(),
			'selected_links' => $this->makeMenuOption(),
			);
		return $helper->generateForm($this->getForm());
	}
	public function getConfigFieldsValues() {
		$values = array();
		
		$languages = Language::getLanguages(false);
		
		foreach ($languages as $lang) {
			$values['SNSPRT_TITLE'][$lang['id_lang']] = Tools::getValue('SNSPRT_TITLE_'.$lang['id_lang'], Configuration::get('SNSPRT_TITLE', $lang['id_lang']));
			$values['SNSPRT_PRETEXT'][$lang['id_lang']] = Tools::getValue('SNSPRT_PRETEXT_'.$lang['id_lang'], Configuration::get('SNSPRT_PRETEXT', $lang['id_lang']));
			$values['SNSPRT_POSTEXT'][$lang['id_lang']] = Tools::getValue('SNSPRT_POSTEXT_'.$lang['id_lang'], Configuration::get('SNSPRT_POSTEXT', $lang['id_lang']));
		}
		$values['SNSPRT_NUMDISPLAY'] = Configuration::get('SNSPRT_NUMDISPLAY');
		$values['SNSPRT_NUMLOAD'] = Configuration::get('SNSPRT_NUMLOAD');
		$values['SNSPRT_EFFECT'] = Configuration::get('SNSPRT_EFFECT');
		$values['SNSPRT_ORDERBY'] = Configuration::get('SNSPRT_ORDERBY');
		$values['SNSPRT_ORDERWAY'] = Configuration::get('SNSPRT_ORDERWAY');
		$values['SNSPRT_PRDIDS'] = Configuration::get('SNSPRT_PRDIDS');
		$values['SNSPRT_XS'] = Configuration::get('SNSPRT_XS');
		$values['SNSPRT_SM'] = Configuration::get('SNSPRT_SM');
		$values['SNSPRT_MD'] = Configuration::get('SNSPRT_MD');
		$values['SNSPRT_LG'] = Configuration::get('SNSPRT_LG');
		$values['SNSPRT_CLASSSFX'] = Configuration::get('SNSPRT_CLASSSFX');
		return $values;
	}
	public function hookDisplayHeader($params) {
		//$this->updateValue('PS_NB_DAYS_NEW_PRODUCT', 57);
		
		if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index')
			return;
		$this->context->controller->addJS($this->_path . 'js/snsproducttabs.js');
		$this->context->controller->addCSS($this->_path . 'css/style.css');
	}
	public function getVarAssign() {
		$context = Context::getContext();
		$id_lang = (int) $context->language->id;
		$vars = array();
		$xs = 12 / Configuration::get('SNSPRT_XS');
		$sm = 12 / Configuration::get('SNSPRT_SM');
		$md = 12 / Configuration::get('SNSPRT_MD');
		$lg = 12 / Configuration::get('SNSPRT_LG');
		$vars['item_class'] = 'col-xs-'.$xs.' col-sm-'.$sm.' col-md-'.$md.' col-lg-'.$lg.' col-phone-12';
		
		$lang_fields = array('SNSPRT_TITLE', 'SNSPRT_PRETEXT', 'SNSPRT_POSTEXT');
		$fields = array('SNSPRT_NUMDISPLAY', 'SNSPRT_NUMLOAD', 'SNSPRT_EFFECT', 'SNSPRT_XS', 'SNSPRT_SM', 'SNSPRT_MD', 'SNSPRT_LG', 'SNSPRT_CLASSSFX');

		foreach($fields as $field)
			$vars[$field] = Configuration::get($field);
		
		foreach($lang_fields as $field) 
		{
			if(is_bool(Configuration::get($field, $id_lang)))
				$vars[$field] = Configuration::get($field, Configuration::get('PS_LANG_DEFAULT'));
			else
				$vars[$field] = Configuration::get($field, $id_lang);
		}
		
		$vars['xs'] = $xs;
		$vars['sm'] = $sm;
		$vars['md'] = $md;
		$vars['lg'] = $lg;
		
		return $vars;
	}
	public function displaySNSProductTabs()
	{
		$context = Context::getContext();
		$id_lang = (int) $context->language->id;
		
		$cache_id = $this->getCacheId('snsproducttabs');
		if (!$this->isCached('snsproducttabs.tpl', $cache_id))
		{
			$tabs = $this->_getData();
			if (empty($tabs))
				return;
			$context->smarty->assign(array('tabs' => $tabs));
			$context->smarty->assign($this->getVarAssign());
			$context->smarty->assign(array('homeSize' => Image::getSize(ImageType::getFormatedName('home'))));
		}
		
		return $this->display(__FILE__, 'snsproducttabs.tpl', $cache_id);
	}
	public function hookDisplayHome() {
		return $this->displaySNSProductTabs();
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
			//$product_list = $context->smarty->fetch(_PS_THEME_DIR_.'product-tabs.tpl');
			$vars = array(
				'productList' => $product_list
			);
			die( Tools::jsonEncode($vars));
		}
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
	public function clearCache() {
		$this->_clearCache('snsproducttabs.tpl');
	}
	public function hookAddProduct($params) {
		$this->clearCache();
	}
	public function hookUpdateProduct($params) {
		$this->clearCache();
	}
	public function hookDeleteProduct($params) {
		$this->clearCache();
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
					), '`name` = \''.pSQL($key).'\''.SNSProductTabs::sqlRestriction($id_shop_group, $id_shop), 1, true);
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
										.SNSProductTabs::sqlRestriction($id_shop_group, $id_shop)
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

