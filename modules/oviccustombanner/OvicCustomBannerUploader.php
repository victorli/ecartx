<?php
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
require_once(dirname(__FILE__).'/oviccustombanner.php');
$module = new OvicCustomBanner();
$fileName = $_FILES["uploadimage"]["name"];
$pathFile = $module->pathTemp.$fileName;
if (!Tools::isSubmit('secure_key') || Tools::getValue('secure_key') != $module->secure_key){		
	echo "you need to login with the admin account.";
}else{
	if(($_FILES["uploadimage"]["size"] > 1000000)){
		echo "File size is greater than 1MB";
	}else{
		if (@move_uploaded_file($_FILES['uploadimage']['tmp_name'], $pathFile)) {		
		  	echo $fileName; 
		}else {
			echo "File upload failed.";
		}	
	}	
}
?>