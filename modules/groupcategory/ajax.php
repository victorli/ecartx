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
require_once(dirname(__FILE__).'/groupcategory.php');
$module = new GroupCategory();
if (!Tools::isSubmit('secure_key') || Tools::getValue('secure_key') != $module->secure_key){
	$response = new stdClass();
	$response->status = 0;
	$response->msg = "you need to login with the admin account.";
	die(Tools::jsonEncode($response));
}

$action = Tools::getValue('action');
GroupCategoryAjax::$action();

class GroupCategoryAjax{
    static function clearCache(){
        $module = new  GroupCategory();
        $module->clearCache();
        $response = 'Clear cache success!';
        die(Tools::jsonEncode($response)); 
    }
    
    
    
    
	//
    public static function loadGroupByLang(){
        $shopId = Context::getContext()->shop->id;
        $langId = intval($_POST['langId']);
        $itemId = intval($_POST['itemId']);
        $item = GroupCategoryLibraries::getGroupLangById($itemId, $langId, $shopId);// DB::getInstance()->getRow("Select * From "._DB_PREFIX_."flexiblecustom_modules_lang Where module_id = ".$itemId." AND id_lang = ".$langId." AND id_shop = ".$shopId);
        $response = new stdClass();
        if($item){
            $response->name = $item['name'];  
			$response->banner = $item['banner'];
			$response->banner_link = $item['banner_link'];
        }else{
            $response->name = '';            
			$response->banner = '';
			$response->banner_link = '';
        }
        die(Tools::jsonEncode($response));
    }
    // save group
    public static function saveGroup(){
    	
    	$module = new GroupCategory();		
	   	$module->clearCache();
        $shopId = Context::getContext()->shop->id;
		$languages = $module->getAllLanguage();		
        $db= DB::getInstance();
        $groupId = intval($_POST['groupId']);
		
		$names = Tools::getValue('names', array());
		$groupIcon = Tools::getValue('groupIcon', '');
		$banners = Tools::getValue('banners', array());
		$links = Tools::getValue('links', array());
		$style_id = intval($_POST['style_id']);
		$categoryId = intval($_POST['categoryId']);
		$type_default = $_POST['type_default'];
		$types = Tools::getValue('types', array());		
		if($types){
			$types = json_encode($_POST['types']);
		}else{
			$types = '';
		}
		$manufacturerIds = Tools::getValue('manufacturerIds', array());
		$imageWidth = intval($_POST['imageWidth']);
		$imageHeight = intval($_POST['imageHeight']);
		$layout = 'default';
		$position = intval($_POST['position']);				$position_name = Hook::getNameById($position);		
		
        
        
        $itemData = new stdClass();
        $itemData->itemWidth  = $module->imageHomeSize['width'];
        $itemData->itemHeight  = $module->imageHomeSize['height'];
		$itemData->itemMinWidth = 200;
		$itemData->countItem = intval($_POST['countItem']);
		
		
        
		
                
                
        $manufacturer = new stdClass();
		$manufacturer->ids = array();
        $manIds = array();
        if($manufacturerIds){
            foreach($manufacturerIds as $i=>$value){                
                if($value != '0') $manufacturer->ids[] = $value;
            }
        }
        $manufacturer->imageWidth = $imageWidth;
        $manufacturer->imageHeight = $imageHeight;
        $manufactureConfig = json_encode($manufacturer);
		
		
        $itemConfig = json_encode($itemData);
        require_once(dirname(__FILE__).'/GroupCategoryThumb.php');
        $img = new GroupCategoryThumb();
        $response = new stdClass();		
        if($groupId == 0){
        	$maxOrdering = $db->getValue("Select MAX(ordering) From "._DB_PREFIX_."groupcategory_groups Where `position` = ".$position);
	   		if($maxOrdering >0) $maxOrdering++;
	   		else $maxOrdering = 1;
            $sql = "Insert Into "._DB_PREFIX_."groupcategory_groups (`position`, `position_name`, `id_shop`, `categoryId`, `style_id`, `manufactureConfig`, `itemConfig`, `types`, `ordering`, `status`, `type_default`, `layout`) Values ('".$position."', '$position_name', '$shopId', '".$categoryId."', '".$style_id."', '".$manufactureConfig."', '".$itemConfig."', '".$types."', '".$maxOrdering."', 1, '".$type_default."', '".$layout."')";
            if(DB::getInstance()->execute($sql)){
                $insertId = DB::getInstance()->Insert_ID();
				if($groupIcon && file_exists($module->pathTemp.$groupIcon)){                    
                    $path_info = pathinfo($groupIcon);
                    $ext = $path_info['extension'];
                    $iconName = $insertId.'-'.$shopId.'-icon.'.$ext;
					if(copy($module->pathTemp.$groupIcon, $module->pathBanner.$iconName)){
						unlink($module->pathTemp.$groupIcon);
						DB::getInstance()->execute("Update "._DB_PREFIX_."groupcategory_groups Set icon='".$iconName."' Where id = ".$insertId);	
					}                    
                                        
                }
				if($languages){
                	$insertDatas = array();
                	$defaultBanner = array();
                	foreach($languages as $index=>$language){	                		
                		if($banners[$index] && file_exists($module->pathTemp.$banners[$index])){	
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
													
							$insertDatas[] = array('group_id'=>$insertId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'name'=>$db->escape($names[$index]), 'banner'=>$bannerName, 'banner_link'=>$db->escape($links[$index]), 'banner_size'=>json_encode($params)) ;							              
		                }else{
		                	if($defaultBanner){
		                		$path_info = pathinfo($defaultBanner['banner']);
		                		$ext = $path_info['extension'];
		                    	$bannerName = $insertId.'-'.$language->id.'-'.$shopId.'-banner.'.$ext;
		                		$insertDatas[] = array('group_id'=>$insertId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'name'=>$db->escape($names[$index]), 'banner'=>$bannerName, 'banner_link'=>$db->escape($links[$index]), 'banner_size'=>json_encode($defaultBanner['params'])) ;
		                	}else{
		                		$insertDatas[] = array('group_id'=>$insertId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'name'=>$db->escape($names[$index]), 'banner'=>'', 'banner_link'=>$db->escape($links[$index])) ;	
		                	}		                	
		                }
                	}
					if($insertDatas) $db->insert('groupcategory_group_lang', $insertDatas);
                }
				
                $response->status = 1;
                $response->msg = "Add new Group Success!";
            }else{
                $response->status = 0;
                $response->msg = "Add new Group not Success!";
            }
        }else{
			
            $item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."groupcategory_groups Where id = ".$groupId);
            if($item){
                $sql = "Update "._DB_PREFIX_."groupcategory_groups Set 
                    `position` = ".$position.", 					
                    `position_name` = '".$position_name."',
                    `id_shop` = '".$shopId."', 
                    `categoryId` = '".$categoryId."',
                    `type_default` = '".$type_default."', 
                    `style_id` = '".$style_id."', 
                    `manufactureConfig` = '".$manufactureConfig."', 
                    `itemConfig` = '".$itemConfig."', 
                    `types` = '".$types."',                     
                    `layout` = '".$layout."'  Where id = ".$groupId;
                    DB::getInstance()->execute($sql);
					
					if($groupIcon && file_exists($module->pathTemp.$groupIcon)){                    
                        $path_info = pathinfo($groupIcon);
                        $ext = $path_info['extension'];
                        $iconName = $groupId.'-'.$shopId.'-icon.'.$ext;
						copy($module->pathTemp.$groupIcon, $module->pathIcon.$iconName);						
                        unlink($module->pathTemp.$groupIcon);
						DB::getInstance()->execute("Update "._DB_PREFIX_."groupcategory_groups Set `icon`='$iconName' Where `id` = ".$groupId);                    
                    }
					
					if($languages){
	                	$insertDatas = array();
	                	$defaultBanner = array();
	                	foreach($languages as $index=>$language){
	                		$check = DB::getInstance()->getValue("Select group_id From "._DB_PREFIX_."groupcategory_group_lang Where group_id = ".$groupId." AND `id_lang` = ".$language->id." AND id_shop = ".$shopId);						
	                		if($banners[$index] && file_exists($module->pathTemp.$banners[$index])){                    
			                    $path_info = pathinfo($banners[$index]);
			                    $ext = $path_info['extension'];
			                    $bannerName = $groupId.'-'.$language->id.'-'.$shopId.'-banner.'.$ext;
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
			                    if($check){
			                    	$db->execute("Update "._DB_PREFIX_."groupcategory_group_lang Set `name` = '".$db->escape($names[$index])."', `banner` = '$bannerName', `banner_link` = '".$db->escape($links[$index])."', `banner_size` = '".json_encode($params)."' Where `group_id` = $groupId AND `id_lang` = ".$language->id." AND `id_shop` = ".$shopId);	
			                    }else{
			                    	$insertDatas[] = array('group_id'=>$itemId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'name'=>$db->escape($names[$index]), 'banner'=>$bannerName, 'banner_link'=>$db->escape($links[$index]), 'banner_size'=>json_encode($params)) ;
			                    }		                    
			                }else{
			                	if($check){
			                		$db->execute("Update "._DB_PREFIX_."groupcategory_group_lang Set `name` = '".$db->escape($names[$index])."', `banner_link` = '".$db->escape($links[$index])."'  Where `group_id` = $groupId AND `id_lang` = ".$language->id." AND `id_shop` = ".$shopId);
								}else{
									if($defaultBanner){
				                		$path_info = pathinfo($defaultBanner['banner']);
				                		$ext = $path_info['extension'];
				                    	$bannerName = $groupId.'-'.$language->id.'-'.$shopId.'-banner.'.$ext;
				                		$insertDatas[] = array('group_id'=>$groupId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'name'=>$db->escape($names[$index]), 'banner'=>$bannerName, 'banner_link'=>$db->escape($links[$index]), 'banner_size'=>json_encode($defaultBanner['params'])) ;
				                	}else{
				                		$insertDatas[] = array('group_id'=>$groupId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'name'=>$db->escape($names[$index]), 'banner'=>'', 'banner_link'=>$db->escape($links[$index])) ;	
		                			}	
								}	
			                }
	                	}
						if($insertDatas) $db->insert('groupcategory_group_lang', $insertDatas);
	                }
                    $response->status = 0;
                    $response->msg = "Update Group Success!";
            }else{
                $response->status = 0;
                $response->msg = "Item not found!";
            }            
        }
		$module->clearCache();
        die(Tools::jsonEncode($response));
    }
	
	
    public static function loadGroup(){
        $itemId = intval($_POST['itemId']);
		$module = new GroupCategory();		
        $response = new stdClass();
        $data = new stdClass();
        if($itemId >0){
        	$response->form = $module->ovicRenderGroupForm($itemId);
			if($response->form) $response->status = 1;
			else{
				$response->status = 0;
				$response->smg = 'Item not found!';
			}
        }else{
            $response->status = '0';
            $response->msg = 'Item not found!';
        }
        die(Tools::jsonEncode($response));
    }
    public static function loadAllGroup(){
        $module = new GroupCategory();        
        $response = new stdClass();
        $response->status = '1';
        $response->data =  $module->getAllGroup();
        die(Tools::jsonEncode($response));
    }
    
    
    
    
    
    
    
    
}
