<?php
error_reporting(E_ALL ^ E_NOTICE);
define("PATH","");
?>

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
<a href="#" class="logo"></a>
<div class="logo-text"><a href="#">Nadpis webu</a></div>
<ul class="lista">
	<li><a href="#">Home</a></li>
	<li><a href="#">Kontakt</a></li>
</ul>
</div><!--head-->

	<!-- LEFT -->

<div class="left">

<div class="box-top">
	<a href="#" class="plus" id="b1"></a>
	Nadpis boxu
</div>
<div class="box-out" id="box-b1">
<div class="box">
	text boxu
	<ul>
		<li>první bod boxu</li>
		<li>druhý</li>
	</ul>
</div>
</div>

</div><!--left-->

	<!-- MAIN -->

<div class="main">

<? //Tools::printFlash(); ?>
	<noscript>
		<div class="flash-alert">
			Pro správnou funkci těchto stránek si musíte zapnout javascript!
		</div>
	</noscript>

<h1>Nadpis h1</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sed luctus erat. Duis vel ligula vel nulla porttitor tempor. Cras tincidunt, mauris eu tempor fringilla, augue dui accumsan eros, a suscipit nunc velit eu sapien. Donec elementum condimentum <a href="#">lectus</a> at blandit. Donec eget mauris diam, sed pharetra nunc. Quisque nulla lacus, fringilla vel aliquam non, gravida sagittis dui.</p>
<p>Pellentesque consequat interdum feugiat. Cras cursus, urna vel tempus eleifend, nulla tortor interdum leo, vel tincidunt sem tellus suscipit tellus. Etiam ac tellus at justo tempor congue. Nam diam tortor, tincidunt ut pharetra a, ultrices consectetur neque. Integer magna odio, gravida at fringilla non, pharetra ut tellus. In consectetur malesuada erat vel blandit. Suspendisse potenti.</p>
<h2>Nadpis h2</h2>
<p>Curabitur ante lacus, porttitor vel tincidunt lacinia, sagittis at lectus. Sed tempus condimentum erat. In justo dui, tristique eu tincidunt quis, sollicitudin vitae leo. Duis ac quam sed libero rutrum tempor interdum et elit. Ut in elit ac est faucibus molestie. Duis lorem elit, aliquam in molestie eget, tristique eget odio. Aliquam metus est, iaculis a tincidunt nec, dignissim id est. Mauris commodo, erat a volutpat faucibus, neque quam eleifend neque, in vestibulum lorem mi vitae neque. Nulla mattis, ante ut tempus mollis, erat est mollis diam, sed tincidunt enim est ut urna. Ut vitae eros lorem, et dignissim velit. Suspendisse potenti. In porta molestie nibh, ut elementum urna tempus sed. Suspendisse at ornare justo. Vivamus facilisis lobortis odio ut varius. Ut tellus est, adipiscing vitae placerat nec, convallis in felis. Praesent pharetra sem nec metus elementum vel accumsan lorem auctor. Nam vehicula pharetra fringilla.</p>
<p>&nbsp;</p>
	<div class="clear"></div> 

	<div><form method="post" name="contact_form" action="" enctype="multipart/form-data" class="m-form m-form-contact_form" id='kontakt-form'><div id="form-loading-contact_form" class="form-loading"><div><span>Zpracovávám...</span></div></div>	<h2>Kontaktní formulář:</h2>
	<table class="my-table">
		<tr><th>Jméno/firma:</th><td><input class=" required" value="" onBlur=" control_input('jmeno','contact_form',0,'/_def_ci/ajax/form','text');" onKeyUp=" control_input('jmeno','contact_form',1,'/_def_ci/ajax/form','text');" type="text" name="jmeno" />
</td></tr>
		<tr><th>Kontakt:</th><td><input class=" optional" value="" onBlur=" control_input('kontakt','contact_form',0,'/_def_ci/ajax/form','text');" onKeyUp=" control_input('kontakt','contact_form',1,'/_def_ci/ajax/form','text');" type="text" name="kontakt" />
