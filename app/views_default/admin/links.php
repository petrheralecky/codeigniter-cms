<h1>Odkazy</h1>

<div class="center">
<fieldset>
	<a href="<?=BASE?>admin/links" class="clean">vyčistit formulář</a>
<?$f->start()?>
	<table>
		<tr><td width="100">Název:</td><td><?$f->_('<input name="link" />',1)?></td></tr>
		<tr><td>Popisek:</td><td><?$f->_('<input name="link_title" />')?>
			<span class="m-help _link_title"><span>zobrazí se v atributu title odkazu (zobrazuje se při najetí myší).<br /> Je nepovinný.</span></span></td></tr>
		<tr><td>URL:</td><td><?$f->_('<input name="url" />',1,"www")?></td></tr>

	<tr><td colspan="2"><?$f->_('<input type="submit" value="uložit" name="ok" />',1)?></td></tr>
	</table>
<?$f->stop()?><br />
</fieldset><br />
</div>

<div class="left">
<table class="table-br">
	<? foreach($items as $p): ?>
		<tr>
			<td><a href="<?=BASE?>admin/links/<?=$p['id']?>" title="<?=$p['link_title']?>"><?=$p['link']?></a></td>
			<td><a href="<?=BASE?>admin/links/<?=$p['id']?>" class="edit" title="upravit">✎</a>
			<a href="<?=BASE?>admin/del/links/<?=$p['id']?>" onclick="return confirm('opravdu smazat odkaz?');" class="del" title="smazat">✗</a></td>
		</tr>
	<? endforeach; ?>
</table>
</div>

