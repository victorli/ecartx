<?php
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
require_once(dirname(__FILE__).'/verticalmegamenus.php');
$tempPath = _PS_MODULE_DIR_.'verticalmegamenus/images/temps/';
$fileName = $_FILES["uploadimage"]["name"];
$pathFile = $tempPath.$fileName;
if(($_FILES["uploadimage"]["size"] > 1000000)){
	echo "File size is greater than 1MB";
}else{
	if (@move_uploaded_file($_FILES['uploadimage']['tmp_name'], $pathFile)) {		
	  	echo $fileName; 
	}else {
		echo "File upload failed.";
	}	
}
?>