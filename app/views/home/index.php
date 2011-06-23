

	<h1>Nadpis první úrovně</h1>
	<p>
		Vítejte na stránkách firmy ACER Herálecký - tepelné izolace, zateplení budov fasád, zvukové izolace a jiné..
	</p>
	<p>
		Nabízíme Vám již od roku 1991 na českém trhu výrobek Climatizer Plus, který je jedním z nejkvalitnějších izolačních materiálu s ojedinělou technologií aplikace.
		Aplikace prováděná naší firmou, Vám zaručuje kvalitní a přitom levnou aplikaci.
	</p>
	<h2>Online kalkulačka</h2>
	<p>
		Pomocí naší kalkulačky můžete spočítat odhadovanou cenu vaší zakázky. Dále tyto výsledky můžete odeslat pomocí
		objednávkového formuláře a my vás budeme kontaktovat.
	</p>
	<div id="kalkulacka">

		<div id="off-javascript">Pro používání webové kalkulačky musíte mít zapnut javascript</div>



	<?php for($i=1;$i<=5;$i++): ?>

	<div class="in" id="r<?=$i?>">
	<p>
		<? if($i>1): ?>
		<a href="<?=$i?>" class="delete">x</a>
		<? endif; ?>
		<strong><?=$i?>. rozměr</strong> &nbsp;
		<span class="smaller znasobit">(<a href="<?=$i?>">znásobit</a>)</span>
		<span class="znasobit-input"><input type="text" class="s" name="nasobek<?=$i?>" value="1" /> krát
			<span class="help"><span>Při zateplování více identických ploch můžete rozměr roznásobit</span></span></span>

	</p>
	<hr />
	<div class="vysledek">
	<table >
		<tr>
			<td width="210">Objem při tloušťce <span class="k-tloustka">20</span>cm:  </td>
			<td class="tar k-obem">0</td><td>m<span class="index-up">3</span></td>
		</tr>
		<tr>
			<td>Objemová hmotnost:  </td>
			<td class="tar k-ro">0</td><td>kg/m<span class="index-up">3</span></td>
		</tr>
		<tr>
			<td>Cena za 1m<span class="index-up">3</span>:  </td>
			<td class="tar k-kubik">1200</td><td>kč</td>
		</tr>
		<tr>
			<td>Cena celkem:</td>
			<td class="tar k-rozmer">12000</td><td>kč</td>
		</tr>
	</table>

	</div> <!-- vysledek -->

	<table>
		<tr>
			<td width="150" class="bigger">Izolovaná plocha</td>
			<td><input class="s" type="text" name="plocha<?=$i?>" /> m<span class="index-up">2</span></td>
		</tr>
		<tr>
			<td>Tloušťka izolace
				<span class="help"><span>Minimální vrstva je 10cm.<br />
						Doporučená vrstva izolace střechy je <strong>25cm</strong>. <br />
						(Pasivní domy mívají až 40cm)</span></span><br />
			</td>
			<td><input class="s" type="text" name="tloustka<?=$i?>" placeholder="10" /> cm</td>
		</tr>
		<tr>
			<td colspan="2">
				<label><input type="radio" name="typ<?=$i?>" value="1" class="radio" checked="checked" /> volné foukání
					<span class="help"><span>suchá aplikace materiálu<br /> (max. úhel 30°)</span></span></label><br />
				<label><input type="radio" name="typ<?=$i?>" value="2" class="radio" /> uzavřená konstrukce</label>
					<span class="help"><span>materiál vyplní obsah duté konstrukce. (trámové stropy, střešní konstrukce)</span></span><br />

				<span class="konstrukce">
					<label><input type="radio" name="kons<?=$i?>" value="1" class="radio" checked="checked" /> vodorovná</label>
					<label><input type="radio" name="kons<?=$i?>" value="2" class="radio" /> do 30°</label><br />
					<label><input type="radio" name="kons<?=$i?>" value="3" class="radio" /> do 45°</label>
					<label><input type="radio" name="kons<?=$i?>" value="4" class="radio" /> 45° - 90°</label><br />
				</span>
			</td>
		</tr>
	</table>
	<div class="placeholder"></div>

	</div> <!-- in -->

	<?php endfor; ?>

	<a href="#" class="in dalsi"><span>+</span> další rozměr</a>

	<div class="in celkem">
		<p><strong>Celkem:</strong></p>
		<hr />
		<table>
		<!--<tr>
			<td colspan="2">Poznámka s nějakým podrobnějším textem</td>
		</tr>-->
		<tr id="k-manipulacni-tr">
			<td>Manipulační poplatek: <span class="help"><span>U menších zakázek (do 12000kč)
						účtujeme úměrný manipulační poplatek na pokrytí dopravy atp.</span></span></td>
			<td class="tar" id="k-manipulacni">1000</td><td>kč</td>
		</tr>
		<tr>
			<td>Cena celkem:
			<span class="help"><span>Cena je včetně práce.</span></span></td>
			<td class="tar"><strong id="k-celkem">12000</strong></td><td>kč</td>
		</tr>
		<tr>
			<td width="212">Cena celkem s DPH <input type="text" class="s" name="k-dph" value="10" />%:  </td>
			<td class="tar"><strong id="k-celkem-dph">12800</strong></td><td>kč</td>
		</tr>
	</table>
	</div>
	<div class="contact">
		<p><br /><strong>Nezávazná objednávka:</strong></p>
	<table>
		<tr><td>Jméno: </td><td><input tabindex="10" type="text" name="name" /></td>
			<td rowspan="4" class="textlabel">Text:</td>
			<td rowspan="4"><textarea tabindex="14" cols="" rows="" name="text" class="texttext"></textarea></td></tr>
		<tr><td>Adresa: </td><td><input tabindex="11" type="text" name="adress" /></td></tr>
		<tr><td>E-mail: </td><td><input tabindex="12" type="text" name="email" /></td></tr>
		<tr><td>Tel: </td><td><input tabindex="13" type="text" name="tel" /></td></tr>
		<tr><td>&nbsp;</td><td colspan="2">
				<input tabindex="16" type="submit" name="ok-calc" value="Zaslat nezávaznou poptávku" class="sub" /></td>
			<td valign="top"><input tabindex="15" type="checkbox" name="with" checked="checked" class="auto-width" /> Přiložit vypočítané výsledky</td></tr>
	</table>
	</div>
	<div class="placeholder"></div>
	</div> <!-- kalkulacka -->

	<p>&nbsp;</p>
	<p>&nbsp;</p>

	<h2>Orientační ceník</h2>

	<script type="text/javascript">
	var typy = Array(
		'Volné vodorovné foukání',
		'Objemové plnění do vodorovné uzavřené dutiny',
		'Konstrukce - sklon do 30°',
		'Konstrukce - sklon do 45°',
		'Konstrukce - sklon od 45° do 90°'
	);
	document.write('<table  class="cenik-table">');
	document.write('<tr><th width="190">Druh aplikace<br /> materiálu</th><th>Tloušťka aplikace [cm]</th>\n\
		<th>Objemová hmotnost [kg/m<span class="index-up">3</span>]</th>\n\
		<th width="150">Cena za m<span class="index-up">3</span> [Kč]</th></tr>');
	for(var c in cena){
		document.write('<tr class="tb"><td rowspan='+hodnot+'>'+typy[c]+'</td>');
		for(var r in cena[c]){
			var price = Math.round(v_kubik(cena[c][r]['prum'],c)/10)*10;
			var ro = Math.round(v_ro(price));
			if(r==0){
				document.write('<td>10 - '+cena[c][r]['do']+'</td><td>'+ro+'</td><td>'+price+'</td></tr>');
			}else if(r == hodnot-1){
				document.write('<tr><td>'+cena[c][r]['od']+' - ...</td><td>'+ro+'</td><td>'+price+'</td></tr>');
			}else{
				document.write('<tr><td>'+cena[c][r]['od']+' - '+cena[c][r]['do']+'</td><td>'+ro+'</td><td>'+price+'</td></tr>');
			}
		}
		//document.write('</tr>');
	}

	document.write('</table>');
	</script>

	<a href="#head">nahoru</a>