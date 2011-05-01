<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller {
	public $data;

	public function __construct(){
		parent::__construct();
		//$this->data = $this->controller_model->viewData();
		Form::$escape_quotes = true;
		if(!$this->users_model->is_admin()){
			Tools::flash("Nemáte přístup do administrační částí!");
			$this->uri->redirect("home/login");
			$this->uri->back(array('no_controller'=>'admin','no_action'=>'logout','action'=>'login'));
		}
		$this->load->helper('image_helper');
	}

	function index () { 
		$f = new Form("settings",array("data"=>$this->data['settings']));
		if($f->ready()){
			$data = $f->get_data(); 
			if($this->settings_model->save_settings($data)){
				Tools::flash("uloženo...","succ");
				$this->uri->redirect("admin");
			}
		}

		$this->data['f'] = $f;
		$this->load->view('layout/admin',$this->data);
	}

	public function def ($edit_id = 0) {
		$this->load->model($model="def_model");
		$this->def = $this->$model;
		$f = new Form($model);
		if($edit_id){
			if(!($this->data['item'] = $this->def->get_one($edit_id))){
				Tools::flash("Zadaná stránka nebyla nalezena...");
				$this->uri->redirect("admin");
			}
			$f->load_data($this->data['item']);
		}
		if($f->ready()){
			$data = $f->get_data();
			if ($this->def->save($data,$edit_id)){
				Tools::flash ("uloženo...","succ");
				$this->uri->redirect("admin/links");
			}else{
				Tools::flash ("Nelze vložit data, Admin::action().","critical");
				$this->uri->back();
			}
		}
		$this->data['items'] = $this->def->get_all();
		$this->data['f'] = $f;
		$this->load->view('layout/admin',$this->data);
	}
	
	public function links ($edit_id = 0) {
		$this->load->model("base_model","links");
		$this->def = $this->links;
		$this->def->load(NULL,$edit_id,"links");
		$f = new Form("links");
		if($edit_id){
			if(!($this->data['item'] = $this->def->get_one($edit_id)))
				$this->uri->redirect("admin/links");
			$f->load_data($this->data['item']);
		}
		if($f->ready()){
			$data = $f->get_data();
			if ($this->def->save($data,$edit_id)){
				Tools::flash ("uloženo...","succ");
				$this->uri->redirect("admin/links");
			}
			else
				Tools::flash ("Nelze vložit data, Admin::links().","critical");
			$this->uri->back();
		}
		$this->data['items'] = $this->def->get_all();
		$this->data['f'] = $f;
		$this->load->view('layout/admin',$this->data);
	}

	public function reference ($edit_id = 0) {
		$this->load->model("reference_model");
		$this->def = $this->reference_model;
		$f = new Form("ref");
		if($edit_id){
			$this->data['item'] = $this->def->get_one($edit_id);
			if(empty($this->data['item'])) $this->uri->redirect("admin/reference");
			$f->load_data($this->data['item']);
		}else{
			$f->load_data(array('order_reference'=>$this->def->new_order()));
		}
		if($f->ready()){
			$data = $f->get_data();
			if ($this->def->save_reference($data,$edit_id)){
				Tools::flash ("uloženo...","succ");
				$this->uri->redirect("admin/reference");
			}
			else
				Tools::flash ("Nelze vložit data, Admin::links().","critical");
			$this->uri->back();
		}
		$this->data['items'] = $this->def->get_all();
		$this->data['f'] = $f;
		$this->load->view('layout/admin',$this->data);
	}

	public function act ($edit_id = 0) {
		$this->load->model("act_model");
		$this->def = $this->act_model;
		$f = new Form("act");
		if($edit_id){
			$this->data['item'] = $this->def->get_one($edit_id);
			if(empty($this->data['item'])) $this->uri->redirect("admin/act");
			$f->load_data($this->data['item']);
		}
		if($f->ready()){
			$data = $f->get_data();
			if ($this->def->save_act($data,$edit_id)){
				Tools::flash ("uloženo...","succ");
				$this->uri->redirect("admin/act");
			}
			else
				Tools::flash ("Nelze vložit data, Admin::links().","critical");
			$this->uri->back();
		}
		$this->data['items'] = $this->def->get_all();
		$this->data['f'] = $f;
		$this->load->view('layout/admin',$this->data);
	}


	public function sites ($edit_id = 0) {
		$this->load->model("sites_model");
		$this->def = $this->sites_model;
		$f = new Form("sites");
		if($f->ready()){
			$data = $f->get_data();
			if ($this->def->save_site($data,$edit_id)){
				Tools::flash ("uloženo...","succ");
				$this->uri->redirect("admin/sites");
			}
			else
				Tools::flash ("Nelze vložit data, Admin::sites().","critical");
			$this->uri->back();
		}
		if($edit_id){
			$this->data['item'] = $this->def->get_one($edit_id);
			if(empty($this->data['item'])) $this->uri->redirect("admin/sites");
			$f->load_data($this->data['item']);
		}else{
			$f->load_data(array('order_sites'=>$this->def->new_order()));
		}
		$this->data['items'] = $this->def->get_all();
		$this->data['f'] = $f;
		$this->load->view('layout/admin',$this->data);
	}

	public function files ($edit_id = 0) {
		$this->load->model("files_model");
		$this->def = $this->files_model;
		$this->data['edit'] = false;
		$f = new Form("links");
		if($edit_id){
			$this->data['item'] = $this->def->get_one($edit_id);
			$this->data['edit'] = true;
			$f->load_data($this->data['item']);
		}
		$f->extra_validator(empty($_POST['file_url']) && empty($_FILES['uploaded_file']['name']) && !$edit_id,
				'Buď vložte sobor nebo zadejte adresu souboru');
		if($f->ready()){
			
			$data = $f->get_data();
			if ($this->data['edit']){
				unset($data['file_url']);
			}
			if ($this->def->save_file($data,$edit_id)){
				Tools::flash ("uloženo...","succ");
				$this->uri->redirect("admin/files");
			}
		}
		$this->data['items'] = $this->def->get_all();
		$this->data['f'] = $f;
		$this->load->view('layout/admin',$this->data);
	}

	function users($id=0){
		$f = new Form("users");
		if($id){
			$this->data['form_user'] = $this->users_model->get_one($id);
			//die(var_dump($this->data['form_user']));
			if(empty($this->data['form_user'])){
				Tools::flash("Tento uživatel (id: ".$id.") již neexistuje");
				$this->uri->redirect("admin/users");
			}
			$f->load_data($this->data['form_user']);
		}
		if($f->ready()){
			$data = $f->get_data();
			$data['role'] = 11;
			if($this->users_model->save_user($data,$id)){
				Tools::flash("údaje o uživateli uloženy...","succ");
				$this->uri->back();
			}else{
				Tools::flash("chyba při ukládání","critical");
			}
		}

		$this->data['users'] = $this->users_model->get_all();
		$this->data['f'] = $f;
		$this->load->view('layout/admin',$this->data);
	}

	function del($table,$id,$p3="",$p4=""){
		$redirect = "";  // redirect after deleting on succes which can depend on $table
		$succ_msg = "Položka byla smazána...";  // flash message on succes which can depend on $table
		switch($table){
			case('img'): 
				// $p4 is number of image      $p3 is type like "act"
				foreach(array('full','a','b','c','min') as $option){
					$f = "www/photos/".$p3.$id. ($p4!=="" ? "-".$p4 : "") .$option.".jpg";
					if(file_exists($f)) unlink($f);
				}
				break;
			case('act'):
				$this->load->model("act_model");
				if(!$this->act_model->del_act($id)) $fail = true;
				break;
			case('sites'):
				$this->load->model("sites_model");
				if(!$this->sites_model->del_site($id)) $fail = true;
				break;
			case('reference'):
				$this->load->model("reference_model");
				if(!$this->reference_model->del_reference($id)) $fail = true;
				break;
			// without break can be case used just for rewrite message or redirecting
			default:
				$model = $table."_model";
				if (file_exists(APPPATH.'models/'.$model.EXT)){
					$this->load->model($model);
					if(!$this->$model->del($id,$p3)) $fail = true;
				}else{
					$this->base_model->load(NULL,$id,$table);
					if(!$this->base_model->del()) $fail = true;
				}
		}
		// if there
		if(!empty($fail)){
			Tools::flash("maybe bad model '".$table."' was specified or id is wrong [admin->del()]","critical");
		}else{
			Tools::flash("smazáno...","succ");
		}
		// option to specific redirect dependent on table-case
		if(!empty($redirect)){
			$this->uri->reditect($redirect);
		}else{
			$this->uri->back();
		}
	}


	function manual(){
		$this->load->view('layout/admin',$this->data);
	}
	
}

?>
