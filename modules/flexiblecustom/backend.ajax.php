<?php
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
require_once(dirname(__FILE__).'/flexiblecustom.php');
$module = new FlexibleCustom();
if (!Tools::isSubmit('secure_key') || Tools::getValue('secure_key') != $module->secure_key){
	$response = new stdClass();
	$response->status = 0;
	$response->msg = "you need to login with the admin account.";
	die(Tools::jsonEncode($response));
}
$action = Tools::getValue('action');
OvicFlexibleCustomAjax::$action();
class OvicFlexibleCustomAjax{     
    static function clearCache(){
        $module = new  FlexibleCustom();
        $module->clearCache();
        $response = 'Clear cache success!';
        die(Tools::jsonEncode($response)); 
    }
	static function saveModule(){		
		$module = new  FlexibleCustom();        
        $module->clearCache();
		$languages = $module->getAllLanguages();
		$shopId = Context::getContext()->shop->id;
		$itemId = Tools::getValue('moduleId');
		$position = Tools::getValue('position', 0);
		$position_name = Hook::getNameById($position);		
		$titles = Tools::getValue('titles', array());
		$params = new stdClass();
		
		$params->moduleLayout = Tools::getValue('moduleLayout');
		$params->displayOnly = '';//Tools::getValue('displayOnly');
		$params->orderValue = '';//Tools::getValue('orderValue');
		$params->orderType = '';//Tools::getValue('orderType');
       $response = new stdClass();
	   $db = DB::getInstance();
		if($itemId == 0){
       		$maxOrdering = $db->getValue("Select MAX(ordering) From "._DB_PREFIX_."flexiblecustom_modules Where `position` = ".$position);
			if($maxOrdering >0) $maxOrdering++;
			else $maxOrdering = 1;            
            if($db->execute("Insert Into "._DB_PREFIX_."flexiblecustom_modules (`id_shop`, `position`, `position_name`, `status`, `params`, `ordering`) Values ('".$shopId."', '$position', '$position_name', 1, '".json_encode($params)."', '$maxOrdering')")){
                $insertId = $db->Insert_ID();                
                if($languages){
                	$insertSql = array();
                	foreach($languages as $i=>$language){
                		$images = Tools::getValue('bannerNames'.$language->id, array());
						$links = Tools::getValue('bannerLink'.$language->id, array());
						$alts = Tools::getValue('bannerAlt'.$language->id, array());
						$banners = array();
						if($images){
							foreach ($images as $j => $image) {
								if($image && file_exists($module->tempPath.$image)){
									if(copy($module->tempPath.$image, $module->bannerPath.$image)){
										$banners[] = array('image'=>$image, 'link'=>$links[$j], 'alt'=>$alts[$j]);
										unlink($module->tempPath.$image);			
									}									
								}
							}							
							$insertSql[] = array('module_id'=>$insertId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'module_title'=>$db->escape($titles[$i]), 'banners'=>json_encode($banners)) ;
						}else{
							$insertSql[] = array('module_id'=>$insertId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'module_title'=>$db->escape($titles[$i]), 'banners'=>'') ;
						}						
                	}					
					if($insertSql) $db->insert('flexiblecustom_modules_lang', $insertSql);
                }                
                $response->status = 1;
				$response->msg = 'Add new module success!';      
            }else{
            	$response->status = 0;
				$response->msg = 'Error: Add new module failed!';        
            }
       }else{
            $item = $db->getRow("Select * From "._DB_PREFIX_."flexiblecustom_modules Where id = ".$itemId);
            if($item){
                $db->execute("Update "._DB_PREFIX_."flexiblecustom_modules Set `params`='".json_encode($params)."', `id_shop`='".$shopId."', `position` = '$position', `position_name` = '$position_name' Where id = ".$itemId);
				if($languages){
                	$insertSql = array();
                	foreach($languages as $i=>$language){
                		$check = DB::getInstance()->getValue("Select module_id From "._DB_PREFIX_."flexiblecustom_modules_lang Where module_id = ".$itemId." AND id_lang = ".$language->id." AND id_shop = ".$shopId);						
                		$images = Tools::getValue('bannerNames'.$language->id, array());
						$links = Tools::getValue('bannerLink'.$language->id, array());
						$alts = Tools::getValue('bannerAlt'.$language->id, array());
						$banners = array();
						if($images){
							foreach ($images as $j => $image){
								if($image && file_exists($module->tempPath.$image)){
									if(copy($module->tempPath.$image, $module->bannerPath.$image)){										
										unlink($module->tempPath.$image);			
									}									
								}
								$banners[] = array('image'=>$image, 'link'=>$links[$j], 'alt'=>$alts[$j]);
							}
							if($check){
								$db->execute("Update "._DB_PREFIX_."flexiblecustom_modules_lang Set `module_title`='".$db->escape($titles[$i])."', `banners`='".json_encode($banners)."' Where `module_id` = '".$itemId."' AND `id_lang` = '".$language->id."' AND `id_shop` = '".$shopId."'");	
							}else{
								$insertSql[] = array('module_id'=>$itemId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'module_title'=>$db->escape($titles[$i]), 'banners'=>json_encode($banners)) ;
							}
						}else{
							if($check){
								$db->execute("Update "._DB_PREFIX_."flexiblecustom_modules_lang Set `module_title`='".$db->escape($titles[$i])."', `banners`='' Where `module_id` = '".$itemId."' AND `id_lang` = '".$language->id."' AND `id_shop` = '".$shopId."'");	
							}else{
								$insertSql[] = array('module_id'=>$itemId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'module_title'=>$db->escape($titles[$i]), 'banners'=>'') ;
							}
						}						
                	}
					if($insertSql) $db->insert('flexiblecustom_modules_lang', $insertSql);
                }
				$response->status = 1;
				$response->msg = 'Update module success!';				   
            }else{
				$response->status = 1;
				$response->msg = 'Error: Not isset Group.';
            }
       }
       die(Tools::jsonEncode($response));               
	}
	
