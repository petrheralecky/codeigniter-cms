<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pieces extends CI_Controller {
	public $data;	// view data

	function __construct(){
		parent::__construct();
		$this->data = $this->controller_model->viewData();
	}

	public function sklad ($edit_id = 0) {
		$this->data['p'] = $this->products_model->get_one($edit_id);
		$this->load->view('layout/blanc',$this->data);
	}
	
}

?>
