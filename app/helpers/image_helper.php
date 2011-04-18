<?php
 
class Image {
   
   var $image;
   var $image_type;
 
   function load($filename) {
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
   }
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);         
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename);
      }   
      if( $permissions != null) {
         chmod($filename,$permissions);
      }
   }
   function output($image_type=IMAGETYPE_JPEG) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);         
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }   
   }
   function getWidth() {
      return imagesx($this->image);
   }
   function getHeight() {
      return imagesy($this->image);
   }
   function resize_max($width,$height,$enlarge=true) { // zmenší do rámečku
	   if($enlarge || ($this->getHeight()>$height || $this->getWidth()>$width)){
			if($width/$height > $this->getWidth()/$this->getHeight())
				$this->resizeToHeight($height);
			else
				$this->resizeToWidth($width);
	   }
   }
   function resize_min($width,$height,$enlarge=true) { // vyplní
	   if($enlarge || ($this->getHeight()>$height && $this->getWidth()>$width)){
			if($width/$height < $this->getWidth()/$this->getHeight())
				$this->resizeToHeight($height);
			else
				$this->resizeToWidth($width);
	   }
   }
   function crop($width,$height,$trim = false) {
	   if($trim){
		   if($width>$this->getWidth()) $width=$this->getWidth();
		   if($height>$this->getHeight()) $height=$this->getHeight();
	   }
	  $new_image = imagecreatetruecolor($width, $height);
      imagecopy($new_image, $this->image, 0, 0, ($this->getWidth()-$width)/2, ($this->getHeight()-$height)/2, $width, $height);
	  $color = imagecolorexact($new_image, 255, 255, 255);
	  imagefill($new_image, 0, 0, $color);
	  imagefill($new_image, $width-1, $height-1, $color);
      $this->image = $new_image; 
   }
   function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getHeight() * $ratio;
      $this->resize($width,$height);
   }
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100; 
      $this->resize($width,$height);
   }
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;   
   }      
}
?>