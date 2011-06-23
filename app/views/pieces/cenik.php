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