<?php
/**
 * Description of Ses
 *
 * @author Melounek
 */
class Ses {

	/*
	 *  this is just prepared... not used jet
	 *
	public static $db_host = DB_HOST;
	public static $db_user = DB_USER;
	public static $db_pass = DB_PASS;
	public static $db_db = DB_DB;
	*/

	public static $prefix = ID;
	public static $path = "/";
	
	public static function user($item,$value = false){
		$time = time() + 60*60*24*100;
		$cook = self::cook2array(self::$prefix);
		if(is_array($item)){
			if(empty($item)){
				self::log("Tools::session() tried to save empty array!",3);
				return false;
			}
			foreach($item as $i=>$d){
				$cook[$i] = $d;
			}
			self::log("Tools::session() saved: ".var_export($item,1)." \n\nIn Cookie:\n".self::array2cook($cook),4);
			setcookie (self::$prefix, self::array2cook($cook), $time, self::$path );
		}elseif($item && $value!==false){
			$cook[$item] = $value;
			self::log("Tools::session() saved variable: ".$item."=>".$value." \n\nIn Cookie:\n".self::array2cook($cook),4);
			setcookie (self::$prefix,  self::array2cook($cook) , $time, self::$path );
		}elseif($item){
			if(isset($cook[$item])){
				// nejde logovat (kvůli zacyklení)
				return $cook[$item];
			}else
				return false;
		}
		return true;
	}

	public static function user_array(){
		$data = self::cook2array(self::$prefix);
		self::log("Tools::session_array() returns: ".var_export($data,1),4);
		return $data;
	}
	public static function user_destroy(){
		self::log("Tools::session_destroy() destroyed user data (logout)",3);
		setcookie (self::$prefix,  "" , time()-60, self::$path );
	}

	// library

	// not used jet
	private function db_connect(){
		mysql_connect(self::$db_host,self::$db_user,self::$db_pass);
		mysql_select_db(self::$db_pass);
	}
	private static function log($msq,$priority){
		try{
			Tools::log($msq,$priority);
		}catch(Exception $e){
			;
		}
	}

	private static function cook2array($prefix){
		// nelogovat!
		if(!isset($_COOKIE[$prefix])){
			return array();
		}
		$dvojice = explode("##",$_COOKIE[$prefix]);
		$result = array();
		foreach($dvojice as $d){
			$e = explode("&&",$d);
			$result[$e[0]] = $e[1];
		}
		return $result;
	}
	private static function array2cook($cook){
		$result = "";
		foreach($cook as $i=>$d){
			if($result) $result.="##";
			$result.= $i."&&".$d;
		}
		return $result;
	}
	
}
?>
