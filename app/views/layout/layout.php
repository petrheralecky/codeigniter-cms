<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="foukaná, tepelná, izolace, střech, climatizer, zateplení, herálecký" />
<meta name="description" content="<?=$description?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="all" />
<meta name="author" content="Acer Herálecký" />
<link rel="shortcut icon" href="<?=PATH?>img/favicon.gif" />
<link rel="icon" type="image/gif" href="<?=PATH?>img/favicon.gif" />
<link rel="stylesheet" type="text/css" href="<?=PATH?>css/styles.css" />
<link rel="stylesheet" type="text/css" href="<?=PATH?>css/form.css" />
<link rel="stylesheet" type="text/css" href="<?=PATH?>css/rotator.css" />
<link rel="stylesheet" type="text/css" href="<?=PATH?>js/lightbox/themes/white-green/jquery.lightbox.css" />
<script src='<?=PATH?>js/jquery.js' type='text/javascript'></script>
<script src='<?=PATH?>js/form.js' type='text/javascript'></script>
<script src='<?=PATH?>js/melou.js' type='text/javascript'></script>
<script src='<?=PATH?>js/js.js' type='text/javascript'></script>
<script src="<?=PATH?>js/lightbox/jquery.lightbox.min.js" type="text/javascript"></script>
<script src='<?=PATH?>js/analytics.js' type='text/javascript'></script>
<title><?=$title?></title>
</head>

<body>
<div class="all">

								<!-- HEAD -->
<div class="head">
<a href="<?=$this->uri->sef("home/index/1")?>" class="logo"></a>
<div class="logo-text"><a href="<?=$this->uri->sef("home/index/1")?>">Nadpis webu</a></div>
<ul class="lista">
	<? 
	foreach($sites as $s){
		echo "<li><a ";
		if(!empty($uri_array[3]) && $s['id']==$uri_array[3] && $action=="index"){ echo "class='sel' "; }
		echo "href=".$this->uri->sef("home/site/".$s['id']).">";
		echo "<span>".$s['title']."</span></a></li>";
	}
	?>
</ul>
</div><!--head-->

	<!-- LEFT -->

<div class="left">

<? $this->load->view("layout/left.php"); ?>

</div><!--left-->

	<!-- MAIN -->

<div class="main_out">
<div class="main">

	<?
	if($controller=="basket" && !in_array($action,array('orders','password','forgot'))){
		$this->load->view("pieces/basket-butt.php");
	}
	?>

<? Tools::printFlash(); ?>
	<noscript>
		<div class="flash-alert">
			Pro správnou funkci těchto stránek si musíte zapnout javascript!
		</div>
	</noscript>
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

	<div class="clear"></div>
</div><!--main-->
</div><!--main_out-->

								<!-- FOOTER -->
<div class="footer">
	<? if($settings['footer_webmaster']): ?>
	<a class="webmaster" href="http://melou.cz" title="webové aplikace | www stránky">web::design::melou.cz</a>
	<? endif; ?>
	<?= $settings['footer'] ?>
	
</div><!--footer-->
<div class="vv v2"></div><div class="vv v3"></div><div class="vv v1"></div><div class="vv v0"></div><div class="vv v4"></div>

</div><!--all-->
<a href="<?=$this->uri->sef("admin")?>" class="admin-link">&nbsp;</a>


<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery('.lightbox').lightbox();
  });
</script>
</body>
</html>
