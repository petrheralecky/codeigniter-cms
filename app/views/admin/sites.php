<h1>Podstránky</h1>

<div class="center">
<fieldset>
	<a href="<?=BASE?>admin/sites" class="clean">vyčistit formulář</a>
	<?$f->start()?>
	<table>
		<tr><td width="130">Typ podstránky:</td><td><?$f->_('<select class="t-sites" name="t_sites" >',t_sites())?> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;
				pořadové číslo: &nbsp; <?$f->_('<input name="order_sites" style="width: 29px;">',1,'order')?>
				<span class="m-help _order_sites"><span>pro slouží ke změně pořadí stránek</span></span>
			</td></tr>
		<tr><td>Titulek:</td><td><?$f->_('<input name="title" />',1)?>
				<span class="m-help _title"><span>tento název se zobrazí na liště</span></span></td></tr>
		<tr class="f-subtitle" <?=((!empty($item['t_sites']) && $item['t_sites']==2)||(!empty($_POST['t_sites']) && $_POST['t_sites']==2)?"style='display: none;' ":"")?>">
			<td>Pod-titulek:</td><td><?$f->_('<input name="subtitle" />')?>
				<span class="m-help _subtitle"><span>tento název se zobrazí na liště</span></span></td></tr>
		<tr><td>Titulek v záhlaví (seo):</td>
			<td><?$f->_('<input name="title_seo" />')?>
				<span class="m-help _title_seo"><span>titulek v záhlaví dokumentu a v adrese (ideálně 5 klíčových slov). <br />Prázdné = titulek+podtitulek</span></span></td></tr>
		<tr><td colspan="2"><?$f->_('<textarea name="content" class="mce" style="  height: 400px;"></textarea>',1)?></td></tr>
		<tr><td>Description (seo):</td><td><?$f->_('<textarea name="description" style="float: left;"></textarea>')?>
				<span class="m-help _description"><span>Popis webové stránky pro vyhledávače. 3-5 vět obsahující klíčová slova.
					(nepřehlcovat! je dobré když se každá stránka zaměří jen na některá klíčová slova)<br />Prázdné = obsah stránky</span></span></td></tr>
		<tr><td>&nbsp;</td><td><?$f->_('<input type="submit" value="Uložit stránku" name="ok" />',1)?></td></tr>
	</table>
	<?$f->stop()?><br />
</fieldset><br />
</div>

<div class="left">
<table class="table-br">
	<? $type=0; foreach($items as $p): ?>
		<? if($type!=$p['t_sites']): $type=$p['t_sites']; ?>
		<tr><th colspan="2" style="padding-top: 10px;"><em><?=t_sites($type)?></em></th></tr>
		<? endif; ?>
		<tr>
			<td><a href="<?=BASE?>admin/sites/<?=$p['id']?>"><?=$p['title']?></a></td>
			<td><a href="<?=BASE?>admin/sites/<?=$p['id']?>" class="edit" title="upravit">✎</a>
			<? if($p['id']!=1 && $p['id']!=2): ?>
				<a href="<?=BASE?>admin/del/sites/<?=$p['id']?>" class="del" title="smazat"onclick="return confirm('opravdu smazat tuto podstránku??');" >✗</a>
			<? endif; ?>
			</td>
		</tr>
	<? endforeach; ?>
</table>

	<? //if(!empty($item) && ($item['id']==2 || $item['id']==1)): ?>
	<div class="promenne">
	<h3>Proměnné: <span class="m-help"><span>Pokud jednu z proměnných použijete kdekoliv v textu, bude nahrazena blokem hotového kódu</span></span></h3>
	<table>
		<tr><td>##aktuality##</td></tr>
		<tr><td>##kalkulacka##</td></tr>
		<tr><td>##cenik##</td></tr>
		<tr><td>##mapa##</td></tr>
		<tr><td>##kontakt-form##</td></tr>
	</table>

	</div>

</div>

