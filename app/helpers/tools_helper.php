<?php
class Tools {

	/*    STYLES
	.flash-all { margin: 0 5px 10px;  padding: 3px 20px; }
	.flash-succ { border: 1px solid #cdc;  color: #363;  background: #f2fff2; }
	.flash-warn { border: 1px solid #edb;  color: #542;  background: #faf6d9; }
	.flash-alert { border: 1px solid #eba;  color: #400;  background: #fff3f3; }
	.flash-critical { border: 2px solid #faa;  color: #500;  background: #fee;  font-weight: bold; }
	 */
	public static $db_host = DB_HOST;
	public static $db_user = DB_USER;
	public static $db_pass = DB_PASS;
	public static $db_db = DB_DB;

	protected static $instance;
	public static $db; // if is not defined, log is disabled

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

	public static function escape(&$data){
		if(is_array($data)){
			foreach($data as $i=>$d){
				if(is_array($d)){
					self::escape($data[$i]);
					continue;
				}
				$data[$i] = str_replace(array("\\","'","\""),array("\\\\","\\'","\\\""),$d);
			}
		}else{
			$data = str_replace(array("\\","'","\""),array("\\\\","\\'","\\\""),$data);
		}
		return $data;
	}

	public static function seo($url){
		//self::$db->query();
	}

	private static $types = array("succ","warn","alert","critical","debug");

    public static function flash ($message,$type = "alert") {
		$type = strtolower($type);
		if(!in_array($type,self::$types)){
			self::flash('Unknow type of message in static function flash. [Tools]',"critical");
			return false;
		}
        $_SESSION['flashMessages'][$type][] = $message;
		self::log($message,(strtolower($type)=="critical"?1:2),$type);
	}
	public static function printFlash() {
		$result = "";
		if(!empty($_SESSION['flashMessages'])){
			foreach (self::$types as $type){
				$print = "";
				$printed = array();
				if(!empty($_SESSION['flashMessages'][$type])){
					foreach ($_SESSION['flashMessages'][$type] as $i => $message) {
						if(!in_array($message,$printed) && $type!="debug"){     // TODO!
							$printed[] = $message;
							$print .= '<li>' . $message . '</li>';
						}
						unset($_SESSION['flashMessages'][$type][$i]);
					}
				}
				if($print){
					$result .= "<ul class='flash-all flash-". $type ."'>". $print ."</ul>\n\n";
				}
			}
		}
        echo $result;
    }
	public static function log($message,$priority=2,$type=""){
		if(!is_numeric($priority)){
			self::flash("\$priority musí být integer [Tools::log()]","critical");
			return false;
		}
		$message = str_replace(array("\\","'",'"'),array("\\\\","\'","\\\""),$message);
		$sql = "insert into log (text,priority,type,user) values ('{$message}','{$priority}','{$type}','".Ses::user('id')."')";
		self::db_connect();
		mysql_query($sql);
	}

	public static function rozmezi ($d1,$d2=""){
		if(!$d2 || $d2<=$d1) return date("j.n.Y",strtotime($d1));
		if(date("Y",strtotime($d1))!=date("Y",strtotime($d2))){
			$prvni = date("j.n.Y",strtotime($d1));
		}elseif(date("n",strtotime($d1))!=date("n",strtotime($d2))){
			$prvni = date("j.n.",strtotime($d1));
		}else{
			$prvni = date("j.",strtotime($d1));
		}
		return $prvni . " - " . date("j.n.Y",strtotime($d2));
	}
	public static function str2url($text){
		
		if (is_string($text))
		{
			$prevodni_tabulka = Array( 'ä'=>'a',' '=>'_','Ä'=>'A',		  'á'=>'a',		  'Á'=>'A',		  'à'=>'a',		  'À'=>'A',		  'ã'=>'a',		  'Ã'=>'A',		  'â'=>'a',		  'Â'=>'A',	  'č'=>'c',		  'Č'=>'C',		  'ć'=>'c',		  'Ć'=>'C',		  'ď'=>'d',		  'Ď'=>'D',		  'ě'=>'e',		  'Ě'=>'E',		  'é'=>'e',		  'É'=>'E',		  'ë'=>'e',		  'Ë'=>'E',		  'è'=>'e',		  'È'=>'E',		  'ê'=>'e',		  'Ê'=>'E',		  'í'=>'i',		  'Í'=>'I',		  'ï'=>'i',		  'Ï'=>'I',		  'ì'=>'i',		  'Ì'=>'I',		  'î'=>'i',		  'Î'=>'I',		  'ľ'=>'l',		  'Ľ'=>'L',		  'ĺ'=>'l',		  'Ĺ'=>'L',		  'ń'=>'n',		  'Ń'=>'N',		  'ň'=>'n',		  'Ň'=>'N',		  'ñ'=>'n',		  'Ñ'=>'N',		  'ó'=>'o',		  'Ó'=>'O',		  'ö'=>'o',		  'Ö'=>'O',		  'ô'=>'o',		  'Ô'=>'O',		  'ò'=>'o',		  'Ò'=>'O',		  'õ'=>'o',		  'Õ'=>'O',		  'ő'=>'o',		  'Ő'=>'O',		  'ř'=>'r',		  'Ř'=>'R',		  'ŕ'=>'r',		  'Ŕ'=>'R',		  'š'=>'s',		  'Š'=>'S',		  'ś'=>'s',		  'Ś'=>'S',		  'ť'=>'t',		  'Ť'=>'T',		  'ú'=>'u',		  'Ú'=>'U',		  'ů'=>'u',		  'Ů'=>'U',		  'ü'=>'u',		  'Ü'=>'U',		  'ù'=>'u',		  'Ù'=>'U',		  'ũ'=>'u',		  'Ũ'=>'U',		  'û'=>'u',		  'Û'=>'U',		  'ý'=>'y',		  'Ý'=>'Y',		  'ž'=>'z',		  'Ž'=>'Z',		  'ź'=>'z',		  'Ź'=>'Z'		);
			$result = strtolower(strtr($text, $prevodni_tabulka));
			return $result;
		}
		return "";
	}

	// generovani variabilniho symbolu z ID
	public static function vs($id){
		$_vs = strval($id);
		$length = strlen($_vs);
		$vs = date("y");
		for ($i=$length;$i<6;$i++)
			$vs .= "0";
		$vs .= $_vs;
		return $vs;
	}

	private function db_connect(){
		mysql_pconnect(self::$db_host,self::$db_user,self::$db_pass);
		mysql_select_db(self::$db_pass);
	}
	
}
