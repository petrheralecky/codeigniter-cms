<?php

class Mail_model extends Base_model {


	/**
	 * @param array $options
	 *		*to
	 *		*subject
	 *		*body
	 *		from
	 *		attachments (array)
	 *		from_name
	 *		styles
	 *		mail_type [html|text]
	 * @return <type>
	 */
	function mail($options = NULL){
		if(empty($options['subject']) || empty($options['to']) || empty($options['body'])){
			throw new Exception('Try to email without required options in parameters');
			return false;
		}
		$from = isset($options['from']) ? $options['from'] : "noreply@ci.cz";
		$from_name = isset($options['from_name']) ? $options['from_name'] : $from;
		$styles = isset($options['styles']) ? $options['styles'] : "";
		$mail_type = isset($options['mail_type']) ? $options['mail_type'] : "html"; // text or html

		$this->load->library('email');
		$this->email->set_newline("\r\n");
		$this->email->from($from,$from_name);
		$this->email->subject($options['subject']);
		//$this->email->set_alt_message($options['text_body']);
		$this->email->message($options['body']);
		$this->email->set_mailtype($mail_type);
		if(!empty($options['attachments'])){
			foreach($options['attachments'] as $at){
				$this->email->attach($at);
			}
		}
		$return = TRUE;
		$this->email->to($options['to']);
		if(!$this->email->send('www/img'))
			$return = false;
			
		//Tools::log("mail_model->mail() debugger: ".$this->email->print_debugger());
		return $return;
	}

}

?>
