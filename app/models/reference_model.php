<?php
/**
 * Ref
 *
 * @author Melounek
 */
class Reference_model extends Base_model {
	protected $table = "reference";
    public function __construct(){

	}
	public function get_all(){
		$sql = "select *,". $this->table .".id as id from " . $this->table . $this->left_join($this->table) . " order by order_reference";
		$data = $this->db->query($sql)->result_array();
		return $data;
	}
	public function get_one($id=NULL){
		$this->load(NULL,$id);
		$sql = "select *,". $this->table .".id as id from ". $this->table . $this->left_join($this->table) ."
				where ". $this->table .".id='". $this->id ."'";
		if(($item = $this->db->query($sql)->row_array())){
			$next = $this->db->query("select id from reference where order_reference > '".$item['order_reference']."'
				order by order_reference asc limit 1")->row_array();
			$before = $this->db->query("select id from reference where order_reference < '".$item['order_reference']."'
				order by order_reference desc limit 1")->row_array();
			$item['next'] = (isset($next['id']) ? $next['id'] : NULL) ;
			$item['before'] = (isset($before['id']) ? $before['id'] : NULL) ;
			return $item;
		}else{
			return NULL;
		}
	}

	public function save_reference($data=NULL,$id=NULL){
		$this->load($data,$id);
		$this->uri->save_sef("home/reference/".$this->id,$this->data['title_reference']);
		if(($return = $this->save())){
			$this->upload_img();
		}
		return $return;
	}

	public function new_order(){
		$o = $this->db->query("select order_reference from " . $this->table . " order by order_reference desc limit 1")->row_array();
		return @$o['order_reference']+2;
	}

	public function del_reference($id=NULL){
		$this->load(NULL,$id);
		$this->del_img();
		return $this->del();
	}

	public function upload_img(){
		$id = $this->id;
		$image = new Image();
		for($i=0;$i<6;$i++){
			if(empty($this->data['img_'.$i]['tmp_name'])) continue;
			$image->load($this->data['img_'.$i]['tmp_name']);
			$h15 = $image->getHeight()*1.5;
			$w15 = $image->getWidth()*1.5;
			if($h15<500 && $w15<630){
				$image->resize_max($w15,$h15);
			}else{
				$image->resize_max(630,500);
			}
			$image->save("www/photos/ref".$id."-".$i."a.jpg",IMAGETYPE_JPEG,80);
			if($i==0){
				$i2 = $image;
				$i2->resize_min(200,155);
				$i2->crop(195,150);
				$i2->save("www/photos/ref".$id."-".$i."b.jpg",IMAGETYPE_JPEG,80);
			}
			$image->resize_min(125,100);
			$image->crop(115,90);
			$image->save("www/photos/ref".$id."-".$i."min.jpg",IMAGETYPE_JPEG,80);
		}
	}

	public function del_img(){
		for($i=0;$i<5;$i++){
			if(file_exists("www/photos/ref".$this->id."-".$i."min.jpg")) unlink("www/photos/ref".$this->id."-".$i."min.jpg");
			if(file_exists("www/photos/ref".$this->id."-".$i."a.jpg")) unlink("www/photos/ref".$this->id."-".$i."a.jpg");
			if(file_exists("www/photos/ref".$this->id."-".$i."b.jpg")) unlink("www/photos/ref".$this->id."-".$i."b.jpg");
		}
	}

}