	static function loadModuleItem(){
		$itemId = intval($_POST['itemId']);
		$module = new FlexibleCustom();
		$response = new stdClass();
		if($itemId){
			$html = $module->ovicModuleRenderForm($itemId);
			$response->config = $html['config'];
			$response->banners = $html['banners'];
			$response->status = 1;			
		}else{
			$response->status = 0;
			$response->msg = 'Module not found!';
		}
		die(Tools::jsonEncode($response));
	}
	static function loadGroup(){
		$itemId = intval($_POST['itemId']);
		$module = new FlexibleCustom();
		$response = new stdClass();
		if($itemId){
			$response->form = $module->ovicGroupRenderForm($itemId);
			$response->status = 1;			
		}else{
			$response->status = 0;
			$response->msg = 'Module not found!';
		}
		die(Tools::jsonEncode($response));
	}
	
	static function loadGroupByModule(){
		$moduleId = intval($_POST['moduleId']);
		$module = new FlexibleCustom();
		$response = new stdClass();
		if($moduleId){
			$response->content = $module->getAllGroup($moduleId);
			$response->status = 1;
		}else{
			$response->status = 0;
			$response->msg = 'Module not found!';
		}
		die(Tools::jsonEncode($response));
	}
	static function saveGroup(){
		$module = new FlexibleCustom();
		$languages = $module->getAllLanguages();		
		$shopId = Context::getContext()->shop->id;
        $itemId = intval($_POST['groupId']);
        $titles = Tools::getValue('titles', array());
		$db = DB::getInstance();
        $icon = $db->escape(Tools::getValue('icon', ''));
		$iconActive = $db->escape(Tools::getValue('iconActive', ''));
		$type = $db->escape(Tools::getValue('type', ''));
		$categoryId = intval($_POST['categoryId']);
		$productCount = 4;// intval($_POST['productCount']);
		$maxItem = intval($_POST['maxItem']);
		$moduleId = intval($_POST['moduleId']);
		$params = new stdClass();	
		$params->displayOnly = Tools::getValue('displayOnly');
		$params->orderValue = Tools::getValue('orderValue');
		$params->orderType = Tools::getValue('orderType');		
		$response = new stdClass();
        if($itemId == 0){
        	$maxOrdering = $db->getValue("Select MAX(ordering) From "._DB_PREFIX_."flexiblecustom_module_groups Where `module_id` = ".$moduleId);
			if($maxOrdering >0) $maxOrdering++;
			else $maxOrdering = 1;
            if($db->execute("Insert Into "._DB_PREFIX_."flexiblecustom_module_groups (`module_id`, `categoryId`, `productCount`, `maxItem`, `type`, `ordering`, `status`, `params`) Values ('$moduleId', '$categoryId', '$productCount', '$maxItem', '$type', '$maxOrdering', '1', '".json_encode($params)."')")){
                $insertId = $db->Insert_ID();
				if($icon && file_exists($module->tempPath.$icon)){
					$path_info = pathinfo($icon);
                    $ext = $path_info['extension'];
                    $fileName = $insertId.'-icon.'.$ext;
                    if(copy($module->tempPath.$icon, $module->iconPath.$fileName)){
                    	unlink($module->tempPath.$icon);
                    	DB::getInstance()->execute("Update "._DB_PREFIX_."flexiblecustom_module_groups Set icon = '$fileName' Where `id` = ".$insertId);	
                    }                    
                }
                if($iconActive && file_exists($module->tempPath.$iconActive)){
					$path_info = pathinfo($iconActive);
                    $ext = $path_info['extension'];
                    $fileName = $insertId.'-icon.'.$ext;
                    if(copy($module->tempPath.$iconActive, $module->iconPath.$fileName)){
                    	unlink($module->tempPath.$iconActive);
                    	DB::getInstance()->execute("Update "._DB_PREFIX_."flexiblecustom_module_groups Set iconActive = '$fileName' Where `id` = ".$insertId);	
                    }                    
                }
				if($languages){
                	$insertSql = array();
                	foreach($languages as $i=>$language){
                		$insertSql[] = array('group_id'=>$insertId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'title'=>$db->escape($titles[$i])) ;						
                	}					
					if($insertSql) $db->insert('flexiblecustom_module_group_lang', $insertSql);                
                }
				$response->status = 1;
				$response->msg = 'Add new group success!';                    
            }else{
                $response->status = 1;
				$response->msg = 'Add new group not success!';
            }
        }else{
            $item = $db->getRow("Select * From "._DB_PREFIX_."flexiblecustom_module_groups Where id = ".$itemId);			
            if($item){
            	$db->execute("Update "._DB_PREFIX_."flexiblecustom_module_groups Set `categoryId` = ".$categoryId.", `type`='$type', `productCount`='$productCount', `maxItem`='$maxItem', `params`='".json_encode($params)."' Where id = ".$itemId);				
				if($icon && file_exists($module->tempPath.$icon)){
					$path_info = pathinfo($icon);
	                $ext = $path_info['extension'];
	                $fileName = $itemId.'-icon.'.$ext;
	                if(copy($module->tempPath.$icon, $module->iconPath.$fileName)){
	                	unlink($module->tempPath.$icon);
	                	$db->execute("Update "._DB_PREFIX_."flexiblecustom_module_groups Set `icon`='$fileName' Where id = ".$itemId);	
	                }               
                }
				if($iconActive && file_exists($module->tempPath.$iconActive)){
					$path_info = pathinfo($iconActive);
	                $ext = $path_info['extension'];
	                $fileName = $itemId.'-icon-active.'.$ext;
	                if(copy($module->tempPath.$iconActive, $module->iconPath.$fileName)){
	                	unlink($module->tempPath.$iconActive);
	                	$db->execute("Update "._DB_PREFIX_."flexiblecustom_module_groups Set `iconActive`='$fileName' Where id = ".$itemId);	
	                }               
                }
				
				if($languages){
                	$insertSql = array();
                	foreach($languages as $i=>$language){
                		$check = DB::getInstance()->getValue("Select group_id From "._DB_PREFIX_."flexiblecustom_module_group_lang Where `group_id` = '$itemId' AND `id_lang` = '".$language->id."' AND `id_shop` = '".$shopId."'");
						if($check){
							DB::getInstance()->execute("Update "._DB_PREFIX_."flexiblecustom_module_group_lang Set `title` = '".$db->escape($titles[$i])."' Where `group_id` = '$itemId' AND `id_lang` = '".$language->id."' AND `id_shop` = '".$shopId."'");
						}else{
							$insertSql[] = array('group_id'=>$itemId, 'id_lang'=>$language->id, 'id_shop'=>$shopId, 'title'=>$db->escape($titles[$i])) ;	
						}
                	}					
					if($insertSql) $db->insert('flexiblecustom_module_group_lang', $insertSql);                
                }
                $response->status = 1;
				$response->msg = 'Update group success!';
            }else{
                $response->status = 0;
				$response->msg = 'Group not found!';
            }
        }
        $module->clearCache();
		die(Tools::jsonEncode($response));
	}
	static function updateGroupOrdering(){
        $module = new FlexibleCustom();
        $module->clearCache();
        $ids = $_POST['ids'];        
        if($ids){
            $strIds = implode(', ', $ids);            
            $minOrder = DB::getInstance()->getValue("Select Min(ordering) From "._DB_PREFIX_."flexiblecustom_module_groups Where id IN ($strIds)");            
            foreach($ids as $i=>$id){
                DB::getInstance()->query("Update "._DB_PREFIX_."flexiblecustom_module_groups Set ordering=".($minOrder + $i)." Where id = ".$id);                
            }
        }
        die(Tools::jsonEncode('Update ordering success!'));
    }
	
