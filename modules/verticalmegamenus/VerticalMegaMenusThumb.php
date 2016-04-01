<?php
class VerticalMegaMenusThumb {
	var $image;
	var $mime;
	var $imageName;
	var $maxHeight=150;
	var $maxWidth=200;
	var $quality=75;
	var $rotMode="CW";
	var $rotColor="FFCC00";
	# sets thumb max width
	function pSetWidth($val){
		$this->maxWidth=$val;
	}
	
	# sets thumb max height
	function pSetHeight($val){
		$this->maxHeight=$val;
	}
	
	# set thumb both width and height
	function pSetSize($width, $height){
		$this->maxWidth=$width;
		$this->maxHeight=$height;
	}
	
	# set output quality
	function pSetQuality($number){
		$this->quality=$number;
	}

	# destroy the resource
	function pDestroy(){
		imagedestroy($this->image);
	}
	
	# rotate the image
	function pRotate($degrees, $mode=NULL, $bgcolor=NULL){
		if($mode!=NULL) $this->rotMode=strtoupper($mode);
		if($bgcolor!=NULL) $this->rotColor=$bgcolor;
		
		$bg=base_convert($this->rotColor, 16, 10);
		$img=$this->image;
		switch($this->rotMode){
			case "CW":
			case 1:
				$rotation=360-$degrees;
				break;
			case "CCW":
			case 2:
				$rotation=$degrees;
				break;
		}
		
		$img=imagerotate($this->image, $rotation, $bg);
		$this->image=$img;
	}
	
