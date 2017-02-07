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

if (!defined('_PS_VERSION_'))
	exit;

class SNSNavigation extends Module
{
	public function __construct()
	{
		$this->name = 'snsnavigation';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'SNS Theme';

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Sns Navigation');
		$this->description = $this->l('Adds a block navigation.');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		if (!parent::install() ||
			!$this->registerHook('displaySNSNavigation') ||
			// Temporary hooks. Do NOT hook any module on it. Some CRUD hook will replace them as soon as possible.
			!$this->registerHook('actionCategoryAdd') ||

			!$this->registerHook('leftColumn') ||
			
			!$this->registerHook('actionCategoryUpdate') ||
			!$this->registerHook('actionCategoryDelete') ||
			!$this->registerHook('categoryAddition') ||
			!$this->registerHook('categoryUpdate') ||
			!$this->registerHook('categoryDeletion') ||
			!$this->registerHook('actionAdminMetaControllerUpdate_optionsBefore') ||
			!$this->registerHook('actionAdminLanguagesControllerStatusBefore') ||
			!$this->registerHook('displayBackOfficeCategory') ||
			!Configuration::updateValue('SNSNAV_MAX_DEPTH', 4) ||
			!Configuration::updateValue('SNSNAV_ROOT_CATEGORY', 0) ||
			!Configuration::updateValue('SNS_CATICONS', ''))
				return false;
		
		$this->_createTab();
		return true;
	}

	public function uninstall()
	{
		if (!parent::uninstall() ||
			!Configuration::deleteByName('SNSNAV_MAX_DEPTH') ||
			!Configuration::deleteByName('SNSNAV_ROOT_CATEGORY') ||
			!Configuration::deleteByName('SNS_CATICONS'))
			return false;
		
		$this->_deleteTab();
		return true;
	}

	public function getContent()
	{
		$output = '';
		if (Tools::isSubmit('submitSNSNavigation'))
		{
			$maxDepth = (int)(Tools::getValue('SNSNAV_MAX_DEPTH'));
			if ($maxDepth < 0)
				$output .= $this->displayError($this->l('Maximum depth: Invalid number.'));
			else
			{
				Configuration::updateValue('SNSNAV_MAX_DEPTH', (int)$maxDepth);
				Configuration::updateValue('SNSNAV_SORT_WAY', Tools::getValue('SNSNAV_SORT_WAY'));
				Configuration::updateValue('SNSNAV_SORT', Tools::getValue('SNSNAV_SORT'));
				Configuration::updateValue('SNSNAV_ROOT_CATEGORY', Tools::getValue('SNSNAV_ROOT_CATEGORY'));

				$this->_clearNavigationCache();

				Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&conf=6');
			}
		}
		return $output.$this->renderForm();
	}

	public function getTree($resultParents, $resultIds, $maxDepth, $id_category = null, $currentDepth = 0)
	{
		if (is_null($id_category))
			$id_category = $this->context->shop->getCategory();

		$children = array();
		if (isset($resultParents[$id_category]) && count($resultParents[$id_category]) && ($maxDepth == 0 || $currentDepth < $maxDepth))
			foreach ($resultParents[$id_category] as $subcat)
				$children[] = $this->getTree($resultParents, $resultIds, $maxDepth, $subcat['id_category'], $currentDepth + 1);

		if (!isset($resultIds[$id_category]))
			return false;


    	$data_current_icons = Configuration::get('SNS_CATICONS');
    	$current_icons = $this->snsUnSerialize($data_current_icons);
    	$icon = (isset($current_icons[$id_category]) && $current_icons[$id_category]) ? $current_icons[$id_category] : '';
		$return = array(
			'id' => $id_category,
			'link' => $this->context->link->getCategoryLink($id_category, $resultIds[$id_category]['link_rewrite']),
			'name' =>  $resultIds[$id_category]['name'],
			'desc'=>  $resultIds[$id_category]['description'],
			'children' => $children,
			'icon' => $icon
		);
		
		return $return;
	}


