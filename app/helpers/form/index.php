<?
include('form/form_helper.php');
$data = array(); // push values (useful for editing)
$f = new Form('form_name',array("data"=>$data));
if($f->ready()){	// this method must be called everytime... it's validating
	echo "form was succesfuly send";
	$sent_data = $f->getData();
	// now just handle this data
}
$f->no_error_names = array("podminky","like"); // input's names where are special error printing
?>

<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="example_files/styles.css" type="text/css" />
	<link rel="stylesheet" href="form/formfiles/form.css" type="text/css" />
	<script type="text/javascript" src="form/formfiles/form.js"></script>
			
			<link rel="stylesheet" href="http://melou.cz/codelib/higlighter.css" type="text/css" />
			<script type="text/javascript" src="http://melou.cz/codelib/highlighter.js"></script>
</head>
<body>
<? $f->print_errors();  //: <ul class="form_errors">...</ul> ?>
<? $f->start();  //: <form method=.......>   ?>
<table>
    <tr><td width="120">E-mail:</td>
        <td><? $f->_('<input type="text" name="email" />',1,array('email','emailFilter','emptyEmail')); ?></td></tr>
	<tr><td>Date:</td>
        <td><? $f->_('<input type="text" name="date[1][pepa]" class="date" value="2010-07-14" />',1,"date"); ?></td></tr>
	<tr><td>Date-time:</td>
        <td><? $f->_('<input type="text" name="datetime" class="datetime" />',1,"date"); ?></td></tr>
    <tr><td>Tel:</td>
        <td><? $f->_('<input type="text" name="tel" />',0,array('tel','telFilter13')); ?></td></tr>
	<tr><td>Mobil:</td>
        <td><? $f->_('<input type="text" name="mobil" />',0,array('mobil','telFilter9')); ?></td></tr>
    <tr><td>Sex:</td>
        <td><?
		$pohlavi = array('-choose-', 'male', 'female'); // options for select "pohlavi"
		$f->_('<select name="pohlavi">',$pohlavi,'pohlavi');
		?></td></tr>
	<tr><td>Your smile:</td>
        <td><?
		$smiles = array(':)', ':-O', ':-$', '>:-}'); // options for select "pohlavi"
		$f->_('<select multiple  name="smile[]">',$smiles);
		?></td></tr>
	<tr><td>Password:</td>
		<td><? $f->_('<input name="password" type="password" />',1,'delkaHesla'); ?></td></tr>
	<tr><td>Again:</td>
		<td><? $f->_('<input name="again" type="password" />',0,'rePasswordEmpty'); ?></td></tr>
	<tr><td></td>
		<td><? $f->_('<input type="checkbox" name="podminky" class="checkbox" />',1,'emptyPodminky'); ?> Accept something
			<?php $f->print_error("podminky");  //: special error printing ?>
		</td></tr>
	<tr><td>Do you like this?</td>
		<td>
			<? $f->_('<input name="like" type="radio" class="radio" value="1" />',1); ?> Yes &nbsp; &nbsp; &nbsp; &nbsp;
		    <? $f->_('<input name="like" type="radio" class="radio" value="0" />',1); ?> No
			<?php $f->print_error("like"); ?>
		</td></tr>
    <tr><td>Pozn:</td>
        <td><? $f->_('<textarea style="width:450px;" name="pozn">pozn</textarea>'); ?></td></tr>
	<tr><td>TextEditor:</td>
        <td><? $f->_('<textarea class="mce" style="width:450px;" name="pozn">pozn</textarea>'); ?></td></tr>
	<tr><td>Transcribe</td><td><? $f->captcha(); ?></td></tr>
    
	<tr><td colspan="2"><? $f->_('<input class="sub" value="Submit" type="submit" />'); ?></td></tr>
</table>
<? $f->stop();  //: </form>  ?>

	<br>
	<strong><a href="form.zip">Download source</a></strong>
	<br>
	<h3>Benefits:</h3>
