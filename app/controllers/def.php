<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Def extends CI_Controller {
	public $data;	// view data
	public $def;	// default model

	function __construct(){
		parent::__construct();
		$this->data = $this->controller_model->viewData();
		$this->load->model('def_model');
	}

	public function index () {
		$this->data['def'] = $this->def_model->get_all();
		
		$this->load->view('layout/layout',$this->data);
	}

	public function items ($edit_id = 0) {
		$this->load->model("items_model");
		$this->def = $this->items_model;
		$f = new Form("items");
		if($edit_id){
			$this->data['item'] = $this->def->get_one($edit_id);
			$f->load_data($this->data['item']);
		}
		if($f->ready()){
			$data = $f->get_data();
			$this->def->save($data,$edit_id);
			Tools::back();
		}
		$this->data['items'] = $this->def->get_all();
		$this->data['f'] = $f;
		$this->load->view('layout/layout',$this->data);
	}
	
}

?>
