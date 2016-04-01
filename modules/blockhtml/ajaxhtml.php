<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
include_once(dirname(__FILE__).'/blockhtml.php');
$blockhtml = new BlockHtml();
$id_position = (int)Tools::getValue('id_position');
if ($id_position>0)
    echo $blockhtml->AjaxCall($id_position);
