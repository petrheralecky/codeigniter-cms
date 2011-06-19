<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

	function __construct(){
		parent::__construct();
	}

	function form(){
		 echo "/*";
		$f = new Form($_POST['form_name']);

		if($f->validate_one($_POST['value'], $_POST['name'],1) === false ){
			$javascript = "
				elements = document.getElementsByName('{$_POST['name']}');
				element = elements[elements.length-1];
				if(element.className.indexOf(' not_valid') == -1){
					element.className=element.className+' not_valid';
				}
				txt = '".Form::$errors_decorator."';
				display_error('{$_POST['name']}',txt.replace('%msg%','{$f->not_valid_data[$_POST['name']]}'));
			";
		}else{
			$javascript = "
				elements = document.getElementsByName('{$_POST['name']}');
				element = elements[elements.length-1];
				element.className = element.className.replace('not_valid','');
				".(!$_POST['only_error']?"element.value = '{$_POST['value']}';":"")."
				display_error('{$_POST['name']}','');
			";
		}
		echo "*/";

		echo "
				if(citac_prijatych=={$_POST['citac']}){
					citac_prijatych++;
					preteceni=0;
					{$javascript}
				}else if(citac_prijatych<{$_POST['citac']}){
					if(preteceni++>8){
						citac_prijatych++;
						preteceni=0;
					}
					processAjax('{$_POST['form_name']}','value={$_POST['value']}&name={$_POST['name']}&citac={$_POST['citac']}&only_error={$_POST['only_error']}&ajax_path={$_POST['ajax_path']}','{$_POST['ajax_path']}');
				}";

		 

	 }

}