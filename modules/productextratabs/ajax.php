<?php
require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/productextratabs.php');
$module =  new ProductExtraTabs();
$action = Tools::getValue('action');
$context = Context::getContext();
if (strcmp($action,'getproducts')==0){
    $id_category = (int)Tools::getValue('id_category');
    if($id_category){
        $products = $module->getProductsByCategory($id_category);
        die(Tools::jsonEncode($products));
    }else
        die(Tools::jsonEncode(false)) ;
}