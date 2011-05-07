function gi (id) { return document.getElementById(id) }
function gid (id,display) { return document.getElementById(id).style.display = (display?"block":"none"); }
function gicl (id,classname) { return document.getElementById(id).className = classname; }
function gt (id) { return document.getElementsByTagName(id) }
function gn (id) { return document.getElementsByName(id) }

function cookie(name,value,days) {
	if(typeof value == "undefined"){ // return
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
	}else if(value === null){ // delete
		cookie(name,"",-1);
	}else{						// save
		var re1=/^[a-z0-9:_\/-]+$/;
		if(!re1.test(name))	throw "bad parameter name!";
		if(days && typeof days !== typeof 1) throw "bad parameter days!";
		if (!days) days = 30;
		if(name==""){
			alert("function cookies() get empty name parameter!");
			return null;
		}
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		document.cookie = name+"="+value+expires+"; path=/";
	}
	return null;
}
