<?php

class Gallery_model extends Model {

	public $dir = "photos";

	 function htmlGallery($type,$id){
		$nums = array();
		for($i=1;$i<=5;$i++){
			if(file_exists("../img/{$this->dir}/".$type."a-".$i."-".$id.".jpg")){
				$nums[] = $i;
			}
		}
		if(empty($nums)){
			return "";
		}
		$html = '<h3>Fotogalerie:</h3><div id="gallery">';
		if(count($nums)>1){
			$html .= '<div id="mini">';
			foreach($nums as $i){
				$html .= '<img onMouseOver="getElementById(\'gallery_img\').src=\''.BASE."/img/{$this->dir}/".$type.'a-'.$i.'-'.$id.'.jpg\'" src="'.BASE.'/img/cerpadla/'.$type.'b-'.$i.'-'.$id.'.jpg" height="90" alt="" />';
			}
			$html .= '</div>';
		}
		$html .= '<img id="gallery_img" src="'.BASE."/img/{$this->dir}/".$type.'a-'.$nums[0].'-'.$id.'.jpg" alt="" />';
		$html .= '</div>';

		return $html;
	}
	function save_images ( $tmp_array,$id,$prefix="i" ) {
		foreach($tmp_array as $i=>$path){
			if(empty($path['tmp_name'])) continue;
			$this->load($path['tmp_name']);
			$this->resize_max(500,400);
			$this->save("../img/{$this->dir}/".$prefix."a-".($i+1)."-".$id.".jpg",IMAGETYPE_JPEG,70);
			$this->resize_min(85,80);
			$this->crop(82,80);
			$this->save("../img/{$this->dir}/".$prefix."b-".($i+1)."-".$id.".jpg",IMAGETYPE_JPEG,60);
		}
	}
	
	function delete ($typ,$array){
		foreach($array as $row){
			for($i=1;$i<=5;$i++){
				if(file_exists("../img/{$this->dir}/{$typ}a-{$i}-{$row}.jpg")) unlink("../img/{$this->dir}/{$typ}a-{$i}-{$row}.jpg");
				if(file_exists("../img/{$this->dir}/{$typ}b-{$i}-{$row}.jpg")) unlink("../img/{$this->dir}/{$typ}b-{$i}-{$row}.jpg");
				if(file_exists("../img/{$this->dir}/{$typ}c-{$i}-{$row}.jpg")) unlink("../img/{$this->dir}/{$typ}c-{$i}-{$row}.jpg");
			}
		}
	}



	////// library

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
   function resize_max($width,$height) {
	  	if($width/$height > $this->getWidth()/$this->getHeight())
	  		$this->resizeToHeight($height);
		else
			$this->resizeToWidth($width);
   }
   function resize_min($width,$height) {
	  	if($width/$height < $this->getWidth()/$this->getHeight())
	  		$this->resizeToHeight($height);
		else
			$this->resizeToWidth($width);
   }
   function crop($width,$height) {
	   if($width>$this->getWidth()) $width=$this->getWidth();
	   if($height>$this->getHeight()) $height=$this->getHeight();
	  $new_image = imagecreatetruecolor($width, $height);
      imagecopy($new_image, $this->image, 0, 0, ($this->getWidth()-$width)/2, ($this->getHeight()-$height)/2, $width, $height);
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
