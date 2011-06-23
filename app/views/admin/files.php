<h1>Ke stažení</h1>

<div class="center">
<fieldset>
	<a href="<?=BASE?>admin/files" class="clean">vyčistit formulář</a>
<?$f->start()?>
	<table>
		<tr><td>Název:</td><td><?$f->_('<input name="file" />',1)?></td></tr>
		<tr><td>Popisek:</td><td><?$f->_('<input name="file_title" />')?>
				<span class="m-help _file_title"><span>Zobrazí se při najetí na odkaz</span></span></td></tr>
		<? if ($edit): ?>
			<tr><td>Umístění souboru: </td><td><?$f->_('<input type="text" name="file_url" readonly />')?>
				<span class="m-help _file_url"><span>Název či umístění dokumentu nelze měnit. Lze však soubor smazat a znovu nahrát</span></span></td></tr>
		<? else: ?>
			<tr><td><?$f->_('<input type="radio" name="file_type" value="0" id="local" class="auto-width" checked="checked" />',1)?>
					<label for="local">Nahrát soubor:</label></td>
				<td><?$f->_('<input type="file" name="uploaded_file" />')?></td></tr>
			<tr><td><?$f->_('<input type="radio" name="file_type" value="1" id="url" class="auto-width" />',1)?>
					<label for="url">Externí soubor:</label></td>
				<td><?$f->_('<input type="text" name="file_url"/>',0,'www')?>
				<span class="m-help _file_url"><span>zadejte hrl externího souboru ve tvaru "www.stranka.cz/dokument.pdf"</span></span></td></tr>
		<? endif; ?>

	<tr><td colspan="2"><?$f->_('<input type="submit" value="uložit" name="ok" />',1)?></td></tr>
	</table>
<?$f->stop()?><br />
</fieldset><br />
</div>

<div class="left">
<table class="table-br">
	<? foreach($items as $p): ?>
		<tr>
			<td><a href="<?=BASE?>admin/files/<?=$p['id']?>" title="<?=$p['file_title']?>"><?=$p['file']?></a></td>
			<td><a href="<?=BASE?>admin/files/<?=$p['id']?>" class="edit" title="upravit">✎</a>
			<a href="<?=BASE?>admin/del/files/<?=$p['id']?>" onclick="return confirm('opravdu smazat dokument?');" class="del" title="smazat">✗</a></td>
		</tr>
	<? endforeach; ?>
</table>
</div>

