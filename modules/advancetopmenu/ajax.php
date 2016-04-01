<?php
require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/advancetopmenu.php');
$module =  new AdvanceTopMenu();
$action = Tools::getValue('action');
if (strcmp($action,'updateposition')==0){
    $menupos = Tools::getValue('menu_order');
    if ($module->updateMenuPosition($menupos))
        die(Tools::jsonEncode(true));
    else
        die(Tools::jsonEncode(false));
}elseif (strcmp($action,'updateblockposition')==0){
    $block_order = Tools::getValue('block_order');
    if ($module->updateBlockPosition($block_order))
        die(Tools::jsonEncode(true));
    else
        die(Tools::jsonEncode(false));
}