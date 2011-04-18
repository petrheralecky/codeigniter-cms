<style type="text/css">
	body { font: 10pt/1.4em Arial,verdana; }

</style>


<? foreach($items as $item): ?>

	<? if($item['company']): ?>
		<?=$item['company']?><br />
	<? else: ?>
		<?=$item['name']?> <?=$item['surname']?><br />
	<? endif; ?>
		<?=$item['street']?><br />
		<?=$item['post']?> &nbsp; <?=$item['zip']?><br />
		<br />
		<br />
<? endforeach; ?>