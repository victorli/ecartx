<?php

if (!defined('_PS_VERSION_'))
	exit;

class SnsQuickSearch extends Module
{
	public $spacer_size = '2';
	public function __construct()
	{
		$this->name = 'snsquicksearch';
		$this->tab = 'front_office_features';
		$this->version = 1.0;
		$this->author = 'SNS Theme';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('SNS Quick search');
		$this->description = $this->l('Quick search block.');
                
	}

	public function install()
	{
        if (Shop::isFeatureActive()){
            Shop::setContext(Shop::CONTEXT_ALL);
        }

		if (!parent::install() || !$this->registerHook('header') || !$this->registerHook('displayHeaderSearchBlock'))
			return false;
		return true;
	}

	public function hookHeader($params)
	{
		if (Configuration::get('PS_SEARCH_AJAX')){
            $this->context->controller->addJqueryPlugin('autocompleteCustom', $this->_path.'js/');
            $this->context->controller->addJqueryPlugin('snsquicksearch', $this->_path.'js/');
        }
	}
	private function generateCategoriesOption($categories)
	{
		//	var_dump($categories); die;
		$html = '';

		foreach ($categories as $key => $category)
		{
			$html .= '<option value="'.(int)$category['id_category'].'">'
				.str_repeat('-', $this->spacer_size * ((int)$category['level_depth'] - 1)).$category['name'].'</option>';

			if (isset($category['children']) && !empty($category['children']))
				$html .= $this->generateCategoriesOption($category['children']);

		}
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
	public function hookDisplayHeaderSearchBlock($params)
	{
			/*
			$id_shop = (int)Context::getContext()->shop->id;
			$id_lang = (int)$this->context->language->id;
			$categories = Category::getNestedCategories();
			//$categories = $this->customGetNestedCategories($id_shop, null, $id_lang, true);
			$html = '<select name="selectedcategory">';
			$html .= '<option>All Category</option>';
			$html .= $this->generateCategoriesOption($categories);
			$html .= '</select>';
			$this->smarty->assign(array(
				'cats_html' => $html
			));
			*/
            $this->calculHookCommon($params);
            $this->smarty->assign('blocksearch_type', 'top');
            return $this->display(__FILE__, 'snsquicksearch.tpl');
	}


	/**
	 * _hookAll has to be called in each hookXXX methods. This is made to avoid code duplication.
	 *
	 * @param mixed $params
	 * @return void
	 */
	private function calculHookCommon($params)
	{
		$this->smarty->assign(array(
			'ENT_QUOTES' =>		ENT_QUOTES,
			'search_ssl' =>		Tools::usingSecureMode(),
			'ajaxsearch' =>		Configuration::get('PS_SEARCH_AJAX'),
			'instantsearch' =>	Configuration::get('PS_INSTANT_SEARCH'),
		));

		return true;
	}
}

