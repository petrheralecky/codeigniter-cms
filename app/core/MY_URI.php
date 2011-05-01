<?php
class MY_URI extends CI_Uri {

	var	$keyval	= array();
	var $sef_string;
	var $uri_string;
	var $segments		= array();
	var $rsegments		= array();

    public function __construct() {

        parent::__construct();

    }

	public function redirect($url="",$permanent=false,$with_base=false){
		if($url=="404"){
			$url = BASE."cokoliv_co_se_presmeruje_na_error";
		}
		if($permanent) header("HTTP/1.1 301 Moved Permanently");
		if(!$with_base) $url = BASE.$url;
		header("location: ".$url);
		die();
	}
	public function real_url($path){
		$this->db_connect();
		$e = explode("/",trim($path,"/"));
		$q = mysql_query("select url from urls where sef='".$e[0]."'");
		$a = mysql_fetch_array($q);
		if(empty($a)){
			return $path;
		}else{
			return "/" . $a['url'] . substr(trim($path,"/"),strlen($e[0]));
		}
	}
	//returns sef
	public function sef($path){
		$this->db_connect();
		$sql = "select sef from urls where url='".trim($path,"/")."' order by id desc limit 1";
		$q = mysql_query($sql);
		$a = mysql_fetch_array($q);
		if(empty($a)){
			return BASE.trim($path,"/");
		}else{
			return BASE.trim($a['sef'],"/");
		}
	}
	// save sef
	public function save_sef($url,$sef){
		$this->db_connect();
		$sef = my_str2url($sef);
		$url = trim($url,"/");
		// if find saved url, break
		$q = mysql_query("select * from urls where url='".$url."'");
		$break = false;
		while($row = mysql_fetch_assoc($q)){
			if(substr($row['sef'],0,strlen($sef)) == $sef && strlen($sef)>=strlen($row['sef'])-3){
				$break = true;
			}else $break = false;
		}
		if($break) return;
		// if find saved sef, find other
		$i = -1;
		do{
			$save_sef = $sef . (++$i?"-".$i:"");
			$q = mysql_query("select id from urls where sef='".trim($save_sef,"/")."'");
			$a = mysql_fetch_array($q);
		}while(!empty($a));
		mysql_query("insert into urls (url,sef) values ('".$url."','".trim($save_sef,"/")."')");
		
	}
	// redirects old urls to sef or old sef in db
	public function sef_redirect(){
		$this->db_connect();
		$q = mysql_query("select sef from urls where url='".trim($this->sef_string,"/")."' order by id desc limit 1");
		$a = mysql_fetch_array($q);		
		if(!empty($a)){
			$this->redirect($a['sef'],true);
		}
		$e = explode("/",trim($this->sef_string,"/"));
		$sql = "select sef from urls where url=(select url from urls where sef='".$e[0]."' limit 1) order by id desc limit 1";
		$q = mysql_query($sql);
		$a = mysql_fetch_array($q);
		if(!empty($a['sef']) && $a['sef']!=$e[0]){
			$this->redirect($a['sef'].substr(trim($this->sef_string,"/"),strlen($e[0])),true);
		}
	}

	/**
	 * saves url of this site to intern history
	 * @param array $segments
	 */
	public function save_history($url,$segments){
		$max = 12;
		$url = trim($url," /\\");
		$_SESSION['history'][] = array(
			'controller' => $segments[1],
			'action' => $segments[2],
			'url' => $url,
			);
		if(count($_SESSION['history'])>$max){
			foreach($_SESSION['history'] as $i=>$h){
				unset($_SESSION['history'][$i]); break;
			}
		}
	}

	public function back($options = array()){
		$url = self::back_url($options);
		$this->redirect($url,0,1);
	}
	/**
	 *
	 * @param array $options there we can specify:
	 *		controller
	 *		action
	 */
	public function back_url($options = array()){
		if(!isset($_SESSION['history'])){
			return (BASE);
		}

		$history = array_reverse($_SESSION['history']);
		$i = 0;
		foreach($history as $h){
			if(!$i){
				$i++;
				continue;  // vynecha prvni
			}
			if(isset($options['deep']) && $options['deep']>$i){
				continue;
			}
			if(
				   (empty($options['action']) || $h['action']==$options['action'])
				&& (empty($options['controller']) || $h['controller']==$options['controller'])
				&& (empty($options['no_controller']) || $h['controller']!=$options['no_controller'])
				&& (empty($options['no_action']) || $h['action']!=$options['no_action'])
			){
				// ends cycling
				return (BASE.$h['url']);
			}
			$i++;
		}
		// if there isn't useable url
		array_pop ( $_SESSION['history'] ); // vyhodi pouzitou adresu z historie
		if(isset($history[1]['url'])){
			return (BASE.$history[1]['url']);
		}elseif(isset($history[0]['url'])){
			return (BASE.$history[0]['url']);
		}else{
			return (BASE);
		}

	}

	private function db_connect(){
		mysql_pconnect(DB_HOST,DB_USER,DB_PASS);
		mysql_select_db(DB_DB);
	}


