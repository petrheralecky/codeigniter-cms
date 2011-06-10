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

	<? if (count($acts) != 0): ?>
	<div class="box-top">
		<a href="#" class="plus" id="b2"></a>
		Aktuality
	</div>
	<div class="box-out" id="box-b2">
	<div class="box">
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
	<? endif; ?>

	<? if(!empty($files)): ?>
	<div class="box-top">
		<a href="#" class="plus" id="b4"></a>
		Ke stažení
	</div>
	<div class="box-out" id="box-b4">
	<div class="box">
		<ul>
			<? foreach($files as $s): ?>
			<li><a href="<?=$s['f_url']?>" title="<?=$s['file_title']?>" target="_blanc"><?=$s['file']?></a></li>
			<? endforeach; ?>
		</ul>
	</div>
	</div>
	<? endif; ?>

	<? if(!empty($links)): ?>
	<div class="box-top">
		<a href="#" class="plus" id="b3"></a>
		Příbuzné weby
	</div>
	<div class="box-out" id="box-b3">
	<div class="box">
		<ul>
			<? foreach($links as $s): ?>
			<li><a href="<?=$s['url']?>" title="<?=$s['link_title']?>"><?=$s['link']?></a></li>
			<? endforeach; ?>
		</ul>
	</div>
	</div>
	<? endif; ?>

