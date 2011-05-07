// two styles paths passed to tiny_mce
//styles = "css/styles.css";
styles = "css/mce_rules.css";

$(document).ready(function(){
	// rewrite dates to human friendly
	dt2human();
	// helpy
	$('.m-help span').addClass('m-help-in');
	$('.m-help[class!="m-help-in"]').hover(function(){
		$(this).addClass("hover-help");
		$('.hover-help span').show("fast");
	});
	$('.m-help').mouseout(function(){
		$(this).removeClass("hover-help");
		$('.m-help span').stop(true,true).hide("fast");
	});
	// focuses
	$('input, textarea').focus(function(){
		$('._'+$(this).attr('name')+' span').show('fast');
	})
	$('input, textarea').blur(function(){
		$('._'+$(this).attr('name')+' span').stop(true,true).hide('fast');
	})
})

if(window.tinyMCE != undefined){
	tinyMCE.init({
	// General options
	convert_urls : false,
	relative_urls : false,
	entities : "38,amp,34,quot,60,lt,62,gt",
	mode : "specific_textareas",
	editor_selector : "mce",
	theme : "advanced",
	// includes default styles
	content_css : styles
	});
}

var citac_odeslanych = 0;
var citac_prijatych = 0;
var preteceni = 0;
var errors = "";

function display_error(name,cont){
	if(document.getElementById('error_'+name)){
		document.getElementById('error_'+name).innerHTML = cont;
	}
}

