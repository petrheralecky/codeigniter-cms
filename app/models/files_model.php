<?php
/**
 * Def
 *
 * @author Melounek
 */
class Files_model extends Base_model {
	protected $table = "files";
    public function __construct(){
		
	}

	public function get_all(){
		$sql = "select *,". $this->table .".id as id from " . $this->table . $this->left_join($this->table) ."";
		$result = $this->db->query($sql)->result_array();
		foreach($result as $i=>$d){
			$result[$i]['f_url'] = (!$d['file_type'] ? PATH . "files/" : "") . $d['file_url'];
		}
		return $result;
	}

	public function get_one($id=NULL){
		$this->load(NULL,$id);
		$sql = "select *,". $this->table .".id as id from ". $this->table . $this->left_join($this->table) ."
				where ". $this->table .".id='". $this->id ."'";
		$this->data = $this->db->query($sql)->row_array();
		return $this->data;
	}

	public function save_file($data=NULL,$id=NULL){
		$this->load($data,$id);
		if ($id || (!empty($data['file_type']) && $data['file_type']==1)){
			return $this->save();
		}
		$name = my_str2url($data['uploaded_file']['name']);
		if(file_exists("www/files/" . $name)){
			Tools::flash("Soubor se zadaným názvem již existuje.");
			return FALSE;
		}
		if(move_uploaded_file($data['uploaded_file']['tmp_name'], "www/files/" .$name)){
			$this->data['file_url'] = $name;
			return $this->save();
		}else{
			Tools::flash ("upload souboru se nezdaril, Files_model::save_file()", "critical");
		}
	}

	public function del_file($id=NULL){
		$this->load(NULL,$id);
		$this->get_one();
		if ($this->data["file_type"]==0 && !unlink(PATH."files/".$this->data["file_url"])){
			Tools::flash ("nelze smazat soubor, Download_model::del_file()", "critical");
			return false;
		}else{
			return $this->del();
		}
	}
}
