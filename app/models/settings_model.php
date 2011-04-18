<?php
/**
 * Settings
 *
 * @author Melounek
 */
class Settings_model extends Base_model {
	protected $table = "settings";
    public function __construct(){
		
	}
	public function get_all(){
		$sql = "select * from " . $this->table;
		$result = $this->db->query($sql)->result_array();
		$data = array();
		foreach($result as $row){
			$data[$row['name']] = $row['value'];
		}
		return $data;
	}
	public function get_one($id){
		// todo
	}
	public function save_settings($data){
		foreach($data as $i=>$d){
			if($i=="captcha") continue;
			$row = $this->db->query("select * from settings where name='".$i."'")->row_array();
			if(empty($row)){
				$sql = "insert into settings (value,name) values ('".$d."','".$i."')";
			}else{
				$sql = "update settings set value='".$d."' where name='".$i."'";
			}
			$this->db->query($sql);
		}
		return true;
	}
}