<ul>
		<li>No transcribing to php - parameters are whole html tags. It's simple and fast</li>
		<li>simple validating or controll required tags - each validator is one function with one error message</li>
		<li>Filtering inputs - example: 1.1 => 2010-01-01 (just try)</li>
		<li>Total freedom in styling forms, errors... :)</li>
		<li>Simple ajax - programmer don't do anything extra, and it run without JS too</li>
		<li>Integrated CuteEditor for wysiwyg editing</li>
		<li>Captcha: by default only javascript, but you can write $form->captcha(); there it is...</li>
		<li>Calendar is integrated (just type class="date") and now I'm working on text-editor. </li>
	</ul>
	<br>

	<a href="#" id="hide" onclick="document.getElementById('code').style.display = 'block'; document.getElementById('hide').style.display = 'none'; return false;">show source code</a>
<br />
	<div  id="code" style="display: none;">
<pre class="brush:php">
&lt;?
include('form/form.php');
Form::$errors_under_input = true;
$data = array(&quot;date&quot; =&gt; date(&quot;Y-m-d&quot;)); // push values (useful for editing)
$f = new Form('form_name',array(&quot;data&quot;=&gt;$data));
$f-&gt;setErrorDecorators('&lt;span class=&quot;error_msg&quot;&gt;', '&lt;/span&gt;');
if($f-&gt;ready()){	// this method must be called everytime... it's validating
echo &quot;form was succesfuly send&quot;;
$sent_data = $f-&gt;getData();
// now just handle this data
}
$f-&gt;no_error_names = array(&quot;podminky&quot;,&quot;like&quot;); // input's names where are special error printing
?&gt;

