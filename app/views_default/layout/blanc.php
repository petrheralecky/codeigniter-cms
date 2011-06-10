<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="outdoor, oblečení, boty, funkční, allseasons, obchod, shop, tepláky, kalhoty, mikiny" />
<meta name="description" content="description" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Author" content="Miroslav Hlavička - allseasons.cz" />
<meta name="Robots" content="index,follow" />

<link rel="shortcut icon" href="<?=PATH?>img/favicon.gif" />
<link rel="icon" type="image/gif" href="<?=PATH?>img/favicon.gif" />
<link rel="stylesheet" type="text/css" href="<?=PATH?>css/styles.css" />
<link rel="stylesheet" type="text/css" href="<?=PATH?>js/lightbox/themes/white-green/jquery.lightbox.css" />
<!--[if IE 6]><link rel="stylesheet" type="text/css" href="<?=PATH?>js/lightbox/themes/white-green/jquery.lightbox.ie6.css" /><![endif]-->
<!--[if lt ie 9]><link href="<?=PATH?>css/ie.css" type="text/css" rel="stylesheet" /><![endif]-->
<?php if(!strpos($_SERVER["HTTP_USER_AGENT"],"MSIE")){
	echo '<script type="text/javascript">explorer = false;</script>';
}else{
	echo '<script type="text/javascript">explorer = true;</script>';
	;//echo '<script type="text/javascript">alert("Bohužel používáte Internet Explorer, ve kterém stránky nebudou tak pěkné :(")</script>';
}?>

<script src='<?=PATH?>js/jquery.js' type='text/javascript'></script>
<script src='<?=PATH?>js/form.js' type='text/javascript'></script>
<script src='<?=PATH?>js/js.js' type='text/javascript'></script>
<script type="text/javascript" src="<?=PATH?>js/lightbox/jquery.lightbox.min.js"></script>

<style type="text/css">
	body { background: none }
</style>

<title>Allseasons</title>

</head>

	<body>

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

</body>
</html>
