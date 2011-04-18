<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Author" content="Miroslav Hlavička - allseasons.cz" />
<meta name="Robots" content="noindex,nofollow" />

<link rel="shortcut icon" href="<?=PATH?>img/favicon.gif" />
<link rel="icon" type="image/gif" href="<?=PATH?>img/favicon.gif" />
<link rel="stylesheet" type="text/css" href="<?=PATH?>css/admin.css" />
<link rel="stylesheet" type="text/css" href="<?=PATH?>css/form.css" />
<script type="text/javascript">
<?php if(!strpos($_SERVER["HTTP_USER_AGENT"],"MSIE")){	echo 'var explorer = false;';
}else{	echo 'var explorer = true;'; }?>
	var styles = "<?=PATH?>css/styles.css";
	var styles2 = "<?=PATH?>css/mce_rules.css";
</script>
<link rel="stylesheet" type="text/css" href="<?=PATH?>js/lightbox/themes/white-green/jquery.lightbox.css" />
<script src='<?=PATH?>js/jquery.js' type='text/javascript'></script>
<script src="<?=PATH?>js/tiny_mce/tiny_mce.js" type="text/javascript" ></script>
<script src='<?=PATH?>js/form.js' type='text/javascript'></script>
<script src='<?=PATH?>js/admin.js' type='text/javascript'></script>
<script type="text/javascript" src="<?=PATH?>js/lightbox/jquery.lightbox.min.js"></script>

<title>Administrace</title>

</head>
<body>

								<!-- HEAD -->
<div class="head">
<div class="head-in">
	<div class="top-links">
		<a href="<?=$this->uri->sef("")?>">hlavní strana</a> |
		<a href="http://www.podpora.eu/phpmyadmin/" onclick="alert('loginMsql6 / heslo')">phpMyAdmin</a> |
		<a href="http://melou.cz/tasks/">Webmaster tasklist</a>
	</div>

	<ul class="lista">
		<li style="float: right;"><a href="<?=BASE?>admin/manual">Manuál</a></li>
		<li style="float: right;"><a href="<?=BASE?>home/logout" style="color: red;">Odhlásit</a></li>

		<li><a href="<?=BASE?>admin">Hlavní</a></li>
		<li><a href="<?=BASE?>admin/sites">Podstránky</a></li>
		<li><a href="#" onclick="return false;">Entity ↓</a>
			<ul>
				<li><a href="<?=BASE?>admin/act">Aktuality</a></li>
				<li><a href="<?=BASE?>admin/links">Odkazy</a></li>
				<li><a href="<?=BASE?>admin/files">Ke stažení</a></li>
			</ul>
		</li>
		<li><a href="<?=BASE?>admin/users">Uživatelé</a></li>
	</ul>

</div><!--head-in-->
</div><!--head-->

<div class="all">



<? Tools::printFlash(); ?>
<?

	// VYPSÁNÍ PŘÍSLUŠNÉHO VIEW

if($view_content){ // view naplněno v controlleru
	echo $view_content;
}elseif($view_render){ // speciální cesta k view
	$this->load->view($view_render);
}else{  // view naplněno v controlleru
	$this->load->view($controller. '/' .$action. ".php");
}
?>

</div><!--all-->
<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery('.lightbox').lightbox();
  });
</script>
</body>
</html>
