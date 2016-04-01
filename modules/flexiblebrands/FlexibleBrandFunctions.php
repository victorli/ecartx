<?php
class FlexibleBrandFunctions{
    static function buildSelectOption( $arrContent = array(), $selected = ''){
        $keys = array_keys($arrContent);		
    	$content = '';					
		for($i = 0; $i<count($arrContent); $i++){		  
			if($keys[$i] === $selected){
				$content .= '<option value = "'.$keys[$i].'" selected="selected">'.$arrContent[$keys[$i]].'</option>';						
			}else{
				$content .= '<option value = "'.$keys[$i].'">'.$arrContent[$keys[$i]].'</option>';
			}					
		}
    	return $content;
    }
    static function getAllCategories($parentId = 0, $sp='', $arr=null){
        if($arr == null) $arr = array();
        $items = DB::getInstance()->executeS("Select id_category, nleft, nright From "._DB_PREFIX_."category Where id_parent = $parentId");
        if($items){
            foreach($items as $item){
                $arr[] = array('id_category'=>$item['id_category'], 'sp'=>$sp);
                $arr = Functions::getAllCategories($item['id_category'], $sp.'- ', $arr);
            }
        }
        return $arr;
    }
    static function getCategoryIds($parentId = 0, $arr=null){
        if($arr == null) $arr = array();
        $items = DB::getInstance()->executeS("Select id_category From "._DB_PREFIX_."category Where id_parent = $parentId");
        if($items){
            foreach($items as $item){
                $arr[] = $item['id_category'];
                $arr = Functions::getCategoryIds($item['id_category'], $arr);
            }
        }
        return $arr;
    }
    static function getCategoryNameById($id, $langId, $shopId){
        return DB::getInstance()->getValue("Select name From "._DB_PREFIX_."category_lang Where id_category = $id AND `id_shop` = '$shopId' AND `id_lang` = '$langId'");        
    }
    static function getModuleTitleById($id, $langId, $shopId){
        $module_title =  DB::getInstance()->getValue("Select module_title From "._DB_PREFIX_."flexiblecustom_modules_lang Where module_id = $id AND `id_shop` = '$shopId' AND `id_lang` = '$langId'");
        if($module_title) return $module_title;
        else return "";
    }
    static function getCategoryTitleById($id, $langId, $shopId){
        $categoryTitle =  DB::getInstance()->getValue("Select title From "._DB_PREFIX_."flexiblecustom_module_category_lang Where category_id = $id AND `id_shop` = '$shopId' AND `id_lang` = '$langId'");
        if($categoryTitle) return $categoryTitle;
        else return "";
    }
    public static function getProductsOrderSales($id_lang, $arrCategory = array(), $params = null, $limit, $short = true, $getProperties = true, $total=false, $offset = 0){	
        $order_by = 'sales';
        $order_way = $params->orderType;
        $where = "";
        if($arrCategory) $catIds = implode(', ', $arrCategory);        
        if($params->displayOnly == 'condition-new') $where .= " AND p.condition = 'new'";
        elseif($params->displayOnly == 'condition-used') $where .= " AND p.condition = 'used'";
        elseif($params->displayOnly == 'condition-refurbished') $where .= " AND p.condition = 'refurbished'";
        
        if (Group::isFeatureActive())
		{
			$groups = FrontController::getCurrentCustomerGroups();
			$where .= 'AND p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_group` cg
				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
				WHERE cp.id_category IN ('.$catIds.') AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').'
			)';
		}else{
            $where .= 'AND p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_product` cp 
				WHERE cp.id_category IN ('.$catIds.'))';
		}
		if($total == true){
			$sql = 'SELECT COUNT(p.id_product)
				FROM `'._DB_PREFIX_.'product_sale` ps
				LEFT JOIN `'._DB_PREFIX_.'product` p ON ps.`id_product` = p.`id_product`
				'.Shop::addSqlAssociation('product', 'p', false).'
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'				
				WHERE product_shop.`active` = 1 AND p.`visibility` != \'none\'  '.$where;
				return (int) DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);				
		}        	
		//Main query
        $interval = Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;
        if($short == true){
            $select = 'p.id_product, p.on_sale, p.price, p.id_category_default, p.reference, p.ean13, p.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`name`, pl.`link_rewrite`, MAX(image_shop.`id_image`) id_image, DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.$interval.' DAY)) > 0 AS new, ps.`quantity` AS sales';
        }else{
            $select = 'p.*, product_shop.*,
					pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`,
					pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`,
					
					MAX(image_shop.`id_image`) id_image, il.`legend`,
					ps.`quantity` AS sales, pl.`meta_keywords`, pl.`meta_title`, pl.`meta_description`,
					DATEDIFF(p.`date_add`, DATE_SUB(NOW(),INTERVAL '.$interval.' DAY)) > 0 AS new';
        }
        
		$sql = 'SELECT DISTINCT '.$select.'
				FROM `'._DB_PREFIX_.'product_sale` ps
				LEFT JOIN `'._DB_PREFIX_.'product` p ON ps.`id_product` = p.`id_product`
				'.Shop::addSqlAssociation('product', 'p', false).'
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
				LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (product_shop.`id_tax_rules_group` = tr.`id_tax_rules_group`)
					AND tr.`id_country` = '.(int)Context::getContext()->country->id.'
					AND tr.`id_state` = 0
				LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
				'.Product::sqlStock('p').'
				WHERE product_shop.`active` = 1
					AND p.`visibility` != \'none\'  '.$where.' 
				GROUP BY product_shop.id_product
				ORDER BY `'.pSQL($order_by).'` '.pSQL($order_way).' Limit '.$offset.', '.$limit;
				//LIMIT '.(int)($page_number * $nb_products).', '.(int)$nb_products;
        			
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);               
		//if ($final_order_by == 'price') Tools::orderbyPrice($result, $order_way);
		if (!$result) return false;
        if($getProperties == false) return $result;
		return Product::getProductsProperties($id_lang, $result);
	}
	public static function getProductRatings($id_product)
	{
		$validate = Configuration::get('PRODUCT_COMMENTS_MODERATE');
		$sql = 'SELECT (SUM(pc.`grade`) / COUNT(pc.`grade`)) AS avg,
				MIN(pc.`grade`) AS min,
				MAX(pc.`grade`) AS max
			FROM `'._DB_PREFIX_.'product_comment` pc
			WHERE pc.`id_product` = '.(int)$id_product.'
			AND pc.`deleted` = 0'.
			($validate == '1' ? ' AND pc.`validate` = 1' : '');


		return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);

	}
    static function getProductsOrderPrice($id_lang, $arrCategory = array(), $params = null, $limit, $short = true, $getProperties = true, $total = false, $offset = 0){
        $order_by = 'price';
        $order_way = $params->orderType;        
        $where = "";
        if($arrCategory) $catIds = implode(', ', $arrCategory);
        
        if($params->displayOnly == 'condition-new') $where .= " AND p.condition = 'new'";
        elseif($params->displayOnly == 'condition-used') $where .= " AND p.condition = 'used'";
        elseif($params->displayOnly == 'condition-refurbished') $where .= " AND p.condition = 'refurbished'";
        
        if (Group::isFeatureActive())
		{
			$groups = FrontController::getCurrentCustomerGroups();
			$where .= 'AND p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_group` cg
				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
				WHERE cp.id_category IN ('.$catIds.') AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').'
			)';
		}else{
            $where .= 'AND p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_product` cp 
				WHERE cp.id_category IN ('.$catIds.'))';
		}
		if($total == true){
			$sql = 'SELECT COUNT(p.id_product)
				FROM  `'._DB_PREFIX_.'product` p 
                '.Shop::addSqlAssociation('product', 'p', false).'				
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'				
				WHERE product_shop.`active` = 1
					AND p.`visibility` != \'none\' '.$where;				
				return (int) DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);				
		}
		
        $interval = Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;
        if($short == true){
            $select = 'p.id_product, p.on_sale, p.price, p.id_category_default, p.reference, p.ean13, p.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`name`, pl.`link_rewrite`, MAX(image_shop.`id_image`) id_image, DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.$interval.' DAY)) > 0 AS new';
        }else{
            $select = 'p.*, product_shop.*,
					pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`,
					pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`,					
					MAX(image_shop.`id_image`) id_image, DATEDIFF(p.`date_add`, DATE_SUB(NOW(),INTERVAL '.$interval.' DAY)) > 0 AS new';
        }         
        $sql = 'SELECT DISTINCT '.$select.'
				FROM  `'._DB_PREFIX_.'product` p 
                '.Shop::addSqlAssociation('product', 'p', false).'				
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
				LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (product_shop.`id_tax_rules_group` = tr.`id_tax_rules_group`)
					AND tr.`id_country` = '.(int)Context::getContext()->country->id.'
					AND tr.`id_state` = 0
				LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
				'.Product::sqlStock('p').'				
				WHERE product_shop.`active` = 1
					AND p.`visibility` != \'none\' '.$where.'			
				GROUP BY product_shop.id_product
				ORDER BY p.`'.pSQL($order_by).'` '.pSQL($order_way).' Limit '.$offset.', '.$limit;
               
            
           $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            
          if (!$result) return false;
            if($getProperties == false) return $result;
    		return Product::getProductsProperties($id_lang, $result);

    }
    static function getProductsOrderRand($id_lang, $arrCategory = array(), $params = null, $limit, $short = true, $getProperties = true){
        $order_by = 'RAND()';
        $order_way = $params->orderType;        
        $where = "";
        if($arrCategory) $catIds = implode(', ', $arrCategory);
        
        if($params->displayOnly == 'condition-new') $where .= " AND p.condition = 'new'";
        elseif($params->displayOnly == 'condition-used') $where .= " AND p.condition = 'used'";
        elseif($params->displayOnly == 'condition-refurbished') $where .= " AND p.condition = 'refurbished'";
        
        if (Group::isFeatureActive())
		{
			$groups = FrontController::getCurrentCustomerGroups();
			$where .= 'AND p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_group` cg
				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
				WHERE cp.id_category IN ('.$catIds.') AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').'
			)';
		}else{
            $where .= 'AND p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_product` cp 
				WHERE cp.id_category IN ('.$catIds.'))';
		}
		
        $interval = Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;        
        if($short == true){
            $select = 'p.id_product, p.on_sale, p.price, p.id_category_default, p.reference, p.ean13, p.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`name`, pl.`link_rewrite`, MAX(image_shop.`id_image`) id_image, DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.$interval.' DAY)) > 0 AS new';
        }else{
            $select = 'p.*, product_shop.*, 
					pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`,
					pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`,
					MAX(image_shop.`id_image`) id_image, DATEDIFF(p.`date_add`, DATE_SUB(NOW(),INTERVAL '.$interval.' DAY)) > 0 AS new';
        }        
        
        $sql = 'SELECT DISTINCT '.$select.'
				FROM  `'._DB_PREFIX_.'product` p 
                '.Shop::addSqlAssociation('product', 'p', false).'				
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
				LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (product_shop.`id_tax_rules_group` = tr.`id_tax_rules_group`)
					AND tr.`id_country` = '.(int)Context::getContext()->country->id.'
					AND tr.`id_state` = 0
				LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
				'.Product::sqlStock('p').'
				WHERE product_shop.`active` = 1
					AND p.`visibility` != \'none\' '.$where.' 					
				GROUP BY product_shop.id_product
				ORDER BY RAND() Limit '.$limit;       
           $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    
          if (!$result) return false;
            if($getProperties == false) return $result;
    		return Product::getProductsProperties($id_lang, $result);

    }
    static function getProductsOrderAddDate($id_lang, $arrCategory = array(), $params = null, $limit, $short = true, $getProperties = true, $total=false, $offset = 0){
        $order_by = 'date_add';
        $order_way = $params->orderType;        
        $where = "";
        if($arrCategory) $catIds = implode(', ', $arrCategory);        
        if($params->displayOnly == 'condition-new') $where .= " AND p.condition = 'new'";
        elseif($params->displayOnly == 'condition-used') $where .= " AND p.condition = 'used'";
        elseif($params->displayOnly == 'condition-refurbished') $where .= " AND p.condition = 'refurbished'";         
        if (Group::isFeatureActive())
		{
			$groups = FrontController::getCurrentCustomerGroups();
			$where .= 'AND p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_group` cg
				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
				WHERE cp.id_category IN ('.$catIds.') AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').'
			)';
		}else{
            $where .= 'AND p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_product` cp 
				WHERE cp.id_category IN ('.$catIds.'))';
		}
		if($total == true){
			$sql = 'SELECT COUNT(p.id_product)
				FROM  `'._DB_PREFIX_.'product` p 
                '.Shop::addSqlAssociation('product', 'p', false).'				
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'				
				WHERE product_shop.`active` = 1 AND p.`visibility` != \'none\' '.$where;				
				return (int) DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);				
		}
		$interval = Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;
        if($short == true){
            $select = 'p.id_product, p.on_sale, p.price, p.id_category_default, p.reference, p.ean13, p.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`name`, pl.`link_rewrite`, MAX(image_shop.`id_image`) id_image, DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.$interval.' DAY)) > 0 AS new';
        }else{
            $select = 'p.*, product_shop.*, 
					pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`,
					pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`,
					MAX(image_shop.`id_image`) id_image, DATEDIFF(p.`date_add`, DATE_SUB(NOW(),INTERVAL '.$interval.' DAY)) > 0 AS new';
        }        
        
        $sql = 'SELECT DISTINCT '.$select.'
				FROM  `'._DB_PREFIX_.'product` p 
                '.Shop::addSqlAssociation('product', 'p', false).'				
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
				LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (product_shop.`id_tax_rules_group` = tr.`id_tax_rules_group`)
					AND tr.`id_country` = '.(int)Context::getContext()->country->id.'
					AND tr.`id_state` = 0
				LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
				'.Product::sqlStock('p').'
				WHERE product_shop.`active` = 1
					AND p.`visibility` != \'none\' '.$where.' 					
				GROUP BY product_shop.id_product
				ORDER BY p.`'.pSQL($order_by).'` '.pSQL($order_way).' Limit '.$offset.', '.$limit;               
           $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    
          if (!$result) return false;
            if($getProperties == false) return $result;
    		return Product::getProductsProperties($id_lang, $result);
    }
    static function getProductById($id_lang, $productId, $short = true, $getProperties = true){
         
		$interval = Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;
        if($short == true){
            $select = 'p.id_product, p.on_sale, p.price, p.id_category_default, p.reference, p.ean13, p.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`name`, pl.`link_rewrite`, MAX(image_shop.`id_image`) id_image, DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.$interval.' DAY)) > 0 AS new';
        }else{
           $select = 'p.*, product_shop.*, 
				pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`,
				pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`,
				MAX(image_shop.`id_image`) id_image, DATEDIFF(p.`date_add`, DATE_SUB(NOW(),INTERVAL '.$interval.' DAY)) > 0 AS new';
               
        }
        $sql = 'SELECT DISTINCT '.$select.'
				FROM  `'._DB_PREFIX_.'product` p 
                '.Shop::addSqlAssociation('product', 'p', false).'				
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
				LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (product_shop.`id_tax_rules_group` = tr.`id_tax_rules_group`)
					AND tr.`id_country` = '.(int)Context::getContext()->country->id.'
					AND tr.`id_state` = 0
				LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
				'.Product::sqlStock('p').'
				WHERE product_shop.`active` = 1
                    AND product_shop.`id_product` = '.$productId.'
					AND p.`visibility` != \'none\' 					
				GROUP BY product_shop.id_product';                   
                
           $result = DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            if (!$result) return false;
            if($getProperties == false) return $result;            
    		return Product::getProductsProperties($id_lang, $result);
    }
       
    public static function getProductsOrderSpecial($id_lang, $arrCategory = array(), $params = null, $limit, $short = true, $getProperties = true, $total = false, $offset = 0)
	{
        $currentDate = date('Y-m-d');
        $context = Context::getContext();
        $id_address = $context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
		$ids = Address::getCountryAndState($id_address);
		$id_country = (int)($ids['id_country'] ? $ids['id_country'] : Configuration::get('PS_COUNTRY_DEFAULT'));
        
        $order_by = 'reduction';
        $order_way = $params->orderType;        
        $where = "";
        if($arrCategory) $catIds = implode(', ', $arrCategory);        
        if($params->displayOnly == 'condition-new') $where .= " AND p.condition = 'new'";
        elseif($params->displayOnly == 'condition-used') $where .= " AND p.condition = 'used'";
        elseif($params->displayOnly == 'condition-refurbished') $where .= " AND p.condition = 'refurbished'";
                
        if (Group::isFeatureActive())
		{
			$groups = FrontController::getCurrentCustomerGroups();
			$where .= 'AND p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_group` cg
				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
				WHERE cp.id_category IN ('.$catIds.') AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').'
			)';
		}else{
            $where .= 'AND p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_product` cp 
				WHERE cp.id_category IN ('.$catIds.'))';
		}
		if($total == true){
			$sql = 'SELECT COUNT(p.id_product)
				FROM  (`'._DB_PREFIX_.'product` p 
                INNER JOIN `'._DB_PREFIX_.'specific_price` sp On p.id_product = sp.id_product)  
                '.Shop::addSqlAssociation('product', 'p', false).'				
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'				
				WHERE product_shop.`active` = 1 
                    AND sp.`id_shop` IN(0, '.(int)$context->shop->id.') 
					AND sp.`id_currency` IN(0, '.(int)$context->currency->id.') 
					AND sp.`id_country` IN(0, '.(int)$id_country.') 
					AND sp.`id_group` IN(0, '.(int)$context->customer->id_default_group.') 
					AND sp.`id_customer` IN(0) 
					AND sp.`from_quantity` = 1 					
					AND (sp.`from` = \'0000-00-00 00:00:00\' OR \''.pSQL($currentDate).'\' >= sp.`from`)
					AND (sp.`to` = \'0000-00-00 00:00:00\' OR \''.pSQL($currentDate).'\' <= sp.`to`)					
					AND sp.`reduction` > 0
					AND p.`visibility` != \'none\' '.$where;			
			return DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);					
		}
		$interval = Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;
        if($short == true){
            $select = 'p.id_product, p.on_sale, p.price, p.id_category_default, p.reference, p.ean13, p.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`name`, pl.`link_rewrite`, MAX(image_shop.`id_image`) id_image, DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.$interval.' DAY)) > 0 AS new';
            
        }else{
            $select = 'p.*, product_shop.*, 
					pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`,
					pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`,					
					MAX(image_shop.`id_image`) id_image, DATEDIFF(p.`date_add`, DATE_SUB(NOW(),INTERVAL '.$interval.' DAY)) > 0 AS new';
        }        
        
        $sql = 'SELECT DISTINCT '.$select.'
				FROM  (`'._DB_PREFIX_.'product` p 
                INNER JOIN `'._DB_PREFIX_.'specific_price` sp On p.id_product = sp.id_product)  
                '.Shop::addSqlAssociation('product', 'p', false).'				
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
				LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (product_shop.`id_tax_rules_group` = tr.`id_tax_rules_group`)
					AND tr.`id_country` = '.(int)Context::getContext()->country->id.'
					AND tr.`id_state` = 0
				LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
				'.Product::sqlStock('p').'
				WHERE product_shop.`active` = 1 
                    AND sp.`id_shop` IN(0, '.(int)$context->shop->id.') 
					AND sp.`id_currency` IN(0, '.(int)$context->currency->id.') 
					AND sp.`id_country` IN(0, '.(int)$id_country.') 
					AND sp.`id_group` IN(0, '.(int)$context->customer->id_default_group.') 
					AND sp.`id_customer` IN(0) 
					AND sp.`from_quantity` = 1 					
					AND (sp.`from` = \'0000-00-00 00:00:00\' OR \''.pSQL($currentDate).'\' >= sp.`from`)
					AND (sp.`to` = \'0000-00-00 00:00:00\' OR \''.pSQL($currentDate).'\' <= sp.`to`)					
					AND sp.`reduction` > 0
					AND p.`visibility` != \'none\' '.$where.' 					
				GROUP BY product_shop.id_product
				ORDER BY sp.`'.pSQL($order_by).'` '.pSQL($order_way).' Limit '.$offset.', '.$limit;
				            
				
           $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    
          if (!$result) return false;
            if($getProperties == false) return $result;
    		return Product::getProductsProperties($id_lang, $result);        
	}
    
    static function getProductList($id_lang, $arrCategory = array(), $notIn = '', $keyword = '', $getTotal = false, $offset=0, $limit=10){
        
        $where = "";
        if($arrCategory){
            $catIds = implode(', ', $arrCategory);
        }
        if (Group::isFeatureActive())
		{
			$groups = FrontController::getCurrentCustomerGroups();
			$where .= ' AND p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_group` cg
				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
				WHERE cp.`id_product` Not In ('.$notIn.') AND cp.id_category IN ('.$catIds.') AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').'
			)';
		}else{
            $where .= ' AND p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_product` cp 
				WHERE cp.`id_product` Not In ('.$notIn.') AND cp.id_category IN ('.$catIds.'))';
		}
        if($keyword != '') $where .= " AND (p.id_product) LIKE '%".$keyword."%' OR pl.name LIKE '%".$keyword."%'";
        $sqlTotal = 'SELECT COUNT(p.`id_product`) AS nb
					FROM `'._DB_PREFIX_.'product` p
					'.Shop::addSqlAssociation('product', 'p').' 
                    LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					   ON p.`id_product` = pl.`id_product`
					   AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
					WHERE product_shop.`active` = 1 AND product_shop.`active` = 1 AND p.`visibility` != \'none\' '.$where;
        $total = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sqlTotal);
        if($getTotal == true) return $total;
        if($total <=0) return false;                    
        $sql = 'Select p.*, pl.`name`, pl.`link_rewrite`, IFNULL(stock.quantity, 0) as quantity_all, MAX(image_shop.`id_image`) id_image 
                FROM  `'._DB_PREFIX_.'product` p 
                '.Shop::addSqlAssociation('product', 'p', false).'				
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
				LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (product_shop.`id_tax_rules_group` = tr.`id_tax_rules_group`)
					AND tr.`id_country` = '.(int)Context::getContext()->country->id.'
					AND tr.`id_state` = 0
				LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
				'.Product::sqlStock('p').'
				WHERE product_shop.`active` = 1
					AND p.`visibility` != \'none\'  '.$where.'			
				GROUP BY product_shop.id_product Limit '.$offset.', '.$limit;
			
                $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                return Product::getProductsProperties($id_lang, $result);            
    		      //return $result;

    }
    
    
    
    public static function paginationAjaxEx($total, $page_size, $current = 1, $index_limit = 10, $func='loadPage'){
		$total_pages=ceil($total/$page_size);
		$start=max($current-intval($index_limit/2), 1);
		$end=$start+$index_limit-1;
		$output = '';                       
		$output = '<ul class="pagination">';
		if($current==1) {
			$output .= '<li><span>Prev</span></li>';
		}else{
			$i = $current-1;
			$output .= '<li><a href="javascript:void(0)" onclick="'.$func.'(\''.$i.'\')">Prev</a></li>';
		}
		if($start>1){
			$i = 1;
			$output .= '<li><a href="javascript:void(0)" onclick="'.$func.'(\''.$i.'\')">'.$i.'</a></li>';
			$output .= '<li><span>...</span></li>';
		}	
		for ($i=$start;$i<=$end && $i<= $total_pages;$i++) {
			if($i==$current) 
				$output .= '<li class="active"><span >'.$i.'</span></li>';
			else 
				$output .= '<li><a  href="javascript:void(0)" onclick="'.$func.'(\''.$i.'\')">'.$i.'</a></li>';
		}		
		if($total_pages>$end) {
			$i = $total_pages;
			$output .= '<li><span>...</span></li>';
			$output .= '<li><a href="javascript:void(0)" onclick="'.$func.'(\''.$i.'\')">'.$i.'</a></li>';
		}		
		if($current<$total_pages) {
			$i = $current+1;
			$output .= '<li><a href="javascript:void(0)" onclick="'.$func.'(\''.$i.'\')">Next</a></li>';
		} else {
			$output .= '<li><span>Next</span></li>';
		}
		$output .= '</ul>';		
		return $output;		
	}
    public static function showModuleCategoryIcon($icon = ''){
        if($icon && file_exists(_PS_MODULE_DIR_.'flexiblecustom/images/icons/'.$icon))
            return _PS_BASE_URL_.__PS_BASE_URI__.'modules/flexiblecustom/images/icons/'.$icon;
        else
            return _PS_BASE_URL_.__PS_BASE_URI__.'modules/flexiblecustom/images/icons/default.jpg'; 
    }
    public static function showModuleBanner($banner = '', $check = false){
        if($banner && file_exists(_PS_MODULE_DIR_.'flexiblecustom/images/banners/'.$banner))
            return _PS_BASE_URL_.__PS_BASE_URI__.'modules/flexiblecustom/images/banners/'.$banner;
        else
            if($check == true) 
                return '';
            else
                return _PS_BASE_URL_.__PS_BASE_URI__.'modules/flexiblecustom/images/banners/default.jpg'; 
    }
}