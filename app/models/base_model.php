<?php
/**
 * Tasks
 *
 * @author Melounek
 */
class Base_model extends CI_Model {
	protected $table = "def";
	public $data =  array();
	public $id = NULL;

	public function __construct(){
		
	}

	// use update or insert depends on id. Returns affected id
	public function save($data=array(),$id=NULL){
		$this->load($data,$id);
		if(!empty($this->data)){
			if($this->id){
				return $this->update();
			}else{
				return $this->insert();
			}
		}else{
			Tools::flash("No data to save! [Base_model->save()]","critical");
			return false;
		}
	}

	public function load($data=array(), $id=NULL, $table=NULL){ // table is useable for alone base_model
		if(is_numeric($id) && $id){
			$this->id = $id;
			Tools::log("Base_model->load() id: " . $id,4);
		}
		if(!empty($data)){
			$this->data = $data;
			if(!empty($this->data['id']) && is_numeric($this->data['id']) && !$this->id){
				$this->id = $this->data['id']; // if id is in data, copy it to $this->id
			}
			Tools::log("Base_model->load() data ",4);
		}
		if(!empty($table)){
			$this->table = $table;
			Tools::log("Base_model->load() table: " . $table,4);
		}
	}

    public function update($data=array(),$id=NULL){
		$this->load($data,$id);
		
		$columns = array();
		$q = $this->db->query("show columns from " . $this->table);
		$structure = $q->result_array($q);
		foreach($structure as $column){
			$columns[] = $column['Field'];
		}
		$q = "update {$this->table} set ";
		$c = 0;
		foreach($this->data as $i=>$d){
			if(!in_array($i, $columns)) continue;
			if($this->id=="" && $i=="id"){$this->id = $d; continue;}
			elseif($i=="id") continue;
			if($c++) $q.=", ";
			$q .= $i."='{$d}'";
		}
		if($c && $this->id){
			$updated_row = $this->db->query("select id from ".$this->table." where id='".$this->id."' limit 1")->row_array();
			if(empty($updated_row)){
				Tools::flash("Tools::update() - unknow id ".$this->id,"critical");
				return false;
			}
			$q .= " where id='{$this->id}'";
			$this->db->query($q);
			Tools::log("Base_model->update() id: " . $this->id . " data: " . var_export($this->data,1),3);
			return $this->id;
		}
		Tools::flash("Base_model->update() unknow error ... updated data: " . var_export($this->data,1),"critical");
		return false;
	}
	public function insert($data=array(),$options=array()){
		if(isset($options['table'])){
			$table = $options['table'];
		}else{
			$table = $this->table;
		}
		$this->load($data,NULL);
		$columns = array();
		$q = $this->db->query("show columns from " . $table);
		$structure = $q->result_array($q);
		foreach($structure as $column){
			$columns[] = $column['Field'];
		}
		$names = ""; $values = "";
		$c = 0;
		/// insert existing id
		if(!empty($this->data['id'])){
			$select_id = $this->db->query("select id from ".$table." where id='".$this->data['id']."'")->row_array();
		}
		if(isset($this->data['id']) && !empty($select_id)){
			$saved_id = $this->data['id'];
			unset($this->data['id']);
		}
		///
		foreach($this->data as $i=>$d){
			if(!in_array($i, $columns)) continue;
			if($c++){ $names.=", "; $values.=", "; }
			$names .= $i;
			$values .= "'".$d."'";
			
		}
		if($names!=""){
			$q = "insert into ".$table." ({$names}) values ({$values})";
			if($this->db->query($q)){
				$insert_id = $this->db->insert_id();
				//Tools::log("Base_model->insert() id: " . $this->db->insert_id() . " data: " . var_export($this->data,1),3 );
			}
			if(isset($saved_id) && $saved_id){
				$this->del($saved_id,array('table'=>$table));
				$this->db->query("update ".$table." set id=".$saved_id." where id=".$insert_id);
				$insert_id = $saved_id;
				Tools::log("Base_model->insert() overwrites product id " . $saved_id ,4 );
			}
			$this->id = $insert_id;
			return $insert_id;
		}
		Tools::log("Base_model->insert() has get noone data-key which is in db... id: " . $saved_id ,4 );
		return false;
	}

	public function del($id=NULL,$options=array()){
		if(isset($options['table'])){
			$table = $options['table'];
		}else{
			$table = $this->table;
		}
		$this->load(NULL,$id);
		$q = "delete from ".$table." where id='{$this->id}'";
		$this->db->query($q);
		return $this->db->affected_rows();
	}

	public function left_join($table){
		$q = $this->db->query("show columns from " . $table);
		$structure = $q->result_array($q);
		$result = "";
		foreach($structure as $col){
			if(strpos($col['Field'],"id_") !== false){
				$sub_table = substr($col['Field'],3);
				$result .= "\n\tleft join {$sub_table} on {$table}.id_{$sub_table} = {$sub_table}.id";
			}
		}
		return $result;
	}

	// there are same methods like in all models. Usually are replaced in child model classes.
	// this is more for using base model alone.
	public function get_all(){
		if($this->table=="def"){ Tools::flash("table isn't set","critical"); return false; }
		$where = " where 1";
		$order = " order by id";
		$limit = "";
		$sql = "select *,". $this->table .".id as id from " . $this->table . $this->left_join($this->table)
				. $where . $limit;
		$result = $this->db->query($sql)->result_array();
		return $result;
	}
	public function get_one($id=NULL){
		if($this->table=="def"){ Tools::flash("table isn't set","critical"); return false; }
		$this->load(NULL,$id);
		if(empty($this->id)){
			Tools::flash("called get_one() without id!","critical");
			return false;
		}
		// it above is just necessary garbage
		$sql = "select *,". $this->table .".id as id from ". $this->table . $this->left_join($this->table) ."
				where ". $this->table .".id='". $this->id ."'";
		$this->data = $this->db->query($sql)->row_array();
		return $this->data;
	}
}
