<? if (count($acts) != 0): ?>
<div class="acts-top"></div>
<div class="acts">
<div class="acts-in">
	<!--<p><strong class="act-title">Aktuálně:</strong></p>-->
	<? foreach($acts as $a): ?>
	<div class="act <? if(!empty($a['text'])){ echo "with"; }?> <? if(empty($first)){ echo "first"; $first=true;}?>"
		 <? if(!empty($a['text'])): ?>
		 onclick="window.location.href='<?=$this->uri->sef('home/act/'.$a['id'])?>'"
		 <? endif; ?>
		 >
		<? if(file_exists('www/photos/act'.$a['id'].'min.jpg')): ?>
		<img src="<?=PATH?>photos/act<?=$a['id']?>min.jpg" alt="<?=$a['act']?>" />
		<? endif; ?>
		<p class="date"><?=$a['f_created']?></p>
		<h3><a <? if(!empty($a['text'])) echo "href='".$this->uri->sef('home/act/'.$a['id'])."'"?>><?=$a['act']?></a></h3>
		<p><?
		if($a['short_text_copy']){
			echo my_short_text($a['text'],120);
		}else{
			echo $a['short_text'];
		}	?>
			<? if(!empty($a['text'])): ?>
			<a href="#" class="link">číst více...</a>
			<? endif; ?>
		</p>
	</div>
	<? endforeach; ?>
</div>
</div>
<? else: ?>
<p>Aktuality jsou prázdné...</p>
<? endif; ?>