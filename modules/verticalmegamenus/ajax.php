<?php
/*
mutiple insert
$abd = Db::getInstance()->autoExecute(_DB_PREFIX_.'mobiles', array(

'id' => '',
'email' => pSQL($email),
'number'=> pSQL($mobile),
'ip_registration_newsletter'=> pSQL($ip)

), 'INSERT'); 
*/
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
require_once(dirname(__FILE__).'/verticalmegamenus.php');
require_once(dirname(__FILE__).'/VerticalMegaMenusThumb.php');
require_once(dirname(__FILE__).'../../../classes/Cookie.php');
$module = new VerticalMegaMenus();
if (!Tools::isSubmit('secure_key') || Tools::getValue('secure_key') != $module->secure_key){
	$response = new stdClass();
	$response->status = 0;
	$response->msg = "you need to login with the admin account.";
	die(Tools::jsonEncode($response));
}
$action = Tools::getValue('action');
VerticalMegaMenusAjax::$action();
class VerticalMegaMenusAjax{
    
    function __construct(){        
        
    }
    public static function saveModule(){
    	$module = new VerticalMegaMenus();		
        $shopId = Context::getContext()->shop->id;
		$languages = $module->getAllLanguage();        
        $response = new stdClass();		
        $itemId = intval($_POST['moduleId']);
		$db = DB::getInstance();
		$names = $_POST['module_titles'];
        $position = intval($_POST['position']);
		$position_name = Hook::getNameById($position);		
        $layout = Tools::getValue('moduleLayout', 'default');
		$showCount = intval($_POST['showCount']);
        
        if($itemId == 0){
			$maxOrdering = $db->getValue("Select MAX(ordering) From "._DB_PREFIX_."verticalmegamenus_modules Where `position` = ".$position);
		   	if($maxOrdering >0) $maxOrdering++;
		   	else $maxOrdering = 1;
            if($db->execute("Insert Into "._DB_PREFIX_."verticalmegamenus_modules (`id_shop`, `position`, `position_name`, `layout`, `ordering`, `status`, `show_count_item`) Values ('".$shopId."', '".$position."', '$position_name', '".$layout."', '".$maxOrdering."', '1', '".$showCount."')")){
                $insertId = $db->Insert_ID();  
				if($languages){
                	$insertDatas = array();
                	foreach($languages as $index=>$language){                		
						$insertDatas[] = array('module_id'=>$insertId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'name'=>$db->escape($names[$index]));                   		                
                	}
					if($insertDatas) $db->insert('verticalmegamenus_module_lang', $insertDatas);
                }                
                $response->status = '1';
                $response->msg = $module->ajaxTranslate('Add new Module Success!');                
            }else{
                $response->status = '0';
                $response->msg = $module->ajaxTranslate('Add new Module not Success!');
            }
        }else{
            $item = $db->getRow("Select * From "._DB_PREFIX_."verticalmegamenus_modules Where id = ".$itemId);
            $db->execute("Update "._DB_PREFIX_."verticalmegamenus_modules Set `id_shop`='".$shopId."', `position` = '".$position."', `position_name`='$position_name', `layout`='".$layout."', `show_count_item` = '".$showCount."' Where id = ".$itemId);
            
			if($languages){
				$insertDatas = array();            	
            	foreach($languages as $index=>$language){
            		$check = DB::getInstance()->getValue("Select module_id From "._DB_PREFIX_."verticalmegamenus_module_lang Where module_id = $itemId AND id_lang = ".$language->id." AND id_shop = ".$shopId);
            		if($check){
            			$db->execute("Update "._DB_PREFIX_."verticalmegamenus_module_lang Set `name` = '".$db->escape($names[$index])."' Where `module_id` = ".$itemId." AND `id_lang` = ".$language->id." AND `id_shop` = ".$shopId);	
            		}else{
            			$insertDatas[] = array('module_id'=>$itemId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'name'=>$db->escape($names[$index]));
            		}
					
            	}
            	if($insertDatas) $db->insert('verticalmegamenus_module_lang', $insertDatas);
            }            
            $response->status = '1';
            $response->msg = $module->ajaxTranslate('Update Module Success!');
        }
		$module->clearCache();
        die(Tools::jsonEncode($response));
    }
    public static function getModuleItem(){
        $module = new VerticalMegaMenus();
        $response = new stdClass();
        $itemId = intval($_POST['itemId']);
        if($itemId){
        	$response->form = $module->ovicRenderModuleForm($itemId);			       
            $response->status = '1';            
            $response->msg = '';
        }else{
            $response->status = '0';
            $response->msg = $module->ajaxTranslate('Item not found!');
        }		
        die(Tools::jsonEncode($response));
    }
    public static function updateModuleOrdering(){
        $module = new VerticalMegaMenus();
        $response = new stdClass();
        $ids = $_POST['ids'];        
        if($ids){
            $strIds = implode(', ', $ids);            
            $minOrder = DB::getInstance()->getValue("Select Min(ordering) From "._DB_PREFIX_."verticalmegamenus_modules Where id IN ($strIds)");            
            foreach($ids as $i=>$id){
                DB::getInstance()->query("Update "._DB_PREFIX_."verticalmegamenus_modules Set ordering=".($minOrder + $i)." Where id = ".$id);                
            }
            $response->status = '1';
            $response->msg = $module->ajaxTranslate('Update Module Ordering Success!');
        }else{
            $response->status = '0';
            $response->msg = $module->ajaxTranslate('Update Module Ordering not Success!');
        }
		$module->clearCache();
        die(Tools::jsonEncode($response));
    }
    public static function loadModuleByLang(){
        $shopId = Context::getContext()->shop->id;
        $langId = intval($_POST['langId']);
        $itemId = intval($_POST['itemId']);
        $item = VerticalMegaMenusLibraries::getModuleLangById($itemId, $langId, $shopId);// DB::getInstance()->getRow("Select * From "._DB_PREFIX_."flexiblecustom_modules_lang Where module_id = ".$itemId." AND id_lang = ".$langId." AND id_shop = ".$shopId);
        $response = new stdClass();
        if($item){
            $response->name = $item['name'];                        
        }else{
            $response->name = '';            
        }
        die(Tools::jsonEncode($response));
    }
    
