<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_2_0($object)
{		
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
		return true;
}
