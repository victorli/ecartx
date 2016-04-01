<?php
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
require_once(dirname(__FILE__).'/ovicnewsletter.php');
$module = new OvicNewsletter();
$task = Tools::getValue('task');
if($task == "cancelRegisNewsletter"){		
	$persistent = (int)Tools::getValue('persistent', 0);
    Context::getContext()->cookie->__set('persistent', $persistent);
    die(Tools::jsonEncode("1"));
}
if($task == "regisNewsletter"){
	$result = $module->newsletterRegistration();
	$persistent = (int)Tools::getValue('persistent', 0);
    Context::getContext()->cookie->__set('persistent', $persistent);
	die(Tools::jsonEncode($result));
}