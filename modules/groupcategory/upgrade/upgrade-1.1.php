<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_1($object)
{		
		/*
		$langId = Context::getContext()->language->id;		
		$shopId = Context::getContext()->shop->id;
		DB::getInstance()->execute("ALTER TABLE "._DB_PREFIX_."groupcategory_groups ADD `id_shop` INT(6) unsigned NOT NULL AFTER `id`");
		DB::getInstance()->execute("ALTER TABLE "._DB_PREFIX_."groupcategory_styles ADD `id_shop` INT(6) unsigned NOT NULL AFTER `id`");
		DB::getInstance()->execute("Update "._DB_PREFIX_."groupcategory_groups Set `id_shop` = ".$shopId);
		DB::getInstance()->execute("Update "._DB_PREFIX_."groupcategory_styles Set `id_shop` = ".$shopId);
		*/
		return true;
}
