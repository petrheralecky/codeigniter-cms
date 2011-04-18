<?php
/**
 *  description?
 */
class Def_model extends Base_model {
	protected $table = "def";
    public function __construct(){
		
	}
	public function get_all(){
		$where = " where 1";
		$order = " order by id";
		$limit = "";
		$sql = "select *,". $this->table .".id as id from " . $this->table . $this->left_join($this->table) 
				. $where . $order . $limit;
		$result = $this->db->query($sql)->result_array();
		return $result;
	}
	public function get_one($id=NULL){
		$this->load(NULL,$id);
		if(empty($this->id)){
			Tools::flash("called get_one() with no id!","critical");
			return false;
		}
		// it above is just necessary garbage
		$sql = "select *,". $this->table .".id as id from ". $this->table . $this->left_join($this->table) ."
				where ". $this->table .".id='". $this->id ."'";
		$this->data = $this->db->query($sql)->row_array();
		return $this->data;
	}
	
	// below are useful methods if model has special requirements. Similary works insert, update or load
	public function save_def($data=NULL,$id=NULL){
		$this->load($data,$id);
		if($this->save()){
			// special requirements
			//$this->uri->save_sef("home/index/".$this->id,$this->data['title']);
			return true;
		}
		return false;
	}
	public function del_def($id=NULL){
		$this->load(NULL,$id);
			// special requirements
		return $this->del();
	}
}
