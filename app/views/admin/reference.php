<? if(!empty($chyba)): ?>
<div id="flash-alert">Pozor: u chybně vyplněného formuláře znovu vložte obrázky!</div>
<?endif;?>

<h1>Reference</h1>
<div class="center">

<fieldset>
	<a href="<?=BASE?>admin/reference" class="clean">vyčistit formulář</a>
<? $f->start(); ?>
	<table>
	<tr><td>Titulek:</td><td><? $f->_('<input type="text" name="title_reference" />',1); ?>
		<span class="m-help _title_reference"><span>Titulek bude zároveň jako nadpis. Nepsat do textu znovu nadpis!</span></span></td></tr>

	<tr><td>Text:</td><td colspan="2"><? $f->_('<textarea class="mce wider" name="text_reference"></textarea>'); ?></td></tr>

	<tr><td>Obrázky:
		<span class="m-help"><span>obrázek na 1. místě bude použit jako hlavní,
		proto dbejte aby tam byl, jinak se ve výpisu zobrazí jenom červený křížek</span></span>
		</td><td>
			<? for($i=0;$i<5;$i++): ?>
				<? $f->_('<input type="file" name="img_'.$i.'" />',0,"imageFile"); ?>&nbsp;
				<? if(!empty($item['id']) && file_exists("www/photos/ref{$item['id']}-{$i}min.jpg")): ?>
					<a href="<?=BASE?>admin/del/img/<?=$item['id']?>/ref/<?=$i?>" class="del" title="smazat"
					   onclick="return confirm('opravdu smazat obrázek? \n(změny ve formuláři se ztratí)');">✗</a>
					 <span class="m-help"><span><img src=<?=PATH."photos/ref{$item['id']}-{$i}min.jpg"?>
							class="img-min" /></span></span>
				<?endif;?><br />
			<? endfor; ?>

		</td></tr>
	<tr><td>pořadové číslo:</td><td><? $f->_('<input type="text" class="s" name="order_reference" />',1,'order'); ?></td></tr>
	<tr><td colspan="2"><? $f->_('<input type="submit" name="ok_settings" value="Uložit" class="sub" />'); ?></tr></tr>
	</table>
	
<? $f->stop(); ?>
</fieldset>
</div>

<div class="left">
	<table class="table-br">
		<? foreach($items as $i): ?>
			<tr>
				<td><a href="<?=BASE?>admin/reference/<?=$i['id']?>"><?=$i['title_reference']?></a></td>
				<td width="40"><a href="<?=BASE?>admin/reference/<?=$i['id']?>" class="edit" title="upravit">✎</a>
				<a href="<?=BASE?>admin/del/reference/<?=$i['id']?>" class="del" title="smazat"onclick="return confirm('opravdu trvale smazat?');" >✗</a>
				</td>
			</tr>
		<? endforeach; ?>
	</table>
</div>