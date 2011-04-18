<?php
/**
 * Users
 *
 * @author Melounek
 */
class Users_model extends Base_model {
	protected $table = "users";
    public function __construct(){
		
	}
	public function get_all($options=array()){

		$sql = "select * from " . $this->table . $this->left_join($this->table) . " where active=1";

		// pagination   depends on: $options[per_page]
		$this->load->library("pagination");
		$u = $this->uri->segment_array();
		$config['base_url'] = BASE;
		foreach($u as $uu){
			if(strpos($uu,"p:")===false)
				$config['base_url'] .= $uu."/";
		}
		$config['total_rows'] = $this->db->query($sql)->num_rows();
		if(isset($options['per_page'])){
			$config['per_page'] = $options['per_page'];
		}else{
			$config['per_page'] = 100;
		}
		$config['num_links'] = 5;
		if(isset($_GET['p'])) $config['cur_page'] = $_GET['p'];
		$config['prev_tag_open'] = '<div class="none">'; $config['prev_tag_close'] = '</div>';
		$config['next_tag_open'] = '<div class="none">'; $config['next_tag_close'] = '</div>';
		$config['first_link'] = '&lt; první'; $config['last_link'] = 'poslední &gt;';
		$this->pagination->initialize($config);
		$limit = "";
		if($config['per_page']) $limit = " limit ".(!empty($_GET['p'])?$_GET['p'].",":"").$config['per_page'];

		
		$q = $this->db->query($sql.$limit);
		$data = $q->result_array();
		return $data;
	}
	public function get_one($id=NULL,$only_active=false){
		$this->load(NULL,$id);
		$sql = "select * from ". $this->table . $this->left_join($this->table) ." where id='". $this->id ."'";
		if($only_active) $sql .= " && active=1";
		$item = $this->db->query($sql)->row_array();
		$item['password_md5'] = $item['password'];
		unset($item['password']);
		return $item;
	}
	/**
	 * @return array user (like get_one)
	 */
	public function save_user($data,$id=NULL){
		$this->load($data,$id);
		if($this->id){
			if(!empty($this->data['password'])){
				$this->data['password'] = md5($this->data['password']);
			}
			if($this->update()){
				$this->data['id'] = $this->id;
				return $this->data;
			}
		}else{
			if(!empty($this->data['password'])){
				$this->data['password'] = md5($this->data['password']);
			}elseif(empty($this->data['password'])){
				$password = rand_str(6);
				$this->data['password'] = md5($password);
				$this->data['password_new'] = $password;
			}
			$this->data['id'] = $this->insert($this->data);
		}
		return $this->data;
	}

	/**
	 * if login is succes, $this->data is filled by user data
	 * There is no logout method ... for this purpose is Ses::user_destroy() method
	 * @return int id or NULL
	 */
	public function login($data){
		$md5 = md5($data['password']);
		$sql = "select id from users where login='".$data['login']."'  && password='".$md5."'";
		$q = $this->db->query($sql);
		$id = $q->row_array();
		if(isset($id['id'])){
			$this->id = $id['id'];
			$this->data = $this->get_one(); // data is filled by users data
			$this->data['hash'] = md5(date("Y-m-d-H:i:s").$this->data['id']);
			Ses::user('hash',$this->data['hash']); // for optional better safety
			$this->db->query('update users set hash="'.$this->data['hash'].'" where id="'.$this->id.'"'); // renew hash
			Tools::log("hash of user id:".$this->id." is now ".$this->data['hash']);
			return $this->id;
		}else{
			return NULL;
		}
	}

	// this method co with Ses_helper
	// if safety, admin is stored in session => login every new session
	public function is_admin($safety = false){
		$u = $this->db->query("select hash from users where id='".Ses::user('id')."'")->row_array();
		if(!$safety){
			if(Ses::user('role')>9 && !empty($u) && $u['hash']==Ses::user('hash')){
				return true;
			}
		}else
			if(Ses::user('role')>9 && !empty($u) && !empty($_SESSION['users_hash']) && $u['hash']==$_SESSION['users_hash'])
				return true;
		return false;
	}

	public function get_one_transport($id){
		$sql = "select * from transport where id='". $id ."'";
		$q = $this->db->query($sql);
		$data = $q->row_array();
		return $data;
	}
	public function del($id){
		$q = $this->db->query("delete from ". $this->table ." where id='". $id ."'");
		return $this->db->affected_rows();
	}
}
