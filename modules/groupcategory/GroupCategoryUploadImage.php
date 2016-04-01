<?php
/**
 * upload file image
 * @author SONNC
 */
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
require_once(dirname(__FILE__).'/groupcategory.php');
$module = new GroupCategory();
$fileType = strtolower(pathinfo($_FILES["uploadimage"]["name"], PATHINFO_EXTENSION));
$fileName = strtolower(Tools::encrypt(time().$_FILES["uploadimage"]["name"]).'.'.$fileType);
$fileTemp = $module->pathImage.'temps/'.$fileName;

//$tempPath = _PS_MODULE_DIR_.'groupcategory/images/temps/';
//$fileName = $_FILES["uploadimage"]["name"];
//$pathFile = $tempPath.$fileName;
if(($_FILES["uploadimage"]["size"] > 1000000)){
	echo "File size is greater than 1MB";
}else{
	if (@move_uploaded_file($_FILES['uploadimage']['tmp_name'], $fileTemp)) {		
	  	echo $fileName; 
	}else {
		echo "File upload failed.";
	}	
}
?>