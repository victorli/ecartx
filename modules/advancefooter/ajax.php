<?php
require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/advancefooter.php');
$module =  new AdvanceFooter();
$action = Tools::getValue('action');
$context = Context::getContext();
if (strcmp($action,'getModuleHook')==0){
    $module_name = Tools::getValue('module_name');
    if(strlen($module_name)>0){
        $hooks = $module->getHookOptionByModuleName($module_name);
        echo $hooks;
    }else
        die(Tools::jsonEncode(false)) ;
}elseif(strcmp($action,'updaterowposition')==0){
    $row_order = Tools::getValue('row_order');
    if ($module->updateRowPosition($row_order))
        die(Tools::jsonEncode(true));
    else
        die(Tools::jsonEncode(false));
}elseif(strcmp($action,'updateblockposition')==0){
    $block_order = Tools::getValue('block_order');
    if ($module->updateBlockPosition($block_order))
        die(Tools::jsonEncode(true));
    else
        die(Tools::jsonEncode(false));
}elseif(strcmp($action,'updateitemposition')==0){
   $item_order = Tools::getValue('item_order');
    if ($module->updateItemPosition($item_order))
        die(Tools::jsonEncode(true));
    else
        die(Tools::jsonEncode(false));
}