	# resize an image
	function pResize($img, $width, $height){
		$img_sizes=getimagesize($img);
		switch($img_sizes[2]){
			case 1:
				$source=imagecreatefromgif($img);
				$this->mime="GIF";
				break;
			case 2:
				$source=imagecreatefromjpeg($img);
				$this->mime="JPG";
				break;
			case 3:
				$source=imagecreatefrompng($img);
				$this->mime="PNG";
				break;
		}
		$thumb=imagecreatetruecolor($width, $height);
		if($this->mime=="PNG"){
			imagealphablending($thumb, false);
			imagesavealpha($thumb,true);
			$transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
			imagefilledrectangle($thumb, 0, 0, $width, $height, $transparent);			
		}
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $width, $height, $img_sizes[0], $img_sizes[1]);		
		$this->image=$thumb;
	}
	
	# create a thumb
	function pCreate($img, $maxwidth=NULL, $maxheight=NULL, $quality=NULL, $resize=true){
		if(file_exists($img)){			
			$this->imageName=basename($img);
			$img_sizes	=	getimagesize($img);			
			if($maxwidth!=NULL){
				$this->maxWidth=$maxwidth;
			}else{
				$this->maxWidth=$img_sizes[0];
			} 
			if($maxheight!=NULL){
				$this->maxHeight=$maxheight;
			}else{
				$this->maxHeight=$img_sizes[1];
			} 
			if($quality!=NULL) $this->quality=$quality;				
			if($resize == true){
				if($img_sizes[0] > $this->maxWidth || $img_sizes[1] > $this->maxHeight){
					$percent_width = $this->maxWidth / $img_sizes[0];
					$percent_height = $this->maxHeight / $img_sizes[1];
					$percent=min($percent_width, $percent_height);
				}else{
					$percent=1;
				}			
				$width=$img_sizes[0]*$percent;
				$height=$img_sizes[1]*$percent;	
			}else{
				$width = $maxwidth;
				$height = $maxheight;
			}							
			$this->pResize($img, $width, $height);
		}else{
			$this->image=imagecreate($this->maxWidth, $this->maxHeight);			
			$line=$this->pDecColors("FF0000");
			$fill=$this->pDecColors("EFE6C2");			
            $fillcolor = imagecolorallocate($this->image, $fill['r'], $fill['b'], $fill['g']);
            $linecolor     = imagecolorallocate($this->image, $line['r'], $line['b'], $line['g']);			
            imagefill($this->image, 0, 0, $fillcolor);
			imagerectangle($this->image, 0, 0, ($this->maxWidth-1), ($this->maxHeight-1), $linecolor);
            imageline($this->image, 0, 0, $this->maxWidth, $this->maxHeight, $linecolor);
            imageline($this->image, 0, $this->maxHeight, $this->maxWidth, 0, $linecolor);
		}
	}
	
	# function taken from "http://ro2.php.net/manual/en/function.mkdir.php"
	function MakeDirectory($dir, $mode = 0777){
	  if (is_dir($dir) || @mkdir($dir,$mode)) return TRUE;
	  if (!$this->MakeDirectory(dirname($dir),$mode)) return FALSE;
	  return @mkdir($dir,$mode);
	}
	
	# output the image
	function pOutput($print=true){
		if($print!=true){
			header('Content-type: application/force-download');
			header('Content-Transfer-Encoding: Binary');
			header('Content-Disposition: attachment; filename="'.$this->imageName.'"');
		}
		switch($this->mime){
			case "JPEG":
			case "JPG":
				header("Content-Type: image/jpeg");
				imagejpeg($this->image, NULL, $this->quality);
				break;
			case "GIF":
				header("Content-Type: image/gif");
				imagegif($this->image);
				break;
			case "PNG":
				header("Content-Type: image/png");
				imagepng($this->image);
				break;
			default :
				header("Content-Type: image/gif");
				imagegif($this->image);
		}
		$this->pDestroy();
	}

	# save image
	function pSave($path){
		if(!file_exists(dirname($path))){
			$this->MakeDirectory(dirname($path));
		}
		switch($this->mime){
			case "JPEG":
			case "JPG":
				imagejpeg($this->image, $path, $this->quality);
				break;
			case "GIF":
				imagegif($this->image, $path);
				break;
			case "PNG":
				imagepng($this->image, $path);
				break;
		}
	}
	
	function pDecColors($color){
		$color	=	str_replace("#", "", $color);
		$colors['r']	=	hexdec(substr($color, 0, 2));
		$colors['b']	=	hexdec(substr($color, 2, 2));
		$colors['g']=	hexdec(substr($color, 4, 2));
		return $colors;
	}
	/**
	 * @name pCreateFull
     * @var $sourceFile: Đường dẫn đến file nguồn (bắt buộc)
     * @var $newPath: Đường dẫn file mới (bắt buộc)
     * @var $newName: Tên file mới (default = '')
     * @var $ext: Đuôi file (default = .jpg)
     * @var $maxWidth: Độ rộng của ảnh (default = 800)
     * @var $ratio: Tỉ lệ chiều cao so với chiều rộng (h = w/ratio) (default = 1.333)
     * @var $quantity: Chất lượng ảnh (default = 90)
     * @var $text: Text chèn vào ảnh (default = "")
     * @var $delete: Có xóa ảnh nguồn sau khi tạo mới hay không (default = 0)
	 */
    public static function pCreateFull($sourceFile, $newPath, $newName = '', $ext='.jpg', $maxWidth = 250, $maxHeight = 250, $quantity = 90, $text = '', $delete=false){
    	if($newName){		
    		$newFile = $newPath.$newName;
    	}else{ 
    		$fileInfo = pathinfo($sourceFile);
    		$newFile = $newPath.$fileInfo['filename'];
    	}
    	$newFile .= $ext;
    	if(file_exists($sourceFile)){
    		//if($maxWidth == null) $maxWidth = 1000;
    		//$maxHeight = floor($maxWidth/$ratio);
    		// create image1
    		$image1 = imagecreatetruecolor($maxWidth, $maxHeight);	
    		imagealphablending($image1, false);
    		imagesavealpha($image1,true);
    		$transparent = imagecolorallocatealpha($image1, 255, 255, 255, 127);
    		imagefilledrectangle($image1, 0, 0, $maxWidth, $maxHeight, $transparent);				
    		$img_sizes	= getimagesize($sourceFile);
    		switch($img_sizes[2]){
    			case 1:
    				$source=imagecreatefromgif($sourceFile);
    				break;
    			case 2:
    				$source=imagecreatefromjpeg($sourceFile);
    				break;
    			case 3:
    				$source=imagecreatefrompng($sourceFile);				
    				break;
    		}			
    		if($img_sizes[0] > $maxWidth || $img_sizes[1] > $maxHeight){
    			$percent_width = $maxWidth / $img_sizes[0];
    			$percent_height = $maxHeight / $img_sizes[1];
    			$percent = min($percent_width, $percent_height);
    		}else{
    			$percent_width = $maxWidth / $img_sizes[0];
    			$percent_height = $maxHeight / $img_sizes[1];
    			$percent=min($percent_width, $percent_height);
    		}
    		
    		$w = round($img_sizes[0]*$percent, 0);
    		$h = round($img_sizes[1]*$percent, 0);	
    		
    		$image2 = imagecreatetruecolor($w, $h);		
    		imagecopyresized($image2, $source, 0, 0, 0, 0, $w, $h, $img_sizes[0],$img_sizes[1]);
    		$posX = intval(($maxWidth/2) - ($w/2));
    		$posY = intval(($maxHeight/2) - ($h/2));		
    		imagecopymerge($image1 , $image2 , $posX, $posY, 0, 0, $w, $h, 100);
    		if($text){
    			$txtcolor = imagecolorallocate( $image1, 120, 120, 120 );
    			imagestring($image1, 5, 6, $maxHeight-20, $image1, $txtcolor);	
    		}		
    		switch(strtolower($ext)){
    			case ".jpeg":
    			case ".jpg":
    				imagejpeg($image1, $newFile, $quantity);
    				break;
    			case ".gif":
    				imagegif($image1, $newFile);
    				break;
    			case ".png":
    				imagepng($image1, $newFile);
    				break;
    		}
    		imagedestroy($source);
    		imagedestroy($image2);
    		imagedestroy($image1);		
    		if($delete) unlink($sourceFile);
    		return $newFile;
    	}else return '';
    }
}
?>