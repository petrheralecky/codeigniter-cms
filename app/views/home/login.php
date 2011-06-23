<p>&nbsp; </p>
<h2>Přihlášení do administrace</h2>
<? $f->start() ?>
<table>
	<tr><td>Login:</td><td><? $f->_("<input name='login' />"); ?></td></tr>
	<tr><td>Heslo:</td><td><? $f->_("<input type='password' name='password' />"); ?></td></tr>
	<tr><td>&nbsp;</td><td><? $f->_("<input type='submit' name='ok' value='Přihlásit se' />"); ?></td></tr>
</table>
<? $f->stop() ?>
<p>&nbsp; </p>