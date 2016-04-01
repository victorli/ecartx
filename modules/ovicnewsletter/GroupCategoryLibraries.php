<?php
class GroupCategoryLibraries{
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


    static function getCategoryLangNameById($id, $langId, $shopId){
        $name =  DB::getInstance()->getValue("Select name From "._DB_PREFIX_."category_lang Where id_category = $id AND `id_shop` = '$shopId' AND `id_lang` = '$langId'");
        if($name) return $name;
        else return '';   
    }
	static function getGroupLangById($id, $langId, $shopId){
        $groupLang =  DB::getInstance()->getRow("Select * From "._DB_PREFIX_."groupcategory_group_lang Where group_id = $id AND `id_shop` = '$shopId' AND `id_lang` = '$langId'");
        if($groupLang) return $groupLang;
        else return null;
    }
    static function getItemLangById($id, $langId, $shopId){
        $itemLang =  DB::getInstance()->getRow("Select * From "._DB_PREFIX_."groupcategory_item_lang Where itemId = $id AND `id_shop` = '$shopId' AND `id_lang` = '$langId'");
        if($itemLang) return $itemLang;
        else return null;
    }
    
    
    static function getAllCategories($langId, $shopId, $parentId = 0, $sp='', $arr=null){
        if($arr == null) $arr = array();
        $items = DB::getInstance()->executeS("Select c.id_category, cl.name From "._DB_PREFIX_."category as c Inner Join "._DB_PREFIX_."category_lang as cl On c.id_category = cl.id_category Where c.id_parent = $parentId AND cl.id_lang = ".$langId." AND id_shop = ".$shopId);
        if($items){
            foreach($items as $item){
                $arr[] = array('id_category'=>$item['id_category'], 'name'=>$item['name'], 'sp'=>$sp);
                $arr = GroupCategoryLibraries::getAllCategories($langId, $shopId, $item['id_category'], $sp.'- ', $arr);
            }
        }
        return $arr;
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
    public static function getItemBannerSrc($image = '', $check = false){
        if($image && file_exists(_PS_MODULE_DIR_.$this->name.'/images/banners/'.$image))
            return _PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/images/banners/'.$image;
        else
            if($check == true) 
                return '';
            else
                return _PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/images/banners/default.jpg'; 
    }
    public static function getGroupBannerSrc($image = '', $check = false){
        if($image && file_exists(_PS_MODULE_DIR_.$this->name.'/images/banners/'.$image))
            return _PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/images/banners/'.$image;
        else
            if($check == true) 
                return '';
            else
                return _PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/images/banners/default.jpg'; 
    }
    public static function getGroupIconSrc($image = '', $check = false){
        if($image && file_exists(_PS_MODULE_DIR_.$this->name.'/images/icons/'.$image))
            return _PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/images/icons/'.$image;
        else
            if($check == true) 
                return '';
            else
                return _PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/images/icons/default.jpg'; 
    }
	
	static function getCategoryIds($parentId = 0, $arr=null){
        if($arr == null) $arr = array();
        $items = DB::getInstance()->executeS("Select id_category From "._DB_PREFIX_."category Where id_parent = $parentId");
        if($items){
            foreach($items as $item){
                $arr[] = $item['id_category'];
                $arr = GroupCategoryLibraries::getCategoryIds($item['id_category'], $arr);
            }
        }
        return $arr;
    }
	public static function getProductRatings($id_product)
	{
		$validate = Configuration::get('PRODUCT_COMMENTS_MODERATE');
		$sql = 'SELECT COUNT(pc.`grade`) AS total, (SUM(pc.`grade`) / COUNT(pc.`grade`)) AS avg,
				MIN(pc.`grade`) AS min,
				MAX(pc.`grade`) AS max
			FROM `'._DB_PREFIX_.'product_comment` pc
			WHERE pc.`id_product` = '.(int)$id_product.'
			AND pc.`deleted` = 0'.
			($validate == '1' ? ' AND pc.`validate` = 1' : '');

		return DB::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
	}
    public static function getTotalViewed($id_product)
	{
		$view = DB::getInstance()->getRow('
		SELECT SUM(pv.`counter`) AS total
		FROM `'._DB_PREFIX_.'page_viewed` pv
		LEFT JOIN `'._DB_PREFIX_.'date_range` dr ON pv.`id_date_range` = dr.`id_date_range`
		LEFT JOIN `'._DB_PREFIX_.'page` p ON pv.`id_page` = p.`id_page`
		LEFT JOIN `'._DB_PREFIX_.'page_type` pt ON pt.`id_page_type` = p.`id_page_type`
		WHERE pt.`name` = \'product.php\'
		AND p.`id_object` = '.intval($id_product).'');
		return isset($view['total']) ? $view['total'] : 0;
	}
	public static function getProducts_Sales($id_lang, $arrCategory = array(), $params = null, $total=false, $limit=0, $offset = 0){			$context = Context::getContext();	                $where = "";        if($arrCategory) $catIds = implode(', ', $arrCategory);                if($params){            if($params->displayOnly == 'condition-new') $where .= " AND p.condition = 'new'";            elseif($params->displayOnly == 'condition-used') $where .= " AND p.condition = 'used'";            elseif($params->displayOnly == 'condition-refurbished') $where .= " AND p.condition = 'refurbished'";            }                        if (Group::isFeatureActive())		{			$groups = FrontController::getCurrentCustomerGroups();			$where .= 'AND p.`id_product` IN (				SELECT cp.`id_product`				FROM `'._DB_PREFIX_.'category_group` cg				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)				WHERE cp.id_category IN ('.$catIds.') AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').'			)';		}else{            $where .= 'AND p.`id_product` IN (				SELECT cp.`id_product`				FROM `'._DB_PREFIX_.'category_product` cp 				WHERE cp.id_category IN ('.$catIds.'))';		}		if($total == true){			$sql = 'SELECT COUNT(p.id_product)				FROM `'._DB_PREFIX_.'product_sale` ps				LEFT JOIN `'._DB_PREFIX_.'product` p ON ps.`id_product` = p.`id_product`				'.Shop::addSqlAssociation('product', 'p', false).'				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl					ON p.`id_product` = pl.`id_product`					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'								WHERE product_shop.`active` = 1 AND p.`visibility` != \'none\'  '.$where;				return (int) DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);						}        			//Main query        $interval = Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;				$sql = 'SELECT			p.id_product,  MAX(product_attribute_shop.id_product_attribute) id_product_attribute, pl.`link_rewrite`, pl.`name`, pl.`description_short`, product_shop.`id_category_default`,			MAX(image_shop.`id_image`) id_image, il.`legend`,			ps.`quantity` AS sales, p.`ean13`, p.`upc`, cl.`link_rewrite` AS category, p.show_price, p.available_for_order, IFNULL(stock.quantity, 0) as quantity, p.customizable,			IFNULL(pa.minimal_quantity, p.minimal_quantity) as minimal_quantity, stock.out_of_stock,			product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.(Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ? (int)Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY')).'" as new, product_shop.`on_sale`		FROM `'._DB_PREFIX_.'product_sale` ps		LEFT JOIN `'._DB_PREFIX_.'product` p ON ps.`id_product` = p.`id_product`		'.Shop::addSqlAssociation('product', 'p').'		LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa			ON (p.`id_product` = pa.`id_product`)		'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'		'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl			ON p.`id_product` = pl.`id_product`			AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'		LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.		Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')		LEFT JOIN `'._DB_PREFIX_.'category_lang` cl			ON cl.`id_category` = product_shop.`id_category_default`			AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').'		WHERE product_shop.`active` = 1		AND p.`visibility` != \'none\' '.$where.' 				GROUP BY product_shop.id_product		ORDER BY `sales` DESC Limit '.$offset.', '.$limit;				$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);               		if (!$result) return false;        return $result;
	}
	static function getProducts_Price($id_lang, $arrCategory = array(), $params = null, $total = false, $limit=0, $offset = 0){               		$context = Context::getContext();        $where = "";        if($arrCategory) $catIds = implode(', ', $arrCategory);                if($params){            if($params->displayOnly == 'condition-new') $where .= " AND p.condition = 'new'";            elseif($params->displayOnly == 'condition-used') $where .= " AND p.condition = 'used'";            elseif($params->displayOnly == 'condition-refurbished') $where .= " AND p.condition = 'refurbished'";            }                if (Group::isFeatureActive())		{			$groups = FrontController::getCurrentCustomerGroups();			$where .= 'AND p.`id_product` IN (				SELECT cp.`id_product`				FROM `'._DB_PREFIX_.'category_group` cg				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)				WHERE cp.id_category IN ('.$catIds.') AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').'			)';		}else{            $where .= 'AND p.`id_product` IN (				SELECT cp.`id_product`				FROM `'._DB_PREFIX_.'category_product` cp 				WHERE cp.id_category IN ('.$catIds.'))';		}		if($total == true){			$sql = 'SELECT COUNT(p.id_product)				FROM  `'._DB_PREFIX_.'product` p                 '.Shop::addSqlAssociation('product', 'p', false).'								LEFT JOIN `'._DB_PREFIX_.'product_lang` pl					ON p.`id_product` = pl.`id_product`					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'								WHERE product_shop.`id_shop` = '.(int)$context->shop->id.' AND product_shop.`active` = 1 AND p.`visibility` != \'none\' '.$where;								return (int) DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);						}		        $interval = Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;                $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,					il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'						DAY)) > 0 AS new, product_shop.price AS orderprice				FROM `'._DB_PREFIX_.'category_product` cp				LEFT JOIN `'._DB_PREFIX_.'product` p					ON p.`id_product` = cp.`id_product`				'.Shop::addSqlAssociation('product', 'p').'				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa				ON (p.`id_product` = pa.`id_product`)				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl					ON (product_shop.`id_category_default` = cl.`id_category`					AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl					ON (p.`id_product` = pl.`id_product`					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')				LEFT JOIN `'._DB_PREFIX_.'image` i					ON (i.`id_product` = p.`id_product`)'.				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'				LEFT JOIN `'._DB_PREFIX_.'image_lang` il					ON (image_shop.`id_image` = il.`id_image`					AND il.`id_lang` = '.(int)$id_lang.')				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m					ON m.`id_manufacturer` = p.`id_manufacturer`				WHERE product_shop.`id_shop` = '.(int)$context->shop->id.' AND product_shop.`active` = 1 AND p.`visibility` != \'none\' '.$where.' GROUP BY product_shop.id_product ORDER BY p.`price` DESC Limit '.$offset.', '.$limit;           	$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);                      	if (!$result) return false;            return $result;
    }
    static function getProducts_View($id_lang, $arrCategory = array(), $params = null, $total = false, $limit=0, $offset = 0){               		$context = Context::getContext();        $where = "";        if($arrCategory) $catIds = implode(', ', $arrCategory);                if($params){            if($params->displayOnly == 'condition-new') $where .= " AND p.condition = 'new'";            elseif($params->displayOnly == 'condition-used') $where .= " AND p.condition = 'used'";            elseif($params->displayOnly == 'condition-refurbished') $where .= " AND p.condition = 'refurbished'";            }                if (Group::isFeatureActive())		{			$groups = FrontController::getCurrentCustomerGroups();			$where .= 'AND p.`id_product` IN (				SELECT cp.`id_product`				FROM `'._DB_PREFIX_.'category_group` cg				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)				WHERE cp.id_category IN ('.$catIds.') AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').'			)';		}else{            $where .= 'AND p.`id_product` IN (				SELECT cp.`id_product`				FROM `'._DB_PREFIX_.'category_product` cp 				WHERE cp.id_category IN ('.$catIds.'))';		}		if($total == true){			$sql = 'SELECT COUNT(p.id_product)				FROM  `'._DB_PREFIX_.'product` p                 '.Shop::addSqlAssociation('product', 'p', false).'								LEFT JOIN `'._DB_PREFIX_.'product_lang` pl					ON p.`id_product` = pl.`id_product`					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'                LEFT JOIN `'._DB_PREFIX_.'groupcategory_product_view` AS gv                    On gv.productId = p.id_product								WHERE product_shop.`active` = 1					AND p.`visibility` != \'none\' '.$where;								return (int) DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);						}		        $interval = Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;        $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,					il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'						DAY)) > 0 AS new, product_shop.price AS orderprice				FROM `'._DB_PREFIX_.'category_product` cp				LEFT JOIN `'._DB_PREFIX_.'product` p					ON p.`id_product` = cp.`id_product`				'.Shop::addSqlAssociation('product', 'p').'				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa				ON (p.`id_product` = pa.`id_product`)				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl					ON (product_shop.`id_category_default` = cl.`id_category`					AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')				LEFT JOIN `'._DB_PREFIX_.'groupcategory_product_view` AS gv                    On gv.productId = p.id_product					LEFT JOIN `'._DB_PREFIX_.'product_lang` pl					ON (p.`id_product` = pl.`id_product`					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')				LEFT JOIN `'._DB_PREFIX_.'image` i					ON (i.`id_product` = p.`id_product`)'.				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'				LEFT JOIN `'._DB_PREFIX_.'image_lang` il					ON (image_shop.`id_image` = il.`id_image`					AND il.`id_lang` = '.(int)$id_lang.')				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m					ON m.`id_manufacturer` = p.`id_manufacturer`				WHERE product_shop.`id_shop` = '.(int)$context->shop->id.' AND product_shop.`active` = 1 AND p.`visibility` != \'none\' '.$where.' GROUP BY product_shop.id_product ORDER BY gv.`total` DESC Limit '.$offset.', '.$limit;				           	$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);                      	if (!$result) return false;            return $result;
    }
	static function getProducts_List($id_lang, $arrCategory = array(), $params = null, $total = false, $limit=0, $offset = 0){               
        $where = "";
        if($arrCategory) $catIds = implode(', ', $arrCategory);        
        
		if($params){
			if($params->displayOnly == 'condition-new') $where .= " AND p.condition = 'new'";
			elseif($params->displayOnly == 'condition-used') $where .= " AND p.condition = 'used'";
			elseif($params->displayOnly == 'condition-refurbished') $where .= " AND p.condition = 'refurbished'";         
		}
        
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
		
        $interval = Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;				$sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,					il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0 AS new, product_shop.price AS orderprice				FROM `'._DB_PREFIX_.'category_product` cp				LEFT JOIN `'._DB_PREFIX_.'product` p					ON p.`id_product` = cp.`id_product`				'.Shop::addSqlAssociation('product', 'p').'				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa				ON (p.`id_product` = pa.`id_product`)				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl					ON (product_shop.`id_category_default` = cl.`id_category`					AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl					ON (p.`id_product` = pl.`id_product`					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')				LEFT JOIN `'._DB_PREFIX_.'image` i					ON (i.`id_product` = p.`id_product`)'.				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'				LEFT JOIN `'._DB_PREFIX_.'image_lang` il					ON (image_shop.`id_image` = il.`id_image`					AND il.`id_lang` = '.(int)$id_lang.')				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m					ON m.`id_manufacturer` = p.`id_manufacturer`				WHERE product_shop.`id_shop` = '.(int)$context->shop->id.' AND product_shop.`active` = 1 AND p.`visibility` != \'none\' '.$where.' GROUP BY product_shop.id_product Limit '.$offset.', '.$limit;		
           	$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);            
          	if (!$result) return false;
            return $result;
    }
	static function getProducts_AddDate($id_lang, $arrCategory = array(), $params = null, $total=false, $limit=0, $offset = 0){		$context = Context::getContext();        $where = "";        if($arrCategory) $catIds = implode(', ', $arrCategory);                if($params){            if($params->displayOnly == 'condition-new') $where .= " AND p.condition = 'new'";            elseif($params->displayOnly == 'condition-used') $where .= " AND p.condition = 'used'";            elseif($params->displayOnly == 'condition-refurbished') $where .= " AND p.condition = 'refurbished'";            }                 if (Group::isFeatureActive())		{			$groups = FrontController::getCurrentCustomerGroups();			$where .= 'AND p.`id_product` IN (				SELECT cp.`id_product`				FROM `'._DB_PREFIX_.'category_group` cg				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)				WHERE cp.id_category IN ('.$catIds.') AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').'			)';		}else{            $where .= 'AND p.`id_product` IN (				SELECT cp.`id_product`				FROM `'._DB_PREFIX_.'category_product` cp 				WHERE cp.id_category IN ('.$catIds.'))';		}		if($total == true){			$sql = 'SELECT COUNT(p.id_product)				FROM  `'._DB_PREFIX_.'product` p                 '.Shop::addSqlAssociation('product', 'p', false).'								LEFT JOIN `'._DB_PREFIX_.'product_lang` pl					ON p.`id_product` = pl.`id_product`					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'								WHERE product_shop.`active` = 1 AND p.`visibility` != \'none\' '.$where;								return (int) DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);						}				$interval = Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;              $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,					il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0 AS new, product_shop.price AS orderprice				FROM `'._DB_PREFIX_.'category_product` cp				LEFT JOIN `'._DB_PREFIX_.'product` p					ON p.`id_product` = cp.`id_product`				'.Shop::addSqlAssociation('product', 'p').'				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa				ON (p.`id_product` = pa.`id_product`)				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl					ON (product_shop.`id_category_default` = cl.`id_category`					AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl					ON (p.`id_product` = pl.`id_product`					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')				LEFT JOIN `'._DB_PREFIX_.'image` i					ON (i.`id_product` = p.`id_product`)'.				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'				LEFT JOIN `'._DB_PREFIX_.'image_lang` il					ON (image_shop.`id_image` = il.`id_image`					AND il.`id_lang` = '.(int)$id_lang.')				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m					ON m.`id_manufacturer` = p.`id_manufacturer`				WHERE product_shop.`id_shop` = '.(int)$context->shop->id.' AND product_shop.`active` = 1 AND p.`visibility` != \'none\' '.$where.' GROUP BY product_shop.id_product ORDER BY p.`date_add` DESC Limit '.$offset.', '.$limit;				            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);          	if (!$result) return false;			return $result;
    }
	
	public static function getProducts_Special($id_lang, $arrCategory = array(), $params = null, $total = false, $limit=0, $offset = 0)
	{
        $currentDate = date('Y-m-d');        $context = Context::getContext();        $id_address = $context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};		$ids = Address::getCountryAndState($id_address);		$id_country = (int)($ids['id_country'] ? $ids['id_country'] : Configuration::get('PS_COUNTRY_DEFAULT'));                                $where = "";        if($arrCategory) $catIds = implode(', ', $arrCategory);                if($params){            if($params->displayOnly == 'condition-new') $where .= " AND p.condition = 'new'";            elseif($params->displayOnly == 'condition-used') $where .= " AND p.condition = 'used'";            elseif($params->displayOnly == 'condition-refurbished') $where .= " AND p.condition = 'refurbished'";            }                        if (Group::isFeatureActive())		{			$groups = FrontController::getCurrentCustomerGroups();			$where .= 'AND p.`id_product` IN (				SELECT cp.`id_product`				FROM `'._DB_PREFIX_.'category_group` cg				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)				WHERE cp.id_category IN ('.$catIds.') AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').'			)';		}else{            $where .= 'AND p.`id_product` IN (				SELECT cp.`id_product`				FROM `'._DB_PREFIX_.'category_product` cp 				WHERE cp.id_category IN ('.$catIds.'))';		}				if($total == true){			$sql = 'SELECT COUNT(p.id_product)				FROM  (`'._DB_PREFIX_.'product` p                 INNER JOIN `'._DB_PREFIX_.'specific_price` sp On p.id_product = sp.id_product)                  '.Shop::addSqlAssociation('product', 'p', false).'								LEFT JOIN `'._DB_PREFIX_.'product_lang` pl					ON p.`id_product` = pl.`id_product`					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'								WHERE product_shop.`active` = 1                     AND sp.`id_shop` IN(0, '.(int)$context->shop->id.') 					AND sp.`id_currency` IN(0, '.(int)$context->currency->id.') 					AND sp.`id_country` IN(0, '.(int)$id_country.') 					AND sp.`id_group` IN(0, '.(int)$context->customer->id_default_group.') 					AND sp.`id_customer` IN(0) 					AND sp.`from_quantity` = 1 										AND (sp.`from` = \'0000-00-00 00:00:00\' OR \''.pSQL($currentDate).'\' >= sp.`from`)					AND (sp.`to` = \'0000-00-00 00:00:00\' OR \''.pSQL($currentDate).'\' <= sp.`to`)										AND sp.`reduction` > 0					AND p.`visibility` != \'none\' '.$where;						return DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);							}		$interval = Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;                $sql = 'SELECT DISTINCT p.*, product_shop.*, 					pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`,					pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`,										MAX(image_shop.`id_image`) id_image, DATEDIFF(p.`date_add`, DATE_SUB(NOW(),INTERVAL '.$interval.' DAY)) > 0 AS new				FROM  (`'._DB_PREFIX_.'product` p                 INNER JOIN `'._DB_PREFIX_.'specific_price` sp On p.id_product = sp.id_product)                  '.Shop::addSqlAssociation('product', 'p', false).'								LEFT JOIN `'._DB_PREFIX_.'product_lang` pl					ON p.`id_product` = pl.`id_product`					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)				LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (product_shop.`id_tax_rules_group` = tr.`id_tax_rules_group`)					AND tr.`id_country` = '.(int)Context::getContext()->country->id.'					AND tr.`id_state` = 0				LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)				'.Product::sqlStock('p').'				WHERE product_shop.`active` = 1                     AND sp.`id_shop` IN(0, '.(int)$context->shop->id.') 					AND sp.`id_currency` IN(0, '.(int)$context->currency->id.') 					AND sp.`id_country` IN(0, '.(int)$id_country.') 					AND sp.`id_group` IN(0, '.(int)$context->customer->id_default_group.') 					AND sp.`id_customer` IN(0) 					AND sp.`from_quantity` = 1 										AND (sp.`from` = \'0000-00-00 00:00:00\' OR \''.pSQL($currentDate).'\' >= sp.`from`)					AND (sp.`to` = \'0000-00-00 00:00:00\' OR \''.pSQL($currentDate).'\' <= sp.`to`)										AND sp.`reduction` > 0					AND p.`visibility` != \'none\' '.$where.' 									GROUP BY product_shop.id_product				ORDER BY sp.`reduction` DESC Limit '.$offset.', '.$limit;           $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);		               if (!$result) return false;            return $result;  
	}

}