	static function loadProductByGroup(){
		$flexModule = new FlexibleCustom();
		$langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;
		
        $link = Context::getContext()->link;
        $groupId = Tools::getValue('groupId');
        $moduleId = Tools::getValue('moduleId');
        $group = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."flexiblecustom_module_groups Where id = ".$groupId);
        $module = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."flexiblecustom_modules Where id = ".$moduleId);        
        $params = json_decode($group['params']);
        
        $arrSubCategory = $flexModule->getCategoryIds($group['categoryId']);
        $arrSubCategory[] = $group['categoryId'];
        $response = new stdClass();
        $response->content = '';
        //$response->pagination = '';
        //$pageSize = $moduleCategoryItem['productCount'];
        //$page = intval($_POST['page']);
        //$offset=($page - 1) * $pageSize;    
        if($group['type'] == 'auto'){
            if($params->orderValue == 'sales'){
                //$total = Functions::getProductsOrderSales($langId, $arrSubCategory, $params, $moduleCategoryItem['productCount'], true, false, true);
                $products =  $flexModule->getProductsOrderSales($langId, $arrSubCategory, $params, $group['maxItem'], true, false, false, 0);                
            }elseif($params->orderValue == 'price'){
                //$total = Functions::getProductsOrderPrice($langId, $arrSubCategory, $params, $moduleCategoryItem['productCount'], true, false, true);
                $products = $flexModule->getProductsOrderPrice($langId, $arrSubCategory, $params, $group['maxItem'], true, false, false, 0);
            }elseif($params->orderValue == 'discount'){
               //$total = Functions::getProductsOrderSpecial($langId, $arrSubCategory, $params, $moduleCategoryItem['productCount'], true, false, true);
                $products = $flexModule->getProductsOrderSpecial($langId, $arrSubCategory, $params, $group['maxItem'], true, false, false, 0);
            }elseif($params->orderValue == 'add'){
               //$total = Functions::getProductsOrderAddDate($langId, $arrSubCategory, $params, $moduleCategoryItem['productCount'], true, false, true);
                $products = $flexModule->getProductsOrderAddDate($langId, $arrSubCategory, $params, $group['maxItem'], true, false, false, 0);
            }elseif($params->orderValue == 'rand'){
                //$total = $moduleCategoryItem['productCount'];
                $products = $flexModule->getProductsOrderRand($langId, $arrSubCategory, $params, $group['maxItem'], true, false);
            }            
            if($products){
                foreach($products as $i=> $product){
                    $imagePath = $link->getImageLink($product['link_rewrite'], $product['id_image'], 'cart_default');
                    $response->content .= '<tr id="ptr_'.$product['id_product'].'">
                                    <td>'.$product['id_product'].'</td>
                                    <td class="center"><img src="'.$imagePath.'" width="60" /></td>
                                    <td>'.$product['name'].'</td>
                                    <td>'.$product['reference'].'</td>
                                    <td>'.$flexModule->getCategoryNameById($product['id_category_default'], $langId, $shopId).'</td>                                    
                                    <td class="center">'.$product['price'].'</td>
                                    <td class="center">'.$product['quantity'].'</td>
                                    <td class="center">'.($i + 1).'</td>                              
                                    <td></td>
                                </tr>';
                }
            }
        }else{
            //$total = DB::getInstance()->getValue("Select COUNT(*) From "._DB_PREFIX_."flexiblecustom_module_group_products Where module_id = ".$moduleId." AND group_id = ".$groupId);
            $items = DB::getInstance()->executeS("Select product_id, ordering From "._DB_PREFIX_."flexiblecustom_module_group_products Where module_id = ".$moduleId." AND group_id = ".$groupId." Order By ordering");
            if($items){                
                foreach($items as $item){                    
                    $product = $flexModule->getProductById($langId, $item['product_id'], true, false);// new ProductCore($productId, false, $langId, $shopId);
                    
                                               
                        $imagePath = $link->getImageLink($product['link_rewrite'], $product['id_image'], 'cart_default');                        
                        $response->content .= '<tr id="ptr_'.$product['id_product'].'">
                                    <td>'.$product['id_product'].'</td>
                                    <td class="center"><img src="'.$imagePath.'" width="60" /></td>
                                    <td>'.$product['name'].'</td>
                                    <td>'.$product['reference'].'</td>
                                    <td>'.$flexModule->getCategoryNameById($product['id_category_default'], $langId, $shopId).'</td>                                    
                                    <td class="center">'.$product['price'].'</td>
                                    <td class="center">'.$product['quantity'].'</td>
                                    <td class="pointer dragHandle center"><div class="dragGroup"><div class="positions">'.$item['ordering'].'</div></div></td>
                                    <td><a href="javascript:void(0)" item-id="'.$product['id_product'].'" class="link-delete-product"><i class="icon-trash" ></i></a></td>
                                </tr>';                                
                                        
                                         
                }    
            }
        }       
        die(Tools::jsonEncode($response));
    }	
	static function loadListProducts(){
		$flexModule = new FlexibleCustom();
        $link = Context::getContext()->link;
        $langId = Context::getContext()->language->id;
        $shopId = Context::getContext()->shop->id;
        
        $pageSize = 5;
        $page = intval($_POST['page']);
        $groupId = intval($_POST['groupId']);
        $moduleId = intval($_POST['moduleId']);
        $keyword = Tools::getValue('keyword', '');
        $rows = DB::getInstance()->executeS("Select product_id From "._DB_PREFIX_."flexiblecustom_module_group_products Where module_id = ".$moduleId." AND `group_id` = ".$groupId);
        $arrProductId = array();
        $arrProductId[] = '0';
        if($rows){
            foreach($rows as $row) $arrProductId[] = $row['product_id'];            
        }
        $productIds = implode(', ', $arrProductId);
        
        $group = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."flexiblecustom_module_groups Where id = ".$groupId);
        $arrSubCategory = $flexModule->getCategoryIds($group['categoryId']);
        $arrSubCategory[] = $group['categoryId'];
        $offset=($page - 1) * $pageSize;
        $total = $flexModule->getProductList($langId, $arrSubCategory, $productIds, $keyword, true);
		$response = new stdClass();
        $response->pagination = '';
        $response->list = '';
        if($total >0){            
            $response->pagination = $flexModule->paginationAjaxEx($total, $pageSize, $page, 6, 'loadListProducts');
            $items = $flexModule->getProductList($langId, $arrSubCategory, $productIds, $keyword, false, $offset, $pageSize);
            if($items){
                if($items){
                    foreach($items as $item){
                        $imagePath = $link->getImageLink($item['link_rewrite'], $item['id_image'], 'cart_default');
                        $response->list .= '<tr id="pListTr_'.$item['id_product'].'">
                                                <td>'.$item['id_product'].'</td>
                                                <td class="center"><img src="'.$imagePath.'" width="50" /></td>
                                                <td>'.$item['name'].'</td>
                                                <td>'.$item['reference'].'</td>
                                                <td class="center">'.$item['price'].'</td>
                                                <td class="center">'.$item['quantity_all_versions'].'</td>
                                                <td class="center"><div><a href="javascript:void(0)" item-id="'.$item['id_product'].'" item-name="'.$item['name'].'" class="link-add-manual-product"><i class="icon-plus"></i></a></div></td>
                                            </tr>';
                    }
                }
            }   
        }
        die(Tools::jsonEncode($response));
        
    }
	static function addManualProductItem(){
        $module = new FlexibleCustom();
        $module->clearCache();
        $groupId =  intval($_POST['groupId']);
        $itemId = intval($_POST['itemId']);
        $moduleId = intval($_POST['moduleId']);
        $check = DB::getInstance()->getRow("Select product_id From "._DB_PREFIX_."flexiblecustom_module_group_products Where module_id = ".$moduleId." AND `group_id` = ".$groupId." AND product_id = ".$itemId);
        $response = new stdClass();
        if($check){
            $response->status = 1;
            $response->msg = 'Add product success!';            
        }else{
            $maxOrdering = (int)DB::getInstance()->getValue("Select MAX(ordering) From "._DB_PREFIX_."flexiblecustom_module_group_products Where module_id = ".$moduleId." AND group_id = ".$groupId);
            if(DB::getInstance()->execute("Insert Into "._DB_PREFIX_."flexiblecustom_module_group_products (`module_id`, `group_id`, `product_id`, `ordering`) Values ('".$moduleId."', '".$groupId."', '".$itemId."', '".($maxOrdering + 1)."')")){
                $response->status = 1;
                $response->msg = 'Add product success!';    
            }else{
                $response->status = 0;
                $response->msg = 'Add product not success!';
            }
        }
        die(Tools::jsonEncode($response));
    }
    static function deleteManualProductItem(){
        $module = new FlexibleCustom();
        $module->clearCache();
        $itemId = intval($_POST['itemId']);
        $groupId = intval($_POST['groupId']);
        $moduleId = intval($_POST['moduleId']);
        if(DB::getInstance(_PS_USE_SQL_SLAVE_)->execute("Delete From "._DB_PREFIX_."flexiblecustom_module_group_products Where `module_id` = ".$moduleId." AND `group_id` = ".$groupId." AND `product_id` = ".$itemId)){
            die(Tools::jsonEncode('ok'));
        }else{
            die(Tools::jsonEncode('Delete manual product not success!'));
        }        
    }
    static function deleteGroup(){
    	$module = new FlexibleCustom();
		$response = new stdClass();
        $itemId = intval($_POST['itemId']);      
		$item = DB::getInstance()->getRow("Select * From "._DB_PREFIX_."flexiblecustom_module_groups Where id = $itemId");
		if($item){
			if(DB::getInstance()->execute("Delete From "._DB_PREFIX_."flexiblecustom_module_groups Where id = ".$itemId)){
				DB::getInstance()->escape("Delete From "._DB_PREFIX_."flexiblecustom_module_group_lang Where group_id = $itemId");
				if($item['icon'] && file_exists($module->iconPath.$item['icon'])) unlink($module->iconPath.$item['icon']);
				DB::getInstance()->escape("Delete From "._DB_PREFIX_."flexiblecustom_module_group_products Where group_id = $itemId");
				$response->status = 1;
				$response->msg = "Delete group success!";
	        }else{
	            $response->status = 0;
				$response->msg = "Delete group not success!";
	        }	
		}else{
			$response->status = 0;
			$response->msg = "Group not found!";
		}  
        $module->clearCache();
        die(Tools::jsonEncode($response));
    }
	static function updateProductOrdering(){
		$groupId = intval($_POST['groupId']);
		$moduleId = intval($_POST['moduleId']);
        $ids = $_POST['ids'];        
        if($ids){
            $strIds = implode(', ', $ids);            
            $minOrder = DB::getInstance()->getValue("Select Min(ordering) From "._DB_PREFIX_."flexiblecustom_module_group_products Where module_id = $moduleId AND group_id = '$groupId' AND product_id IN ($strIds)");            
            foreach($ids as $i=>$id){
                DB::getInstance()->query("Update "._DB_PREFIX_."flexiblecustom_module_group_products Set ordering=".($minOrder + $i)." Where module_id = $moduleId AND group_id = '$groupId' AND product_id = ".$id);                
            }
        }
        $module = new FlexibleCustom();
        $module->clearCache();
        die(Tools::jsonEncode('Update ordering success!'));
    }
	static function deleteModule(){
		$module = new FlexibleCustom();
        $itemId = intval($_POST['itemId']);  
		$check = (int)DB::getInstance()->getValue("Select count(*) From "._DB_PREFIX_."flexiblecustom_module_groups Where module_id = $itemId");
		$response = new stdClass();
		if($check >0){
			$response->status = 0;
			$response->msg = 'Error: The module has groups. You need to delete the groups in module.';
			
		}else{			
			if(DB::getInstance()->execute("Delete From "._DB_PREFIX_."flexiblecustom_modules Where id = ".$itemId)){
				$itemLangs = DB::getInstance()->executeS("Select * From "._DB_PREFIX_."flexiblecustom_modules_lang Where module_id = $itemId");
	            if($itemLangs){
	            	foreach($itemLangs as $itemLang){
	            		if($itemLang['banners']){
	            			$banners = json_decode($itemLang['banners']);
	            			if($banners){
	            				foreach($banners as $banner){
	            					if($banner->image && file_exists($module->bannerPath.$banner->image)) unlink($module->bannerPath.$banner->image);
	            				}
	            			}
	            		}
	            	}
	            }
	            DB::getInstance()->execute("Delete From "._DB_PREFIX_."flexiblecustom_modules_lang Where module_id = ".$itemId);
				$response->status = 1;
				$response->msg = 'Delete module success!';				
	        }else{
	            $response->status = 0;
				$response->msg = 'Delete module not success!';    
	        }
		}
        $module->clearCache();
		die(Tools::jsonEncode($response));
    }
    public static function changeModuleStatus(){
		$module = new FlexibleCustom();
		$itemId = intval($_POST['itemId']);
		$value = intval($_POST['value']);		
		$response = new stdClass();
		if($value == '1'){
			DB::getInstance()->execute("Update "._DB_PREFIX_."flexiblecustom_modules Set `status` = 0 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}else{
			DB::getInstance()->execute("Update "._DB_PREFIX_."flexiblecustom_modules Set `status` = 1 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}		
        
        $module->clearCache();
		die(Tools::jsonEncode($response));
	}
    public static function changeGroupStatus(){
		$module = new FlexibleCustom();
		$itemId = intval($_POST['itemId']);
		$value = intval($_POST['value']);		
		$response = new stdClass();
		if($value == '1'){
			DB::getInstance()->execute("Update "._DB_PREFIX_."flexiblecustom_module_groups Set `status` = 0 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}else{
			DB::getInstance()->execute("Update "._DB_PREFIX_."flexiblecustom_module_groups Set `status` = 1 Where id = ".$itemId);
			$response->status = 1;
			$response->msg = 'Update status success';
		}		
		
        $module->clearCache();
        die(Tools::jsonEncode($response));
        
	}
	static function updateModuleOrdering(){
        $module = new FlexibleCustom();
        $module->clearCache();
        $ids = $_POST['ids'];        
        if($ids){
            $strIds = implode(', ', $ids);            
            $minOrder = DB::getInstance()->getValue("Select Min(ordering) From "._DB_PREFIX_."flexiblecustom_modules Where id IN ($strIds)");            
            foreach($ids as $i=>$id){
                DB::getInstance()->query("Update "._DB_PREFIX_."flexiblecustom_modules Set ordering=".($minOrder + $i)." Where id = ".$id);                
            }
        }
        die(Tools::jsonEncode('Update ordering success!'));
        
    }
}
