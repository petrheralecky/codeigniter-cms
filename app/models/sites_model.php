<?php
/**
 * Subsites
 *
 * @author Melounek
 */
class Sites_model extends Base_model {
	protected $table = "sites";
    public function __construct(){
		
	}
	public function get_all($type=NULL){
		$where = " where active=1 ";
		if($type) $where .= "&& id_sites_types='".$type."' ";
		$sql = "select sites.id as id,title,tree,id_sites_types,sites_type from " . $this->table  . $this->left_join($this->table)
				. $where . " order by id_sites_types, tree";
		$data = $this->db->query($sql)->result_array();
		return $data;
	}
	public function get_one($id){
		$sql = "select *,". $this->table .".id as id from ". $this->table . $this->left_join($this->table) ."
				where active=1 && ". $this->table .".id='". $id ."'";
		$item = $this->db->query($sql)->row_array();
		return $item;
	}
	
	public function types(){
		$result = $this->db->query('select * from sites_types')->result_array();
		$types = array();
		foreach($result as $r) $types[$r['id']] = $r['sites_type'];
		return $types;
	}
	public function del_site($id){
		$this->load(NULL,$id);
		$this->db->query('update sites set active="0" where id="'.$this->id.'"');
		return true;
	}
	public function save_site($data=NULL,$id=NULL){
		$this->load($data,$id);
		$return = $this->save();
		$this->uri->save_sef("home/site/".$this->id,!empty($this->data['title_seo'])?$this->data['title_seo']:$this->data['title']);
		return $return;
	}
}
