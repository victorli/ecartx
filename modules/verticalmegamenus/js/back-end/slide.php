<?php
class MOD_slide extends CMS{
	var $read = false;
	var $temp = '';
	var $elId = '';
	var $elClass = '';
	var $build = true;
	function build($module, $temp='', $elId='', $elClass='', $build=true){
		global $db, $main_smarty, $thetemp, $templates, $json;
		$this->temp = $temp;
		$this->elId = $elId;
		$this->elClass = $elClass;		
		$this->build = $build;	
		$params = $json->decode($module->params);		
		$buildFuction = $params->layout.'Build';
		$cssFile = "templates/$thetemp/$templates/css/modules/".$module->type.".".$params->layout.".css";		
		if(file_exists(mnmpath.$cssFile)){			
			setHeadCSS(my_web_base.'/'.$cssFile);
		}
		return $this->$buildFuction($module, $params);
	}
	function defaultBuild($module, $params){
		global $db, $main_smarty, $thetemp, $templates, $json;
		/**
		 * get silde
		 */
		
		if($this->category && $this->category->option == 'products'){
			$categoryParams = $json->decode($this->category->params);
			if(intval($categoryParams->slideId) >0){
				$items = $db->get_results("Select * From #__slides Where category_id = ".intval($categoryParams->slideId)." Order By ordering");
			}else $items = null;			
		}else{
			$items = $db->get_results("Select * From #__slides Where category_id = ".intval($params->slideCategoryID)." Order By ordering");	
		}		
		if($items){
			if(!$this->temp) $this->temp = "$thetemp/$templates/modules/mod_".$module->type."_".$params->layout."_".$module->id.".tpl";
			$mod['show_title_'.$module->id] = $params->show_title;
			$mod['class_prefix_'.$module->id] = $params->class_prefix;			
			$mod['name_'.$module->id] = $module->name;
			foreach ($items as $i=>$item){
				$thumbs .= '<li style="width: auto"><span>'.$item->title.'</span></li>';
				if($item->link != ""){
					//$slides .= '<li><img width="" height="240" src="'.liveSlide.$item->file.'" alt="'.$item->title.'"><div class="slider-description"><h4><a href="'.$item->link.'">'.$item->title.'</a></h4>'.($item->intro ? "<p>$item->intro</p>":"").'</div></li>';
					$slides .= '<li><img width="" height="240" src="'.liveSlide.$item->file.'" alt="'.$item->title.'"></li>';
				}else{
					//$slides .= '<li><img height="240" src="'.liveSlide.$item->file.'" alt="'.$item->title.'" ><div class="slider-description"><h4>'.$item->title.'</h4>'.($item->intro ? "<p>$item->intro</p>":"").'</div></li>';
					$slides .= '<li><img height="240" src="'.liveSlide.$item->file.'" alt="'.$item->title.'" ></li>';
				}
			}
			if ($this->build == false) return $slides;			
			$mod['thumbs_'.$module->id] = $thumbs;
			$mod['fullSlide_'.$module->id] = $slides;				
			$main_smarty->assign('mod_'.$module->id, $mod);				
			if(!file_exists(mnmpath.'templates/'.$this->temp)){
				$fileContent = file_get_contents(mnmoptions.'modules/temps/'.$module->type.'.'.$params->layout.'.tpl');					
				if((int)$params->width <=0) $params->width = 686;
				if((int)$params->height <=0) $params->height = 240;					
				$fileContent = str_replace('{WIDTH}', $params->width, $fileContent);	
				$fileContent = str_replace('{HEIGHT}', $params->height, $fileContent);		
				$fileContent = str_replace('{$MOD_ID}', $this->elId, $fileContent);		
				$fileContent = str_replace('{$MOD_CLASSNAME}', $this->elClass, $fileContent);	
				$fileContent = str_replace('{MOD}', 'mod_'.$module->id, $fileContent);
				$fileContent = str_replace('{CLASS_PREFIX}', 'class_prefix_'.$module->id, $fileContent);
				$fileContent = str_replace('{FULL_SLIDE}', 'fullSlide_'.$module->id, $fileContent);					
				$fileContent = str_replace('{THUMBS_SLIDE}', 'thumbs_'.$module->id, $fileContent);									
				$fh = fopen(mnmpath."templates/".$this->temp, 'w') or die("không thể tạo file ".$this->temp);
				fwrite($fh, $fileContent);				
				fclose($fh);			
			}
			return true;
		}else return false;
	}	
}
?>