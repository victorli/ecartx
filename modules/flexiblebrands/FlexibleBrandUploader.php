<?php
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
require_once(dirname(__FILE__).'/flexiblebrands.php');
$module = new FlexibleBrands();
$fileName = $_FILES["uploadimage"]["name"];
$fileTypes = array('jpg', 'jpeg', 'png', 'gif');
$pathFile = $module->tempPath.$fileName;
$fileType = strtolower(pathinfo($pathFile, PATHINFO_EXTENSION));
$response = new stdClass();
if (!Tools::isSubmit('secure_key') || Tools::getValue('secure_key') != $module->secure_key){
	$response->status = 0;		
	$response->msg =  "you need to login with the admin account.";
}else{	$typeUpload = Tools::getValue('typeUpload', 'banner');
	$langId = Tools::getValue('langId', 1);	
	if($typeUpload == 'banner'){		
		if(in_array($fileType, $fileTypes)){
			if(($_FILES["uploadimage"]["size"] > 2000000)){
				$response->status = 0;	
				$response->msg = "File size is greater than 2MB";	
			}else{
				if (@move_uploaded_file($_FILES['uploadimage']['tmp_name'], $pathFile)) {			
					$response->status = 1;
					$response->livePath = $module->livePath;
					$response->fileName = $fileName;
				}else {	
					$response->msg = "File upload failed.";
					$response->status = 0;	
				}		
			}	
		}else{
			$response->msg = "File format not support (is jpg, png, gif)!";
			$response->status = 0;
		}
			
	}else if($typeUpload == 'icon'){
		if(($_FILES["uploadimage"]["size"] > 1000000)){
			$response->status = 0;	
			$response->msg = "File size is greater than 1MB";	
		}else{
			if (@move_uploaded_file($_FILES['uploadimage']['tmp_name'], $pathFile)) {
				$response->status = 1;
				$response->fileName = $fileName; 
			}else {
				$response->status = 0;		
				$response->msg = "File upload failed.";	
			}		
		}
	}		
}echo json_encode($response);
?>