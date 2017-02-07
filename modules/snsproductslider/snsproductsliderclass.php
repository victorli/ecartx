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

class SNSProductSliderClass {
	public static function getViewedProduct($params, $id_lang, $page_number = 0, $nb_products = 10, $count = false,
		$order_by = null, $order_way = null, $beginning = false, $ending = false, Context $context = null)
	{
		$ids_product = (isset($params['cookie']->viewed) && !empty($params['cookie']->viewed)) ? array_slice(array_reverse(explode(',', $params['cookie']->viewed)), 0, 10) : array();
		
		if (count($ids_product)) {
			
			if (!Validate::isBool($count))
				die(Tools::displayError());

			if (!$context) $context = Context::getContext();
			if ($page_number < 0) $page_number = 0;
			if ($nb_products < 1) $nb_products = 10;
			if (empty($order_by) || $order_by == 'position') $order_by = 'price';
			if (empty($order_way)) $order_way = 'DESC';
			if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add'  || $order_by == 'date_upd')
				$order_by_prefix = 'p';
			else if ($order_by == 'name')
				$order_by_prefix = 'pl';
			if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way))
				die (Tools::displayError());
			$current_date = date('Y-m-d H:i:s');

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

			if ($count)
			{
				return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
				SELECT COUNT(DISTINCT p.`id_product`)
				FROM `'._DB_PREFIX_.'product` p
				'.Shop::addSqlAssociation('product', 'p').'
				WHERE product_shop.`active` = 1
				AND product_shop.`show_price` = 1
				'.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').'
				'.((!$beginning && !$ending) ? 'AND p.`id_product` IN('.((is_array($tab_id_product) && count($tab_id_product)) ? implode(', ', $tab_id_product) : 0).')' : '').'
				'.$sql_groups);
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
			ORDER BY '.(isset($order_by_prefix) ? pSQL($order_by_prefix).'.' : '').pSQL($order_by).' '.pSQL($order_way).'
			LIMIT '.(int)($page_number * $nb_products).', '.(int)$nb_products;

			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

			if (!$result)
				return false;

			if ($order_by == 'price')
				Tools::orderbyPrice($result, $order_way);
			
