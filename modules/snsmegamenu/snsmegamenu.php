<?php
/*
* 2007-2014 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

require (dirname(__FILE__).'/snsmenuclass.php');

class Snsmegamenu extends Module
{
	private $_menu = '';
	private $_html = '';
	private $_menucmscat = '';
	private $user_groups;

	/*
	 * Pattern for matching config values
	 */
	private $pattern = '/^([A-Z_]*)[0-9]+/';

	/*
	 * Name of the controller
	 * Used to set item selected or not in top menu
	 */
	private $page_name = '';

	/*
	 * Spaces per depth in BO
	 */
	private $spacer_size = '5';

	public function __construct()
	{
		$this->name = 'snsmegamenu';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'SNS Theme';

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('SNS Mega Menu');
		$this->description = $this->l('Adds a new menu to your e-commerce website.');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install($delete_params = true) {
		if (!parent::install() ||
			!$this->registerHook('displayMainMenu') ||
			!$this->registerHook('actionObjectCategoryUpdateAfter') ||
			!$this->registerHook('actionObjectCategoryDeleteAfter') ||
			!$this->registerHook('actionObjectCategoryAddAfter') ||
			!$this->registerHook('actionObjectCmsUpdateAfter') ||
			!$this->registerHook('actionObjectCmsDeleteAfter') ||
			!$this->registerHook('actionObjectCmsAddAfter') ||
			!$this->registerHook('actionObjectSupplierUpdateAfter') ||
			!$this->registerHook('actionObjectSupplierDeleteAfter') ||
			!$this->registerHook('actionObjectSupplierAddAfter') ||
			!$this->registerHook('actionObjectManufacturerUpdateAfter') ||
			!$this->registerHook('actionObjectManufacturerDeleteAfter') ||
			!$this->registerHook('actionObjectManufacturerAddAfter') ||
			!$this->registerHook('actionObjectProductUpdateAfter') ||
			!$this->registerHook('actionObjectProductDeleteAfter') ||
			!$this->registerHook('actionObjectProductAddAfter') ||
			!$this->registerHook('categoryUpdate'))// ||
			//!$this->registerHook('actionObjectLanguageAddAfter') ||
			//!$this->registerHook('actionObjectShopAddAfter'))
			return false;
		$this->_createTab();
		$this->clearMenuCache();

		if ($delete_params)
			if (!$this->installDb() 
			|| !Configuration::updateGlobalValue('SNSMM_ITEMS', 'LNK1,LNK2,CAT3,LNK3,CMS4')
			//|| !Configuration::updateGlobalValue('SNSMM_STICKYMENU', '1')
			|| !Configuration::updateGlobalValue('SNSMM_RESMENU', '1'))
				return false;

		return true;
	}

	public function installDb() {
		return (Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'snsmegamenu` (
			`id_megamenu` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`id_shop` INT(11) UNSIGNED NOT NULL,
			`new_window` TINYINT( 1 ) NOT NULL,
			`is_drop` TINYINT( 1 ) NOT NULL,
			INDEX (`id_shop`)
		) ENGINE = '._MYSQL_ENGINE_.' CHARACTER SET utf8 COLLATE utf8_general_ci;')
		&& Db::getInstance()->execute('
			 CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'snsmegamenu_lang` (
			`id_megamenu` INT(11) UNSIGNED NOT NULL,
			`id_lang` INT(11) UNSIGNED NOT NULL,
			`id_shop` INT(11) UNSIGNED NOT NULL,
			`label` VARCHAR( 128 ) NOT NULL ,
			`link` VARCHAR( 128 ) NOT NULL ,
			`customhtml` text,
			INDEX ( `id_megamenu` , `id_lang`, `id_shop`)
		) ENGINE = '._MYSQL_ENGINE_.' CHARACTER SET utf8 COLLATE utf8_general_ci;')
		&&  Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'snsmegamenu` (`id_megamenu`, `id_shop`, `new_window`, `is_drop`) VALUES
			(1, 1, 0, 0),
			(2, 1, 0, 1),
			(3, 1, 0, 1),
			(4, 1, 0, 0);
		')
		&&  Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'snsmegamenu_lang` (`id_megamenu`, `id_lang`, `id_shop`, `label`, `link`, `customhtml`) VALUES
			(1, 1, 1, \'Home\', \'[__HOME__]\', \'\'),
			(2, 1, 1, \'Men\', \'[__CATID_3__]\', \'<div class="row">\r\n<div class="col-sm-6">\r\n<div class="row">\r\n<div class="col-sm-4">\r\n<h4 class="title">Mobile</h4>\r\n<ul class="menu">\r\n<li><a href="#">Dresses</a></li>\r\n<li><a href="#">Jumpsuits</a></li>\r\n<li><a href="#">Blouses & Tops</a></li>\r\n<li><a href="#">Skirts</a></li>\r\n</ul>\r\n</div>\r\n<div class="col-sm-4">\r\n<h4 class="title">Accessories</h4>\r\n<ul class="menu">\r\n<li><a href="#">Skirts</a></li>\r\n<li><a href="#">Jumpsuits</a></li>\r\n<li><a href="#">Blouses & Tops</a></li>\r\n<li><a href="#">Dress Pants</a></li>\r\n</ul>\r\n</div>\r\n<div class="col-sm-4">\r\n<h4 class="title">Mobile</h4>\r\n<ul class="menu">\r\n<li><a href="#">Dresses</a></li>\r\n<li><a href="#">Jumpsuits</a></li>\r\n<li><a href="#">Skirts</a></li>\r\n<li><a href="#">Dress Pants</a></li>\r\n</ul>\r\n</div>\r\n</div>\r\n<br />\r\n<div class="row">\r\n<div class="col-sm-4">\r\n<h4 class="title">Mobile</h4>\r\n<ul class="menu">\r\n<li><a href="#">Dresses</a></li>\r\n<li><a href="#">Blouses & Tops</a></li>\r\n<li><a href="#">Skirts</a></li>\r\n<li><a href="#">Dress Pants</a></li>\r\n</ul>\r\n</div>\r\n<div class="col-sm-4">\r\n<h4 class="title">Accessories</h4>\r\n<ul class="menu">\r\n<li><a href="#">Skirts</a></li>\r\n<li><a href="#">Jumpsuits</a></li>\r\n<li><a href="#">Blouses & Tops</a></li>\r\n<li><a href="#">Dress Pants</a></li>\r\n</ul>\r\n</div>\r\n<div class="col-sm-4">\r\n<h4 class="title">Mobile</h4>\r\n<ul class="menu">\r\n<li><a href="#">Jumpsuits</a></li>\r\n<li><a href="#">Blouses & Tops</a></li>\r\n<li><a href="#">Skirts</a></li>\r\n<li><a href="#">Dress Pants</a></li>\r\n</ul>\r\n</div>\r\n</div>\r\n</div>\r\n<div class="col-sm-6"><a class="banner" href="#"><img src="__SNSPS_BASE_URI__modules/snsmegamenu/img/nazic/menu3.jpg" alt="" /></a><br />\r\n<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'\'t look even slightly believable.</p>\r\n</div>\r\n</div>\'),
			(3, 1, 1, \'Accessories\', \'[__CATID_3__]\', \'<div class="wrap_rightblock">\r\n<div class="row">\r\n<div class="col-sm-2">\r\n<h4 class="title">Gloves</h4>\r\n<ul class="menu">\r\n<li><a href="#"> Men\'\'s Clothing </a></li>\r\n<li class="has-child"><a href="#"> Blog </a>\r\n<div class="wrap_submenu">\r\n<ul>\r\n<li><a href="#"> Men\'\'s Clothing </a></li>\r\n<li><a href="#"> Blog </a></li>\r\n<li class="has-child"><a href="#"> Blog </a>\r\n<div class="wrap_submenu">\r\n<ul>\r\n<li><a href="#"> Men\'\'s Clothing </a></li>\r\n<li class="has-child"><a href="#"> Blog </a>\r\n<div class="wrap_submenu">\r\n<ul>\r\n<li><a href="#"> Men\'\'s Clothing </a></li>\r\n<li><a href="#"> Blog </a></li>\r\n<li><a href="#"> Scarves </a></li>\r\n<li><a href="#"> Lifestyle </a></li>\r\n<li><a href="#"> Bras </a></li>\r\n</ul>\r\n</div>\r\n</li>\r\n<li><a href="#"> Scarves </a></li>\r\n<li><a href="#"> Lifestyle </a></li>\r\n<li><a href="#"> Bras </a></li>\r\n</ul>\r\n</div>\r\n</li>\r\n<li><a href="#"> Lifestyle </a></li>\r\n<li><a href="#"> Bras </a></li>\r\n</ul>\r\n</div>\r\n</li>\r\n<li><a href="#"> Scarves </a></li>\r\n<li><a href="#"> Lifestyle </a></li>\r\n<li><a href="#"> Bras </a></li>\r\n</ul>\r\n</div>\r\n<div class="col-sm-2">\r\n<h4 class="title">Gloves</h4>\r\n<ul class="menu">\r\n<li><a href="#"> Men\'\'s Clothing </a></li>\r\n<li><a href="#"> Blog </a></li>\r\n<li><a href="#"> Scarves </a></li>\r\n<li><a href="#"> Lifestyle </a></li>\r\n<li><a href="#"> Bras </a></li>\r\n</ul>\r\n</div>\r\n<div class="col-sm-2">\r\n<h4 class="title">Gloves</h4>\r\n<ul class="menu">\r\n<li><a href="#"> Men\'\'s Clothing </a></li>\r\n<li><a href="#"> Blog </a></li>\r\n<li><a href="#"> Scarves </a></li>\r\n<li><a href="#"> Lifestyle </a></li>\r\n<li><a href="#"> Bras </a></li>\r\n</ul>\r\n</div>\r\n<div class="col-sm-2">\r\n<h4 class="title">Gloves</h4>\r\n<ul class="menu">\r\n<li><a href="#"> Men\'\'s Clothing </a></li>\r\n<li><a href="#"> Blog </a></li>\r\n<li><a href="#"> Scarves </a></li>\r\n<li><a href="#"> Lifestyle </a></li>\r\n<li><a href="#"> Bras </a></li>\r\n</ul>\r\n</div>\r\n<div class="col-sm-2">\r\n<h4 class="title">Gloves</h4>\r\n<ul class="menu">\r\n<li><a href="#"> Men\'\'s Clothing </a></li>\r\n<li><a href="#"> Blog </a></li>\r\n<li><a href="#"> Scarves </a></li>\r\n<li><a href="#"> Lifestyle </a></li>\r\n<li><a href="#"> Bras </a></li>\r\n</ul>\r\n</div>\r\n<div class="col-sm-2">\r\n<h4 class="title">Gloves</h4>\r\n<ul class="menu">\r\n<li><a href="#"> Men\'\'s Clothing </a></li>\r\n<li><a href="#"> Blog </a></li>\r\n<li><a href="#"> Scarves </a></li>\r\n<li><a href="#"> Lifestyle </a></li>\r\n<li><a href="#"> Bras </a></li>\r\n</ul>\r\n</div>\r\n</div>\r\n</div>\r\n<p> </p>\r\n<div class="wrap_bottomblock">\r\n<div class="row">\r\n<div class="col-sm-6"><a class="banner" href="#"><img src="__SNSPS_BASE_URI__modules/snsmegamenu/img/nazic/menu1.jpg" alt="" /></a></div>\r\n<div class="col-sm-6"><a class="banner" href="#"><img src="__SNSPS_BASE_URI__modules/snsmegamenu/img/nazic/menu2.jpg" alt="" /></a></div>\r\n</div>\r\n</div>\'),
			(4, 1, 1, \'Blog\', \'[__HOME__]snsblog.html\', \'\');
		'));
	}

	public function uninstall($delete_params = true) {
		if (!parent::uninstall())
			return false;

		$this->clearMenuCache();

		if ($delete_params)
			if (!$this->uninstallDB() 
				|| !Configuration::deleteByName('SNSMM_ITEMS')
				//|| !Configuration::deleteByName('SNSMM_STICKYMENU')
				|| !Configuration::deleteByName('SNSMM_RESMENU')
			)
				return false;
		$this->_deleteTab();
		return true;
	}

	private function uninstallDb()
	{
		Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'snsmegamenu`');
		Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'snsmegamenu_lang`');
		return true;
	}

	public function reset() {
		if (!$this->uninstall(false))
			return false;
		if (!$this->install(false))
			return false;
		return true;
	}

	public function getContent()
	{
		$this->context->controller->addjQueryPlugin('hoverIntent');

		$id_lang = (int)Context::getContext()->language->id;
		$languages = $this->context->controller->getLanguages();
		$default_language = (int)Configuration::get('PS_LANG_DEFAULT');

		$labels = Tools::getValue('label') ? array_filter(Tools::getValue('label'), 'strlen') : array();
		$links_label = Tools::getValue('link') ? array_filter(Tools::getValue('link'), 'strlen') : array();
		$customhtml = Tools::getValue('customhtml') ? array_filter(Tools::getValue('customhtml'), 'strlen') : array();
		$customhtml = $this->replaceLinkContent($customhtml, true);
		$spacer = str_repeat('&nbsp;', $this->spacer_size);
		$divLangName = 'link_label';

		$update_cache = false;

		if (Tools::isSubmit('submitSnsmegamenu'))
		{
			$errors_update_shops = array();
			$items = Tools::getValue('items');
			$shops = Shop::getContextListShopID();


			foreach ($shops as $shop_id)
			{
				$shop_group_id = Shop::getGroupFromShop($shop_id);
				$updated = true;

				if (count($shops) == 1) {
					if (is_array($items) && count($items))
		 				$updated = Configuration::updateValue('SNSMM_ITEMS', (string)implode(',', $items), false, (int)$shop_group_id, (int)$shop_id);
		 			else
		 				$updated = Configuration::updateValue('SNSMM_ITEMS', '', false, (int)$shop_group_id, (int)$shop_id);
		 		}

		 		//$updated &= Configuration::updateValue('SNSMM_STICKYMENU', (bool)Tools::getValue('SNSMM_STICKYMENU'), false, (int)$shop_group_id, (int)$shop_id);
		 		$updated &= Configuration::updateValue('SNSMM_RESMENU', Tools::getValue('SNSMM_RESMENU'), false, (int)$shop_group_id, (int)$shop_id);
	 			if (!$updated)
	 			{
	 				$shop = new Shop($shop_id);
	 				$errors_update_shops[] =  $shop->name;
	 			}

			}

 			if (!count($errors_update_shops))
				$this->_html .= $this->displayConfirmation($this->l('The settings have been updated.'));
			else
				$this->_html .= $this->displayError(sprintf($this->l('Unable to update settings for the following shop(s): %s'), implode(', ', $errors_update_shops)));

			$update_cache = true;
		}
		else if (Tools::isSubmit('submitSnsmegamenuLinks'))
		{
			$errors_add_link = array();

			foreach ($languages as $key => $val)
			{
				$links_label[$val['id_lang']] = Tools::getValue('link_'.(int)$val['id_lang']);
				$labels[$val['id_lang']] = Tools::getValue('label_'.(int)$val['id_lang']);
				$customhtml[$val['id_lang']] = Tools::getValue('customhtml_'.(int)$val['id_lang']);
			}

			$count_links_label = count($links_label);
			$count_customhtml = count($customhtml);
			$count_label = count($labels);

			if ($count_links_label || $count_label || $count_customhtml) {
				if (!$count_links_label)
					$this->_html .= $this->displayError($this->l('Please complete the "Link" field.'));
				if (!$count_customhtml)
					$this->_html .= $this->displayError($this->l('Please complete the "Custom HTML" field.'));
				elseif (!$count_label)
					$this->_html .= $this->displayError($this->l('Please add a label.'));
				elseif (!isset($labels[$default_language]))
					$this->_html .= $this->displayError($this->l('Please add a label for your default language.'));
				else
				{
					$shops = Shop::getContextListShopID();

					foreach ($shops as $shop_id)
					{
						$added = SNSMenuClass::add($links_label, $labels, $this->replaceLinkContent($customhtml, false), Tools::getValue('is_drop', 0), Tools::getValue('new_window', 0), (int)$shop_id);

						if (!$added)
						{
							$shop = new Shop($shop_id);
 							$errors_add_link[] =  $shop->name;
						}

					}

					if (!count($errors_add_link))
						$this->_html .= $this->displayConfirmation($this->l('The link has been added.'));
					else
						$this->_html .= $this->displayError(sprintf($this->l('Unable to add link for the following shop(s): %s'), implode(', ', $errors_add_link)));
				}
			}
			$update_cache = true;

		}
		elseif (Tools::isSubmit('deletesnsmegamenu'))
		{
			$errors_delete_link = array();
			$id_megamenu = Tools::getValue('id_megamenu', 0);
			$shops = Shop::getContextListShopID();

			foreach ($shops as $shop_id)
			{
				$deleted = SNSMenuClass::remove($id_megamenu, (int)$shop_id);
				Configuration::updateValue('SNSMM_ITEMS', str_replace(array('LNK'.$id_megamenu.',', 'LNK'.$id_megamenu), '', Configuration::get('SNSMM_ITEMS')));

				if (!$deleted)
				{
					$shop = new Shop($shop_id);
					$errors_delete_link[] =  $shop->name;
				}

			}

			if (!count($errors_delete_link))
				$this->_html .= $this->displayConfirmation($this->l('The link has been removed.'));
			else
				$this->_html .= $this->displayError(sprintf($this->l('Unable to remove link for the following shop(s): %s'), implode(', ', $errors_delete_link)));

			$update_cache = true;
		}
		elseif (Tools::isSubmit('updatesnsmegamenu'))
		{
			$id_megamenu = (int)Tools::getValue('id_megamenu', 0);
			$id_shop = (int)Shop::getContextShopID();

			if (Tools::isSubmit('updatelink'))
			{
				$link = array();
				$label = array();
				$customhtml = array();
				$new_window = (int)Tools::getValue('new_window', 0);
				$is_drop = (int)Tools::getValue('is_drop', 0);

				foreach (Language::getLanguages(false) as $lang)
				{
					$link[$lang['id_lang']] = Tools::getValue('link_'.(int)$lang['id_lang']);
					$label[$lang['id_lang']] = Tools::getValue('label_'.(int)$lang['id_lang']);
					$customhtml[$lang['id_lang']] = Tools::getValue('customhtml_'.(int)$lang['id_lang']);
				}

				SNSMenuClass::update($link, $label, $this->replaceLinkContent($customhtml, false), $is_drop, $new_window, (int)$id_shop, (int)$id_megamenu, (int)$id_megamenu);
				$this->_html .= $this->displayConfirmation($this->l('The link has been edited.'));
			}
			$update_cache = true;
		}

		if ($update_cache)
			$this->clearMenuCache();


		$shops = Shop::getContextListShopID();
		$links = array();

		if (count($shops) > 1)
			$this->_html .= $this->getWarningMultishopHtml();

		if (Shop::isFeatureActive())
			$this->_html .= $this->getCurrentShopInfoMsg();

		$this->_html .= $this->renderForm().$this->renderAddForm();

		foreach ($shops as $shop_id)
			$links = array_merge($links, SNSMenuClass::gets((int)$id_lang, null, (int)$shop_id));

		if (!count($links))
			return $this->_html;

		$this->_html .= $this->renderList();
		return $this->_html;
	}

	private function getWarningMultishopHtml()
	{
		return '<p class="alert alert-warning">'.
					$this->l('You cannot manage top menu items from a "All Shops" or a "Group Shop" context, select directly the shop you want to edit').
				'</p>';
	}

	private function getCurrentShopInfoMsg()
	{
		$shop_info = null;

		if (Shop::getContext() == Shop::CONTEXT_SHOP)
			$shop_info = $this->l(sprintf('The modifications will be applied to shop: %s', $this->context->shop->name));
		else if (Shop::getContext() == Shop::CONTEXT_GROUP)
			$shop_info = $this->l(sprintf('The modifications will be applied to this group: %s', Shop::getContextShopGroup()->name));
		else
			$shop_info = $this->l('The modifications will be applied to all shops');

		return '<div class="alert alert-info">'.
					$shop_info.
				'</div>';
	}

	private function getMenuItems()
	{
		$items = Tools::getValue('items');
		if (is_array($items) && count($items))
			return $items;
		else
		{
			$shops = Shop::getContextListShopID();
			$conf = null;

			if (count($shops) > 1)
			{
				foreach ($shops as $key => $shop_id)
				{
					$shop_group_id = Shop::getGroupFromShop($shop_id);
					$conf .= (string)($key > 1 ? ',' : '').Configuration::get('SNSMM_ITEMS', null, $shop_group_id, $shop_id);
				}
			}
			else
			{
				$shop_id = (int)$shops[0];
				$shop_group_id = Shop::getGroupFromShop($shop_id);
				$conf = Configuration::get('SNSMM_ITEMS', null, $shop_group_id, $shop_id);
			}

			if (strlen($conf))
				return explode(',', $conf);
			else
				return array();
		}
	}

	private function makeMenuOption()
	{
		$id_shop = (int)Shop::getContextShopID();

		$menu_item = $this->getMenuItems();
		$id_lang = (int)$this->context->language->id;

		$html = '<select multiple="multiple" name="items[]" id="items" style="width: 300px; height: 160px;">';
		foreach ($menu_item as $item)
		{
			if (!$item)
				continue;

			preg_match($this->pattern, $item, $values);
			$id = (int)substr($item, strlen($values[1]), strlen($item));

			switch (substr($item, 0, strlen($values[1])))
			{
				case 'CAT':
					$category = new Category((int)$id, (int)$id_lang);
					if (Validate::isLoadedObject($category))
						$html .= '<option selected="selected" value="CAT'.$id.'">'.$category->name.'</option>'.PHP_EOL;
					break;

				case 'PRD':
					$product = new Product((int)$id, true, (int)$id_lang);
					if (Validate::isLoadedObject($product))
						$html .= '<option selected="selected" value="PRD'.$id.'">'.$product->name.'</option>'.PHP_EOL;
					break;

				case 'CMS':
					$cms = new CMS((int)$id, (int)$id_lang);
					if (Validate::isLoadedObject($cms))
						$html .= '<option selected="selected" value="CMS'.$id.'">'.$cms->meta_title.'</option>'.PHP_EOL;
					break;

				case 'CMS_CAT':
					$category = new CMSCategory((int)$id, (int)$id_lang);
					if (Validate::isLoadedObject($category))
						$html .= '<option selected="selected" value="CMS_CAT'.$id.'">'.$category->name.'</option>'.PHP_EOL;
					break;

				// Case to handle the option to show all Manufacturers
				case 'ALLMAN':
					$html .= '<option selected="selected" value="ALLMAN0">'.$this->l('All manufacturers').'</option>'.PHP_EOL;
					break;

				case 'MAN':
					$manufacturer = new Manufacturer((int)$id, (int)$id_lang);
					if (Validate::isLoadedObject($manufacturer))
						$html .= '<option selected="selected" value="MAN'.$id.'">'.$manufacturer->name.'</option>'.PHP_EOL;
					break;

				// Case to handle the option to show all Suppliers
				case 'ALLSUP':
					$html .= '<option selected="selected" value="ALLSUP0">'.$this->l('All suppliers').'</option>'.PHP_EOL;
					break;

				case 'SUP':
					$supplier = new Supplier((int)$id, (int)$id_lang);
					if (Validate::isLoadedObject($supplier))
						$html .= '<option selected="selected" value="SUP'.$id.'">'.$supplier->name.'</option>'.PHP_EOL;
					break;

				case 'LNK':
					$link = SNSMenuClass::get((int)$id, (int)$id_lang, (int)$id_shop);
					if (count($link))
					{
						if (!isset($link[0]['label']) || ($link[0]['label'] == ''))
						{
							$default_language = Configuration::get('PS_LANG_DEFAULT');
							$link = SNSMenuClass::get($link[0]['id_megamenu'], (int)$default_language, (int)Shop::getContextShopID());
						}
						$html .= '<option selected="selected" value="LNK'.(int)$link[0]['id_megamenu'].'">'.Tools::safeOutput($link[0]['label']).'</option>';
					}
					break;

				case 'SHOP':
					$shop = new Shop((int)$id);
					if (Validate::isLoadedObject($shop))
						$html .= '<option selected="selected" value="SHOP'.(int)$id.'">'.$shop->name.'</option>'.PHP_EOL;
					break;
			}
		}

		return $html.'</select>';
	}

	private function makeMenu()
	{
		$menu_items = $this->getMenuItems();
		$id_lang = (int)$this->context->language->id;
		$id_shop = (int)Shop::getContextShopID();

		foreach ($menu_items as $item)
		{
			if (!$item)
				continue;

			preg_match($this->pattern, $item, $value);
			$id = (int)substr($item, strlen($value[1]), strlen($item));

			switch (substr($item, 0, strlen($value[1])))
			{
				case 'CAT':
					$this->_menu .= $this->generateCategoriesMenu(Category::getNestedCategories($id, $id_lang, true, $this->user_groups));
					break;

				case 'PRD':
					$liClass = 'level0 prd-item';
					$liClass .= ($this->page_name == 'product' && (Tools::getValue('id_product') == $id)) ? ' active' : '';
					$product = new Product((int)$id, true, (int)$id_lang);
					if (!is_null($product->id))
						$this->_menu .= '<li class="'.$liClass.'"><a class="menu-title-lv0" href="'.Tools::HtmlEntitiesUTF8($product->getLink()).'" title="'.$product->name.'"><span>'.$product->name.'</span></a></li>'.PHP_EOL;
					break;

				case 'CMS':
					$liClass = 'level0 cms-item';
					$liClass .= ($this->page_name == 'cms' && (Tools::getValue('id_cms') == $id)) ? ' active' : '';
					$cms = CMS::getLinks((int)$id_lang, array($id));
					if (count($cms))
						$this->_menu .= '<li class="'.$liClass.'"><a class="menu-title-lv0" href="'.Tools::HtmlEntitiesUTF8($cms[0]['link']).'" title="'.Tools::safeOutput($cms[0]['meta_title']).'"><span>'.Tools::safeOutput($cms[0]['meta_title']).'</span></a></li>'.PHP_EOL;
					break;

				case 'CMS_CAT':
					$category = new CMSCategory((int)$id, (int)$id_lang);
					if (count($category)) {
						$this->getCMSMenuItems($category->id);
						$liClass = 'level0 cmscat-item';
						if($this->_menucmscat != '') $liClass .= ' has-child';
						
						$this->_menu .= '<li class="'.$liClass.'"><a class="menu-title-lv0" href="'.Tools::HtmlEntitiesUTF8($category->getLink()).'" title="'.$category->name.'"><span>'.$category->name.'</span></a>';
						$this->_menu .= $this->_menucmscat;
						$this->_menucmscat = '';
						$this->_menu .= '</li>'.PHP_EOL;
					}
					break;

				// Case to handle the option to show all Manufacturers
				case 'ALLMAN':
					$link = new Link;
					$manufacturers = Manufacturer::getManufacturers();
					
					$liClass = 'level0 allman-item';
					if(count($manufacturers)) $liClass .= ' has-child';
					$this->_menu .= '<li class="'.$liClass.'"><a class="menu-title-lv0" href="'.$link->getPageLink('manufacturer').'" title="'.$this->l('All manufacturers').'"><span>'.$this->l('All manufacturers').'</span></a><div class="wrap_submenu"><ul class="level0">'.PHP_EOL;
					
					foreach ($manufacturers as $key => $manufacturer)
						$this->_menu .= '<li class="level1"><a class="menu-title-lv1" href="'.$link->getManufacturerLink((int)$manufacturer['id_manufacturer'], $manufacturer['link_rewrite']).'" title="'.Tools::safeOutput($manufacturer['name']).'"><span>'.Tools::safeOutput($manufacturer['name']).'</span></a></li>'.PHP_EOL;
					$this->_menu .= '</ul></div>';
					break;

				case 'MAN':
					$liClass = 'level0 man-item';
					$liClass .= ($this->page_name == 'manufacturer' && (Tools::getValue('id_manufacturer') == $id)) ? ' active' : '';
					$manufacturer = new Manufacturer((int)$id, (int)$id_lang);
					if (!is_null($manufacturer->id)) {
						if (intval(Configuration::get('PS_REWRITING_SETTINGS')))
							$manufacturer->link_rewrite = Tools::link_rewrite($manufacturer->name);
						else
							$manufacturer->link_rewrite = 0;
						$link = new Link;
						$this->_menu .= '<li class="'.$liClass.'"><a class="menu-title-lv0" href="'.Tools::HtmlEntitiesUTF8($link->getManufacturerLink((int)$id, $manufacturer->link_rewrite)).'" title="'.Tools::safeOutput($manufacturer->name).'"><span>'.Tools::safeOutput($manufacturer->name).'</span></a></li>'.PHP_EOL;
					}
					break;

				// Case to handle the option to show all Suppliers
				case 'ALLSUP':
					$link = new Link;
					$suppliers = Supplier::getSuppliers();
				
					$liClass = 'level0 allsup-item';
					if(count($suppliers)) $liClass .= ' has-child';
					$this->_menu .= '<li class="'.$liClass.'"><a class="menu-title-lv0" href="'.$link->getPageLink('supplier').'" title="'.$this->l('All suppliers').'"><span>'.$this->l('All suppliers').'</span></a><div class="wrap_submenu"><ul class="level0">'.PHP_EOL;
					foreach ($suppliers as $key => $supplier)
						$this->_menu .= '<li class="level1"><a class="menu-title-lv1" href="'.$link->getSupplierLink((int)$supplier['id_supplier'], $supplier['link_rewrite']).'" title="'.Tools::safeOutput($supplier['name']).'"><span>'.Tools::safeOutput($supplier['name']).'</span></a></li>'.PHP_EOL;
					$this->_menu .= '</ul></div>';
					break;

				case 'SUP':
					$liClass = 'level0 sup-item';
					$liClass .= ($this->page_name == 'supplier' && (Tools::getValue('id_supplier') == $id)) ? ' active' : '';
					$supplier = new Supplier((int)$id, (int)$id_lang);
					if (!is_null($supplier->id)) {
						$link = new Link;
						$this->_menu .= '<li class="'.$liClass.'"><a class="menu-title-lv0" href="'.Tools::HtmlEntitiesUTF8($link->getSupplierLink((int)$id, $supplier->link_rewrite)).'" title="'.$supplier->name.'"><span>'.$supplier->name.'</span></a></li>'.PHP_EOL;
					}
					break;

				case 'SHOP':
					$liClass = 'level0 shop-item';
					$liClass .= ($this->page_name == 'index' && ($this->context->shop->id == $id)) ? ' active' : '';
					$shop = new Shop((int)$id);
					if (Validate::isLoadedObject($shop))
					{
						$link = new Link;
						$this->_menu .= '<li class="'.$liClass.'"><a class="menu-title-lv0" href="'.Tools::HtmlEntitiesUTF8($shop->getBaseURL()).'" title="'.$shop->name.'"><span>'.$shop->name.'</span></a></li>'.PHP_EOL;
					}
					break;
				case 'LNK':
					//var_dump(_PS_BASE_URL_.__PS_BASE_URI__); die;
					
					$link = SNSMenuClass::get((int)$id, (int)$id_lang, (int)$id_shop);
					if (count($link)) {
						if (!isset($link[0]['label']) || ($link[0]['label'] == '')) {
							$default_language = Configuration::get('PS_LANG_DEFAULT');
							$link = SNSMenuClass::get($link[0]['id_megamenu'], $default_language, (int)Shop::getContextShopID());
						}
						$link[0]['link'] = $this->replaceLink($link[0]['link']);
						$liClass = 'level0 custom-item';
						if($link[0]['is_drop'] && $this->replaceLinkContent($link[0]['customhtml'], true) != '')
							$liClass .= ' has-child';
						$this->_menu .= '<li class="'.$liClass.'">';
						$this->_menu .=	'<a class="menu-title-lv0" href="'.Tools::HtmlEntitiesUTF8($link[0]['link']).'"'.(($link[0]['new_window']) ? ' onclick="return !window.open(this.href);"': '').' title="'.Tools::safeOutput($link[0]['label']).'"><span>'.Tools::safeOutput($link[0]['label']).'</span></a>';
						
						if($link[0]['is_drop'] && $this->replaceLinkContent($link[0]['customhtml'], true) != '') {
							$this->_menu .= '<div class="wrap_dropdown fullwidth">';
							$this->_menu .= $this->replaceLinkContent($link[0]['customhtml'], true);
							$this->_menu .= '</div>';
						}
						
						$this->_menu .= '</li>'.PHP_EOL;
					}
					break;
			}
		}
	}
	private function replaceLink($string) {
		$id_lang = (int)$this->context->language->id;
		$id_shop = (int)Shop::getContextShopID();
		//$lang_iso = (in_array($id_shop, array($this->context->shop->id,  null)) || !Language::isMultiLanguageActivated($id_shop) || !(int)Configuration::get('PS_REWRITING_SETTINGS', null, null, $id_shop)) ? Language::getIsoById($id_lang).'/' : '';
		$languages = Language::getLanguages(true, $this->context->shop->id);
		$lang_iso = (count($languages) > 1) ? Language::getIsoById($id_lang).'/' : '';
		
		preg_match('/\[__HOME__\]/', $string, $match);
		if(count($match))
			return preg_replace('/\[__HOME__\]/', _PS_BASE_URL_.__PS_BASE_URI__ . $lang_iso, $string);
		
		preg_match('/\[__(MANID|SUPID|CMSCATID|CATID|PRDID|CMSID)_([0-9]+)__\]/', $string, $match);
		if(count($match)) {
			switch ($match[1]) {
			    case 'MANID':
					return $this->context->link->getManufacturerLink($match[2]);
			        break;
			    case 'SUPID':
					return $this->context->link->getSupplierLink($match[2]);
			        break;
			    case 'CMSCATID':
					return $this->context->link->getCMSCategoryLink($match[2]);
			        break;
			    case 'CATID':
					return $this->context->link->getCategoryLink($match[2]);
			        break;
			    case 'PRDID':
					return $this->context->link->getProductLink($match[2]);
			        break;
			    case 'CMSID':
					return $this->context->link->getCMSLink($match[2]);
			        break;
			}
		}
		return $string;
	}	
	private function generateCategoriesOption($categories, $items_to_skip = null)
	{
		$html = '';

		foreach ($categories as $key => $category)
		{
			if (isset($items_to_skip) /*&& !in_array('CAT'.(int)$category['id_category'], $items_to_skip)*/)
			{
				$shop = (object) Shop::getShop((int)$category['id_shop']);
				$html .= '<option value="CAT'.(int)$category['id_category'].'">'
					.str_repeat('&nbsp;', $this->spacer_size * (int)$category['level_depth']).$category['name'].' ('.$shop->name.')</option>';
			}

			if (isset($category['children']) && !empty($category['children']))
				$html .= $this->generateCategoriesOption($category['children'], $items_to_skip);

		}
		return $html;
	}

	private function generateCategoriesMenu($categories, $is_children = 0, $level = 0)
	{
		$html = '';

		foreach ($categories as $key => $category)
		{
			if ($category['level_depth'] > 1)
			{
				$cat = new Category($category['id_category']);
				$link = Tools::HtmlEntitiesUTF8($cat->getLink());
			}
			else
				$link = $this->context->link->getPageLink('index');
			
			$liClass = '';
			$liClass .= 'level'.$level;
			if($level == 0) $liClass .= ' cat-item';
			if (isset($category['children']) && !empty($category['children'])) $liClass .= ' has-child';
			if($this->page_name == 'category' && (int)Tools::getValue('id_category') == (int)$category['id_category'])
				$liClass .= ' active';
			$html .= '<li class="'.$liClass.'">';
			$html .= '<a class="menu-title-lv'.$level.'" href="'.$link.'" title="'.$category['name'].'"><span>'.$category['name'].'</span></a>';

			if (isset($category['children']) && !empty($category['children'])) {
				$html .= '<div class="wrap_submenu"><ul class="level'.$level.'">';
				$html .= $this->generateCategoriesMenu($category['children'], 1, $level + 1);

//				if ((int)$category['level_depth'] > 1 && !$is_children) {
//					$files = scandir(_PS_CAT_IMG_DIR_);
//
//					if (count($files) > 0)
//					{
//						$html .= '<li class="category-thumbnail">';
//
//						foreach ($files as $file)
//							if (preg_match('/^'.$category['id_category'].'-([0-9])?_thumb.jpg/i', $file) === 1)
//								$html .= '<div><img src="'.$this->context->link->getMediaLink(_THEME_CAT_DIR_.$file)
//								.'" alt="'.Tools::SafeOutput($category['name']).'" title="'
//								.Tools::SafeOutput($category['name']).'" class="imgm" /></div>';
//
//						$html .= '</li>';
//					}
//				}

				$html .= '</ul></div>';
			}

			$html .= '</li>';
		}

		return $html;
	}

	private function getCMSMenuItems($parent, $depth = 1, $id_lang = false, $level = 0) {
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;

		if ($depth > 3)
			return;

		$categories = $this->getCMSCategories(false, (int)$parent, (int)$id_lang);
		$pages = $this->getCMSPages((int)$parent);

		if (count($categories) || count($pages)) {
			$this->_menucmscat .= '<div class="wrap_submenu"><ul class="level'.$level.'">';

			foreach ($categories as $category) {
				$cat = new CMSCategory((int)$category['id_cms_category'], (int)$id_lang);

				$this->_menucmscat .= '<li class="level'.($level + 1).'">';
				$this->_menucmscat .= '<a class="menu-title-lv'.($level + 1).'" href="'.Tools::HtmlEntitiesUTF8($cat->getLink()).'"><span>'.$category['name'].'</span></a>';
				$this->getCMSMenuItems($category['id_cms_category'], (int)$depth + 1, $level + 1);
				$this->_menucmscat .= '</li>';
			}

			foreach ($pages as $page) {
				$cms = new CMS($page['id_cms'], (int)$id_lang);
				$links = $cms->getLinks((int)$id_lang, array((int)$cms->id));
				
				$liClass = 'level' . ($level + 1);
				$liClass .= ($this->page_name == 'cms' && ((int)Tools::getValue('id_cms') == $page['id_cms'])) ? ' active' : '';
				
				$this->_menucmscat .= '<li class="'.$liClass.'">';
				$this->_menucmscat .= '<a class="menu-title-lv'.($level + 1).'" href="'.$links[0]['link'].'"><span>'.$cms->meta_title.'</span></a>';
				$this->_menucmscat .= '</li>';
			}

			$this->_menucmscat .= '</ul></div>';
		}
	}

	private function getCMSOptions($parent = 0, $depth = 1, $id_lang = false, $items_to_skip = null)
	{
		$html = '';
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
		$categories = $this->getCMSCategories(false, (int)$parent, (int)$id_lang);
		$pages = $this->getCMSPages((int)$parent, false, (int)$id_lang);

		$spacer = str_repeat('&nbsp;', $this->spacer_size * (int)$depth);

		foreach ($categories as $category)
		{
			if (isset($items_to_skip) && !in_array('CMS_CAT'.$category['id_cms_category'], $items_to_skip))
				$html .= '<option value="CMS_CAT'.$category['id_cms_category'].'" style="font-weight: bold;">'.$spacer.$category['name'].'</option>';
			$html .= $this->getCMSOptions($category['id_cms_category'], (int)$depth + 1, (int)$id_lang, $items_to_skip);
		}

		foreach ($pages as $page)
			if (isset($items_to_skip) && !in_array('CMS'.$page['id_cms'], $items_to_skip))
				$html .= '<option value="CMS'.$page['id_cms'].'">'.$spacer.$page['meta_title'].'</option>';

		return $html;
	}

	protected function getCacheId($name = null)
	{
		$page_name = in_array($this->page_name, array('category', 'supplier', 'manufacturer', 'cms', 'product')) ? $this->page_name : 'index';
		return parent::getCacheId().'|'.$page_name.($page_name != 'index' ? '|'.(int)Tools::getValue('id_'.$page_name) : '');
	}

	public function hookDisplayMainMenu($param)
	{
		$this->user_groups =  ($this->context->customer->isLogged() ? $this->context->customer->getGroups() : array(Configuration::get('PS_UNIDENTIFIED_GROUP')));
		$this->page_name = Dispatcher::getInstance()->getController();
		if (!$this->isCached('snsmegamenu.tpl', $this->getCacheId()))
		{
			if (Tools::isEmpty($this->_menu))
				$this->makeMenu();

			$shop_id = (int)$this->context->shop->id;
			$shop_group_id = Shop::getGroupFromShop($shop_id);
			//$this->smarty->assign('SNSMM_STICKYMENU', Configuration::get('SNSMM_STICKYMENU', null, $shop_group_id, $shop_id));
			$this->smarty->assign('SNSMM_RESMENU', Configuration::get('SNSMM_RESMENU', null, $shop_group_id, $shop_id));
			$this->smarty->assign('MENU', $this->_menu);
			$this->smarty->assign('this_path', $this->_path);
		}

		$html = $this->display(__FILE__, 'snsmegamenu.tpl', $this->getCacheId());
		return $html;
	}

	private function getCMSCategories($recursive = false, $parent = 1, $id_lang = false)
	{
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;

		if ($recursive === false)
		{
			$sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
				FROM `'._DB_PREFIX_.'cms_category` bcp
				INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
				ON (bcp.`id_cms_category` = cl.`id_cms_category`)
				WHERE cl.`id_lang` = '.(int)$id_lang.'
				AND bcp.`id_parent` = '.(int)$parent;

			return Db::getInstance()->executeS($sql);
		}
		else
		{
			$sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
				FROM `'._DB_PREFIX_.'cms_category` bcp
				INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
				ON (bcp.`id_cms_category` = cl.`id_cms_category`)
				WHERE cl.`id_lang` = '.(int)$id_lang.'
				AND bcp.`id_parent` = '.(int)$parent;

			$results = Db::getInstance()->executeS($sql);
			foreach ($results as $result)
			{
				$sub_categories = $this->getCMSCategories(true, $result['id_cms_category'], (int)$id_lang);
				if ($sub_categories && count($sub_categories) > 0)
					$result['sub_categories'] = $sub_categories;
				$categories[] = $result;
			}

			return isset($categories) ? $categories : false;
		}

	}

	private function getCMSPages($id_cms_category, $id_shop = false, $id_lang = false)
	{
		$id_shop = ($id_shop !== false) ? (int)$id_shop : (int)Context::getContext()->shop->id;
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;

		$sql = 'SELECT c.`id_cms`, cl.`meta_title`, cl.`link_rewrite`
			FROM `'._DB_PREFIX_.'cms` c
			INNER JOIN `'._DB_PREFIX_.'cms_shop` cs
			ON (c.`id_cms` = cs.`id_cms`)
			INNER JOIN `'._DB_PREFIX_.'cms_lang` cl
			ON (c.`id_cms` = cl.`id_cms`)
			WHERE c.`id_cms_category` = '.(int)$id_cms_category.'
			AND cs.`id_shop` = '.(int)$id_shop.'
			AND cl.`id_lang` = '.(int)$id_lang.'
			AND c.`active` = 1
			ORDER BY `position`';

		return Db::getInstance()->executeS($sql);
	}

	public function hookActionObjectCategoryAddAfter($params)
	{
		$this->clearMenuCache();
	}

	public function hookActionObjectCategoryUpdateAfter($params)
	{
		$this->clearMenuCache();
	}

	public function hookActionObjectCategoryDeleteAfter($params)
	{
		$this->clearMenuCache();
	}

	public function hookActionObjectCmsUpdateAfter($params)
	{
		$this->clearMenuCache();
	}

	public function hookActionObjectCmsDeleteAfter($params)
	{
		$this->clearMenuCache();
	}

	public function hookActionObjectCmsAddAfter($params)
	{
		$this->clearMenuCache();
	}

	public function hookActionObjectSupplierUpdateAfter($params)
	{
		$this->clearMenuCache();
	}

	public function hookActionObjectSupplierDeleteAfter($params)
	{
		$this->clearMenuCache();
	}

	public function hookActionObjectSupplierAddAfter($params)
	{
		$this->clearMenuCache();
	}

	public function hookActionObjectManufacturerUpdateAfter($params)
	{
		$this->clearMenuCache();
	}

	public function hookActionObjectManufacturerDeleteAfter($params)
	{
		$this->clearMenuCache();
	}

	public function hookActionObjectManufacturerAddAfter($params)
	{
		$this->clearMenuCache();
	}

	public function hookActionObjectProductUpdateAfter($params)
	{
		$this->clearMenuCache();
	}

	public function hookActionObjectProductDeleteAfter($params)
	{
		$this->clearMenuCache();
	}

	public function hookActionObjectProductAddAfter($params)
	{
		$this->clearMenuCache();
	}

	public function hookCategoryUpdate($params)
	{
		$this->clearMenuCache();
	}

	private function clearMenuCache()
	{
		$this->_clearCache('snsmegamenu.tpl');
	}
	public function hookActionObjectLanguageAddAfter($params) {
		//var_dump('hookActionObjectLanguageAddAfter'); die;
	}
	public function hookActionObjectShopAddAfter($params) {
		//var_dump('hookActionObjectShopAddAfter'); die;
	}
//	public function hookActionShopDataDuplication($params)
//	{
//		var_dump('hookActionShopDataDuplication'); die;
//		$snsmegamenu = Db::getInstance()->executeS('
//			SELECT *
//			FROM '._DB_PREFIX_.'snsmegamenu
//			WHERE id_shop = '.(int)$params['old_id_shop']
//			);
//
//		foreach($snsmegamenu as $id => $link)
//		{
//			Db::getInstance()->execute('
//				INSERT IGNORE INTO '._DB_PREFIX_.'snsmegamenu (id_megamenu, id_shop, new_window)
//				VALUES (null, '.(int)$params['new_id_shop'].', '.(int)$link['new_window'].')');
//
//			$snsmegamenu[$id]['new_id_megamenu'] = Db::getInstance()->Insert_ID();
//		}
//
//		foreach($snsmegamenu as $id => $link)
//		{
//			$lang = Db::getInstance()->executeS('
//					SELECT id_lang, '.(int)$params['new_id_shop'].', label, link
//					FROM '._DB_PREFIX_.'snsmegamenu_lang
//					WHERE id_megamenu = '.(int)$link['id_megamenu'].' AND id_shop = '.(int)$params['old_id_shop']);
//
//			foreach($lang as $l)
//				Db::getInstance()->execute('
//					INSERT IGNORE INTO '._DB_PREFIX_.'snsmegamenu_lang (id_megamenu, id_lang, id_shop, label, link)
//					VALUES ('.(int)$link['new_id_megamenu'].', '.(int)$l['id_lang'].', '.(int)$params['new_id_shop'].', '.(int)$l['label'].', '.(int)$l['link'].' )');
//		}
//	}

	public function renderForm()
	{
		$shops = Shop::getContextListShopID();

		if (count($shops) == 1)
			$fields_form = array(
				'form' => array(
					'legend' => array(
						'title' => $this->l('Main Menu'),
						'icon' => 'icon-link'
					),
					'input' => array(
						array(
							'type' => 'link_choice',
							'label' => '',
							'name' => 'link',
							'lang' => true,
						),
//			           	array(
//							'type' => 'switch',
//							'label' => $this->l('Use sticky menu'),
//							'name' => 'SNSMM_STICKYMENU',
//							'values' => array(
//								array(
//									'id' => 'SNSMM_STICKYMENU_ON',
//									'value' => 1,
//									'label' => $this->l('Enabled')
//								),
//								array(
//									'id' => 'SNSMM_STICKYMENU_OFF',
//									'value' => 0,
//									'label' => $this->l('Disabled')
//								)
//							)
//						),
						array(
							'type' => 'select',
							'label' => 'Responsive menu',
							'name' => 'SNSMM_RESMENU',
							'options' => array(
								'query' => array(
									array('id' => 1, 'name' => 'Sidebar'),
									array('id' => 2, 'name' => 'Collapse')
								),
								'id' => 'id',
								'name' => 'name'
							)
						)
					),
					'submit' => array(
						'name' => 'submitSnsmegamenu',
						'title' => $this->l('Save')
					)
				),
			);
		else
			$fields_form = array(
				'form' => array(
					'legend' => array(
						'title' => $this->l('Main Menu'),
						'icon' => 'icon-link'
					),
					'info' => '<div class="alert alert-warning">' .$this->l('All active products combinations quantities will be changed').'</div>',
					'input' => array(
//			           	array(
//							'type' => 'switch',
//							'label' => $this->l('Use sticky menu'),
//							'name' => 'SNSMM_STICKYMENU',
//							'values' => array(
//								array(
//									'id' => 'SNSMM_STICKYMENU_ON',
//									'value' => 1,
//									'label' => $this->l('Enabled')
//								),
//								array(
//									'id' => 'SNSMM_STICKYMENU_OFF',
//									'value' => 0,
//									'label' => $this->l('Disabled')
//								)
//							)
//						),
						array(
							'type' => 'select',
							'label' => 'Responsive menu',
							'name' => 'SNSMM_RESMENU',
							'options' => array(
								'query' => array(
									array('id' => 1, 'name' => 'Sidebar'),
									array('id' => 2, 'name' => 'Collapse')
								),
								'id' => 'id',
								'name' => 'name'
							)
						)
					),
					'submit' => array(
						'name' => 'submitSnsmegamenu',
						'title' => $this->l('Save')
					)
				),
			);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->module = $this;
		$helper->identifier = $this->identifier;
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
			'choices' => $this->renderChoicesSelect(),
			'selected_links' => $this->makeMenuOption(),
		);
		return $helper->generateForm(array($fields_form));
	}

	public function renderAddForm() {
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => (Tools::getIsset('updatesnsmegamenu') && !Tools::getValue('updatesnsmegamenu')) ? $this->l('Update link') : $this->l('Add a new link'),
					'icon' => 'icon-link'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Label'),
						'name' => 'label',
						'lang' => true,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Link'),
						'name' => 'link',
						'lang' => true,
						'desc' => $this->l('[__HOME__] : homepage link.')
									 .'<br />'.$this->l('[__MANID_{ID}__] : link to a manufacturer. Eg: [__MANID_2__]')
									 .'<br />'.$this->l('[__SUPID_{ID}__] : link to a supplier. Eg: [__SUPID_3__]')
									 .'<br />'.$this->l('[__CMSCATID_{ID}__] : link to a CMS category. Eg: [__CMSCATID_2__]')
									 .'<br />'.$this->l('[__CATID_{ID}__] : link to a category. Eg: [__CATID_2__]')
									 .'<br />'.$this->l('[__PRDID_{ID}__] : link to a product. Eg: [__PRDID_2__]')
									 .'<br />'.$this->l('[__CMSID_{ID}__] : link to a CMS page. Eg: [__CMSID_2__]'),
			     
			
			
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Drop with block html'),
						'name' => 'is_drop',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						)
					),
	                array(
	                    'type' => 'textarea',
	                    'label' => $this->l('Custom html'),
	                    'name' => 'customhtml',
	                    'lang' => true,
	                    'autoload_rte' => true,
	                    'cols' => 40,
	                    'rows' => 10
	                ),
					array(
						'type' => 'switch',
						'label' => $this->l('New window'),
						'name' => 'new_window',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						),
					)
				),
				'submit' => array(
					'name' => 'submitSnsmegamenuLinks',
					'title' => $this->l('Add')
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->identifier = $this->identifier;
		$helper->fields_value = $this->getAddLinkFieldsValues();

		if (Tools::getIsset('updatesnsmegamenu') && !Tools::getValue('updatesnsmegamenu'))
			$fields_form['form']['submit'] = array(
				'name' => 'updatesnsmegamenu',
				'title' => $this->l('Update')
			);

		if (Tools::isSubmit('updatesnsmegamenu'))
		{
			$fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'updatelink');
			$fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_megamenu');
			$helper->fields_value['updatelink'] = '';
		}

		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->languages =$this->context->controller->getLanguages();
		$helper->default_form_language = (int)$this->context->language->id;

		return $helper->generateForm(array($fields_form));
	}

	public function renderChoicesSelect()
	{
		$spacer = str_repeat('&nbsp;', $this->spacer_size);
		$items = $this->getMenuItems();


		$html = '<select multiple="multiple" id="availableItems" style="width: 300px; height: 160px;">';
		$html .= '<optgroup label="'.$this->l('CMS').'">';
		$html .= $this->getCMSOptions(0, 1, $this->context->language->id, $items);
		$html .= '</optgroup>';

		// BEGIN SUPPLIER
		$html .= '<optgroup label="'.$this->l('Supplier').'">';
		// Option to show all Suppliers
		$html .= '<option value="ALLSUP0">'.$this->l('All suppliers').'</option>';
		$suppliers = Supplier::getSuppliers(false, $this->context->language->id);
		foreach ($suppliers as $supplier)
			if (!in_array('SUP'.$supplier['id_supplier'], $items))
				$html .= '<option value="SUP'.$supplier['id_supplier'].'">'.$spacer.$supplier['name'].'</option>';
		$html .= '</optgroup>';

		// BEGIN Manufacturer
		$html .= '<optgroup label="'.$this->l('Manufacturer').'">';
		// Option to show all Manufacturers
		$html .= '<option value="ALLMAN0">'.$this->l('All manufacturers').'</option>';
		$manufacturers = Manufacturer::getManufacturers(false, $this->context->language->id);
		foreach ($manufacturers as $manufacturer)
			if (!in_array('MAN'.$manufacturer['id_manufacturer'], $items))
				$html .= '<option value="MAN'.$manufacturer['id_manufacturer'].'">'.$spacer.$manufacturer['name'].'</option>';
		$html .= '</optgroup>';

		// BEGIN Categories
		$shop = new Shop((int)Shop::getContextShopID());
		$html .= '<optgroup label="'.$this->l('Categories').'">';

		$shops_to_get = Shop::getContextListShopID();

		foreach ($shops_to_get as $shop_id)
			$html .= $this->generateCategoriesOption($this->customGetNestedCategories($shop_id, null, (int)$this->context->language->id, true), $items);
		$html .= '</optgroup>';

		// BEGIN Shops
		if (Shop::isFeatureActive())
		{
			$html .= '<optgroup label="'.$this->l('Shops').'">';
			$shops = Shop::getShopsCollection();
			foreach ($shops as $shop)
			{
				if (!$shop->setUrl() && !$shop->getBaseURL())
					continue;

				if (!in_array('SHOP'.(int)$shop->id, $items))
					$html .= '<option value="SHOP'.(int)$shop->id.'">'.$spacer.$shop->name.'</option>';
			}
			$html .= '</optgroup>';
		}

		// BEGIN Products
		$html .= '<optgroup label="'.$this->l('Products').'">';
		$html .= '<option value="PRODUCT" style="font-style:italic">'.$spacer.$this->l('Choose product ID').'</option>';
		$html .= '</optgroup>';

		// BEGIN Menu Top Links
		$html .= '<optgroup label="'.$this->l('Menu Top Links').'">';
		$links = SNSMenuClass::gets($this->context->language->id, null, (int)Shop::getContextShopID());
		foreach ($links as $link)
		{
			if ($link['label'] == '')
			{
				$default_language = Configuration::get('PS_LANG_DEFAULT');
				$link = SNSMenuClass::get($link['id_megamenu'], $default_language, (int)Shop::getContextShopID());
				if (!in_array('LNK'.(int)$link[0]['id_megamenu'], $items))
					$html .= '<option value="LNK'.(int)$link[0]['id_megamenu'].'">'.$spacer.Tools::safeOutput($link[0]['label']).'</option>';
			}
			elseif (!in_array('LNK'.(int)$link['id_megamenu'], $items))
				$html .= '<option value="LNK'.(int)$link['id_megamenu'].'">'.$spacer.Tools::safeOutput($link['label']).'</option>';
		}
		$html .= '</optgroup>';
		$html .= '</select>';
		return $html;
	}


	public function customGetNestedCategories($shop_id, $root_category = null, $id_lang = false, $active = true, $groups = null, $use_shop_restriction = true, $sql_filter = '', $sql_sort = '', $sql_limit = '')
	{
		if (isset($root_category) && !Validate::isInt($root_category))
			die(Tools::displayError());

		if (!Validate::isBool($active))
			die(Tools::displayError());

		if (isset($groups) && Group::isFeatureActive() && !is_array($groups))
			$groups = (array)$groups;

		$cache_id = 'Category::getNestedCategories_'.md5((int)$shop_id.(int)$root_category.(int)$id_lang.(int)$active.(int)$active
			.(isset($groups) && Group::isFeatureActive() ? implode('', $groups) : ''));

		if (!Cache::isStored($cache_id))
		{
			$result = Db::getInstance()->executeS('
							SELECT c.*, cl.*
				FROM `'._DB_PREFIX_.'category` c
				INNER JOIN `'._DB_PREFIX_.'category_shop` category_shop ON (category_shop.`id_category` = c.`id_category` AND category_shop.`id_shop` = "'.(int)$shop_id.'")
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_shop` = "'.(int)$shop_id.'")
				WHERE 1 '.$sql_filter.' '.($id_lang ? 'AND cl.`id_lang` = '.(int)$id_lang : '').'
				'.($active ? ' AND (c.`active` = 1 OR c.`is_root_category` = 1)' : '').'
				'.(isset($groups) && Group::isFeatureActive() ? ' AND cg.`id_group` IN ('.implode(',', $groups).')' : '').'
				'.(!$id_lang || (isset($groups) && Group::isFeatureActive()) ? ' GROUP BY c.`id_category`' : '').'
				'.($sql_sort != '' ? $sql_sort : ' ORDER BY c.`level_depth` ASC').'
				'.($sql_sort == '' && $use_shop_restriction ? ', category_shop.`position` ASC' : '').'
				'.($sql_limit != '' ? $sql_limit : '')
			);

			$categories = array();
			$buff = array();

			foreach ($result as $row)
			{
				$current = &$buff[$row['id_category']];
				$current = $row;

				if ($row['id_parent'] == 0)
					$categories[$row['id_category']] = &$current;
				else
					$buff[$row['id_parent']]['children'][$row['id_category']] = &$current;
			}

			Cache::store($cache_id, $categories);
		}

		return Cache::retrieve($cache_id);
	}

	public function getConfigFieldsValues() {
		$shops = Shop::getContextListShopID();
		$resmenu = true;
		$stickymenu = true;

		foreach ($shops as $shop_id) {
			$shop_group_id = Shop::getGroupFromShop($shop_id);
			$resmenu = (int)Configuration::get('SNSMM_RESMENU', null, $shop_group_id, $shop_id);
			//$stickymenu &= (bool)Configuration::get('SNSMM_STICKYMENU', null, $shop_group_id, $shop_id);
		}
		return array(
			'SNSMM_RESMENU' => (int)$resmenu,
			//'SNSMM_STICKYMENU' => (int)$stickymenu,
		);
	}

	public function getAddLinkFieldsValues() {
		$links_label_edit = '';
		$labels_edit = '';
		$customhtmls_edit = '';
		$is_drop_edit = '';
		$new_window_edit = '';

		if (Tools::isSubmit('updatesnsmegamenu'))
		{
			$link = SNSMenuClass::getLinkLang(Tools::getValue('id_megamenu'), (int)Shop::getContextShopID());

			foreach ($link['link'] as $key => $label)
				$link['link'][$key] = Tools::htmlentitiesDecodeUTF8($label);

			$links_label_edit = $link['link'];
			$labels_edit = $link['label'];
			$customhtmls_edit = $link['customhtml'];
			$is_drop_edit = $link['is_drop'];
			$new_window_edit = $link['new_window'];
		}

		$fields_values = array(
			'is_drop' => Tools::getValue('is_drop', $is_drop_edit),
			'new_window' => Tools::getValue('new_window', $new_window_edit),
			'id_megamenu' => Tools::getValue('id_megamenu'),
		);

		if (Tools::getValue('submitAddmodule'))
		{
			foreach (Language::getLanguages(false) as $lang)
			{
				$fields_values['label'][$lang['id_lang']] = '';
				$fields_values['customhtml'][$lang['id_lang']] = '';
				$fields_values['link'][$lang['id_lang']] = '';
			}
		}
		else
			foreach (Language::getLanguages(false) as $lang)
			{
				$fields_values['label'][$lang['id_lang']] = Tools::getValue('label_'.(int)$lang['id_lang'], isset($labels_edit[$lang['id_lang']]) ? $labels_edit[$lang['id_lang']] : '');
				$fields_values['customhtml'][$lang['id_lang']] = Tools::getValue('label_'.(int)$lang['id_lang'], isset($customhtmls_edit[$lang['id_lang']]) ? $this->replaceLinkContent($customhtmls_edit[$lang['id_lang']], true) : '');
				$fields_values['link'][$lang['id_lang']] = Tools::getValue('link_'.(int)$lang['id_lang'], isset($links_label_edit[$lang['id_lang']]) ? $links_label_edit[$lang['id_lang']] : '');
			}

		return $fields_values;
	}

	public function renderList()
	{
		$shops = Shop::getContextListShopID();
		$links = array();

		$menuIds = array();
		foreach ($shops as $shop_id) {
			$link = SNSMenuClass::gets((int)$this->context->language->id, null, (int)$shop_id);
			foreach($link as $k => $v) {
				$link[$k]['link'] = $this->replaceLink($v['link']);
				if(!in_array($v['id_megamenu'], $menuIds)) {
					$menuIds[] = $v['id_megamenu'];
				} else {
					unset($link[$k]);
				}
			}
			$links = array_merge($links, $link);
		}
		$fields_list = array(
			'id_megamenu' => array(
				'title' => $this->l('Link ID'),
				'type' => 'text',
			),
			'name' => array(
				'title' => $this->l('Shop name'),
				'type' => 'text',
			),
			'label' => array(
				'title' => $this->l('Label'),
				'type' => 'text',
			),
			'link' => array(
				'title' => $this->l('Link'),
				'type' => 'link',
			),
			'is_drop' => array(
				'title' => $this->l('Is Drop'),
				'type' => 'bool',
				'align' => 'center',
				'active' => 'status',
			),
			'new_window' => array(
				'title' => $this->l('New window'),
				'type' => 'bool',
				'align' => 'center',
				'active' => 'status',
			)
		);

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = true;
		$helper->identifier = 'id_megamenu';
		$helper->filter = 'id_megamenu';
		$helper->table = 'snsmegamenu';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = false;
		$helper->module = $this;
		$helper->title = $this->l('Link list');
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

		return $helper->generateList($links, $fields_list);
	}
	public function getBaseUrl(){
		if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS']))
			return 'https://'.Tools::getShopDomain();
		else
			return 'http://'.Tools::getShopDomain();
	}
	public function replaceLinkContent($string, $out = false) {
		if($out) {
			return str_replace('__SNSPS_BASE_URI__', $this->getBaseUrl().__PS_BASE_URI__, $string);
		} else { 
			$return = str_replace(_PS_BASE_URL_SSL_.__PS_BASE_URI__, '__SNSPS_BASE_URI__', $string); 
			return str_replace(_PS_BASE_URL_.__PS_BASE_URI__, '__SNSPS_BASE_URI__', $return); 
		}
	}
    //  CREATE THE TAB MENU
    private function _createTab() {
        $response = true;

        // First check for parent tab
        $parentTabID = Tab::getIdFromClassName('AdminSNS');

        if ($parentTabID) {
            $parentTab = new Tab($parentTabID);
        } else {
            $parentTab = new Tab();
            $parentTab->active = 1;
            $parentTab->name = array();
            $parentTab->class_name = "AdminSNS";
            foreach (Language::getLanguages() as $lang){
                $parentTab->name[$lang['id_lang']] = "SNS Theme";
            }
            $parentTab->id_parent = 0;
            $parentTab->module = $this->name;
            $response &= $parentTab->add();
        }

        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = "AdminSNSMegaMenu";
        $tab->name = array();
        foreach (Language::getLanguages() as $lang){
            $tab->name[$lang['id_lang']] = "SNS Mega Menu";
        }
        $tab->id_parent = $parentTab->id;
        $tab->module = $this->name;
        $response &= $tab->add();

        return $response;
    }
    private function _deleteTab() {
        $id_tab = Tab::getIdFromClassName('AdminSNSMegaMenu');
        $parentTabID = Tab::getIdFromClassName('AdminSNS');

        $tab = new Tab($id_tab);
        $tab->delete();

        $tabCount = Tab::getNbTabs($parentTabID);
        if ($tabCount == 0) {
            $parentTab = new Tab($parentTabID);
            $parentTab->delete();
        }
        return true;
    }
}
