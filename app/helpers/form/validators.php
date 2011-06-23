<?php
/*
 * FormValidators je objekt volaný z Form
 *
 * Method names are validators or filtres used in your application
 */

class FormValidators
{
    public $emsg = array(  // erorové hlášky
        'empty' => "Musíte vyplnit všechny povinné pole",
		'emptyCaptcha' => "Musíte správně opsat kontrolní text",

        'emptyEmail' => "Email musí být vyplněn", // hláška co přetíží required protože v názvu obsahuje "empty"
		'emptyPodminky' => "Musíte souhlasit s licenčními podmínkami",
		'emptyDoprava' => "Musíte vybrat způsob dopravy",

        'password' => "Špatně vyplněný login nebo heslo",
		'email' => "Chybně vyplněný E-mail",
        'tel' => "Chybně vyplněný Telefon",
		'psc' => "Chybně vyplněné PSČ",
		'ico' => "Chybně vyplněné IČO",
		'dic' => "Chybně vyplněné DIČ",
        'mobil' => "Chybně vyplněný Mobil",
		'date' => "Chybně vyplněné Datum",
		'www' => "Chybně vyplněná www adresa",
		'pohlavi' => "Musíte vybrat pohlaví",
		'rePasswordEmpty' => "Špatně opsané heslo", //using $_POST['password']
		'delkaHesla' => "Minimální délka hesla je 6 znaků",
		'oldPassword' => "Zadali jste špatně staré heslo",
		'ks' => "počet kusů musí být číslo",
		'emptyks' => "musíte vyplnit počet kusů",
		'cena' => "cena musí být číslo",
		'order' => "pořadí musí být číslo",
		'emptyadmin' => "pro pokračování musíte zaškrtnout políčko administrátor",

        'emptyFile' => "Musíte vložit soubor(y)",
        'fileError' => "Vložení souboru se nezdařilo (pravděpodobně moc velký soubor)",
        'imageFile' => "Vkládaný obrázek musí končit: .jpg/.gif/.png",
		'xlsFile' => "Vkládaný soubor musí mít příponu .xls",
        'fileSize' => "Vkládaný soubor může mít maximálně 2Mb",

		'jmeno' => "Jméno musí obsahovat minimálně 4 znaky a být jednoslovné"
    );

//// * VALIDATORS * ////

	public function password ($value){
		return UsersModel::check($value, $_POST['login']);
	}

	public function emptyadmin ($value){
		return $value;
	}

	public function cena ($value){
		return is_numeric($value);
	}

	public function order ($value){
		return is_numeric($value);
	}

	public function ks ($value){
		return is_numeric($value);
	}

	public function jmeno ($value){
		if(strlen($value)<4) return false;
		if(strpos(trim($value)," ")) return false;
		return true;
	}

    public function _email($value){
		$value = trim($value);
        return preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])' .
