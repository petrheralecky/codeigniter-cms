<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {
	public $data;

	function __construct(){
		parent::__construct();
	}

	function index ($id=NULL) { 
		if(empty($id) || !is_numeric($id)){
			//$this->uri->redirect(404);
		}else{
			$site = $this->sites_model->get_one($id);
			if(empty($site)) $this->uri->redirect(404);
		}
		if(isset($_GET['error']) && $_GET['error']==404) Tools::flash("404 - Tato stránka bohužel neexistuje. Byli jste přesměrováni na hlavní stranu","warn");
		$this->load->model("mail_model");
		$this->data['mail_model'] = $this->mail_model;
		// vyřízení kontaktního formuláře
		$f_contact = new Form('contact_form',array('data'=>array()));
		if($f_contact->ready()){
			$data = $f_contact->get_data();
			if($this->mail_model->mail(
				$this->data['settings']['email'],
				"Dotaz z webu ".$data['jmeno'],
				"Jméno: ".$data['jmeno']."\n\nKontakt: ".$data['kontakt']."\n\n".$data['vzkaz'],
				array("from"=>"web@tepelna-izolace.com","mail_type" => "text")
			)){
				Tools::flash("Váš dotaz byl úspěšně odeslán.","succ");
				$this->uri->back();
			}
		}
		$contact_data['f'] = $f_contact;

		$cont = $site['content'];
		$cont = str_replace('##base##', BASE, $cont);
		$cont = str_replace('##banner##', $this->load->view('pieces/banner',array(),true), $cont);
		$cont = str_replace('##rotator##', $this->load->view('pieces/rotator',array(),true), $cont);
		$cont = str_replace('##mapa##', $this->load->view('pieces/map',array(),true), $cont);
		$cont = str_replace('##reference##', $this->load->view('pieces/reference',$this->data,true), $cont);
		$cont = str_replace('##cenik##', $this->load->view('pieces/cenik',array(),true), $cont);
		$cont = str_replace('##kontakt-form##', $this->load->view('pieces/contact-form',$contact_data,true), $cont);
		$cont = str_replace('##aktuality##', $this->load->view('pieces/act',$this->data,true), $cont);
		$this->data['title'] = $site['title_seo'];
		$this->data['description'] = $site['description'];
		$this->data['view_content'] = $cont;
		$this->load->view('layout/layout',$this->data);

	}

	function login (){
		if($this->users_model->is_admin()){
			$this->uri->redirect("admin");
		}
		$f = new Form("login");
		if($f->ready()){
			$data = $f->get_data();
			if($this->users_model->login($data)){
				Ses::user($this->users_model->data);
				Tools::flash("přístup do administrace povolen","succ");
				$this->uri->redirect("admin");
			}else{
				Tools::flash("Zadal jste špatný login nebo heslo","alert");
			}
		}
		$this->data['f'] = $f;
		$this->load->view('layout/layout',$this->data);
	}

	function reference($edit_id){
		$this->load->model('reference_model');

		$this->data['ref'] = $this->reference_model->get_one($edit_id);
		$this->data['title'] = $this->data['ref']['title_reference'];
		$this->data['description'] = my_short_text($this->data['ref']['text_reference'],70);

		$this->data['refs'] = $this->reference_model->get_all();
		$this->load->view('layout/layout',$this->data);
	}

	function act($edit_id){
		$this->load->model('act_model');
		$this->data['acts'] = $this->act_model->get_all();
		$this->data['item'] = $this->act_model->get_one($edit_id);
		$this->data['title'] = $this->data['item']['act'];
		if(!empty($this->data['item']['short_text'])){
			$this->data['description'] = $this->data['item']['short_text'];
		}else{
			$this->data['description'] = my_short_text($this->data['item']['text'],70);
		}
		
		$this->load->view('layout/layout',$this->data);
	}

	function logout (){
		Ses::user_destroy();
		Tools::flash("byl jste úspěšně odhlášen","succ");
		$this->uri->redirect("");
	}


}

?>
