<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Printer extends CI_Controller {
	public $data;	// view data
	public $def;	// default model

	function __construct(){
		parent::__construct();
		$this->data = $this->controller_model->viewData();
	}

	public function order_adress ($edit_ids) {
		$ids = explode("-", $edit_ids);
		$this->load->model("orders_model");
		$this->data['items'] = array();
		foreach($ids as $id){
			$this->data['items'][] = $this->orders_model->get_one($id);
		}
		$this->load->view('layout/blanc',$this->data);
	}
	
}

?>
