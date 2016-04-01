<?php
/*
*  @author SonNC Ovic <nguyencaoson.zpt@gmail.com>
*/
require (dirname(__FILE__).'/VerticalMegaMenusLibraries.php');
class VerticalMegaMenus extends Module
{
    const INSTALL_SQL_FILE = 'install.sql';	
    public $arrType = array();
    public $arrGroupType = array();
    public $arrProductType = array();
    public $arrLayout = array();
    public $arrCol = array();
	public $imageHomeSize = array();
    public $pathTemp = '';
    public $pathBanner = '';
    public $pathIcon = '';
	protected static $productCache = array();
	protected static $arrPosition = array('displayVerticalMenu');
	protected $cache = null;
	public function __construct()
	{		
		$this->name = 'verticalmegamenus';
		$this->arrLayout = array('default'=>$this->l('Layout [default]'));
        $this->arrType = array('link'=>$this->l('Link'), 'image'=>$this->l('Image'), 'html'=>$this->l('Custom HTML'));
        $this->arrGroupType = array('link'=>$this->l('Link'), 'product'=>$this->l('Product'), 'custom'=>$this->l('Custom'));
        $this->arrProductType = array('saller'=>$this->l('Best Seller'), 'special'=>$this->l('Specials'), 'arrival'=>$this->l('New Arrivals'), 'manual'=>$this->l('Manual'));
        $this->arrCol = array('col-sm-1'=>$this->l('1 Column'),'col-sm-2'=>$this->l('2 Columns'),'col-sm-3'=>$this->l('3 Columns'),'col-sm-4'=>$this->l('4 Columns'),'col-sm-5'=>$this->l('5 Columns'),'col-sm-6'=>$this->l('6 Columns'),'col-sm-7'=>$this->l('7 Columns'),'col-sm-8'=>$this->l('8 Columns'),'col-sm-9'=>$this->l('9 Columns'),'col-sm-10'=>$this->l('10 Columns'),'col-sm-11'=>$this->l('11 Columns'),'col-sm-12'=>$this->l('12 Columns'));
		$this->secure_key = Tools::encrypt('ovic-soft'.$this->name);
        $this->pathTemp = dirname(__FILE__).'/images/temps/';
        $this->pathBanner = dirname(__FILE__).'/images/banners/';
        $this->pathIcon = dirname(__FILE__).'/images/icons/';                
        if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
			$this->livePath = _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/verticalmegamenus/images/'; 
		else
			$this->livePath = _PS_BASE_URL_.__PS_BASE_URI__.'modules/verticalmegamenus/images/'; 
		
		$this->tab = 'front_office_features';
		$this->version = '1.0';
		$this->author = 'OvicSoft [@]';		
		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->l('Supershop - Vertical Mega Menus Module');
		$this->description = $this->l('Vertical Mega Menus Module');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}
	/*
    public function  __call($method, $args){        
        if(!method_exists($this, $method)) {
            return $this->hooks($method, $args);
        }
    }
	*/
	public function install($keep = true)
	{
		if(!parent::install()) return false;
		if(!Configuration::updateGlobalValue('MOD_VERTICAL_MEGA_MENUS', '1')) return false;
		if(!$this->registerHook('displayHeader') || !$this->registerHook('displayVerticalMenu')) return false;
	   if ($keep){				
			if (!file_exists(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
				return false;
			else if (!$sql = file_get_contents(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
				return false;
			$sql = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
			$sql = preg_split("/;\s*[\r\n]+/", trim($sql));
			foreach ($sql as $query) 
				if (!DB::getInstance()->execute(trim($query))) return false;				
		}
		$this->moduleUpdatePosition();
		return true;
	}
	public function moduleUpdatePosition(){
		$items = DB::getInstance()->executeS("Select DISTINCT position_name From "._DB_PREFIX_."verticalmegamenus_modules  Where `position_name` <> ''");
		if($items){
			foreach ($items as $key => $item) {
				$position = Hook::getIdByName($item['position_name']);
				DB::getInstance()->execute("Update "._DB_PREFIX_."verticalmegamenus_modules Set position = '".$position."' Where `position_name` = '".$item['position_name']."'");
			}
		}
		return true;
	}
	public function uninstall($keep = true)
	{	   
		if (!parent::uninstall()) return false;
        if($keep){
			
            if(!Db::getInstance()->execute('
			DROP TABLE IF EXISTS 
			`'._DB_PREFIX_.'verticalmegamenus_groups`,
            `'._DB_PREFIX_.'verticalmegamenus_group_lang`,
            `'._DB_PREFIX_.'verticalmegamenus_menus`,
            `'._DB_PREFIX_.'verticalmegamenus_menu_items`,
            `'._DB_PREFIX_.'verticalmegamenus_menu_item_lang`,
            `'._DB_PREFIX_.'verticalmegamenus_menu_lang`,
            `'._DB_PREFIX_.'verticalmegamenus_modules`,
			`'._DB_PREFIX_.'verticalmegamenus_module_lang`')) return false;
			
        }		
        if (!Configuration::deleteByName('MOD_VERTICAL_MEGA_MENUS')) return false;
		return true;
	}
	public function reset()
	{
		if (!$this->uninstall(false))
			return false;
		if (!$this->install(false))
			return false;
        
		return true;
	}
    public function getIconSrc($image = '', $check = false){
        if($image && file_exists($this->pathIcon.$image))
            return $this->livePath.'icons/'.$image;
        else
            if($check == true) 
                return '';
            else
                return $this->livePath.'icons/default.jpg'; 
    }
    public function getBannerSrc($image = '', $check = false){
        if($image && file_exists($this->pathBanner.$image))
            return $this->livePath.'banners/'.$image;
        else
            if($check == true) 
                return '';
            else
                return $this->livePath.'banners/default.jpg'; 
    }
    public function getCategoryOptions($selected = 0, $parentId = 0){
        $langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;
        $categoryOptions = '';
        if($parentId <=0) $parentId = Configuration::get('PS_HOME_CATEGORY');
        $items = VerticalMegaMenusLibraries::getAllCategories($langId, $shopId, $parentId, '|- ', null);        
        if($items){
            foreach($items as $item){
                if($item['id_category'] == $selected) $categoryOptions .='<option selected="selected" value="'.$item['id_category'].'">'.$item['sp'].$item['name'].'</option>';
                else $categoryOptions .='<option value="'.$item['id_category'].'">'.$item['sp'].$item['name'].'</option>';
            }
        }
        return  $categoryOptions;
    }
    public function getStyleOptions($selected = 0){
        $langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;
        $styleOptions = '';
        $items = DB::getInstance()->executeS("Select id, name From "._DB_PREFIX_."groupcategory_styles");        
        if($items){
            foreach($items as $item){
                if($item['id'] == $selected) $styleOptions .='<option selected="selected" value="'.$item['id'].'">'.$item['name'].'</option>';
                else $styleOptions .='<option value="'.$item['id'].'">'.$item['name'].'</option>';
            }
        }
        return  $styleOptions;
    }
    public function getLangOptions(){
        $langId = Context::getContext()->language->id;
        $items = DB::getInstance()->executeS("Select id_lang, name, iso_code From "._DB_PREFIX_."lang Where active = 1");
        $langOptions = '';
        if($items){
            foreach($items as $item){
                if($item['id_lang'] == $langId){
                    $langOptions .= '<option value="'.$item['id_lang'].'" selected="selected">'.$item['iso_code'].'</option>';
                }else{
                    $langOptions .= '<option value="'.$item['id_lang'].'">'.$item['iso_code'].'</option>';
                }
            }
        }
        return $langOptions;
    }
    public function getPositionOptions($selected = 0){
    	$options = '';		
		if(self::$arrPosition){			
			foreach(self::$arrPosition as $value){
				$hookId = Hook::getIdByName($value);
				if($hookId == $selected) $options .='<option selected="selected" value="'.$hookId.'">'.$value.'</option>';
				else $options .='<option value="'.$hookId.'">'.$value.'</option>';
			}
		}		
        return $options;
		/*
        $positionOptions = '';
        $items = DB::getInstance()->executeS("Select id_hook From "._DB_PREFIX_."hook_module Where id_module = ".$this->id);
        if($items){
            foreach($items as $item){
                if($selected == $item['id_hook']) $positionOptions .= '<option selected="selected" value="'.$item['id_hook'].'">'.Hook::getNameById($item['id_hook']).'</option>';
                else $positionOptions .= '<option value="'.$item['id_hook'].'">'.Hook::getNameById($item['id_hook']).'</option>';
            }
        }
        return $positionOptions;
		*/  
    }
    public function getLayoutOptions($selected = ''){
        $options = '';        
        foreach($this->arrLayout as $key=> $value){
            if($key == $selected) $options .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
            else $options .= '<option  value="'.$key.'">'.$value.'</option>';            
        }        
        return $options; 
    }
    public function getTypeOptions($selected = ''){
        $options = '';        
        foreach($this->arrType as $key=> $value){
            if($key == $selected) $options .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
            else $options .= '<option  value="'.$key.'">'.$value.'</option>';            
        }        
        return $options; 
    }
    public function getGroupTypeOptions($selected = ''){
        $options = '';        
        foreach($this->arrGroupType as $key=> $value){
            if($key == $selected) $options .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
            else $options .= '<option  value="'.$key.'">'.$value.'</option>';            
        }        
        return $options; 
    }
    public function getProductTypeOptions($selected = ''){
        $options = '';
        foreach($this->arrProductType as $key=> $value){
            if($key == $selected) $options .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
            else $options .= '<option  value="'.$key.'">'.$value.'</option>';            
        }        
        return $options; 
    }
    public function getColumnOptions($selected = ''){
        $options = '';               
        foreach($this->arrCol as $key=> $value){
            if($key == $selected) $options .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
            else $options .= '<option  value="'.$key.'">'.$value.'</option>';            
        }        
        return $options; 
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

			return DB::getInstance()->executeS($sql);
		}else{
			$sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
				FROM `'._DB_PREFIX_.'cms_category` bcp
				INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
				ON (bcp.`id_cms_category` = cl.`id_cms_category`)
				WHERE cl.`id_lang` = '.(int)$id_lang.'
				AND bcp.`id_parent` = '.(int)$parent;

			$results = DB::getInstance()->executeS($sql);
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

		return DB::getInstance()->executeS($sql);
	} 
    private function getCMSOptions($parent = 0, $depth = 1, $id_lang = false, $selected='')
	{
		$html = '';
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;		
		$categories = $this->getCMSCategories(false, (int)$parent, (int)$id_lang);
        
		$pages = $this->getCMSPages((int)$parent, false, (int)$id_lang);

		$spacer = str_repeat('|- ', 1 * (int)$depth);

		foreach ($categories as $category)
		{
			//if (isset($items_to_skip) && !in_array('CMS_CAT'.$category['id_cms_category'], $items_to_skip))
			$key = 'CMS_CAT-'.$category['id_cms_category'];
            if($key == $selected)
                $html .= '<option selected="selected" value="'.$key.'" style="font-weight: bold;">'.$spacer.$category['name'].'</option>';
            else 
               $html .= '<option value="'.$key.'" style="font-weight: bold;">'.$spacer.$category['name'].'</option>';
                
			$html .= $this->getCMSOptions($category['id_cms_category'], (int)$depth + 1, (int)$id_lang, $selected);
		}

		foreach ($pages as $page){
            $key = 'CMS-'.$page['id_cms'];
            if($key == $selected)
			    $html .= '<option selected="selected" value="'.$key.'">'.$spacer.$page['meta_title'].'</option>';
            else 
                $html .= '<option value="'.$key.'">'.$spacer.$page['meta_title'].'</option>';
		}
			//if (isset($items_to_skip) && !in_array('CMS'.$page['id_cms'], $items_to_skip))
                

		return $html;
	}
    private function getPagesOption($id_lang = null, $selected = '')
    {
        if (is_null($id_lang)) $id_lang = (int)$this->context->cookie->id_lang;
        $files = Meta::getMetasByIdLang($id_lang);
        $html = '';
        foreach ($files as $file)
        {
            $key = 'PAG-'.$file['page'];
            if($key == $selected)
                $html .= '<option selected="selected" value="'.$key.'">' . (($file['title'] !='') ? $file['title'] : $file['page']) . '</option>';
            else
                $html .= '<option value="'.$key.'">' . (($file['title'] !='') ? $file['title'] : $file['page']) . '</option>';

        }
        return $html;
    }
    public function getCategoryLinkOptions($parentId = 0, $selected = ''){
        $langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;
        $categoryOptions = '';
        if($parentId <=0) $parentId = Configuration::get('PS_HOME_CATEGORY');
        $items = VerticalMegaMenusLibraries::getAllCategories($langId, $shopId, $parentId, '|- ', null);        
        if($items){
            foreach($items as $item){
                $key = 'CAT-'.$item['id_category'];                
                if($key == $selected) $categoryOptions .='<option selected="selected" value="'.$key.'">'.$item['sp'].$item['name'].'</option>';
                else $categoryOptions .='<option value="'.$key.'">'.$item['sp'].$item['name'].'</option>';
            }
        }
        return  $categoryOptions;
    }
	static function getCategoryNameById($id, $langId=0, $shopId=0){
		if(!$langId) $langId = Context::getContext()->language->id;
        if(!$shopId) $shopId = Context::getContext()->shop->id;
        $name =  DB::getInstance()->getValue("Select name From "._DB_PREFIX_."category_lang Where id_category = $id AND `id_shop` = '$shopId' AND `id_lang` = '$langId'");
        if($name) return $name;
        else return '';   
    }
    public function getAllLinkOptions($selected = '')
    {
    	$suppliers = Supplier::getSuppliers(false, false);
        $manufacturers = Manufacturer::getManufacturers(false, false);
        $allLink = '';
        if($selected == 'CUSTOMLINK-0')
            $allLink .= '<option selected="selected" value="CUSTOMLINK-0">'.$this->l('-- Custom Link --').'</option>';
        else
            $allLink .= '<option value="CUSTOMLINK-0">'.$this->l('-- Custom Link --').'</option>';
            
        $allLink .= '<optgroup label="' . $this->l('Category Link') . '">'.$this->getCategoryLinkOptions(0, $selected).'</optgroup>';
        $allLink .= '<optgroup label="' . $this->l('CMS Link') . '">'.$this->getCMSOptions(0, 1, false, $selected).'</optgroup>';        
        $allLink .= '<optgroup label="'.$this->l('Supplier Link').'">';
		if($selected == 'ALLSUP-0')
            $allLink .= '<option value="ALLSUP-0">'.$this->l('All suppliers').'</option>';
        else
            $allLink .= '<option value="ALLSUP-0">'.$this->l('All suppliers').'</option>';
            	
            foreach ($suppliers as $supplier){
                $key = 'SUP-'.$supplier['id_supplier'];
                if($key == $selected)
                    $allLink .= '<option selected="selected" value="'.$key.'">|- '.$supplier['name'].'</option>';  
                else 
                    $allLink .= '<option value="'.$key.'">|- '.$supplier['name'].'</option>';
            } 
		$allLink .= '</optgroup>';
        
        $allLink .= '<optgroup label="'.$this->l('Manufacturer Link').'">';
        if($selected == 'ALLMAN-0')
            $allLink .= '<option value="ALLMAN-0">'.$this->l('All manufacturers').'</option>';
        else 
            $allLink .= '<option value="ALLMAN-0">'.$this->l('All manufacturers').'</option>';
        foreach ($manufacturers as $manufacturer){
            $key = 'MAN-'.$manufacturer['id_manufacturer'];
            if($key == $selected)
                $allLink .= '<option selected="selected" value="'.$key.'">|- '.$manufacturer['name'].'</option>';
            else
                $allLink .= '<option value="'.$key.'">|- '.$manufacturer['name'].'</option>';
        }
		$allLink .= '</optgroup>';
        
        
        $allLink .= '<optgroup label="' . $this->l('Page Link') . '">'.$this->getPagesOption(false, $selected).'</optgroup>';
        if (Shop::isFeatureActive())
		{
			$allLink .= '<optgroup label="'.$this->l('Shops Link').'">';
			$shops = Shop::getShopsCollection();
			foreach ($shops as $shop)
			{
				if (!$shop->setUrl() && !$shop->getBaseURL()) continue;
                $key = 'SHO-'.$shop->id;
                if($key == $selected)
                    $allLink .= '<option selected="selected" value="SHOP-'.(int)$shop->id.'">'.$shop->name.'</option>';
                else
                    $allLink .= '<option value="SHOP-'.(int)$shop->id.'">'.$shop->name.'</option>';
			}	
			$allLink .= '</optgroup>';
		}
        $allLink .= '<optgroup label="'.$this->l('Product Link').'">';
        if($selected == 'PRODUCT-0')
            $allLink .= '<option selected value="PRODUCT-0" style="font-style:italic">'.$this->l('Choose product ID').'</option>';
        else
            $allLink .= '<option value="PRODUCT-0" style="font-style:italic">'.$this->l('Choose product ID').'</option>';
		$allLink .= '</optgroup>';
        return $allLink;
    }
	public function getAllLanguage(){
        $langId = Context::getContext()->language->id;
        $items = DB::getInstance()->executeS("Select id_lang, name, iso_code From "._DB_PREFIX_."lang Where active = 1 Order By id_lang");
        $languages = array();
        if($items){
            foreach($items as $i=>$item){
            	$objItem = new stdClass();
				$objItem->id = $item['id_lang'];
				$objItem->iso_code = $item['iso_code'];
                if($item['id_lang'] == $langId){
                    $objItem->active = 1;
                }else{
                    $objItem->active = 0;
                }
				$languages[$i] = $objItem;
            }
        }
        return $languages;
    }
    public function getAllMenu($moduleId=0){
        $html = '';
        if($moduleId >0){
            $items = DB::getInstance()->executeS("Select * From "._DB_PREFIX_."verticalmegamenus_menus Where moduleId = ".$moduleId." Order By ordering");
            if($items){
                foreach($items as $item){
                    $itemLang = $this->getMenuByLang($item['id']);
                    if($item['status'] == "1"){
                        $status = '<a title="Enabled" class="list-action-enable action-enabled lik-menu-status" item-id="'.$item['id'].'" value="'.$item['status'].'"><i class="icon-check"></i></a>';
                    }else{
                        $status = '<a title="Disabled" class="list-action-enable action-disabled lik-menu-status" item-id="'.$item['id'].'" value="'.$item['status'].'"><i class="icon-check"></i></a>';
                    }
                    $icon = $this->getIconSrc($item['icon'], true);
                    if($icon) $icon = '<img width="24" src="'.$icon.'" alt="'.$itemLang['title'].'" />';
                    
                    $html .= '<tr id="mn_'.$item['id'].'">
                                        <td class="center">'.$item['id'].'</td>
                                        <td class="center">'.$icon.'</td>                                        
                                        <td><a href="javascript:void(0)" item-id="'.$item['id'].'" title="'.$itemLang['title'].'" class="lik-menu">'.$itemLang['title'].'</a></td>                
                                        <td class="center">'.$this->arrCol[$item['width']].'</td>                    
                                        <td class="center">'.$this->arrType[$item['menuType']].'</td>
                                        <td>'.$item['link'].'</td>
                                        <td class="pointer dragHandle center" ><div class="dragGroup"><div class="positions">'.$item['ordering'].'</div></div></td>
                                        <td class="center">'.$status.'</td>
                                        <td class="center"><a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-menu-edit"><i class="icon-edit"></i></a>&nbsp;<a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-menu-delete"><i class="icon-trash" ></i></a></td>
                                    </tr>';
                }
            }   
        }
        return $html;
    }
    
    public function getAllMenuGroup($menuId){
        $html = '';
        if($menuId >0){
            $items = DB::getInstance()->executeS("Select * From "._DB_PREFIX_."verticalmegamenus_groups Where menuId = ".$menuId." Order By ordering");
            if($items){
                foreach($items as $item){                	
                    $itemLang = $this->getMenuGroupByLang($item['id']);
                    if($item['status'] == "1"){
                        $status = '<a title="Enabled" class="list-action-enable action-enabled lik-group-status" item-id="'.$item['id'].'" value="'.$item['status'].'"><i class="icon-check"></i></a>';
                    }else{
                        $status = '<a title="Disabled" class="list-action-enable action-disabled lik-group-status" item-id="'.$item['id'].'" value="'.$item['status'].'"><i class="icon-check"></i></a>';
                    }
                    $txtParam = '';
                    if($item['type'] == 'product'){                        
                        $params = json_decode($item['params']);
                        $txtParam = '- Category : '. $this->getCategoryNameById($params->productCategory);
                        if($params->productType == 'manual'){
                            $txtParam .= '<br /> - Type : '.$this->arrProductType[$params->productType];
                            $txtParam .= '<br /> - Ids : '.implode(', ', $params->productIds);
                            $txtParam .= '<br /> - Item width : '.$this->arrCol[$params->productWidth];                                
                        }else{
                            $txtParam .= '<br /> - Type : '.$this->arrProductType[$params->productType];
                            $txtParam .= '<br /> - Count : '.$params->productCount;
							$txtParam .= '<br /> - Item width : '.$this->arrCol[$params->productWidth];
                        }
                    }
                    $html .= '<tr id="gr_'.$item['id'].'"><td class="center">'.$item['id'].'</td><td><a href="javascript:void(0)" item-id="'.$item['id'].'" item-type="'.$item['type'].'" title="'.$itemLang['title'].'" class="lik-group">'.$itemLang['title'].'</a></td><td class="center">'.$this->arrCol[$item['width']].'</td><td class="center">'.$this->arrGroupType[$item['type']].'</td><td>'.$txtParam.'</td><td class="pointer dragHandle center" ><div class="dragGroup"><div class="positions">'.$item['ordering'].'</div></div></td><td class="center">'.$status.'</td><td class="center"><a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-group-edit"><i class="icon-edit"></i></a>&nbsp;<a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-group-delete"><i class="icon-trash" ></i></a></td></tr>';
                }
            }   
        }
        return $html;
    }

    public function getModuleByLang($id, $langId=0, $shopId=0){
    	if(!$langId) $langId = Context::getContext()->language->id;
        if(!$shopId) $shopId = Context::getContext()->shop->id;
		$itemLang = DB::getInstance()->getRow("Select name From "._DB_PREFIX_."verticalmegamenus_module_lang Where module_id = $id AND `id_lang` = '$langId' AND `id_shop` = '$shopId'" );
		if(!$itemLang) $itemLang = array('name'=>'');
		return $itemLang;
    }
	public function getMenuByLang($id, $langId=0, $shopId=0){
		if(!$langId) $langId = Context::getContext()->language->id;
        if(!$shopId) $shopId = Context::getContext()->shop->id;
		$itemLang = DB::getInstance()->getRow("Select title, image, image_alt, html From "._DB_PREFIX_."verticalmegamenus_menu_lang Where menu_id = $id AND `id_lang` = '$langId' AND `id_shop` = '$shopId'" );
		if(!$itemLang) $itemLang = array('title'=>'', 'image'=>'', 'image_alt'=>'', 'html'=>'');
		return $itemLang;
	}
	public function getMenuItemByLang($id, $langId=0, $shopId=0){
		if(!$langId) $langId = Context::getContext()->language->id;
        if(!$shopId) $shopId = Context::getContext()->shop->id;
		$itemLang = DB::getInstance()->getRow("Select title, image, imageAlt, html From "._DB_PREFIX_."verticalmegamenus_menu_item_lang Where menuId = $id AND `id_lang` = '$langId' AND `id_shop` = '$shopId'" );
		if(!$itemLang) $itemLang = array('title'=>'', 'image'=>'', 'imageAlt'=>'', 'html'=>'');
		return $itemLang;
	}
	
	function getMenuGroupByLang($id, $langId=0, $shopId=0){
		if(!$langId) $langId = Context::getContext()->language->id;
        if(!$shopId) $shopId = Context::getContext()->shop->id;
		$itemLang = DB::getInstance()->getRow("Select title From "._DB_PREFIX_."verticalmegamenus_group_lang Where group_id = $id AND `id_lang` = '$langId' AND `id_shop` = '$shopId'" );
		if(!$itemLang) $itemLang = array('title'=>'');
		return $itemLang;
	}
	public function ovicRenderModuleForm($id=0){
		$item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."verticalmegamenus_modules Where id = $id");
		if(!$item) $item = array('id'=>0, 'position'=>0, 'layout'=>'default', 'ordering'=>1, 'status'=>1, 'show_count_item'=>10);
		$langActive = '<input type="hidden" id="moduleLangActive" value="0" />';
		$inputName = '';
		$languages = $this->getAllLanguage();
		if($languages){
			foreach ($languages as $key => $lang) {				
				$itemLang = $this->getModuleByLang($id, $lang->id);
				if($lang->active == '1'){
					$langActive = '<input type="hidden" id="moduleLangActive" value="'.$lang->id.'" />';
					$inputName .= '<input type="text" value="'.$itemLang['name'].'" name="module_titles[]" id="module_titles_'.$lang->id.'" class="form-control module-lang-'.$lang->id.'" />';	
				}else{
					$inputName .= '<input type="text" value="'.$itemLang['name'].'" name="module_titles[]" id="module_titles_'.$lang->id.'" class="form-control module-lang-'.$lang->id.'" style="display:none" />';					
				}				
			}
		}
		$langOptions = $this->getLangOptions();
		$html = '<input type="hidden" name="moduleId" value="'.$item['id'].'" />';
		$html .= $langActive;
		$html .= '<input type="hidden" name="action" value="saveModule" />';
		$html .= '<input type="hidden" name="secure_key" value="'.$this->secure_key.'" />';
		$html .= '<div class="form-group"><label class="control-label col-sm-3">'.$this->l('Name').'</label><div class="col-sm-9"><div class="col-sm-10">'.$inputName.'</div><div class="col-sm-2"><select class="module-lang" onchange="moduleChangeLanguage(this.value)">'.$langOptions.'</select></div></div></div>';
		$html .= '<div class="form-group"><label class="control-label col-sm-3">'.$this->l('Position').'</label><div class="col-sm-9"><div class="col-sm-12"><select class="form-control" name="position">'.$this->getPositionOptions($item['position']).'</select></div></div></div>';
		$html .= '<div class="form-group"><label class="control-label col-sm-3">'.$this->l('Layout').'</label><div class="col-sm-9"><div class="col-sm-12"><select class="form-control" name="moduleLayout">'.$this->getLayoutOptions($item['layout']).'</select></div></div></div>';
		$html .= '<div class="form-group"><label class="control-label col-sm-3">'.$this->l('Show Item').'</label><div class="col-sm-9"><div class="col-sm-3"><input type="text" onkeypress="return handleEnterNumberInt(event)" name="showCount" value="'.$item['show_count_item'].'" class="form-control" /></div></div></div>';
		return $html;
	}
	public function ovicRenderMenuForm($id=0){		
		$item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."verticalmegamenus_menus Where id = $id");
		if(!$item) $item = array('id'=>0, 'moduleId'=>0, 'icon'=>'', 'menuType'=>'link', 'linkType'=>'', 'link'=>'', 'width'=>'col-sm-12', 'status'=>1, 'ordering'=>1, 'image'=>'');		
		$langActive = '<input type="hidden" id="menuLangActive" value="0" />';
		$languages = $this->getAllLanguage();
		$inputTitle = '';
		$inputImage = '';
		$inputImageAlt = '';
		$inputHtml = '';
		if($languages){
			foreach ($languages as $key => $language) {				
				$itemLang = $this->getMenuByLang($id, $language->id);
				if($language->active == '1'){
					$langActive = '<input type="hidden" id="menuLangActive" value="'.$language->id.'" />';
					$inputTitle .= '<input type="text" value="'.$itemLang['title'].'" name="titles[]"  class="form-control menu-lang-'.$language->id.'" />';
					$inputImage .= '<input type="text" value="'.$itemLang['image'].'" name="images[]" id="menuImage-'.$language->id.'" class="form-control menu-lang-'.$language->id.'"  />';
					$inputImageAlt .= '<input type="text" value="'.$itemLang['image_alt'].'" name="alts[]" class="form-control menu-lang-'.$language->id.'" />';
					$inputHtml .= '<div class="menu-lang-'.$language->id.' editor-container" data-editor-id="memu-custom-html-'.$language->id.'"><textarea class="editor" name="htmls[]" id="memu-custom-html-'.$language->id.'">'.$itemLang['html'].'</textarea></div>';
				}else{
					$inputTitle .= '<input type="text" value="'.$itemLang['title'].'" name="titles[]" class="form-control menu-lang-'.$language->id.'" style="display:none" />';
					$inputImage .= '<input type="text" value="'.$itemLang['image'].'" name="images[]" id="menuImage-'.$language->id.'" class="form-control menu-lang-'.$language->id.'" style="display:none"  />';
					$inputImageAlt .= '<input type="text" value="'.$itemLang['image_alt'].'" name="alts[]" class="form-control menu-lang-'.$language->id.'" style="display:none" />';
					$inputHtml .= '<div style="display:none" class="editor-container menu-lang-'.$language->id.'" data-editor-id="memu-custom-html-'.$language->id.'"><textarea class="editor" name="htmls[]" id="memu-custom-html-'.$language->id.'">'.$itemLang['html'].'</textarea></div>';					
				}				
			}
		}
		$langOptions = $this->getLangOptions();
		$html = '';
		$html .= '<input type="hidden" name="menuId" value="'.$item['id'].'" />';
		$html .= $langActive;
		$html .= '<input type="hidden" name="action" value="saveMenu" />';
		$html .= '<input type="hidden" name="secure_key" value="'.$this->secure_key.'" />';
		$html .= '<div class="form-group"><label class="control-label col-sm-3">'.$this->l('Title').'</label><div class="col-sm-9"><div class="col-sm-10">'.$inputTitle.'</div><div class="col-sm-2"><select class="menu-lang" onchange="menuChangeLanguage(this.value)">'.$langOptions.'</select></div></div></div>';
		$html .= '<div class="form-group clearfix"><label class="control-label col-sm-3">'.$this->l('Icon').'</label><div class="col-sm-9"><div class="col-sm-5"><div class="input-group"><input type="text" class="form-control" value="'.$item['icon'].'" name="icon" id="menu-icon" /><span class="input-group-btn"><button id="menu-icon-uploader" type="button" class="btn btn-default"><i class="icon-folder-open"></i></button></span></div></div><label class="control-label col-sm-2">'.$this->l('Width').'</label><div class="col-sm-5">                        <select name="width" class="form-control">'.$this->getColumnOptions($item['width']).'</select></div></div></div>';
		$html .= '<div class="form-group clearfix"><label class="control-label col-sm-3">'.$this->l('Select Link').'</label><div class="col-sm-9"><div class="col-sm-12"><select name="linkType" class="form-control" onchange="generationUrl(this.value, \'menu-link\')">'.$this->getAllLinkOptions($item['linkType']).'</select></div></div></div>';
		$html .= '<div class="form-group clearfix"><label class="control-label col-sm-3">'.$this->l('Url').'</label><div class="col-sm-9"><div class="col-sm-7"><input type="text" name="link" id="menu-link" value="'.$item['link'].'" class="form-control" /></div><label class="control-label col-sm-2 ">'.$this->l('Type').'</label><div class="col-sm-3"><select name="menuType" class="form-control" onchange="showContentByType(this.value)">'.$this->getTypeOptions($item['menuType']).'</select></div></div></div>';
		$html .= '<div class="type-image" style="display:'.($item['menuType'] == 'image' ? 'block' : 'none').'"><div class="form-group clearfix"><label class="control-label col-sm-3">'.$this->l('Image').'</label><div class="col-sm-9"><div class="col-sm-10"> <div class="input-group">'.$inputImage.'<span class="input-group-btn"><button id="menu-image-uploader" type="button" class="btn btn-default"><i class="icon-folder-open"></i></button></span></div></div> <div class="col-sm-2"><select class="menu-lang" onchange="menuChangeLanguage(this.value)">'.$langOptions.'</select></div></div></div><div class="form-group clearfix"><label class="control-label col-sm-3">'.$this->l('Image Alt').'</label><div class="col-sm-9"><div class="col-sm-10">'.$inputImageAlt.'</div><div class="col-sm-2"><select class="menu-lang" onchange="menuChangeLanguage(this.value)">'.$langOptions.'</select></div></div></div></div>';
		$html .= '<div class="type-html" style="display:'.($item['menuType'] == 'html' ? 'block' : 'none').'"><div class="form-group clearfix"><label class="control-label col-sm-3">'.$this->l('Custom HTML').'</label><div class="col-sm-9"><div class="col-sm-10">'.$inputHtml.'</div><div class="col-sm-2"><select class="menu-lang" onchange="menuChangeLanguage(this.value)">'.$langOptions.'</select></div></div></div></div>';
		return $html;
	}
	public function ovicRenderMenuGroupForm($id=0){		
		$item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."verticalmegamenus_groups Where id = $id");
		$params = new stdClass();
		if(!$item){
			$item = array('id'=>0, 'menuId'=>0, 'display_title'=>1, 'type'=>'link', 'params'=>'', 'width'=>'col-sm-12', 'status'=>1, 'ordering'=>1);			
			$params->productCategory = 0;
			$params->productType = 'auto';
			$params->productCount = 3;
			$params->productWidth = 'col-sm-4';
			$params->customWidth = 'col-sm-12';
			$params->productIds = array();
		}else{
			$params = json_decode($item['params']);
		}		
		$langActive = '<input type="hidden" id="menuGroupLangActive" value="0" />';
		$languages = $this->getAllLanguage();
		$inputTitle = '';
		if($languages){
			foreach ($languages as $key => $language) {				
				$itemLang = $this->getMenuGroupByLang($id, $language->id);
				if($language->active == '1'){
					$langActive = '<input type="hidden" id="menuGroupLangActive" value="'.$language->id.'" />';
					$inputTitle .= '<input type="text" value="'.$itemLang['title'].'" name="titles[]"  class="form-control menu-group-lang-'.$language->id.'" />';					
				}else{
					$inputTitle .= '<input type="text" value="'.$itemLang['title'].'" name="titles[]" class="form-control menu-group-lang-'.$language->id.'" style="display:none" />';					
				}				
			}
		}
		$langOptions = $this->getLangOptions();
		$html = '';
		$html .= '<input type="hidden" name="menuGroupId" value="'.$item['id'].'" />';
		$html .= $langActive;
		$html .= '<input type="hidden" name="action" value="saveGroup" />';
		$html .= '<input type="hidden" name="secure_key" value="'.$this->secure_key.'" />';
		$html .= '<div class="form-group"><label class="control-label col-sm-3 required">'.$this->l('Title').'</label><div class="col-sm-9"><div class="col-sm-10">'.$inputTitle.'</div><div class="col-sm-2"><select class="menu-group-lang" onchange="menuGroupChangeLanguage(this.value)">'.$langOptions.'</select></div></div></div>';
		if($item['display_title'] == 1){
			$html .= '<div class="form-group">
                    <label class="control-label col-sm-3">'.$this->l('Display Title').'</label>
                    <div class="col-sm-9">
                        <div class="col-sm-5">
                            <span class="switch prestashop-switch fixed-width-lg" id="group-display-title">
                                <input type="radio" value="1" class="group_display_title" checked="checked" id="group_display_title_on" name="group_display_title" />
            					<label for="group_display_title_on">Yes</label>
            				    <input type="radio" value="0" class="group_display_title" id="group_display_title_off" name="group_display_title" />
            					<label for="group_display_title_off">No</label>
                                <a class="slide-button btn"></a>
            				</span>
                        </div>                        
                    </div>				    
                </div>';	
		}else{
			$html .= '<div class="form-group">
                    <label class="control-label col-sm-3">'.$this->l('Display Title').'</label>
                    <div class="col-sm-9">
                        <div class="col-sm-5">
                            <span class="switch prestashop-switch fixed-width-lg" id="group-display-title">
                                <input type="radio" value="1" class="group_display_title" id="group_display_title_off" name="group_display_title" />
            					<label for="group_display_title_off">Yes</label>
            				    <input type="radio" value="0" class="group_display_title" checked="checked" id="group_display_title_on" name="group_display_title" />
            					<label for="group_display_title_on">No</label>
                                <a class="slide-button btn"></a>
            				</span>
                        </div>                        
                    </div>				    
                </div>';
		}
		$html .= '<div class="form-group clearfix">
                    <label class="control-label col-sm-3">'.$this->l('Group width').'</label>
                    <div class="col-sm-9">
                        <div class="col-sm-5">                        
                            <select name="width" id="group-width" class="form-control">'.$this->getColumnOptions($item['width']).'</select>                       
                        </div>
                        <label class="control-label col-sm-2">'.$this->l('Type').'</label>
                        <div class="col-sm-5">                        
                            <select name = "groupType" id="group-type" class="form-control" onchange="showGroupType(this.value)">'.$this->getGroupTypeOptions($item['type']).'</select>                        
                        </div>
                    </div>  
                </div>';
		$html .= '<div id="group-type-custom" style="display:'.($item['type'] == 'custom' ? 'block' : 'none').'">                    
                    <div class="form-group clearfix">
                        <label class="control-label col-sm-3">'.$this->l('Item width').'</label>
                        <div class="col-sm-9">
                            <div class="col-sm-12">                        
                                <select name="customItemWidth" id="custom-item-width" class="form-control">'.$this->getColumnOptions($params->customWidth).'</select>                       
                            </div>                        
                        </div>  
                    </div>
                </div>';
		$html .= '<div id="group-type-product" style="display:'.($item['type'] == 'product' ? 'block' : 'none').'">
                    <div class="form-group clearfix">
                        <label class="control-label col-sm-3">'.$this->l('Category').'</label>
                        <div class="col-sm-9">
                            <div class="col-sm-12">                        
                                <select name = "groupProductCategory" id="group-product-category" class="form-control">'.$this->getCategoryOptions($params->productCategory).'</select>                       
                            </div>                        
                        </div>  
                    </div>
                    <div class="form-group clearfix">
                        <label class="control-label col-sm-3">'.$this->l('Options').'</label>
                        <div class="col-sm-9">
                            <div class="col-sm-12">                        
                                <select name="groupProductType" id="group-product-type" class="form-control" onchange="showProductOption(this.value)">'.$this->getProductTypeOptions($params->productType).'</select>                       
                            </div>                        
                        </div>  
                    </div>
                    
                    <div class="form-group clearfix" id="group-product-type-auto" style="display:'.($params->productType != 'manual' ? 'block' : 'none').'">
                        <label class="control-label col-sm-3">'.$this->l('Count Item').'</label>
                        <div class="col-sm-9">
                            <div class="col-sm-3">                        
                                <input name="groupCountProduct" type="text" onkeypress="return handleEnterNumberInt(event)" id="group-count-product" value="'.$params->productCount.'" class="form-control" />                       
                            </div>                        
                        </div>  
                    </div>
                    <div class="form-group clearfix" id="group-product-type-manual" style="display:'.($params->productType == 'manual' ? 'block' : 'none').'">
                        <label class="control-label col-sm-3">'.$this->l('Product Ids').'</label>
                        <div class="col-sm-9">
                            <div class="col-sm-12">                        
                                <input type="text" name="groupProductIds" id="group-product-ids" value="'.($params->productIds ? implode(',', $params->productIds) : '').'" class="form-control" />                       
                            </div>                        
                        </div>  
                    </div>
                    <div class="form-group clearfix">
                        <label class="control-label col-sm-3">'.$this->l('Item width').'</label>
                        <div class="col-sm-9">
                            <div class="col-sm-12">                        
                                <select name="groupProductWidth" id="item-width" class="form-control">'.$this->getColumnOptions($params->productWidth).'</select>                       
                            </div>                        
                        </div>  
                    </div>
                </div>';
		return $html;
	}	
	public function ovicRenderMenuItemForm($id = 0){
		$item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."verticalmegamenus_menu_items Where id = $id");		
		if(!$item) $item = array('id'=>0, 'menuId'=>0, 'groupId'=>0, 'parentId'=>0, 'menuType'=>'link', 'linkType'=>'', 'link'=>'', 'status'=>1, 'ordering'=>1);		
		$langActive = '<input type="hidden" id="menuItemLangActive" value="0" />';
		$languages = $this->getAllLanguage();
		$inputTitle = '';
		$inputImage = '';
		$inputImageAlt = '';
		$inputHtml = '';
		if($languages){
			foreach ($languages as $key => $language) {				
				$itemLang = $this->getMenuItemByLang($id, $language->id);
				if($language->active == '1'){
					$langActive = '<input type="hidden" id="menuItemLangActive" value="'.$language->id.'" />';
					$inputTitle .= '<input type="text" value="'.$itemLang['title'].'" name="titles[]"  class="form-control menu-item-lang-'.$language->id.'" />';
					$inputImage .= '<input type="text" value="'.$itemLang['image'].'" name="images[]" id="menuItemImage-'.$language->id.'" class="form-control menu-item-lang-'.$language->id.'"  />';
					$inputImageAlt .= '<input type="text" value="'.$itemLang['imageAlt'].'" name="alts[]" class="form-control menu-item-lang-'.$language->id.'" />';
					$inputHtml .= '<div class="menu-item-lang-'.$language->id.'"><textarea class="editor" name="menuItemHtmls[]" id="memu-item-custom-html-'.$language->id.'">'.$itemLang['html'].'</textarea></div>';
				}else{
					$inputTitle .= '<input type="text" value="'.$itemLang['title'].'" name="titles[]"  class="form-control menu-item-lang-'.$language->id.'" style="display:none" />';
					$inputImage .= '<input type="text" value="'.$itemLang['image'].'" name="images[]" id="menuItemImage-'.$language->id.'" class="form-control menu-item-lang-'.$language->id.'"  style="display:none" />';
					$inputImageAlt .= '<input type="text" value="'.$itemLang['imageAlt'].'" name="alts[]" class="form-control menu-item-lang-'.$language->id.'" style="display:none" />';
					$inputHtml .= '<div style="display:none" class="menu-item-lang-'.$language->id.'"><textarea class="editor" name="menuItemHtmls[]" id="memu-item-custom-html-'.$language->id.'">'.$itemLang['html'].'</textarea></div>';					
				}				
			}
		}
		$langOptions = $this->getLangOptions();
		$html = '';
		$html .= '<input type="hidden" name="menuItemId" value="'.$item['id'].'" />';
		$html .= $langActive;
		$html .= '<input type="hidden" name="action" value="saveMenuItem" />';
		$html .= '<input type="hidden" name="secure_key" value="'.$this->secure_key.'" />';
		$html .= '<div class="form-group">
                    <label class="control-label col-sm-3 required">'.$this->l('Title').'</label>
				    <div class="col-sm-9">
                        <div class="col-sm-10">
                            '.$inputTitle.'
                        </div>
                        <div class="col-sm-2">
                            <select class="menu-item-lang" onchange="menuItemChangeLanguage(this.value)">'.$langOptions.'</select>
                        </div>                                                                        
                    </div>
                </div> ';
		
		$html .= '<div class="form-group clearfix">
                    <label class="control-label col-sm-3">'.$this->l('Select Link').'</label>
                    <div class="col-sm-9">
                        <div class="col-sm-12">                        
                            <select name="linkType" class="form-control" onchange="generationUrl(this.value, \'menu-item-link\')">'.$this->getAllLinkOptions($item['linkType']).'</select>                        
                        </div>                        
                    </div>  
                </div>';
		$html .= '<div class="form-group clearfix">
                    <label class="control-label col-sm-3 required">'.$this->l('Url').'</label>
                    <div class="col-sm-9">
                        <div class="col-sm-7">                        
                            <input name="link" type="text" id="menu-item-link" value="'.$item['link'].'" class="form-control" />                   
                        </div>
                        <label class="control-label col-sm-2 ">'.$this->l('Type').'</label>
                        <div class="col-sm-3">                        
                            <select name="menuType" id="menu-item-type" class="form-control" onchange="showItemContentByType(this.value)">'.$this->getTypeOptions($item['menuType']).'</select>                        
                        </div>

                    </div>  
                </div>';
		$html .= '<div class="item-type-image" style="display:'.($item['menuType'] == 'image' ? 'block' : 'none').'">
                    <div class="form-group clearfix">
                        <label class="control-label col-sm-3 required">'.$this->l('Image').'</label>
                        <div class="col-sm-9">
                            <div class="col-sm-10"> 
                                <div class="input-group">
                                    '.$inputImage.'
                                    <span class="input-group-btn">
                                        <button id="menu-item-image-uploader" type="button" class="btn btn-default"><i class="icon-folder-open"></i></button>
                                    </span>
                                </div>
                            </div> 
                            <div class="col-sm-2">
                                <select class="menu-item-lang" onchange="menuItemChangeLanguage(this.value)">'.$langOptions.'</select>
                            </div>             
                        </div>  
                    </div>
                    <div class="form-group clearfix">
                        <label class="control-label col-sm-3">'.$this->l('Image Alt').'</label>
                        <div class="col-sm-9">    
                            <div class="col-sm-10">
                                '.$inputImageAlt.'
                            </div>
                            <div class="col-sm-2">
                                <select class="menu-item-lang" onchange="menuItemChangeLanguage(this.value)">'.$langOptions.'</select>
                            </div>
                        </div>  
                    </div>
                </div>';
			$html .= '<div class="item-type-html" style="display:'.($item['menuType'] == 'html' ? 'block' : 'none').'">
                    <div class="form-group clearfix">
                        <label class="control-label col-sm-3 required">'.$this->l('Custom HTML').'</label>
                        <div class="col-sm-9">
                            <div class="col-sm-10">
                                '.$inputHtml.'
                            </div>
                            <div class="col-sm-2">
                                <select class="menu-item-lang" onchange="menuItemChangeLanguage(this.value)">'.$langOptions.'</select>
                            </div>
                        </div>  
                    </div>
                    
                </div>';			
		return $html;
	}
	public function getContent()
	{       
	   $langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;
	   $checkUpdate = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."verticalmegamenus_modules");
        if($checkUpdate){
            if(!isset($checkUpdate['id_shop'])){
                DB::getInstance()->execute("ALTER TABLE "._DB_PREFIX_."verticalmegamenus_modules ADD `id_shop` INT(6) unsigned NOT NULL AFTER `id`");
                DB::getInstance()->execute("Update "._DB_PREFIX_."verticalmegamenus_modules Set `id_shop` = ".$shopId);
            }
        } 
		$this->context->controller->addJS(($this->_path).'js/back-end/common.js');                
        $this->context->controller->addJS(($this->_path).'js/back-end/ajaxupload.3.5.js');
		$this->context->controller->addJS(($this->_path).'js/back-end/tinymce.inc.js');
		$this->context->controller->addJS(($this->_path).'js/back-end/jquery.serialize-object.min.js');		
		//Tools::addJS(_PS_BASE_URL_.__PS_BASE_URI__.'js/tinymce.inc.js');
		$this->context->controller->addJS(__PS_BASE_URI__.'js/jquery/plugins/jquery.tablednd.js');
        $this->context->controller->addJS(__PS_BASE_URI__.'js/jquery/plugins/jquery.colorpicker.js');        
        $this->context->controller->addJS(__PS_BASE_URI__.'js/tiny_mce/tinymce.min.js');
        //Tools::addJS(_PS_BASE_URL_.__PS_BASE_URI__.'js/tinymce.inc.js');        
        $this->context->controller->addCSS(($this->_path).'css/back-end/style.css');
        $this->context->controller->addCSS(($this->_path).'css/back-end/style-upload.css');
        
        $items = DB::getInstance()->executeS("Select m.*, ml.name From "._DB_PREFIX_."verticalmegamenus_modules AS m Left Join "._DB_PREFIX_."verticalmegamenus_module_lang AS ml On ml.module_id = m.id Where m.id_shop=".$shopId." AND ml.id_lang = ".$langId." Order By m.ordering");
        $listModule = '';
        $listLeftModule = '';
        if($items){
            foreach($items as $item){
                if($item['status'] == "1"){
                    $status = '<a title="Enabled" class="list-action-enable action-enabled lik-module-status" item-id="'.$item['id'].'" value="'.$item['status'].'"><i class="icon-check"></i></a>';
                }else{
                    $status = '<a title="Disabled" class="list-action-enable action-disabled lik-module-status" item-id="'.$item['id'].'" value="'.$item['status'].'"><i class="icon-check"></i></a>';
                }
                $listLeftModule .= '<a href="#item-module" class="list-group-item lik-module" item-id="'.$item['id'].'" title="'.$item['name'].'">&nbsp;-&nbsp;'.$item['name'].'</a>';
                $listModule .= '<tr id="mo_'.$item['id'].'">
                    <td class="center">'.$item['id'].'</td>
                    <td>'.$item['name'].'</td>                
                    <td class="center">'.Hook::getNameById($item['position']).'</td>                    
                    <td class="center">'.$this->arrLayout[$item['layout']].'</td>
                    <td class="pointer dragHandle center" ><div class="dragGroup"><div class="positions">'.$item['ordering'].'</div></div></td>
                    <td class="center">'.$status.'</td>
                    <td class="center"><a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-module-edit"><i class="icon-edit"></i></a>&nbsp;<a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-module-delete"><i class="icon-trash" ></i></a></td>
                </tr>'; 
            }
        }                 
        $this->context->smarty->assign(array(
            'baseModuleUrl'=> __PS_BASE_URI__.'modules/'.$this->name,
            'moduleId'=>$this->id,
            'langId'=>$langId,
            'ad'=>$ad = dirname($_SERVER["PHP_SELF"]),
            'layoutOptions'=>$this->getLayoutOptions(),
            'typeOptions'=>$this->getTypeOptions(),
            'positionOptions' => $this->getPositionOptions(),
            'categoryOptions'=>$this->getCategoryOptions(),            
            'langOptions'=>$this->getLangOptions(),
            'columnOptions'=>$this->getColumnOptions('col-sm-12'),            
            'list_module_tpl'=>dirname(__FILE__).'/views/templates/admin/list_module.tpl',
            'list_menu_tpl'=>dirname(__FILE__).'/views/templates/admin/list_menu.tpl',
            'listLeftModule'=>$listLeftModule,
            'listModule'=>$listModule,
            'allLink'=>$this->getAllLinkOptions(),
            'groupTypeOptions'=>$this->getGroupTypeOptions(),
            'productTypeOptions'=>$this->getProductTypeOptions(),
            'secure_key'=>$this->secure_key,
            'moduleForm' => $this->ovicRenderModuleForm(),
            'menuForm' =>$this->ovicRenderMenuForm(),
            'menuGroupForm' => $this->ovicRenderMenuGroupForm(),
            'menuItemForm' => $this->ovicRenderMenuItemForm()
                        
        ));
		return $this->display(__FILE__, 'views/templates/admin/modules.tpl');
	}
    
    public function hookdisplayHeader()
	{
        $this->arrLayout = array('default'=>$this->l('Layout [default]'));
        $this->arrType = array('link'=>$this->l('Link'), 'image'=>$this->l('Image'), 'html'=>$this->l('Custom HTML'));
        $this->arrGroupType = array('link'=>$this->l('Link'), 'product'=>$this->l('Product'), 'custom'=>$this->l('Custom'));
        $this->arrProductType = array('saller'=>$this->l('Best Seller'), 'special'=>$this->l('Specials'), 'arrival'=>$this->l('New Arrivals'), 'manual'=>$this->l('Manual'));
        $this->arrCol = array('col-sm-1'=>$this->l('1 Column'),'col-sm-2'=>$this->l('2 Columns'),'col-sm-3'=>$this->l('3 Columns'),'col-sm-4'=>$this->l('4 Columns'),'col-sm-5'=>$this->l('5 Columns'),'col-sm-6'=>$this->l('6 Columns'),'col-sm-7'=>$this->l('7 Columns'),'col-sm-8'=>$this->l('8 Columns'),'col-sm-9'=>$this->l('9 Columns'),'col-sm-10'=>$this->l('10 Columns'),'col-sm-11'=>$this->l('11 Columns'),'col-sm-12'=>$this->l('12 Columns'));
		
        // Call in global.css
		//$this->context->controller->addCSS(($this->_path).'css/front-end/style.css');
        $this->context->controller->addJS(($this->_path).'js/front-end/common.js');
        //$this->context->controller->addJS(($this->_path).'js/front-end/jquery.actual.min.js');
		$this->context->smarty->assign(array(            
            'verticalModuleUrl'=> __PS_BASE_URI__.'modules/'.$this->name,
			'HOOK_VerticalMenu' => Hook::exec('displayVerticalMenu')
        )); 		
	}
	public function generationUrl($value, $default="#"){
        $response = $default;
        if($value){
            $langId = Context::getContext()->language->id;
            $shopId = Context::getContext()->shop->id;
            $arr = explode('-', $value);            
            switch ($arr[0]){
                case 'PRD':
					$product = new Product((int)$arr[1], true, (int)$langId);
                    $response = Tools::HtmlEntitiesUTF8($product->getLink());                    
					break;
                case 'CAT':           
				    $response = Tools::HtmlEntitiesUTF8(Context::getContext()->link->getCategoryLink((int)$arr[1], null, $langId));
                    break;
                case 'CMS_CAT':                                                    
                    $response = Tools::HtmlEntitiesUTF8(Context::getContext()->link->getCMSCategoryLink((int)$arr[1], null, $langId));
                    break;    
                case 'CMS':                                
                    $response = Tools::HtmlEntitiesUTF8(Context::getContext()->link->getCMSLink((int)$arr[1], null, $langId));                
                    break;
                case 'ALLMAN':
                    $response = Tools::HtmlEntitiesUTF8(Context::getContext()->link->getPageLink('manufacturer'), true, $langId);					
					break;        
                case 'MAN':
                    $man = new Manufacturer((int)$arr[1], $langId);
                    $response = Tools::HtmlEntitiesUTF8(Context::getContext()->link->getManufacturerLink($man->id, $man->link_rewrite, $langId)); 
                    break;
                case 'ALLSUP':
					$response = Tools::HtmlEntitiesUTF8(Context::getContext()->link->getPageLink('supplier'), true, $langId);
					break;    
                case 'SUP':
                    $sup = new Supplier((int)$arr[1], $langId);    
                    $response = Tools::HtmlEntitiesUTF8(Context::getContext()->link->getSupplierLink($sup->id, $sup->link_rewrite, $langId));
                    break;
                case 'PAG':    
                    $pag = Meta::getMetaByPage($arr[1], $langId);                    
                    $response = Tools::HtmlEntitiesUTF8(Context::getContext()->link->getPageLink($pag['page'], true, $langId));
                    break;
                case 'SHO':
                    $shop = new Shop((int)$key);
                    $response = $shop->getBaseURL();    
                    break;    
                default: 
                    break;
            }  
   
        }
        return $response; 
    }
	public function hookdisplayVerticalMenu($params)
	{
		return $this->hooks('hookdisplayVerticalMenu', $params);		
	}
    public function hooks($hookName, $param){            
        $page_name = Dispatcher::getInstance()->getController();		
		$page_name = (preg_match('/^[0-9]/', $page_name) ? 'page_'.$page_name : $page_name);
        $this->context->smarty->assign('page_name', $page_name);
		
		$moduleLayout = 'verticalmegamenus.tpl';		
        $langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;        
        $hookName = str_replace('hook','', $hookName);        
        $hookId = Hook::getIdByName($hookName);
        $modules = array();
        $pageCache = Dispatcher::getInstance()->getController();		
		$cacheKey = 'module|'.$langId.'|'.$hookId.'|'.$pageCache;
        if (!$this->isCached($moduleLayout, $cacheKey)){
            $items = DB::getInstance()->executeS("Select DISTINCT m.*, ml.`name` From "._DB_PREFIX_."verticalmegamenus_modules AS m INNER JOIN "._DB_PREFIX_."verticalmegamenus_module_lang AS ml On m.id = ml.module_id Where m.status = 1 AND m.position = ".$hookId." AND m.id_shop=".$shopId." AND ml.id_lang = ".$langId." AND ml.id_shop = ".$shopId." Order By m.ordering");        
            if($items){            
                foreach($items as $i=>$item){                    			
                    $buildFunction = 'buildModule_'.$item['layout'];                     
                	$modules[$i]['title'] = $item['name'];
                	$modules[$i]['layout'] = $item['layout'];
                    $modules[$i]['sections'] = $this->buildModule_default($item, $langId, $shopId, 'menu-'.$item['id'].'|'.$cacheKey);
                }			  
            }else return "";   
            $this->context->smarty->assign('verticalModules', $modules); 
        }
        return $this->display(__FILE__, $moduleLayout, $cacheKey);    
    }
	function s_print($str){
		echo "<pre>";
		print_r($str);
		echo "</pre>";
		die;
	}
    function buildModule_default($module, $langId, $shopId, $cacheKey=''){    	
    	//if (!$this->isCached('menu.tpl', $cacheKey)){
    	   
        	$menus = DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS("Select DISTINCT m.*, ml.title, ml.image_alt, ml.html From "._DB_PREFIX_."verticalmegamenus_menus AS m Inner Join "._DB_PREFIX_."verticalmegamenus_menu_lang AS ml On m.id = ml.menu_id Where m.moduleId = '".$module['id']."' AND m.status = 1 AND ml.id_lang = ".$langId." AND ml.id_shop = ".$shopId." Order By m.ordering");
			
			
    		if($menus){
    			foreach ($menus as $i => &$menu) {
	              if($menu['menuType'] == 'link')
	                  if($menu['linkType'] && $menu['linkType'] != 'CUSTOMLINK-0' && $menu['linkType'] != 'PRODUCT-0')
	                      $menu['link'] = $this->generationUrl($menu['linkType']);
                        
    				$icon = $this->getIconSrc($menu['icon'], true);
    				$menu['iconPath'] = $icon;
    				$menu['group_content'] = $this->buildGroups_default($menu, $langId, $shopId, 'group-'.$menu['id'].'|'.$cacheKey);
    			}
    		}
    		$this->context->smarty->assign(array(
    			'verticalMenus'=>$menus,
    			'moduleName'=>$module['name'],
    			'moduleId'=>$module['id']
    		));   
        //}
		return $this->display(__FILE__, 'menu.tpl');		
    	//return $html;
    }
	function buildGroups_default($menu, $langId, $shopId, $cacheKey=''){    	
    	//if (!$this->isCached('group.tpl', $cacheKey)){
        	$groups = DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS("Select DISTINCT g.*, gl.title From "._DB_PREFIX_."verticalmegamenus_groups AS g Inner Join "._DB_PREFIX_."verticalmegamenus_group_lang AS gl On g.id = gl.group_id Where g.menuId = '".$menu['id']."' AND g.status = 1 AND gl.id_lang = ".$langId." AND gl.id_shop = ".$shopId." Order By g.ordering");		
    		if($groups){
    			foreach ($groups as $i => &$group) {				
    				if($group['type'] == 'custom') $group['group_content'] = $this->buildGroup_ItemCustom_default($group, $langId, $shopId, 'custom-'.$group['id'].'|'.$cacheKey);
    				elseif($group['type'] == 'link') $group['group_content'] = $this->buildGroup_ItemLink_default($group, $langId, $shopId, 'link-'.$group['id'].'|'.$cacheKey);
    				else $group['group_content'] = $this->buildGroup_Products_default($group, $langId, $shopId, 'product-'.$group['id'].'|'.$cacheKey);				
    			}
    		}else return "";		
    		$this->context->smarty->assign(array(
    			'verticalGroups'=>$groups,
    			'groupWidth'=>$menu['width']
    		));   
        //}
		return $this->display(__FILE__, 'group.tpl');
		//return $html;
    }
    function buildGroup_Products_default($group, $langId, $shopId, $cacheKey= ''){
        //return "";
		//if (!$this->isCached('group.product.tpl', $cacheKey)){
    		$params = json_decode($group['params']);
			$products = array();
    		//array('saller'=>$this->l('Best Saller'), 'view'=>$this->l('Most View'), 'special'=>$this->l('Specials'), 'arrival'=>$this->l('New Arrivals'), 'manual'=>$this->l('Manual'));		
    		if($params->productType == 'saller'){
    			//$arrSubCategory = VerticalMegaMenusLibraries::getCategoryIds($params->productCategory);
            	///$arrSubCategory[] = $params->productCategory;			
    			//$products =  $this->getProductsOrderSales($langId, $arrSubCategory, null, false, false, $params->productCount, 0, true);
				$products = $this->_getProducts_before($params->productCategory, 'all', 2, 2, 2, $langId, 0, $params->productCount, 'seller', 'desc');
				
    		}elseif($params->productType == 'special'){
    			//$arrSubCategory = VerticalMegaMenusLibraries::getCategoryIds($params->productCategory);
            	//$arrSubCategory[] = $params->productCategory;
    			//$products =  $this->getProductsOrderSpecial($langId, $arrSubCategory, null, false, false, $params->productCount, 0, true);
				$products = $this->_getProducts_before($params->productCategory, 'all', 2, 2, 2, $langId, 0, $params->productCount, 'discount', 'desc');
    		}elseif($params->productType == 'arrival'){
    			
    			//$arrSubCategory = VerticalMegaMenusLibraries::getCategoryIds($params->productCategory);
            	//$arrSubCategory[] = $params->productCategory;
    			//$products =  $this->getProductsOrderAddDate($langId, $arrSubCategory, null, false, false, $params->productCount, 0, true);
				$products = $this->_getProducts_before($params->productCategory, 'all', 2, 2, 2, $langId, 0, $params->productCount, 'date_add', 'desc');
    		}elseif($params->productType == 'manual'){
    			
    			if($params->productIds && count($params->productIds) >0){
					foreach($params->productIds as $productId){
						if($productId >0)
	                    	$products[] = $this->_getProductById_before($productId, $langId);		                    
	                } 
				}
    			//$products =  $this->getProductsByIds($langId, $params->productIds, false, true);
    		}
			
            if(!$products) return "";
            
    		$this->context->smarty->assign(
                array(
        			'verticalProducts'=>$products,
                    'productWidth'=>$params->productWidth, 
        			
                )
            ); 
        //}
		return $this->display(__FILE__, 'group.product.tpl');
    }
    function buildGroup_ItemLink_default($group, $langId, $shopId, $cacheKey=''){
    	//if (!$this->isCached('group.link.tpl', $cacheKey)){
    	   $params = json_decode($group['params']);    	
        	$menuItems = DB::getInstance()->executeS("Select DISTINCT m.*, ml.title, ml.imageAlt, ml.html From "._DB_PREFIX_."verticalmegamenus_menu_items AS m Inner Join "._DB_PREFIX_."verticalmegamenus_menu_item_lang AS ml On m.id = ml.menuId Where m.`groupId` = '".$group['id']."' AND m.status = 1 AND  ml.id_lang = ".$langId." AND ml.id_shop = ".$shopId." Order By m.ordering");
    		$html = '';
    		if($menuItems){
    			foreach ($menuItems as $i => &$menuItem) {				
    				if($menuItem['menuType'] == 'image'){
    					$image = $this->getBannerSrc($menuItem['image'], true);
    					if($image) $menuItem['imageSrc'] = $image;
    					else $menuItem['imageSrc'] = '';
    				}else {
    				    $menuItem['imageSrc'] = '';
    				    if($menuItem['menuType'] == 'link')
						if($menuItem['linkType'] && $menuItem['linkType'] != 'CUSTOMLINK-0' && $menuItem['linkType'] != 'PRODUCT-0')
    	                      $menuItem['link'] = $this->generationUrl($menuItem['linkType']);
    				}		
    			}
    		}else return "";
    		$this->context->smarty->assign(array(
    			'verticalLinks'=>$menuItems,
    			'verticalCustomWidth'=>$params->customWidth
    		));   
        //}
        
		return $this->display(__FILE__, 'group.link.tpl');
    }
	function buildGroup_ItemCustom_default($group, $langId, $shopId, $cacheKey=''){    	
    	//if (!$this->isCached('group.custom.tpl', $cacheKey)){
    	   $params = json_decode($group['params']);    	

        	$menuItems = DB::getInstance()->executeS("Select DISTINCT m.*, ml.title, ml.image, ml.imageAlt, ml.html From "._DB_PREFIX_."verticalmegamenus_menu_items AS m Inner Join "._DB_PREFIX_."verticalmegamenus_menu_item_lang AS ml On m.id = ml.menuId Where m.`groupId` = '".$group['id']."' AND m.status = 1 AND  ml.id_lang = ".$langId." Order By m.ordering");
			$html = '';
    		if($menuItems){
    			$html .= '<div class="mega-custom-html">';
    			foreach ($menuItems as $i => &$menuItem) {
    				
    				if($menuItem['menuType'] == 'link'){
    					$html .= '<div class="item item-link '.$params->customWidth.'"><div class="row"><a href="'.$menuItem['link'].'">'.$menuItem['title'].'</a></div></div>';	
    				}elseif($menuItem['menuType'] == 'image'){
    					$image = $this->getBannerSrc($menuItem['image'], true);					
    					if($image) $menuItem['imageSrc'] = $image;	
    					else $menuItem['imageSrc'] = '';
    				}else $menuItem['imageSrc'] = '';			
    			}			
    		}else return "";
    		$this->context->smarty->assign(array(
    				'verticalCustoms'=>$menuItems,
    				'verticalCustomWidth'=>$params->customWidth
    			));  
        //}
        
		return $this->display(__FILE__, 'group.custom.tpl');
    }
    
    function ajaxTranslate($text=''){
        return $this->l($text);
    }
    function clearCache()
	{
	   Tools::clearCache();
       /*
	   $items = DB::getInstance()->executeS("Select * From "._DB_PREFIX_."verticalmegamenus_modules");
       if($items){
            foreach($items as $item){
                $this->_clearCache('verticalmegamenus.tpl', $item['position']);
                $this->_clearCache('menu.tpl', 'menu|'.$item['id']);
                $menus = DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS("Select * From "._DB_PREFIX_."verticalmegamenus_menus Where moduleId = '".$item['id']."'");
                if($menus){
                    foreach($menus as $menu){
                        $this->_clearCache('group.tpl', 'group|'.$menu['id']);
                        $groups = DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS("Select * From "._DB_PREFIX_."verticalmegamenus_groups  Where menuId = '".$menu['id']."'");
                        if($groups){
                            foreach($groups as $group){                                
                                $this->_clearCache('group.product.tpl', 'product|'.$group['id']);
                                $this->_clearCache('group.link.tpl', 'link|'.$group['id']);
                                $this->_clearCache('group.custom.tpl', 'custom|'.$group['id']);
                            }
                        }                 
                    }
                }
            }
                
       }      
       */ 
       return true;
		//$this->_clearCache('verticalmegamenus.tpl');
	}
    
    
	
	protected function _getProductById_before($productId = 0, $id_lang = null, $id_shop = null){		
		if(!$productId) return array();		
		if(!$id_lang) $id_lang = $this->context->language->id;
		if(!$id_shop) $id_shop = $this->context->shop->id;
		
		$product_cache_key = 'product_'.$productId.'_'.$id_lang.'_'.$id_shop;
		if(_PS_CACHE_ENABLED_){
			if($this->cache->exists($product_cache_key)){
				$product =  $this->cache->get($product_cache_key);						
				if($product){
					return $product;					
				}
			}		
		}

		//$context = Context::getContext();
		$sql = 'SELECT 
							p.*, 
							product_shop.*, 
							stock.out_of_stock, 
							IFNULL(stock.quantity, 0) as quantity, 
							MAX(product_attribute_shop.id_product_attribute) id_product_attribute, 
							product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, 
							pl.`description`, 
							pl.`description_short`, 
							pl.`available_now`, 
							pl.`available_later`, 
							pl.`link_rewrite`, 
							pl.`meta_description`, 
							pl.`meta_keywords`, 
							pl.`meta_title`, 
							pl.`name`, 
							MAX(image_shop.`id_image`) id_image, 
							il.`legend`, 
							m.`name` AS manufacturer_name, 
							cl.`name` AS category_default, 
							DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0 AS new, 
							product_shop.price AS orderprice 
						FROM `'._DB_PREFIX_.'category_product` cp 
							LEFT JOIN (`'._DB_PREFIX_.'product` p LEFT JOIN `'._DB_PREFIX_.'product_sale` ps ON ps.`id_product` = p.`id_product`) 
								ON (p.`id_product` = cp.`id_product`)'.
							Shop::addSqlAssociation('product', 'p').' '.
							'LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa 
								ON (p.`id_product` = pa.`id_product`)'.
							Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').' '.
							Product::sqlStock('p', 0).' 
							LEFT JOIN `'._DB_PREFIX_.'category_lang` cl 
								ON (product_shop.`id_category_default` = cl.`id_category` AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').') 
							LEFT JOIN `'._DB_PREFIX_.'product_lang` pl 
								ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').') 
							LEFT JOIN `'._DB_PREFIX_.'image` i
								ON (i.`id_product` = p.`id_product`)'.
							Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
							LEFT JOIN `'._DB_PREFIX_.'image_lang` il 
								ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.') 
							LEFT JOIN `'._DB_PREFIX_.'manufacturer` m 
								ON m.`id_manufacturer` = p.`id_manufacturer` 
						WHERE  
							product_shop.`id_shop` = '.(int)$id_shop.' 
							AND  product_shop.`id_product` = '.(int)$productId .' 
							AND  product_shop.`active` = 1 
							AND  product_shop.`visibility` IN ("both", "catalog")';
		
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
		
		if (!$result) return array();		
		$result = Product::getProductProperties($id_lang, $result);
		if(_PS_CACHE_ENABLED_)
			$this->cache->set($product_cache_key, $result);
		return $result;
	}
    protected function _getProducts_before($categoryId = 0, $on_condition='all', $on_sale=2, $on_new=2, $on_discount=2, $id_lang=null, $p, $n, $order_by = null, $order_way = null, $beginning=null, $ending=null, $deal=false, $get_total = false, $active = true, $random = false, $random_number_products = 1, Context $context = null){
		if(!$categoryId) return array();
		$shopId = $this->context->shop->id;		
		if(!$id_lang) $id_lang = $this->context->language->id;
		Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ? $PS_NB_DAYS_NEW_PRODUCT = (int)Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : $PS_NB_DAYS_NEW_PRODUCT = 20;
		$where = "";
		if($on_condition != 'all'){
             $where .= " AND p.`condition` = '".$on_condition."' ";                
        }
		if($on_sale != 2){
			$where .= " AND p.`on_sale` = '".$on_sale."' ";
		}        
		if($on_new == 0){
			$where .= " AND pps.`date_add` <= '".date('Y-m-d', strtotime('-'.$PS_NB_DAYS_NEW_PRODUCT.' DAY'))."' ";
		}elseif($on_new == 1){
			$where .= " AND pps.`date_add` > '".date('Y-m-d', strtotime('-'.$PS_NB_DAYS_NEW_PRODUCT.' DAY'))."' ";
		}
		
		
		if($order_by == 'seller'){
			if($on_discount != 2){
				$sql = "Select 
							p.`id_product`, 
							ps.`quantity`, 
							pps.`active`  
						From 
							`"._DB_PREFIX_."product` as p 
							Inner Join `"._DB_PREFIX_."category_product` as cp 
								On (p.`id_product` = cp.`id_product` AND cp.`id_category`='".$categoryId."') 
							Inner Join `"._DB_PREFIX_."product_shop` as pps  
								On (p.`id_product` = pps.`id_product` AND pps.`id_shop` = '".$shopId."') 
							Inner Join `"._DB_PREFIX_."specific_price` as sp  
								On (
										p.`id_product` = sp.`id_product` 
										AND sp.`id_shop` IN (0, ".$shopId.") 
										AND sp.`id_currency` IN (0, ".(int)$this->context->currency->id.") 
										AND sp.`id_group` IN (0, ".(int)$this->context->customer->id_default_group.") 
										AND sp.`from_quantity` = 1 
										AND sp.`reduction` > 0
									) 
							Inner Join `"._DB_PREFIX_."product_sale` as ps 
								On (p.`id_product` = ps.`id_product`) 
							Where 
								pps.`active` = 1 ".$where." 
							GROUP BY  
								p.id_product 
							ORDER BY 
								ps.`quantity` ".$order_way." 
							Limit 0, ".$n;							
			}else{
				$sql = "Select 
							p.`id_product`, 
							ps.`quantity`, 
							pps.`active`  
						From 
							`"._DB_PREFIX_."product` as p 
							Inner Join `"._DB_PREFIX_."category_product` as cp 
								On (p.`id_product` = cp.`id_product` AND cp.`id_category`='".$categoryId."') 
							Inner Join `"._DB_PREFIX_."product_shop` as pps  
								On (p.`id_product` = pps.`id_product` AND pps.`id_shop` = '".$shopId."') 							
							Inner Join `"._DB_PREFIX_."product_sale` as ps 
								On (p.`id_product` = ps.`id_product`) 
							Where 
								pps.`active` = 1 ".$where." 
							GROUP BY  
								p.id_product 
							ORDER BY 
								ps.`quantity` ".$order_way." 
							Limit 0, ".$n;
			}
		}elseif($order_by == 'price'){
			if($on_discount != 2){
				$sql = "Select 
							p.`id_product`, 
							pps.`price`,  
							pps.`active` 
						From 
							`"._DB_PREFIX_."product` as p 
							Inner Join `"._DB_PREFIX_."category_product` as cp 
								On (p.`id_product` = cp.`id_product` AND cp.`id_category`='".$categoryId."') 
							Inner Join `"._DB_PREFIX_."product_shop` as pps  
								On (p.`id_product` = pps.`id_product` AND pps.`id_shop` = '".$shopId."') 
							Inner Join `"._DB_PREFIX_."specific_price` as sp  
								On (
										p.`id_product` = sp.`id_product` 
										AND sp.`id_shop` IN (0, ".$shopId.") 
										AND sp.`id_currency` IN (0, ".(int)$this->context->currency->id.") 
										AND sp.`id_group` IN (0, ".(int)$this->context->customer->id_default_group.") 
										AND sp.`from_quantity` = 1 
										AND sp.`reduction` > 0
									) 
							Where 
								pps.`active` = 1 ".$where." 
							GROUP BY  
								p.id_product 
							ORDER BY 
								pps.`price` ".$order_way." 
							Limit 0, ".$n;							
			}else{
				$sql = "Select 
							p.`id_product`, 
							pps.`price`,  
							pps.`active` 
						From 
							`"._DB_PREFIX_."product` as p 
							Inner Join `"._DB_PREFIX_."category_product` as cp 
								On (p.`id_product` = cp.`id_product` AND cp.`id_category`='".$categoryId."') 
							Inner Join `"._DB_PREFIX_."product_shop` as pps  
								On (p.`id_product` = pps.`id_product` AND pps.`id_shop` = '".$shopId."') 
							Where 
								pps.`active` = 1 ".$where." 
							GROUP BY  
								p.id_product 
							ORDER BY 
								pps.`price` ".$order_way." 
							Limit 0, ".$n;
			}
		}elseif($order_by == 'discount'){
			if($on_discount != 2){
				$sql = "Select 
							p.`id_product`, 
							sp.`reduction`, 
							pps.`active` 
						From 
							`"._DB_PREFIX_."product` as p 
							Inner Join `"._DB_PREFIX_."category_product` as cp 
								On (p.`id_product` = cp.`id_product` AND cp.`id_category`='".$categoryId."') 
							Inner Join `"._DB_PREFIX_."product_shop` as pps  
								On (p.`id_product` = pps.`id_product` AND pps.`id_shop` = '".$shopId."') 
							Inner Join `"._DB_PREFIX_."specific_price` as sp  
								On (
										p.`id_product` = sp.`id_product` 
										AND sp.`id_shop` IN (0, ".$shopId.") 
										AND sp.`id_currency` IN (0, ".(int)$this->context->currency->id.") 
										AND sp.`id_group` IN (0, ".(int)$this->context->customer->id_default_group.") 
										AND sp.`from_quantity` = 1 
										AND sp.`reduction` > 0
									) 
							Where 
								pps.`active` = 1 ".$where." 
							GROUP BY  
								p.id_product 
							ORDER BY 
								sp.`reduction` ".$order_way." 
							Limit 0, ".$n;	
			}else{
				$sql = "Select 
							p.`id_product`, 
							sp.`reduction`, 
							pps.`active` 
						From 
							`"._DB_PREFIX_."product` as p 
							Inner Join `"._DB_PREFIX_."category_product` as cp 
								On (p.`id_product` = cp.`id_product` AND cp.`id_category`='".$categoryId."') 
							Inner Join `"._DB_PREFIX_."product_shop` as pps  
								On (p.`id_product` = pps.`id_product` AND pps.`id_shop` = '".$shopId."') 
							Inner Join `"._DB_PREFIX_."specific_price` as sp  
								On (
										p.`id_product` = sp.`id_product` 
										AND sp.`id_shop` IN (0, ".$shopId.") 
										AND sp.`id_currency` IN (0, ".(int)$this->context->currency->id.") 
									) 
							Where 
								pps.`active` = 1 ".$where." 
							GROUP BY  
								p.id_product 
							ORDER BY 
								sp.`reduction` ".$order_way." 
							Limit 0, ".$n;
			}
		}elseif($order_by == 'date_add'){
			if($on_discount != 2){
				$sql = "Select 
							p.`id_product`, 
							pps.`date_add`, 
							pps.`active` 
						From 
							`"._DB_PREFIX_."product` as p 
							Inner Join `"._DB_PREFIX_."category_product` as cp 
								On (p.`id_product` = cp.`id_product` AND cp.`id_category`='".$categoryId."') 
							Inner Join `"._DB_PREFIX_."product_shop` as pps  
								On (p.`id_product` = pps.`id_product` AND pps.`id_shop` = '".$shopId."') 
							Inner Join `"._DB_PREFIX_."specific_price` as sp  
								On (
										p.`id_product` = sp.`id_product` 
										AND sp.`id_shop` IN (0, ".$shopId.") 
										AND sp.`id_currency` IN (0, ".(int)$this->context->currency->id.") 
										AND sp.`id_group` IN (0, ".(int)$this->context->customer->id_default_group.") 
										AND sp.`from_quantity` = 1 
										AND sp.`reduction` > 0
									) 
							Where 
								pps.`active` = 1 ".$where." 
							GROUP BY  
								p.id_product 
							ORDER BY 
								pps.`date_add` ".$order_way." 
							Limit 0, ".$n;							
			}else{
				$sql = "Select 
							p.`id_product`, 
							pps.`date_add`, 
							pps.`active`  
						From 
							`"._DB_PREFIX_."product` as p 
							Inner Join `"._DB_PREFIX_."category_product` as cp 
								On (p.`id_product` = cp.`id_product` AND cp.`id_category`='".$categoryId."') 
							Inner Join `"._DB_PREFIX_."product_shop` as pps  
								On (p.`id_product` = pps.`id_product` AND pps.`id_shop` = '".$shopId."') 
							Where 
								pps.`active` = 1 ".$where." 
							GROUP BY  
								p.id_product 
							ORDER BY 
								pps.`date_add` ".$order_way." 
							Limit 0, ".$n;
			}
		}elseif($order_by == 'position'){
			if($on_discount != 2){
				$sql = "Select 
							p.`id_product`, 
							cp.`position`, 
							pps.`active` 
						From 
							`"._DB_PREFIX_."product` as p 
							Inner Join `"._DB_PREFIX_."category_product` as cp 
								On (p.`id_product` = cp.`id_product` AND cp.`id_category`='".$categoryId."') 
							Inner Join `"._DB_PREFIX_."product_shop` as pps  
								On (p.`id_product` = pps.`id_product` AND pps.`id_shop` = '".$shopId."') 
							Inner Join `"._DB_PREFIX_."specific_price` as sp  
								On (
										p.`id_product` = sp.`id_product` 
										AND sp.`id_shop` IN (0, ".$shopId.") 
										AND sp.`id_currency` IN (0, ".(int)$this->context->currency->id.") 
										AND sp.`id_group` IN (0, ".(int)$this->context->customer->id_default_group.") 
										AND sp.`from_quantity` = 1 
										AND sp.`reduction` > 0
									) 
							Where 
								pps.`active` = 1 ".$where." 
							GROUP BY  
								p.id_product 
							ORDER BY 
								cp.`position` ".$order_way." 
							Limit 0, ".$n;
			}else{
				$sql = "Select 
							p.`id_product`, 
							cp.`position`, 
							pps.`active`  
						From 
							`"._DB_PREFIX_."product` as p 
							Inner Join `"._DB_PREFIX_."category_product` as cp 
								On (p.`id_product` = cp.`id_product` AND cp.`id_category`='".$categoryId."') 
							Inner Join `"._DB_PREFIX_."product_shop` as pps  
								On (p.`id_product` = pps.`id_product` AND pps.`id_shop` = '".$shopId."') 
							Where 
								pps.`active` = 1 ".$where." 
							GROUP BY  
								p.id_product 
							ORDER BY 
								cp.`position` ".$order_way." 
							Limit 0, ".$n;
			}
		}elseif($order_by == 'review'){
			if(!Module::isInstalled('productcomments')) return array();
			if($on_discount != 2){
				$sql = "Select 
							p.`id_product`, 
							pps.`active`, 
							COUNT(pc.`id_product`) as total_review 
						From 
							`"._DB_PREFIX_."product` as p 
							Inner Join `"._DB_PREFIX_."category_product` as cp 
								On (p.`id_product` = cp.`id_product` AND cp.`id_category`='".$categoryId."') 
							Inner Join `"._DB_PREFIX_."product_shop` as pps  
								On (p.`id_product` = pps.`id_product` AND pps.`id_shop` = '".$shopId."') 
							Inner Join `"._DB_PREFIX_."specific_price` as sp  
								On (
										p.`id_product` = sp.`id_product` 
										AND sp.`id_shop` IN (0, ".$shopId.") 
										AND sp.`id_currency` IN (0, ".(int)$this->context->currency->id.") 
										AND sp.`id_group` IN (0, ".(int)$this->context->customer->id_default_group.") 
										AND sp.`from_quantity` = 1 
										AND sp.`reduction` > 0
									) 
							Left Join "._DB_PREFIX_."product_comment as pc 
								On (pc.id_product = p.id_product) 
							Where 
								pps.`active` = 1 ".$where." 
							GROUP BY  
								p.id_product 
							ORDER BY 
								total_review ".$order_way." 
							Limit 0, ".$n;
			}else{
				$sql = "Select 
							p.`id_product`,  
							pps.`active`, 
							COUNT(pc.`id_product`) as total_review  
						From 
							`"._DB_PREFIX_."product` as p 
							Inner Join `"._DB_PREFIX_."category_product` as cp 
								On (p.`id_product` = cp.`id_product` AND cp.`id_category`='".$categoryId."') 
							Inner Join `"._DB_PREFIX_."product_shop` as pps  
								On (p.`id_product` = pps.`id_product` AND pps.`id_shop` = '".$shopId."') 							
							Inner Join `"._DB_PREFIX_."product_sale` as ps 
								On (p.`id_product` = ps.`id_product`) 
							Left Join "._DB_PREFIX_."product_comment as pc 
								On (pc.id_product = p.id_product) 
							Where 
								pps.`active` = 1 ".$where." 
							GROUP BY  
								p.id_product 
							ORDER BY 
								total_review ".$order_way." 
							Limit 0, ".$n;
			}
		}elseif($order_by == 'rate'){
			if(!Module::isInstalled('productcomments')) return array();
			if($on_discount != 2){
				$sql = "Select 
							p.`id_product`, 
							pps.`active`, 
							(SUM(pc.`grade`) / COUNT(pc.`grade`)) AS total_avg
						From 
							`"._DB_PREFIX_."product` as p 
							Inner Join `"._DB_PREFIX_."category_product` as cp 
								On (p.`id_product` = cp.`id_product` AND cp.`id_category`='".$categoryId."') 
							Inner Join `"._DB_PREFIX_."product_shop` as pps  
								On (p.`id_product` = pps.`id_product` AND pps.`id_shop` = '".$shopId."') 
							Inner Join `"._DB_PREFIX_."specific_price` as sp  
								On (
										p.`id_product` = sp.`id_product` 
										AND sp.`id_shop` IN (0, ".$shopId.") 
										AND sp.`id_currency` IN (0, ".(int)$this->context->currency->id.") 
										AND sp.`id_group` IN (0, ".(int)$this->context->customer->id_default_group.") 
										AND sp.`from_quantity` = 1 
										AND sp.`reduction` > 0
									) 
							Left Join "._DB_PREFIX_."product_comment as pc 
								On (pc.id_product = p.id_product) 
							Where 
								pps.`active` = 1 ".$where." 
							GROUP BY  
								p.id_product 
							ORDER BY 
								total_avg ".$order_way." 
							Limit 0, ".$n;
			}else{
				$sql = "Select 
							p.`id_product`, 
							pps.`active`,
							(SUM(pc.`grade`) / COUNT(pc.`grade`)) AS total_avg  
						From 
							`"._DB_PREFIX_."product` as p 
							Inner Join `"._DB_PREFIX_."category_product` as cp 
								On (p.`id_product` = cp.`id_product` AND cp.`id_category`='".$categoryId."') 
							Inner Join `"._DB_PREFIX_."product_shop` as pps  
								On (p.`id_product` = pps.`id_product` AND pps.`id_shop` = '".$shopId."') 							
							Inner Join `"._DB_PREFIX_."product_sale` as ps 
								On (p.`id_product` = ps.`id_product`) 
							Left Join "._DB_PREFIX_."product_comment as pc 
								On (pc.id_product = p.id_product) 
							Where 
								pps.`active` = 1 ".$where." 
							GROUP BY  
								p.id_product 
							ORDER BY 
								total_avg ".$order_way." 
							Limit 0, ".$n;
			}
		}elseif($order_by == 'view'){
			if($on_discount != 2){
				$sql = "Select 
							p.`id_product`, 
							pps.`active`, 
							pv.`counter` 
						From 
							`"._DB_PREFIX_."product` as p 
							Inner Join `"._DB_PREFIX_."category_product` as cp 
								On (p.`id_product` = cp.`id_product` AND cp.`id_category`='".$categoryId."') 
							Inner Join `"._DB_PREFIX_."product_shop` as pps  
								On (p.`id_product` = pps.`id_product` AND pps.`id_shop` = '".$shopId."') 
							Inner Join `"._DB_PREFIX_."specific_price` as sp  
								On (
										p.`id_product` = sp.`id_product` 
										AND sp.`id_shop` IN (0, ".$shopId.") 
										AND sp.`id_currency` IN (0, ".(int)$this->context->currency->id.") 
										AND sp.`id_group` IN (0, ".(int)$this->context->customer->id_default_group.") 
										AND sp.`from_quantity` = 1 
										AND sp.`reduction` > 0
									) 
							LEFT JOIN (`"._DB_PREFIX_."page` pg 
								LEFT JOIN `"._DB_PREFIX_."page_viewed` pv 
									ON pv.`id_page` = pg.`id_page` 
								LEFT JOIN `"._DB_PREFIX_."date_range` dr 
									ON pv.`id_date_range` = dr.`id_date_range` 
								LEFT JOIN `"._DB_PREFIX_."page_type` pt 
									ON (
											pt.`id_page_type` = pg.`id_page_type` 
											AND dr.`time_start` > '".date('Y-m-d', strtotime('-'.$PS_NB_DAYS_NEW_PRODUCT.' DAY'))."' 
											AND pt.`name` = 'product'
										) 
								) 
								ON pg.`id_object` = p.`id_product`  
							Where 
								pps.`active` = 1  ".$where." 
							GROUP BY  
								p.id_product 
							ORDER BY 
								pv.`counter` ".$order_way." 
							Limit 0, ".$n;
			}else{
				$sql = "Select 
							p.`id_product`, 
							pps.`active`, 
							pv.`counter` 
						From 
							`"._DB_PREFIX_."product` as p 
							Inner Join `"._DB_PREFIX_."category_product` as cp 
								On (p.`id_product` = cp.`id_product` AND cp.`id_category`='".$categoryId."') 
							Inner Join `"._DB_PREFIX_."product_shop` as pps  
								On (p.`id_product` = pps.`id_product` AND pps.`id_shop` = '".$shopId."') 
							LEFT JOIN (`"._DB_PREFIX_."page` pg 
								LEFT JOIN `"._DB_PREFIX_."page_viewed` pv 
									ON pv.`id_page` = pg.`id_page` 
								LEFT JOIN `"._DB_PREFIX_."date_range` dr 
									ON pv.`id_date_range` = dr.`id_date_range` 
								LEFT JOIN `"._DB_PREFIX_."page_type` pt 
									ON (
											pt.`id_page_type` = pg.`id_page_type` 
											AND dr.`time_start` > '".date('Y-m-d', strtotime('-'.$PS_NB_DAYS_NEW_PRODUCT.' DAY'))."' 
											AND pt.`name` = 'product'
										) 
								) 
								ON pg.`id_object` = p.`id_product`  
							Where 
								pps.`active` = 1 ".$where." 
							GROUP BY  
								p.id_product 
							ORDER BY 
								pv.`counter` ".$order_way." 
							Limit 0, ".$n;
			}
		}else{
			if($on_discount != 2){
				$sql = "Select 
							p.`id_product`, 
							cp.`position`, 
							pps.`active` 
						From 
							`"._DB_PREFIX_."product` as p 
							Inner Join `"._DB_PREFIX_."category_product` as cp 
								On (p.`id_product` = cp.`id_product` AND cp.`id_category`='".$categoryId."') 
							Inner Join `"._DB_PREFIX_."product_shop` as pps  
								On (p.`id_product` = pps.`id_product` AND pps.`id_shop` = '".$shopId."') 
							Where 
								pps.`active` = 1 ".$where." 
							GROUP BY  
								p.id_product 
							ORDER BY 
								cp.`position` ".$order_way." 
							Limit 0, ".$n;
			}else{
				$sql = "Select 
							p.`id_product`, 
							cp.`position`, 
							pps.`active`  
						From 
							`"._DB_PREFIX_."product` as p 
							Inner Join `"._DB_PREFIX_."category_product` as cp 
								On (p.`id_product` = cp.`id_product` AND cp.`id_category`='".$categoryId."') 
							Inner Join `"._DB_PREFIX_."product_shop` as pps  
								On (p.`id_product` = pps.`id_product` AND pps.`id_shop` = '".$shopId."') 
							Where 
								pps.`active` = 1 ".$where." 
							GROUP BY  
								p.id_product 
							ORDER BY 
								cp.`position` ".$order_way." 
							Limit 0, ".$n;
			}
		}
		
		$results = array();
		$rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		
		if($rows){			
			foreach($rows as $row){					
				$results[] = $this->_getProductById_before($row['id_product'], $id_lang, $shopId);
			}
		}	
		
		return $results;
	}
	
	
    
