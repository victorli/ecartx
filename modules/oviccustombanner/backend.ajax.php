<?php
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
require_once(dirname(__FILE__).'/oviccustombanner.php');
$module = new OvicCustomBanner();
if (!Tools::isSubmit('secure_key') || Tools::getValue('secure_key') != $module->secure_key){
	$response = new stdClass();
	$response->status = 0;
	$response->msg = "you need to login with the admin account.";
	die(Tools::jsonEncode($response));
}
$action = Tools::getValue('action');
OvicCustomBannerAjax::$action();
class OvicCustomBannerAjax{        
	static function saveBanner(){
       $module = new  OvicCustomBanner();
       $module->clearCache();
       $shopId = Context::getContext()->shop->id;
       $itemId = Tools::getValue('bannerId');
	   $position = Tools::getValue('position');
	   $position_name = Hook::getNameById($position);		
	   $langs = $module->getArrLangs();
       $titles = $_POST['titles'];
       $images = $_POST['images'];
	   $links = $_POST['links'];
       $params  = array('layout'=>Tools::getValue('layout', 'default'), 'width'=>Tools::getValue('width', 'none-column'), 'className'=>Tools::getValue('className', ''));
       $response = new stdClass();
	   $db = DB::getInstance();
       if($itemId == 0){
       		$maxOrdering = $db->getValue("Select MAX(ordering) From "._DB_PREFIX_."ovic_custom_banners Where `position` = ".$position);
		   if($maxOrdering >0) $maxOrdering++;
		   else $maxOrdering = 1;            
            if($db->execute("Insert Into "._DB_PREFIX_."ovic_custom_banners (`id_shop`, `position`, `position_name`, `status`, `params`, `ordering`) Values ('".$shopId."', '$position', '$position_name', 1, '".json_encode($params)."', '$maxOrdering')")){
                $insertId = $db->Insert_ID();                
                if($langs){
                	$arrSql = array();
                	foreach($langs as $index=>$lang){
                		if($images[$index] && file_exists($module->pathTemp.$images[$index])){                    
		                    $path_info = pathinfo($images[$index]);
		                    $ext = $path_info['extension'];
		                    $bannerName = $insertId.'-'.$lang->id.'-'.$shopId.'-banner.'.$ext;
		                    copy($module->pathTemp.$images[$index], $module->pathBanner.$bannerName);                    
		                    unlink($module->pathTemp.$images[$index]);
		                    $arrSql[] = array('bannerId'=>$insertId, 'id_lang'=>$lang->id, 'id_shop'=>$shopId, 'banner_image'=>$bannerName, 'banner_link'=>$db->escape($links[$index]), 'banner_title'=>$db->escape($titles[$index])) ;//"Insert Into "._DB_PREFIX_."ovic_custom_banner_lang (`bannerId`, `id_lang`, `id_shop`, `banner_image`, `banner_link`, `banner_title`) Values ('$insertId', '".$lang->id."', '$shopId', '".$bannerName."', '".$links[$index]."', '".$titles[$index]."')";                   
		                }
                	}
					if($arrSql) $db->insert('ovic_custom_banner_lang', $arrSql);
                }                
                $response->status = 1;
				$response->msg = 'Add new Group Success!';      
            }else{
            	$response->status = 0;
				$response->msg = 'Error: Add new Group Failed!';        
            }
       }else{
            $item = $db->getRow("Select * From "._DB_PREFIX_."ovic_custom_banners Where id = ".$itemId);
            if($item){
                $db->execute("Update "._DB_PREFIX_."ovic_custom_banners Set `params`='".json_encode($params)."', `position` = '$position', `position_name` = '$position_name' Where id = ".$itemId);
				$arrSql = array();                
				foreach($langs as $index=>$lang){
					$check = DB::getInstance()->getValue("Select bannerId From "._DB_PREFIX_."ovic_custom_banner_lang Where bannerId = ".$itemId." AND id_lang = ".$lang->id." AND id_shop = ".$shopId);
            		if($images[$index] && file_exists($module->pathTemp.$images[$index])){                    
	                    $path_info = pathinfo($images[$index]);
	                    $ext = $path_info['extension'];
	                    $bannerName = $itemId.'-'.$lang->id.'-'.$shopId.'-banner.'.$ext;
	                    copy($module->pathTemp.$images[$index], $module->pathBanner.$bannerName);                    
	                    unlink($module->pathTemp.$images[$index]);
						if($check){
							$db->execute("Update "._DB_PREFIX_."ovic_custom_banner_lang Set `banner_image` = '".$bannerName."', `banner_link`='".$db->escape($links[$index])."', `banner_title`='".$db->escape($titles[$index])."' Where `bannerId` = '".$itemId."' AND `id_lang` = '".$lang->id."' AND `id_shop` = '".$shopId."'");	
						}else{
							$arrSql[] = array('bannerId'=>$itemId, 'id_lang'=>$lang->id, 'id_shop'=>$shopId, 'banner_image'=>$bannerName, 'banner_link'=>$db->escape($links[$index]), 'banner_title'=>$db->escape($titles[$index])) ;//"Insert Into "._DB_PREFIX_."ovic_custom_banner_lang (`bannerId`, `id_lang`, `id_shop`, `banner_image`, `banner_link`, `banner_title`) Values ('$insertId', '".$lang->id."', '$shopId', '".$bannerName."', '".$links[$index]."', '".$titles[$index]."')";
						}
						                  
	                }else{
	                	if($check){
	                		$db->execute("Update "._DB_PREFIX_."ovic_custom_banner_lang Set `banner_link`='".$db->escape($links[$index])."', `banner_title`='".$db->escape($titles[$index])."' Where `bannerId` = '".$itemId."' AND `id_lang` = '".$lang->id."' AND `id_shop` = '".$shopId."'");	
	                	}else{
	                		$arrSql[] = array('bannerId'=>$itemId, 'id_lang'=>$lang->id, 'id_shop'=>$shopId, 'banner_image'=>$bannerName, 'banner_link'=>$db->escape($links[$index]), 'banner_title'=>$db->escape($titles[$index])) ;//"Insert Into "._DB_PREFIX_."ovic_custom_banner_lang (`bannerId`, `id_lang`, `id_shop`, `banner_link`, `banner_title`) Values ('$insertId', '".$lang->id."', '$shopId', '".$links[$index]."', '".$titles[$index]."')";
	                	}
	                	
	                }
            	}
				if($arrSql) $db->insert('ovic_custom_banner_lang', $arrSql);
				$response->status = 1;
				$response->msg = 'Update Group success!';				   
            }else{
				$response->status = 1;
				$response->msg = 'Error: Not isset Group.';
            }
       }
       die(Tools::jsonEncode($response));               
	}
	static function loadAllBanner(){
		$response = new stdClass();
		$module = new OvicCustomBanner();
		$response->content = $module->getAllBanner();
		if($response->content) $response->status = 1;
		else $response->status = 0;
		die(Tools::jsonEncode($response));
	}
	public static function changeBannerStatus(){
		$itemId = intval($_POST['itemId']);
		$value = intval($_POST['value']);		
		$response = new stdClass();
		$module = new  OvicCustomBanner();
       	$module->clearCache();
		if($value == '1'){
			DB::getInstance()->execute("Update "._DB_PREFIX_."ovic_custom_banners Set `status` = 0 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}else{
			DB::getInstance()->execute("Update "._DB_PREFIX_."ovic_custom_banners Set `status` = 1 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}
		die(Tools::jsonEncode($response));
	}
	
	
	public static function updateBannerOrdering(){
		$ids = $_POST['ids'];     
		$response = new stdClass();
        if($ids){
            $strIds = implode(', ', $ids);            
            $minOrder = DB::getInstance()->getValue("Select Min(ordering) From "._DB_PREFIX_."ovic_custom_banners Where id IN ($strIds)");            
            foreach($ids as $i=>$id){
                DB::getInstance()->query("Update "._DB_PREFIX_."ovic_custom_banners Set ordering=".($minOrder + $i)." Where id = ".$id);                
            }
            $module = new  OvicCustomBanner();
            $module->clearCache();
            $response->status = 1;
            $response->msg='Update ordering success!';
        }else{
        	$response->status = 0;
            $response->msg='Update ordering not success!';
        }
        die(Tools::jsonEncode($response));
	}
	
	
	public static function loadBanner(){		
		$module = new OvicCustomBanner();			
		$itemId = intval($_POST['itemId']);			
		$response = new stdClass();		
		if($itemId){
			$response->status = 1;
			$response->form = $module->ovicRenderForm($itemId);			
						
		}else{
			$response->status = 0;
			$response->msg = 'Group not Found.';
		}
		die(Tools::jsonEncode($response));
	}	
	public static function deleteBanner(){
		$module = new OvicCustomBanner();
		$itemId = intval($_POST['itemId']);
		$response = new stdClass();
		if(DB::getInstance()->execute("Delete From "._DB_PREFIX_."ovic_custom_banners where id = $itemId")){
			$items = DB::getInstance()->executeS("Select * From "._DB_PREFIX_."ovic_custom_banner_lang Where bannerId = ".$itemId);
			if($items){
				foreach($items as $item){
					if($item['banner_image'] && file_exists($module->pathBanner.$item['banner_image'])) unlink($module->pathBanner.$item['banner_image']);
				}
			}
			DB::getInstance()->execute("Delete From "._DB_PREFIX_."ovic_custom_banner_lang where bannerId = $itemId");
			$module->clearCache();			
			$response->status = 1;
			$response->msg = "Delete Group Success!";
		}else{
			$response->status = 0;
			$response->msg = "Delete Group not Success!";
		}
		die(Tools::jsonEncode($response));
	}
}
