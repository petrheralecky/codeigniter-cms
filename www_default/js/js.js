
$(document).ready(function(){

	// there should be some code

	/**
	 * sliding cookie-boxes
	 */
	$('.plus').ready(function(){
		$('.plus').each(function(ind){
			var id = $(this).attr('id');
			var name = "box-" + id;
			if(cookie(name)=="true"){
				$("#"+id).toggleClass('minus');
				$("#"+name).slideToggle(300);
			}
		})
	})
	$('.plus').click(function(event){
		var id = $(this).attr('id');
		var name = "box-" + id;
		if(cookie(name)=="true"){
			cookie(name,false)
		}else{
			cookie(name,true)
		}
		$("#"+id).toggleClass('minus');
		$("#"+name).slideToggle("fast");
		event.preventDefault();
	})
	// konec boxes

})




