<?php
/**
 * Act
 *
 * @author Melounek
 */
class Act_model extends Base_model {
	protected $table = "act";
    public function __construct(){
		
	}
	public function get_all($active = true){
		$where = " where 1";
		if($active){
			$where .= " && created<=now() && expire>now()";
		}else{
			$where .= " && (created>now() || expire<=now())";
		}
		$sql = "select *,". $this->table .".id as id, date_format(created,'%e.%c.%Y') as f_created from " . $this->table . $this->left_join($this->table) . $where . "";
		$data = $this->db->query($sql)->result_array();
		return $data;
	}
	public function get_one($id){
		$sql = "select *,". $this->table .".id as id, date_format(created,'%e. %c. %Y') as f_created  from ". $this->table . $this->left_join($this->table) ."
				where ". $this->table .".id='". $id ."'";
		if(($item = $this->db->query($sql)->row_array())){
			$next = $this->db->query("select id from act where created > '".$item['created']."' && text
				order by created asc limit 1")->row_array();
			$before = $this->db->query("select id from act where created < '".$item['created']."' && text
				order by created desc limit 1")->row_array();
			$item['next'] = (isset($next['id']) ? $next['id'] : NULL) ;
			$item['before'] = (isset($before['id']) ? $before['id'] : NULL) ;
			return $item;
		}else{
			return NULL;
		}
	}
	
	public function save_act($data,$id){
		$this->load($data,$id);
		if($this->save()){
			$this->upload_img();
			$this->uri->save_sef("home/act/".$this->id,$this->data['act']);
			return true;
		}
		return false;
	}

	public function del_act($id=NULL){
		$this->load(NULL,$id);
		$this->del_img();
		return $this->del();
	}

	public function upload_img(){
		$img = $this->data['img']['tmp_name'];
		$id = $this->id;
		if(!empty($img)){
			$image = new Image();
			$image->load($img);
			$image->resize_max(950,750,false);
			$image->save("www/photos/act".$id."full.jpg",IMAGETYPE_JPEG,80);
			$image->resize_max(240,260);
			$image->save("www/photos/act".$id."a.jpg",IMAGETYPE_JPEG,80);
			$image->resize_min(60,65);
			$image->crop(58,62);
			$image->save("www/photos/act".$id."min.jpg",IMAGETYPE_JPEG,80);
		}
	}

	public function del_img(){
		if(file_exists("www/photos/act".$this->id."min.jpg")) unlink("www/photos/act".$this->id."min.jpg");
		if(file_exists("www/photos/act".$this->id."a.jpg")) unlink("www/photos/act".$this->id."a.jpg");
		if(file_exists("www/photos/act".$this->id."full.jpg")) unlink("www/photos/act".$this->id."full.jpg");
	}

}
