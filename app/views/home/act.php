
<? if(file_exists('www/photos/act'.$item['id'].'a.jpg')): ?>
	<a href="<?=PATH.'photos/act'.$item['id'].'full.jpg'?>" class="lightbox img-box">
		<img src="<?=PATH.'photos/act'.$item['id'].'a.jpg'?>" alt="<?=$item['act']?>" />
	</a>
<? endif; ?>

<div class="act-view">
	
	<h1><?=$item['act']?></h1>

	

	<p class="date">vloženo: <?=$item['f_created']?></p>

	<? if(!$item['short_text_copy'] && $item['short_text']): ?>
	<p><em><?=$item['short_text']?></em></p>
	<? endif; ?>

	<?=$item['text']?>


	<a href="<?=$this->uri->sef('home/index/8')?>" class="zpet">&lt; Hlavní strana</a>


	
</div>