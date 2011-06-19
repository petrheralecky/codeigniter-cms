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
class BASE_Controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		
	}
	public function init(){
		Form::$img_app_path = FORM_IMG_APP_PATH;

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
