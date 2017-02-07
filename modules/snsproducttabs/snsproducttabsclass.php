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

class SNSProductTabsClass extends Module {
	protected $_html = '';
	protected $user_groups;
	protected $pattern = '/^([A-Z_]*)[0-9]+/';
	protected $page_name = '';
	protected $spacer_size = '5';
	protected $_postErrors = array();
	protected $catids_filter = array();
	public function renderChoicesSelect() {
		$spacer = str_repeat('&nbsp;', $this->spacer_size);
		$items = $this->getMenuItems();
		
		$html = '<select multiple="multiple" id="availableItems" style="width: 300px; height: 160px;">';
		

		// BEGIN Categories
		$shop = new Shop((int)Shop::getContextShopID());
		$html .= '<optgroup label="'.$this->l('Categories').'">';	
		$html .= $this->generateCategoriesOption(
			Category::getNestedCategories(null, (int)$this->context->language->id, true), $items);
		$html .= '</optgroup>';
		$html .= '<optgroup label="'.$this->l('Products Fields').'">';	
		$html .= '<option value="featured_product">'.$this->getLabelField('featured_product').'</option>';
		$html .= '<option value="special_product">'.$this->getLabelField('special_product').'</option>';
		$html .= '<option value="new_product">'.$this->getLabelField('new_product').'</option>';
		$html .= '<option value="top_sellers">'.$this->getLabelField('top_sellers').'</option>';
		$html .= '</optgroup>';
		$html .= '</select>';
		return $html;
	}
	protected function getMenuItems() {
		$conf = Configuration::get('SNSPRT_CATEGORY_TABS_ID');
		if (strlen($conf))
			return explode(',', Configuration::get('SNSPRT_CATEGORY_TABS_ID'));
		else
			return array();
	}
	protected function makeMenuOption() {
		$menu_item = $this->getMenuItems();
		
		$id_lang = (int)$this->context->language->id;
		$id_shop = (int)Shop::getContextShopID();
		$html = '<select multiple="multiple" name="items[]" id="items" style="width: 300px; height: 160px;">';
		foreach ($menu_item as $item) {
			if (!$item)
				continue;
			
			preg_match($this->pattern, $item, $values);
			$id = (int)$item;
			$type = $id > 0 ? 'categories' : 'field';
			switch($type){
				default:
				case 'categories':
					$category = new Category((int)$id, (int)$id_lang);
					if (Validate::isLoadedObject($category))
						$html .= '<option selected="selected" value="'.$id.'">'.$category->name.'</option>'.PHP_EOL;
					break;
				case 'field':
						$html .= '<option selected="selected" value="'.$item.'">'.$this->getLabelField($item).'</option>'.PHP_EOL;
					break;
			}
		}
		return $html.'</select>';
	}
	protected function generateCategoriesOption($categories, $items_to_skip = null) {
		$html = '';
		foreach ($categories as $key => $category) {
			if (isset($items_to_skip) && !in_array('CAT'.(int)$category['id_category'], $items_to_skip)) {
				$shop = (object) Shop::getShop((int)$category['id_shop']);
				$html .= '<option value="'.(int)$category['id_category'].'">'
					.str_repeat('&nbsp;', $this->spacer_size * (int)$category['level_depth']).$category['name'].' ('.$shop->name.')</option>';
			}
			if (isset($category['children']) && !empty($category['children']))
				$html .= $this->generateCategoriesOption($category['children'], $items_to_skip);
		}
		
		return $html;
	}
	public  function _getProducts($id_category = false, $field_filter = null,  $countProduct = false )
	{
		$context = Context::getContext();
		$id_lang = (int) $context->language->id;

		$start = Tools::getValue('ajax_start') ? (int)Tools::getValue('ajax_start') : 0;
		$limit = Tools::getValue('nbload') ? (int)Tools::getValue('nbload') : (int)(Configuration::get('SNSPRT_NUMDISPLAY'));

		$only_active = true;
		
		$PS_NB_DAYS_NEW_PRODUCT = Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ? (int)Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;
		
		$front = true;
		if (!in_array($context->controller->controller_type, array('front', 'modulefront')))
			$front = false;

		$order_by = (Configuration::get('SNSPRT_ORDERBY')) ? Configuration::get('SNSPRT_ORDERBY') : 'date_upd';
		$order_way = (Configuration::get('SNSPRT_ORDERWAY')) ? Configuration::get('SNSPRT_ORDERWAY') : 'DESC';
		
		if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way))
			die (Tools::displayError());
		if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add' || $order_by == 'date_upd')
			$order_by_prefix = 'p';
		else if ($order_by == 'name')
			$order_by_prefix = 'pl';
		else if ($order_by == 'position')
			$order_by_prefix = 'c';

		if (strpos($order_by, '.') > 0)
		{
			$order_by = explode('.', $order_by);
			$order_by_prefix = $order_by[0];
			$order_by = $order_by[1];
		}
		
		if($order_by == 'sales' || $order_by == 'rand' )
			$order_by_prefix = '';
		$sql = 'SELECT DISTINCT  p.`id_product`, p.*, product_shop.*, pl.* , m.`name` AS manufacturer_name, s.`name` AS supplier_name,
				MAX(product_attribute_shop.id_product_attribute) id_product_attribute,  MAX(image_shop.`id_image`) id_image,  il.`legend`, ps.`quantity` AS sales, cl.`link_rewrite` AS category, IFNULL(stock.quantity,0) as quantity, IFNULL(pa.minimal_quantity, p.minimal_quantity) as minimal_quantity, stock.out_of_stock, product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.($PS_NB_DAYS_NEW_PRODUCT ? (int)$PS_NB_DAYS_NEW_PRODUCT : 20).' DAY')).'" as new, product_shop.`on_sale`
				FROM `'._DB_PREFIX_.'product` p
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` '.Shop::addSqlRestrictionOnLang('pl').') 
				LEFT JOIN `'._DB_PREFIX_.'product_sale` ps ON (p.`id_product` = ps.`id_product` '.Shop::addSqlAssociation('product_sale', 'ps').')
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.`id_product` = pa.`id_product`)
				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).' 
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
				ON cl.`id_category` = product_shop.`id_category_default`
				AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').'
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`) 
				LEFT JOIN `'._DB_PREFIX_.'supplier` s ON (s.`id_supplier` = p.`id_supplier`)'.
				($id_category ? ' LEFT JOIN `'._DB_PREFIX_.'category_product` c ON (c.`id_product` = p.`id_product`)' : '').'
				WHERE pl.`id_lang` = '.(int)$id_lang.
					' AND c.`id_category` IN ('.implode(',', array_map('intval', $id_category)).')'.
					($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').
					($only_active ? ' AND product_shop.`active` = 1' : '').'
				GROUP BY  p.`id_product`
				ORDER BY '.(isset($order_by_prefix) ? (($order_by_prefix != '') ? pSQL($order_by_prefix).'.': '')  : '').($order_by == 'rand'?' rand() ':'`'.pSQL($order_by).'`') .pSQL($order_way);
			if( !$countProduct ) $sql .=  ($limit > 0 ? ' LIMIT '.(int)$start.','.(int)$limit : '');
			$rq = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
			if($countProduct ) return count($rq);
			if ($order_by == 'price')
				Tools::orderbyPrice($rq, $order_way);
			$products_ids = array();
			foreach ($rq as $row)
				$products_ids[] = $row['id_product'];
			
			Product::cacheFrontFeatures($products_ids, $id_lang);
		return Product::getProductsProperties((int)$id_lang, $rq);	
	}
	protected static function _getProductIdByDate($beginning, $ending, Context $context = null, $with_combination = false)
	{
		if (!$context)
			$context = Context::getContext();

		$id_address = $context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
		$ids = Address::getCountryAndState($id_address);
		$id_country = (int)($ids['id_country'] ? $ids['id_country'] : Configuration::get('PS_COUNTRY_DEFAULT'));

		return SpecificPrice::getProductIdByDate(
			$context->shop->id,
			$context->currency->id,
			$id_country,
			$context->customer->id_default_group,
			$beginning,
			$ending,
			0,
			$with_combination
		);
	}
	public static function getPricesDrop($countProduct = false)
	{
		$context = Context::getContext();
		$id_lang = (int) $context->language->id;
		$order_by = null;
		$order_way = null;
		$beginning = false;
		$ending = false;
	
		$start = Tools::getValue('ajax_start') ? (int)Tools::getValue('ajax_start') : 0;
		$limit = Tools::getValue('nbload') ? (int)Tools::getValue('nbload') : (int)(Configuration::get('SNSPRT_NUMDISPLAY'));

		if (!$context) $context = Context::getContext();
		if (empty($order_by) || $order_by == 'position') $order_by = 'price';
		if (empty($order_way)) $order_way = 'DESC';
		if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add'  || $order_by == 'date_upd')
			$order_by_prefix = 'p';
		else if ($order_by == 'name')
			$order_by_prefix = 'pl';
		if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way))
			die (Tools::displayError());
		$current_date = date('Y-m-d H:i:s');
		$ids_product = SNSProductTabsClass::_getProductIdByDate((!$beginning ? $current_date : $beginning), (!$ending ? $current_date : $ending), $context);

		$tab_id_product = array();
		foreach ($ids_product as $product)
			if (is_array($product))
				$tab_id_product[] = (int)$product['id_product'];
			else
				$tab_id_product[] = (int)$product;

		$front = true;
		if (!in_array($context->controller->controller_type, array('front', 'modulefront')))
			$front = false;

		$sql_groups = '';
		if (Group::isFeatureActive())
		{
			$groups = FrontController::getCurrentCustomerGroups();
			$sql_groups = 'AND p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_group` cg
				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
				WHERE cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').'
			)';
		}
		
		if (strpos($order_by, '.') > 0)
		{
			$order_by = explode('.', $order_by);
			$order_by = pSQL($order_by[0]).'.`'.pSQL($order_by[1]).'`';
		}

		$sql = '
		SELECT
			p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`,
			MAX(product_attribute_shop.id_product_attribute) id_product_attribute,
			pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`,
			pl.`name`, MAX(image_shop.`id_image`) id_image, il.`legend`, m.`name` AS manufacturer_name,
			DATEDIFF(
				p.`date_add`,
				DATE_SUB(
					NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
				)
			) > 0 AS new
		FROM `'._DB_PREFIX_.'product` p
		'.Shop::addSqlAssociation('product', 'p').'
		LEFT JOIN '._DB_PREFIX_.'product_attribute pa ON (pa.id_product = p.id_product)
		'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on=1').'
		'.Product::sqlStock('p', 0, false, $context->shop).'
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
			p.`id_product` = pl.`id_product`
			AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
		)
		LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
		Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
		LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
		WHERE product_shop.`active` = 1
		AND product_shop.`show_price` = 1
		'.($front ? ' AND p.`visibility` IN ("both", "catalog")' : '').'
		'.((!$beginning && !$ending) ? ' AND p.`id_product` IN ('.((is_array($tab_id_product) && count($tab_id_product)) ? implode(', ', $tab_id_product) : 0).')' : '').'
		'.$sql_groups.'
		GROUP BY product_shop.id_product
		ORDER BY '.(isset($order_by_prefix) ? pSQL($order_by_prefix).'.' : '').pSQL($order_by).' '.pSQL($order_way);

		if( !$countProduct ) $sql .=  ($limit > 0 ? ' LIMIT '.(int)$start.','.(int)$limit : '');

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		if($countProduct ) return count($result);
		if ($order_by == 'price')
			Tools::orderbyPrice($result, $order_way);
		$products_ids = array();
		foreach ($result as $row)
			$products_ids[] = $row['id_product'];
		
		Product::cacheFrontFeatures($products_ids, $id_lang);

		return Product::getProductsProperties($id_lang, $result);
	}
	public static function getBestSalesLight($countProduct = false)
	{
		$context = Context::getContext();
		$id_lang = (int) $context->language->id;
		
		$start = Tools::getValue('ajax_start') ? (int)Tools::getValue('ajax_start') : 0;
		$limit = Tools::getValue('nbload') ? (int)Tools::getValue('nbload') : (int)(Configuration::get('SNSPRT_NUMDISPLAY'));

		$sql_groups = '';
		if (Group::isFeatureActive())
		{
			$groups = FrontController::getCurrentCustomerGroups();
			$sql_groups = 'AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1');
		}

		//Subquery: get product ids in a separate query to (greatly!) improve performances and RAM usage
		$products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT cp.`id_product`
		FROM `'._DB_PREFIX_.'category_product` cp
		LEFT JOIN `'._DB_PREFIX_.'category_group` cg ON (cg.`id_category` = cp.`id_category`)
		WHERE cg.`id_group` '.$sql_groups);
		
		$ids = array();
		foreach ($products as $product)
			$ids[$product['id_product']] = 1;

		$ids = array_keys($ids);		
		sort($ids);
		$ids = count($ids) > 0 ? implode(',', $ids) : 'NULL';

		//Main query
		$sql = '
		SELECT
			p.id_product,  MAX(product_attribute_shop.id_product_attribute) id_product_attribute, pl.`link_rewrite`, pl.`name`, pl.`description_short`, product_shop.`id_category_default`,
			MAX(image_shop.`id_image`) id_image, il.`legend`,
			ps.`quantity` AS sales, p.`ean13`, p.`upc`, cl.`link_rewrite` AS category, p.show_price, p.available_for_order, IFNULL(stock.quantity, 0) as quantity, p.customizable,
			IFNULL(pa.minimal_quantity, p.minimal_quantity) as minimal_quantity, stock.out_of_stock,
			product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.(Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ? (int)Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY')).'" as new, product_shop.`on_sale`
		FROM `'._DB_PREFIX_.'product_sale` ps
		LEFT JOIN `'._DB_PREFIX_.'product` p ON ps.`id_product` = p.`id_product`
		'.Shop::addSqlAssociation('product', 'p').'
		LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
			ON (p.`id_product` = pa.`id_product`)
		'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
		'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
			ON p.`id_product` = pl.`id_product`
			AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
		LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
		Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
		LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
			ON cl.`id_category` = product_shop.`id_category_default`
			AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').'
		WHERE product_shop.`active` = 1
		AND p.`visibility` != \'none\'
		AND p.`id_product` IN ('.$ids.')
		GROUP BY product_shop.id_product
		ORDER BY sales DESC';

		if( !$countProduct ) $sql .=  ($limit > 0 ? ' LIMIT '.(int)$start.','.(int)$limit : '');

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		if($countProduct ) return count($result);
		
		$products_ids = array();
		foreach ($result as $row)
			$products_ids[] = $row['id_product'];
		
		Product::cacheFrontFeatures($products_ids, $id_lang);

		return Product::getProductsProperties($id_lang, $result);
	}
	public static function getNewProducts($countProduct = false)
	{
		$context = Context::getContext();
		$id_lang = (int) $context->language->id;
		$order_by = null;
		$order_way = null;
		
		$start = Tools::getValue('ajax_start') ? (int)Tools::getValue('ajax_start') : 0;
		$limit = Tools::getValue('nbload') ? (int)Tools::getValue('nbload') : (int)(Configuration::get('SNSPRT_NUMDISPLAY'));

		$PS_NB_DAYS_NEW_PRODUCT = Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ? (int)Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;

		$front = true;
		if (!in_array($context->controller->controller_type, array('front', 'modulefront')))
			$front = false;

		if (empty($order_by) || $order_by == 'position') $order_by = 'date_add';
		if (empty($order_way)) $order_way = 'DESC';
		if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add'  || $order_by == 'date_upd')
			$order_by_prefix = 'p';
		else if ($order_by == 'name')
			$order_by_prefix = 'pl';
		if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way))
			die(Tools::displayError());

		$sql_groups = '';
		if (Group::isFeatureActive())
		{
			$groups = FrontController::getCurrentCustomerGroups();
			$sql_groups = 'AND p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_group` cg
				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
				WHERE cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').'
			)';
		}

		if (strpos($order_by, '.') > 0)
		{
			$order_by = explode('.', $order_by);
			$order_by_prefix = $order_by[0];
			$order_by = $order_by[1];
		}

		$sql = new DbQuery();
		$sql->select(
			'p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`,
			pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`, MAX(image_shop.`id_image`) id_image, il.`legend`, m.`name` AS manufacturer_name,
			product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.$PS_NB_DAYS_NEW_PRODUCT.' DAY')).'" as new'
		);

		$sql->from('product', 'p');
		$sql->join(Shop::addSqlAssociation('product', 'p'));
		$sql->leftJoin('product_lang', 'pl', '
			p.`id_product` = pl.`id_product`
			AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl')
		);
		$sql->leftJoin('image', 'i', 'i.`id_product` = p.`id_product`');
		$sql->join(Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1'));
		$sql->leftJoin('image_lang', 'il', 'i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang);
		$sql->leftJoin('manufacturer', 'm', 'm.`id_manufacturer` = p.`id_manufacturer`');

		$sql->where('product_shop.`active` = 1');
		if ($front)
			$sql->where('product_shop.`visibility` IN ("both", "catalog")');
		$sql->where('product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.$PS_NB_DAYS_NEW_PRODUCT.' DAY')).'"');
		if (Group::isFeatureActive())
			$sql->where('p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_group` cg
				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
				WHERE cg.`id_group` '.$sql_groups.'
			)');
		$sql->groupBy('product_shop.id_product');

		$sql->orderBy((isset($order_by_prefix) ? pSQL($order_by_prefix).'.' : '').'`'.pSQL($order_by).'` '.pSQL($order_way));
		
		if( !$countProduct && $limit > 0) {
			$sql->limit((int)$limit, (int)$start);
		}
		
		if (Combination::isFeatureActive())
		{
			$sql->select('MAX(product_attribute_shop.id_product_attribute) id_product_attribute');
			$sql->leftOuterJoin('product_attribute', 'pa', 'p.`id_product` = pa.`id_product`');
			$sql->join(Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on = 1'));
		}
		$sql->join(Product::sqlStock('p', Combination::isFeatureActive() ? 'product_attribute_shop' : 0));

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		if($countProduct ) return count($result);

		if ($order_by == 'price')
			Tools::orderbyPrice($result, $order_way);
		if (!$result)
			return false;

		$products_ids = array();
		foreach ($result as $row)
			$products_ids[] = $row['id_product'];
		// Thus you can avoid one query per product, because there will be only one query for all the products of the cart
		Product::cacheFrontFeatures($products_ids, $id_lang);
		return Product::getProductsProperties((int)$id_lang, $result);
	}
	public static function getFeaturedProducts($countProduct = false) {
		$context = Context::getContext();
		$id_lang = (int) $context->language->id;
		$start = Tools::getValue('ajax_start') ? (int)Tools::getValue('ajax_start') : 0;
		$limit = Tools::getValue('nbload') ? (int)Tools::getValue('nbload') : (int)(Configuration::get('SNSPRT_NUMDISPLAY'));
		$p = 0; 
		$n = 10;
		$order_by = null;
		$order_way = null;
		$active = true;

		$product_ids = Configuration::get('SNSPRT_PRDIDS');
		
		if(!$product_ids) return;

		$front = true;
		if (!in_array($context->controller->controller_type, array('front', 'modulefront')))
			$front = false;

		if ($p < 1) $p = 1;

		if (empty($order_by))
			$order_by = 'position';
		else
			/* Fix for all modules which are now using lowercase values for 'orderBy' parameter */
			$order_by = strtolower($order_by);

		if (empty($order_way))
			$order_way = 'ASC';

		$order_by_prefix = false;
		if ($order_by == 'id_product' || $order_by == 'date_add' || $order_by == 'date_upd')
			$order_by_prefix = 'p';
		elseif ($order_by == 'name')
			$order_by_prefix = 'pl';
		elseif ($order_by == 'manufacturer' || $order_by == 'manufacturer_name')
		{
			$order_by_prefix = 'm';
			$order_by = 'name';
		}
		elseif ($order_by == 'position')
			$order_by_prefix = 'cp';

		if ($order_by == 'price')
			$order_by = 'orderprice';

		if (!Validate::isBool($active) || !Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way))
			die (Tools::displayError());

		$id_supplier = (int)Tools::getValue('id_supplier');

		$sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity'.(Combination::isFeatureActive() ? ', MAX(product_attribute_shop.id_product_attribute) id_product_attribute, MAX(product_attribute_shop.minimal_quantity) AS product_attribute_minimal_quantity' : '').', pl.`description`, pl.`description_short`, pl.`available_now`,
					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,
					MAX(il.`legend`) as legend, m.`name` AS manufacturer_name, cl.`name` AS category_default,
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new, product_shop.price AS orderprice
				FROM `'._DB_PREFIX_.'category_product` cp
				LEFT JOIN `'._DB_PREFIX_.'product` p
					ON p.`id_product` = cp.`id_product`
				'.Shop::addSqlAssociation('product', 'p').
				(Combination::isFeatureActive() ? 'LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
				ON (p.`id_product` = pa.`id_product`)
				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop) :  Product::sqlStock('p', 'product', false, Context::getContext()->shop)).'
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
					ON (product_shop.`id_category_default` = cl.`id_category`
					AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON (p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
				LEFT JOIN `'._DB_PREFIX_.'image` i
					ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il
					ON (image_shop.`id_image` = il.`id_image`
					AND il.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m
					ON m.`id_manufacturer` = p.`id_manufacturer`
				WHERE product_shop.`id_shop` = '.(int)$context->shop->id
					.($active ? ' AND product_shop.`active` = 1' : '')
					.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '')
					.($id_supplier ? ' AND p.id_supplier = '.(int)$id_supplier : '')
					.' AND p.`id_product` IN ('.$product_ids.')'
					.' GROUP BY product_shop.id_product';

		$sql .= ' ORDER BY FIELD(LEFT(p.`id_product`, 5), '.$product_ids.')';

		if( !$countProduct ) $sql .=  ($limit > 0 ? ' LIMIT '.(int)$start.','.(int)$limit : '');

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		if($countProduct ) return count($result);

		if ($order_by == 'orderprice')
			Tools::orderbyPrice($result, $order_way);

		$products_ids = array();
		foreach ($result as $row)
			$products_ids[] = $row['id_product'];
		
		Product::cacheFrontFeatures($products_ids, $id_lang);

		return Product::getProductsProperties($id_lang, $result);
		
	}
	public  function _getFieldProducts($field_filter = null, $countProduct = false ) {
		//	featured_product	special_product		new_product		top_sellers
		$products = array();
		if($field_filter == 'special_product') {
			$products = SNSProductTabsClass::getPricesDrop($countProduct);
		} elseif($field_filter == 'top_sellers') {
			$products = SNSProductTabsClass::getBestSalesLight($countProduct);
		} elseif($field_filter == 'new_product') {
			$products = SNSProductTabsClass::getNewProducts($countProduct);
		} elseif($field_filter == 'featured_product') {
			$products = SNSProductTabsClass::getFeaturedProducts($countProduct);
		}
		return $products;
	}
	protected function _countProduct($catids , $field = null){
		!is_array($catids) && settype($catids, 'array');
		if($field == null) {
			$countProduct = $this->_getProducts($catids, $field, true);
		} else {
			$countProduct = $this->_getFieldProducts($field, true);
		}
		return $countProduct;	
	}
	public function _getProductInfor($_catids , $field = null)
	{
		$field = $field == 'categories' ? null : $field;
		$_catids = is_string($_catids) &&  $_catids != '' ? explode(',',$_catids) : $_catids;
		!is_array($_catids) && settype($_catids, 'array');
		if($field == null) {
			$products = $this->_getProducts($_catids, $field);
		} else {
			$products = $this->_getFieldProducts($field);
		}
		if(empty($products)) return;
		$list = array();
		foreach ($products as $product)
		{
			$obj     = new Product((int) ($product['id_product']), false, $this->context->language->id);
			$images  = $obj->getImages($this->context->language->id);
			$_images = array();
			if (!empty($images))
			{
				foreach ($images as $k => $image)
				{
					$_images[] = $obj->id . '-' . $image['id_image'];
				}
			}
			// $product['title']   = $this->truncate($product['name'], $params['name_maxlength']);
			// $product['desc']    = $this->_cleanText($product['description']);
			// $product['desc']    = $this->_trimEncode($product['desc']) != '' ? $product['desc'] : $this->_cleanText($product['description_short']);
			// $product['desc']    = $this->truncate($product['desc'], $params['description_maxlength']);
			$product['_images'] = $_images;
			//$product['_target'] = $this->parseTarget($params['target']);
			$list[]        = $product;
		}
		return $list;
	}
	protected function _getCategoriesInfor($catids){
	
		!is_array($catids) && settype($catids, 'array');
		if(empty($catids)) return;
		$categories = Category::getCategoryInformations($catids);
		if(empty($categories)) return;
		$list = array();
		foreach( $categories as $category )
		{
			$categoryImageUrl = $this->context->link->getCatImageLink(
				$category['link_rewrite'],
				$category['id_category'],
				 'category_default'
			);
			$category['image'] = $categoryImageUrl;
			$category['count'] = $this->_countProduct($category['id_category']);
			$category['child'] = $this->_getProductInfor($category['id_category']);
			if($category['count'] > 0)
			{
				$list[$category['id_category']] = $category;
			}
			
		}
		return $list;
	}
	protected function _getData()
	{
		$menu_item = $this->getMenuItems();
		if (empty($menu_item)) return;
		$categories = array();
		$catids_all = array();
		$catids_filter = array();
		$catids_field = array();
		$menu_item = array_unique($menu_item);
		foreach ($menu_item as $i => $item) {
			$catids_all[] = $item;
			$id = (int) $item;
			if ($id > 0)
				$catids_filter[] = $id ;
			else 
                $catids_field[] = $item;			
		}
		
		$return = array();
		$list = array();
		foreach($catids_all as  $catid) {
			$flag = true;
			$type = $catid > 0 ? 'categories' : 'field';
			switch ($type) 
			{
				case 'categories':
					!is_array($catid) && settype($catid, 'array');
					$categories = Category::getCategoryInformations($catid);
					if(empty($categories)) return;
					foreach( $categories as $category )
					{
						$categoryImageUrl = $this->context->link->getCatImageLink(
							$category['link_rewrite'],
							$category['id_category'],
							 'category_default'
						);
						$category['tab_name'] = $category['name'];
						$category['tab_catid'] = implode(',',(array)$category['id_category']);
						$category['tab_type'] = 'categories';
						$category['tab_unique'] = $category['id_category'];
						$category['image'] = $categoryImageUrl;
						$category['count'] = $this->_countProduct($category['id_category']);
						//if($category['count'] > 0)
						//{
							$list[$category['id_category']] = $category;
						//}
						
					}
					
					break;
				case 'field':
					$tmp = array();
					if(empty($catids_field)) return;
					foreach($catids_field as $cat_f) {
						$tmp['tab_name'] = $this->getLabelField($cat_f);
						$tmp['tab_catid'] = '';//implode(',',$catids_filter);
						$tmp['tab_type'] = $cat_f;
						$tmp['tab_unique'] = $cat_f;
						$tmp['count'] = $this->_countProduct($catids_filter , $cat_f );
						$list[$cat_f] = $tmp;
					}
					break;
			}
			
		}
		if(empty($list)) return;
		foreach($catids_all as  $catid) {
			$return[$catid] = $list[$catid];
		}
		
		$selected = false;
		if (!$selected) 
		{
			foreach ($return as $key => $ret) 
			{
				//if ($ret['count'] > 0) {
					$ret['first_select'] = 'active tab-loaded';
					
					if ($ret['count'] > 0) {
						$ret['child'] = $this->_getProductInfor($ret['tab_catid'],$ret['tab_type']);
					} else {
						$ret['child'] = 'emptyproduct';
					}
					
					$return[$key] = $ret;
					break;
				//}
			}
		}
		return $return;
	}

    protected function _createTab() {
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
        $tab->class_name = "AdminSNSProductTabs";
        $tab->name = array();
        foreach (Language::getLanguages() as $lang){
            $tab->name[$lang['id_lang']] = "SNS Product Tabs";
        }
        $tab->id_parent = $parentTab->id;
        $tab->module = $this->name;
        $response &= $tab->add();

        return $response;
    }
    protected function _deleteTab() {
        $id_tab = Tab::getIdFromClassName('AdminSNSProductTabs');
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