&lt;html&gt;
&lt;head&gt;
&lt;meta http-equiv=&quot;Content-type&quot; content=&quot;text/html; charset=UTF-8&quot; /&gt;
&lt;link rel=&quot;stylesheet&quot; href=&quot;example_files/styles.css&quot; type=&quot;text/css&quot; /&gt;
&lt;link rel=&quot;stylesheet&quot; href=&quot;form/formfiles/form.css&quot; type=&quot;text/css&quot; /&gt;
&lt;script type=&quot;text/javascript&quot; src=&quot;form/formfiles/form.js&quot;&gt;&lt;/script&gt;
&lt;/head&gt;
&lt;body&gt;
&lt;? $f-&gt;printErrors();  //: &lt;ul class=&quot;form_errors&quot;&gt;...&lt;/ul&gt; ?&gt;
&lt;? $f-&gt;start();  //: &lt;form method=.......&gt;   ?&gt;
&lt;table&gt;
&lt;tr&gt;&lt;td width=&quot;120&quot;&gt;E-mail:&lt;/td&gt;
&lt;td&gt;&lt;? $f-&gt;_('&lt;input type=&quot;text&quot; name=&quot;email&quot; /&gt;',1,array('email','emailFilter','emptyEmail')); ?&gt;&lt;/td&gt;&lt;/tr&gt;
&lt;tr&gt;&lt;td&gt;Date:&lt;/td&gt;
&lt;td&gt;&lt;? $f-&gt;_('&lt;input type=&quot;text&quot; name=&quot;date&quot; class=&quot;date&quot; /&gt;',1,&quot;date&quot;); ?&gt;&lt;/td&gt;&lt;/tr&gt;
&lt;tr&gt;&lt;td&gt;Date-time:&lt;/td&gt;
&lt;td&gt;&lt;? $f-&gt;_('&lt;input type=&quot;text&quot; name=&quot;datetime&quot; class=&quot;datetime&quot; /&gt;',1,&quot;date&quot;); ?&gt;&lt;/td&gt;&lt;/tr&gt;
&lt;tr&gt;&lt;td&gt;Tel:&lt;/td&gt;
&lt;td&gt;&lt;? $f-&gt;_('&lt;input type=&quot;text&quot; name=&quot;tel&quot; /&gt;',0,array('tel','telFilter13')); ?&gt;&lt;/td&gt;&lt;/tr&gt;
&lt;tr&gt;&lt;td&gt;Mobil:&lt;/td&gt;
&lt;td&gt;&lt;? $f-&gt;_('&lt;input type=&quot;text&quot; name=&quot;mobil&quot; /&gt;',0,array('mobil','telFilter9')); ?&gt;&lt;/td&gt;&lt;/tr&gt;
&lt;tr&gt;&lt;td&gt;Sex:&lt;/td&gt;
&lt;td&gt;&lt;?
$pohlavi = array('-choose-', 'male', 'female'); // options for select &quot;pohlavi&quot;
$f-&gt;_('&lt;select name=&quot;pohlavi&quot;&gt;',$pohlavi,'pohlavi');
?&gt;&lt;/td&gt;&lt;/tr&gt;
&lt;tr&gt;&lt;td&gt;Your smile:&lt;/td&gt;
&lt;td&gt;&lt;?
$smiles = array(':)', ':-O', ':-$', '&gt;:-}'); // options for select &quot;pohlavi&quot;
$f-&gt;_('&lt;select multiple  name=&quot;smile[]&quot;&gt;',$smiles);
?&gt;&lt;/td&gt;&lt;/tr&gt;
&lt;tr&gt;&lt;td&gt;Password:&lt;/td&gt;
&lt;td&gt;&lt;? $f-&gt;_('&lt;input name=&quot;password&quot; type=&quot;password&quot; /&gt;',1,'delkaHesla'); ?&gt;&lt;/td&gt;&lt;/tr&gt;
&lt;tr&gt;&lt;td&gt;Again:&lt;/td&gt;
&lt;td&gt;&lt;? $f-&gt;_('&lt;input name=&quot;again&quot; type=&quot;password&quot; /&gt;',0,'rePasswordEmpty'); ?&gt;&lt;/td&gt;&lt;/tr&gt;
&lt;tr&gt;&lt;td&gt;&lt;/td&gt;
&lt;td&gt;&lt;? $f-&gt;_('&lt;input type=&quot;checkbox&quot; name=&quot;podminky&quot; class=&quot;checkbox&quot; /&gt;',1,'emptyPodminky'); ?&gt; Accept something
&lt;?php $f-&gt;printError(&quot;podminky&quot;);  //: special error printing ?&gt;
&lt;/td&gt;&lt;/tr&gt;
&lt;tr&gt;&lt;td&gt;Do you like this?&lt;/td&gt;
&lt;td&gt;
&lt;? $f-&gt;_('&lt;input name=&quot;like&quot; type=&quot;radio&quot; class=&quot;radio&quot; value=&quot;1&quot; /&gt;',1); ?&gt; Yes &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;
&lt;? $f-&gt;_('&lt;input name=&quot;like&quot; type=&quot;radio&quot; class=&quot;radio&quot; value=&quot;0&quot; /&gt;',1); ?&gt; No
&lt;?php $f-&gt;printError(&quot;like&quot;); ?&gt;
&lt;/td&gt;&lt;/tr&gt;
&lt;tr&gt;&lt;td&gt;Pozn:&lt;/td&gt;
&lt;td&gt;&lt;? $f-&gt;_('&lt;textarea style=&quot;width:450px;&quot; name=&quot;pozn&quot;&gt;pozn&lt;/textarea&gt;'); ?&gt;&lt;/td&gt;&lt;/tr&gt;
&lt;tr&gt;&lt;td&gt;TextEditor:&lt;/td&gt;
&lt;td&gt;&lt;? $f-&gt;editor('textname',array(&quot;Width&quot;=&gt;&quot;450px&quot;, &quot;Height&quot;=&gt;&quot;150px&quot;, &quot;required&quot;=&gt;&quot;1&quot;)); ?&gt;&lt;/td&gt;&lt;/tr&gt;
&lt;tr&gt;&lt;td&gt;Transcribe&lt;/td&gt;&lt;td&gt;&lt;? $f-&gt;captcha(); ?&gt;&lt;/td&gt;&lt;/tr&gt;

&lt;tr&gt;&lt;td colspan=&quot;2&quot;&gt;&lt;? $f-&gt;_('&lt;input class=&quot;sub&quot; value=&quot;Submit&quot; type=&quot;submit&quot; /&gt;'); ?&gt;&lt;/td&gt;&lt;/tr&gt;
&lt;/table&gt;
&lt;? $f-&gt;stop();  //: &lt;/form&gt;  ?&gt;
&lt;/body&gt;
&lt;/html&gt;
	</pre>
	</div>
	<script type="text/javascript">
		 SyntaxHighlighter.all()
	</script>
    <br /><br />
</body>
</html>