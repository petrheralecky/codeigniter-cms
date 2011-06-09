<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MY_Controller
 *
 * @author Melounek
 */

require_once("app/controllers/BASE_Controller.php");

class MY_Controller extends BASE_Controller {

	public function __construct(){
		parent::__construct();



		$this->data['settings'] = $this->settings_model->get_all();
		$this->data['controller'] = $this->uri->rsegment(1);
		$this->data['action'] = $this->uri->rsegment(2);
		$this->data['uri_array'] = $this->uri->segment_array();


		/**
		* takes every adress parameter with ":" to _GET variable
		* ex:  /var:value/  ->  $_GET['var'] == value
		*/
		foreach($this->data['uri_array'] as $d){
			if(strpos($d,":")!==false){
				$e = explode(":",$d);
				$_GET[$e[0]] = $e[1];
			}
		}

		$this->data['view_content'] = ""; // alternative view
		$this->data['view_render'] = ""; // alternative path to view
		$this->uri->save_history($this->uri->uri_string(),$this->uri->rsegment_array());
	
		$this->init();
	}

}
?>
