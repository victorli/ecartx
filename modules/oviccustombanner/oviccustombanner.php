<?php
/*
*  @author SonNC Ovic <nguyencaoson.zpt@gmail.com>
*/
class OvicCustomBanner extends Module
{
    const INSTALL_SQL_FILE = 'install.sql';	
    public $arrLayout = array('default'=>'Default');
	public $imageHomeSize = array();
    public $arrCols = array('none-col'=>'None Column', 'col-sm-1'=>'1 Column', 'col-sm-2'=>'2 Columns', 'col-sm-3'=>'3 Columns', 'col-sm-4'=>'4 Columns', 'col-sm-5'=>'5 Columns', 'col-sm-6'=>'6 Columns', 'col-sm-7'=>'7 Columns', 'col-sm-8'=>'8 Columns', 'col-sm-9'=>'9 Columns', 'col-sm-10'=>'10 Columns', 'col-sm-11'=>'11 Columns', 'col-sm-12'=>'12 Columns');
	public $livePath = '';
	protected static $arrPosition = array('displayCustomBanner1', 'displayCustomBanner2', 'displayCustomBanner3', 'displayGroupFashions', 'displayGroupFoods', 'displayGroupSports');
	public function __construct()
	{
				
		$this->name = 'oviccustombanner';
		$this->tab = 'front_office_features';
		$this->version = '1.0';
		$this->author = 'OvicSoft';		
		$this->secure_key = Tools::encrypt('ovic-soft'.$this->name);
		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->l('Supershop - Custom Banner Module');
		$this->description = $this->l('Custom Banner Module');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
		$this->pathTemp = dirname(__FILE__).'/images/temps/';
        $this->pathBanner = dirname(__FILE__).'/images/';
		if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
			$this->livePath = _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/oviccustombanner/images/'; 
		else
			$this->livePath = _PS_BASE_URL_.__PS_BASE_URI__.'modules/oviccustombanner/images/';
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
			|| !$this->registerHook('displayCustomBanner1')
			|| !$this->registerHook('displayCustomBanner2')
			|| !$this->registerHook('displayCustomBanner3')
			|| !$this->registerHook('displayGroupFashions')
			|| !$this->registerHook('displayGroupFoods')
			|| !$this->registerHook('displayGroupSports')) return false;
		if (!Configuration::updateGlobalValue('MOD_CUSTOM_BANNER', '1')) return false;
        $this->clearCache();		
		$this->moduleUpdatePosition();
		return true;
	}
	public function moduleUpdatePosition(){
		$items = DB::getInstance()->executeS("Select DISTINCT position_name From "._DB_PREFIX_."ovic_custom_banners  Where `position_name` <> ''");
		if($items){
			foreach ($items as $key => $item) {
				$position = Hook::getIdByName($item['position_name']);
				DB::getInstance()->execute("Update "._DB_PREFIX_."ovic_custom_banners Set position = '".$position."' Where `position_name` = '".$item['position_name']."'");
			}
		}
	}
	public function uninstall($keep = true)
	{	   
		if (!parent::uninstall()) return false;
		$this->clearCache();
		
        if($keep){
			
            if(!Db::getInstance()->execute('
			DROP TABLE IF EXISTS
			`'._DB_PREFIX_.'ovic_custom_banner_lang`,
			`'._DB_PREFIX_.'ovic_custom_banners`')) return false;
			
        }
		
        if (!Configuration::deleteByName('MOD_CUSTOM_BANNER')) return false;
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
	public function getBannerSrc($image = '', $check = false){
        if($image && file_exists($this->pathBanner.$image))
            return $this->livePath.$image;
        else
            if($check == true) 
                return '';
            else
                return $this->livePath.'default.jpg'; 
    }
	public function getLangOptions($langId = 0){
        if(intval($langId) == 0) $langId = Context::getContext()->language->id;
        $items = DB::getInstance()->executeS("Select id_lang, name, iso_code From "._DB_PREFIX_."lang Where active = 1");
        $options = '';
        if($items){
            foreach($items as $item){
                if($item['id_lang'] == $langId){
                    $options .= '<option value="'.$item['id_lang'].'" selected="selected">'.$item['iso_code'].'</option>';
                }else{
                    $options .= '<option value="'.$item['id_lang'].'">'.$item['iso_code'].'</option>';
                }
            }
        }
        return $options;
    }
	public function getArrLangs(){
        $langId = Context::getContext()->language->id;
        $items = DB::getInstance()->executeS("Select id_lang, name, iso_code From "._DB_PREFIX_."lang Where active = 1 Order By id_lang");
        $arr = array();
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
				$arr[$i] = $objItem;
            }
        }
        return $arr;
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
        $options = '';
        if($items){
            foreach($items as $item){
                if($selected == $item['id_hook']) $options .= '<option selected="selected" value="'.$item['id_hook'].'">'.Hook::getNameById($item['id_hook']).'</option>';
                else $options .= '<option value="'.$item['id_hook'].'">'.Hook::getNameById($item['id_hook']).'</option>';
            }
        }
        return $options;
		 * 
		 */ 
    }
    public function getLayoutOptions($selected=''){
        $options = '';        
        foreach($this->arrLayout as $key=>$value){
            if($selected == $key) $options .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
            else $options .= '<option value="'.$key.'">'.$value.'</option>';
        }        
        return $options;
    }
    public function getColumnsOptions($selected=''){
        $options = '';        
        foreach($this->arrCols as $key=>$value){
            if($selected == $key) $options .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
            else $options .= '<option value="'.$key.'">'.$value.'</option>';
        }        
        return $options;
    }
	public function getBannerByLang($itemId=0, $langId=0, $shopId=0){
		if($langId == 0) $langId = Context::getContext()->language->id;
        //if($shopId) $shopId = Context::getContext()->shop->id;
		$item = array();
		if($itemId) $item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."ovic_custom_banner_lang Where `bannerId` = ".$itemId." AND `id_lang` = ".$langId);
		if(!$item){			
			$item['banner_image'] = '';
			$item['banner_link'] = '';
			$item['banner_title'] = '';
		} 		
		return $item;		
	}
	public function getBanner($itemId=0){		
		$item = array();
		if($itemId) $item = DB::getInstance()->getRow("Select name From "._DB_PREFIX_."ovic_custom_banners Where `id` = ".$itemId);
        $params = array('layout'=>'default', 'width'=>'none-column', 'className'=>'');	
		if(!$item){			
			$item['id'] = 0;
			$item['position'] = 0;
			$item['status'] = 1;
			$item['params'] = '';
			$item['ordering'] = 1;
            		
		}else{
            if($item['params']){
                $params = get_object_vars(json_decode($item['params']));
            }
		}
		return $item;		
	}
	public function getAllBanner(){
		$langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;
		$items = DB::getInstance()->executeS("Select * From "._DB_PREFIX_."ovic_custom_banners Where id_shop='".$shopId."' Order By position, ordering");
		$content = '';
		if($items){
            foreach($items as $item){
                if($item['status'] == "1"){
                    $status = '<a title="Enabled" class="list-action-enable action-enabled lik-banner-status" item-id="'.$item['id'].'" value="'.$item['status'].'"><i class="icon-check"></i></a>';
                }else{
                    $status = '<a title="Disabled" class="list-action-enable action-disabled lik-banner-status" item-id="'.$item['id'].'" value="'.$item['status'].'"><i class="icon-check"></i></a>';
                }                
                $params = json_decode($item['params']);                			
                $itemLang = $this->getBannerByLang($item['id'], $langId, $shopId);                
                $content .= '<tr id="bn_'.$item['id'].'"><td><img width="80" src="'.$this->getBannerSrc($itemLang['banner_image']).'" alt="'.$itemLang['banner_title'].'" /></td><td class="center">'.Hook::getNameById($item['position']).'</td><td class="pointer dragHandle center" ><div class="dragGroup"><div class="positions">'.$item['ordering'].'</div></div></td><td class="center">'.$status.'</td><td class="center"><a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-banner-edit"><i class="icon-edit"></i></a>&nbsp;<a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-banner-delete"><i class="icon-trash" ></i></a></td></tr>';
            }
        }
		return $content;
	}
	
	public function ovicRenderForm($id = 0){		
		$shopId = Context::getContext()->shop->id;
		$item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."ovic_custom_banners Where id = ".$id);
        
		$params = array('layout'=>'default', 'width'=>'none-column', 'className'=>'');
        if(!$item) $item = array('id'=>0, 'position'=>0, 'status'=>1, 'ordering'=>1, 'params'=>'');
        else{
            if($item['params']) $params = get_object_vars(json_decode($item['params']));            
        }
		
		$langs = $this->getArrLangs();
		$inputTitle = '';
		$inputLink = '';
		$inputImage = '';
		$langActive = '<input type="hidden" id="langActive" value="0" />';
		if($langs){
			foreach ($langs as $key => $lang) {
				$itemLang = $this->getBannerByLang($id, $lang->id, $shopId);
				if($lang->active == '1'){
					$langActive = '<input type="hidden" id="langActive" value="'.$lang->id.'" />';
					$inputTitle .= '<input type="text" value="'.$itemLang['banner_title'].'" name="titles[]" id="title-'.$lang->id.'" class="form-control lang-'.$lang->id.'" />';
					$inputLink .= '<input type="text" id="link-'.$lang->id.'"  name="links[]" value="'.$itemLang['banner_link'].'" class="form-control lang-'.$lang->id.'" />';
					$inputImage .= '<input type="text" name="images[]" id="image-'.$lang->id.'" value="'.$itemLang['banner_image'].'" class="form-control lang-'.$lang->id.'" />';	
				}else{
					$inputTitle .= '<input type="text" value="'.$itemLang['banner_title'].'" name="titles[]" id="title-'.$lang->id.'" class="form-control lang-'.$lang->id.'" style="display:none" />';
					$inputLink .= '<input type="text" id="link-'.$lang->id.'"  name="links[]" value="'.$itemLang['banner_link'].'" class="form-control lang-'.$lang->id.'" style="display:none" />';
					$inputImage .= '<input type="text" name="images[]" id="image-'.$lang->id.'" value="'.$itemLang['banner_image'].'" class="form-control lang-'.$lang->id.'" style="display:none" />';
				}				
			}
		}
		$langOptions = $this->getLangOptions();
		$html = '<input type="hidden" name="bannerId" value="'.$item['id'].'" />';
		$html .= '<input type="hidden" name="action" value="saveBanner" />';
		$html .= '<input type="hidden" name="secure_key" value="'.$this->secure_key.'" />';
		$html .= $langActive;
		$html .= '<div class="form-group">                    
                        <label class="control-label col-lg-3">'.$this->l('Position').'</label>
                        <div class="col-lg-9 ">
                            <div class="col-lg-12">
                                <select name="position" id="position" class="form-control">'.$this->getPositionOptions($item['position']).'</select>
                            </div>
                        </div>
                    </div>';
        $html .= '<div class="form-group">                    
                        <label class="control-label col-lg-3">'.$this->l('Layout').'</label>
                        <div class="col-lg-9 ">
                            <div class="col-lg-12">
                                <select name="layout" class="form-control">'.$this->getLayoutOptions($params['layout']).'</select>
                            </div>
                        </div>
                    </div>';
        $html .= '<div class="form-group">                    
                        <label class="control-label col-lg-3">'.$this->l('Width').'</label>
                        <div class="col-lg-9 ">
                            <div class="col-lg-12">
                                <select name="width" class="form-control">'.$this->getColumnsOptions($params['width']).'</select>
                            </div>
                        </div>
                    </div>';
        $html .= '<div class="form-group">                    
                        <label class="control-label col-lg-3">'.$this->l('Custom class name').'</label>
                        <div class="col-lg-9 ">
                            <div class="col-lg-12">
                                <input type="text" name="className" value="'.$params['className'].'" class="form-control" />
                            </div>
                        </div>
                    </div>';
		$html .= '<div class="form-group">
                        <label class="control-label col-lg-3">'.$this->l('Title').'</label>
                        <div class="col-lg-9 ">
                            <div class="col-sm-10">
                                '.$inputTitle.'
                            </div>
                            <div class="col-sm-2">
                                <select class="lang form-control" onchange="changeLanguage(this.value)">'.$langOptions.'</select>
                            </div>
                        </div>
                    </div>';
		$html .= '<div class="form-group clearfix">
                        <label class="control-label col-sm-3">'.$this->l('Banner').'</label>
                        <div class="col-sm-9">
                            <div class="col-sm-10">                        
                                <div class="input-group">
                                    '.$inputImage.'                                
                                    <span class="input-group-btn">
                                        <button id="banner" type="button" class="btn btn-default"><i class="icon-folder-open"></i></button>
                                    </span>
                                </div>                        
                            </div>
                            <div class="col-sm-2">
                                <select class="lang form-control" onchange="changeLanguage(this.value)">'.$langOptions.'</select>
                            </div>
                        </div>  
                    </div>';
		$html .= '<div class="form-group">
                        <label class="control-label col-lg-3">'.$this->l('Link').'</label>
                        <div class="col-lg-9 ">
                            <div class="col-sm-10">
                                '.$inputLink.'
                            </div>
                            <div class="col-sm-2">
                                <select class="lang form-control" onchange="changeLanguage(this.value)">'.$langOptions.'</select>
                            </div>
                        </div>
                    </div>';
		return $html;
	}
	public function getContent()
	{
	   $langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;
       $checkUpdate = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."ovic_custom_banners");
        if($checkUpdate){
            if(!isset($checkUpdate['id_shop'])){
                DB::getInstance()->execute("ALTER TABLE "._DB_PREFIX_."ovic_custom_banners ADD `id_shop` INT(6) unsigned NOT NULL AFTER `id`");
                DB::getInstance()->execute("Update "._DB_PREFIX_."ovic_custom_banners Set `id_shop` = ".$shopId);
            }
        }         
		$this->context->controller->addJS(($this->_path).'js/back-end/common.js');                
		$this->context->controller->addJS(($this->_path).'js/back-end/jquery.serialize-object.min.js');
		$this->context->controller->addJS(__PS_BASE_URI__.'js/jquery/plugins/jquery.tablednd.js');
		$this->context->controller->addJS(($this->_path).'js/back-end/ajaxupload.3.5.js');
        $this->context->controller->addCSS(($this->_path).'css/back-end/style.css');
		$this->context->controller->addCSS(($this->_path).'css/back-end/style-upload.css');
        
        
        $this->context->smarty->assign(array(
            'positionOptions' => $this->getPositionOptions(),
            'baseModuleUrl'=> __PS_BASE_URI__.'modules/'.$this->name,
            'moduleId'=>$this->id,
            'langId'=>$langId,
            'content'=>$this->getAllBanner(),
            'langOptions'=>$this->getLangOptions($langId),
            'secure_key'=> $this->secure_key,
            'form'=>$this->ovicRenderForm()
        ));
		return $this->display(__FILE__, 'views/templates/admin/modules.tpl');
	}
    public function hookdisplayHeader()
	{	
	    // Call in global.css
		//$this->context->controller->addCSS(($this->_path).'css/front-end/style.css');
        //$this->context->controller->addJS(($this->_path).'js/front-end/common.js');
	}
	public function hookDisplayCustomBanner1($params)
	{		
		return $this->hooks('hookdisplayCustomBanner1', $params);
	}
	public function hookDisplayCustomBanner2($params)
	{		
		return $this->hooks('hookdisplayCustomBanner2', $params);
	}
	public function hookDisplayCustomBanner3($params)
	{		
		return $this->hooks('hookdisplayCustomBanner3', $params);
	}
	public function hookDisplayGroupFashions($params)
	{		
		return $this->hooks('hookDisplayGroupFashions', $params);
	}
	public function hookdisplayGroupFoods($params)
	{		
		return $this->hooks('hookdisplayGroupFoods', $params);
	}
	public function hookDisplayGroupSports($params)
	{		
		return $this->hooks('hookDisplayGroupSports', $params);
	}
    public function hooks($hookName, $param){   
		
        $moduleLayout = 'oviccustombanner.tpl';
        $langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;        
        $hookName = str_replace('hook','', $hookName);        
        $hookId = (int)Hook::getIdByName($hookName);
		if($hookId <=0 ) return '';
		$page_name = Dispatcher::getInstance()->getController();
		$page_name = (preg_match('/^[0-9]/', $page_name) ? 'page_'.$page_name : $page_name);
		$cacheKey = 'oviccustombanner|'.$langId.'|'.$hookId.'|'.$page_name;        
        if (!$this->isCached('oviccustombanner.tpl', $cacheKey)){
			$this->context->smarty->assign('hookname', $hookName);
			$items = DB::getInstance()->executeS("Select DISTINCT b.*, bl.`banner_image`, bl.`banner_link`, bl.`banner_title` From "._DB_PREFIX_."ovic_custom_banners as b INNER JOIN "._DB_PREFIX_."ovic_custom_banner_lang AS bl On b.id = bl.bannerId Where b.status = 1 AND b.position = ".$hookId." AND b.id_shop='".$shopId."' AND bl.id_lang = ".$langId." AND bl.id_shop = ".$shopId." Order By b.ordering");						
			if($items){
				foreach($items as &$item){
				    if($item['params']){
				        $params = get_object_vars(json_decode($item['params']));
				    }else{
				        $params = array('layout'=>'default','width'=>'none-column', 'className'=>'');
				    }
                    $item['layout'] = $params['layout'];
                    if($params['width'] == 'none-column'){
                        $item['width'] = '';    
                    }else{
                        $item['width'] = $params['width'];
                    }                    
                    $item['className'] = $params['className'];
					$imgSrc = $this->getBannerSrc($item['banner_image'], true);
					if($imgSrc) $item['banner_image_src'] = $imgSrc;
					else unset($item);					                                                     					
				}
			}else return '';
			$this->context->smarty->assign('customBanners', $items);
		}
		return $this->display(__FILE__, 'oviccustombanner.tpl', $cacheKey);		
    }    
    function getCacheId($name=null)
	{
		return parent::getCacheId('oviccustombanner|'.$name);
	}
    function clearCache($name=null)    
	{		
		Tools::clearCache();		
	}
   	function ajaxTranslate($text=''){
        return $this->l($text);
    }
}
