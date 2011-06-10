<h1>Hlavní nastavení</h1>
<p>Právě se nacházíte v administraci. Pokud je Hlavní nastavení v pořádku, pokračujte administrací podstránek, referencí či některé z entit
(aktuality, odazy, ke stažení). Vpravo se můžete odhlásit nebo si pročíst manuál k této administraci</p>


<fieldset id="main_fieldset">
	<? $f->start(); ?>
	<table>
		
		<tr><td width="100">E-mail</td>
			<td><? $f->_('<input name="email" />',1,"email"); ?>
			<span class="m-help _email"><span>Na tento mail vám budou docházet dotazy zákazníků</span></span></td></tr>
		
		<tr><td>Zápatí</td><td>
			<a href="#" onclick="$('#footer-div').show('normal'); $('#footer-a').hide(); return false;" id="footer-a">upravit</a>
			<div class="none" id="footer-div"><? $f->_('<textarea id="footer-area" class="mce wider" name="footer"></textarea>',1); ?>
			<? $f->_('<input type="checkbox" name="footer_webmaster" class="auto-width" />'); ?> Ponechat v zápatí odkaz na webmastera</div>
		</td></tr>

		<tr><td>&nbsp;</td><td><? $f->_('<input type="submit" value="Uložit" />'); ?></td></tr>

	</table>
	<? $f->stop(); ?>
</fieldset>



<ul>
	<li><a href="https://www.google.com/analytics/">Statistiky Google Analytics</a> <br />
		login: login  heslo: heslo</li>
</ul>
<br /><br />