	// sitemap methods
	public $sitemap;
	public $sitemap_xml;
	public $url = "sitemap.xml";

	// return array of sites and push it into $this->sitemap
	public function get_sitemap(){
		$this->db_connect();
		$data = array(); // returned array

		// urls
		$result = mysql_query("select * from urls order by id asc");
		while($row = mysql_fetch_array($result)){
			if(!empty($used_urls[$row['url']])) unset($data[$used_urls[$row['url']]]);
			$data[$row['id']] = array(
				'loc' => BASE.$row['sef'],
				//'priority' => "1.0"
			);
			$used_urls[$row['url']] = $row['id'];
		}
		$this->sitemap = $data;
		return $data;
	}
	public function get_sitemap_xml($sitemap = NULL){
		// use sitemap in parameter, then $this->sitemap and then call $this->sitemap() method
		if(!empty($sitemap) && is_array($sitemap)){
			$this->sitemap = $sitemap;
		}elseif(empty($this->sitemap)){
			$this->get_sitemap();
		}
		$xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
		foreach($this->sitemap as $s){

			$xml .= "\t<url>\n";
			$xml .= "\t\t<loc>".$s['loc']."</loc>\n";
			foreach(array('priority',"lastmod","changefreq") as $t){
				if(isset($s[$t])){
					$xml .= "\t\t<{$t}>{$s[$t]}</{$t}>\n";
				}
			}
			$xml .= "\t</url>\n";
		}
		$xml .= "</urlset>\n";
		$this->sitemap_xml = $xml;
		return $xml;
	}

	/**
	 * @uses $url
	 * @uses $sitemap_url
	 * @return bool
	 */
	public function save_sitemap($sitemap_xml = NULL){
		if(!empty($sitemap_xml)){
			$this->sitemap_xml = $sitemap_xml;
		}elseif(empty($this->sitemap_xml)){
			$this->get_sitemap_xml();
		}
		if(($f = fopen($this->url, "w+"))==false){
			Tools::flash('nepodařilo se otevřít soubor: '.$this->url,"critical");
			return false;
		}
		if(fwrite($f,$this->sitemap_xml)){
			return true;
		}else{
			Tools::flash('sitamap se nepodařilo uložit',"critical");
			return false;
		}
	}

	  // to jenom kdyby mě sraly zakázané znaky v adrese... http://codeigniter.com/forums/viewthread/160377/

	// this functin disable this: $this->config->item('permitted_uri_chars')

	function _filter_uri($str) {

		// Convert programatic characters to entities
		$bad	= array('$', 		'(', 		')',	 	'%28', 		'%29');
		$good	= array('&#36;',	'&#40;',	'&#41;',	'&#40;',	'&#41;');

		return str_replace($bad, $good, $str);


    }

	// --------------------------------------------------------------------

	/**
	 * Get the URI String
	 *
	 * @access	private
	 * @return	string
	 */
	function _fetch_uri_string()
	{
		if (strtoupper($this->config->item('uri_protocol')) == 'AUTO')
		{
			// If the URL has a question mark then it's simplest to just
			// build the URI string from the zero index of the $_GET array.
			// This avoids having to deal with $_SERVER variables, which
			// can be unreliable in some environments
			if (is_array($_GET) && count($_GET) == 1 && trim(key($_GET), '/') != '')
			{
				$this->uri_string = key($_GET);
				return;
			}

			// Is there a PATH_INFO variable?
			// Note: some servers seem to have trouble with getenv() so we'll test it two ways
			$path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
			if (trim($path, '/') != '' && $path != "/".SELF)
			{
				//$this->uri_string = $path;
				$this->sef_string = $path;
				$this->sef_redirect();
				$this->uri_string = $this->real_url($path);
				return;
			}

			// No PATH_INFO?... What about QUERY_STRING?
			$path =  (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
			if (trim($path, '/') != '')
			{
				$this->uri_string = $path;
				return;
			}

			// No QUERY_STRING?... Maybe the ORIG_PATH_INFO variable exists?
			$path = str_replace($_SERVER['SCRIPT_NAME'], '', (isset($_SERVER['ORIG_PATH_INFO'])) ? $_SERVER['ORIG_PATH_INFO'] : @getenv('ORIG_PATH_INFO'));
			if (trim($path, '/') != '' && $path != "/".SELF)
			{
				// remove path and script information so we have good URI data
				$this->uri_string = $path;
				return;
			}

			// We've exhausted all our options...
			$this->sef_string = $path;
			$this->sef_redirect();
			$this->uri_string = $this->real_url('');
			//$this->uri_string = '';
		}
		else
		{
			$uri = strtoupper($this->config->item('uri_protocol'));

			if ($uri == 'REQUEST_URI')
			{
				$this->uri_string = $this->_parse_request_uri();
				return;
			}

			$this->uri_string = (isset($_SERVER[$uri])) ? $_SERVER[$uri] : @getenv($uri);
		}

		// If the URI contains only a slash we'll kill it
		if ($this->uri_string == '/')
		{
			$this->uri_string = '';
		}
	}
	 
}
