<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {
	public $data;	// view data
	public $def;	// default model

	function __construct(){
		parent::__construct();
	}

	function index () {
		
		echo "Generovani sitemap...";
		if($this->uri->save_sitemap()){
			echo "uspech<br /><br />";
			Tools::log("sitemap bylo úspěšné [cron]",3);
		}else{
			echo "SE NEZDARILO<br /><br />";
			Tools::log("sitemap - neúspěch [cron]",2,"critical");
		}


	}

}

?>