'(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i',$value);
    }

	public function email (&$value){
		$this->emailFilter($value);
		return $this->_email($value);
	}

	public function _www($value){
		$value = trim($value);
        return preg_match('/^(https?:\/\/)?[^(\/\/)]*\..+.+$/i',$value);
    }

	public function www(&$value){ // filtruje i validuje...
		$this->wwwFilter($value);
        return $this->_www($value);
    }

    public function tel($value){
        $chyba = false;
        $this->telFilter13($value);
        if(strlen($value)!=13) $chyba = true;
        if(!preg_match('/^\+[0-9]*$/i',$value)) $chyba = true;
        return !$chyba;
    }
	public function psc(&$value){
		$v = str_replace(" ","",$value);
		if(strlen($v)!=5) return false;
		if(!is_numeric($v)) return false;
		$value = substr($v,0,3)." ".substr($v,3);
		return true;
	}
	public function ico($value){
		$value = preg_replace('#\s+#', '', $value);
		if (!preg_match('#^\d{8}$#', $value)) {
			return FALSE;
		}
		$a = 0;
		for ($i = 0; $i < 7; $i++) {
			$a += $value[$i] * (8 - $i);
		}
		$a = $a % 11;
		if ($a === 0) $c = 1;
		elseif ($a === 10) $c = 1;
		elseif ($a === 1) $c = 0;
		else $c = 11 - $a;
		return (int) $value[7] === $c;
	}
	public function dic($value){
		// todo
		return true;
	}

    public function mobil($value){ // přejatá funkce tel, jen pozměněna error hláška
        return $this->tel($value);
    }

	public function _date ($value){ // validní jsou formaty 2000-01-01 nebo 2000-01-01 10:10:01 nebo 2000-01-01 1:10
		if(strlen($value)==10){
			$e = explode("-",$value);
			return checkdate($e[1],$e[2],$e[0]);
		}elseif(strlen($value)>14 && strlen($value)<19){
			$e1 = explode(" ",$value);
			$time = $e1[1];
			
			$e = explode("-",$e1[0]);
			$result1 = checkdate($e[1],$e[2],$e[0]);
			$result2 = preg_match('/^((23)|(22)|(21)|(20)|([0-1]?[0-9])):([0-5][0-9])(:([0-5][0-9]))?$/i',$time);
			return ($result1 && $result2);
		}else{
			return false;
		}
	}

	public function date(&$value,$other){ // filtruje i validuje...
		$this->dateFilter($value);
		$result = $this->_date($value);
		if(isset($other['ajax']) && $other['ajax']){
			$this->dateFilterHuman($value);
		}
        return $result;
    }

	public function pohlavi ($value){
        return $value!=0;
    }

	public function delkaHesla ($value){
        return strlen($value)>5;
    }

	public function rePasswordEmpty(&$value,$options){ // Empty je v nazvu, aby se kontolovalo i nevyplnene
		$result = true;
        if(isset($_POST['password'])) $result = $value==$_POST['password'];
		if(!$result && (!isset($options['ajax']) || !$options['ajax'])) $value = "";
        return $result;
    }

	public function oldPassword($value,$options){
		if($options['ajax']){ //var_dump($options);
			return true;
		}else{
			return (md5($_POST['password_old']) == $options['old']);
		}
	}

	public function emptyCaptcha($value){
        return (trim($value)==$_SESSION['captcha_str'] && trim($value));
    }

	// FILE validators
    public function imageFile ($value){ // kontroluje jesli value obsahuje jpg/png/gif
        return (($value["type"] == "image/gif") || ($value["type"] == "image/jpeg") || ($value["type"] == "image/pjpeg"));
    }
	public function xlsFile ($value){ // kontroluje jesli value obsahuje slx
        return ($value["type"] == "application/vnd.ms-excel");
    }
    public function fileSize ($value){
        return ($value["size"] <= 2000000);
    }

//// * empty VALIDATORS * ////
    // speciální validátory s "empty" v názvu se provedou i když je odeslaná hodnota prázdná a přetíží required (fungujou i filtry s empty)
    public function emptyEmail ($value){
        return trim($value)!="";
    }
	public function emptyks ($value){
        return trim($value)!="";
    }
	public function emptyPodminky ($value){
        return $value != false;
    }
	public function emptyDoprava ($value){
        return $value != false;
    }