			return Product::getProductsProperties($id_lang, $result);
		}
		return;
	}
	public static function getNewProducts($dayfornew ,$id_lang, $page_number = 0, $nb_products = 10, $count = false, $order_by = null, $order_way = null, Context $context = null)
	{
		if (!$context)
			$context = Context::getContext();

		$front = true;
		if (!in_array($context->controller->controller_type, array('front', 'modulefront')))
			$front = false;

		if ($page_number < 0) $page_number = 0;
		if ($nb_products < 1) $nb_products = 10;
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

		if ($count)
		{
			$sql = 'SELECT COUNT(p.`id_product`) AS nb
					FROM `'._DB_PREFIX_.'product` p
					'.Shop::addSqlAssociation('product', 'p').'
					WHERE product_shop.`active` = 1
					AND product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.($dayfornew ? (int)$dayfornew : 20).' DAY')).'"
					'.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').'
					'.$sql_groups;
			return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
		}

		$sql = new DbQuery();
		$sql->select(
			'p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`,
			pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`, MAX(image_shop.`id_image`) id_image, il.`legend`, m.`name` AS manufacturer_name,
			product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.($dayfornew ? (int)$dayfornew : 20).' DAY')).'" as new'
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
		$sql->where('product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.($dayfornew ? (int)$dayfornew : 20).' DAY')).'"');
		if (Group::isFeatureActive())
			$sql->where('p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_group` cg
				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
				WHERE cg.`id_group` '.$sql_groups.'
			)');
		$sql->groupBy('product_shop.id_product');

		$sql->orderBy((isset($order_by_prefix) ? pSQL($order_by_prefix).'.' : '').'`'.pSQL($order_by).'` '.pSQL($order_way));
		$sql->limit($nb_products, $page_number * $nb_products);

		if (Combination::isFeatureActive())
		{
			$sql->select('MAX(product_attribute_shop.id_product_attribute) id_product_attribute');
			$sql->leftOuterJoin('product_attribute', 'pa', 'p.`id_product` = pa.`id_product`');
			$sql->join(Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on = 1'));
		}
		$sql->join(Product::sqlStock('p', Combination::isFeatureActive() ? 'product_attribute_shop' : 0));

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

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
	public static function getProductIdByDate($id_shop, $id_currency, $id_country, $id_group, $beginning, $ending, $id_customer = 0, $with_combination_id = false)
	{
		if (!SpecificPrice::isFeatureActive())
			return array();

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT `id_product`, `id_product_attribute`
			FROM `'._DB_PREFIX_.'specific_price`
			WHERE	`id_shop` IN(0, '.(int)$id_shop.') AND
					`id_currency` IN(0, '.(int)$id_currency.') AND
					`id_country` IN(0, '.(int)$id_country.') AND
					`id_group` IN(0, '.(int)$id_group.') AND
					`id_customer` IN(0, '.(int)$id_customer.') AND
					`from_quantity` = 1 AND
					(
						(`from` = \'0000-00-00 00:00:00\' OR \''.pSQL($beginning).'\' >= `from`)
						AND
						\''.pSQL($ending).'\' <= `to`
					)
					AND
					`reduction` > 0
		', false);
		$ids_product = array();
		while ($row = Db::getInstance()->nextRow($result))
			$ids_product[] = $with_combination_id ? array('id_product' => (int)$row['id_product'], 'id_product_attribute' => (int)$row['id_product_attribute']) : (int)$row['id_product'];
		return $ids_product;
	}
	protected static function _getProductIdByDate($beginning, $ending, Context $context = null, $with_combination = false)
	{
		if (!$context)
			$context = Context::getContext();

		$id_address = $context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
		$ids = Address::getCountryAndState($id_address);
		$id_country = (int)($ids['id_country'] ? $ids['id_country'] : Configuration::get('PS_COUNTRY_DEFAULT'));

		return SNSProductSliderClass::getProductIdByDate(
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
	public static function getDealsProducts($id_lang, $page_number = 0, $nb_products = 10, $count = false,
		$order_by = null, $order_way = null, $beginning = false, $ending = false, Context $context = null)
	{
		if (!Validate::isBool($count))
			die(Tools::displayError());

		if (!$context) $context = Context::getContext();
		if ($page_number < 0) $page_number = 0;
		if ($nb_products < 1) $nb_products = 10;
		if (empty($order_by) || $order_by == 'position') $order_by = 'price';
		if (empty($order_way)) $order_way = 'DESC';
		if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add'  || $order_by == 'date_upd')
			$order_by_prefix = 'p';
		else if ($order_by == 'name')
			$order_by_prefix = 'pl';
		if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way))
			die (Tools::displayError());
		$current_date = date('Y-m-d H:i:s');
		$ids_product = SNSProductSliderClass::_getProductIdByDate((!$beginning ? $current_date : $beginning), (!$ending ? $current_date : $ending), $context);

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

		if ($count)
		{
			return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
			SELECT COUNT(DISTINCT p.`id_product`)
			FROM `'._DB_PREFIX_.'product` p
			'.Shop::addSqlAssociation('product', 'p').'
			WHERE product_shop.`active` = 1
			AND product_shop.`show_price` = 1
			'.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').'
			'.((!$beginning && !$ending) ? 'AND p.`id_product` IN('.((is_array($tab_id_product) && count($tab_id_product)) ? implode(', ', $tab_id_product) : 0).')' : '').'
			'.$sql_groups);
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
		ORDER BY '.(isset($order_by_prefix) ? pSQL($order_by_prefix).'.' : '').pSQL($order_by).' '.pSQL($order_way).'
		LIMIT '.(int)($page_number * $nb_products).', '.(int)$nb_products;

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

		if (!$result)
			return false;

		if ($order_by == 'price')
			Tools::orderbyPrice($result, $order_way);

		return Product::getProductsProperties($id_lang, $result);
	}
}
