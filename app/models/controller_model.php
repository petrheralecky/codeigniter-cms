<?php
/**
 *
 *
 * @author Melounek
 */
class Controller_model extends CI_Model {

    public function __construct(){
		Form::$img_path = PATH . "img/form/";
		Form::$img_app_path = "./www/img/form/";
		Form::$cuteeditor_path = PATH . "cuteeditor/";
		Form::$log_tool = Tools::get_instance();
		Form::$ajax_path = BASE . "ajax/form";
	}
	public function viewData(){
		$this_1=get_instance();
		$data['settings'] = $this_1->settings_model->get_all();
		$data['controller'] = $this->uri->rsegment(1);
		$data['action'] = $this->uri->rsegment(2);
		$data['uri_array'] = $this->uri->segment_array();

		/**
		* takes every adress parameter with ":" to _GET variable
		* ex:  /var:value/  ->  $_GET['var'] == value
		*/
		foreach($data['uri_array'] as $d){
			if(strpos($d,":")!==false){
				$e = explode(":",$d);
				$_GET[$e[0]] = $e[1];
			}
		}
		
		$data['view_content'] = ""; // alternative view
		$data['view_render'] = ""; // alternative path to view
		$this->uri->save_history($this->uri->uri_string(),$this->uri->rsegment_array());


		// title
		$data['title'] = "Allseasons eshop s outdoor oblečením";
		$data['description'] = "";

		$data['user'] = Ses::user_array();

		// act
		$this_1->load->model('act_model');
		$data['acts'] = $this_1->act_model->get_all();

		// files
		$this_1->load->model("files_model");
		$data['files'] = $this_1->files_model->get_all();

		// links
		$this_1->load->model("base_model","links");
		$this_1->links->load(0,0,"links");
		$data['links'] = $this_1->links->get_all();

		// subsites
		$data['sites'] = $this_1->sites_model->get_all();
		if(SERVER=="local" && $data['controller']!="admin"){
			$this->output->enable_profiler();
		}

		return $data;
	}
}
