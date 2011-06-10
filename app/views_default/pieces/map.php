<div class="map" id="mojeMapa"></div>

<script type="text/javascript" src="http://api.mapy.cz/main?key=067d05b14&ver=3&encoding=utf-8"></script>
<script type="text/javascript">// <![CDATA[
    if(SZN.isSupported){
      var mapa = new SZN.MapEngine(document.getElementById('mojeMapa'));
      // nastaveni stupne priblizeni
      mapa.zoomSet(9);
	  mapa.mouseSet(7); // interakce

	  //Loc: 49°9'6.433"N, 17°47'57.316"E
	  var ff = mapa.wgsToPP('49d9m6.43sN','17d47m57.32sE');

      // a nyni nastavime novy stred
      mapa.setCenter(ff.x,ff.y);

	  // vytvorime znacku
      var mark = mapa.makeMark('center','Acer Herálecký');

      // umistime znacku do mapy na drive vypoctene souradnice
      mapa.addMark(ff.x,ff.y,mark);
    }


// ]]>
</script>