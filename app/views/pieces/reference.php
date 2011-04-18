
<div class="refs">
<? foreach($refs as $a): ?>
<div class="ref" onclick="window.location.href='<?=$this->uri->sef('home/reference/'.$a['id'])?>'">

	<div class="img">
	<? if(file_exists('www/photos/ref'.$a['id'].'-0b.jpg')): ?>
	<img src="<?=PATH?>photos/ref<?=$a['id']?>-0b.jpg" alt="<?=$a['title_reference']?>" />
	<? endif; ?>
	</div>

	<p><a href="<?=$this->uri->sef('home/reference/'.$a['id'])?>"><?=my_short_text($a['title_reference'],25)?></a></p>

	<p><?=my_short_text($a['text_reference'],100)?></p>

</div>
<? endforeach; ?>
</div>

