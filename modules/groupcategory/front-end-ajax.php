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
$action = Tools::getValue('action');
GroupCategoryFrontEndAjax::$action();

class GroupCategoryFrontEndAjax{
    //public static $module = null;
    function __construct(){
        //self::$module = new GroupCategory();
    }
    
    public static function loadPageDefault(){
    	
		$langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;
		$groupID = intval($_POST['groupID']);
		$type = $_POST['type'];
		$itemId = intval($_POST['itemId']);
		$page = intval($_POST['page']);
		$itemWidth = intval($_POST['itemWidth']);
		$itemHeight = round($_POST['itemHeight'], 3);
		$css = 'width: '.$itemWidth.'px; height:'.$itemHeight."px;";
		$group = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."groupcategory_groups Where id = ".$groupID);
		$response = '';
        $products = array();
        if($group){
			$module = new GroupCategory();
			$itemConfig = json_decode($group['itemConfig']);
			if(!$itemConfig->countItem || (int)$itemConfig->countItem <=0) $pageSize = 3;
			else $pageSize = $itemConfig->countItem;
			$offset= $page * $pageSize;
			if($itemId == 0){				
				$arrSubCategory = GroupCategoryLibraries::getCategoryIds($group['categoryId']);
	        	$arrSubCategory[] = $group['categoryId'];				 
				if($type == 'saller'){
	        		$products =  GroupCategoryLibraries::getProducts_Sales($langId, $arrSubCategory, null, false, $pageSize, $offset);
				}elseif($type == 'view'){
	        		$products =  GroupCategoryLibraries::getProducts_View($langId, $arrSubCategory, null, false, $pageSize, $offset);
				}elseif($type == 'special'){
	        		$products =  GroupCategoryLibraries::getProducts_Special($langId, $arrSubCategory, null, false, $pageSize, $offset);
				}elseif($type == 'arrival'){
	        		$products =  GroupCategoryLibraries::getProducts_AddDate($langId, $arrSubCategory, null, false, $pageSize, $offset);
				}else{
					if($group['type_default'] == 'saller'){
						$products =  GroupCategoryLibraries::getProducts_Sales($langId, $arrSubCategory, null, false, $pageSize, $offset);
					}elseif($group['type_default'] == 'view'){
						$products =  GroupCategoryLibraries::getProducts_View($langId, $arrSubCategory, null, false, $pageSize, $offset);
					}elseif($group['type_default'] == 'special'){
						$products =  GroupCategoryLibraries::getProducts_Special($langId, $arrSubCategory, null, false, $pageSize, $offset);
					}else{
						$products =  GroupCategoryLibraries::getProducts_AddDate($langId, $arrSubCategory, null, false, $pageSize, $offset);	
					}					
				}				
			}else{
				$item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."groupcategory_items Where id = ".$itemId);
				if($item){
					$arrSubCategory = GroupCategoryLibraries::getCategoryIds($item['categoryId']);
	        		$arrSubCategory[] = $item['categoryId'];
					$products =  GroupCategoryLibraries::getProducts_AddDate($langId, $arrSubCategory, null, false, $pageSize, $offset);					
				}				
			}
            if($products) $response = $module->generationHtml_default($products, $css);
		}
				
        die(Tools::jsonEncode($response));		
    }
}