    public static function deleteModule(){
        $itemId = intval($_POST['itemId']);
        $module = new  VerticalMegaMenus();
        $response = new stdClass();
        $check = DB::getInstance()->getValue("Select id From "._DB_PREFIX_."verticalmegamenus_menus Where moduleId = ".$itemId);
        if(!$check){
            if(DB::getInstance()->execute("Delete From "._DB_PREFIX_."verticalmegamenus_modules Where id = ".$itemId)){
                DB::getInstance()->execute("Delete From "._DB_PREFIX_."verticalmegamenus_module_lang Where module_id = ".$itemId);
                $response->status = '1';
                $response->msg = $module->ajaxTranslate('Delete Module Success!');
            }else{
                $response->status = '0';
                $response->msg = $module->ajaxTranslate('Delete Module not Success!');
            }
        }else{
            $response->status = '0';
            $response->msg = $module->ajaxTranslate('You need to delete the previous Menu.');
        }
        $module->clearCache();
        die(Tools::jsonEncode($response));
    }
    
    public static function generationUrl(){
        $value = Tools::getValue('value');
        $response = '';
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
                    $response = '#';    
                    break;
            }  
   
        }else $response = '#';
        die(Tools::jsonEncode($response)); 
    }
    public static function saveMenu(){
        $shopId = Context::getContext()->shop->id;
		$module = new VerticalMegaMenus();
		$languages = $module->getAllLanguage();
		$img = new VerticalMegaMenusThumb();
		$moduleId = intval($_POST['moduleId']);
		$db = DB::getInstance();						        
		$response = new stdClass();		
        if($moduleId >0){
        	$itemId = intval($_POST['menuId']);
			$titles = $_POST['titles'];
			$images = $_POST['images'];			
			$alts = $_POST['alts'];
			$htmls = $_POST['htmls'];
			$width = $_POST['width'];
			$icon = Tools::getValue('icon', '');
			$link = $db->escape($_POST['link']);        
            $linkType = Tools::getValue('linkType');
            $menuType = Tools::getValue('menuType', 'link');			           
            if($itemId <=0){
				$maxOrdering = $db->getValue("Select MAX(ordering) From "._DB_PREFIX_."verticalmegamenus_menus Where `moduleId` = ".$moduleId);
		   		if($maxOrdering >0) $maxOrdering++;
		   		else $maxOrdering = 1;				
                if($db->execute("Insert Into "._DB_PREFIX_."verticalmegamenus_menus (`moduleId`, `menuType`, `linkType`, `link`, `width`, `status`, `ordering`) Values ('".$moduleId."', '".$menuType."', '".$linkType."', '".$link."', '".$width."', '1', '".$maxOrdering."')")){
                    $insertId = $db->Insert_ID();					
					if($icon && file_exists($module->pathTemp.$icon)){                    
                        $path_info = pathinfo($icon);
                        $ext = $path_info['extension'];
                        $iconName = Tools::encrypt($icon).'.'.$ext;  
						if(copy($module->pathTemp.$icon, $module->pathIcon.$iconName)){
							unlink($module->pathTemp.$icon);
							$db->execute("Update "._DB_PREFIX_."verticalmegamenus_menus Set `icon` = '".$iconName."' Where id = ".$insertId);	
						}
                    }
					
					if($languages){
	                	$insertDatas = array();
	                	foreach($languages as $index=>$language){	                		
	                		if($images[$index] && file_exists($module->pathTemp.$images[$index])){	                			                 
			                    $path_info = pathinfo($images[$index]);								
			                    $ext = $path_info['extension'];
			                    $bannerName = Tools::encrypt($images[$index]).'.'.$ext;
			                    if(copy($module->pathTemp.$images[$index], $module->pathBanner.$bannerName)){
			                    	unlink($module->pathTemp.$images[$index]);
			                    	$insertDatas[] = array('menu_id'=>$insertId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'title'=>$db->escape($titles[$index]), 'image'=>$bannerName, 'image_alt'=>$db->escape($alts[$index]), 'html'=>$db->escape($htmls[$index])) ;	
			                    }else{
			                    	$insertDatas[] = array('menu_id'=>$insertId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'title'=>$db->escape($titles[$index]), 'image_alt'=>$db->escape($alts[$index]), 'html'=>$db->escape($htmls[$index])) ;
			                    }                    
			                                       
			                }else{
			                	$insertDatas[] = array('menu_id'=>$insertId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'title'=>$db->escape($titles[$index]), 'image_alt'=>$db->escape($alts[$index]), 'html'=>$db->escape($htmls[$index])) ;
			                }
	                	}
						if($insertDatas) $db->insert('verticalmegamenus_menu_lang', $insertDatas);
	                }                    
                    $response->status = '1';
                    $response->msg = $module->ajaxTranslate("Add new Menu Success!");
                }else{
                    $response->status = '0';
                    $response->msg = $module->ajaxTranslate("Add new Menu not Success!");
                }
            }else{
                $item = $db->getRow("Select * From "._DB_PREFIX_."verticalmegamenus_menus Where id = ".$itemId);                
                
                if($icon && file_exists($module->pathTemp.$icon)){                    
                    $path_info = pathinfo($icon);
                    $ext = $path_info['extension'];
                    $iconName = Tools::encrypt($icon).'.'.$ext;  //$itemId.'-icon.'.$ext;  
					if(copy($module->pathTemp.$icon, $module->pathIcon.$iconName)){
						unlink($module->pathTemp.$icon);	
					}                  
                    $db->execute("Update "._DB_PREFIX_."verticalmegamenus_menus Set `menuType` = '".$menuType."', `linkType` = '".$linkType."', `link` = '".$link."', `width` = '".$width."', `icon`='$iconName' Where id = ".$itemId);                     
                }else{
                    if($icon == ""){
                        if($item['icon'] && file_exists($module->pathIcon.$item['icon'])) unlink($module->pathIcon.$item['icon']); 
                        $db->execute("Update "._DB_PREFIX_."verticalmegamenus_menus Set `menuType` = '".$menuType."', `linkType` = '".$linkType."', `link` = '".$link."', `width` = '".$width."', `icon`='' Where id = ".$itemId);                        
                    }else{
                    	$db->execute("Update "._DB_PREFIX_."verticalmegamenus_menus Set `menuType` = '".$menuType."', `linkType` = '".$linkType."', `link` = '".$link."', `width` = '".$width."' Where id = ".$itemId);
                    }
                }				
                if($languages){
                	$insertDatas = array();
                	foreach($languages as $index=>$language){
                		$check = DB::getInstance()->getValue("Select menu_id From "._DB_PREFIX_."verticalmegamenus_menu_lang Where menu_id = ".$itemId." AND `id_lang` = ".$language->id." AND id_shop = ".$shopId);						
                		if($images[$index] && file_exists($module->pathTemp.$images[$index])){                    
		                    $path_info = pathinfo($images[$index]);
		                    $ext = $path_info['extension'];
							$bannerName = Tools::encrypt($images[$index]).'.'.$ext;
		                    //$bannerName = $itemId.'-'.$language->id.'-'.$shopId.'-banner.'.$ext;
		                    copy($module->pathTemp.$images[$index], $module->pathBanner.$bannerName);                    
		                    unlink($module->pathTemp.$images[$index]);
		                    if($check){
		                    	$db->execute("Update "._DB_PREFIX_."verticalmegamenus_menu_lang Set `title` = '".$db->escape($titles[$index])."', `image` = '$bannerName', `image_alt` = '".$db->escape($alts[$index])."', `html` = '".$db->escape($htmls[$index])."' Where `menu_id` = $itemId AND `id_lang` = ".$language->id." AND `id_shop` = ".$shopId);	
		                    }else{
		                    	$insertDatas[] = array('menu_id'=>$itemId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'title'=>$db->escape($titles[$index]), 'image'=>$bannerName, 'image_alt'=>$db->escape($alts[$index]), 'html'=>$db->escape($htmls[$index])) ;
		                    }		                    
		                }else{
		                	if($check){
		                		$db->execute("Update "._DB_PREFIX_."verticalmegamenus_menu_lang Set `title` = '".$db->escape($titles[$index])."', `image_alt` = '".$db->escape($alts[$index])."', `html` = '".$db->escape($htmls[$index])."'  Where `menu_id` = $itemId AND `id_lang` = ".$language->id." AND `id_shop` = ".$shopId);
							}else{
								$insertDatas[] = array('menu_id'=>$itemId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'title'=>$db->escape($titles[$index]), 'image_alt'=>$db->escape($alts[$index]), 'html'=>$db->escape($htmls[$index])) ;
							}	
		                }
                	}
					if($insertDatas) $db->insert('verticalmegamenus_menu_lang', $insertDatas);
                }
                $response->status = '1';
                $response->msg = $module->ajaxTranslate("Update Menu Success!"); 
            }  
        }else{
            $response->status = '0';
            $response->msg = $module->ajaxTranslate("Menu not found");
        }
		$module->clearCache();
        die(Tools::jsonEncode($response));
    }
    public static function loadAllMenu(){
        $moduleId = intval($_POST['moduleId']);
        $module = new  VerticalMegaMenus();
        $response = new stdClass();
        if($moduleId >0){
        	$response->content = $module->getAllMenu($moduleId);
			if($response->content){
				$response->status = 1;
				$response->msg = $module->ajaxTranslate('Load menu success!');	
			}else {
				$response->status = 0;
				$response->msg = $module->ajaxTranslate('Is not items');
			}			
        }else{
        	$response->status = 0;
			$response->msg = $module->ajaxTranslate('Module not found!');
        }
        die(Tools::jsonEncode($response));
    }
    public static function getMenu(){
        $itemId = intval($_POST['itemId']);
        $module = new VerticalMegaMenus();
        $response = new stdClass();
        $langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;
        if($itemId){
        	$response->form = $module->ovicRenderMenuForm($itemId);
			$response->status = 1;
			$response->msg = '';			
        }else{
            $response->status = '0';
			$response->form = $module->ovicRenderMenuForm();
            $response->msg = $module->ajaxTranslate('Item not found.');
        }
        die(Tools::jsonEncode($response));
    }
    public static function updateMenuOrdering(){
        $module = new VerticalMegaMenus();
        $response = new stdClass();
        $ids = $_POST['ids'];        
        if($ids){
            $strIds = implode(', ', $ids);            
            $minOrder = DB::getInstance()->getValue("Select Min(ordering) From "._DB_PREFIX_."verticalmegamenus_menus Where id IN ($strIds)");            
            foreach($ids as $i=>$id){
                DB::getInstance()->query("Update "._DB_PREFIX_."verticalmegamenus_menus Set ordering=".($minOrder + $i)." Where id = ".$id);                
            }
            $response->status = '1';
            $response->msg = $module->ajaxTranslate('Update Module Ordering Success!');
        }else{
            $response->status = '0';
            $response->msg = $module->ajaxTranslate('Update Module Ordering not Success!');
        }
		$module->clearCache();
        die(Tools::jsonEncode($response));
    }
    public static function deleteMenu(){
        $itemId = intval($_POST['itemId']);
        $response = new stdClass();
        $module = new VerticalMegaMenus();
        if($itemId >0){
            $check = DB::getInstance()->getValue("Select id From "._DB_PREFIX_."verticalmegamenus_groups Where menuId = ".$itemId);
            if(!$check){
                $item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."verticalmegamenus_menus Where id = ".$itemId);
                if($item){
                    if(DB::getInstance()->execute("Delete From "._DB_PREFIX_."verticalmegamenus_menus Where id = ".$itemId)){
                        DB::getInstance()->execute("Delete From "._DB_PREFIX_."verticalmegamenus_menu_lang Where menu_id = ".$itemId);
                        if($item['icon'] && file_exists($module->pathIcon.$item['icon'])) unlink($module->pathIcon.$item['icon']);
                        if($item['image'] && file_exists($module->pathBanner.$item['image'])) unlink($module->pathBanner.$item['image']);
                        $response->status = '1';
                        $response->msg = $module->ajaxTranslate('Delete menu success!');
                    }else{
                       $response->status = '0';
                        $response->msg = $module->ajaxTranslate('Delete menu not success.'); 
                    }
                }else{
                    $response->status = '0';
                    $response->msg = $module->ajaxTranslate('Item menu not found.');
                }     
            }else{
                $response->status = '0';
                $response->msg = $module->ajaxTranslate('You need to delete the previous Group.');
            }
            
        }else{
            $response->status = '0';
            $response->msg = $module->ajaxTranslate('Item menu not found.');
        }
		$module->clearCache();
        die(Tools::jsonEncode($response));
    }
    public static function loadMenuByLang(){
        $shopId = Context::getContext()->shop->id;
        $langId = intval($_POST['langId']);
        $itemId = intval($_POST['itemId']);
        $item = VerticalMegaMenusLibraries::getMenuLangById($itemId, $langId, $shopId);// DB::getInstance()->getRow("Select * From "._DB_PREFIX_."flexiblecustom_modules_lang Where module_id = ".$itemId." AND id_lang = ".$langId." AND id_shop = ".$shopId);
        $response = new stdClass();
        if($item){
            $response->title = $item['title'];    
            $response->image_alt = $item['image_alt'];
            $response->html = $item['html'];
        }else{
            $response->title = '';    
            $response->image_alt = '';
            $response->html = '';            
        }
        die(Tools::jsonEncode($response));
    }
    public static function saveGroup(){        
		
		$shopId = Context::getContext()->shop->id;
		$module = new VerticalMegaMenus();
		$languages = $module->getAllLanguage();
		$db = DB::getInstance();
        $menuId = intval($_POST['menuId']);		        
        if($menuId >0){			
        	$itemId = intval($_POST['menuGroupId']);    
			$titles = $_POST['titles'];
			$displayTitle = intval($_POST['group_display_title']);
			$width = $_POST['width'];
			$groupType = $_POST['groupType'];
			
            $params = new stdClass();
            $params->productCategory = Tools::getValue('groupProductCategory');
            $params->productType = Tools::getValue('groupProductType');
            $params->productCount = Tools::getValue('groupCountProduct');
            $params->productWidth = Tools::getValue('groupProductWidth');
            $params->customWidth = Tools::getValue('customItemWidth');
            $productIds = Tools::getValue('groupProductIds');                        
            $params->productIds = explode(',', $productIds);			
            $response = new stdClass();
            if($itemId <=0){
            	$maxOrdering = $db->getValue("Select MAX(ordering) From "._DB_PREFIX_."verticalmegamenus_groups Where `menuId` = ".$menuId);
		   		if($maxOrdering >0) $maxOrdering++;
		   		else $maxOrdering = 1;
				
                if(DB::getInstance()->execute("Insert Into "._DB_PREFIX_."verticalmegamenus_groups (`menuId`, `type`, `display_title`, `params`, `width`, `status`, `ordering`) Values ('".$menuId."', '".$groupType."', '".$displayTitle."', '".(json_encode($params))."', '".$width."', '1', '".$maxOrdering."')")){
                    $insertId = DB::getInstance()->Insert_ID();
					if($languages){
	                	$insertDatas = array();
	                	foreach($languages as $index=>$language){	                			                			                		
			                $insertDatas[] = array('group_id'=>$insertId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'title'=>$db->escape($titles[$index])) ;			                
	                	}
						if($insertDatas) $db->insert('verticalmegamenus_group_lang', $insertDatas);
	                }
                    $response->status ='1';
                    $response->msg = 'Add new Group Success.';
                }else{
                    $response->status ='0';
                    $response->msg = 'Add new Group not success.';
                }
            } else{
                $item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."verticalmegamenus_groups Where id = ".$itemId);
                DB::getInstance()->execute("Update "._DB_PREFIX_."verticalmegamenus_groups Set `type` = '".$groupType."', `display_title`='".$displayTitle."', `params` = '".(json_encode($params))."', `width`='".$width."' Where id = ".$itemId);
                if($languages){
                	$insertDatas = array();          	
                	foreach($languages as $index=>$language){
                		$check = DB::getInstance()->getValue("Select group_id From "._DB_PREFIX_."verticalmegamenus_group_lang Where group_id = '".$itemId."' AND `id_lang` = '".$language->id."' AND `id_shop` = ".$shopId);
						if($check)
                			DB::getInstance()->execute("Update "._DB_PREFIX_."verticalmegamenus_group_lang Set title = '".$db->escape($titles[$index])."' Where `group_id` = ".$itemId." AND `id_lang` = '".$language->id."' AND id_shop = ".$shopId);
						else {
							$insertDatas[] = array('group_id'=>$itemId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'title'=>$db->escape($titles[$index])) ;
						}	                			                			                					                
                	}
					if($insertDatas) $db->insert('verticalmegamenus_group_lang', $insertDatas);
                }                
                $response->status ='1';
                $response->msg = 'Update Group success.';
            }
        }else{
            $response->status ='0';
            $response->msg = 'Menu not found.';
        }
		$module->clearCache();
        die(Tools::jsonEncode($response));
    }
    public static function loadGroupByLang(){
        $shopId = Context::getContext()->shop->id;
        $langId = intval($_POST['langId']);
        $itemId = intval($_POST['itemId']);
        $item = VerticalMegaMenusLibraries::getGroupLangById($itemId, $langId, $shopId);
        $response = new stdClass();
        if($item){
            $response->title = $item['title'];                
        }else{
            $response->title = '';
        }
        die(Tools::jsonEncode($response));
    }
	
	public static function loadMenuItemByLang(){
        $shopId = Context::getContext()->shop->id;
        $langId = intval($_POST['langId']);
        $itemId = intval($_POST['itemId']);
        $item = VerticalMegaMenusLibraries::getMenuItemLangById($itemId, $langId, $shopId);
        $response = new stdClass();
        if($item){
            $response->title = $item['title'];                
			$response->imageAlt = $item['imageAlt'];                
			$response->html = $item['html'];                
        }else{
            $response->title = '';
			$response->imageAlt = '';
			$response->html = '';
        }
        die(Tools::jsonEncode($response));
    }
    public static function updateGroupOrdering(){
        $module = new VerticalMegaMenus();
        $response = new stdClass();
        $ids = $_POST['ids'];        
        if($ids){
            $strIds = implode(', ', $ids);            
            $minOrder = DB::getInstance()->getValue("Select Min(ordering) From "._DB_PREFIX_."verticalmegamenus_groups Where id IN ($strIds)");            
            foreach($ids as $i=>$id){
                DB::getInstance()->query("Update "._DB_PREFIX_."verticalmegamenus_groups Set ordering=".($minOrder + $i)." Where id = ".$id);                
            }
            $response->status = '1';
            $response->msg = $module->ajaxTranslate('Update Group Ordering Success!');
        }else{
            $response->status = '0';
            $response->msg = $module->ajaxTranslate('Update Group Ordering not Success!');
        }
		$module->clearCache();
        die(Tools::jsonEncode($response));
    }
    public static function loadAllMenuGroup(){
        $menuId = intval($_POST['menuId']);		
        $module = new  VerticalMegaMenus();
        $response = new stdClass();
        if($menuId >0){
            $response->content = $module->getAllMenuGroup($menuId);
			if($response->content){
				$response->status = 1;
				$response->msg = "Load group in menu success!";
			}else{
				$response->status = 0;
				$response->msg = "Is not group!";
			}
        }else{
        	$response->status = 0;
			$response->msg = "Menu not found!";
        }
        die(Tools::jsonEncode($response));
    }
    public static function getMenuGroup(){
        $itemId = intval($_POST['itemId']);		
        $response = new stdClass();
        $module = new VerticalMegaMenus();
        if($itemId){
        	$response->form = $module->ovicRenderMenuGroupForm($itemId);			
            $response->status = '1';			   
        }else{
            $response->status = '0';
            $response->msg = $module->ajaxTranslate("Group not found.");
        }
        die(Tools::jsonEncode($response)); 
    }
    public static function deleteGroup(){
        $itemId = intval($_POST['itemId']);
        $response = new stdClass();
        $module = new VerticalMegaMenus();		
        if($itemId >0){
            $check = DB::getInstance()->getValue("Select id From "._DB_PREFIX_."verticalmegamenus_menu_items Where groupId = ".$itemId);
            if(!$check){
                if(DB::getInstance()->execute("Delete From "._DB_PREFIX_."verticalmegamenus_groups Where id = ".$itemId)){
                    DB::getInstance()->execute("Delete From "._DB_PREFIX_."verticalmegamenus_group_lang Where group_id = ".$itemId);                
                    $response->status = '1';
                    $response->msg = $module->ajaxTranslate('Delete Group success!');
                }else{
                    $response->status = '0';
                    $response->msg = $module->ajaxTranslate('Delete Group not success.');     
                }
            }else{
                $response->status = '0';
                $response->msg = $module->ajaxTranslate('You need to delete the previous Menu Item.');                
            }
        }else{
            $response->status = '0';
            $response->msg = $module->ajaxTranslate('Group not found.');
        }
		$module->clearCache();
        die(Tools::jsonEncode($response)); 
    }
        
    public static function saveMenuItem(){
        $module = new VerticalMegaMenus();        
		$languages = $module->getAllLanguage();
        $shopId = Context::getContext()->shop->id;
        $db = DB::getInstance();
        $groupId = intval($_POST['groupId']);
        $menuId = intval($_POST['menuId']);		
		$response = new stdClass();
        if($groupId >0){
            $itemId = intval($_POST['menuItemId']);       
			$titles = $_POST['titles'];
			$linkType = $db->escape($_POST['linkType']);
			$link = $db->escape($_POST['link']);
			$menuType = $db->escape($_POST['menuType']);
			$images = $_POST['images'];
			$alts= $_POST['alts'];
			$htmls = $_POST['menuItemHtmls'];			
            if($itemId <=0){				
				$maxOrdering = $db->getValue("Select MAX(ordering) From "._DB_PREFIX_."verticalmegamenus_menu_items Where `groupId` = ".$groupId);
		   		if($maxOrdering >0) $maxOrdering++;
		   		else $maxOrdering = 1;				
                
                if($db->execute("Insert Into "._DB_PREFIX_."verticalmegamenus_menu_items (`menuId`, `groupId`, `parentId`, `menuType`, `linkType`, `link`, `status`, `ordering`) Values ('".$menuId."', '".$groupId."', 0, '".$menuType."', '".$linkType."', '".$link."', '1', '".$maxOrdering."')")){
                    $insertId = $db->Insert_ID();										
					if($languages){
	                	$insertDatas = array();
	                	foreach($languages as $index=>$language){	                		
	                		if($images[$index] && file_exists($module->pathTemp.$images[$index])){	                			                 
			                    $path_info = pathinfo($images[$index]);								
			                    $ext = $path_info['extension'];
			                    $bannerName = $insertId.'-'.$language->id.'-'.$shopId.'-item-banner.'.$ext;
			                    if(copy($module->pathTemp.$images[$index], $module->pathBanner.$bannerName)){
			                    	unlink($module->pathTemp.$images[$index]);
			                    	$insertDatas[] = array('menuId'=>$insertId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'title'=>$db->escape($titles[$index]), 'image'=>$bannerName, 'imageAlt'=>$db->escape($alts[$index]), 'html'=>$htmls[$index]) ;	
			                    }else{
			                    	$insertDatas[] = array('menuId'=>$insertId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'title'=>$db->escape($titles[$index]), 'image'=>'', 'imageAlt'=>$db->escape($alts[$index]), 'html'=>$htmls[$index]) ;
			                    }                    
			                                       
			                }else{
			                	$insertDatas[] = array('menuId'=>$insertId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'title'=>$db->escape($titles[$index]), 'image'=>'', 'imageAlt'=>$db->escape($alts[$index]), 'html'=>$htmls[$index]) ;
			                }
	                	}
						if($insertDatas) $db->insert('verticalmegamenus_menu_item_lang', $insertDatas);
	                }                    
                    $response->status = '1';
                    $response->msg = $module->ajaxTranslate("Add new Menu item Success!");
                }else{
                    $response->status = '0';
                    $response->msg = $module->ajaxTranslate("Add new Menu item not Success!");
                }
            }else{
            	                
                $item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."verticalmegamenus_menu_items Where id = ".$itemId);                
                DB::getInstance()->execute("Update "._DB_PREFIX_."verticalmegamenus_menu_items Set `menuType` = '".$menuType."', `linkType` = '".$linkType."', `link` = '".$link."' Where id = ".$itemId);                
				if($languages){
					$insertDatas = array();
                	foreach($languages as $index=>$language){
						$check = DB::getInstance()->getValue("Select menuId From "._DB_PREFIX_."verticalmegamenus_menu_item_lang Where menuId = ".$itemId." AND `id_lang` = ".$language->id." AND `id_shop` = ".$shopId);	                		
                		if($images[$index] && file_exists($module->pathTemp.$images[$index])){                    
		                    $path_info = pathinfo($images[$index]);
		                    $ext = $path_info['extension'];
		                    $bannerName = $itemId.'-'.$language->id.'-'.$shopId.'-item-banner.'.$ext;
		                    copy($module->pathTemp.$images[$index], $module->pathBanner.$bannerName);                    
		                    unlink($module->pathTemp.$images[$index]);
		                    if($check){
		                    	$db->execute("Update "._DB_PREFIX_."verticalmegamenus_menu_item_lang Set `title` = '".$db->escape($titles[$index])."', `image` = '$bannerName', `imageAlt` = '".$db->escape($alts[$index])."', `html` = '".$htmls[$index]."' Where `menuId` = $itemId AND `id_lang` = ".$language->id." AND `id_shop` = ".$shopId);	
		                    }else{
		                    	$insertDatas[] = array('menuId'=>$itemId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'title'=>$db->escape($titles[$index]), 'image'=>$bannerName, 'imageAlt'=>$db->escape($alts[$index]), 'html'=>$htmls[$index]) ;
		                    }
		                }else{
		                	if($check){
		                    	$db->execute("Update "._DB_PREFIX_."verticalmegamenus_menu_item_lang Set `title` = '".$db->escape($titles[$index])."', `imageAlt` = '".$db->escape($alts[$index])."', `html` = '".$htmls[$index]."'  Where `menuId` = $itemId AND `id_lang` = ".$language->id." AND `id_shop` = ".$shopId);	
		                    }else{
		                    	$insertDatas[] = array('menuId'=>$itemId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'title'=>$db->escape($titles[$index]), 'image'=>'', 'imageAlt'=>$db->escape($alts[$index]), 'html'=>$htmls[$index]) ;
		                    }
		                	
		                }
						if($insertDatas) DB::getInstance()->insert('verticalmegamenus_menu_item_lang', $insertDatas);
                	}
                }
            }
			$response->status = 1;
            $response->msg = $module->ajaxTranslate("Update menu item success!");  
        }else{
            $response->status = '0';
            $response->msg = $module->ajaxTranslate("Menu Item not found");
        }
		$module->clearCache();
        die(Tools::jsonEncode($response));
    }
    
    
    public static function loadAllMenuItem(){
        $groupId = intval($_POST['groupId']);
        $module = new  VerticalMegaMenus();
        $langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;
        $response = '';
        
        if($groupId >0){            
            $items = DB::getInstance()->executeS("Select * From "._DB_PREFIX_."verticalmegamenus_menu_items Where groupId = ".$groupId." Order By ordering");
            if($items){
                foreach($items as $item){
                    $itemLang = VerticalMegaMenusLibraries::getMenuItemLangById($item['id'], $langId, $shopId);
                    
                    if($item['status'] == "1"){
                        $status = '<a title="Enabled" class="list-action-enable action-enabled lik-menu-item-status" item-id="'.$item['id'].'" value="'.$item['status'].'"><i class="icon-check"></i></a>';
                    }else{
                        $status = '<a title="Disabled" class="list-action-enable action-disabled lik-menu-item-status" item-id="'.$item['id'].'" value="'.$item['status'].'"><i class="icon-check"></i></a>';
                    }
                    $banner = $module->getBannerSrc($item['image'], true);
                    if($banner) $banner = '<img src="'.$banner.'" width="40" alt="'.$itemLang['imageAlt'].'" />';
                    $response .= '<tr id="mni_'.$item['id'].'">
                                        <td class="center">'.$item['id'].'</td>                                        
                                        <td>'.$itemLang['title'].'</td>                    
                                        <td class="center">'.$module->arrType[$item['menuType']].'</td>
                                        <td>'.$item['link'].'</td>
                                        <td class="center">'.$banner.'</td>
                                        <td class="pointer dragHandle center" ><div class="dragGroup"><div class="positions">'.$item['ordering'].'</div></div></td>
                                        <td class="center">'.$status.'</td>
                                        <td class="center"><a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-menu-item-edit"><i class="icon-edit"></i></a>&nbsp;<a href="javascript:void(0)" item-id="'.$item['id'].'" class="lik-menu-item-delete"><i class="icon-trash" ></i></a></td>
                                    </tr>';
                }
            }   
        }
        die(Tools::jsonEncode($response));
    }
    
    
    public static function updateMenuItemOrdering(){
        $module = new VerticalMegaMenus();
        $response = new stdClass();
        $ids = $_POST['ids'];        
        if($ids){
            $strIds = implode(', ', $ids);            
            $minOrder = DB::getInstance()->getValue("Select Min(ordering) From "._DB_PREFIX_."verticalmegamenus_menu_items Where id IN ($strIds)");            
            foreach($ids as $i=>$id){
                DB::getInstance()->query("Update "._DB_PREFIX_."verticalmegamenus_menu_items Set ordering=".($minOrder + $i)." Where id = ".$id);                
            }
            $response->status = '1';
            $response->msg = $module->ajaxTranslate('Update Menu Item Ordering Success!');
        }else{
            $response->status = '0';
            $response->msg = $module->ajaxTranslate('Update Menu Item Ordering not Success!');
        }
		$module->clearCache();
        die(Tools::jsonEncode($response));
    }
    
    
    public static function getMenuItem(){
        $itemId = intval($_POST['itemId']);
        $module = new VerticalMegaMenus();
        $response = new stdClass();
        
        if($itemId){
            $response->content = $module->ovicRenderMenuItemForm($itemId);
            if($response->content) $response->status = '1';
			else {
				$response->status = 0;
				$response->msg = "Item not found!";
			}
        }else{
            $response->status = '0';
            $response->msg = $module->ajaxTranslate('Item not found.');
        }
        die(Tools::jsonEncode($response));
    }
    
    public static function deleteMenuItem(){
        $itemId = intval($_POST['itemId']);
        $response = new stdClass();
        $module = new VerticalMegaMenus();        
        if($itemId >0){
            $item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."verticalmegamenus_menu_items Where id = ".$itemId);
            if($item){
                if(DB::getInstance()->execute("Delete From "._DB_PREFIX_."verticalmegamenus_menu_items Where id = ".$itemId)){
                    DB::getInstance()->execute("Delete From "._DB_PREFIX_."verticalmegamenus_menu_item_lang Where menuId = ".$itemId);                    
                    if($item['image'] && file_exists($module->pathBanner.$item['image'])) unlink($module->pathBanner.$item['image']);
                    $response->status = '1';
                    $response->msg = $module->ajaxTranslate('Delete menu success!');
                }else{
                   $response->status = '0';
                    $response->msg = $module->ajaxTranslate('Delete menu not success.'); 
                }
            }else{
                $response->status = '0';
                $response->msg = $module->ajaxTranslate('Item menu not found.');
            }
        }else{
            $response->status = '0';
            $response->msg = $module->ajaxTranslate('Item menu not found.');
        }
		$module->clearCache();
        die(Tools::jsonEncode($response));
    }
    public static function changModuleStatus(){
		$module = new VerticalMegaMenus();
		$itemId = intval($_POST['itemId']);
		$value = intval($_POST['value']);		
		$response = new stdClass();
		if($value == '1'){
			DB::getInstance()->execute("Update "._DB_PREFIX_."verticalmegamenus_modules Set `status` = 0 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}else{
			DB::getInstance()->execute("Update "._DB_PREFIX_."verticalmegamenus_modules Set `status` = 1 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}
		$module->clearCache();
		die(Tools::jsonEncode($response));
	}
    public static function changMenuStatus(){
		$module = new VerticalMegaMenus();
		$itemId = intval($_POST['itemId']);
		$value = intval($_POST['value']);		
		$response = new stdClass();
		if($value == '1'){
			DB::getInstance()->execute("Update "._DB_PREFIX_."verticalmegamenus_menus Set `status` = 0 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}else{
			DB::getInstance()->execute("Update "._DB_PREFIX_."verticalmegamenus_menus Set `status` = 1 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}
		$module->clearCache();
		die(Tools::jsonEncode($response));
	}
    public static function changGroupStatus(){
		$module = new VerticalMegaMenus();
		$itemId = intval($_POST['itemId']);
		$value = intval($_POST['value']);		
		$response = new stdClass();
		if($value == '1'){
			DB::getInstance()->execute("Update "._DB_PREFIX_."verticalmegamenus_groups Set `status` = 0 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}else{
			DB::getInstance()->execute("Update "._DB_PREFIX_."verticalmegamenus_groups Set `status` = 1 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}
		$module->clearCache();
		die(Tools::jsonEncode($response));
	}
    
    public static function changMenuItemStatus(){
		$module = new VerticalMegaMenus();
		$itemId = intval($_POST['itemId']);
		$value = intval($_POST['value']);		
		$response = new stdClass();
		if($value == '1'){
			DB::getInstance()->execute("Update "._DB_PREFIX_."verticalmegamenus_menu_items Set `status` = 0 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}else{
			DB::getInstance()->execute("Update "._DB_PREFIX_."verticalmegamenus_menu_items Set `status` = 1 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}
		$module->clearCache();
		die(Tools::jsonEncode($response));
	}
}

