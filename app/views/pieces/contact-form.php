<? $f->start("","id='kontakt-form'"); ?>
	<h2>Kontaktní formulář:</h2>
	<table class="my-table">
		<tr><th>Jméno/firma:</th><td><? $f->_('<input type="text" name="jmeno" />',1); ?></td></tr>
		<tr><th>Kontakt:</th><td><? $f->_('<input type="text" name="kontakt" />'); ?></td></tr>
		<tr><td colspan="2"><? $f->_('<textarea name="vzkaz"></textarea>',1); ?></td></tr>
		<tr><td colspan="2"><? $f->_('<input class="sub" type="submit" name="ok_vzkaz" value="Odeslat" />'); ?></td></tr>
	</table>
<? $f->stop(); ?>