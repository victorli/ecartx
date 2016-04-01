<?php
/*
*  @author SonNC Ovic <nguyencaoson.zpt@gmail.com>
*/
// check module da cai hay chua Module::isInstalled('productcomments') != 1
//require (dirname(__FILE__).'/GroupCategoryLibraries.php');
class GroupCategory extends Module
{
    
    const INSTALL_SQL_FILE = 'install.sql';	
    public $arrType = array();
    public $arrLayout = array();
	public $imageHomeSize = array();
    public $pathTemp = '';
    public $pathBanner = '';
    public $pathIcon = '';
	public $livePath = '';	
    public $compareProductIds;
	public $codeCss = array();
    public $cacheTime = 86400;
	
	public static $orderBy = array();
    public static $orderWay = array();
    public static $displayOnCondition = array();
	public static $displayOnSale = array();
	public static $displayOnNew = array();
	public static $displayOnDiscount = array();
	public static $categoryType = array();
	public static $features = array();
	public $pathImage = '';
	public $liveImage = '';
	//protected static $productCache = array();
    protected static $cacheProducProperties = array();
    public static $_taxCalculationMethod = null;
	protected static $arrPosition = array('displayGroupFashions', 'displayGroupFoods', 'displayGroupSports');
	protected $cache = null;
	public function __construct()
	{				
		$this->name = 'groupcategory';
		self::$orderBy = array(
			'seller'	=>	$this->l('Seller'), 
			'price'		=>	$this->l('Price'), 
			'discount'	=>	$this->l('Discount'), 
			'date_add'	=>	$this->l('Add Date'), 
			'position'	=>	$this->l('Position'), 
			'review'	=>	$this->l('Review'), 
			'view'		=>	$this->l('View'), 
			'rate'		=>	$this->l('Rates'),
		);
        self::$orderWay = array(
        	'asc'	=>	$this->l('Ascending'), 
        	'desc'	=>	$this->l('Descending'),
		);
        self::$displayOnCondition = array(
        	'all'			=>	$this->l('All'), 
        	'new'			=>	$this->l('New'), 
        	'used'			=>	$this->l('Used'), 
        	'refurbished'	=>	$this->l('Refurbished'),
		);
		self::$displayOnSale = array(
			'2'	=>	$this->l('All'), 
			'0'	=>	$this->l('No'), 
			'1'	=>	$this->l('Yes')
		);
		self::$displayOnNew = array(
			'2'	=>	$this->l('All'), 
			'0'	=>	$this->l('No'), 
			'1'	=>	$this->l('Yes'));
		self::$displayOnDiscount = array(
			'2'	=>	$this->l('All'), 
			'0'	=>	$this->l('No'), 
			'1'	=>	$this->l('Yes')
		);		
        self::$categoryType = array(
        	'auto'		=>	$this->l('Auto'), 
        	'manual'	=>	$this->l('Manual'),
		);
		self::$features = array(
        	'seller'	=>	$this->l('Best Sellers'), 
        	'view'		=>	$this->l('Most View'),
        	'review'	=>	$this->l('Most Review'),
        	'rate'		=>	$this->l('Most rates'),
        	'special'	=>	$this->l('Specials'),
        	'arrival'	=>	$this->l('New Arrivals'),
		);
		
		$this->arrType = array('saller'=>$this->l('Best Sellers'), 'view'=>$this->l('Most View'), 'special'=>$this->l('Specials'), 'arrival'=>$this->l('New Arrivals'));		
		$this->secure_key = Tools::encrypt('ovic-soft[group-category]'.$this->name);
		$this->imageHomeSize = Image::getSize(ImageType::getFormatedName('home'));
        $this->arrLayout = array('default'=>$this->l('Layout [default]'));
		
		$this->pathTemp = dirname(__FILE__).'/images/temps/';
        $this->pathBanner = dirname(__FILE__).'/images/banners/';
        $this->pathIcon = dirname(__FILE__).'/images/icons/';		
		if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
			$this->livePath = _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/groupcategory/images/'; 
		else
			$this->livePath = _PS_BASE_URL_.__PS_BASE_URI__.'modules/groupcategory/images/';
			
			
		$this->pathImage = dirname(__FILE__).'/images/';
		if(Configuration::get('PS_SSL_ENABLED'))
			$this->liveImage = _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/'.$this->name.'/images/'; 
		else
			$this->liveImage = _PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/images/';
		$this->tab = 'front_office_features';
		$this->version = '2.0';
		$this->author = 'OvicSoft';		
		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->l('Supershop - Group Category Module');
		$this->description = $this->l('Group Category Module');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
		if(_PS_CACHE_ENABLED_)
			$this->cache = Cache::getInstance();
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
	   if ($keep)
		{			
			if (!file_exists(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
				return false;
			else if (!$sql = file_get_contents(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
				return false;
			$sql = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
			$sql = preg_split("/;\s*[\r\n]+/", trim($sql));
			foreach ($sql as $query)
				if (!DB::getInstance()->execute(trim($query))) return false;
			
		}
		if(!parent::install() 
			|| !$this->registerHook('displayHeader')
			|| !$this->registerHook('displayGroupFashions')
            || !$this->registerHook('actionProductAdd')
            || !$this->registerHook('actionProductAttributeDelete')
            || !$this->registerHook('actionProductAttributeUpdate')
            || !$this->registerHook('actionProductDelete')
            || !$this->registerHook('actionProductSave')
            || !$this->registerHook('actionProductUpdate')
            || !$this->registerHook('actionCartSave')
            || !$this->registerHook('actionCategoryAdd')
            || !$this->registerHook('actionCategoryDelete')
			|| !$this->registerHook('displayGroupFoods')
			|| !$this->registerHook('displayGroupSports')) return false;
		if (!Configuration::updateGlobalValue('MOD_GROUP_CATEGORY', '1')) return false;
        $this->clearCache();	
		$this->updateDemoData();
		return true;
	}
	public function updateDemoData(){
		$langId = Context::getContext()->language->id;		
	    $shopId = Context::getContext()->shop->id;
		$items = DB::getInstance()->executeS("Select * From "._DB_PREFIX_."groupcategory_groups  Where `id_shop` = '$shopId'");
		$defaultCat = Configuration::get('PS_HOME_CATEGORY');
		if($items){
			foreach ($items as $key => $item) {
				Db::getInstance()->update('groupcategory_groups', array('categoryId'=>$defaultCat), "id = ".$item['id']);				
			}
		}
		$items = DB::getInstance()->executeS("Select * From "._DB_PREFIX_."groupcategory_items");
		if($items){
			foreach ($items as $key => $item) {
				Db::getInstance()->update('groupcategory_items', array('categoryId'=>$defaultCat), "id = ".$item['id']);				
			}
		}
		
	}
	public function uninstall($keep = true)
	{	   
		if (!parent::uninstall()) return false;
        if($keep){			
            if(!DB::getInstance()->execute('
			DROP TABLE IF EXISTS
			`'._DB_PREFIX_.'groupcategory_group_lang`,
            `'._DB_PREFIX_.'groupcategory_groups`,
            `'._DB_PREFIX_.'groupcategory_item_lang`,
            `'._DB_PREFIX_.'groupcategory_product_view`,
            `'._DB_PREFIX_.'groupcategory_styles`,
			`'._DB_PREFIX_.'groupcategory_items`')) return false;
			
        }		
        if (!Configuration::deleteByName('MOD_GROUP_CATEGORY')) return false;
        $this->clearCache();
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
	
	protected function getOrderByOptions($selected=''){
		$options = '';
		foreach (self::$orderBy as $key => $value) {
			if($selected == $key) $options .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
			else $options .= '<option value="'.$key.'">'.$value.'</option>';
		}
		return $options;
	}
	protected function getOrderWayOptions($selected=''){
		$options = '';
		foreach (self::$orderWay as $key => $value) {
			if($selected == $key) $options .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
			else $options .= '<option value="'.$key.'">'.$value.'</option>'; 
		}
		return $options;
	}
	protected function getCategoryTypeOptions($selected = ''){
		$options = '';
		foreach (self::$categoryType as $key => $value) {
			if($selected == $key) $options .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
			else $options .= '<option value="'.$key.'">'.$value.'</option>';
		}
		return $options;
	}
	protected static function getOnConditionOptions($selected = ''){
		$options = '';
		foreach (self::$displayOnCondition as $key => $value) {
			if($selected == $key) $options .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
			else $options .= '<option value="'.$key.'">'.$value.'</option>';
		}
		return $options;
	}
	protected static function getOnSaleOptions($selected = ''){
		$options = '';
		foreach (self::$displayOnSale as $key => $value) {
			if($selected == $key) $options .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
			else $options .= '<option value="'.$key.'">'.$value.'</option>';
		}
		return $options;
	}
	protected static function getOnNewOptions($selected = ''){
		$options = '';
		foreach (self::$displayOnNew as $key => $value) {
			if($selected == $key) $options .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
			else $options .= '<option value="'.$key.'">'.$value.'</option>';
		}
		return $options;
	}
	protected static function getOnDiscountOptions($selected = ''){
		$options = '';
		foreach (self::$displayOnDiscount as $key => $value) {
			if($selected == $key) $options .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
			else $options .= '<option value="'.$key.'">'.$value.'</option>';
		}
		return $options;
	}
	protected static function getFeatureOptions($selected = ''){
		$options = '';
		foreach (self::$features as $key => $value) {
			if($selected == $key) $options .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
			else $options .= '<option value="'.$key.'">'.$value.'</option>';
		}
		return $options;
	}
	
    function getCategoryIds($parentId = 0, $arr=null){
        if($arr == null) $arr = array();
        $items = DB::getInstance()->executeS("Select id_category From "._DB_PREFIX_."category Where id_parent = $parentId");
        if($items){
            foreach($items as $item){
                $arr[] = $item['id_category'];
                $arr = $this->getCategoryIds($item['id_category'], $arr);
            }
        }
        return $arr;
    }
    public function getAllStyle(){
        $shopId = Context::getContext()->shop->id;
        $items = DB::getInstance()->executeS("Select * From "._DB_PREFIX_."groupcategory_styles Where id_shop = ".$shopId);
        $content = '';
        if($items){
            foreach($items as $item){
				if($item['params']){
					$params = json_decode($item['params']);								
					$arrParams = get_object_vars($params);				
					$keys = array_keys($arrParams);
					$value = '';
					if($keys){
						foreach($keys as $key){
							$value .= '<div class="style-values"><span class="style-value" style="background: '.$arrParams[$key].'">&nbsp;</span><label>'.$key.'</label>: <span>'.$arrParams[$key].'</span></div>';
							
						}
					}
					$content .= '<tr><td>'.$item['id'].'</td><td>'.$item['name'].'</td><td>'.$value.'</td><td class="center"><a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-style-edit"><i class="icon-edit"></i></a>&nbsp;<a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-style-delete"><i class="icon-trash" ></i></a></td></tr>';
				}                
            }
        }		
        return $content;
    }
    public function getAllGroup(){
        $langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;
        $items = DB::getInstance()->executeS("Select DISTINCT g.*, s.name as styleName From "._DB_PREFIX_."groupcategory_groups as g Left Join "._DB_PREFIX_."groupcategory_styles as s On s.id = g.style_id Where g.id_shop = ".$shopId." Order By g.position_name, g.ordering");        
        $listGroup = '';        
        if($items){
            foreach($items as $item){
                
                $itemLang = $this->getGroupLangById($item['id'], $langId, $shopId);
                if($item['status'] == "1"){
                    $status = '<a title="Enabled" class="list-action-enable action-enabled lik-group-status" item-id="'.$item['id'].'" value="'.$item['status'].'"><i class="icon-check"></i></a>';
                }else{
                    $status = '<a title="Disabled" class="list-action-enable action-disabled lik-group-status" item-id="'.$item['id'].'" value="'.$item['status'].'"><i class="icon-check"></i></a>';
                }
                $listGroup .= '<tr id="gr_'.$item['id'].'"><td class="center">'.$item['id'].'</td><td><a class="cat-group" href="javascript:void(0)" item-id="'.$item['id'].'">'.$itemLang['name'].'</a></td><td>'.$this->getCategoryLangNameById($item['categoryId'], $langId, $shopId).'</td><td>'.$item['position_name'].'</td><td>'.$item['styleName'].'</td><td class="pointer dragHandle center" ><div class="dragGroup"><div class="positions">'.$item['ordering'].'</div></div></td><td class="center">'.$status.'</td><td class="center"><a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-group-edit"><i class="icon-edit"></i></a>&nbsp;<a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-group-delete"><i class="icon-trash" ></i></a></td></tr>';
            }
        }        
        return $listGroup;
    }
    public function getManufacturerOptions($selected = array()){
        $items = DB::getInstance()->executeS("Select id_manufacturer, name From "._DB_PREFIX_."manufacturer Where active = 1");
        $manufacturerOptions ='<option value="0">-- No selected --</option>';
        if($items){
            foreach($items as $item){
                if($selected){
                    if(in_array($item['id_manufacturer'], $selected)){
                        $manufacturerOptions .='<option selected="selected" value="'.$item['id_manufacturer'].'">'.$item['name'].'</option>';    
                    }else{
                        $manufacturerOptions .='<option value="'.$item['id_manufacturer'].'">'.$item['name'].'</option>';    
                    }                        
                }else{
                    $manufacturerOptions .='<option value="'.$item['id_manufacturer'].'">'.$item['name'].'</option>';
                }                
            }
        }
        return $manufacturerOptions;
    }
	public function getAllCategories($langId, $shopId, $parentId = 0, $sp='', $arr=null){
        if($arr == null) $arr = array();
        $sql = "Select DISTINCT 
					c.id_category, 
					cl.name 
				From `"._DB_PREFIX_."category` as c 
					Inner Join `"._DB_PREFIX_."category_shop` as cs 
						On (c.id_category = cs.id_category AND cs.id_shop = ".$shopId.") 
					Inner Join "._DB_PREFIX_."category_lang as cl 
						On (c.id_category = cl.id_category AND cl.id_lang = ".$langId.") 
				Where 
					c.active = 1  
					AND c.id_parent = ".$parentId;
        $items = Db::getInstance()->executeS($sql);
        if($items){
            foreach($items as $item){
                $arr[] = array('id_category'=>$item['id_category'], 'name'=>$item['name'], 'sp'=>$sp);
                $arr = $this->getAllCategories($langId, $shopId, $item['id_category'], $sp.'- ', $arr);
            }
        }
        return $arr;
    }
    public function getCategoryOptions($selected = 0, $parentId = 0){
        $langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;
        $categoryOptions = '';
        if($parentId <=0) $parentId = Configuration::get('PS_HOME_CATEGORY');		
        $items = $this->getAllCategories($langId, $shopId, $parentId, '- ', null);        
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
    public function getPositionOptions($selected = ''){
    	$options = '';		
		if(self::$arrPosition){			
			foreach(self::$arrPosition as $value){
				if($value == $selected) $options .='<option selected="selected" value="'.$value.'">'.$value.'</option>';
				else $options .='<option value="'.$value.'">'.$value.'</option>';
			}
		}		
        return $options;
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
	/**
	 *  getTypeCheckbox
	 * 	var $checked = array();
	 */
    public function getTypeCheckbox($checked = array()){
        $typeCheckbox = '';
        if($checked){            
            foreach($this->arrType as $key=>$value){
                if(in_array($key, $checked)){
                    $typeCheckbox .= '<div class="col-sm-6"><input type="checkbox" name="types[]" checked="checked" class="types" id="type-'.$key.'" value="'.$key.'" />&nbsp;<label for="type-'.$key.'" class="control-label">'.$value.'</label></div>';    
                }else{
                    $typeCheckbox .= '<div class="col-sm-6"><input type="checkbox" name="types[]" class="types" id="type-'.$key.'" value="'.$key.'" />&nbsp;<label for="type-'.$key.'" class="control-label">'.$value.'</label></div>';
                }                
            }
        }else{
            foreach($this->arrType as $key=>$value){                
                $typeCheckbox .= '<div class="col-sm-6"><input type="checkbox" name="types[]" class="types" id="type-'.$key.'" value="'.$key.'" />&nbsp;<label for="type-'.$key.'" class="control-label">'.$value.'</label></div>';
            }            
        }
        return $typeCheckbox;
    }
	
	public function getImageSrc($image, $check = true){
        if($image){
            if(strpos($image, 'http') !== false){
                return $image;
    	   }else{
                if(file_exists($this->pathImage.'banners/'.$image))
                    return $this->liveImage.'banners/'.$image;
                else
                    if($check == true) 
                        return '';
                    else
                        return $this->liveImage.'banners/default.jpg';	  	 
          }    
        }else{
            if($check == true) 
                return '';
            else
                return $this->liveImage.'banners/default.jpg';
        }	
	}
	public function getIconSrc($image){
		if($image){
        	if(strpos($image, '.') !== false){        		
	        	if(strpos($image, 'http') !== false){
	                $results = array('type'=>'image', 'img'=>$image);
	            }else{
	                if(file_exists($this->pathImage.$image))
						$results = array('type'=>'image', 'img'=>$this->liveImage.$image);	                    
	        		else if($image && file_exists($this->pathImage.'icons/'.$image))
						$results = array('type'=>'image', 'img'=>$this->liveImage.'icons/'.$image);
	                else{
	                	$results = array('type'=>'none', 'img'=>'');
	                }
						
	                        
	            }	
        	}else{
        		$results = array('type'=>'class', 'img'=>$image);
        	}
        }else{
        	$results = array('type'=>'none', 'img'=>'');
        }    
		return $results;
	
		
	}
	
	
	
	public function getItemBannerSrc($image = '', $check = false){
        if($image && file_exists(_PS_MODULE_DIR_.'groupcategory/images/banners/'.$image))
            return $this->livePath.'banners/'.$image;
        else
            if($check == true) 
                return '';
            else
                return $this->livePath.'banners/default.jpg'; 
    }
    public function getGroupBannerSrc($image = '', $check = false){
        if($image && file_exists(_PS_MODULE_DIR_.'groupcategory/images/banners/'.$image))
            return $this->livePath.'banners/'.$image;
        else
            if($check == true) 
                return '';
            else
                return $this->livePath.'banners/default.jpg'; 
    }
    public function getGroupIconSrc($image = '', $check = false){
        if($image && file_exists(_PS_MODULE_DIR_.'groupcategory/images/icons/'.$image))
            return $this->livePath.'icons/'.$image;
        else
            if($check == true) 
                return '';
            else
                return $this->livePath.'icons/default.jpg'; 
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
	function getGroupByLang($id, $langId=0, $shopId=0){
		if(!$langId) $langId = Context::getContext()->language->id;
        if(!$shopId) $shopId = Context::getContext()->shop->id;
		$itemLang = DB::getInstance()->getRow("Select name, banner, banner_link, banner_size From "._DB_PREFIX_."groupcategory_group_lang Where group_id = $id AND `id_lang` = '$langId' AND `id_shop` = '$shopId'" );
		if(!$itemLang) $itemLang = array('name'=>'', 'banner'=>'', 'banner_link'=>'', 'banner_size'=>'');
		return $itemLang;
	}
	function ovicRenderGroupForm($id = 0){
		$item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."groupcategory_groups Where id = $id");		
        if(!$item){        	
			$types = array();
			$manufactureIds = array();
			$manImageWidth = 126;
			$manImageHeight = 51;			
			$item = array(
				'id'=>0, 
				'position'=>0, 
				'position_name'	=>	'',
				'categoryId'	=>	0, 
				'cat_type'		=>	'auto',
				'order_by'		=>	'position',
				'order_way'		=>	'desc',
				'on_condition'	=>	'all',
				'on_sale'		=>	2,
				'on_new'		=>	2,
				'on_discount'	=>	2,
				'params'		=>	'',
				'is_cache'		=>	1,
				'max_item'		=>	12,
				'type_default'	=>	'arrival', 
				'style_id'		=>	0, 
				'layout'		=>	'default', 
				'manufactureConfig'=>'', 
				'itemConfig'	=>	'', 
				'types'			=>	'', 
				'icon'			=>	'', 
				'ordering'		=>	1, 
				'status'		=>	1, 
				'note'			=>	'',	
							
			);
			$params = new stdClass();
			$params->features = array();
			$params->manufacturers = array();
			$params->products = array();
            		
		}else{
			if($item['params']) $params = Tools::jsonDecode($item['params']);
			else{
				$params = new stdClass();
				$params->features = array();
				$params->manufacturers = array();
				$params->products = array();
			}			
			$manufactureConfig = json_decode($item['manufactureConfig']);			
			$manufactureIds = $manufactureConfig->ids;
			$manImageHeight = $manufactureConfig->imageHeight;
			$manImageWidth = $manufactureConfig->imageWidth;
			$types = json_decode($item['types']);
            $itemConfig = get_object_vars(json_decode($item['itemConfig'])) ;
		}		
		$langActive = '<input type="hidden" id="groupLangActive" value="0" />';
		$languages = $this->getAllLanguage();
		$inputName = '';
		$inputBanner = '';
		$inputBannerLink = '';
		$inputHtml = '';
		if($languages){
			foreach ($languages as $key => $language) {				
				$itemLang = $this->getGroupByLang($id, $language->id);
				if($language->active == '1'){
					$langActive = '<input type="hidden" id="groupLangActive" value="'.$language->id.'" />';
					$inputName .= '<input type="text" value="'.$itemLang['name'].'" name="names[]"  class="form-control group-lang-'.$language->id.'" />';
					$inputBanner .= '<input type="text" value="'.$itemLang['banner'].'" name="banners[]" id="groupBanner-'.$language->id.'" class="form-control group-lang-'.$language->id.'"  />';
					$inputBannerLink .= '<input type="text" value="'.$itemLang['banner_link'].'" name="links[]" class="form-control group-lang-'.$language->id.'" />';
				}else{
					$inputName .= '<input type="text" value="'.$itemLang['name'].'" name="names[]"  class="form-control group-lang-'.$language->id.'" style="display:none" />';
					$inputBanner .= '<input type="text" value="'.$itemLang['banner'].'" name="banners[]" id="groupBanner-'.$language->id.'" class="form-control group-lang-'.$language->id.'" style="display:none" />';
					$inputBannerLink .= '<input type="text" value="'.$itemLang['banner_link'].'" name="links[]" class="form-control group-lang-'.$language->id.'" style="display:none" />';										
				}				
			}
		}
		$langOptions = $this->getLangOptions();
		$html = array();
		$html['config'] = '';
		$html['product_config'] = '';
		$html['config'] .= '<input type="hidden" name="groupId" value="'.$item['id'].'" />';		
		$html['config'] .= $langActive;
		$html['config'] .= '<input type="hidden" name="action" value="saveGroup" />';
		$html['config'] .= '<input type="hidden" name="secure_key" value="'.$this->secure_key.'" />';		
		$html['config'] .= '<div class="form-group">
			                    <label class="control-label col-sm-2 required">'.$this->l('Group Name').'</label>
							    <div class="col-sm-10">
			                        <div class="col-sm-10">
			                            '.$inputName.'
			                        </div>
			                        <div class="col-sm-2">
			                            <select class="group-lang" onchange="groupChangeLanguage(this.value)">'.$langOptions.'</select>
			                        </div>                                                                        
			                    </div>
			                </div>';
		$html['config'] .= '<div class="form-group">
			                    <label class="control-label col-sm-2">'.$this->l('Position').'</label>
							    <div class="col-sm-10">
			                        <div class="col-sm-5">
			                            <select class="form-control" name="position_name">'.$this->getPositionOptions($item['position_name']).'</select>
			                        </div>
			                        <label class="control-label col-sm-2">'.$this->l('Style').'</label> 
			                        <div class="col-sm-5">
			                            <select class="form-control" name="style_id" id="styleId">'.$this->getStyleOptions($item['style_id']).'</select>
			                        </div>                       
			                    </div>
			                </div>';
		$html['config'] .= '<div class="form-group">
			                    <label class="control-label col-sm-2">'.$this->l('Layout').'</label>
							    <div class="col-sm-10">
			                        <div class="col-sm-5">
			                            <select class="form-control" name="layout">'.$this->getLayoutOptions($item['layout']).'</select>
			                        </div> 
			                        <label class="control-label col-sm-2">'.$this->l('Cache').'</label>		                    
			                        <div class="col-sm-5">
			                            <span class="switch prestashop-switch fixed-width-lg" id="group-is_cache">
			                                <input type="radio" value="1" class="group-is_cache" '.($item['is_cache'] == 1 ? 'checked="checked"':'').'  id="group-is_cache_on" name="is_cache" />
			            					<label for="group-is_cache_on">Yes</label>
			            				    <input type="radio" value="0" class="group-is_cache" '.($item['is_cache'] == 0 ? 'checked="checked"':'').' id="group-is_cache_off" name="is_cache" />
			            					<label for="group-is_cache_off">No</label>
			                                <a class="slide-button btn"></a>
			            				</span>
			                        </div>                                                      
			                    </div>
			                </div>';
		$html['config'] .= '<div class="form-group clearfix">
			                    <label class="control-label col-sm-2 required">'.$this->l('Icon').'</label>
			                    <div class="col-sm-10">
			                        <div class="col-sm-4">                        
			                            <div class="input-group">
			                                <input type="text" class="form-control" name="groupIcon" value="'.$item['icon'].'" id="group-icon" readonly="readonly" />
			                                <span class="input-group-btn">
			                                    <button id="icon-uploader" type="button" class="btn btn-default"><i class="icon-folder-open"></i></button>
			                                </span>
			                            </div>                  
			                        </div>
			                        <label class="control-label col-sm-2 required">'.$this->l('Banner').'</label>
			                        <div class="col-sm-4">                        
			                            <div class="input-group">
			                                '.$inputBanner.'
			                                <span class="input-group-btn">
			                                    <button id="image-uploader" type="button" class="btn btn-default"><i class="icon-folder-open"></i></button>
			                                </span>
			                            </div>                        
			                        </div>
			                        <div class="col-sm-2">
			                            <select class="group-lang" onchange="groupChangeLanguage(this.value)">'.$langOptions.'</select>
			                        </div>
			                    </div>  
			                </div>';
		$html['config'] .= '<div class="form-group">
			                    <label class="control-label col-sm-2">'.$this->l('Link banner').'</label>
							    <div class="col-sm-10">
							    	<div class="col-sm-10">'.$inputBannerLink.'</div>
			                        <div class="col-sm-2">
			                            <select class="group-lang" onchange="groupChangeLanguage(this.value)">'.$langOptions.'</select>
			                        </div>
			                    </div>
			                </div>';
		
		$html['config'] .='<div class="form-group">
								<label class="control-label col-sm-2">'.$this->l('Features').'&nbsp;<a href="javascript:void(0)" class="link-open-dialog-feature" data-group="'.$item['id'].'" title="'.$this->l('Add feature').'"><i class="icon-plus-circle"></i></a></label>
								<div class="col-sm-10">
									<div class="col-sm-12">
										<ul id="list-features" class="ul-sortable">'.$this->getShowFeatures($params->features).'</ul>
									</div>
								</div>
							</div>';
		$html['config'] .='<div class="form-group">
								<label class="control-label col-sm-2">'.$this->l('Manufacturers').'&nbsp;<a href="javascript:void(0)" class="link-open-dialog-manufacturer" data-group="'.$item['id'].'" title="'.$this->l('Add manufacturer').'"><i class="icon-plus-circle"></i></a></label>
								<div class="col-sm-10">
									<div class="col-sm-12">
										<ul id="manufacturer-list" class="ul-sortable">'.$this->getShowManufactory($params->manufacturers).'</ul>
									</div>		
								</div>
							</div>';
		$html['product_config'] .= '<div class="form-group">
			                    <label class="control-label col-sm-2">'.$this->l('Category').'</label>
							    <div class="col-sm-10">
			                        <div class="col-sm-5">
			                            <select class="form-control" id="group-category" name="categoryId" >'.$this->getCategoryOptions($item['categoryId']).'</select>
			                        </div> 
			                        <label class="control-label col-sm-2">'.$this->l('Type').'</label> 
			                        <div class="col-sm-5">
			                            <select class="form-control" name="cat_type" onchange="groupChangeType(this.value)">'.$this->getCategoryTypeOptions($item['cat_type']).'</select>
			                        </div>                                                                         
			                    </div>
			                </div>';
		$html['product_config'] .= '<div class="group-type-auto group-type" style="display:'.($item['cat_type'] == 'auto' ? 'block' : 'none').'">
										<div class="form-group">
											<label class="control-label col-sm-2">'.$this->l('Only Sale').'</label>
											<div class="col-sm-10">
												<div class="col-sm-5">
													<select name="on_sale">'.$this->getOnSaleOptions($item['on_sale']).'</select>
												</div>
												<label class="control-label col-sm-2">'.$this->l('Only New').'</label>
												<div class="col-sm-5 "><select name="on_new">'.$this->getOnNewOptions($item['on_new']).'</select></div>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-2">'.$this->l('Only Discount').'</label>
											<div class="col-sm-10">
												<div class="col-sm-5"><select name="on_discount">'.$this->getOnDiscountOptions($item['on_discount']).'</select></div>
												<label class="control-label col-sm-2">'.$this->l('Condition').'</label>
												<div class="col-sm-5 "><select name="on_condition">'.self::getOnConditionOptions($item['on_condition']).'</select></div>
											</div>
										</div>
										
										
										<div class="form-group">
											<label class="control-label col-sm-2">'.$this->l('Order by').'</label>
											<div class="col-sm-10">
												<div class="col-sm-5"><select name="order_by">'.$this->getOrderByOptions($item['order_by']).'</select></div>
												<label class="control-label col-sm-2">'.$this->l('Order way').'</label>
												<div class="col-sm-5 "><select name="order_way">'.$this->getOrderWayOptions($item['order_way']).'</select></div>
											</div>
										</div>
										
										<div class="form-group">
											<label class="control-label col-sm-2">'.$this->l('Max item').'</label>
											<div class="col-sm-10">
												<div class="col-sm-5"><input type="text" name="max_item" value="'.$item['max_item'].'" class="form-control" /></div>
												
											</div>
										</div>										
									</div>';
		$html['product_config'] .= '<div class="group-type group-type-manual" style="display:'.($item['cat_type'] == 'manual' ? 'block' : 'none').'">								
										<div class="form-group">
											<div class="col-sm-12">
												<label class="control-label col-sm-2">'.$this->l('Product list').'&nbsp;<a href="javascript:void(0)" class="link-open-dialog-manual-product" data-group="'.$item['id'].'" title="'.$this->l('Add product').'"><i class="icon-plus-circle"></i> </a></label>
												<div class="col-sm-10">
													<ul id="groupmanual-product-list" class="ul-sortable">'.$this->getGroupCategoryProduct($params->products).'</ul>												
												</div>
											</div>
										</div>
									</div>';
		
		
		return $html;
	}
	
	protected function getShowManufactory($manufactories=array()){		
		$result = '';
		if($manufactories){
			foreach($manufactories as $manufactory){
				$manufactoryName = ManufacturerCore::getNameById($manufactory);
				$result .= '<li id="manufacturer-'.$manufactory.'">
								<input type="hidden" class="manufactories" name="manufacturers[]" value="'.$manufactory.'" />
								<span>'.$manufactoryName.'</span>
								<a class="link-trash-manufacturer pull-right" data-id="'.$manufactory.'"><i class="icon-trash "></i></a>
							</li>';
			}
		}
		return $result;
	}
	protected function getShowFeatures($features=array()){		
		$result = '';
		if($features){
			foreach($features as $feature){
				
				$result .= '<li id="feature-'.$feature.'">
								<input type="hidden" class="feature_selected" name="features[]" value="'.$feature.'" />
								<span>'.self::$features[$feature].'</span>
								<a class="link-trash-feature pull-right" data-id="'.$feature.'"><i class="icon-trash "></i></a>
							</li>';
			}
		}
		return $result;
	}
	protected function getGroupCategoryProduct($products=array()){
		
				
		$result = '';
		if($products){
			foreach($products as $productId){
				$productName = Product::getProductName($productId);
				$result .= '<li id="manual-product-'.$productId.'">
								<input type="hidden" class="manual_product_id" name="product_ids[]" value="'.$productId.'" />
								<span>'.$productName.'</span>
								<a class="manual-product-delete pull-right" data-id="'.$productId.'"><i class="icon-trash "></i></a>
							</li>';
			}
		}
		return $result;
	}

	function getItemByLang($id, $langId=0, $shopId=0){
		if(!$langId) $langId = Context::getContext()->language->id;
        if(!$shopId) $shopId = Context::getContext()->shop->id;
		$itemLang = DB::getInstance()->getRow("Select name, banner, banner_link, banner_size From "._DB_PREFIX_."groupcategory_item_lang Where itemId = $id AND `id_lang` = '$langId' AND `id_shop` = '$shopId'" );
		if(!$itemLang) $itemLang = array('name'=>'', 'banner'=>'', 'banner_link'=>'', 'banner_size'=>'');
		return $itemLang;
	}
	function ovicRenderItemForm($id, $groupId){
		
		$item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."groupcategory_items Where id = $id");
		$parentCategory = DB::getInstance()->getValue("Select categoryId From "._DB_PREFIX_."groupcategory_groups Where id = ".$groupId);
		if(!$parentCategory) $parentCategory = 0;		
		if(!$item){
			$item = array(
				'id'=>0, 
				'groupId'=>0, 
				'categoryId'=>0,
				'cat_type'		=>	'auto',
				'order_by'		=>	'position',
				'order_way'		=>	'desc',
				'on_condition'	=>	'all',
				'on_sale'		=>	2,
				'on_new'		=>	2,
				'on_discount'	=>	2,
				'params'		=>	'',
				'is_cache'		=>	1,
				'max_item'		=>	12, 
				'maxItem'=>12, 
				'ordering'=>1, 
				'status'=>1,
				);		
				$params = new stdClass();
				$params->products = array();
		}else{
			if($item['params']) $params = Tools::jsonEncode($item['params']);
			else
				$params = new stdClass();
				$params->products = array();
		}
		$langActive = '<input type="hidden" id="itemLangActive" value="0" />';
		$languages = $this->getAllLanguage();
		$inputName = '';
		$inputBanner = '';
		$inputBannerLink = '';
		$inputHtml = '';
		if($languages){
			foreach ($languages as $key => $language) {				
				$itemLang = $this->getItemByLang($id, $language->id);
				if($language->active == '1'){
					$langActive = '<input type="hidden" id="itemLangActive" value="'.$language->id.'" />';
					$inputName .= '<input type="text" value="'.$itemLang['name'].'" name="names[]"  class="form-control item-lang-'.$language->id.'" />';
					$inputBanner .= '<input type="text" value="'.$itemLang['banner'].'" name="banners[]" id="itemBanner-'.$language->id.'" class="form-control item-lang-'.$language->id.'"  />';
					$inputBannerLink .= '<input type="text" value="'.$itemLang['banner_link'].'" name="links[]" class="form-control item-lang-'.$language->id.'" />';
				}else{
					$inputName .= '<input type="text" value="'.$itemLang['name'].'" name="names[]"  class="form-control item-lang-'.$language->id.'" style="display:none" />';
					$inputBanner .= '<input type="text" value="'.$itemLang['banner'].'" name="banners[]" id="itemBanner-'.$language->id.'" class="form-control item-lang-'.$language->id.'" style="display:none" />';
					$inputBannerLink .= '<input type="text" value="'.$itemLang['banner_link'].'" name="links[]" class="form-control item-lang-'.$language->id.'" style="display:none" />';										
				}				
			}
		}		
		$langOptions = $this->getLangOptions();
		$html = '';
		$html .= '<input type="hidden" name="itemId" value="'.$item['id'].'" />';		
		$html .= $langActive;
		$html .= '<input type="hidden" name="action" value="saveItem" />';
		$html .= '<input type="hidden" name="secure_key" value="'.$this->secure_key.'" />';
		$html .= '<div class="form-group">
                    <label class="control-label col-sm-2 required">'.$this->l('Item Name').'</label>
				    <div class="col-sm-10">
                        <div class="col-sm-10">'.$inputName.'</div>
                        <div class="col-sm-2">
                            <select class="item-lang" onchange="itemChangeLanguage(this.value)">'.$langOptions.'</select>
                        </div>                                                                        
                    </div>
                </div>';
        $html .= '<div class="form-group">
	                    <label class="control-label col-sm-2">'.$this->l('Cache').'</label>
					    <div class="col-sm-10">
	                        <div class="col-sm-4">
	                            <span class="switch prestashop-switch fixed-width-lg" id="item-is_cache">
	                                <input type="radio" value="1" class="item-is_cache" '.($item['is_cache'] == 1 ? 'checked="checked"':'').'  id="item-is_cache_on" name="is_cache" />
	            					<label for="item-is_cache_on">Yes</label>
	            				    <input type="radio" value="0" class="item-is_cache" '.($item['is_cache'] == 0 ? 'checked="checked"':'').' id="item-is_cache_off" name="is_cache" />
	            					<label for="item-is_cache_off">No</label>
	                                <a class="slide-button btn"></a>
	            				</span>
	                        </div> 
	                        <label class="control-label col-sm-2">'.$this->l('Banner').'</label>		                    
	                        <div class="col-sm-4">
	                            <div class="input-group">
	                                '.$inputBanner.'
	                                <span class="input-group-btn">
	                                    <button id="item-image-uploader" type="button" class="btn btn-default"><i class="icon-folder-open"></i></button>
	                                </span>
	                            </div> 
	                        </div>
	                        <div class="col-sm-2">
	                            <select class="item-lang" onchange="itemChangeLanguage(this.value)">'.$langOptions.'</select>
	                        </div>                                                      
	                    </div>
	                </div>';        
		$html .= '<div class="form-group">
                    <label class="control-label col-sm-2">'.$this->l('Link banner').'</label>
				    <div class="col-sm-10">
                        <div class="col-sm-10">
                            '.$inputBannerLink.'
                        </div>
                        <div class="col-sm-2">
                            <select class="item-lang" onchange="itemChangeLanguage(this.value)">'.$langOptions.'</select>
                        </div>                                                                                                
                    </div>
                </div>';
		$html .= '<div class="form-group">
                    <label class="control-label col-sm-2">'.$this->l('Category').'</label>
				    <div class="col-sm-10">
                        <div class="col-sm-5">
                            <select class="form-control" name="categoryId">'.$this->getCategoryOptions($item['categoryId']).'</select>
                        </div> 
                        <label class="control-label col-sm-2">'.$this->l('Type').'</label> 
                        <div class="col-sm-5">
                            <select class="form-control" name="cat_type" onchange="itemChangeType(this.value)">'.$this->getCategoryTypeOptions($item['cat_type']).'</select>
                        </div>                                                                       
                    </div>
                </div>';
		$html .= '<div class="item-type-auto item-type" style="display:'.($item['cat_type'] == 'auto' ? 'block' : 'none').'">
						<div class="form-group">
							<label class="control-label col-sm-2">'.$this->l('Only Sale').'</label>
							<div class="col-sm-10">
								<div class="col-sm-5">
									<select name="on_sale">'.$this->getOnSaleOptions($item['on_sale']).'</select>
								</div>
								<label class="control-label col-sm-2">'.$this->l('Only New').'</label>
								<div class="col-sm-5 "><select name="on_new">'.$this->getOnNewOptions($item['on_new']).'</select></div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">'.$this->l('Only Discount').'</label>
							<div class="col-sm-10">
								<div class="col-sm-5"><select name="on_discount">'.$this->getOnDiscountOptions($item['on_discount']).'</select></div>
								<label class="control-label col-sm-2">'.$this->l('Condition').'</label>
								<div class="col-sm-5 "><select name="on_condition">'.self::getOnConditionOptions($item['on_condition']).'</select></div>
							</div>
						</div>
						
						
						<div class="form-group">
							<label class="control-label col-sm-2">'.$this->l('Order by').'</label>
							<div class="col-sm-10">
								<div class="col-sm-5"><select name="order_by">'.$this->getOrderByOptions($item['order_by']).'</select></div>
								<label class="control-label col-sm-2">'.$this->l('Order way').'</label>
								<div class="col-sm-5 "><select name="order_way">'.$this->getOrderWayOptions($item['order_way']).'</select></div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-sm-2">'.$this->l('Max item').'</label>
							<div class="col-sm-10">
								<div class="col-sm-5"><input type="text" name="max_item" value="'.$item['max_item'].'" class="form-control" /></div>								
							</div>
						</div>										
					</div>';
		$html .= '<div class="item-type item-type-manual" style="display:'.($item['cat_type'] == 'manual' ? 'block' : 'none').'">								
						<div class="form-group">
							<div class="col-sm-12">
								<label class="control-label col-sm-2">'.$this->l('Product list').'&nbsp;<a href="javascript:void(0)" class="link-open-dialog-manual-product" data-group="'.$item['id'].'" title="'.$this->l('Add product').'"><i class="icon-plus-circle"></i> </a></label>
								<div class="col-sm-10">
									<ul id="itemmanual-product-list" class="ul-sortable">'.$this->getGroupCategoryProduct($params->products).'</ul>												
								</div>
							</div>
						</div>
					</div>';				
		return $html;
	}
	protected function getCurrentUrl($excls=array()){
		$pageURL = 'http';		
     	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
     	$pageURL .= "://";
     	if ($_SERVER["SERVER_PORT"] != "80") {
    		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    	} else {
    		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
     	}
     	return $pageURL;
	}
	
	public function getContent()
	{
		$action = Tools::getValue('action', 'view');
		if($action == 'view'){
			//$this->updateVersions();
		   	$langId = Context::getContext()->language->id;		
	        $shopId = Context::getContext()->shop->id;
			$this->context->controller->addJquery();
			$this->context->controller->addJQueryUI('ui.sortable');
			$this->context->controller->addjQueryPlugin(array('tablednd','colorpicker'));
			$this->context->controller->addJS(array(
				($this->_path).'js/back-end/common.js',
				($this->_path).'js/back-end/ajaxupload.3.5.js',
				($this->_path).'js/back-end/jquery.serialize-object.min.js',
			));                	
	        $this->context->controller->addCSS(($this->_path).'css/back-end/style.css');
	        $this->context->smarty->assign(array(
	            'baseModuleUrl'			=>	__PS_BASE_URI__.'modules/'.$this->name,
	            'currentUrl'			=>	$this->getCurrentUrl(),
	            'moduleId'				=>	$this->id,            
				'langId'				=>	$langId,
	            'iso'					=>	$this->context->language->iso_code,
	            'ad'					=>	$ad = dirname($_SERVER["PHP_SELF"]),
	            'langOptions'			=>	$this->getLangOptions(),
	            'secure_key'			=>	$this->secure_key,
	            'style_tpl'				=>	dirname(__FILE__).'/views/templates/admin/style.tpl',
	            'group_tpl'				=>	dirname(__FILE__).'/views/templates/admin/group.tpl',
	            'dialog_product'    	=>  dirname(__FILE__).'/views/templates/admin/dialog.product.tpl',
	            'dialog_feature'    	=>  dirname(__FILE__).'/views/templates/admin/dialog.feature.tpl',
	            'dialog_manufacturer'   =>  dirname(__FILE__).'/views/templates/admin/dialog.manufacturer.tpl',
	            'listStyles'			=>	$this->getAllStyle(),
	            'listGroup'				=>	$this->getAllGroup(),
	            'groupForm'				=>	$this->ovicRenderGroupForm(),
	            'itemForm' 				=>	$this->ovicRenderItemForm(0, 0),	
	        ));
			return $this->display(__FILE__, 'views/templates/admin/modules.tpl');
		}else{
			if(method_exists ($this, $action)){			 
				$this->$action();
			}else{
				$response = new stdClass();
				if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
		            $response->status = 0;
            		$response->msg = $this->l("Method ".$action."() not found!.");
					die(Tools::jsonEncode($response));
		        }else{
		        	die($this->l("Method ".$action."() not found!."));
		        }
			}
		}
		
	}
	public function updateVersions(){
		$module = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("Select * From "._DB_PREFIX_."module Where name = '".$this->name."'");		
		if($module['version'] == '1.0'){
			/*
			$checkUpdate1_0 = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."groupcategory_groups");
	        if($checkUpdate1_0){
	            if(!isset($checkUpdate['id_shop'])){
	            	$langId = Context::getContext()->language->id;		
        			$shopId = Context::getContext()->shop->id;
	                DB::getInstance()->execute("ALTER TABLE "._DB_PREFIX_."groupcategory_groups ADD `id_shop` INT(6) unsigned NOT NULL AFTER `id`");
	                DB::getInstance()->execute("ALTER TABLE "._DB_PREFIX_."groupcategory_styles ADD `id_shop` INT(6) unsigned NOT NULL AFTER `id`");
	                DB::getInstance()->execute("Update "._DB_PREFIX_."groupcategory_groups Set `id_shop` = ".$shopId);
	                DB::getInstance()->execute("Update "._DB_PREFIX_."groupcategory_styles Set `id_shop` = ".$shopId);
	            }
	        }
			*/
			$this->version = '1.1';
			$module['version'] = '1.1';
			Db::getInstance()->update('module', array('version'=>$module['version']), "id_module=".$module['id_module']);
		}
		if($module['version'] == '1.1'){
			Db::getInstance(_PS_USE_SQL_SLAVE_)->execute("ALTER TABLE  `"._DB_PREFIX_."groupcategory_groups` ADD  `cat_type` ENUM(  'auto',  'manual' ) NOT NULL DEFAULT  'auto' AFTER  `categoryId` ,
															ADD  `order_by` ENUM(  'seller',  'price',  'discount',  'date_add',  'position',  'review',  'view' ) NOT NULL DEFAULT  'position' AFTER  `cat_type` ,
															ADD  `order_way` ENUM(  'asc',  'desc' ) NOT NULL DEFAULT  'desc' AFTER  `order_by` ,
															ADD  `on_condition` ENUM(  'all',  'new',  'used',  'refurbished' ) NOT NULL DEFAULT  'all' AFTER  `order_way` ,
															ADD  `on_sale` TINYINT( 2 ) UNSIGNED NOT NULL DEFAULT  '2' AFTER  `on_condition` ,
															ADD  `on_new` TINYINT( 2 ) UNSIGNED NOT NULL DEFAULT  '2' AFTER  `on_sale` ,
															ADD  `on_discount` TINYINT( 2 ) UNSIGNED NOT NULL DEFAULT  '2' AFTER  `on_new` ,
															ADD  `max_item` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT  '12' AFTER `on_discount` ,
															ADD  `params` VARCHAR( 1000 ) NOT NULL AFTER  `max_item` ,
															ADD  `is_cache` TINYINT NOT NULL DEFAULT  '1' AFTER  `params`");			
			Db::getInstance(_PS_USE_SQL_SLAVE_)->execute("ALTER TABLE  `"._DB_PREFIX_."groupcategory_items` ADD  `cat_type` ENUM(  'auto',  'manual' ) NOT NULL DEFAULT  'auto' AFTER  `categoryId` ,
															ADD  `order_by` ENUM(  'seller',  'price',  'discount',  'date_add',  'position',  'review',  'view' ) NOT NULL DEFAULT  'position' AFTER  `cat_type` ,
															ADD  `order_way` ENUM(  'asc',  'desc' ) NOT NULL DEFAULT  'desc' AFTER  `order_by` ,
															ADD  `on_condition` ENUM(  'all',  'new',  'used',  'refurbished' ) NOT NULL DEFAULT  'all' AFTER  `order_way` ,
															ADD  `on_sale` TINYINT( 2 ) UNSIGNED NOT NULL DEFAULT  '2' AFTER  `on_condition` ,
															ADD  `on_new` TINYINT( 2 ) UNSIGNED NOT NULL DEFAULT  '2' AFTER  `on_sale` ,
															ADD  `on_discount` TINYINT( 2 ) UNSIGNED NOT NULL DEFAULT  '2' AFTER  `on_new` ,
															ADD  `max_item` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT  '12' AFTER `on_discount` ,
															ADD  `params` VARCHAR( 1000 ) NOT NULL AFTER  `max_item` ,
															ADD  `is_cache` TINYINT NOT NULL DEFAULT  '1' AFTER  `params`");
			
			$items = DB::getInstance()->executeS("Select * From "._DB_PREFIX_."groupcategory_groups");
			if($items){
				$params = new stdClass();			
				foreach($items as $item){					
					if($item['types']){
						$types = Tools::jsonDecode($item['types']);
						if($types){
							foreach($types as &$type){
								if($type == 'saller') $type = 'seller';
							}
							$params->features = $types;
						}else{
							$params->features = array();
						}												
					}else{
						$params->features = array();
					}
					if($item['manufactureConfig']){
						$manufactureConfig = Tools::jsonDecode($item['manufactureConfig']);								
						$params->manufacturers = $manufactureConfig->ids;
					}else{
						$params->manufacturers = array();
					}		
					if($item['itemConfig']){
						$itemConfig = Tools::jsonDecode($item['itemConfig']);								
						$max_item = (int)$itemConfig->countItem;
					}else{
						$max_item = 12;
					}
					$params->products = array();
					Db::getInstance()->update('groupcategory_groups', array('max_item'=>$max_item, 'params'=>Tools::jsonEncode($params)), "id=".$item['id']);
				}
				$this->clearCache();
			}
			
			$items = DB::getInstance()->executeS("Select * From "._DB_PREFIX_."groupcategory_items");
			if($items){
				$params = new stdClass();			
				foreach($items as $item){	
					$params->products = array();
					$max_item = $item['maxItem'];
					Db::getInstance()->update('groupcategory_items', array('max_item'=>$max_item, 'params'=>Tools::jsonEncode($params)), "id=".$item['id']);			
				}
			}
			$this->version = '2.0';
			$module['version'] = '2.0';
			Db::getInstance()->update('module', array('version'=>$module['version']), "id_module=".$module['id_module']);
			$this->clearCache();
		}
		return true;
	}
	public function styleEdit(){
        $itemId = intval($_POST['itemId']);
        $response = new stdClass();
        if($itemId >0){
            $item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."groupcategory_styles Where id = ".$itemId);            
            if($item){
                $response = json_decode($item['params']);                
                $response->status = '1';
                $response->id = $item['id'];
                $response->name = $item['name'];
            }else $response->status = '0';    
        }else $response->status = '0';
       die(Tools::jsonEncode($response)); 
    }
	public function deleteStyle(){
        $itemId = intval($_POST['itemId']);
        $response = new stdClass();
        if($itemId >0){             
            $check = DB::getInstance()->getValue("Select id From "._DB_PREFIX_."groupcategory_groups Where style_id = ".$itemId);                        
            if(!$check){
                if(DB::getInstance()->execute("Delete From "._DB_PREFIX_."groupcategory_styles Where id = ".$itemId)){
                    $cssFile = dirname(__FILE__).'/css/front-end/style-'.$itemId.'.css';
                    unlink($cssFile);
                    $response->status = '1';
                    $response->msg = 'Delete Success!';
					$this->clearCache();
                }else{
                    $response->status = '0';
                    $response->msg = 'Delete Not Success!';  
                }
            }else{
                $response->status = '0';
                $response->msg = 'Is style being used!';
            }             
        }else{
            $response->status = '0';
            $response->msg = 'Delete Not Success!';
        } 
		
       die(Tools::jsonEncode($response));
    }
	public function loadItem(){
        $langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;
        $itemId = intval($_POST['itemId']);
		$groupId = intval($_POST['groupId']);
        $response = new stdClass();
		$response->form = $this->ovicRenderItemForm($itemId, $groupId);
		$response->status = 1;		
        die(Tools::jsonEncode($response));
    }
	public function updateGroupOrdering(){
        $ids = $_POST['ids'];        
        if($ids){
            $strIds = implode(', ', $ids);            
            $minOrder = DB::getInstance()->getValue("Select Min(ordering) From "._DB_PREFIX_."groupcategory_groups Where id IN ($strIds)");            
            foreach($ids as $i=>$id){
                DB::getInstance()->query("Update "._DB_PREFIX_."groupcategory_groups Set ordering=".($minOrder + $i)." Where id = ".$id);                
            }
            $this->clearCache();			
        }		
        die(Tools::jsonEncode($this->l('Update group ordering success!')));
    }
	public function deleteGroup(){
        $itemId = intval($_POST['itemId']);
        $db = DB::getInstance();
        $group = $db->getRow("Select * From "._DB_PREFIX_."groupcategory_groups Where id = ".$itemId);        
        $response = new stdClass();
        if($group){
            if($db->execute("Delete From "._DB_PREFIX_."groupcategory_groups Where id = ".$itemId)){
            	if($group['icon'] && file_exists($this->pathImage.'icons/'.$group['icon'])) unlink($this->pathImage.'icons/'.$group['icon']);
            	$groupLangs = DB::getInstance()->executeS("Select banner From "._DB_PREFIX_."groupcategory_group_lang Where group_id = ".$itemId);
				if($groupLangs){
					foreach($groupLangs as $groupLang){
						if($groupLang['banner'] && file_exists($this->pathImage.'banners/'.$groupLang['banner'])) unlink($this->pathImage.'banners/'.$groupLang['banner']);
					}
				} 
				$db->execute("Delete From "._DB_PREFIX_."groupcategory_group_lang Where group_id = ".$itemId);
				$items = DB::getInstance()->executeS("Select banner From "._DB_PREFIX_."groupcategory_item_lang Where itemId IN (Select id From "._DB_PREFIX_."groupcategory_items Where groupId = ".$itemId.")");
				if($items){
					foreach($items as $item){
						if($item['banner'] && file_exists($this->pathImage.'banners/'.$item['banner'])) unlink($this->pathImage.'banners/'.$item['banner']);
						
					}
				}
				DB::getInstance()->execute("Delete From "._DB_PREFIX_."groupcategory_item_lang Where itemId IN (Select id From "._DB_PREFIX_."groupcategory_items Where groupId = ".$itemId.")");
				DB::getInstance()->execute("Delete From "._DB_PREFIX_."groupcategory_items Where groupId = ".$itemId);
                $response->status = '1';
                $response->msg = $this->l('Delete Group Success!');
				$this->clearCache();
            }else{
                $response->status = '0';
                $response->msg = $this->l('Delete Group not Success!');
            }

        }else{
            $response->status = '0';
            $response->msg = $this->l('Group not found!');
        }
        die(Tools::jsonEncode($response));
    }
	public function changGroupStatus(){
		$itemId = intval($_POST['itemId']);
		$value = intval($_POST['value']);		
		$response = new stdClass();
		if($value == '1'){
			DB::getInstance()->execute("Update "._DB_PREFIX_."groupcategory_groups Set `status` = 0 Where id = ".$itemId);			
		}else{
			DB::getInstance()->execute("Update "._DB_PREFIX_."groupcategory_groups Set `status` = 1 Where id = ".$itemId);			
		}
		$response->status = 1;
		$response->msg = $this->l('Update status success');
		$this->clearCache();
		die(Tools::jsonEncode($response));
	}
	
	public function loadAllGroup(){   
        $response = new stdClass();
        $response->status = '1';
        $response->data = $this->getAllGroup();
        die(Tools::jsonEncode($response));
    }
	public function loadGroup(){
        $itemId = intval($_POST['itemId']);	
        $response = new stdClass();
        $data = new stdClass();
        if($itemId >0){
        	$html = $this->ovicRenderGroupForm($itemId);
        	$response->group_config = $html['config'];
			$response->product_config = $html['product_config'];
			$response->status = 1;			
			$response->smg = '';			
        }else{
            $response->status = '0';
            $response->msg = 'Item not found!';
        }
        die(Tools::jsonEncode($response));
    }
    
	// save group
    public function saveGroup(){    	
    	$langId = Context::getContext()->language->id;		
	    $shopId = Context::getContext()->shop->id;
		$params = new stdClass();
		$params->features = array();
		$params->manufacturers = array();
		$params->products = array();
		
		$languages = $this->getAllLanguage();		
        $db= DB::getInstance();
        $groupId = intval($_POST['groupId']);		
		$names = Tools::getValue('names', array());
		$icon = Tools::getValue('groupIcon', '');
		$banners = Tools::getValue('banners', array());
		$links = Tools::getValue('links', array());
		$position_name = Tools::getValue('position_name', '');		
		$style_id = intval($_POST['style_id']);
		
		$params->features = Tools::getValue('features', array());
		$params->manufacturers = Tools::getValue('manufacturers', array());
		$params->products = Tools::getValue('product_ids', array());
		
		$categoryId = intval($_POST['categoryId']);
		$cat_type = Tools::getValue('cat_type', 'auto');
		$on_sale = Tools::getValue('on_sale', 2);
		$on_new = Tools::getValue('on_new', 2);
		$on_discount = Tools::getValue('on_discount', 'all');
		$on_condition = Tools::getValue('on_condition', 'all');
		$max_item = Tools::getValue('max_item', 4);
		$order_by = Tools::getValue('order_by', 'position'); 		
		$order_way = Tools::getValue('order_way', 'desc');
		
		
		$layout = Tools::getValue('layout', 'default');
		$is_cache = Tools::getValue('is_cache', 1);
		
		
        //require_once(dirname(__FILE__).'/GroupCategoryThumb.php');
        //$img = new GroupCategoryThumb();
        $response = new stdClass();		
        if($groupId == 0){
        	$maxOrdering = $db->getValue("Select MAX(ordering) From "._DB_PREFIX_."groupcategory_groups Where `position_name` = '$position_name'");
	   		if($maxOrdering >0) $maxOrdering++;
	   		else $maxOrdering = 1;
			$arrInsert = array(
				'position_name'		=>	$position_name,
				'id_shop'		=>	$shopId,
				'categoryId'		=>	$categoryId,
				'cat_type'		=>	$cat_type,
				'order_by'		=>	$order_by,
				'order_way'		=>	$order_way,
				'on_condition'		=>	$on_condition,
				'on_sale'		=>	$on_sale,
				'on_new'		=>	$on_new,
				'on_discount'		=>	$on_discount,
				'max_item'		=>	$max_item,
				'params'		=>	Tools::jsonEncode($params),
				'is_cache'		=>	$is_cache,
				'style_id'		=>	$style_id,
				'layout'		=>	$layout,
				'icon'		=>	'',
				'ordering'		=>	$maxOrdering,
				'status'		=>	1,				
			);
			if($icon){
				if(strpos($icon, '.') === false){
					$arrInsert['icon'] = $icon;
				}else{
					if(strpos($icon, 'http') !== false){
						$arrInsert['icon'] = $icon;
					}else{
						if(file_exists($this->pathImage.'temps/'.$icon)){
							if(copy($this->pathImage.'temps/'.$icon, $this->pathImage.'icons/'.$icon)){
								$arrInsert['icon'] = $icon;
							}
							unlink($this->pathImage.'temps/'.$icon);
						}	
					}	
				}
			}  
            if(Db::getInstance(_PS_USE_SQL_SLAVE_)->insert('groupcategory_groups', $arrInsert)){
                $insertId = DB::getInstance()->Insert_ID();				
				if($languages){
                	$insertDatas = array();
					$defaultName = '';
					$defaultBanner = '';
					$defaultBannerLink = '';
                	
                	foreach($languages as $index=>$language){
                		$name = pSQL($names[$index]);
						$bannerLink = pSQL($links[$index]);
						
						if(!$defaultName) $defaultName = $name;
						else
							if(!$name) $name = $defaultName;
						
						if(!$defaultBannerLink) $defaultBannerLink = $bannerLink;
						else
							if(!$bannerLink) $bannerLink = $defaultBannerLink;
						
						$bannerName = $banners[$index];			
                		if($bannerName && file_exists($this->pathImage.'temps/'.$bannerName)){
                			if(copy($this->pathImage.'temps/'.$bannerName, $this->pathImage.'banners/'.$bannerName)){
								if(!$defaultBanner) $defaultBanner = $bannerName;
                			}else{
                				if($defaultBanner && file_exists($this->pathImage.'banners/'.$defaultBanner)){
                					$fileType = strtolower(pathinfo($defaultBanner, PATHINFO_EXTENSION));
                					$bannerName = Tools::encrypt($defaultBanner.$index).'.'.$fileType;
									if(!copy($this->pathImage.'banners/'.$defaultBanner, $this->pathImage.'banners/'.$bannerName))
										$bannerName = '';
                				}else 
									$bannerName = '';
                			}
                			/*	
		                    $path_info = pathinfo($banners[$index]);								
		                    $ext = $path_info['extension'];
		                    $bannerName = $insertId.'-'.$language->id.'-'.$shopId.'-banner.'.$ext;
							$sourceSize = getimagesize ($module->pathTemp.$banners[$index]);
							if($sourceSize[0] >281){
								@$img->pCreate($module->pathTemp.$banners[$index], 281, null, 100, true);	
								@$img->pSave($module->pathBanner.$bannerName);
								
							}else{
								copy($module->pathTemp.$banners[$index], $module->pathBanner.$bannerName);
							}	
							unlink($module->pathTemp.$banners[$index]);
							$size =  getimagesize ($module->pathBanner.$bannerName);
							$params = new stdClass();
							$params->width = $size[0];
							$params->height = $size[1];
							$params->w_per_h = round(($size[0]/$size[1]), 3);
							if(!$defaultBanner){
								$defaultBanner['banner'] = $bannerName;
								$defaultBanner['params'] = 	$params;	
							}
							*/						
														              
		                }else{
		                	if($defaultBanner && file_exists($this->pathImage.'banners/'.$defaultBanner)){
            					$fileType = strtolower(pathinfo($defaultBanner, PATHINFO_EXTENSION));
            					$bannerName = Tools::encrypt($defaultBanner.$index).'.'.$fileType;
								if(!copy($this->pathImage.'banners/'.$defaultBanner, $this->pathImage.'banners/'.$bannerName))
									$bannerName = '';
            				}else 
								$bannerName = '';
		                }
						$insertDatas[] = array('group_id'=>$insertId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'name'=>$name, 'banner'=>$bannerName, 'banner_link'=>$bannerLink, 'banner_size'=>'') ;
						
                	}
					if($insertDatas) Db::getInstance(_PS_USE_SQL_SLAVE_)->insert('groupcategory_group_lang', $insertDatas);
                }
				$this->clearCache();
                $response->status = 1;
                $response->msg = $this->l("Add new group successful!");
            }else{
                $response->status = 0;
                $response->msg = $this->l("Add new group not success!");
            }
        }else{			
            $item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."groupcategory_groups Where id = ".$groupId);
            if($item){
            	
            	$arrUpdate = array(
                	'position_name'	=>	$position_name,
					'categoryId'		=> 	$categoryId,
					'cat_type'	=>	$cat_type,
					'order_by'	=>	$order_by,
					'order_way'			=>	$order_way,
					'on_condition'	=>	$on_condition,
					'on_sale'			=>	$on_sale,
					'on_new'		=>	$on_new,
					'on_discount'		=>	$on_discount,
					'max_item'		=>	$max_item,
					'params'		=>	Tools::jsonEncode($params),
					'is_cache'		=>	$is_cache,
					'style_id'		=>	$style_id,
					'layout'		=>	$layout,
					'icon'			=>	$item['icon'],
				);
				if($icon){
					if(strpos($icon, '.') === false){
						$arrUpdate['icon'] = $icon;
					}else{
						if(strpos($icon, 'http') !== false){
							$arrUpdate['icon'] = $icon;
						}else{
							if(file_exists($this->pathImage.'temps/'.$icon)){
								if(copy($this->pathImage.'temps/'.$icon, $this->pathImage.'icons/'.$icon)){
									$arrUpdate['icon'] = $icon;
								}
								unlink($this->pathImage.'temps/'.$icon);
							}	
						}	
					}
				}  
				Db::getInstance(_PS_USE_SQL_SLAVE_)->update('groupcategory_groups', $arrUpdate, '`id`='.$groupId);                      
				if($languages){
                	$insertDatas = array();
                	$defaultName = '';
					$defaultBanner = '';
					$defaultBannerLink = '';
                	foreach($languages as $index=>$language){
                		$check = DB::getInstance()->getValue("Select group_id From "._DB_PREFIX_."groupcategory_group_lang Where group_id = ".$groupId." AND `id_lang` = ".$language->id." AND id_shop = ".$shopId);						                		
                		$name = pSQL($names[$index]);
						$bannerLink = pSQL($links[$index]);
						$bannerName = $banners[$index];
                		if($bannerName && file_exists($this->pathImage.'temps/'.$bannerName)){
		                    if(!copy($this->pathImage.'temps/'.$bannerName, $this->pathImage.'banners/'.$bannerName)){
								$bannerName = '';
                			}
		                }
						if($check){
	                    	Db::getInstance(_PS_USE_SQL_SLAVE_)->execute("Update "._DB_PREFIX_."groupcategory_group_lang Set `name` = '".$name."', `banner` = '$bannerName', `banner_link` = '".$bannerLink."', `banner_size` = '' Where `group_id` = $groupId AND `id_lang` = ".$language->id." AND `id_shop` = ".$shopId);	
	                    }else{
	                    	$insertDatas[] = array('group_id'=>$groupId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'name'=>$name, 'banner'=>$bannerName, 'banner_link'=>$bannerLink, 'banner_size'=>'') ;
	                    }
                	}
					if($insertDatas) Db::getInstance(_PS_USE_SQL_SLAVE_)->insert('groupcategory_group_lang', $insertDatas);
                }
				$this->clearCache();
                $response->status = 0;
                $response->msg = $this->l("Update successful!");
            }else{
                $response->status = 0;
                $response->msg = $this->l("Item not found!");
            }            
        }
        die(Tools::jsonEncode($response));
    }
	public function getCategoryLangNameById($id, $langId, $shopId){
        $name =  DB::getInstance()->getValue("Select name From "._DB_PREFIX_."category_lang Where id_category = $id AND `id_shop` = '$shopId' AND `id_lang` = '$langId'");
        if($name) return $name;
        else return '';   
    }
	public function getGroupLangById($id, $langId, $shopId){
        $groupLang =  DB::getInstance()->getRow("Select * From "._DB_PREFIX_."groupcategory_group_lang Where group_id = $id AND `id_shop` = '$shopId' AND `id_lang` = '$langId'");
        if($groupLang) return $groupLang;
        else return null;
    }
    public function getItemLangById($id, $langId, $shopId){
        $itemLang =  DB::getInstance()->getRow("Select * From "._DB_PREFIX_."groupcategory_item_lang Where itemId = $id AND `id_shop` = '$shopId' AND `id_lang` = '$langId'");
        if($itemLang) return $itemLang;
        else return null;
    }
	public  function generationCssFile($params, $fileKey){
        $css = file_get_contents(dirname(__FILE__).'/style-tpl.txt');
        $css = str_replace('{#ID#}', $fileKey, $css);
        $keys = array_keys($params);
        if($keys){
            foreach($keys as $key){
                $css = str_replace('{#'.$key.'#}', $params[$key], $css);
            }
        }
        $fh = fopen(dirname(__FILE__)."/css/front-end/style-".$fileKey.".css", 'w');// or die("không th? t?o file ".$this->temp);
		fwrite($fh, $css);				
		fclose($fh);   
        return true;     
    }
	public function changItemStatus(){
		$itemId = intval($_POST['itemId']);
		$value = intval($_POST['value']);		
		$response = new stdClass();
		if($value == '1'){
			DB::getInstance()->execute("Update "._DB_PREFIX_."groupcategory_items Set `status` = 0 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}else{
			DB::getInstance()->execute("Update "._DB_PREFIX_."groupcategory_items Set `status` = 1 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}
		$this->clearCache();
		die(Tools::jsonEncode($response));
	}
	public  function saveStyle(){
	   $response = new stdClass();
       $shopId = Context::getContext()->shop->id;
       $styleId = intval($_POST['styleId']);
       $styleName = DB::getInstance()->escape($_POST['styleName']);//Tools::getValue('styleName', '');
       $arrParams = array();
       $arrParams['backgroundColorHeader'] = Tools::getValue('backgroundColorHeader', '#a6cada');
       if(!$arrParams['backgroundColorHeader']) $arrParams['backgroundColorHeader'] = '#a6cada';
       
       $arrParams['colorBackgroundType'] = Tools::getValue('colorBackgroundType', '#B2D7E8');
       if(!$arrParams['colorBackgroundType']) $arrParams['colorBackgroundType'] = '#B2D7E8';
       
       $arrParams['colorList'] = Tools::getValue('colorList', '#B2D7E8');
       if(!$arrParams['colorList']) $arrParams['colorList'] = '#B2D7E8';
       
       $arrParams['bannerColorFrom'] = Tools::getValue('bannerColorFrom', '#a6cada');
       if(!$arrParams['bannerColorFrom']) $arrParams['bannerColorFrom'] = '#a6cada';
       
       $arrParams['bannerColorTo'] = Tools::getValue('bannerColorTo', '#b2d2de');
       if(!$arrParams['bannerColorTo']) $arrParams['bannerColorTo'] = '#b2d2de';
       $params = json_encode($arrParams);       
       if($styleId == 0){
            $sql = "Insert Into "._DB_PREFIX_."groupcategory_styles (`name`, `id_shop`, `params`) Values ('$styleName', '$shopId', '$params')";
            if(DB::getInstance()->execute($sql)){
                $insertId = DB::getInstance()->Insert_ID();
                $this->generationCssFile($arrParams, $insertId);
                $response->status = '1';
                $response->msg = 'Create new Style Success!';
            }else{
                $response->status = '0';
                $response->msg = 'Create new Style Not Success!';
            }
       }else{
            if(DB::getInstance()->execute("Update "._DB_PREFIX_."groupcategory_styles Set name = '$styleName', `params` = '$params' Where id = ".$styleId)){
                $this->generationCssFile($arrParams, $styleId);
                $response->status = '1';
                $response->msg = 'Update Style Success!';
            }else{
                $response->status = '0';
                $response->msg = 'Update Style Not Success!';
            }
       }
       $this->clearCache();
       die(Tools::jsonEncode($response));
	}
	public function loadAllStyle(){       
        $response = new stdClass();
        $response->status = '1';
        $response->data =  $this->getAllStyle();
        $response->styleOptions = $this->getStyleOptions();
        die(Tools::jsonEncode($response));
    }
	
	public function loadItemsByGroup(){
        $response = new stdClass();      
        $groupId = intval($_POST['groupId']);
        $group = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."groupcategory_groups Where id = ".$groupId);        
        if($group){
            $langId = Context::getContext()->language->id;
            $shopId = Context::getContext()->shop->id;            
            $items = DB::getInstance()->executeS("Select * From "._DB_PREFIX_."groupcategory_items Where groupId = $groupId Order By ordering");
            $response->categoryOptions = $this->getCategoryOptions();
            $response->status = 1;
            $response->msg = '';
            $response->content = '';
            if($items){
                foreach($items as $item){                                       
                    if($item['status'] == "1"){
                        $status = '<a title="Enabled" class="list-action-enable action-enabled lik-item-status" item-id="'.$item['id'].'" value="'.$item['status'].'"><i class="icon-check"></i></a>';
                    }else{
                        $status = '<a title="Disabled" class="list-action-enable action-disabled lik-item-status" item-id="'.$item['id'].'" value="'.$item['status'].'"><i class="icon-check"></i></a>';
                    }
                    $itemLang = $this->getItemLangById($item['id'], $langId, $shopId);
                    $response->content .= '<tr id="it_'.$item['id'].'">
                                                <td>'.$item['id'].'</td>
                                                <td>'.$itemLang['name'].'</td>
                                                <td>'.$this->getCategoryLangNameById($item['categoryId'], $langId, $shopId).'</td>
                                                <td class="pointer dragHandle center" ><div class="dragGroup"><div class="positions">'.$item['ordering'].'</div></div></td>
                                                <td class="center">'.$status.'</td>
                                                <td class="center">
                                                    <a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-item-edit"><i class="icon-edit"></i></a>&nbsp;
                                                    <a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-item-delete"><i class="icon-trash" ></i></a>
                                                </td>
                                            </tr>';
                }            
            }else{
                $response->msg = "Items empty.";
            }                    
        }else{
            $response->status = 0;
            $response->msg = "Group do not exist";
            $response->content = '';
        }
        die(Tools::jsonEncode($response));
    }
    public function updateItemOrdering(){
        $ids = $_POST['ids'];       
        if($ids){
            $strIds = implode(', ', $ids);            
            $minOrder = DB::getInstance()->getValue("Select Min(ordering) From "._DB_PREFIX_."groupcategory_items Where id IN ($strIds)");            
            foreach($ids as $i=>$id){
                DB::getInstance()->query("Update "._DB_PREFIX_."groupcategory_items Set ordering=".($minOrder + $i)." Where id = ".$id);                
            }            
        }
        $this->clearCache();
        die(Tools::jsonEncode('Update group ordering success!'));
    }
	public function deleteItem(){
        $itemId = intval($_POST['itemId']);
        $item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."groupcategory_items Where id = ".$itemId);		
        $response = new stdClass();
        if($item){
            if(DB::getInstance()->execute("Delete From "._DB_PREFIX_."groupcategory_items Where id = ".$itemId)){
            	$itemLangs = DB::getInstance()->executeS("Select banner From "._DB_PREFIX_."groupcategory_item_lang Where itemId = ".$itemId);
				if($itemLangs){
					foreach($itemLangs as $itemLang){
						if($itemLang && file_exists($this->pathImage.'banners/'.$itemLang['banner'])) unlink($this->pathImage.'banners/'.$itemLang['banner']);
					}
				}
                DB::getInstance()->execute("Delete From "._DB_PREFIX_."groupcategory_item_lang Where itemId = ".$itemId);     				
                $response->status = '1';
                $response->msg = $this->l("Delete item success!");
            }else{
                $response->status = '0';
                $response->msg = $this->l("Delete item not success!");
            }    
        }else{
            $response->status = '0';
            $response->msg = $this->l("Item not found!"); 
        }
		$this->clearCache();
        die(Tools::jsonEncode($response));
    }
	// save item
    public  function saveItem(){
		$languages = $this->getAllLanguage();
		$db= DB::getInstance(_PS_USE_SQL_SLAVE_);
		$params = new stdClass();
		$params->products = array();		
        $shopId = Context::getContext()->shop->id;		
		$itemId = intval($_POST['itemId']);
		$names = Tools::getValue('names', array());
		$is_cache = Tools::getValue('is_cache', 1);
		$banners = $_POST['banners'];
		$categoryId = intval($_POST['categoryId']);
		$links = $_POST['links'];
		$cat_type = Tools::getValue('cat_type', 'auto');		
		$on_sale = Tools::getValue('on_sale', 2);
		$on_new = Tools::getValue('on_new', 2);
		$on_discount = Tools::getValue('on_discount', 'all');
		$on_condition = Tools::getValue('on_condition', 'all');
		$max_item = Tools::getValue('max_item', 4);
		$order_by = Tools::getValue('order_by', 'position'); 		
		$order_way = Tools::getValue('order_way', 'desc');		
		$params->products = Tools::getValue('product_ids', array());		
		$groupId = intval($_POST['groupId']);
        //require_once(dirname(__FILE__).'/GroupCategoryThumb.php');
        //$img = new GroupCategoryThumb();       
        $response = new stdClass();
        if($groupId){
            if($itemId == 0){
            	$maxOrdering = $db->getValue("Select MAX(ordering) From "._DB_PREFIX_."groupcategory_items Where `groupId` = ".$groupId);
		   		if($maxOrdering >0) $maxOrdering++;
		   		else $maxOrdering = 1;  
				$arrInsert = array(
					'groupId'=>$groupId,
					'categoryId'=>$categoryId,
					'cat_type'=>$cat_type,
					'order_by'=>$order_by,
					'order_way'=>$order_way,
					'on_condition'=>$on_condition,
					'on_sale'=>$on_sale,
					'on_new'=>$on_new,
					'on_discount'=>$on_discountd,
					'max_item'=>$max_item,
					'params'=>Tools::jsonEncode($params),					
					'ordering'=>$maxOrdering,					
					'status'=>1,
				);     
				
                if(Db::getInstance(_PS_USE_SQL_SLAVE_)->insert('groupcategory_items', $arrInsert)){
                    $insertId = DB::getInstance()->Insert_ID();
					if($languages){
	                	$insertDatas = array();
								
						$defaultName = '';
						$defaultBannerLink = '';
						$defaultBanner = '';
	                	foreach($languages as $index=>$language){
	                		$name = pSQL($names[$index]);
							$bannerLink = pSQL($links[$index]);
							
							if(!$defaultName) $defaultName = $name;
							else
								if(!$name) $name = $defaultName;
							
							if(!$defaultBannerLink) $defaultBannerLink = $bannerLink;
							else
								if(!$bannerLink) $bannerLink = $defaultBannerLink;
							
							$bannerName = $banners[$index];		                		
	                		if($banners[$index] && file_exists($module->pathTemp.$banners[$index])){	
			                    if(copy($this->pathImage.'temps/'.$bannerName, $this->pathImage.'banners/'.$bannerName)){
									if(!$defaultBanner) $defaultBanner = $bannerName;
	                			}else{
	                				if($defaultBanner && file_exists($this->pathImage.'banners/'.$defaultBanner)){
	                					$fileType = strtolower(pathinfo($defaultBanner, PATHINFO_EXTENSION));
	                					$bannerName = Tools::encrypt($defaultBanner.$index).'.'.$fileType;
										if(!copy($this->pathImage.'banners/'.$defaultBanner, $this->pathImage.'banners/'.$bannerName))
											$bannerName = '';
	                				}else 
										$bannerName = '';
	                			}
							
															              
			                }else{
			                	if($defaultBanner && file_exists($this->pathImage.'banners/'.$defaultBanner)){
	            					$fileType = strtolower(pathinfo($defaultBanner, PATHINFO_EXTENSION));
	            					$bannerName = Tools::encrypt($defaultBanner.$index).'.'.$fileType;
									if(!copy($this->pathImage.'banners/'.$defaultBanner, $this->pathImage.'banners/'.$bannerName))
										$bannerName = '';
	            				}else 
									$bannerName = '';
											                	
			                			                	
			                }
							$insertDatas[] = array('itemId'=>$insertId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'name'=>$name, 'banner'=>$bannerName, 'banner_link'=>$bannerLink, 'banner_size'=>'') ;
	                	}
						if($insertDatas) $db->insert('groupcategory_item_lang', $insertDatas);
	                }
                    $response->status = "1";
                    $response->msg = $this->l("Addnew item success!");
					$this->clearCache();
                }else{
                    $response->status = "0";
                    $response->msg = $this->l("Addnew item not success!");
                }
            }else{
            	$arrUpdate = array(                	
					'categoryId'	=> 	$categoryId,
					'cat_type'		=>	$cat_type,
					'order_by'		=>	$order_by,
					'order_way'		=>	$order_way,
					'on_condition'	=>	$on_condition,
					'on_sale'		=>	$on_sale,
					'on_new'		=>	$on_new,
					'on_discount'	=>	$on_discount,
					'max_item'		=>	$max_item,
					'params'		=>	Tools::jsonEncode($params),
					'is_cache'		=>	$is_cache,
				);
                $item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."groupcategory_items Where id = ".$itemId);
                if($item){
					Db::getInstance(_PS_USE_SQL_SLAVE_)->update('groupcategory_items', $arrUpdate, '`id`='.$itemId);	
					if($languages){
	                	$insertDatas = array();
	                	$bannerDefault = array();
	                	foreach($languages as $index=>$language){
	                		$check = DB::getInstance()->getValue("Select itemId From "._DB_PREFIX_."groupcategory_item_lang Where itemId = ".$itemId." AND `id_lang` = ".$language->id);						
	                		
	                		$name = pSQL($names[$index]);
							$bannerLink = pSQL($links[$index]);
							$bannerName = $banners[$index];
	                		if($bannerName && file_exists($this->pathImage.'temps/'.$bannerName)){
			                    if(!copy($this->pathImage.'temps/'.$bannerName, $this->pathImage.'banners/'.$bannerName)){
									$bannerName = '';
	                			}
			                }
	                		if($check){
		                    	$db->execute("Update "._DB_PREFIX_."groupcategory_item_lang Set `name` = '".$name."', `banner` = '$bannerName', `banner_link` = '".$bannerLink."', `banner_size` = '' Where `itemId` = $itemId AND `id_lang` = ".$language->id);	
		                    }else{
		                    	$insertDatas[] = array('itemId'=>$itemId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'name'=>$name, 'banner'=>$bannerName, 'banner_link'=>$bannerLink, 'banner_size'=>'') ;
		                    }							
	                	}
						if($insertDatas) Db::getInstance(_PS_USE_SQL_SLAVE_)->insert('groupcategory_item_lang', $insertDatas);
	                }
                    $response->status = "1";
                    $response->msg = $this->l("Update item success!");
					$this->clearCache();
                }else{
                    $response->status = "0";
                    $response->msg = $this->l("Item not found!");
                }
            }  
        }else{
            $response->status = "0";
            $response->msg = $this->l("Group not found!");
        }
		
        die(Tools::jsonEncode($response));
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	protected function _getAllCategoryIds($parentId = 0, $id_shop=0, $arr=null){
        if($arr == null) $arr = array();
		if(!$id_shop) $id_shop = (int) $this->context->shop->id;		
		$sql = "Select id_category 
			From "._DB_PREFIX_."category 
			Where 
				id_shop_default = $id_shop AND 
				active = 1 AND 
				id_parent = $parentId";	
        $items = DB::getInstance()->executeS($sql);
        if($items){
            foreach($items as $item){
                $arr[] = $item['id_category'];
                $arr = $this->_getAllCategoryIds($item['id_category'], $id_shop, $arr);
            }
        }
        return $arr;
    }
	function loadFeatureList(){
		$selected = Tools::getValue('selecteds', array());
		$response = new stdClass();
        $response->pagination = '';
        $response->list = '';
		if($selected){
			foreach (self::$features as $key => $value) {
				if(in_array($key, $selected)){
					$response->list .= '<li>
											<span>'.$value.'</span>
											<a href="javascript:void(0)" class="link-add-feature-off pull-right" id="link-add-feature-'.$key.'" data-id="'.$key.'" data-name="'.$value.'">
												<i class="icon-check-square-o"></i>
											</a>
										</li>';
				}else{
					$response->list .= '<li>
											<span>'.$value.'</span>
											<a href="javascript:void(0)" class="link-add-feature pull-right" id="link-add-feature-'.$key.'" data-id="'.$key.'" data-name="'.$value.'">
												<i class="icon-plus"></i>
											</a>
										</li>';	
				}				
			}
		}else{
			foreach (self::$features as $key => $value) {
				$response->list .= '<li>
										<span>'.$value.'</span>
										<a href="javascript:void(0)" class="link-add-feature pull-right" id="link-add-feature-'.$key.'" data-id="'.$key.'" data-name="'.$value.'">
											<i class="icon-plus"></i>
										</a>
									</li>';
			}	
		}
		die(Tools::jsonEncode($response));
	}
	function _getManufacturers($langId, $keyword, $total=false, $offset=0, $limit=10){
		if (!$id_lang) {
            $id_lang = $langId = $this->context->language->id;
        }
        $where = '';
        if($keyword) $where = " AND m.name LIKE '%".$keyword."%'";
      	if($total == true){
      		$total = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
				SELECT COUNT(*)
				FROM `'._DB_PREFIX_.'manufacturer` m
				'.Shop::addSqlAssociation('manufacturer', 'm').'
				INNER JOIN `'._DB_PREFIX_.'manufacturer_lang` ml ON (m.`id_manufacturer` = ml.`id_manufacturer` AND ml.`id_lang` = '.(int)$id_lang.') 
				WHERE m.`active` = 1 '.$where);
			return $total;
      	}
        $manufacturers = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT m.*, ml.`description`, ml.`short_description`
		FROM `'._DB_PREFIX_.'manufacturer` m
		'.Shop::addSqlAssociation('manufacturer', 'm').'
		INNER JOIN `'._DB_PREFIX_.'manufacturer_lang` ml ON (m.`id_manufacturer` = ml.`id_manufacturer` AND ml.`id_lang` = '.(int)$id_lang.') 
		WHERE m.`active` = 1
		'.$where.'
		ORDER BY m.`name` ASC Limit '.$offset.', '.$limit);
		return $manufacturers;		
	}
	function loadManufacturerList(){		
        $link = $this->context->link;
        $langId = $this->context->language->id;
        $shopId = $this->context->shop->id;        
        $pageSize = 10;
        $page = Tools::getValue('page', 0);        
        $keyword = Tools::getValue('keyword', '');
		$manufacturers = Tools::getValue('manufacturers', array());
        $offset=($page - 1) * $pageSize;
		
		
        $total = $this->_getManufacturers($langId, $keyword, true);	
		$response = new stdClass();
        $response->pagination = '';
        $response->list = '';
        if($total >0){            
            $response->pagination = $this->paginationAjax($total, $pageSize, $page, 6, 'loadManufacturerList');
            $items = $this->_getManufacturers($langId, $keyword, false, $offset, $pageSize);
                        
            if($items){
            	if($manufacturers){
            		foreach($items as $item){
						if(in_array($item['id_manufacturer'], $manufacturers)){
							$response->list .= '<tr id="mListTr_'.$item['id_manufacturer'].'">
                                                <td>'.$item['id_manufacturer'].'</td>
                                                <td>'.$item['name'].'</td>	                                                
                                                <td class="center"><div><a href="javascript:void(0)" id="manufacturer-'.$item['id_manufacturer'].'" data-id="'.$item['id_manufacturer'].'" data-name="'.$item['name'].'" class="link-add-manufacturer-off"><i class="icon-check-square-o"></i></a></div></td>
                                            </tr>';
						}else{
							$response->list .= '<tr id="mListTr_'.$item['id_product'].'">
                                                    <td>'.$item['id_manufacturer'].'</td>		                                                
	                                                <td>'.$item['name'].'</td>                                                        
	                                                <td class="center"><div><a href="javascript:void(0)" id="manufacturer-'.$item['id_manufacturer'].'" data-id="'.$item['id_manufacturer'].'" data-name="'.$item['name'].'" class="link-add-manufacturer"><i class="icon-plus"></i></a></div></td>
	                                            </tr>';	
						}
                        
                    }
            	}else{
                	foreach($items as $item){
                        $response->list .= '<tr id="mListTr_'.$item['id_product'].'">
                                                    <td>'.$item['id_manufacturer'].'</td>		                                                
	                                                <td>'.$item['name'].'</td>                                                        
	                                                <td class="center"><div><a href="javascript:void(0)" id="manufacturer-'.$item['id_manufacturer'].'" data-id="'.$item['id_manufacturer'].'" data-name="'.$item['name'].'" class="link-add-manufacturer"><i class="icon-plus"></i></a></div></td>
	                                            </tr>';	
                    }	
            	}
                
            }
            
        }
        die(Tools::jsonEncode($response));
    }
	// load product list (add manual product)
    function loadProductList(){		
        $link = $this->context->link;
        $langId = $this->context->language->id;
        $shopId = $this->context->shop->id;        
        $pageSize = 10;
        $page = Tools::getValue('page', 0);// intval($_POST['page']);        
        $categoryId =  Tools::getValue('categoryId', Configuration::get('PS_HOME_CATEGORY')); // Db::getInstance()->getValue("Select category_id From "._DB_PREFIX_."simplecategory_module Where id = ".$moduleId);
        $keyword = Tools::getValue('keyword', '');
		$productIds = Tools::getValue('productIds', array());		
        $arrSubCategory = $this->_getAllCategoryIds($categoryId);
        $arrSubCategory[] = $categoryId;
        $offset=($page - 1) * $pageSize;
        $total = $this->getManualProducts($langId, $arrSubCategory, $keyword, true);		
		$response = new stdClass();
        $response->pagination = '';
        $response->list = '';
        if($total >0){            
            $response->pagination = $this->paginationAjax($total, $pageSize, $page, 6, 'loadProductList');
            $items = $this->getManualProducts($langId, $arrSubCategory, $keyword, false, $offset, $pageSize);
            if($items){
                if($items){
                	if($productIds){
                		foreach($items as $item){
	                        $imagePath = $link->getImageLink($item['link_rewrite'], $item['id_image'], 'cart_default');
							if(in_array($item['id_product'], $productIds)){
								$response->list .= '<tr id="pListTr_'.$item['id_product'].'">
	                                                <td>'.$item['id_product'].'</td>
                                                    <td class="center"><img src="'.$imagePath.'" height="32" /></td>
	                                                <td>'.$item['name'].'</td>	                                                
	                                                <td class="center"><div><a href="javascript:void(0)" id="manual-product-'.$item['id_product'].'" data-id="'.$item['id_product'].'" data-name="'.$item['name'].'" class="link-add-manual-product-off"><i class="icon-check-square-o"></i></a></div></td>
	                                            </tr>';
							}else{
								$response->list .= '<tr id="pListTr_'.$item['id_product'].'">
                                                        <td>'.$item['id_product'].'</td>		                                                
		                                                <td class="center"><img src="'.$imagePath.'" height="32" /></td>
		                                                <td>'.$item['name'].'</td>                                                        
		                                                <td class="center"><div><a href="javascript:void(0)" id="manual-product-'.$item['id_product'].'" data-id="'.$item['id_product'].'" data-name="'.$item['name'].'" class="link-add-manual-product"><i class="icon-plus"></i></a></div></td>
		                                            </tr>';	
							}
	                        
	                    }
                	}else{
	                	foreach($items as $item){
	                        $imagePath = $link->getImageLink($item['link_rewrite'], $item['id_image'], 'cart_default');							
	                        $response->list .= '<tr id="pListTr_'.$item['id_product'].'">
	                                                <td>'.$item['id_product'].'</td>
	                                                <td class="center"><img src="'.$imagePath.'" height="32" /></td>
	                                                <td>'.$item['name'].'</td>	                                                
	                                                <td class="center"><div><a href="javascript:void(0)" id="manual-product-'.$item['id_product'].'" data-id="'.$item['id_product'].'" data-name="'.$item['name'].'" class="link-add-manual-product"><i class="icon-plus"></i></a></div></td>
	                                            </tr>';
	                    }	
                	}
                    
                }
            }   
        }
        die(Tools::jsonEncode($response));
    }
    
	function getManualProducts($id_lang, $arrCategory = array(), $keyword = '', $getTotal = false, $offset=0, $limit=10){        
        $where = "";
        if($arrCategory){
            $catIds = implode(', ', $arrCategory);
        }        
        $where .= ' AND p.`id_product` IN (
			SELECT cp.`id_product`
			FROM `'._DB_PREFIX_.'category_product` cp 
			WHERE cp.id_category IN ('.$catIds.'))';
            		
        if($keyword != '') $where .= " AND (p.id_product) LIKE '%".$keyword."%' OR pl.name LIKE '%".$keyword."%'";
        if($getTotal == true){
            $sqlTotal = 'SELECT COUNT(p.`id_product`) AS nb 
    					FROM `'._DB_PREFIX_.'product` p 
    					'.Shop::addSqlAssociation('product', 'p').'  
                        LEFT JOIN `'._DB_PREFIX_.'product_lang` pl 
    					   ON p.`id_product` = pl.`id_product` 
    					   AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').' 
    					WHERE product_shop.`active` = 1 
                            AND product_shop.`active` = 1 
                            AND p.`visibility` != \'none\' '.$where;
            $total = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sqlTotal);
            if($getTotal == true) return $total;    
        }                            
        $sql = 'Select p.id_product, pl.`name`,  pl.`link_rewrite`, MAX(image_shop.`id_image`) id_image 
                FROM  `'._DB_PREFIX_.'product` p 
                '.Shop::addSqlAssociation('product', 'p', false).'				
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl 
					ON p.`id_product` = pl.`id_product` 
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').' 
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').' 
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')				
				WHERE product_shop.`active` = 1 
					AND p.`visibility` != \'none\'  '.$where.'			
				GROUP BY product_shop.id_product Limit '.$offset.', '.$limit;
                return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);            
    }
	protected function paginationAjax($total, $page_size, $current = 1, $index_limit = 10, $func='loadPage'){
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
	
	
    public function hookActionProductAdd($params)	{		
        //$this->cache->cleanup();
        $this->clearCache();
        return true;	
    }
    public function hookActionProductAttributeDelete($params)	{		
        return $this->hookActionProductAdd();	
    }
    public function hookActionProductAttributeUpdate($params)	{		
        return $this->hookActionProductAdd();	
    }
    public function hookActionProductDelete($params)	{		
        return $this->hookActionProductAdd();	
    }
    public function hookActionProductSave($params)	{		
        return $this->hookActionProductAdd();	
    }
    public function hookActionProductUpdate($params)	{		
        return $this->hookActionProductAdd();	
    }
    public function hookActionCartSave($params)	{		
        //return $this->hookActionProductAdd();	
    }
    public function hookActionCategoryAdd($params)	{		
        return $this->hookActionProductAdd();	
    }
    public function hookActionCategoryDelete($params)	{		
        return $this->hookActionProductAdd();	
    }
    public function hookdisplayHeader()
	{
		/*
		$this->arrType = array('saller'=>$this->l('Best Sellers'), 'view'=>$this->l('Most View'), 'special'=>$this->l('Specials'), 'arrival'=>$this->l('New Arrivals'));		
        $this->page_name = Dispatcher::getInstance()->getController();        
		if ($this->page_name == 'product')
		{		  
			$productId = (int)Tools::getValue('id_product');
            $check = DB::getInstance()->getValue("Select productId From "._DB_PREFIX_."groupcategory_product_view Where productId = ".$productId);
            if($check){
                DB::getInstance()->execute("Update "._DB_PREFIX_."groupcategory_product_view Set total = total + 1 Where productId =" .$productId);
            }else{
                DB::getInstance()->execute("Insert Into "._DB_PREFIX_."groupcategory_product_view (productId, total) Value ('$productId', 1)");
            }
		}        
		*/
		if(count($this->codeCss) == 0){
			$cssStyles = DB::getInstance()->executeS("Select id, name From "._DB_PREFIX_."groupcategory_styles");
			if($cssStyles){
				foreach($cssStyles as $cssStyle){
					$this->codeCss[] = file_get_contents(_PS_MODULE_DIR_.'groupcategory/css/front-end/style-'.$cssStyle['id'].".css");
					
				}        
			}
		}
        
		
        
        $themeOption = @Configuration::get('OVIC_CURRENT_OPTION');
        //$themeOption = 3;
        
        if(isset($themeOption) && $themeOption >0){
            $this->context->controller->addJS(($this->_path).'js/front-end/common'.$themeOption.'.js');
            $this->context->controller->addCSS(($this->_path).'css/front-end/style'.$themeOption.'.css');    
        }else{
            $themeOption = '';
        	$this->context->controller->addJS(($this->_path).'js/front-end/common.js');
            $this->context->controller->addCSS(($this->_path).'css/front-end/style.css');            
        }
        
        //$this->context->controller->addJS(($this->_path).'js/front-end/jquery.actual.min.js');
		$this->context->smarty->assign(array(
            'comparator_max_item' => (int)(Configuration::get('PS_COMPARATOR_MAX_ITEM')),            
            'groupCategoryUrl'=> __PS_BASE_URI__.'modules/'.$this->name,
            'homeSize' => Image::getSize(ImageType::getFormatedName('home')),
            
			'codeCss'=>$this->codeCss,
            'themeOption'=>$themeOption
        )); 
		include_once (_PS_CONTROLLER_DIR_.'front/CompareController.php');
		if(!$this->compareProductIds = CompareProduct::getCompareProducts($this->context->cookie->id_compare)) $this->compareProductIds = array();
	}
	public function hookdisplayGroupFashions($params)
	{
		return $this->hooks('hookdisplayGroupFashions', $params);
	}
	public function hookdisplayGroupFoods($params)
	{
		return $this->hooks('hookdisplayGroupFoods', $params);
	}
	public function hookdisplayGroupSports($params)
	{
		return $this->hooks('hookdisplayGroupSports', $params);
	}	
    public function hooks($hookName, $param){
		
        //$moduleLayout = 'groupcategory.tpl';
        $langId = Context::getContext()->language->id;		
        $shopId = Context::getContext()->shop->id;        
        $hookName = str_replace('hook','', $hookName);        
        $hookId = Hook::getIdByName($hookName);
        $items = DB::getInstance()->executeS("Select DISTINCT g.*, gl.`name`, gl.`banner`, gl.banner_link, gl.`banner_size` From "._DB_PREFIX_."groupcategory_groups as g INNER JOIN "._DB_PREFIX_."groupcategory_group_lang AS gl On g.id = gl.group_id Where g.status = 1 AND g.`position_name` = '".$hookName."' AND g.id_shop=".$shopId." AND gl.id_lang = ".$langId." AND gl.id_shop = ".$shopId." Order By g.ordering");
				
        $modules = array();
        if($items){            
            foreach($items as $i=>$item){                
                $modules[] = array(
					'id'=>$item['id'], 
					'name'=>$item['name'], 
					'layout'=>$item['layout'], 
					'content'=>$this->buildModule($item, $langId, $shopId)
				);                
            }
        }   
        $this->context->smarty->assign('groupCategoryModules', $modules);
        $hookname_div  = 'group-fashion';
       
        
        if ($hookName == 'displayGroupFoods'){
            $hookname_div  = 'group-foods';
        }elseif ($hookName == 'displayGroupSports'){
            $hookname_div  = 'group-sports';
        }elseif ($hookName == 'displayGroupFashions'){
            $hookname_div  = 'group-fashion';
        }        
        $this->context->smarty->assign('hookname_div', $hookname_div);  
		return $this->display(__FILE__, 'groupcategory.tpl');
    }
    public function s_print($content){
    	echo '<pre>';
		print_r($content);
		echo '</pre>';
		die;
    }
    protected function buildProduct($products){
    	
    }
	/* update _getProducts 2015-11-10 */
    public function buildModule($moduleItem, $langId, $shopId){		
        $themeOption = @Configuration::get('OVIC_CURRENT_OPTION');
		
		if(!$themeOption) $themeOption = 'gc';
		$cacheKey = 'groupcategory|'.$moduleItem['position_name'].'|'.$moduleItem['id'];
	 	$sectionFeatures = '';
		$sectionManufacturers='';
		$sectionSubs = '';
		$sectionBanners = '';
		$sectionContent = '';
		$arrBanners = array();
		
		if (!$this->isCached('banners.option'.$themeOption.'.'.$moduleItem['layout'].'.tpl', Tools::encrypt($cacheKey))){
			$moduleBannerImg = 	$this->getImageSrc($moduleItem['banner'], false);
			$arrBanners[] = array('link'=>$moduleItem['banner_link'], 'img'=>$moduleBannerImg, 'key'=>'tab-content-'.$moduleItem['id'].'-0-0', 'title'=>$moduleItem['name']);
		}
		
		$params = Tools::jsonDecode($moduleItem['params']);
		//$moduleCategoryIds = $this->getCategoryIds($moduleItem['categoryId'], array($moduleItem['categoryId']));
		$cacheFile = 'product-list.option'.$themeOption.'.'.$moduleItem['layout'].'.tpl';		
		if($moduleItem['cat_type'] == 'auto'){
			if($moduleItem['order_by'] == 'view' || $moduleItem['order_by'] == 'review' || $moduleItem['order_by'] == 'rate'){			
				$products = $this->_getProducts_before($moduleItem['categoryId'], $moduleItem['on_condition'], $moduleItem['on_sale'], $moduleItem['on_new'], $moduleItem['on_discount'], $langId, 0, $moduleItem['max_item'], $moduleItem['order_by'], $moduleItem['order_way']);				
				$this->context->smarty->assign(
					array(
						'products' 	=>	$products,
						'module_id'	=>	$moduleItem['id'],
						'feature'	=>	'0',
						'item_id'	=>	'0',
						'active'	=>	1,
					)
				);
				$sectionContent .= $this->display(__FILE__, 'product-list-nocache.option'.$themeOption.'.'.$moduleItem['layout'].'.tpl');			
				unset($products);
			}else{							
				$products = array();
				if ($moduleItem['is_cache'] == '0' || !$this->isCached($cacheFile, Tools::encrypt($cacheKey))){
					
					$products = $this->_getProducts_before($moduleItem['categoryId'], $moduleItem['on_condition'], $moduleItem['on_sale'], $moduleItem['on_new'], $moduleItem['on_discount'], $langId, 0, $moduleItem['max_item'], $moduleItem['order_by'], $moduleItem['order_way']);
					
					$this->context->smarty->assign(
						array(
							'products' 	=>	$products,
							'module_id'	=>	$moduleItem['id'],
							'feature'	=>	'0',
							'item_id'	=>	'0',
							'active'	=>	1,
						)
					);
				}			
				$sectionContent .= $this->display(__FILE__, $cacheFile, Tools::encrypt($cacheKey));
				unset($products);
			}	
		}else{
			$products = array();
			if (!$this->isCached($cacheFile, Tools::encrypt($cacheKey))){
				if($params->products && count($params->products) >0){
					foreach($params->products as $productId){
						if($productId >0)
	                    	$products[] = $this->_getProductById_before($productId, $langId);		                    
	                } 
				}
				$this->context->smarty->assign(
					array(
						'products' 	=>	$products,
						'module_id'	=>	$moduleItem['id'],
						'feature'	=>	'0',
						'item_id'	=>	'0',
						'active'	=>	1,
					)
				);
			}			
			//$sectionContent	.='<div id="product-list-'.$moduleItem['id'].'-0-0" class="lazy-carousel check-active active tab-content-'.$moduleItem['id'].'-0-0">';
			$sectionContent .= $this->display(__FILE__, $cacheFile, Tools::encrypt($cacheKey));
			//$sectionContent	.='</div>';
		}		
			
		
		$sectionFeatures .= '<ul class="group-types clearfix features-carousel">';					
		if(isset($params->features) && $params->features){			
			foreach ($params->features as $feature) {
				if (!$this->isCached('banners.option'.$themeOption.'.'.$moduleItem['layout'].'.tpl', Tools::encrypt($cacheKey))){
					$arrBanners[] = array('link'=>$moduleItem['banner_link'], 'img'=>$moduleBannerImg, 'key'=>'tab-content-'.$moduleItem['id'].'-'.$feature.'-0', 'title'=>$moduleItem['name'].' - '.self::$features[$feature]);
				}
				$sectionFeatures .= '<li class="group-type '.$feature.' check-active">
					<a role="tab" data-toggle="tab" data-id="'.$moduleItem['id'].'" href=".tab-content-'.$moduleItem['id'].'-'.$feature.'-0" class="tab-link">
						<i class="icon-20x15 '.$feature.'-icon"></i><br><span>'.self::$features[$feature].'</span>						
					</a>
				</li>';				
				if($feature == 'view' || $feature == 'review' || $feature == 'rate'){								
					$products = $this->_getProducts_before($moduleItem['categoryId'], $moduleItem['on_condition'], $moduleItem['on_sale'], $moduleItem['on_new'], $moduleItem['on_discount'], $langId, 0, $moduleItem['max_item'], $feature, $moduleItem['order_way']);
					$this->context->smarty->assign(
						array(
							'products' 	=>	$products,
							'module_id'	=>	$moduleItem['id'],
							'feature'	=>	$feature,
							'item_id'	=>	'0',
							'active'	=>	0,
						)
					);
					$sectionContent .= $this->display(__FILE__, 'product-list-nocache.option'.$themeOption.'.'.$moduleItem['layout'].'.tpl');					
					unset($products);
				}else{					
					$cacheFile = 'product-list.option'.$themeOption.'.'.$moduleItem['layout'].'.tpl';
					$products = array();
					if ($moduleItem['is_cache'] == '0' || !$this->isCached($cacheFile, Tools::encrypt($cacheKey.'|'.$feature))){
						if($feature == 'arrival')							
							$products = $this->_getProducts_before($moduleItem['categoryId'], $moduleItem['on_condition'], $moduleItem['on_sale'], 1, $moduleItem['on_discount'], $langId, 0, $moduleItem['max_item'], 'date_add', $moduleItem['order_way']);
						else if($feature == 'special')
							$products = $this->_getProducts_before($moduleItem['categoryId'], $moduleItem['on_condition'], $moduleItem['on_sale'], $moduleItem['on_new'], 1, $langId, 0, $moduleItem['max_item'], 'discount', $moduleItem['order_way']);
						else 
							$products = $this->_getProducts_before($moduleItem['categoryId'], $moduleItem['on_condition'], $moduleItem['on_sale'], $moduleItem['on_new'], $moduleItem['on_discount'], $langId, 0, $moduleItem['max_item'], $moduleItem['order_by'], $moduleItem['order_way']);
						$this->context->smarty->assign(
							array(
								'products' 	=>	$products,
								'module_id'	=>	$moduleItem['id'],
								'feature'	=>	$feature,
								'item_id'	=>	'0',
								'active'	=>	0,
							)
						);
					}
					$sectionContent .= $this->display(__FILE__, $cacheFile, Tools::encrypt($cacheKey.'|'.$feature));				
				}
			
			}
		}
		$sectionFeatures .='</ul>';		
		$cacheFile = 'manufacturer.option'.$themeOption.'.'.$moduleItem['layout'].'.tpl';
		if(isset($params->manufacturers) && count($params->manufacturers) >0){
			if (!$this->isCached($cacheFile, Tools::encrypt($cacheKey.'|manufacturer'))){
				$manufacturers = array();
				$brandImageSize = Image::getSize('brand-136x69');
				foreach($params->manufacturers as $i => $manId){
					$man =  new Manufacturer((int)$manId, $langId);
	                if($man){
	                    $manufacturers['items'][] = array('moduleId'=>$moduleItem['id'], 'image'=>_THEME_MANU_DIR_.$man->id.'-brand-136x69.jpg', 'name'=>$man->name, 'link'=>$this->context->link->getManufacturerLink($man->id, $man->link_rewrite, $langId));    
	                }					
				}			
				$this->context->smarty->assign(array('brandImageSize'=>$brandImageSize, 'manufacturers'=>$manufacturers));
			}
			$sectionManufacturers .= $this->display(__FILE__, $cacheFile, Tools::encrypt($cacheKey.'|manufacturer'));
		}
		$items = DB::getInstance()->executeS("Select DISTINCT i.*, il.name, il.`banner`, il.`banner_link` From "._DB_PREFIX_."groupcategory_items AS i Inner Join "._DB_PREFIX_."groupcategory_item_lang AS il On i.id = il.itemId Where i.status = 1 AND i.`groupId` = ".$moduleItem['id']." AND il.id_lang = ".$langId." Order By i.`ordering`");
		$arrSubs = array();       
        if($items){
        	//$sectionSubs .='<ul class="category-list">';			
            foreach($items as $item){
            	$itemParams = Tools::jsonDecode($item['params']);
            	if (!$this->isCached('banners.option'.$themeOption.'.'.$moduleItem['layout'].'.tpl', Tools::encrypt($cacheKey))){
					$arrBanners[] = array('link'=>$item['banner_link'], 'img'=>$this->getImageSrc($item['banner'], false), 'key'=>'tab-content-'.$moduleItem['id'].'-0-'.$item['id'], 'title'=>$item['name']);
				}
				$arrSubs[] = array('item_id'=>$item['id'], 'item_name'=>$item['name']);
            	//$sectionSubs .= '<li class="category-list-item check-active"><a role="tab" data-toggle="tab" data-id="'.$moduleItem['id'].'" href=".tab-content-'.$moduleItem['id'].'-0-'.$item['id'].'" class="tab-link">'.$item['name'].'</a></li>';     
                $arrSubCategory = $this->getCategoryIds($item['categoryId'], array($item['categoryId']));
				if($item['cat_type'] == 'auto'){
					if($item['order_by'] == 'view' || $item['order_by'] == 'review' || $item['order_by'] == 'rate'){			
						//$sectionContent	.='<div id="product-list-'.$moduleItem['id'].'-0-'.$item['id'].'" class="lazy-carousel check-active tab-content-'.$moduleItem['id'].'-0-'.$item['id'].'">';
						$products = $this->_getProducts_before($item['categoryId'], $item['on_condition'], $item['on_sale'], $item['on_new'], $item['on_discount'], $langId, 0, $item['max_item'], $item['order_by'], $item['order_way']);
						$this->context->smarty->assign(
							array(
								'products' 	=>	$products,
								'module_id'	=>	$moduleItem['id'],
								'feature'	=>	'0',
								'item_id'	=>	$item['id'],
								'active'	=>	0,
							)
						);
						$sectionContent .= $this->display(__FILE__, 'product-list-nocache.option'.$themeOption.'.'.$moduleItem['layout'].'.tpl');
						//$sectionContent .='</div>';
					}else{
						$cacheFile = 'product-list.option'.$themeOption.'.'.$moduleItem['layout'].'.tpl';			
						$products = array();
						if ($moduleItem['is_cache'] == '0' || !$this->isCached($cacheFile, Tools::encrypt($cacheKey.'|0|'.$item['id']))){
							$products = $this->_getProducts_before($item['categoryId'], $item['on_condition'], $item['on_sale'], $item['on_new'], $item['on_discount'], $langId, 0, $item['max_item'], $item['order_by'], $item['order_way']);
							$this->context->smarty->assign(
								array(
									'products' 	=>	$products,
									'module_id'	=>	$moduleItem['id'],
									'feature'	=>	'0',
									'item_id'	=>	$item['id'],
									'active'	=>	0,
								)
							);
							
						}			
						//$sectionContent	.='<div id="product-list-'.$moduleItem['id'].'-0-'.$item['id'].'" class="lazy-carousel check-active tab-content-'.$moduleItem['id'].'-0-'.$item['id'].'">';
						$sectionContent .= $this->display(__FILE__, $cacheFile, Tools::encrypt($cacheKey.'|0|'.$item['id']));
						//$sectionContent	.='</div>';
						unset($products);
					}
				}else{
					$cacheFile = 'product-list.option'.$themeOption.'.'.$moduleItem['layout'].'.tpl';
					$products = array();
					if ($moduleItem['is_cache'] == '0' || !$this->isCached($cacheFile, Tools::encrypt($cacheKey.'|0|'.$item['id']))){
						if($itemParams->products && count($itemParams->products) >0){
							$products = array();
							foreach($itemParams->products as $productId){
								if($productId >0)
			                    	$products[] = $this->_getProductById_before($productId, $langId);		                    
			                }							 
						}
						$this->context->smarty->assign(
							array(
									'products' 	=>	$products,
									'module_id'	=>	$moduleItem['id'],
									'feature'	=>	'0',
									'item_id'	=>	$item['id'],
									'active'	=>	0,
								)
						);
					}
					//$sectionContent	.='<div id="product-list-'.$moduleItem['id'].'-0-'.$item['id'].'" class="lazy-carousel check-active tab-content-'.$moduleItem['id'].'-0-'.$item['id'].'">';
					$sectionContent .= $this->display(__FILE__, $cacheFile, Tools::encrypt($cacheKey.'|0|'.$item['id']));
					//$sectionContent	.='</div>';
					unset($products);
				}  
            }
			//$sectionSubs .= '</ul>';
        }		
		if($arrBanners){
			$this->context->smarty->assign(array(
				'banners' => $arrBanners,
				'module_id' => $moduleItem['id'],
			));
		}		
		$sectionBanners .= $this->display(__FILE__, 'banners.option'.$themeOption.'.'.$moduleItem['layout'].'.tpl', Tools::encrypt($cacheKey));
		if($arrSubs){
			$this->context->smarty->assign(array(
				'items' => $arrSubs,
				'module_id' => $moduleItem['id'],
			));		
		}
		$sectionSubs .= $this->display(__FILE__, 'sub-list.option'.$themeOption.'.'.$moduleItem['layout'].'.tpl', Tools::encrypt($cacheKey));
	
        $moduleIcon = $this->getIconSrc($moduleItem['icon']);
        $this->context->smarty->assign(
	 		array(
				'module_id'				=>	$moduleItem['id'],
				'module_name'			=>	$moduleItem['name'],
				'module_banner'			=>	$this->getImageSrc($moduleItem['banner']),
				'module_icon_type'		=>	$moduleIcon['type'],
				'module_icon_img' 		=>	$moduleIcon['img'],
				'module_banner_link'	=>	$moduleItem['banner_link'],
				'module_style'			=>	$moduleItem['style_id'],
				'sectionFeatures'		=>	$sectionFeatures,
				'sectionBanners'		=>	$sectionBanners,
				'sectionManufacturers'	=>	$sectionManufacturers,
				'sectionSubs'			=>	$sectionSubs,
				'sectionContent'		=>	$sectionContent,
			)			
		);
		
		unset($moduleIcon);
        if(isset($themeOption) && $themeOption >0){
            return $this->display(__FILE__, $moduleItem['layout'].'.option'.$themeOption.'.layout.tpl');    
        }else{            
            return $this->display(__FILE__, $moduleItem['layout'].'.layout.tpl');
        }		
    }
	protected function _getProductById($productId = 0, $id_lang){		
		if(!$productId) return array();		
		$context = Context::getContext();
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
							product_shop.`id_shop` = '.(int)$context->shop->id.' 
							AND  product_shop.`id_product` = '.(int)$productId .' 
							AND  product_shop.`active` = 1 
							AND  product_shop.`visibility` IN ("both", "catalog")';
		
		
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);		
		if (!$result) return array();		
		return Product::getProductProperties($id_lang, $result);
	}
	protected function _getProducts($categoryId = 0, $on_condition='all', $on_sale=2, $on_new=2, $on_discount=2, $id_lang, $p, $n, $order_by = null, $order_way = null, $beginning=null, $ending=null, $deal=false, $get_total = false, $active = true, $random = false, $random_number_products = 1, Context $context = null){
		if(!$categoryId) return array();		
		Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ? $PS_NB_DAYS_NEW_PRODUCT = (int)Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : $PS_NB_DAYS_NEW_PRODUCT = 20;
		$where = "";
		if($on_condition != 'all'){
             $where .= " AND p.`condition` = '".$on_condition."' ";                
        }
		if($on_sale != 2){
			$where .= " AND p.`on_sale` = '".$on_sale."' ";
		}        
		if($on_new == 0){
			$where .= " AND product_shop.`date_add` <= '".date('Y-m-d', strtotime('-'.$PS_NB_DAYS_NEW_PRODUCT.' DAY'))."' ";
		}elseif($on_new == 1){
			$where .= " AND product_shop.`date_add` > '".date('Y-m-d', strtotime('-'.$PS_NB_DAYS_NEW_PRODUCT.' DAY'))."' ";
		}
		$arrProductIds = array();
		$whereProductId = '';
		if($on_discount != 2){
			$current_date = date('Y-m-d H:i:s');
			$product_reductions = $this->_getProductIdByDate((!$beginning ? $current_date : $beginning), (!$ending ? $current_date : $ending), $context, true, 0, $deal);		
			if ($product_reductions){
				$a = count($product_reductions);
				$i=0;
				foreach ($product_reductions as $product_reduction){
					if($i < $a-1)
						$whereProductId .= $product_reduction['id_product'].', ';
					else
						$whereProductId .= $product_reduction['id_product'];
					$i++;
				}
				
			}
			if($whereProductId) {
				if($on_discount == 0)
					$whereProductId = ' AND p.`id_product` NOT IN ('.$whereProductId.')';
				else	
					$whereProductId = ' AND p.`id_product` IN ('.$whereProductId.')';
			}
		}
		if (!$context) $context = Context::getContext();
		$front = true;	
		if ($p < 1) $p = 1;
		
		if (empty($order_by)){
			$order_by = 'position';
		}else{
			$order_by = strtolower($order_by);
		}			
		if (empty($order_way)) $order_way = 'ASC';		
		$order_by_prefix = false;
		if ($order_by == 'id_product' || $order_by == 'date_add' || $order_by == 'date_upd'){
			if($on_discount != 2){
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
							product_shop.price AS orderprice, 
							sp.reduction, 
							sp.`from`, 
							sp.`to` 
						FROM 
							`'._DB_PREFIX_.'category_product` cp 
							LEFT JOIN (`'._DB_PREFIX_.'product` p INNER JOIN `'._DB_PREFIX_.'specific_price` sp On p.`id_product` = sp.`id_product`) 
								ON (p.`id_product` = cp.`id_product` '.$whereProductId.') '.
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
							product_shop.`id_shop` = '.(int)$context->shop->id.' 
							AND cp.`id_category` = '.(int)$categoryId .' 
							AND product_shop.`active` = 1 
							AND product_shop.`visibility` IN ("both", "catalog") '.
							$where.' 
						GROUP BY 
							cp.id_product 
						ORDER BY 
							p.`'.bqSQL($order_by).'` '.pSQL($order_way).' 
						Limit 
							'.(((int)$p - 1) * (int)$n).', '.(int)$n;
			}else{
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
						FROM 
							`'._DB_PREFIX_.'category_product` cp 
							LEFT JOIN `'._DB_PREFIX_.'product` p 
								ON (p.`id_product` = cp.`id_product` '.$whereProductId.')  '.
							Shop::addSqlAssociation('product', 'p').' '.
							'LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa 
								ON (p.`id_product` = pa.`id_product`)'.
							Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').' '.
							Product::sqlStock('p', 0).
							'LEFT JOIN `'._DB_PREFIX_.'category_lang` cl 
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
							product_shop.`id_shop` = '.(int)$context->shop->id.' 
							AND cp.`id_category` = '.(int)$categoryId .' 
							AND product_shop.`active` = 1 
							AND product_shop.`visibility` IN ("both", "catalog") '.
							$where.' 
						GROUP BY 
							cp.id_product  
						ORDER BY 
							p.`'.bqSQL($order_by).'` '.pSQL($order_way).' 
						Limit 
							'.(((int)$p - 1) * (int)$n).', '.(int)$n;
			}
			
		}elseif ($order_by == 'name'){
			if($on_discount != 2){
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
							product_shop.price AS orderprice, 
							sp.reduction, 
							sp.`from`, 
							sp.`to`  
						FROM 
							`'._DB_PREFIX_.'category_product` cp 
							LEFT JOIN (`'._DB_PREFIX_.'product` p INNER JOIN `'._DB_PREFIX_.'specific_price` sp On p.`id_product` = sp.`id_product`) 
								ON (p.`id_product` = cp.`id_product` '.$whereProductId.')  '.
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
							product_shop.`id_shop` = '.(int)$context->shop->id.' 
							AND cp.`id_category` = '.(int)$categoryId .' 
							AND product_shop.`active` = 1 
							AND product_shop.`visibility` IN ("both", "catalog") '.
							$where.' 
						GROUP BY 
							cp.id_product 
						ORDER BY  
							pl.`name` '.pSQL($order_way).'  
						Limit '.(((int)$p - 1) * (int)$n).', '.(int)$n;
			}else{
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
						FROM 
							`'._DB_PREFIX_.'category_product` cp 
							LEFT JOIN `'._DB_PREFIX_.'product` p 
								ON (p.`id_product` = cp.`id_product` '.$whereProductId.')  '.
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
							product_shop.`id_shop` = '.(int)$context->shop->id.' 
							AND cp.`id_category` = '.(int)$categoryId .' 
							AND product_shop.`active` = 1 
							AND product_shop.`visibility` IN ("both", "catalog") '.
							$where.' 
						GROUP BY 
							cp.id_product 
						ORDER BY  
							pl.`name` '.pSQL($order_way).' 
						Limit 
							'.(((int)$p - 1) * (int)$n).', '.(int)$n;
			}
			
		
		}elseif ($order_by == 'position'){
			//$order_by_prefix = 'cp';
			if($on_discount != 2){
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
							product_shop.price AS orderprice, 
							sp.reduction, 
							sp.`from`, 
							sp.`to`   
						FROM 
							`'._DB_PREFIX_.'category_product` cp 
							LEFT JOIN (`'._DB_PREFIX_.'product` p INNER JOIN `'._DB_PREFIX_.'specific_price` sp On p.`id_product` = sp.`id_product`) 
								ON (p.`id_product` = cp.`id_product` '.$whereProductId.')  '.
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
							product_shop.`id_shop` = '.(int)$context->shop->id.' 
							AND cp.`id_category` = '.(int)$categoryId .' 
							AND product_shop.`active` = 1 
							AND product_shop.`visibility` IN ("both", "catalog") '.
							$where.' 
						GROUP BY 
							cp.id_product 
						ORDER BY 
							cp.`position` '.pSQL($order_way).' 
						Limit 
							'.(((int)$p - 1) * (int)$n).', '.(int)$n;
			}else{
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
						FROM 
							`'._DB_PREFIX_.'category_product` cp 
							LEFT JOIN `'._DB_PREFIX_.'product` p 
								ON (p.`id_product` = cp.`id_product` '.$whereProductId.')  '.
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
							LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer` 
						WHERE 
							product_shop.`id_shop` = '.(int)$context->shop->id.' 
							AND cp.`id_category` = '.(int)$categoryId .' 
							AND product_shop.`active` = 1 
							AND product_shop.`visibility` IN ("both", "catalog") '.
							$where.' 
						GROUP BY 
							cp.id_product 
						ORDER BY 
							cp.`position` '.pSQL($order_way).'  
						Limit 
							'.(((int)$p - 1) * (int)$n).', '.(int)$n;
			}
		}elseif($order_by == 'discount'){
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
						product_shop.price AS orderprice, 
						sp.reduction, 
						sp.`from`, 
						sp.`to` 
					FROM 
						`'._DB_PREFIX_.'category_product` cp LEFT JOIN (`'._DB_PREFIX_.'product` p INNER JOIN `'._DB_PREFIX_.'specific_price` sp On p.`id_product` = sp.`id_product`) 
							ON (p.`id_product` = cp.`id_product` '.$whereProductId.')  '.
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
							product_shop.`id_shop` = '.(int)$context->shop->id.' 
							AND  cp.`id_category` = '.(int)$categoryId .' 
							AND  product_shop.`active` = 1 
							AND  product_shop.`visibility` IN ("both", "catalog") '.
							$where.'  
						GROUP BY  
							cp.id_product  
						ORDER BY  
							sp.`reduction` '.pSQL($order_way).'  
						Limit 
							'.(((int)$p - 1) * (int)$n).', '.(int)$n;
		}elseif($order_by == 'review'){			
			if($on_discount != 2){
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
							product_shop.price AS orderprice, 
							COUNT(pr.grade) as total_review , 
							sp.reduction, 
							sp.`from`, 
							sp.`to`  
						FROM 
							`'._DB_PREFIX_.'category_product` cp 
							LEFT JOIN ((`'._DB_PREFIX_.'product` p INNER JOIN `'._DB_PREFIX_.'specific_price` sp On p.`id_product` = sp.`id_product`) LEFT JOIN `'._DB_PREFIX_.'product_comment` pr ON pr.`id_product` = p.`id_product`) 
								ON (p.`id_product` = cp.`id_product` '.$whereProductId.')  '.
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
							product_shop.`id_shop` = '.(int)$context->shop->id.' 
							AND cp.`id_category` = '.(int)$categoryId .' 
							AND product_shop.`active` = 1 
							AND product_shop.`visibility` IN ("both", "catalog") '.
							$where.' 
						GROUP BY 
							cp.id_product  
						ORDER BY  
							`total_review` '.pSQL($order_way).' 
						Limit 
							'.(((int)$p - 1) * (int)$n).', '.(int)$n;
			}else{
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
							product_shop.price AS orderprice, 
							COUNT(pr.grade) as total_review  
						FROM `'._DB_PREFIX_.'category_product` cp 
						LEFT JOIN (`'._DB_PREFIX_.'product` p LEFT JOIN `'._DB_PREFIX_.'product_comment` pr ON pr.`id_product` = p.`id_product`) 
							ON (p.`id_product` = cp.`id_product` '.$whereProductId.')  '.
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
						product_shop.`id_shop` = '.(int)$context->shop->id.' 
						AND cp.`id_category` = '.(int)$categoryId .' 
						AND product_shop.`active` = 1 
						AND product_shop.`visibility` IN ("both", "catalog") '.
						$where.' 
					GROUP BY 
						cp.id_product 
					ORDER BY 
						`total_review` '.pSQL($order_way).'  
					Limit 
						'.(((int)$p - 1) * (int)$n).', '.(int)$n;
			}
			
				
		}elseif($order_by == 'view'){
			if($on_discount != 2){
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
							product_shop.price AS orderprice, 
							pv.`counter`, 
							sp.`reduction`, 
							sp.`from`, 
							sp.`to`  
						FROM `'._DB_PREFIX_.'category_product` cp 
							LEFT JOIN ((`'._DB_PREFIX_.'product` p INNER JOIN `'._DB_PREFIX_.'specific_price` sp On p.`id_product` = sp.`id_product`) LEFT JOIN `'._DB_PREFIX_.'page` pg ON pg.`id_object` = p.`id_product` LEFT JOIN `'._DB_PREFIX_.'page_viewed` pv ON pv.`id_page` = pg.`id_page` LEFT JOIN `'._DB_PREFIX_.'date_range` dr ON pv.`id_date_range` = dr.`id_date_range` LEFT JOIN `'._DB_PREFIX_.'page_type` pt ON pt.`id_page_type` = pg.`id_page_type` AND dr.`time_start` > "'.date('Y-m-d', strtotime('-'.$PS_NB_DAYS_NEW_PRODUCT.' DAY')).'" AND pt.`name` = \'product\') 
								ON (p.`id_product` = cp.`id_product` '.$whereProductId.')  '.
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
							product_shop.`id_shop` = '.(int)$context->shop->id.' 
							AND cp.`id_category` = '.(int)$categoryId .' 
							AND product_shop.`active` = 1 
							AND product_shop.`visibility` IN ("both", "catalog") '.
							$where.' 
						GROUP BY 
							cp.id_product 
						ORDER BY  
							pv.`counter` '.pSQL($order_way).'  
						Limit 
							'.(((int)$p - 1) * (int)$n).', '.(int)$n;
			}else{
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
							product_shop.price AS orderprice, 
							pv.`counter`  
						FROM `'._DB_PREFIX_.'category_product` cp 
							LEFT JOIN (`'._DB_PREFIX_.'product` p LEFT JOIN `'._DB_PREFIX_.'page` pg ON pg.`id_object` = p.`id_product` LEFT JOIN `'._DB_PREFIX_.'page_viewed` pv ON pv.`id_page` = pg.`id_page` LEFT JOIN `'._DB_PREFIX_.'date_range` dr ON pv.`id_date_range` = dr.`id_date_range` LEFT JOIN `'._DB_PREFIX_.'page_type` pt ON pt.`id_page_type` = pg.`id_page_type` AND dr.`time_start` > "'.date('Y-m-d', strtotime('-'.$PS_NB_DAYS_NEW_PRODUCT.' DAY')).'" AND pt.`name` = \'product\') 
								ON (p.`id_product` = cp.`id_product` '.$whereProductId.')  '.
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
							product_shop.`id_shop` = '.(int)$context->shop->id.' 
							AND cp.`id_category` = '.(int)$categoryId .' 
							AND product_shop.`active` = 1 
							AND product_shop.`visibility` IN ("both", "catalog") '.
							$where.' 
						GROUP BY 
							cp.id_product 
						ORDER BY  
							pv.`counter` '.pSQL($order_way).'  
						Limit 
							'.(((int)$p - 1) * (int)$n).', '.(int)$n;
			}
			
			
		}elseif($order_by == 'rate'){
			
			if($on_discount != 2){
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
							product_shop.price AS orderprice, 
							(SUM(pr.`grade`) / COUNT(pr.`grade`)) AS total_avg , 
							sp.reduction, 
							sp.`from`, 
							sp.`to` 
						FROM `'._DB_PREFIX_.'category_product` cp 
							LEFT JOIN ((`'._DB_PREFIX_.'product` p INNER JOIN `'._DB_PREFIX_.'specific_price` sp On p.`id_product` = sp.`id_product`) LEFT JOIN `'._DB_PREFIX_.'product_comment` pr ON pr.`id_product` = p.`id_product`) 
								ON (p.`id_product` = cp.`id_product` '.$whereProductId.')  '.
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
							product_shop.`id_shop` = '.(int)$context->shop->id.' 
							AND  cp.`id_category` = '.(int)$categoryId .' 
							AND  product_shop.`active` = 1 
							AND  product_shop.`visibility` IN ("both", "catalog") '.
							$where.'  
						GROUP BY  
							cp.id_product  
						ORDER BY  
							`total_avg` '.pSQL($order_way).'  
						Limit 
							'.(((int)$p - 1) * (int)$n).', '.(int)$n;	
			}else{
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
							product_shop.price AS orderprice, 
							(SUM(pr.`grade`) / COUNT(pr.`grade`)) AS total_avg  
						FROM `'._DB_PREFIX_.'category_product` cp 
							LEFT JOIN (`'._DB_PREFIX_.'product` p LEFT JOIN `'._DB_PREFIX_.'product_comment` pr ON pr.`id_product` = p.`id_product`) 
								ON (p.`id_product` = cp.`id_product` '.$whereProductId.')   '.
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
							product_shop.`id_shop` = '.(int)$context->shop->id.' 
							AND cp.`id_category` = '.(int)$categoryId .' 
							AND product_shop.`active` = 1 
							AND product_shop.`visibility` IN ("both", "catalog") '.
							$where.' 
						GROUP BY 
							cp.id_product 
						ORDER BY  
							`total_avg` '.pSQL($order_way).' 
						Limit 
							'.(((int)$p - 1) * (int)$n).', '.(int)$n;	
			}
		}elseif($order_by == 'seller'){			
			if($on_discount != 2){
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
							product_shop.price AS orderprice, 
							ps.`quantity` AS sales, 
							sp.reduction, 
							sp.`from`, 
							sp.`to`  
						FROM `'._DB_PREFIX_.'category_product` cp 
							LEFT JOIN ((`'._DB_PREFIX_.'product` p INNER JOIN `'._DB_PREFIX_.'specific_price` sp On p.`id_product` = sp.`id_product` ) LEFT JOIN `'._DB_PREFIX_.'product_sale` ps ON ps.`id_product` = p.`id_product`) 
								ON (p.`id_product` = cp.`id_product` '.$whereProductId.')  '.
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
							product_shop.`id_shop` = '.(int)$context->shop->id.' 
							AND cp.`id_category` = '.(int)$categoryId .' 
							AND product_shop.`active` = 1 
							AND product_shop.`visibility` IN ("both", "catalog") '.
							$where.' 
						GROUP BY 
							cp.id_product 
						ORDER BY 
							ps.`sales` '.pSQL($order_way).' 
						Limit 
							'.(((int)$p - 1) * (int)$n).', '.(int)$n;	
			}else{
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
							product_shop.price AS orderprice, 
							ps.`quantity` AS sales  
						FROM `'._DB_PREFIX_.'category_product` cp 
							LEFT JOIN (`'._DB_PREFIX_.'product` p LEFT JOIN `'._DB_PREFIX_.'product_sale` ps ON ps.`id_product` = p.`id_product`) 
								ON (p.`id_product` = cp.`id_product` '.$whereProductId.')   '.
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
							product_shop.`id_shop` = '.(int)$context->shop->id.' 
							AND  cp.`id_category` = '.(int)$categoryId .' 
							AND  product_shop.`active` = 1 
							AND  product_shop.`visibility` IN ("both", "catalog") '.
							$where.'  
						GROUP BY  
							cp.id_product  
						ORDER BY  
							ps.`sales` '.pSQL($order_way).'  
						Limit 
							'.(((int)$p - 1) * (int)$n).', '.(int)$n;
			}			
		}else{
			if($on_discount != 2){
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
							product_shop.price AS orderprice,
							sp.reduction, 
							sp.`from`, 
							sp.`to` 
						FROM `'._DB_PREFIX_.'category_product` cp 
							LEFT JOIN (`'._DB_PREFIX_.'product` p INNER JOIN `'._DB_PREFIX_.'specific_price` sp On p.`id_product` = sp.`id_product`) 
								ON (p.`id_product` = cp.`id_product` '.$whereProductId.') '.
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
							product_shop.`id_shop` = '.(int)$context->shop->id.' 
							AND  cp.`id_category` = '.(int)$categoryId .' 
							AND  product_shop.`active` = 1 
							AND  product_shop.`visibility` IN ("both", "catalog") '.
							$where.'  
						GROUP BY 
							cp.id_product 
						Limit 
							'.(((int)$p - 1) * (int)$n).', '.(int)$n;
			}else{
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
							LEFT JOIN `'._DB_PREFIX_.'product` p 
								ON (p.`id_product` = cp.`id_product` '.$whereProductId.') '.
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
							product_shop.`id_shop` = '.(int)$context->shop->id.' 
							AND cp.`id_category` = '.(int)$categoryId .' 
							AND product_shop.`active` = 1 
							AND product_shop.`visibility` IN ("both", "catalog") '.
							$where.'  
						GROUP BY  
							cp.id_product 
						Limit 
							'.(((int)$p - 1) * (int)$n).', '.(int)$n;
			}
		}
		
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		if ($order_by == 'price') Tools::orderbyPrice($result, $order_way);
		if (!$result) return array();
		return Product::getProductsProperties($id_lang, $result);
	}
	protected function getMostViewed($nb_day = 30, $limit = 10){
		$products =  Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT p.id_object, pv.counter
		FROM `'._DB_PREFIX_.'page_viewed` pv
		LEFT JOIN `'._DB_PREFIX_.'date_range` dr ON pv.`id_date_range` = dr.`id_date_range`
		LEFT JOIN `'._DB_PREFIX_.'page` p ON pv.`id_page` = p.`id_page`
		LEFT JOIN `'._DB_PREFIX_.'page_type` pt ON pt.`id_page_type` = p.`id_page_type`
        AND dr.`time_start` > "'.date('Y-m-d', strtotime('-'.(int)$nb_day.' DAY')).'"
		WHERE pt.`name` = \'product\'
		'.Shop::addSqlRestriction(false, 'pv').' 
        ORDER BY  pv.counter DESC 
		LIMIT '.(int)$limit);
        
        $most_viewed = array();
        if (is_array($products) && count($products))
			foreach ($products as $product)
			{
				$product_obj = new Product((int)$product['id_object'], true, $this->context->language->id);
				if (!Validate::isLoadedObject($product_obj)){
				    continue;
				}
					
                $product_arr = (array)$product_obj;
                $product_arr['id_product'] = (int)$product['id_object'];
                $product_arr['id_image'] = $product_obj->getCoverWs();
                $most_viewed[] =  $product_arr;
            }
        elseif (Configuration::get('VIEWED_BLOCK_DISPLAY'))
			return;
        return Product::getProductsProperties($this->context->language->id,$most_viewed);
        //return $most_viewed;
	}
	protected function _getProductIdByDate($beginning, $ending, Context $context = null, $with_combination_id = false, $id_customer=0, $deal=false)
	{
		if (!$context)
			$context = Context::getContext();

		$id_address = $context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
		$ids = Address::getCountryAndState($id_address);
		$id_country = $ids['id_country'] ? (int)$ids['id_country'] : (int)Configuration::get('PS_COUNTRY_DEFAULT');
		if (!SpecificPrice::isFeatureActive())
			return array();
		if($deal == true){
			$where = '(`from` = \'0000-00-00 00:00:00\' OR \''.pSQL($beginning).'\' >= `from`) AND (`to` != \'0000-00-00 00:00:00\' AND \''.pSQL($ending).'\' <= `to`)';
		}else{
			$where = '(`from` = \'0000-00-00 00:00:00\' OR \''.pSQL($beginning).'\' >= `from`) AND (`to` = \'0000-00-00 00:00:00\' OR \''.pSQL($ending).'\' <= `to`)';
		}
		
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT `id_product`, `id_product_attribute`
			FROM `'._DB_PREFIX_.'specific_price`
			WHERE	`id_shop` IN(0, '.(int)$context->shop->id.') AND
					`id_currency` IN(0, '.(int)$context->currency->id.') AND
					`id_country` IN(0, '.(int)$id_country.') AND
					`id_group` IN(0, '.(int)$context->customer->id_default_group.') AND
					`id_customer` IN(0, '.(int)$id_customer.') AND
					`from_quantity` = 1 AND
					('.$where.') 
					AND
					`reduction` > 0
		', false);
		$ids_product = array();
		while ($row = Db::getInstance()->nextRow($result))
			$ids_product[] = $with_combination_id ? array('id_product' => (int)$row['id_product'], 'id_product_attribute' => (int)$row['id_product_attribute']) : (int)$row['id_product'];
		return $ids_product;
	}
	protected function frontGetProductById($productId = 0, $id_lang, $active = true, Context $context = null){
		if(!$productId) return array();
		if (!$context) $context = Context::getContext();
		$front = true;
		if (!in_array($context->controller->controller_type, array('front', 'modulefront'))) $front = false;		
		
		
		if (!Validate::isBool($active)) die (Tools::displayError());
		$id_supplier = (int)Tools::getValue('id_supplier');		
		$sql = 'SELECT
				p.id_product,  MAX(product_attribute_shop.id_product_attribute) id_product_attribute, pl.`link_rewrite`, pl.`name`, pl.`description_short`, product_shop.`id_category_default`,
				MAX(image_shop.`id_image`) id_image, il.`legend`, p.`ean13`, p.`upc`, cl.`link_rewrite` AS category, p.show_price, p.available_for_order, IFNULL(stock.quantity, 0) as quantity, p.customizable,
				IFNULL(pa.minimal_quantity, p.minimal_quantity) as minimal_quantity, stock.out_of_stock,
				product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.(Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ? (int)Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY')).'" as new,
				product_shop.`on_sale`, MAX(product_attribute_shop.minimal_quantity) AS product_attribute_minimal_quantity, product_shop.price AS orderprice 
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
				WHERE product_shop.`id_shop` = '.(int)$context->shop->id.'
					AND product_shop.id_product =  '.$productId.
					' AND product_shop.`active` = 1'.
					' AND product_shop.`visibility` IN ("both", "catalog")'.
					($id_supplier ? ' AND p.id_supplier = '.(int)$id_supplier : '').
					' GROUP BY product_shop.id_product';
		
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);		
		if (!$result) return array();		
		return Product::getProductProperties($id_lang, $result);
	}
	protected function frontGetProducts($categoryIds = array(), $on_condition='all', $on_sale=2, $on_new=2, $on_discount=2, $id_lang, $p, $n, $order_by = null, $order_way = null, $beginning=null, $ending=null, $deal=false, $get_total = false, $active = true, $random = false, $random_number_products = 1, Context $context = null){		
		if(!$categoryIds) return array();		
		$where = "";
		if($on_condition != 'all'){
             $where .= " AND p.condition = '".$on_condition."' ";                
        }
		if($on_sale != 2){
			$where .= " AND p.on_sale = '".$on_sale."' ";
		}
        Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ? $PS_NB_DAYS_NEW_PRODUCT = (int)Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : $PS_NB_DAYS_NEW_PRODUCT = 20;
		if($on_new == 0){
			$where .= " AND product_shop.`date_add` <= '".date('Y-m-d', strtotime('-'.$PS_NB_DAYS_NEW_PRODUCT.' DAY'))."' ";
		}elseif($on_new == 1){
			$where .= " AND product_shop.`date_add` > '".date('Y-m-d', strtotime('-'.$PS_NB_DAYS_NEW_PRODUCT.' DAY'))."' ";
		}
		$ids_product = '';
		if($on_discount == 0){
			$current_date = date('Y-m-d H:i:s');
			$product_reductions = $this->_getProductIdByDate((!$beginning ? $current_date : $beginning), (!$ending ? $current_date : $ending), $context, true, 0, $deal);		
			if ($product_reductions){
				$ids_product = ' AND (';
				foreach ($product_reductions as $product_reduction)
					$ids_product .= '( product_shop.`id_product` != '.(int)$product_reduction['id_product'].($product_reduction['id_product_attribute'] ? ' OR product_attribute_shop.`id_product_attribute`='.(int)$product_reduction['id_product_attribute'] :'').') AND';
				$ids_product = rtrim($ids_product, 'AND').')';
			}
		}elseif($on_discount == 1){
			$current_date = date('Y-m-d H:i:s');
			$product_reductions = $this->_getProductIdByDate((!$beginning ? $current_date : $beginning), (!$ending ? $current_date : $ending), $context, true, 0, $deal);            		
			if ($product_reductions)
			{
				$ids_product = ' AND (';
				foreach ($product_reductions as $product_reduction)
					$ids_product .= '( product_shop.`id_product` = '.(int)$product_reduction['id_product'].($product_reduction['id_product_attribute'] ? ' AND product_attribute_shop.`id_product_attribute`='.(int)$product_reduction['id_product_attribute'] :'').') OR';
				$ids_product = rtrim($ids_product, 'OR').')';
			}else{
		      if($deal == true) return array();
			}
		}else{
			if($order_by == 'discount'){
				$current_date = date('Y-m-d H:i:s');
				$product_reductions = $this->_getProductIdByDate((!$beginning ? $current_date : $beginning), (!$ending ? $current_date : $ending), $context, true, 0, $deal);		
				if ($product_reductions){
					$ids_product = ' AND (';
					foreach ($product_reductions as $product_reduction)
						$ids_product .= '( product_shop.`id_product` = '.(int)$product_reduction['id_product'].($product_reduction['id_product_attribute'] ? ' AND product_attribute_shop.`id_product_attribute`='.(int)$product_reduction['id_product_attribute'] :'').') OR';
					$ids_product = rtrim($ids_product, 'OR').')';
				}				
			}
		}		
		if($ids_product) $where .= $ids_product;
		if (!$context) $context = Context::getContext();
		$front = true;
		if (!in_array($context->controller->controller_type, array('front', 'modulefront'))) $front = false;		
		if ($p < 1) $p = 1;
		
		if (empty($order_by)){
			$order_by = 'position';
		}else{
			$order_by = strtolower($order_by);
		}			
		if (empty($order_way)) $order_way = 'ASC';		
		$order_by_prefix = false;
		
		$addJoin = '';
		$addSelect = '';	
		if ($order_by == 'id_product' || $order_by == 'date_add' || $order_by == 'date_upd'){
			$order_by_prefix = 'p';
		}elseif ($order_by == 'name'){
			$order_by_prefix = 'pl';
		}elseif ($order_by == 'manufacturer' || $order_by == 'manufacturer_name'){
			$order_by_prefix = 'm';
			$order_by = 'name';
		}elseif ($order_by == 'position'){
			$order_by_prefix = 'cp';
		}elseif($order_by == 'discount'){
			$order_by_prefix = 'sp';
			$order_by = 'reduction';
			$addJoin = ' LEFT JOIN `'._DB_PREFIX_.'specific_price` sp On p.`id_product` = sp.`id_product` ';
			$addSelect = ', sp.reduction, sp.`from`, sp.`to`';
		}elseif($order_by == 'review'){
			$order_by_prefix = '';
			$order_by = 'total_review';
			$addJoin = ' LEFT JOIN `'._DB_PREFIX_.'product_comment` pr ON pr.`id_product` = p.`id_product` ';
			$addSelect = ', COUNT(pr.grade) as total_review';
		}elseif($order_by == 'view'){
			$order_by_prefix = '';			
			$addJoin = ' 
				LEFT JOIN `'._DB_PREFIX_.'page` pg
					ON pg.`id_object` = p.`id_product` 
				LEFT JOIN `'._DB_PREFIX_.'page_viewed` pv 
					ON pv.`id_page` = pg.`id_page`
				LEFT JOIN `'._DB_PREFIX_.'date_range` dr 
					ON pv.`id_date_range` = dr.`id_date_range` 				
				LEFT JOIN `'._DB_PREFIX_.'page_type` pt 
					ON 
						pt.`id_page_type` = pg.`id_page_type` 
						AND dr.`time_start` > "'.date('Y-m-d', strtotime('-'.$PS_NB_DAYS_NEW_PRODUCT.' DAY')).'" 
						AND pt.`name` = \'product\'';
			$addSelect = ' , pv.counter';			
			$order_by = 'counter';
		}elseif($order_by == 'rate'){
			$order_by_prefix = '';
			$order_by = 'total_avg';
			$addJoin = ' LEFT JOIN `'._DB_PREFIX_.'product_comment` pr ON pr.`id_product` = p.`id_product` ';
			$addSelect = ', (SUM(pr.`grade`) / COUNT(pr.`grade`)) AS total_avg';
		}elseif($order_by == 'seller'){
			$order_by_prefix = '';
			$order_by = 'sales';
			$addJoin = ' LEFT JOIN `'._DB_PREFIX_.'product_sale` ps ON ps.`id_product` = p.`id_product` ';
			$addSelect = ', ps.`quantity` AS sales';
		} 
		if($order_by != 'reduction' && $on_discount != 2){
			$addJoin = ' LEFT JOIN `'._DB_PREFIX_.'specific_price` sp On p.`id_product` = sp.`id_product` ';
			$addSelect = ', sp.reduction, sp.`from`, sp.`to`';
		}
		if ($order_by == 'price') $order_by = 'orderprice';
		
		
		
		
		if (!Validate::isBool($active) || !Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)) die (Tools::displayError());
		$id_supplier = (int)Tools::getValue('id_supplier');
		
		if ($get_total)
		{
			$sql = 'SELECT COUNT(cp.`id_product`) AS total
					FROM `'._DB_PREFIX_.'product` p 					
					'.Shop::addSqlAssociation('product', 'p').' '.$addJoin.'
					LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON p.`id_product` = cp.`id_product`
					WHERE cp.`id_category` IN ('.implode(', ', $categoryIds).') '.$where. 
					($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').
					($active ? ' AND product_shop.`active` = 1' : '').
					(($ids_product) ? $ids_product : '').
					($id_supplier ? 'AND p.id_supplier = '.(int)$id_supplier : '');
			return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
		}
        
		
		
		$sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) AS quantity , pl.`description`, pl.`description_short`, pl.`available_now`,
					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,
					il.`legend` as legend, cl.`name` AS category_default,
					DATEDIFF(product_shop.`date_add`, DATE_SUB("'.date('Y-m-d').' 00:00:00",
					INTERVAL '.(int)$PS_NB_DAYS_NEW_PRODUCT.' DAY)) > 0 AS new, product_shop.price AS orderprice '.$addSelect.'
				FROM `'._DB_PREFIX_.'category_product` cp 
				LEFT JOIN `'._DB_PREFIX_.'product` p 
					ON p.`id_product` = cp.`id_product` 
				'.Shop::addSqlAssociation('product', 'p').$addJoin.
				Product::sqlStock('p', 0).' 
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl 
					ON (product_shop.`id_category_default` = cl.`id_category` 
					AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl 
					ON (p.`id_product` = pl.`id_product` 
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
				LEFT JOIN `'._DB_PREFIX_.'image` i 
					ON (i.`id_product` = p.`id_product`) '.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').' 
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il 
					ON (image_shop.`id_image` = il.`id_image` 
					AND il.`id_lang` = '.(int)$id_lang.')
				WHERE product_shop.`id_shop` = '.(int)$context->shop->id.' 
					AND cp.`id_category` IN ('.implode(', ', $categoryIds).') '
					.$where 
					.($active ? ' AND product_shop.`active` = 1' : '')
					.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '')
					.' GROUP BY product_shop.id_product';
		if ($random === true) $sql .= ' ORDER BY RAND() LIMIT '.(int)$random_number_products;		
		else $sql .= ' ORDER BY '.(!empty($order_by_prefix) ? $order_by_prefix.'.' : '').'`'.bqSQL($order_by).'` '.pSQL($order_way).' LIMIT '.(((int)$p - 1) * (int)$n).','.(int)$n;        		
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);		
		if ($order_by == 'orderprice') Tools::orderbyPrice($result, $order_way);
		if (!$result) return array();
		return Product::getProductsProperties($id_lang, $result);
	}



















































    public static function initPricesComputation($id_customer = null)
	{
		if ($id_customer)
		{
			$customer = new Customer((int)$id_customer);
			if (!Validate::isLoadedObject($customer))
				die(Tools::displayError());
			self::$_taxCalculationMethod = Group::getPriceDisplayMethod((int)$customer->id_default_group);
			$cur_cart = Context::getContext()->cart;
			$id_address = 0;
			if (Validate::isLoadedObject($cur_cart))
				$id_address = (int)$cur_cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
			$address_infos = Address::getCountryAndState($id_address);

			if (self::$_taxCalculationMethod != PS_TAX_EXC
				&& !empty($address_infos['vat_number'])
				&& $address_infos['id_country'] != Configuration::get('VATNUMBER_COUNTRY')
				&& Configuration::get('VATNUMBER_MANAGEMENT'))
				self::$_taxCalculationMethod = PS_TAX_EXC;
		}
		else
			self::$_taxCalculationMethod = Group::getPriceDisplayMethod(Group::getCurrent()->id);
	}

	public static function getTaxCalculationMethod($id_customer = null)
	{
		if (self::$_taxCalculationMethod === null || $id_customer !== null)
			self::initPricesComputation($id_customer);

		return (int)self::$_taxCalculationMethod;
	}
    public static function getProductsProperties($id_lang, $query_result)
	{
		$results_array = array();
		if (is_array($query_result))
			foreach ($query_result as $row)
				if ($row2 = self::getProductProperties($id_lang, $row))
					$results_array[] = $row2;

		return $results_array;
	}
    public static function getProductProperties($id_lang, $row, Context $context = null)
	{
		if (!$row['id_product'])
			return false;

		if ($context == null)
			$context = Context::getContext();

		// Product::getDefaultAttribute is only called if id_product_attribute is missing from the SQL query at the origin of it:
		// consider adding it in order to avoid unnecessary queries
		$row['allow_oosp'] = Product::isAvailableWhenOutOfStock($row['out_of_stock']);
		if (Combination::isFeatureActive() && (!isset($row['id_product_attribute']) || !$row['id_product_attribute'])
			&& ((isset($row['cache_default_attribute']) && ($ipa_default = $row['cache_default_attribute']) !== null)
				|| ($ipa_default = Product::getDefaultAttribute($row['id_product'], !$row['allow_oosp']))))
			$row['id_product_attribute'] = $ipa_default;
		if (!Combination::isFeatureActive() || !isset($row['id_product_attribute']))
			$row['id_product_attribute'] = 0;

		// Tax
		$usetax = Tax::excludeTaxeOption();

		$cache_key = $row['id_product'].'-'.$row['id_product_attribute'].'-'.$id_lang.'-'.(int)$usetax;
		if (isset($row['id_product_pack']))
			$cache_key .= '-pack'.$row['id_product_pack'];

		if (isset(self::$cacheProducProperties[$cache_key]))
			return array_merge($row, self::$cacheProducProperties[$cache_key]);

		// Datas
		$row['category'] = Category::getLinkRewrite((int)$row['id_category_default'], (int)$id_lang);
		$row['link'] = $context->link->getProductLink((int)$row['id_product'], $row['link_rewrite'], $row['category'], $row['ean13']);

		$row['attribute_price'] = 0;
		if (isset($row['id_product_attribute']) && $row['id_product_attribute'])
			$row['attribute_price'] = (float)Product::getProductAttributePrice($row['id_product_attribute']);

		$row['price_tax_exc'] = Product::getPriceStatic(
			(int)$row['id_product'],
			false,
			((isset($row['id_product_attribute']) && !empty($row['id_product_attribute'])) ? (int)$row['id_product_attribute'] : null),
			(self::$_taxCalculationMethod == PS_TAX_EXC ? 2 : 6)
		);

		if (self::$_taxCalculationMethod == PS_TAX_EXC)
		{
			$row['price_tax_exc'] = Tools::ps_round($row['price_tax_exc'], 2);
			$row['price'] = Product::getPriceStatic(
				(int)$row['id_product'],
				true,
				((isset($row['id_product_attribute']) && !empty($row['id_product_attribute'])) ? (int)$row['id_product_attribute'] : null),
				6
			);
			$row['price_without_reduction'] = Product::getPriceStatic(
				(int)$row['id_product'],
				false,
				((isset($row['id_product_attribute']) && !empty($row['id_product_attribute'])) ? (int)$row['id_product_attribute'] : null),
				2,
				null,
				false,
				false
			);
		}
		else
		{
			$row['price'] = Tools::ps_round(
				Product::getPriceStatic(
					(int)$row['id_product'],
					true,
					((isset($row['id_product_attribute']) && !empty($row['id_product_attribute'])) ? (int)$row['id_product_attribute'] : null),
					2
				),
				2
			);

			$row['price_without_reduction'] = Product::getPriceStatic(
				(int)$row['id_product'],
				true,
				((isset($row['id_product_attribute']) && !empty($row['id_product_attribute'])) ? (int)$row['id_product_attribute'] : null),
				6,
				null,
				false,
				false
			);
		}

		$row['reduction'] = Product::getPriceStatic(
			(int)$row['id_product'],
			(bool)$usetax,
			(int)$row['id_product_attribute'],
			6,
			null,
			true,
			true,
			1,
			true,
			null,
			null,
			null,
			$specific_prices
		);

		$row['specific_prices'] = $specific_prices;

		$row['quantity'] = Product::getQuantity(
			(int)$row['id_product'],
			0,
			isset($row['cache_is_pack']) ? $row['cache_is_pack'] : null
		);

		$row['quantity_all_versions'] = $row['quantity'];

		if ($row['id_product_attribute'])
			$row['quantity'] = Product::getQuantity(
				(int)$row['id_product'],
    			$row['id_product_attribute'],
			   isset($row['cache_is_pack']) ? $row['cache_is_pack'] : null
			);

		$row['id_image'] = Product::defineProductImage($row, $id_lang);
		$row['features'] = Product::getFrontFeaturesStatic((int)$id_lang, $row['id_product']);

		$row['attachments'] = array();
		if (!isset($row['cache_has_attachments']) || $row['cache_has_attachments'])
			$row['attachments'] = Product::getAttachmentsStatic((int)$id_lang, $row['id_product']);

		$row['virtual'] = ((!isset($row['is_virtual']) || $row['is_virtual']) ? 1 : 0);

		// Pack management
		$row['pack'] = (!isset($row['cache_is_pack']) ? Pack::isPack($row['id_product']) : (int)$row['cache_is_pack']);
		$row['packItems'] = $row['pack'] ? Pack::getItemTable($row['id_product'], $id_lang) : array();
		$row['nopackprice'] = $row['pack'] ? Pack::noPackPrice($row['id_product']) : 0;
		if ($row['pack'] && !Pack::isInStock($row['id_product']))
			$row['quantity'] = 0;

		$row['customization_required'] = false;
		if (isset($row['customizable']) && $row['customizable'] && Customization::isFeatureActive())
			if (count(Product::getRequiredCustomizableFieldsStatic((int)$row['id_product'])))
				$row['customization_required'] = true;

		$row = Product::getTaxesInformations($row, $context);
		self::$cacheProducProperties[$cache_key] = $row;
		return self::$cacheProducProperties[$cache_key];
	}
    function getProductAttributesOther($products){
        return $products;
    	if($products){
        	$currency = new Currency($this->context->currency->id);
        	$timeNow = time();			
			$http = 'http';		
		 	if (isset($_SERVER["HTTPS"]) &&  $_SERVER["HTTPS"] == "on") {$http .= "s";}
		 	$http .= "://";
            foreach($products as $i=> &$product){
            	$product['image'] = Context::getContext()->link->getImageLink($product['link_rewrite'], $product['id_image'], 'home_default');
                if(strpos($product['image'], $http) === false) $product['image'] = $http.$product['image'];				
				$product['price_new'] = number_format(Tools::convertPriceFull($product['price'], $currency), 2, '.', ',');
				$product['price_old'] = '0';
				$product['reduction'] = '';
				if($product['specific_prices']){
				    
                	$from = strtotime($product['specific_prices']['from']);
                    $to = strtotime($product['specific_prices']['to']);                    
					if($product['specific_prices']['from_quantity'] == '1' && (($timeNow >= $from && $timeNow <= $to) || ($product['specific_prices']['to'] == '0000-00-00 00:00:00'))){
						$product['price_old'] = number_format(Tools::convertPriceFull($product['price_without_reduction'], $currency), 2, '.', ',');												
						if($product['specific_prices']['reduction_type'] == 'percentage'){
							$product['reduction'] = ($product['specific_prices']['reduction']*100).'%';
						}else{
							$product['reduction'] = number_format(Tools::convertPriceFull($product['specific_prices']['reduction'], $currency), 2, '.', ',');
						}						
                    }
                }
				$product['rates'] = '';
				$product['totalRates'] = '0';		
				if(Module::isInstalled('productcomments') == 1){
					$productRate = $this->getProductRatings($product['id_product']);				
					if(isset($productRate) && $productRate['avg'] >0){
						if($productRate['total'] >1)
							$product['totalRates'] = $productRate['total'].'s';
						else
							$product['totalRates'] = $productRate['total'];
						for($i=0; $i<5; $i++){
							if($productRate['avg'] >= $i){
								$product['rates'] .= '<div class="star"></div>';
							}else{
								$product['rates'] .= '<div class="star star_off"></div>';
							}
						}
					}else{
						$product['rates'] .= '<div class="star star_off"></div>';
						$product['rates'] .= '<div class="star star_off"></div>';
						$product['rates'] .= '<div class="star star_off"></div>';
						$product['rates'] .= '<div class="star star_off"></div>';
						$product['rates'] .= '<div class="star star_off"></div>';
					}
				}else{
					$product['rates'] .= '<div class="star star_off"></div>';
					$product['rates'] .= '<div class="star star_off"></div>';
					$product['rates'] .= '<div class="star star_off"></div>';
					$product['rates'] .= '<div class="star star_off"></div>';
					$product['rates'] .= '<div class="star star_off"></div>';
				}
				$product['isCompare'] = 0;									
				if($this->compareProductIds)
					if(in_array($product['id_product'], $this->compareProductIds)) $product['isCompare'] = 1;                
				
            }
        }
        return $products;
    }
   
    function clearCache(){
		Tools::clearCache();
		return true;
    	$themeOption = @Configuration::get('OVIC_CURRENT_OPTION');
		if(!$themeOption) $themeOption = 'gc';
		parent::_clearCache('groupcategory.tpl');
		if($this->arrLayout)
			foreach ($this->arrLayout as $key => $value){
				parent::_clearCache('banners.option'.$themeOption.'.'.$key.'.tpl');
				parent::_clearCache('product-list-nocache.option'.$themeOption.'.'.$key.'.tpl');
				parent::_clearCache('product-list.option'.$themeOption.'.'.$key.'.tpl');
				parent::_clearCache('manufacturer.option'.$themeOption.'.'.$key.'.tpl');
				parent::_clearCache('sub-list.option'.$themeOption.'.'.$key.'.tpl');
			} 		
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
        
    	$sql = 'SELECT p.id_product, p.ean13, p.reference, p.id_category_default, p.on_sale, p.quantity, p.minimal_quantity, p.price, p.wholesale_price, p.quantity_discount, p.show_price, p.condition, 
                product_shop.on_sale, product_shop.id_category_default, product_shop.minimal_quantity, product_shop.price, product_shop.wholesale_price, product_shop.show_price, product_shop.condition, product_shop.indexed, 
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
                    AND product_shop.`active` = 1 AND product_shop.`visibility` IN ("both", "catalog") '.$where.' 
                    GROUP BY product_shop.id_product
                    ORDER BY `'.pSQL($order_by).'` '.pSQL($order_way).' Limit '.$offset.', '.$limit;
		$result = DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);               
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
        
        	$sql = 'SELECT p.id_product, p.ean13, p.reference, p.id_category_default, p.on_sale, p.quantity, p.minimal_quantity, p.price, p.wholesale_price, p.quantity_discount, p.show_price, p.condition, 
                    product_shop.on_sale, product_shop.id_category_default, product_shop.minimal_quantity, product_shop.price, product_shop.wholesale_price, product_shop.show_price, product_shop.condition, product_shop.indexed, 
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
                    AND product_shop.`active` = 1 AND product_shop.`visibility` IN ("both", "catalog") '.$where.' 
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
                    AND product_shop.`active` = 1 AND product_shop.`visibility` IN ("both", "catalog") '.$where.' 
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
        
        	$sql = 'SELECT p.id_product, p.ean13, p.reference, p.id_category_default, p.on_sale, p.quantity, p.minimal_quantity, p.price, p.wholesale_price, p.quantity_discount, p.show_price, p.condition, 
                    product_shop.on_sale, product_shop.id_category_default, product_shop.minimal_quantity, product_shop.price, product_shop.wholesale_price, product_shop.show_price, product_shop.condition, product_shop.indexed, 
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
                    AND product_shop.`active` = 1 AND product_shop.`visibility` IN ("both", "catalog") '.$where.' 
                    GROUP BY product_shop.id_product
                    ORDER BY p.`'.pSQL($order_by).'` '.pSQL($order_way).' Limit '.$offset.', '.$limit;
                    
        
           $result = DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            
          if (!$result) return false;
            if($getProperties == false) return $result;           
    		return Product::getProductsProperties($id_lang, $result);
    }
    function getProductById($id_lang, $productId, $short = true, $getProperties = true){
        $context = Context::getContext();
        if($short == true){
        	$sql = 'SELECT p.id_product, p.ean13, p.reference, p.id_category_default, p.on_sale, p.quantity, p.minimal_quantity, p.price, p.wholesale_price, p.quantity_discount, p.show_price, p.condition, p.date_add, p.date_upd, 
                    product_shop.on_sale, product_shop.id_category_default, product_shop.minimal_quantity, product_shop.price, product_shop.wholesale_price, product_shop.show_price, product_shop.condition, product_shop.indexed, product_shop.date_add, product_shop.date_upd, 
                    stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity,                     
					pl.`available_later`, pl.`link_rewrite`, pl.`name`, MAX(image_shop.`id_image`) id_image,
					il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new, product_shop.price AS orderprice';
        }else{            
            $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,
					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,
					il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new, product_shop.price AS orderprice';
            
        }
        $sql .= ' FROM `'._DB_PREFIX_.'category_product` cp
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
                    AND product_shop.`active` = 1 AND product_shop.`visibility` IN ("both", "catalog")
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
					AND p.`active` = 1 AND p.`visibility` != \'none\' '.$where;			
			return DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);					
		}
        
    	$sql = 'SELECT p.id_product, p.ean13, p.reference, p.id_category_default, p.on_sale, p.quantity, p.minimal_quantity, p.price, p.wholesale_price, p.quantity_discount, p.show_price, 
                product_shop.on_sale, product_shop.id_category_default, product_shop.minimal_quantity, product_shop.price, product_shop.wholesale_price, product_shop.show_price, product_shop.condition, product_shop.indexed, 
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
                    AND product_shop.`active` = 1 AND product_shop.`visibility` IN ("both", "catalog") '.$where.' 
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
        
        if($short == true){
        	$sql = 'SELECT p.id_product, p.ean13, p.reference, p.id_category_default, p.on_sale, p.quantity, p.minimal_quantity, p.price, p.wholesale_price, p.quantity_discount, p.show_price, p.condition, p.date_add, p.date_upd, 
                    product_shop.on_sale, product_shop.id_category_default, product_shop.minimal_quantity, product_shop.price, product_shop.wholesale_price, product_shop.show_price, product_shop.condition, product_shop.indexed, product_shop.date_add, product_shop.date_upd, 
                    stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity,                     
					pl.`available_later`, pl.`link_rewrite`, pl.`name`, MAX(image_shop.`id_image`) id_image,
					il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new, product_shop.price AS orderprice';
        }else{            
            $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,
					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,
					il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new, product_shop.price AS orderprice ';
            
        }
        
        $sql .= ' FROM `'._DB_PREFIX_.'category_product` cp
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
                    AND product_shop.`active` = 1 AND product_shop.`visibility` IN ("both", "catalog") 
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
        $total = (int)DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sqlTotal);
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
    function getProductsOrderView($id_lang, $arrCategory = array(), $params = null, $total=false, $short = true, $limit, $offset = 0, $getProperties = true){
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
        
    
    	$sql = 'SELECT p.id_product, p.ean13, p.reference, p.id_category_default, p.on_sale, p.quantity, p.minimal_quantity, p.price, p.wholesale_price, p.quantity_discount, p.show_price, p.condition, 
                product_shop.on_sale, product_shop.id_category_default, product_shop.minimal_quantity, product_shop.price, product_shop.wholesale_price, product_shop.show_price, product_shop.condition, product_shop.indexed, 
                stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity,                     
				pl.`available_later`, pl.`link_rewrite`, pl.`name`, MAX(image_shop.`id_image`) id_image,
				DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
				INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
					DAY)) > 0 AS new, product_shop.price AS orderprice';
       
        $sql .= ' FROM  `'._DB_PREFIX_.'product` p 
                LEFT JOIN `'._DB_PREFIX_.'groupcategory_product_view` AS gv 
                    On gv.productId = p.id_product 
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
                    AND product_shop.`active` = 1 AND product_shop.`visibility` IN ("both", "catalog") '.$where.' 
                    GROUP BY product_shop.id_product 
                    ORDER BY gv.`total` DESC Limit '.$offset.', '.$limit;
                    
            

           $result = DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            
          if (!$result) return false;
            if($getProperties == false) return $result;            
    		return Product::getProductsProperties($id_lang, $result);
    }


    // using cache
    // copy from megamenu
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

    protected function deleteCacheProduct($productId=0){
		if(!$productId) return false;
		$languages = $this->getAllLanguage();
		$shopId = $this->context->shop->id;
		if($languages){
			foreach($languages as $language){
				$this->cache->delete('product_'.$productId.'_'.$language->id.'_'.$shopId);
			}
		}
	}
	protected function setCacheProduct($productId=0){
		if(!$productId) return false;
		$languages = $this->getAllLanguage();
		$shopId = $this->context->shop->id;
		if($languages){
			foreach($languages as $language){
				$this->cache->set('product_'.$productId.'_'.$language['id'].'_'.$shopId, $this->_getProductById_before($productId, $language->id, $shopId));
			}
		}
	}
	public function hookAddProduct($params){
		if (!isset($params['product'])) return;
		if(_PS_CACHE_ENABLED_)
			$this->setCacheProduct((int)$params['product']->id);
		return true;
	}
	public function hookUpdateProduct($params){
		if (!isset($params['product'])) return;		
		if(_PS_CACHE_ENABLED_)
			$this->deleteCacheProduct((int)$params['product']->id);
		return true;
	}

	public function hookDeleteProduct($params){
		if (!isset($params['product'])) return;
		if(_PS_CACHE_ENABLED_)
			$this->deleteCacheProduct((int)$params['product']->id);
		return true;
	}
    
}
