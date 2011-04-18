<?php

class Mail_model extends Base_model {

	function mail($to,$subject,$body,$options = NULL){
		$from = isset($options['from']) ? $options['from'] : "melou@melou.cz";
		$from_name = isset($options['from_name']) ? $options['from_name'] : $from;
		$styles = isset($options['styles']) ? $options['styles'] : "";
		$mail_type = isset($options['mail_type']) ? $options['mail_type'] : "html"; // text or html

		if($mail_type=="html"){
			$mail = '
				<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta name="keywords" content="Allseasons.cz" />
				<meta name="description" content="Allseasons.cz" />
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Allseasons.cz</title>
				<style text="text/css">
				' . $styles . '
				</style>
				</head>
				<body>
				' . $body . '
				</body>
				</html>';
		}else{
			$mail = $body;
		}
		$this->load->library('email');
		$this->email->set_newline("\r\n");
		$this->email->from($from,$from_name);
		$this->email->subject($subject);
		$this->email->message($mail);
		$this->email->set_mailtype($mail_type);

		$return = TRUE;

		if (!is_array($to)){
			$to = array($to);
		}
		foreach ($to as $value) {
			$this->email->to($value);
			if(!$this->email->send())
				$return = FALSE;
		}
		Tools::log("mail_model->mail() debugger: ".$this->email->print_debugger());
		return $return;
	}

	protected $table = "mails";
	public function __construct(){

	}
	public function get_all(){
		$sql = "select *,". $this->table .".id as id from " . $this->table . $this->left_join($this->table) ."";
		$data = $this->db->query($sql)->result_array();
		return $data;
	}
	public function get_one($id){
		$sql = "select *,". $this->table .".id as id from ". $this->table . $this->left_join($this->table) ."
		where ". $this->table .".id='". $id ."'";
		$item = $this->db->query($sql)->row_array();
		return $item;
	}
}

?>