    public function getTotalViewed($id_product)
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
	public function getProductRatings($id_product)
	{
		$validate = Configuration::get('PRODUCT_COMMENTS_MODERATE');
		$sql = 'SELECT (SUM(pc.`grade`) / COUNT(pc.`grade`)) AS avg,
				MIN(pc.`grade`) AS min,
				MAX(pc.`grade`) AS max,
                COUNT(pc.`grade`) AS total
			FROM `'._DB_PREFIX_.'product_comment` pc
			WHERE pc.`id_product` = '.(int)$id_product.'
			AND pc.`deleted` = 0'.
			($validate == '1' ? ' AND pc.`validate` = 1' : '');


		return DB::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);

	}
    public function getProductsOrderSales($id_lang, $arrCategory = array(), $params = null, $total=false, $short = true, $limit, $offset = 0, $getProperties = true){	
        $context = Context::getContext();
        $order_by = 'sales';
        $order_way = 'desc';
        $where = "";
        if($arrCategory) $catIds = implode(', ', $arrCategory);  
        if($params){
            $order_way = $params->orderType;
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
				FROM `'._DB_PREFIX_.'product_sale` ps
				LEFT JOIN `'._DB_PREFIX_.'product` p ON ps.`id_product` = p.`id_product`
				'.Shop::addSqlAssociation('product', 'p', false).'
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'				
				WHERE product_shop.`active` = 1 AND p.`visibility` != \'none\'  '.$where;
				return (int) DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);				
		}        
        
        
        
        
        	$sql = 'SELECT p.id_product, p.ean13, p.reference, p.id_category_default, p.on_sale, p.quantity, p.minimal_quantity, p.price, p.wholesale_price, p.quantity_discount, p.show_price, p.condition, p.date_add, p.date_upd, 
                    product_shop.on_sale, product_shop.id_category_default, product_shop.minimal_quantity, product_shop.price, product_shop.wholesale_price, product_shop.show_price, product_shop.condition, product_shop.indexed, product_shop.date_add, product_shop.date_upd, 
                    stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity,                     
					pl.`available_later`, pl.`link_rewrite`, pl.`name`, ps.`quantity` AS sales, MAX(image_shop.`id_image`) id_image,
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new, product_shop.price AS orderprice';
           
        
        $sql .= ' FROM `'._DB_PREFIX_.'product_sale` ps
				LEFT JOIN `'._DB_PREFIX_.'product` p
			 	   ON p.`id_product` = ps.`id_product`
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
				ON (p.`id_product` = pa.`id_product`)
				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'				
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON (p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
				LEFT JOIN `'._DB_PREFIX_.'image` i
					ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'				
				WHERE
                    product_shop.`id_shop` = '.(int)$context->shop->id.' 
                    AND product_shop.`visibility` IN ("both", "catalog") '.$where.' 
                    GROUP BY product_shop.id_product
                    ORDER BY `'.pSQL($order_by).'` '.pSQL($order_way).' Limit '.$offset.', '.$limit;
        
       		
		$result = DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);               
		//if ($final_order_by == 'price') Tools::orderbyPrice($result, $order_way);
		if (!$result) return false;
        if($getProperties == false) return $result;
		return Product::getProductsProperties($id_lang, $result);
	}
    
    function getProductsOrderPrice($id_lang, $arrCategory = array(), $params = null, $total = false, $short = true, $limit, $offset = 0, $getProperties = true){
        $context = Context::getContext();
        $order_by = 'price';
        $order_way = 'DESC';        
        $where = "";
        if($arrCategory) $catIds = implode(', ', $arrCategory);
        if($params){
            $order_way = $params->orderType;        
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
		$sql = 'SELECT p.id_product, p.ean13, p.reference, p.id_category_default, p.on_sale, p.quantity, p.minimal_quantity, p.price, p.wholesale_price, p.quantity_discount, p.show_price, p.condition, p.date_add, p.date_upd, 
				product_shop.on_sale, product_shop.id_category_default, product_shop.minimal_quantity, product_shop.price, product_shop.wholesale_price, product_shop.show_price, product_shop.condition, product_shop.indexed, product_shop.date_add, product_shop.date_upd, 
				stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity,                     
				pl.`available_later`, pl.`link_rewrite`, pl.`name`, MAX(image_shop.`id_image`) id_image,
				il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
				DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
				INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
					DAY)) > 0 AS new, product_shop.price AS orderprice';
				
        $sql .= ' FROM  `'._DB_PREFIX_.'product` p 
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
				ON (p.`id_product` = pa.`id_product`)
				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'				
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON (p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
				LEFT JOIN `'._DB_PREFIX_.'image` i
					ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'				
				WHERE
                    product_shop.`id_shop` = '.(int)$context->shop->id.' 
                    AND product_shop.`visibility` IN ("both", "catalog") '.$where.' 
                    GROUP BY product_shop.id_product
                    ORDER BY p.`'.pSQL($order_by).'` '.pSQL($order_way).' Limit '.$offset.', '.$limit;
        
         
           $result = DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            
          if (!$result) return false;
            if($getProperties == false) return $result;
    		return Product::getProductsProperties($id_lang, $result);

    }
    function getProductsOrderRand($id_lang, $arrCategory = array(), $params = null, $total=false, $short = true, $limit, $offset=0, $getProperties = true){
        $context = Context::getContext();
        $order_by = 'RAND()';
        $order_way = '';        
        $where = "";
        if($arrCategory) $catIds = implode(', ', $arrCategory);
        if($params){
            $order_way = $params->orderType;   
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
		
        
        
        	$sql = 'SELECT p.id_product, p.ean13, p.reference, p.id_category_default, p.on_sale, p.quantity, p.minimal_quantity, p.price, p.wholesale_price, p.quantity_discount, p.show_price, p.condition, p.date_add, p.date_upd, 
                    product_shop.on_sale, product_shop.id_category_default, product_shop.minimal_quantity, product_shop.price, product_shop.wholesale_price, product_shop.show_price, product_shop.condition, product_shop.indexed, product_shop.date_add, product_shop.date_upd, 
                    stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity,                     
					pl.`available_later`, pl.`link_rewrite`, pl.`name`, MAX(image_shop.`id_image`) id_image,
					
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new, product_shop.price AS orderprice';
             
        
        $sql .= ' FROM  `'._DB_PREFIX_.'product` p 
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
				ON (p.`id_product` = pa.`id_product`)
				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'
				
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON (p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
				LEFT JOIN `'._DB_PREFIX_.'image` i
					ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				
				WHERE
                    product_shop.`id_shop` = '.(int)$context->shop->id.' 
                    AND product_shop.`visibility` IN ("both", "catalog") '.$where.' 
                    GROUP BY product_shop.id_product
                    ORDER BY RAND() Limit '.$limit;
        
        
           $result = DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    
          if (!$result) return false;
            if($getProperties == false) return $result;
    		return Product::getProductsProperties($id_lang, $result);

    }
    function getProductsOrderAddDate($id_lang, $arrCategory = array(), $params = null, $total=false, $short = true, $limit, $offset = 0, $getProperties = true){
        $context = Context::getContext();
        
        $order_by = 'date_add';
        $order_way = 'DESC';        
        $where = "";
        if($arrCategory) $catIds = implode(', ', $arrCategory);
        if($params){
            $order_way = $params->orderType;        
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
				WHERE product_shop.`active` = 1 AND p.`visibility` != \'none\' '.$where;				
				return (int) DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);				
		}
        
        
        	$sql = 'SELECT p.id_product, p.ean13, p.reference, p.id_category_default, p.on_sale, p.quantity, p.minimal_quantity, p.price, p.wholesale_price, p.quantity_discount, p.show_price, p.condition, p.date_add, p.date_upd, 
                    product_shop.on_sale, product_shop.id_category_default, product_shop.minimal_quantity, product_shop.price, product_shop.wholesale_price, product_shop.show_price, product_shop.condition, product_shop.indexed, product_shop.date_add, product_shop.date_upd, 
                    stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity,                     
					pl.`available_later`, pl.`link_rewrite`, pl.`name`, MAX(image_shop.`id_image`) id_image,
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new, product_shop.price AS orderprice';
       
        $sql .= ' FROM  `'._DB_PREFIX_.'product` p 
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
				ON (p.`id_product` = pa.`id_product`)
				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'
				
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON (p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
				LEFT JOIN `'._DB_PREFIX_.'image` i
					ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'				
				WHERE
                    product_shop.`id_shop` = '.(int)$context->shop->id.' 
                    AND product_shop.`visibility` IN ("both", "catalog") '.$where.' 
                    GROUP BY product_shop.id_product
                    ORDER BY p.`'.pSQL($order_by).'` '.pSQL($order_way).' Limit '.$offset.', '.$limit;
                    
       
           $result = DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            
          if (!$result) return false;
            if($getProperties == false) return $result;
    		return Product::getProductsProperties($id_lang, $result);
    }
    function getProductById($id_lang, $productId, $short = true, $getProperties = true){
        
        $context = Context::getContext();
        $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,
					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,
					il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new, product_shop.price AS orderprice
				FROM `'._DB_PREFIX_.'category_product` cp
				LEFT JOIN `'._DB_PREFIX_.'product` p
					ON p.`id_product` = cp.`id_product`
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
				ON (p.`id_product` = pa.`id_product`)
				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'
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
				WHERE 
                    product_shop.`id_shop` = '.(int)$context->shop->id.'
					AND product_shop.`id_product` = '.$productId.' 
                    AND product_shop.`visibility` IN ("both", "catalog")
                    GROUP BY product_shop.id_product';

          
           $result = DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            if (!$result) return false;
            if($getProperties == false) return $result;            
    		return Product::getProductProperties($id_lang, $result);
    }
       
    public function getProductsOrderSpecial($id_lang, $arrCategory = array(), $params = null, $total = false, $short = true, $limit, $offset = 0, $getProperties = true)
	{
        $currentDate = date('Y-m-d');
        $context = Context::getContext();
        $id_address = $context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
		$ids = Address::getCountryAndState($id_address);
		$id_country = (int)($ids['id_country'] ? $ids['id_country'] : Configuration::get('PS_COUNTRY_DEFAULT'));
        
        $order_by = 'reduction';
        $order_way = 'DESC';
        $where = "";
        
        if($arrCategory) $catIds = implode(', ', $arrCategory);
        if($params){
            $order_way = $params->orderType;
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
       
       
        
	     	$sql = 'SELECT p.id_product, p.ean13, p.reference, p.id_category_default, p.on_sale, p.quantity, p.minimal_quantity, p.price, p.wholesale_price, p.quantity_discount, p.show_price, p.condition, p.date_add, p.date_upd, 
                    product_shop.on_sale, product_shop.id_category_default, product_shop.minimal_quantity, product_shop.price, product_shop.wholesale_price, product_shop.show_price, product_shop.condition, product_shop.indexed, product_shop.date_add, product_shop.date_upd, 
                    stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity,                     
					pl.`available_later`, pl.`link_rewrite`, pl.`name`, MAX(image_shop.`id_image`) id_image,
					
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new, product_shop.price AS orderprice';
            
        
        $sql .= ' FROM (`'._DB_PREFIX_.'product` p 
                INNER JOIN `'._DB_PREFIX_.'specific_price` sp On p.id_product = sp.id_product) 
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
				ON (p.`id_product` = pa.`id_product`)
				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'
				
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON (p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
				LEFT JOIN `'._DB_PREFIX_.'image` i
					ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'				
				WHERE 
                    product_shop.`id_shop` = '.(int)$context->shop->id.' 
                    AND sp.`id_shop` IN(0, '.(int)$context->shop->id.') 
					AND sp.`id_currency` IN(0, '.(int)$context->currency->id.') 
					AND sp.`id_country` IN(0, '.(int)$id_country.') 
					AND sp.`id_group` IN(0, '.(int)$context->customer->id_default_group.')  
					AND sp.`id_customer` IN(0) 
					AND sp.`from_quantity` = 1 
					AND (sp.`from` = \'0000-00-00 00:00:00\' OR \''.pSQL($currentDate).'\' >= sp.`from`) 
					AND (sp.`to` = \'0000-00-00 00:00:00\' OR \''.pSQL($currentDate).'\' <= sp.`to`) 					
					AND sp.`reduction` > 0 
                    AND product_shop.`visibility` IN ("both", "catalog") '.$where.' 
                    GROUP BY product_shop.id_product 
                    ORDER BY sp.`'.pSQL($order_by).'` '.pSQL($order_way).' Limit '.$offset.', '.$limit;
        
        
                       
           $result = DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    
          if (!$result) return false;
            if($getProperties == false) return $result;
    		return Product::getProductsProperties($id_lang, $result);        
	}
    function getProductsByIds($id_lang, $productIds=array(), $total=false, $getProperties){
		$context = Context::getContext();
		if($productIds) $ids = trim(implode(', ', $productIds));		
		else return false;
		if(!$ids) return false;
        
        
        
        
        
		if($total == true){
			$sql = 'SELECT COUNT(p.id_product)
				FROM  `'._DB_PREFIX_.'product` p 
                '.Shop::addSqlAssociation('product', 'p', false).'				
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'				
				WHERE product_shop.`active` = 1 AND p.id_product IN ('.$ids.')
					AND p.`visibility` != \'none\' ';				
				return (int) DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);				
		}
        
        
        
        $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,
					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,
					il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new, product_shop.price AS orderprice
				FROM `'._DB_PREFIX_.'category_product` cp
				LEFT JOIN `'._DB_PREFIX_.'product` p
					ON p.`id_product` = cp.`id_product`
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
				ON (p.`id_product` = pa.`id_product`)
				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'
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
				WHERE 
                    product_shop.`id_shop` = '.(int)$context->shop->id.'  
                    AND product_shop.`visibility` IN ("both", "catalog") 
                    AND product_shop.id_product IN ('.$ids.') 
                    GROUP BY product_shop.id_product';

	
           	$result = DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            if (!$result) return false;
            if($getProperties == false) return $result;
    		return Product::getProductsProperties($id_lang, $result);
    }
    function getProductList($id_lang, $arrCategory = array(), $notIn = '', $keyword = '', $getTotal = false, $offset=0, $limit=10){
        
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
			
                $result = DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                return Product::getProductsProperties($id_lang, $result);

    }
    
    
    
}
