<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function my_str2url($text)
{
	if (is_string($text))
	{
		$prevodni_tabulka = Array( ' - '=>'-', ' '=>'-', '"'=>"", "'"=>"","/"=>"", "\\"=>"","&"=>"-","?"=>"",","=>"",
			'ä'=>'a','Ä'=>'A','á'=>'a','Á'=>'A','à'=>'a','À'=>'A','ã'=>'a','Ã'=>'A','â'=>'a','Â'=>'A',	  'č'=>'c','Č'=>'C','ć'=>'c','Ć'=>'C','ď'=>'d','Ď'=>'D','ě'=>'e','Ě'=>'E','é'=>'e','É'=>'E','ë'=>'e','Ë'=>'E','è'=>'e','È'=>'E','ê'=>'e','Ê'=>'E','í'=>'i','Í'=>'I','ï'=>'i','Ï'=>'I','ì'=>'i','Ì'=>'I','î'=>'i','Î'=>'I','ľ'=>'l','Ľ'=>'L','ĺ'=>'l','Ĺ'=>'L','ń'=>'n','Ń'=>'N','ň'=>'n','Ň'=>'N','ñ'=>'n','Ñ'=>'N','ó'=>'o','Ó'=>'O','ö'=>'o','Ö'=>'O','ô'=>'o','Ô'=>'O','ò'=>'o','Ò'=>'O','õ'=>'o','Õ'=>'O','ő'=>'o','Ő'=>'O','ř'=>'r','Ř'=>'R','ŕ'=>'r','Ŕ'=>'R','š'=>'s','Š'=>'S','ś'=>'s','Ś'=>'S','ť'=>'t','Ť'=>'T','ú'=>'u','Ú'=>'U','ů'=>'u','Ů'=>'U','ü'=>'u','Ü'=>'U','ù'=>'u','Ù'=>'U','ũ'=>'u','Ũ'=>'U','û'=>'u','Û'=>'U','ý'=>'y','Ý'=>'Y','ž'=>'z','Ž'=>'Z','ź'=>'z','Ź'=>'Z'		);
		$result = strtolower(strtr($text, $prevodni_tabulka));
		$result = urlencode($result);
		return $result;
	}
	return "";
}

function my_short_text($text,$lenght=150)
{
	if (is_string($text))
	{
		$text = strip_tags($text);
		$result = substr($text,0,$lenght);
		if(strlen($result)==$lenght) $result.="...";

		return $result;
	}
	return "";
}

function my_rand_str($len){
	// generování náhodného textu
	$pool = 'abcdefghijkmnopqrstuvwxyz23456789ABCDEFGHJKLMNPRSTUVWXYZ';
	$text = '';
	for ($i=0; $i < $len; $i++) {
		$text .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
	}
	return $text;
}


//   typy

function _typy ($pole,$prvek,$url) {
	if($prvek===false){
		return $pole;
	}
	elseif(is_numeric($prvek)){
		if(!isset($pole[$prvek])) return false;
		if($url==1) return strtolower(strtr($pole[$prvek],
			" \xe1\xe4\xe8\xef\xe9\xec\xed\xb5\xe5\xf2\xf3\xf6\xf5\xf4\xf8\xe0\xb9\xbb\xfa\xf9\xfc\xfb\xfd\xbe\xc1\xc4\xc8\xcf\xc9\xcc\xcd\xa5\xc5\xd2\xd3\xd6\xd5\xd4\xd8\xc0\xa9\xab\xda\xd9\xdc\xdb\xdd\xae",
			"-aacdeeillnoooorrstuuuuyzAACDEEILLNOOOORRSTUUUUYZ"
		));
		else return $pole[$prvek];
	}else{
		foreach($pole as $i=>$d){
			if($d==$prvek) return $i;
		}
	}
}

function t_sites ($prvek=false,$url=0) {
	$names = array(
		1 => "Hlavní lišta",
		2 => "Důležité odkazy",
	);
	return _typy($names,$prvek,$url);

}

