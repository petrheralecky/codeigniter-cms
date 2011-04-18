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
		if($type) $where .= "&& t_sites='".$type."' ";
		$sql = "select id,title,order_sites,t_sites from " . $this->table  //. $this->left_join($this->table)
				. $where . " order by t_sites, order_sites";
		$data = $this->db->query($sql)->result_array();
		return $data;
	}
	public function get_one($id){
		$sql = "select *,". $this->table .".id as id from ". $this->table . $this->left_join($this->table) ."
				where active=1 && ". $this->table .".id='". $id ."'";
		$item = $this->db->query($sql)->row_array();
		return $item;
	}
	public function new_order(){
		$o = $this->db->query("select order_sites from " . $this->table . " where active=1 order by order_sites desc limit 1")->row_array();
		return $o['order_sites']+2;
	}
	public function del_site($id){
		$this->load(NULL,$id);
		$this->db->query('update sites set active="0" where id="'.$this->id.'"');
		return true;
	}
	public function save_site($data=NULL,$id=NULL){
		$this->load($data,$id);
		$return = $this->save();
		$this->uri->save_sef("home/index/".$this->id,!empty($this->data['title_seo'])?$this->data['title_seo']:$this->data['title']);
		return $return;
	}
}
