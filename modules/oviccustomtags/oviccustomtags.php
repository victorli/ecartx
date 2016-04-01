<?php
/*
*  @author SonNC Ovic <nguyencaoson.zpt@gmail.com>
*/
class OvicCustomTags extends Module
{
    const INSTALL_SQL_FILE = 'install.sql';	
    public $arrLayout = array();
	public $imageHomeSize = array();
	protected static $arrPosition = array('displayHeader', 'displayFooter');
	public function __construct()
	{
				
		$this->name = 'oviccustomtags';
		$this->tab = 'front_office_features';
		$this->version = '1.0';
		$this->author = 'OvicSoft';		
		$this->secure_key = Tools::encrypt('ovic-soft'.$this->name);
		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->l('Supershop - Custom Tags Module');
		$this->description = $this->l('Custom Tags Module');
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
		|| !$this->registerHook('displayFooter')) return false;
		if (!Configuration::updateGlobalValue('MOD_CUSTOM_TAGS', '1')) return false;
        $this->clearCache();			
		$this->moduleUpdatePosition();	
		return true;
	}
	public function moduleUpdatePosition(){
		$items = DB::getInstance()->executeS("Select DISTINCT position_name From "._DB_PREFIX_."ovic_custom_tags_groups  Where `position_name` <> ''");
		if($items){
			foreach ($items as $key => $item) {
				$position = Hook::getIdByName($item['position_name']);
				DB::getInstance()->execute("Update "._DB_PREFIX_."ovic_custom_tags_groups Set position = '".$position."' Where `position_name` = '".$item['position_name']."'");
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
			`'._DB_PREFIX_.'ovic_custom_tags_group_lang`,
            `'._DB_PREFIX_.'ovic_custom_tags_groups`,
            `'._DB_PREFIX_.'ovic_custom_tags_tag_lang`,
			`'._DB_PREFIX_.'ovic_custom_tags_tags`')) return false;
			
        }		
        if (!Configuration::deleteByName('MOD_CUSTOM_TAGS')) return false;
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
	public function getGroupByLang($itemId, $langId, $shopId){
		$item = DB::getInstance()->getRow("Select name From "._DB_PREFIX_."ovic_custom_tags_group_lang Where `groupId` = ".$itemId." AND `id_lang` = ".$langId." AND `id_shop` = ".$shopId);
		if(!$item) $item = array('name'=>'');
		return $item;		
	}
	public function getAllGroups(){
		$langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;
		$items = DB::getInstance()->executeS("Select * From "._DB_PREFIX_."ovic_custom_tags_groups Where `id_shop`='".$shopId."' Order By position, ordering");
		$content = '';
		if($items){
            foreach($items as $item){
                if($item['status'] == "1"){
                    $status = '<a title="Enabled" class="list-action-enable action-enabled lik-group-status" item-id="'.$item['id'].'" value="'.$item['status'].'"><i class="icon-check"></i></a>';
                }else{
                    $status = '<a title="Disabled" class="list-action-enable action-disabled lik-group-status" item-id="'.$item['id'].'" value="'.$item['status'].'"><i class="icon-check"></i></a>';
                }                
                $params = json_decode($item['params']);
                $value = '<div class="style-values"><span class="style-value" style="background: '.$params->background.'">&nbsp;</span><label>Background</label>: <span>'.$params->background.'</span></div>';
				$value .= '<div class="style-values"><span class="style-value" style="background: '.$params->color.'">&nbsp;</span><label>Color</label>: <span>'.$params->color.'</span></div>';				
                $itemLang = $this->getGroupByLang($item['id'], $langId, $shopId);                
                $content .= '<tr id="gr_'.$item['id'].'">
    				    <td><a class="lik-group" href="javascript:void(0)" item-id="'.$item['id'].'">'.$itemLang['name'].'</a></td>    				    
                        <td class="center">'.Hook::getNameById($item['position']).'</td>
                        <td>'.$value.'</td>								
    				    <td class="pointer dragHandle center" ><div class="dragGroup"><div class="positions">'.$item['ordering'].'</div></div></td>		
    				    <td class="center">'.$status.'</td>                        
                        <td class="center">
                            <a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-group-edit"><i class="icon-edit"></i></a>&nbsp;
                            <a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-group-delete"><i class="icon-trash" ></i></a>
                        </td>
                    </tr>';
            }
        }
		return $content;
	}
	public function getTagByLang($itemId, $langId = 0, $shopId = 0){
		if(!$langId) $langId = Context::getContext()->language->id;
		if(!$shopId) $shopId = Context::getContext()->shop->id;
		$item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."ovic_custom_tags_tag_lang Where `tagId` = ".$itemId." AND `id_lang` = ".$langId." AND `id_shop` = ".$shopId);
		if(!$item) $item = array('title'=>'', 'link'=>'');
		
		return $item;
	}
	public function getTagsByGroup($groupId){
		$langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;
		$items = DB::getInstance()->executeS("Select * From "._DB_PREFIX_."ovic_custom_tags_tags Where groupId = ".$groupId." Order By ordering");
		$content = '';
		if($items){
            foreach($items as $item){
                if($item['status'] == "1"){
                    $status = '<a title="Enabled" class="list-action-enable action-enabled lik-tag-status" item-id="'.$item['id'].'" value="'.$item['status'].'"><i class="icon-check"></i></a>';
                }else{
                    $status = '<a title="Disabled" class="list-action-enable action-disabled lik-tag-status" item-id="'.$item['id'].'" value="'.$item['status'].'"><i class="icon-check"></i></a>';
                }
                $itemLang = $this->getTagByLang($item['id'], $langId, $shopId);                
                $content .= '<tr id="tg_'.$item['id'].'">
    				    <td>'.$itemLang['title'].'</td>
    				    <td>'.$itemLang['link'].'</td>
    				    <td class="pointer dragHandle center" ><div class="dragGroup"><div class="positions">'.$item['ordering'].'</div></div></td>		
    				    <td class="center">'.$status.'</td>                        
                        <td class="center">
                            <a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-tag-edit"><i class="icon-edit"></i></a>&nbsp;
                            <a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-tag-delete"><i class="icon-trash" ></i></a>
                        </td>
                    </tr>';
            }
        }
		return $content;
	}
	public function getAllLanguages(){
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
	public function ovicRenderGroupForm($id = 0){		
		$shopId = Context::getContext()->shop->id;
		$item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."ovic_custom_tags_groups Where id = ".$id);
		if(!$item){
			$item = array('id'=>0, 'position'=>0, 'status'=>1, 'ordering'=>1, 'params'=>'');
			$params = array('background'=>'#666666', 'color'=>'#ffffff');
		}else{
			$params = get_object_vars(json_decode($item['params']));
		} 		
		$languages = $this->getAllLanguages();
		$inputName = '';		
		$langActive = '<input type="hidden" id="groupLangActive" value="0" />';
		if($languages){
			foreach ($languages as $key => $language) {				
				$itemLang = $this->getGroupByLang($id, $language->id, $shopId);				
				if($language->active == '1'){
					$langActive = '<input type="hidden" id="groupLangActive" value="'.$language->id.'" />';
					$inputName .= '<input type="text" value="'.$itemLang['name'].'" name="names[]" class="form-control group-lang-'.$language->id.'" />';
				}else{
					$inputName .= '<input type="text" value="'.$itemLang['name'].'" name="names[]" class="form-control group-lang-'.$language->id.'" style="display:none" />';
				}				
			}
		}
		$langOptions = $this->getLangOptions();
		$html = '<input type="hidden" name="groupId" value="'.$item['id'].'" />';
		$html .= '<input type="hidden" name="action" value="saveGroup" />';
		$html .= '<input type="hidden" name="secure_key" value="'.$this->secure_key.'" />';
		$html .= $langActive;
		
		$html .= '<div class="form-group">
                    <label class="control-label col-lg-3">'.$this->l('Name').'</label>
				    <div class="col-lg-9 ">
                        <div class="col-sm-10">'.$inputName.'</div>
                        <div class="col-sm-2">
                            <select class="group-lang form-control" onchange="groupChangeLanguage(this.value)">'.$langOptions.'</select>
                        </div>
                    </div>
                </div>';
		
		
		$html .= '<div class="form-group">                    
                        <label class="control-label col-lg-3">'.$this->l('Position').'</label>
                        <div class="col-lg-9 ">
                            <div class="col-lg-12">
                                <select name="position" id="position" class="form-control">'.$this->getPositionOptions($item['position']).'</select>
                            </div>
                        </div>
                    </div>';
		$html .= '<div class="form-group">
                    <label class="control-label col-lg-3">'.$this->l('Background Color').'</label>
                    <div class="col-lg-9">
                        <div class="col-sm-6">
                            <div class="input-group">
                                <input type="text" class="mColorPicker form-control" id="color_background" name="background" value="'.$params['background'].'" data-hex="true" />
                                <span id="icp_color_background" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true">
                                    <img src="../img/admin/color.png" />
                                </span>                                
                            </div>
                            
                        </div>
                    </div>
                </div>';
		$html .= '<div class="form-group">
                    <label class="control-label col-lg-3">'.$this->l('Title Color').'</label>
                    <div class="col-lg-9">
                        <div class="col-sm-6">
                            <div class="input-group">
                                <input type="text" class="mColorPicker form-control" id="color_title" name="color" value="'.$params['color'].'" data-hex="true" />
                                <span id="icp_color_title" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true">
                                    <img src="../img/admin/color.png" />
                                </span>                                
                            </div>
                            
                        </div>
                    </div>
                </div>';
		return $html;
	}
	public function ovicRenderTagForm($id = 0){		
		$shopId = Context::getContext()->shop->id;
		$item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."ovic_custom_tags_tags Where id = ".$id);
		if(!$item) $item = array('id'=>0, 'groupId'=>0, 'status'=>1, 'ordering'=>1);		
		$languages = $this->getAllLanguages();
		$inputTitle = '';
		$inputLink = '';
		$langActive = '<input type="hidden" id="tagLangActive" value="0" />';
		if($languages){
			foreach ($languages as $key => $language) {				
				$itemLang = $this->getTagByLang($id, $language->id, $shopId);				
				if($language->active == '1'){
					$langActive = '<input type="hidden" id="tagLangActive" value="'.$language->id.'" />';
					$inputTitle .= '<input type="text" value="'.$itemLang['title'].'" name="titles[]" class="form-control tag-lang-'.$language->id.'" />';
					$inputLink .= '<input type="text"  name="links[]" value="'.$itemLang['link'].'" class="form-control tag-lang-'.$language->id.'" />';	
				}else{
					$inputTitle .= '<input type="text" value="'.$itemLang['title'].'" name="titles[]" class="form-control tag-lang-'.$language->id.'" style="display:none" />';
					$inputLink .= '<input type="text" name="links[]" value="'.$itemLang['link'].'" class="form-control tag-lang-'.$language->id.'" style="display:none" />';
				}				
			}
		}
		$langOptions = $this->getLangOptions();
		$html = '<input type="hidden" name="tagId" value="'.$item['id'].'" />';
		$html .= '<input type="hidden" name="action" value="saveTag" />';
		$html .= '<input type="hidden" name="secure_key" value="'.$this->secure_key.'" />';
		$html .= $langActive;		
		$html .= '<div class="form-group">
                        <label class="control-label col-lg-3">'.$this->l('Title').'</label>
                        <div class="col-lg-9 ">
                            <div class="col-sm-10">
                                '.$inputTitle.'
                            </div>
                            <div class="col-sm-2">
                                <select class="tag-lang form-control" onchange="tagChangeLanguage(this.value)">'.$langOptions.'</select>
                            </div>
                        </div>
                    </div>';		
		$html .= '<div class="form-group">
                        <label class="control-label col-lg-3">'.$this->l('Link').'</label>
                        <div class="col-lg-9 ">
                            <div class="col-sm-10">'.$inputLink.'</div>
                            <div class="col-sm-2">
                                <select class="tag-lang form-control" onchange="tagChangeLanguage(this.value)">'.$langOptions.'</select>
                            </div>
                        </div>
                    </div>';
		return $html;
	}
	public function getContent()
	{
	   $langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;
	   $checkUpdate = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."ovic_custom_tags_groups");
        if($checkUpdate){
            if(!isset($checkUpdate['id_shop'])){
                DB::getInstance()->execute("ALTER TABLE "._DB_PREFIX_."ovic_custom_tags_groups ADD `id_shop` INT(6) unsigned NOT NULL AFTER `id`");
                DB::getInstance()->execute("Update "._DB_PREFIX_."ovic_custom_tags_groups Set `id_shop` = ".$shopId);
            }
        }   
		$this->context->controller->addJS(($this->_path).'js/back-end/common.js');       
		$this->context->controller->addJS(($this->_path).'js/back-end/jquery.serialize-object.min.js');         
		$this->context->controller->addJS(__PS_BASE_URI__.'js/jquery/plugins/jquery.tablednd.js');
		$this->context->controller->addJS(__PS_BASE_URI__.'js/jquery/plugins/jquery.colorpicker.js');
        $this->context->controller->addCSS(($this->_path).'css/back-end/style.css');
        
        $this->context->smarty->assign(array(
            'positionOptions' => $this->getPositionOptions(),
            'baseModuleUrl'=> __PS_BASE_URI__.'modules/'.$this->name,
            'moduleId'=>$this->id,
            'langId'=>$langId,
            'content'=>$this->getAllGroups(),
            'langOptions'=>$this->getLangOptions($langId),
            'secure_key'=> $this->secure_key,
            'groupForm'=>$this->ovicRenderGroupForm(),
            'tagForm'=>$this->ovicRenderTagForm()
        ));
		return $this->display(__FILE__, 'views/templates/admin/modules.tpl');
	}
    public function hookdisplayHeader()
	{		
		//$this->context->controller->addCSS(($this->_path).'css/front-end/style.css');
        //$this->context->controller->addJS(($this->_path).'js/front-end/common.js');
	}
	public function hookdisplayFooter($params)
	{		
		return $this->hooks('hookdisplayFooter', $params);
	}
	
    public function hooks($hookName, $param){    	
        $moduleLayout = 'oviccustomtags.tpl';
        $langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;        
        $hookName = str_replace('hook','', $hookName);        
        $hookId = (int)Hook::getIdByName($hookName);
		if($hookId <=0) return '';        
		$page_name = Dispatcher::getInstance()->getController();
		$page_name = (preg_match('/^[0-9]/', $page_name) ? 'page_'.$page_name : $page_name);
		$cacheKey = 'flexiblebrand|'.$langId.'|'.$hookId.'|'.$page_name;
		
		if (!$this->isCached('oviccustomtags.tpl', $cacheKey)){
			$this->context->smarty->assign('hookname', $hookName);
			$items = DB::getInstance(_PS_USE_SQL_SLAVE_)->executeS("Select DISTINCT g.*, gl.`name` From "._DB_PREFIX_."ovic_custom_tags_groups as g INNER JOIN "._DB_PREFIX_."ovic_custom_tags_group_lang AS gl On g.id = gl.groupId Where g.status = 1 AND g.position = ".$hookId." AND g.id_shop = '".$shopId."' AND gl.id_lang = ".$langId." AND gl.id_shop = ".$shopId." Order By g.ordering");    			
			if($items){				            	
				foreach($items as &$item){
					$params = @json_decode($item['params']);
					if(!$params){
						$params = new stdClass();
						$params->background = "#82A3CC";
						$params->color = "#ffffff";
					}else{
						if(!$params->background) $params->background = "#82A3CC";
						if(!$params->color) $params->color = "#ffffff";
					}
					$item['background']=$params->background;
					$item['color']=$params->color;
					$item['tags'] = DB::getInstance()->executeS("Select t.*, tl.`title`, tl.`link` From "._DB_PREFIX_."ovic_custom_tags_tags as t Inner Join "._DB_PREFIX_."ovic_custom_tags_tag_lang as tl On t.id = tl.tagId Where t.`groupId` = ".$item['id']." AND t.status = 1 AND tl.`id_lang` = ".$langId." AND tl.`id_shop` = ".$shopId." Order By t.ordering");                                                      
				}
				$this->context->smarty->assign('customTags', $items);            
			}else return ''; 
		}	
        return $this->display(__FILE__, 'oviccustomtags.tpl', $cacheKey);       
    }    
    function getCacheId($name=null)
	{
		return parent::getCacheId('oviccustomtags|'.$name);
	}
    function clearCache($name=null)    
	{		
		Tools::clearCache();
	}
   	function ajaxTranslate($text=''){
        return $this->l($text);
    }
}