	// hook front end
	public function hookDisplaySNSNavigation($params)
	{
		//return 'xxxxxxxxxxxx';
		$this->setLastVisitedCategory();
		$phpself = $this->context->controller->php_self;
		$current_allowed_controllers = array('category');

		if ($phpself != null && in_array($phpself, $current_allowed_controllers) && Configuration::get('SNSNAV_ROOT_CATEGORY') && isset($this->context->cookie->last_visited_category) && $this->context->cookie->last_visited_category)
		{
			$category = new Category($this->context->cookie->last_visited_category, $this->context->language->id);
			if (Configuration::get('SNSNAV_ROOT_CATEGORY') == 2 && !$category->is_root_category && $category->id_parent)
				$category = new Category($category->id_parent, $this->context->language->id);
			elseif (Configuration::get('SNSNAV_ROOT_CATEGORY') == 3 && !$category->is_root_category && !$category->getSubCategories($category->id, true))
				$category = new Category($category->id_parent, $this->context->language->id);
		}
		else
			$category = new Category((int)Configuration::get('PS_HOME_CATEGORY'), $this->context->language->id);

		$cacheId = $this->getCacheId($category ? $category->id : null);

		if (!$this->isCached('snsnavigation.tpl', $cacheId))
		{
			$range = '';
			$maxdepth = Configuration::get('SNSNAV_MAX_DEPTH');
			if (Validate::isLoadedObject($category))
			{
				if ($maxdepth > 0)
					$maxdepth += $category->level_depth;
				$range = 'AND nleft >= '.(int)$category->nleft.' AND nright <= '.(int)$category->nright;
			}

			$resultIds = array();
			$resultParents = array();
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT c.id_parent, c.id_category, cl.name, cl.description, cl.link_rewrite
			FROM `'._DB_PREFIX_.'category` c
			INNER JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_lang` = '.(int)$this->context->language->id.Shop::addSqlRestrictionOnLang('cl').')
			INNER JOIN `'._DB_PREFIX_.'category_shop` cs ON (cs.`id_category` = c.`id_category` AND cs.`id_shop` = '.(int)$this->context->shop->id.')
			WHERE (c.`active` = 1 OR c.`id_category` = '.(int)Configuration::get('PS_HOME_CATEGORY').')
			AND c.`id_category` != '.(int)Configuration::get('PS_ROOT_CATEGORY').'
			'.((int)$maxdepth != 0 ? ' AND `level_depth` <= '.(int)$maxdepth : '').'
			'.$range.'
			AND c.id_category IN (
				SELECT id_category
				FROM `'._DB_PREFIX_.'category_group`
				WHERE `id_group` IN ('.pSQL(implode(', ', Customer::getGroupsStatic((int)$this->context->customer->id))).')
			)
			ORDER BY `level_depth` ASC, '.(Configuration::get('SNSNAV_SORT') ? 'cl.`name`' : 'cs.`position`').' '.(Configuration::get('SNSNAV_SORT_WAY') ? 'DESC' : 'ASC'));
			foreach ($result as &$row)
			{
				$resultParents[$row['id_parent']][] = &$row;
				$resultIds[$row['id_category']] = &$row;
			}

			$blockCategTree = $this->getTree($resultParents, $resultIds, $maxdepth, ($category ? $category->id : null));
			$this->smarty->assign('blockCategTree', $blockCategTree);

			if ((Tools::getValue('id_product') || Tools::getValue('id_category')) && isset($this->context->cookie->last_visited_category) && $this->context->cookie->last_visited_category)
			{
				$category = new Category($this->context->cookie->last_visited_category, $this->context->language->id);
				if (Validate::isLoadedObject($category))
					$this->smarty->assign(array('currentCategory' => $category, 'currentCategoryId' => $category->id));
			}
			if (file_exists(_PS_THEME_DIR_.'modules/snsnavigation/snsnavigation.tpl'))
				$this->smarty->assign('branche_tpl_path', _PS_THEME_DIR_.'modules/snsnavigation/category-tree-branch.tpl');
			else
				$this->smarty->assign('branche_tpl_path', _PS_MODULE_DIR_.'snsnavigation/category-tree-branch.tpl');
		}
		return $this->display(__FILE__, 'snsnavigation.tpl', $cacheId);
	}



	public function setLastVisitedCategory()
	{
		$cache_id = 'snsnavigation::setLastVisitedCategory';
		if (!Cache::isStored($cache_id))
		{
			if (method_exists($this->context->controller, 'getCategory') && ($category = $this->context->controller->getCategory()))
				$this->context->cookie->last_visited_category = $category->id;
			elseif (method_exists($this->context->controller, 'getProduct') && ($product = $this->context->controller->getProduct()))
				if (!isset($this->context->cookie->last_visited_category)
					|| !Product::idIsOnCategoryId($product->id, array(array('id_category' => $this->context->cookie->last_visited_category)))
					|| !Category::inShopStatic($this->context->cookie->last_visited_category, $this->context->shop))
						$this->context->cookie->last_visited_category = (int)$product->id_category_default;
			Cache::store($cache_id, $this->context->cookie->last_visited_category);
		}
		return Cache::retrieve($cache_id);
	}



	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'radio',
						'label' => $this->l('Category root'),
						'name' => 'SNSNAV_ROOT_CATEGORY',
						'hint' => $this->l('Select which category is displayed in the block. The current category is the one the visitor is currently browsing.'),
						'values' => array(
							array(
								'id' => 'home',
								'value' => 0,
								'label' => $this->l('Home category')
							),
							array(
								'id' => 'current',
								'value' => 1,
								'label' => $this->l('Current category')
							),
							array(
								'id' => 'parent',
								'value' => 2,
								'label' => $this->l('Parent category')
							),
							array(
								'id' => 'current_parent',
								'value' => 3,
								'label' => $this->l('Current category, unless it has no subcategories, then parent one')
							),
						)
					),
					array(
						'type' => 'text',
						'label' => $this->l('Maximum depth'),
						'name' => 'SNSNAV_MAX_DEPTH',
						'desc' => $this->l('Set the maximum depth of category sublevels displayed in this block (0 = infinite).'),
					),
					array(
						'type' => 'radio',
						'label' => $this->l('Sort'),
						'name' => 'SNSNAV_SORT',
						'values' => array(
							array(
								'id' => 'name',
								'value' => 1,
								'label' => $this->l('By name')
							),
							array(
								'id' => 'position',
								'value' => 0,
								'label' => $this->l('By position')
							),
						)
					),
					array(
						'type' => 'radio',
						'label' => $this->l('Sort order'),
						'name' => 'SNSNAV_SORT_WAY',
						'values' => array(
							array(
								'id' => 'name',
								'value' => 1,
								'label' => $this->l('Descending')
							),
							array(
								'id' => 'position',
								'value' => 0,
								'label' => $this->l('Ascending')
							),
						)
					)
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitSNSNavigation';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}

	public function getConfigFieldsValues()
	{
		return array(
			'SNSNAV_MAX_DEPTH' => Tools::getValue('SNSNAV_MAX_DEPTH', Configuration::get('SNSNAV_MAX_DEPTH')),
			'SNSNAV_SORT_WAY' => Tools::getValue('SNSNAV_SORT_WAY', Configuration::get('SNSNAV_SORT_WAY')),
			'SNSNAV_SORT' => Tools::getValue('SNSNAV_SORT', Configuration::get('SNSNAV_SORT')),
			'SNSNAV_ROOT_CATEGORY' => Tools::getValue('SNSNAV_ROOT_CATEGORY', Configuration::get('SNSNAV_ROOT_CATEGORY'))
		);
	}
	// hook backoffice
	public function hookDisplayBackOfficeCategory($params)
	{
        $cat_id = Tools::getValue('id_category');
    	$data_current_icons = Configuration::get('SNS_CATICONS');
    	$current_icons = $this->snsUnSerialize($data_current_icons);
    	$icon = (isset($current_icons[$cat_id]) && $current_icons[$cat_id]) ? $current_icons[$cat_id] : '';

		$this->context->controller->addJS(__PS_BASE_URI__ . 'modules/'.$this->name.'/iconpicker/js/iconset-fontawesome.min.js');
		$this->context->controller->addJS(__PS_BASE_URI__ . 'modules/'.$this->name.'/iconpicker/js/bootstrap-iconpicker.min.js');
		$this->context->controller->addCSS(__PS_BASE_URI__ . 'modules/'.$this->name.'/iconpicker/css/bootstrap-iconpicker.min.css', 'all');
		$this->context->controller->addCSS(__PS_BASE_URI__ . 'modules/'.$this->name.'/iconpicker/css/font-awesome.min.css', 'all');

    	$html = '<div class="form-group">
					<label class="control-label col-lg-3">'.$this->l('Icon class').'</label>
					<div class="col-lg-9 ">';
		$html .= '<div class="input-group fixed-width-xl">
			<div class="input-group-btn">
	            <button class="btn btn-default" id="sns_cat_icon_iconpickerbtn"></button>
			</div>
			<input type="text"
				name="sns_cat_icon"
				id="sns_cat_icon"
				value="'.$icon.'"
				/>
		</div>';

		$html .= "<script>
			$('#sns_cat_icon_iconpickerbtn').iconpicker({ 
				arrowClass: 'btn-info',
			    arrowPrevIconClass: 'fa fa-arrow-left',
			    arrowNextIconClass: 'fa fa-arrow-right',
				iconset: 'fontawesome',
			    placement: 'bottom',
			    rows: 4,
			    cols: 8,
			    icon: '".$icon."',
			    search: true,
			    searchText: 'Search',
			    selectedClass: 'btn-success',
			    unselectedClass: ''
			});
			$('#sns_cat_icon_iconpickerbtn').on('change', function(e) { 
			    $('#sns_cat_icon').val(e.icon);
			});
		</script>";
		$html .= '	</div>
				</div>';
        return $html;
	}
	// clear cache
	protected function getCacheId($name = null)
	{
		$cache_id = parent::getCacheId();

		if ($name !== null)
			$cache_id .= '|'.$name;

		if ((Tools::getValue('id_product') || Tools::getValue('id_category')) && isset($this->context->cookie->last_visited_category) && $this->context->cookie->last_visited_category)
			$cache_id .= '|'.(int)$this->context->cookie->last_visited_category;

		return $cache_id.'|'.implode('-', Customer::getGroupsStatic($this->context->customer->id));
	}
	private function _clearNavigationCache()
	{
		$this->_clearCache('snsnavigation.tpl');
	}

	public function hookCategoryAddition($params)
	{
		$this->_clearNavigationCache();
	}

	public function hookCategoryUpdate($params)
	{
		$this->_clearNavigationCache();
	}

	public function hookCategoryDeletion($params)
	{
		$this->_clearNavigationCache();
	}

	public function hookActionAdminMetaControllerUpdate_optionsBefore($params)
	{
		$this->_clearNavigationCache();
	}
	// category
    public function hookActionCategoryAdd($params) {
        $cat_id = Tools::getValue('id_category');
		$sql = 'SHOW TABLE STATUS LIKE "'._DB_PREFIX_.'category"';
		$result = Db::getInstance()->executeS($sql);
		$next_id = $result[0][Auto_increment];
		
		$cat_id = $next_id - 1;
        $cat_icon = Tools::getValue('sns_cat_icon');
    	
    	$icons = array();
    	$data_current_icons = Configuration::get('SNS_CATICONS');
    	$icons = $this->snsUnSerialize($data_current_icons);
    	$icons[$cat_id] = $cat_icon;
    	
    	$dataIcons = $this->snsSerialize($icons);
    	
		Configuration::updateValue('SNS_CATICONS', $dataIcons);
    }
    public function hookActionCategoryUpdate($params) {
        $cat_id = Tools::getValue('id_category');
        $cat_icon = Tools::getValue('sns_cat_icon');
    	
    	$icons = array();
    	$data_current_icons = Configuration::get('SNS_CATICONS');
    	$icons = $this->snsUnSerialize($data_current_icons);
    	$icons[$cat_id] = $cat_icon;
    	
    	$dataIcons = $this->snsSerialize($icons);
    	
		Configuration::updateValue('SNS_CATICONS', $dataIcons);
    }
    // untility
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
        $tab->class_name = "AdminSNSNavigation";
        $tab->name = array();
        foreach (Language::getLanguages() as $lang){
            $tab->name[$lang['id_lang']] = "SNS Navigation";
        }
        $tab->id_parent = $parentTab->id;
        $tab->module = $this->name;
        $response &= $tab->add();

        return $response;
    }
    private function _deleteTab() {
        $id_tab = Tab::getIdFromClassName('AdminSNSNavigation');
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
	public function snsSerialize ($serializ) {
		return serialize($serializ);
	}
	public function snsUnSerialize ($serialized) {
		return Tools::unSerialize($serialized);
	}
}
