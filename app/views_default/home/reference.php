<div class="reference">
	<div class="nav">
		<? if(!empty($ref['before'])): ?>
		<a href="<?=$this->uri->sef('home/reference/'.$ref['before'])?>">&lt; Předchozí</a> |
		<? endif; ?>
		<a href="<?=$this->uri->sef('home/index/14')?>">Přehled referencí</a>
		<? if(!empty($ref['next'])): ?>
		| <a href="<?=$this->uri->sef('home/reference/'.$ref['next'])?>">Následující &gt;</a>
		<? endif; ?>
	</div>
	<h1><?=$ref['title_reference']?></h1>

	<?=$ref['text_reference']?><br />

	<? if(file_exists('www/photos/ref'.$ref['id'].'-0a.jpg')): ?>
	<div class="gallery">
	<? 
	$celk = 0;
	$cont = "";
	for($i=0;$i<6;$i++){
		if(file_exists("www/photos/ref".$ref['id']."-".$i."min.jpg")){
			$celk++;
			$cont .= '<img src="'.PATH.'photos/ref'.$ref['id'].'-'.$i.'min.jpg" alt="" class="img"
				onclick="$(\'#g-img img\').attr(\'src\',\''.PATH.'photos/ref'.$ref['id'].'-'.$i.'a.jpg\')" />';
		}
	}
	if($celk > 1){
		echo $cont;
		echo "\n<hr />\n";
	}
	?>

	<div id="g-img">
	<img src="<?=PATH.'photos/ref'.$ref['id'].'-0a.jpg'?>"
		 alt="<?=$ref['title_reference']?>" />
	</div>

	</div>
	<? endif; ?>
</div>