</td></tr>
		<tr><td colspan="2"><textarea class=" required" onBlur=" control_input('vzkaz','contact_form',0,'/_def_ci/ajax/form','textarea');" onKeyUp=" control_input('vzkaz','contact_form',1,'/_def_ci/ajax/form','textarea');" name="vzkaz"></textarea>
</td></tr>
		<tr><td colspan="2"><input onClick=" document.getElementById('form-loading-contact_form').style.display='block';" class="sub" type="submit" name="ok_vzkaz" value="Odeslat" /><noscript><img class="captcha-img" src="/_def_ci/www/img/form/captcha.png" alt="" /><br class="captcha-br" /><input value="" onBlur=" control_input('captcha','contact_form',0,'/_def_ci/ajax/form','text');" onKeyUp=" control_input('captcha','contact_form',1,'/_def_ci/ajax/form','text');" type="text" name="captcha" class="captcha-input required" />
</noscript><br class="captcha-br" /><script type="text/javascript">
						document.write('<input type="hidden" name="captcha" value="tq' + 'pd" />');
				  </script> </td></tr>
	</table>
</form></div>
<h1>Kontakt</h1>
<table id="kontakt-table" class="my-table">
<tbody>
<tr>
<th class="smaller">Kontaktn&iacute; osoba:</th>
<td>Oldřich Her&aacute;leck&yacute;</td>
</tr>
<tr>
<th valign="top">Telefon:</th>
<td>777 675 123</td>
</tr>
<tr>
<th>E-mail:</th>
<td>acerheralecky@volny.cz</td>
</tr>
<tr>
<th colspan="2">&nbsp;</th>
</tr>
<tr>
<th valign="top">Adresa:</th>
<td valign="top">Horn&iacute; Lhota 167<br />76323 Zl&iacute;n</td>
</tr>
<tr>
<th valign="top">IČO:</th>
<td>27759431</td>
</tr>
<tr>
<th>Č&iacute;slo &uacute;čtu:</th>
<td class="smaller">43-1217660207/0100</td>
</tr>
</tbody>
</table>
<div><div class="map" id="mojeMapa"></div>

<script type="text/javascript" src="http://api.mapy.cz/main?key=067d05b14&ver=3&encoding=utf-8"></script>
<script type="text/javascript">
    if(SZN.isSupported){
      var mapa = new SZN.MapEngine(document.getElementById('mojeMapa'));
      // nastaveni stupne priblizeni
      mapa.zoomSet(9);
	  mapa.mouseSet(7); // interakce

	  //Loc: 49°9'6.433"N, 17°47'57.316"E
	  var ff = mapa.wgsToPP('49d9m6.43sN','17d47m57.32sE');

      // a nyni nastavime novy stred
      mapa.setCenter(ff.x,ff.y);

	  // vytvorime znacku
      var mark = mapa.makeMark('center','Acer Herálecký');

      // umistime znacku do mapy na drive vypoctene souradnice
      mapa.addMark(ff.x,ff.y,mark);
    }


</script></div>
<script type="text/javascript">
$(document).ready(function(){
  $('[name="jmeno"]').focus();
})
</script>
	<div class="clear"></div>

	<div class="clear"></div>
</div><!--main-->

								<!-- FOOTER -->
<div class="footer">
	<? if($settings['footer_webmaster']): ?>
	<a class="webmaster" href="http://melou.cz" title="webové aplikace | www stránky">web::design::melou.cz</a>
	<? endif; ?>
	<?= $settings['footer'] ?>

</div><!--footer-->
<div class="vv v2"></div><div class="vv v3"></div><div class="vv v1"></div><div class="vv v0"></div><div class="vv v4"></div>

</div><!--all-->

<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery('.lightbox').lightbox();
  });
</script>
</body>
</html>
