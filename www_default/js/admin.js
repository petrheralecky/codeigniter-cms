
$(document).ready(function(){

		/**** Melou ****/
	// file_upload
	$('input[name="uploaded_file"]').click(function(){ $('input[name="file_type"][value="0"]').attr("checked",true); })
	$('input[name="file_url"]').click(function(){ $('input[name="file_type"][value="1"]').attr("checked",true); })
	$('input[name="file_type"]').change(function(){	file_type(); });
	function file_type(){
		if($('input[name="file_type"][value="0"]').attr("checked")==true) $('input[name="file_url"]').val("");
		else $('input[name="uploaded_file"]').val("");
	}

	// act short_text
	$('input[name="short_text_copy"]').change(function(){ short_text_display(); });
	short_text_display();	
	function short_text_display(){
		if($('input[name="short_text_copy"][value="0"]').attr("checked")==true){
			$('.short_text').show();
		}
		else $('.short_text').hide();
	}
		/**** endMelou ****/



})