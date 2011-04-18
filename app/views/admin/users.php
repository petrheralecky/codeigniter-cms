<h1>Uživatelé</h1>

<div class="center">

	<fieldset id="f_produkt">
		<? $f->start(); ?>
		<? if(empty($form_user['login'])): ?>
		<h2>Nový administrátor</h2>
		<? else: ?>
		<h2>Upravit údaje administrátora </h2>
		<? endif; ?>
		<table>
			<tr><td>Login:</td><td><? $f->_('<input type="text" name="login" />',1); ?></td></tr>
			<tr><td>Heslo:</td><td><? $f->_('<input type="password" name="password" />',0,"delkaHesla"); ?></td></tr>
			<tr><td>Znovu:</td><td><? $f->_('<input type="password" name="password2" />',0,"rePasswordEmpty"); ?></td></tr>
			<tr><td>&nbsp;</td><td><? $f->_('<input type="submit" value="Uložit" />'); ?></td></tr>
		
		</table>
		
		<? $f->stop(); ?>
	</fieldset>
	
	<br />
</div>

<div class="left">
	<table width="100%" class="table-br">
		<? foreach($users as $p): ?>
		<tr>
			<td><a href="<?=BASE?>admin/users/<?=$p['id']?>"><?=$p['login']?> </a></td>
			<td><a href="<?=BASE?>admin/users/<?=$p['id']?>" class="edit" title="editace">✎</a>
				<a href="<?=BASE?>admin/del/users/<?=$p['id']?>" onclick="return confirm('opravdu smazat tohoto uživatele?');" class="del" title="smazat">✗</a></td>
		</tr>
		<? endforeach; ?>
	</table>
</div>