function control_input(name,form_name,only_error,ajax_path,tagtyp){
	elements = document.getElementsByName(name);
	element = elements[elements.length-1];
	value = element.value;
	if(tagtyp=="checkbox"){ value = (element.checked?"0":"1"); }
	if(!only_error || element.className.indexOf(' not_valid') != -1){
		processAjax(form_name,"value="+value+"&name="+name+"&citac="+citac_odeslanych+"&only_error="+only_error+"&ajax_path="+ajax_path,ajax_path); //treti parametr.... +others
		citac_odeslanych++;
	}
}
function processAjax(form_name,parameters,ajax_path) {
	var http_request = false;
    var request = "form_name="+form_name+"&"+parameters;
	if (window.XMLHttpRequest) {
		http_request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		try {
		  http_request = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (error) {
		  http_request = new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	http_request.onreadystatechange = function() {
			if (http_request.readyState == 4) {
				if (http_request.status == 200) {
					if(http_request.responseText!=""){
						//alert(http_request.responseText);
						if(document.getElementById('in')) document.getElementById('in').innerHTML = http_request.responseText;
						eval(http_request.responseText);
					}
				}
			}
		};
	http_request.open('POST', ajax_path, true);
	http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http_request.send(request);
}

// kalendář
function show_calendar(str_target, str_datetime, time, img_path) {
	//var arr_months = ["January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December"];
	var arr_months = ["Leden","Únor","Březen","Duben","Květen","Červen","Červenec","Srpen","Září","Říjen","Listopad","Prosinec"];
	//var week_days = ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"];
	var week_days = ["Ne","Po", "Út", "St", "Čt", "Pá", "So"];
	var n_weekstart = 1; // day week starts from (normally 0 or 1)

	var dt_datetime =  str2dt(str_datetime);
	var dt_prev_month = new Date(dt_datetime);
	dt_prev_month.setMonth(dt_datetime.getMonth()-1);
	var dt_next_month = new Date(dt_datetime);
	dt_next_month.setMonth(dt_datetime.getMonth()+1);
	var dt_firstday = new Date(dt_datetime);
	dt_firstday.setDate(1);
	dt_firstday.setDate(1-(7+dt_firstday.getDay()-n_weekstart)%7);
	var dt_lastday = new Date(dt_next_month);
	dt_lastday.setDate(0);

	// html generation (feel free to tune it for your particular application)
	// print calendar header
	var str_buffer = new String (
		"<html>\n"+
		"<head>\n"+
		"	<title>Calendar</title>\n"+
		"</head>\n"+
		"<body bgcolor=\"White\">\n"+
		"<table class=\"clsOTable\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n"+
		"<tr><td bgcolor=\"#4682B4\">\n"+
		"<table cellspacing=\"1\" cellpadding=\"3\" border=\"0\" width=\"100%\">\n"+
		"<tr>\n	<td bgcolor=\"#4682B4\"><a href=\"javascript:window.opener.show_calendar('"+
		str_target+"', '"+ dt2dtstr(dt_prev_month)+"'+document.cal.time.value,"+time+",'"+img_path+"');\">"+
		"<img src=\""+img_path+"prev.gif\" width=\"16\" height=\"16\" border=\"0\""+
		" alt=\"previous month\"></a></td>\n"+
		"	<td bgcolor=\"#4682B4\" colspan=\"5\">"+
		"<font color=\"white\" face=\"tahoma, verdana\" size=\"2\">"
		+arr_months[dt_datetime.getMonth()]+" "+dt_datetime.getFullYear()+"</font></td>\n"+
		"	<td bgcolor=\"#4682B4\" align=\"right\"><a href=\"javascript:window.opener.show_calendar('"
		+str_target+"', '"+dt2dtstr(dt_next_month)+"'+document.cal.time.value,"+time+",'"+img_path+"');\">"+
		"<img src=\""+img_path+"next.gif\" width=\"16\" height=\"16\" border=\"0\""+
		" alt=\"next month\"></a></td>\n</tr>\n"
	);
		//alert(dt2dtstr(dt_next_month));

	var dt_current_day = new Date(dt_firstday);
	// print weekdays titles
	str_buffer += "<tr>\n";
	for (var n=0; n<7; n++)
		str_buffer += "	<td bgcolor=\"#87CEFA\">"+
		"<font color=\"white\" face=\"tahoma, verdana\" size=\"2\">"+
		week_days[(n_weekstart+n)%7]+"</font></td>\n";
	// print calendar table
	str_buffer += "</tr>\n";
	while (dt_current_day.getMonth() == dt_datetime.getMonth() ||
		dt_current_day.getMonth() == dt_firstday.getMonth()) {
		// print row heder
		str_buffer += "<tr>\n";
		for (var n_current_wday=0; n_current_wday<7; n_current_wday++) {
				if (dt_current_day.getDate() == dt_datetime.getDate() &&
					dt_current_day.getMonth() == dt_datetime.getMonth())
					// print current date
					str_buffer += "	<td bgcolor=\"#FFB6C1\" align=\"right\">";
				else if (dt_current_day.getDay() == 0 || dt_current_day.getDay() == 6)
					// weekend days
					str_buffer += "	<td bgcolor=\"#DBEAF5\" align=\"right\">";
				else
					// print working days of current month
					str_buffer += "	<td bgcolor=\"white\" align=\"right\">";

				if (dt_current_day.getMonth() == dt_datetime.getMonth()){
					// print days of current month
					str_buffer += "<a href=\"javascript:window.opener."+str_target+
					".value='"+dt2dtstr(dt_current_day)+"'";
					if(time) str_buffer += "+document.cal.time.value";
					str_buffer += "; window.opener."+str_target+".focus(); window.close();\">"+
					"<font color=\"black\" face=\"tahoma, verdana\" size=\"2\">";
				}else{
					// print days of other months
					str_buffer += "<a href=\"javascript:window.opener."+str_target+
					".value='"+dt2dtstr(dt_current_day)+"'";
					if(time) str_buffer += "+document.cal.time.value";
					str_buffer += "; window.close();\">"+
					"<font color=\"gray\" face=\"tahoma, verdana\" size=\"2\">";
				}
				str_buffer += dt_current_day.getDate()+"</font></a></td>\n";
				dt_current_day.setDate(dt_current_day.getDate()+1);
		}
		// print row footer
		str_buffer += "</tr>\n";
	}
	// print calendar footer
	str_buffer +=
		"<form name=\"cal\">\n<tr><td colspan=\"7\" bgcolor=\"#87CEFA\" onsubmit=\"return false;\">"+
		"<font color=\"White\" face=\"tahoma, verdana\" size=\"2\">";
	if(time){
		str_buffer += "Time: <input type=\"text\" name=\"time\" onKeyPress=\"if(event.which==13) return false;\" value=\""+dt2tmstr(dt_datetime,(time==2?1:0)) + "\" size=\"8\" maxlength=\"8\">" ;
	}else{
		str_buffer += "<input type=\"hidden\" name=\"time\" value=\""+dt2tmstr(dt_datetime) + "\" size=\"8\" maxlength=\"9\">";
	}

	str_buffer += "</font></td></tr>\n</form>\n" +
		"</table>\n" +
		"</tr>\n</td>\n</table>\n" +
		"</body>\n" +
		"</html>\n";

	var vWinCal;
	if(time){
		vWinCal = window.open("", "Calendar", "width=210,height=250,status=no,resizable=yes,top=200,left=200");
	}else{
		vWinCal = window.open("", "Calendar", "width=210,height=210,status=no,resizable=yes,top=200,left=200");
	}
	vWinCal.opener = self;
	var calc_doc = vWinCal.document;
	calc_doc.write (str_buffer);
	calc_doc.close();
}
function str2dt (str_datetime) {
	var re_date = /^(\d+)\-(\d+)\-(\d+)\s+(\d+)\:(\d+)\:(\d+)$/;
	if (re_date.exec(str_datetime))	return (new Date (RegExp.$1, RegExp.$2-1, RegExp.$3, RegExp.$4, RegExp.$5, RegExp.$6));
	re_date = /^(\d+)\-(\d+)\-(\d+)$/;
	if (re_date.exec(str_datetime))	return (new Date (RegExp.$1, RegExp.$2-1, RegExp.$3));
	re_date = /^(\d+)\-(\d+)\-(\d+)\s+(\d+)\:(\d+)$/;
	if (re_date.exec(str_datetime))	return (new Date (RegExp.$1, RegExp.$2-1, RegExp.$3, RegExp.$4, RegExp.$5));

	re_date = /^(\d+)\.(\d+)\.(\d+)\s+(\d+)\:(\d+)\:(\d+)$/;
	if (re_date.exec(str_datetime))	return (new Date (RegExp.$3, RegExp.$2-1, RegExp.$1, RegExp.$4, RegExp.$5, RegExp.$6));
	re_date = /^(\d+)\.(\d+)\.(\d+)$/;
	if (re_date.exec(str_datetime))	return (new Date (RegExp.$3, RegExp.$2-1, RegExp.$1));
	re_date = /^(\d+)\.(\d+)\.(\d+)\s+(\d+)\:(\d+)$/;
	if (re_date.exec(str_datetime))	return (new Date (RegExp.$3, RegExp.$2-1, RegExp.$1, RegExp.$4, RegExp.$5));

	return (new Date());
}
function dt2dtstr (dt_datetime) {
	return (new String (
			//dt_datetime.getFullYear()+"-"+(dt_datetime.getMonth()>9?"":"0")+(dt_datetime.getMonth()+1)+"-"+(dt_datetime.getDate()>9?"":"0")+dt_datetime.getDate()));
			dt_datetime.getDate()+"."+(dt_datetime.getMonth()+1)+"."+dt_datetime.getFullYear()));
}
function dt2tmstr (dt_datetime,with_seconds) {
	return (new String (
			" "+dt_datetime.getHours()+":"+(dt_datetime.getMinutes()>9?"":"0")+dt_datetime.getMinutes()+
				(with_seconds?":"+(dt_datetime.getSeconds()>9?"":"0")+dt_datetime.getSeconds():"")
		));
}

function getElementsByClassName(classname, node)  {
    if(!node) node = document.getElementsByTagName("body")[0];
    var a = [];
    var re = new RegExp('\\b' + classname + '\\b');
    var els = node.getElementsByTagName("*");
    for(var i=0,j=els.length; i<j; i++)
        if(re.test(els[i].className))a.push(els[i]);
    return a;
}

function dt2human (){
	var e1 = getElementsByClassName("date");
	var e2 = getElementsByClassName("datetime");
	var e3 = getElementsByClassName("datetimesec");
	var e = e1.concat(e2).concat(e3);
	var re1 = /^(\d+)\-(\d+)\-(\d+)\s+(\d+)\:(\d+)\:(\d+)$/;
	var re2 = /^(\d+)\-(\d+)\-(\d+)$/;
	var re3 = /^(\d+)\-(\d+)\-(\d+)\s+(\d+)\:(\d+)$/;
	for(var i=0;i<e.length;i++){
		if (re1.exec(e[i].value)) e[i].value = (RegExp.$3*1+"."+RegExp.$2*1+"."+RegExp.$1+" "+RegExp.$4+":"+RegExp.$5+":"+RegExp.$6);
		if (re2.exec(e[i].value)) e[i].value = (RegExp.$3*1+"."+RegExp.$2*1+"."+RegExp.$1);
		if (re3.exec(e[i].value)) e[i].value = (RegExp.$3*1+"."+RegExp.$2*1+"."+RegExp.$1+" "+RegExp.$4+":"+RegExp.$5);
	}
}
