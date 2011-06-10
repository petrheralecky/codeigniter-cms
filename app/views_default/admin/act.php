<h1>Aktuality</h1>

<div class="center">
<fieldset>
	<a href="<?=BASE?>admin/act" class="clean">vyčistit formulář</a>
<? $f->start(); ?>
<table>
	<tr><td>Nadpis:</td><td><? $f->_('<input name="act" />',1); ?>
		<span class="m-help _act"><span>Nedělejte v textu znovu nadpis!</span></span></td></tr>
	<tr><td>Datum vložení: </td><td><? $f->_('<input name="created" class="date" value="'.date("Y-m-d").'" />',1,"date"); ?>
			<span class="m-help _created"><span>Do tohoto data bude aktualita neaktivní</span></span></td></tr>
	<tr><td>Datum smazání:</td><td><? $f->_('<input name="expire" class="date" value="'.date("Y-m-d",time()+3600*24*365).'" />',1,"date"); ?>
			<span class="m-help _expire"><span>Od tohoto data se aktualita stane neaktivní.
					Dá se použít jako dočasné zneaktivnění aktuality</span></span></td></tr>
	<tr><td>Vložit obrázek:</td><td><? $f->_('<input type="file" name="img" />'); ?>
			<? if(!empty($item['id']) && file_exists("www/photos/act{$item['id']}min.jpg")): ?>
				<a href="<?=BASE?>admin/del/img/<?=$item['id']?>/act" class="del" title="smazat"
				   onclick="return confirm('opravdu smazat obrázek? \n(změny ve formuláři se ztratí)');">✗</a>
				 <span class="m-help"><span><img src=<?=PATH."photos/act{$item['id']}min.jpg"?> alt=""
						class="img-min" /></span></span>
			<? else: ?>
				<span class="m-help"><span>nepovinné</span></span>
			<? endif; ?></td></tr>
	<tr><td style="vertical-align: top;  padding-top: 14px;">Zkrácený text:</td><td>
			<label><? $f->_('<input type="radio" checked="checked" class="auto-width" name="short_text_copy" value="1" />'); ?> &nbsp; Použít úryvek (do 120 písmen) z plného textu</label><br />
			<label><? $f->_('<input type="radio" class="auto-width" name="short_text_copy" value="0" />'); ?> &nbsp; Vlastní text</label><br />
			<div class="short_text"><? $f->_('<textarea name="short_text"></textarea>'); ?>
				<span class="m-help _short_text"><span>Krátký a výstižný popis zobrazujícího se už ve výpise.
					Může se totiž objevit ve vyhledávačích kde by měl zaujmout zákazníka.<br /> (Může zůstat také prázdný)
					</span></span></div></td></tr>
	<tr><td>Plný text:</td><td><? $f->_('<textarea name="text" class="mce wider" ></textarea>'); ?></td></tr>
	<tr><td>&nbsp;</td><td><? $f->_('<input type="submit" value="uložit aktualitu" name="ok" />'); ?></td></tr>
</table>
<? $f->stop(); ?>
</fieldset>
</div>

<div class="left">
	<table class="table-br">
		<? foreach($items as $i): ?>
			<tr>
				<td><a href="<?=BASE?>admin/act/<?=$i['id']?>"><?=$i['act']?></a></td>
				<td><a href="<?=BASE?>admin/act/<?=$i['id']?>" class="edit" title="upravit">✎</a>
				<a href="<?=BASE?>admin/del/act/<?=$i['id']?>" class="del" title="smazat" onclick="return confirm('opravdu trvale smazat?');" >✗</a>
				</td>
			</tr>
		<? endforeach; ?>
	</table>
</div>