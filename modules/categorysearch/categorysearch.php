<?php

if (!defined('_PS_VERSION_'))
	exit;

class CategorySearch extends Module
{
	public function __construct()
	{
		$this->name = 'categorysearch';
		$this->tab = 'search_filter';
		$this->version = '1.0';
		$this->author = 'OvicSoft';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Supershop - Quick search with category');
		$this->description = $this->l('Adds a quick search field to your website.');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		if (!parent::install() || !$this->registerHook('top') || !$this->registerHook('header') || !$this->registerHook('displayMobileTopSiteMap'))
			return false;
		return true;
	}

	public function hookdisplayMobileTopSiteMap($params)
	{
		$this->smarty->assign(array('hook_mobile' => true, 'instantsearch' => false));
		$params['hook_mobile'] = true;
		return $this->hookTop($params);
	}

	public function hookHeader($params)
	{
		if (Configuration::get('PS_SEARCH_AJAX'))
			$this->context->controller->addJqueryPlugin('autocomplete');
		$this->context->controller->addCSS(_THEME_CSS_DIR_.'product_list.css');
		//$this->context->controller->addCSS(($this->_path).'categorysearch.css', 'all');
		if (Configuration::get('PS_SEARCH_AJAX'))
		{
			Media::addJsDef(array('search_url' => $this->context->link->getPageLink('search', Tools::usingSecureMode())));
			$this->context->controller->addJS(($this->_path).'categorysearch.js');
		}
	}

	public function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}

	public function hookRightColumn($params)
	{
		if (Tools::getValue('search_query') || !$this->isCached('categorysearch.tpl', $this->getCacheId()))
		{
			$this->calculHookCommon($params);
			$this->smarty->assign(array(
				'categorysearch_type' => 'block',
				'search_query' => (string)Tools::getValue('search_query')
				)
			);
		}
		Media::addJsDef(array('categorysearch_type' => 'block'));
		return $this->display(__FILE__, 'categorysearch.tpl', Tools::getValue('search_query') ? null : $this->getCacheId());
	}

	public function hookTop($params)
	{
		$key = $this->getCacheId('categorysearch-top'.((!isset($params['hook_mobile']) || !$params['hook_mobile']) ? '' : '-hook_mobile'));
		if (Tools::getValue('search_query') || !$this->isCached('categorysearch-top.tpl', $key))
		{
			$this->calculHookCommon($params);
			$this->smarty->assign(array(
                'search_category' => $this->getCategoryOption(),
				'categorysearch_type' => 'top',
				'search_query' => (string)Tools::getValue('search_query')
				)
			);
		}
		Media::addJsDef(array('categorysearch_type' => 'top'));
		return $this->display(__FILE__, 'categorysearch-top.tpl', Tools::getValue('search_query') ? null : $key);
	}

	public function hookDisplayNav($params)
	{
		return $this->hookTop($params);
	}

	private function calculHookCommon($params)
	{
		$this->smarty->assign(array(
			'ENT_QUOTES' =>		ENT_QUOTES,
			'search_ssl' =>		Tools::usingSecureMode(),
			'ajaxsearch' =>		Configuration::get('PS_SEARCH_AJAX'),
			'instantsearch' =>	Configuration::get('PS_INSTANT_SEARCH'),
			'self' =>			dirname(__FILE__),
		));

		return true;
	}
    private function getCategoryOption($id_category = 1, $id_lang = false, $id_shop = false,$recursive = true)
    {
        $html = '';
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);
        if (is_null($category->id))
            return;
        if ($recursive)
        {
            $children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)
                $id_shop);
            $spacer = '';
            if ($category->level_depth>0)
                $spacer = str_repeat('-', 2 * ((int)$category->level_depth-1));
        }
        $shop = (object)Shop::getShop((int)$category->getShopID());
        if ($category->id != Configuration::get('PS_ROOT_CATEGORY'))
            $html .= '<option value="' . (int)$category->id . '">' .$spacer. $category->name .'</option>';

        if (isset($children) && count($children))
            foreach ($children as $child)
            {
                $html .= $this->getCategoryOption((int)$child['id_category'], (int)$id_lang, (int)
                    $child['id_shop'], $recursive);
            }
        return $html;
    }
}

