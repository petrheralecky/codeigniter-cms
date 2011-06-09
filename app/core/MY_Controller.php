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

		Form::$img_app_path = FORM_IMG_APP_PATH;

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

		// login
		$f = new Form("login");
		if($f->ready()){
			$data = $f->get_data();
			if($this->users_model->login($data)){
				Ses::user($this->users_model->data);
				Tools::flash("Byl jste úspěšně přihlášen","succ");
				$this->uri->redirect("");
			}else{
				Tools::flash("Zadal jste špatný login nebo heslo","alert");
			}
		}

		$this->data['f_login'] = $f;


		// title
		$this->data['title'] = "Direct mail :: Propeople";
		$this->data['description'] = "";

		$this->data['user'] = Ses::user_array();

		// act
		$this->load->model('act_model');
		$this->data['acts'] = $this->act_model->get_all();

		// files
		$this->load->model("files_model");
		$this->data['files'] = $this->files_model->get_all();

		// links
		$this->load->model("base_model","links");
		$this->links->load(0,0,"links");
		$this->data['links'] = $this->links->get_all();

		// subsites
		$this->data['sites'] = $this->sites_model->get_all();
		if(SERVER=="local" && $this->data['controller']!="admin"){
			$this->output->enable_profiler();
		}
		
	}

}
?>