//// * FILTERS * ////
// je potřeba hodnotu předávat odkazem !!
// a vracet true !!

    public function strtolower(&$value){ // pozor kurví české znaky
       $value = strtolower($value);
       return true;
    }

    public function trim(&$value){
       $value = trim($value);
       return true;
    }

	public function diakritikaFilter(&$value){
		if (is_string($value)){
			$prevodni_tabulka = Array( 'ä'=>'a',' '=>'_','Ä'=>'A',		  'á'=>'a',		  'Á'=>'A',		  'à'=>'a',		  'À'=>'A',		  'ã'=>'a',		  'Ã'=>'A',		  'â'=>'a',		  'Â'=>'A',	  'č'=>'c',		  'Č'=>'C',		  'ć'=>'c',		  'Ć'=>'C',		  'ď'=>'d',		  'Ď'=>'D',		  'ě'=>'e',		  'Ě'=>'E',		  'é'=>'e',		  'É'=>'E',		  'ë'=>'e',		  'Ë'=>'E',		  'è'=>'e',		  'È'=>'E',		  'ê'=>'e',		  'Ê'=>'E',		  'í'=>'i',		  'Í'=>'I',		  'ï'=>'i',		  'Ï'=>'I',		  'ì'=>'i',		  'Ì'=>'I',		  'î'=>'i',		  'Î'=>'I',		  'ľ'=>'l',		  'Ľ'=>'L',		  'ĺ'=>'l',		  'Ĺ'=>'L',		  'ń'=>'n',		  'Ń'=>'N',		  'ň'=>'n',		  'Ň'=>'N',		  'ñ'=>'n',		  'Ñ'=>'N',		  'ó'=>'o',		  'Ó'=>'O',		  'ö'=>'o',		  'Ö'=>'O',		  'ô'=>'o',		  'Ô'=>'O',		  'ò'=>'o',		  'Ò'=>'O',		  'õ'=>'o',		  'Õ'=>'O',		  'ő'=>'o',		  'Ő'=>'O',		  'ř'=>'r',		  'Ř'=>'R',		  'ŕ'=>'r',		  'Ŕ'=>'R',		  'š'=>'s',		  'Š'=>'S',		  'ś'=>'s',		  'Ś'=>'S',		  'ť'=>'t',		  'Ť'=>'T',		  'ú'=>'u',		  'Ú'=>'U',		  'ů'=>'u',		  'Ů'=>'U',		  'ü'=>'u',		  'Ü'=>'U',		  'ù'=>'u',		  'Ù'=>'U',		  'ũ'=>'u',		  'Ũ'=>'U',		  'û'=>'u',		  'Û'=>'U',		  'ý'=>'y',		  'Ý'=>'Y',		  'ž'=>'z',		  'Ž'=>'Z',		  'ź'=>'z',		  'Ź'=>'Z'		);
			$value = strtr($value, $prevodni_tabulka);
		}
		return true;
	}

    public function emailFilter(&$value){ // trim & strtolower
	   $this->diakritikaFilter($value);
       $this->strtolower($value);
       $this->trim($value);
       return true;
    }

	public function dateFilter(&$value){ // 1.1.2000 -> 2000-01-01
		$up = trim($value);
		$middle = @strpos($up," ",8);
		if($middle){ // 1.1.2000 1:1
			$date = substr($up, 0, $middle);
			$time = substr($up, $middle+1 );
		}else{
			$date = $up;
		}
		$date = str_replace(" ","",$date);
		$date = str_replace(array(",","/","-"),".",$date);
		$e = explode(".",$date);
		if(@checkdate($e[1],$e[2],$e[0] && $e[0]>1970)){
			$date = sprintf('%1$04d-%2$02d-%3$02d',$e[0],$e[1],$e[2]);
		}elseif(@checkdate($e[1],$e[0],$e[2]) && $e[2]>1970){
			$date = sprintf('%1$04d-%2$02d-%3$02d',$e[2],$e[1],$e[0]);
			//$date = date("Y-m-d",mktime(0,0,0,$e[1],$e[0],$e[2]));
		}elseif(@checkdate($e[1],$e[0],date('Y'))){
			$date = sprintf('%1$04d-%2$02d-%3$02d',date('Y'),$e[1],$e[0]);
		}else{
			$date = false;
		}
		if(!empty($time)){
			$time = str_replace(" ","",$time);
			$time = str_replace(array(".","/","-"),":",$time);
			if(preg_match('/^([0-9]?[0-9]):([0-9]?[0-9])(:([0-9]?[0-9]))?$/i',$time)){
				$e[2] = 0;
				$e = explode(":",$time);
				for($i=0;$i<=2;$i++)  settype($e[$i],"int");
				if($e[0]>23 || $e[1]>59 || $e[2]>59){
					$time = false;
				}else{
					//$time = sprintf('%1$02d:%2$02d:%3$02d',$e[0],$e[1],$e[2]);
					$time = sprintf('%1$d:%2$02d',$e[0],$e[1]);
				}
			}else{
				$time = false;
			}
		}
		if($date && !isset($time)){
			$value = $date;
		}elseif($date && isset($time) && $time){
			$value = $date." ".$time;
		}
		return true;
    }

	public function wwwFilter(&$value){
		if($this->_www($value)){
			$this->diakritikaFilter($value);
			$value = strtolower($value);
			if(strpos($value,"http://")===false && strpos($value,"https://")===false){
				$value = "http://" . $value;
			}
		}
		return true;
	}
	
	public function dateFilterHuman(&$value){
		if($this->_date($value)){
			$date = substr($value,0,10);
			$time = @substr($value,10);
			$value = date("j.n.Y",strtotime($date)) . $time;
		}
		return true;
	}

    public function telFilter13(&$value){ // přefiltruje číslo na třinástimístné (s plusem)
        $value_up = str_replace(array(' ','+'), '', $value);
        if(is_numeric($value_up)){
            if(strlen($value_up)==9){
                $value = '+420'.$value_up;
            }elseif(strlen($value_up)==12){
                $value = '+'.$value_up;
            }elseif(strlen($value_up)==14 && $value_up[0]=='0' && $value_up[1]=='0'){
				$value = '+'.substr($value_up,2);
			}
        }
        return true;
    }

    public function telFilter9(&$value){ // čísla bez nebo s předvolbou +420 vrátí devítimístné bez mezer
       $this->telFilter13($value);
        if($this->tel($value)){
            $value = str_replace('+420', '', $value);
        }
        return true;
    }
}

 ////   testing validators
/*$value = "1.1.20";
$a =  new FormValidators();
$a->dateFilter($value);
echo $value;*/


?>