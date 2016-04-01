<?php
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
require_once(dirname(__FILE__).'/oviccustomtags.php');
$module = new OvicCustomTags();
if (!Tools::isSubmit('secure_key') || Tools::getValue('secure_key') != $module->secure_key || !Tools::getValue('action')){
	$response = new stdClass();
	$response->status = 0;
	$response->msg = "you need to login with the admin account.";
	die(Tools::jsonEncode($response));
}
$module->clearCache();
$action = Tools::getValue('action');
OvicCustomTagAjax::$action();
class OvicCustomTagAjax{        
	static function saveGroup(){
		$db = DB::getInstance();
		$module = new OvicCustomTags();
		$module->clearCache();
		$shopId = Context::getContext()->shop->id;
		$languages = $module->getAllLanguages();
	   $itemId = Tools::getValue('groupId');
	   $names = Tools::getValue('names', array());
	   $position = intval($_POST['position']);
	   $position_name = Hook::getNameById($position);		
	   $arrParams = array('background'=>Tools::getValue('background', '#82A3CC'), 'color'=>Tools::getValue('color', '#ffffff'));	   
	   $params = json_encode($arrParams);
	   
	   $response = new stdClass();
	   if($itemId == 0){
			$maxOrdering = $db->getValue("Select MAX(ordering) From "._DB_PREFIX_."ovic_custom_tags_groups Where `position` = ".$position);
			if($maxOrdering >0) $maxOrdering++;
			else $maxOrdering = 1;            
			if($db->execute("Insert Into "._DB_PREFIX_."ovic_custom_tags_groups (`id_shop`, `position`, `position_name`, `status`, `params`, `ordering`) Values ('".$shopId."', '$position', '$position_name', '1', '$params', '$maxOrdering')")){
				$insertId = $db->Insert_ID();				
				if($languages){
					$arrInsert = array();
					foreach($languages as $index=>$language){
						$arrInsert[] = array('groupId'=>$insertId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'name'=>$db->escape($names[$index]));
					}
					if($arrInsert) $db->insert('ovic_custom_tags_group_lang', $arrInsert);
				}
				$response->status = 1;
				$response->msg = 'Add new Group Success!';      
			}else{
				$response->status = 0;
				$response->msg = 'Error: Add new Group Failed!';        
			}
	   }else{
	        $item = $db->getRow("Select * From "._DB_PREFIX_."ovic_custom_tags_groups Where id = ".$itemId);
			if($item){
				$db->execute("Update "._DB_PREFIX_."ovic_custom_tags_groups Set `params`='$params', `position` = '$position', `position_name`='$position_name' Where id = ".$itemId);
				
				$arrInsert = array();                
				foreach($languages as $index=>$language){
					$check = $db->getValue("Select groupId From "._DB_PREFIX_."ovic_custom_tags_group_lang Where groupId = ".$itemId." AND id_lang = ".$language->id." AND id_shop = ".$shopId);
					if($check){
						$db->execute("Update "._DB_PREFIX_."ovic_custom_tags_group_lang Set `name`='".$db->escape($names[$index])."' Where `groupId` = '".$itemId."' AND `id_lang` = '".$language->id."' AND `id_shop` = '".$shopId."'");	
					}else{
						$arrInsert[] = array('groupId'=>$itemId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'name'=>$db->escape($names[$index]));
					}
				}
				if($arrInsert) $db->insert('ovic_custom_tags_group_lang', $arrInsert);				
				$response->status = 1;
				$response->msg = 'Update Group success!';				   
			}else{
				$response->status = 1;
				$response->msg = 'Error: Not isset Group.';
			}
	   }
	   die(Tools::jsonEncode($response));               
	}
	static function loadAllGroups(){
		$response = new stdClass();
		$module = new OvicCustomTags();
		$response->content = $module->getAllGroups();
		if($response->content) $response->status = 1;
		else $response->status = 0;
		die(Tools::jsonEncode($response));
	}
	public static function changGroupStatus(){
		$itemId = intval($_POST['itemId']);
		$value = intval($_POST['value']);		
		$response = new stdClass();
		$module = new  OvicCustomTags();
		$module->clearCache();
		if($value == '1'){
			DB::getInstance()->execute("Update "._DB_PREFIX_."ovic_custom_tags_groups Set `status` = 0 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}else{
			DB::getInstance()->execute("Update "._DB_PREFIX_."ovic_custom_tags_groups Set `status` = 1 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}
		die(Tools::jsonEncode($response));
	}
	public static function changTagStatus(){
		$itemId = intval($_POST['itemId']);
		$value = intval($_POST['value']);		
		$response = new stdClass();
		$module = new  OvicCustomTags();
       	$module->clearCache();
		if($value == '1'){
			DB::getInstance()->execute("Update "._DB_PREFIX_."ovic_custom_tags_tags Set `status` = 0 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}else{
			DB::getInstance()->execute("Update "._DB_PREFIX_."ovic_custom_tags_tags Set `status` = 1 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}
		die(Tools::jsonEncode($response));
	}
	
	
	public static function updateGroupOrdering(){
		$ids = $_POST['ids'];     
		$response = new stdClass();
		$module = new  OvicCustomTags();
        $module->clearCache();
        if($ids){
            $strIds = implode(', ', $ids);            
            $minOrder = DB::getInstance()->getValue("Select Min(ordering) From "._DB_PREFIX_."ovic_custom_tags_groups Where id IN ($strIds)");            
            foreach($ids as $i=>$id){
                DB::getInstance()->query("Update "._DB_PREFIX_."ovic_custom_tags_groups Set ordering=".($minOrder + $i)." Where id = ".$id);                
            }
            
            $response->status = 1;
            $response->msg='Update ordering success!';
        }else{
        	$response->status = 0;
            $response->msg='Update ordering not success!';
        }
        die(Tools::jsonEncode($response));
	}
	
	public static function loadTag(){
		$module = new OvicCustomTags();		
		$itemId = intval($_POST['itemId']);
		$response = new stdClass();
		if($itemId){
            $response->form = $module->ovicRenderTagForm($itemId);
            $response->status = 1;            					
		}else{
			$response->status = 0;
			$response->msg = 'Tag not Found.';
		}
		die(Tools::jsonEncode($response));
	}
	public static function loadGroup(){
		$module = new OvicCustomTags();		
		$itemId = intval($_POST['itemId']);
		$response = new stdClass();
		if($itemId){
			$response->form = $module->ovicRenderGroupForm($itemId);
			$response->status = 1;				
		}else{
			$response->status = 0;
			$response->msg = 'Group not Found.';
		}
		die(Tools::jsonEncode($response));
	}
	
	public static function deleteTag(){
				
		$itemId = intval($_POST['itemId']);
		$response = new stdClass();
		if(DB::getInstance()->execute("Delete From "._DB_PREFIX_."ovic_custom_tags_tags where id = $itemId")){
			$module = new  OvicCustomTags();
			$module->clearCache();
			DB::getInstance()->execute("Delete From "._DB_PREFIX_."ovic_custom_tags_tag_lang where tagId = $itemId");			
			$response->status = 1;
			$response->msg = "Delete Tag Success!";
		}else{
			$response->status = 0;
			$response->msg = "Delete Tag not Success!";
		}
		die(Tools::jsonEncode($response));
	}
	public static function deleteGroup(){
		
		$itemId = intval($_POST['itemId']);
		$response = new stdClass();
		if(DB::getInstance()->execute("Delete From "._DB_PREFIX_."ovic_custom_tags_groups where id = $itemId")){
			$module = new  OvicCustomTags();
       		$module->clearCache();	
			DB::getInstance()->execute("Delete From "._DB_PREFIX_."ovic_custom_tags_group_lang where groupId = $itemId");
			DB::getInstance()->execute("Delete From "._DB_PREFIX_."ovic_custom_tags_tag_lang Where tagId IN (Select id From "._DB_PREFIX_."ovic_custom_tags_tags Where groupId = $itemId)");
			DB::getInstance()->escape("Delete From "._DB_PREFIX_."ovic_custom_tags_tags Where groupId = ".$itemId);
			$response->status = 1;
			$response->msg = "Delete Group Success!";
		}else{
			$response->status = 0;
			$response->msg = "Delete Group not Success!";
		}
		die(Tools::jsonEncode($response));
	}
	
	public static function loadGroupByLang(){
		$itemId = intval($_POST['itemId']);
		$module = new OvicCustomTags();
		$response = new stdClass();
		$langId = intval($_POST['langId']);
        $shopId = Context::getContext()->shop->id;
		$item = $module->getGroupByLang($itemId, $langId, $shopId);
		if($item) $response->name = $item['name'];		
		else $response->name = '';
		die(Tools::jsonEncode($response));
	}
	
	public static function loadTagsByGroup(){
		$groupId = intval($_POST['groupId']);
		$response = new stdClass();
		$response->content = '';
		$module = new OvicCustomTags();
		if($groupId){
			$response->content = $module->getTagsByGroup($groupId);
			if($response->content) $response->status = 1;
			else{
				$response->status = 0;
				$response->msg = "No Tags";
			}			
		}else{
			$response->status = 0;
			$response->msg = "Group not found!";
		}
		die(Tools::jsonEncode($response));
	}
	public static function saveTag(){
		$module = new  OvicCustomTags();
       	$module->clearCache();
		$languages = $module->getAllLanguages();
		$shopId = Context::getContext()->shop->id;
		$db = DB::getInstance();
		$itemId = intval($_POST['tagId']);		
		$titles = Tools::getValue('titles', array());
		$links = Tools::getValue('links', array());		
		$groupId = intval($_POST['groupId']);
		$response = new stdClass();		
		if($itemId == 0){
			$maxOrdering = (int) $db->getValue("Select MAX(ordering) From "._DB_PREFIX_."ovic_custom_tags_tags Where groupId = ".$groupId);
			if($maxOrdering) $maxOrdering++;
			else $maxOrdering = 1;
			if($db->execute("Insert Into "._DB_PREFIX_."ovic_custom_tags_tags (`groupId`, `status`, `ordering`) Values ('$groupId', '1', '$maxOrdering')")){
				$insertId = $db->Insert_ID();
				if($languages){
		        	$arrInsert = array();
		        	foreach($languages as $index=>$language){
		        		$arrInsert[] = array('tagId'=>$insertId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'title'=>$db->escape($titles[$index]), 'link'=>$db->escape($links[$index]));
		        	}
					if($arrInsert) $db->insert('ovic_custom_tags_tag_lang', $arrInsert);
		        }				
				$response->status = 1;
				$response->msg = $module->ajaxTranslate("Add new Tag Success!");
			}else{
				$response->status = 0;
				$response->msg = $module->ajaxTranslate("Add new Tag Not Success!");
			}
		}else{
			$arrInsert = array();                
			foreach($languages as $index=>$language){
				$check = $db->getValue("Select tagId From "._DB_PREFIX_."ovic_custom_tags_tag_lang Where tagId = ".$itemId." AND id_lang = ".$language->id." AND id_shop = ".$shopId);
	    		if($check){
	        		$db->execute("Update "._DB_PREFIX_."ovic_custom_tags_tag_lang Set `title`='".$db->escape($titles[$index])."', `link`='".$links[$index]."' Where `tagId` = '".$itemId."' AND `id_lang` = '".$language->id."' AND `id_shop` = '".$shopId."'");	
	        	}else{
	        		$arrInsert[] = array('tagId'=>$itemId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'title'=>$db->escape($names[$index]), 'link'=>$db->escape($links[$index]));
	        	}
	    	}
			if($arrInsert) $db->insert('ovic_custom_tags_tag_lang', $arrInsert);	
			$response->status = 1;
			$response->msg = $module->ajaxTranslate("Update Tag Success!");
		}
		die(Tools::jsonEncode($response));	
	}
	public static function updateTagOrdering(){
		$ids = $_POST['ids'];     
		$response = new stdClass();   
        if($ids){
            $strIds = implode(', ', $ids);            
            $minOrder = DB::getInstance()->getValue("Select Min(ordering) From "._DB_PREFIX_."ovic_custom_tags_tags Where id IN ($strIds)");            
            foreach($ids as $i=>$id){
                DB::getInstance()->query("Update "._DB_PREFIX_."ovic_custom_tags_tags Set ordering=".($minOrder + $i)." Where id = ".$id);                
            }
            $module = new  OvicCustomTags();
       		$module->clearCache();
            $response->status = 1;
            $response->msg='Update ordering success!';
        }else{
        	$response->status = 0;
            $response->msg='Update ordering not success!';
        }
        die(Tools::jsonEncode($response));
	}
	
}
