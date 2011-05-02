<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
|--------------------------------------------------------------------------
| File Stream Modes and DB
|--------------------------------------------------------------------------
|
| My constants
|
*/

// global
define("ID","_def_ci"); // site id
define("DEFAULT_CONTROLLER","home"); // path to root folder
define("FORM_IMG_APP_PATH","www/img/form");

// local
if(SERVER=="local"){
define("DB_HOST","localhost");
define("DB_USER","root");
define("DB_PASS","");
define("DB_DB",ID);
define("PATH","/".ID."/www/"); // path to root folder
define("BASE","/".ID."/"); // path to root folder
define("DOMAIN","http://localhost");
}

// melou
if(SERVER=="test"){
define("PATH","/other/".ID."/www/"); // path to root folder
define("BASE","/other/".ID."/"); // path to root folder
define("DB_HOST","localhost");
define("DB_USER","melouneksql6");
define("DB_PASS","asdfasdf");
define("DB_DB","melouneksql6");
define("DOMAIN","http://melou.cz");
}

// melou.propeople
if(SERVER=="melou.propeople"){
define("PATH","/www/"); // path to root folder
define("BASE","/"); // path to root folder
define("DB_HOST","localhost");
define("DB_USER","meloucms");
define("DB_PASS","Av4tar255");
define("DB_DB","meloucms");
define("DOMAIN","http://meloucms.propeople.cz");
}

// ostry
if(SERVER=="hard"){
define("PATH","/www/"); // path to root folder
define("BASE","/"); // path to root folder
define("DB_HOST","localhost");
define("DB_USER","melouneksql7");
define("DB_PASS","heralecky123");
define("DB_DB","melouneksql7");
define("DOMAIN","http://www.tepelna-izolace.com");
}


/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 					'ab');
define('FOPEN_READ_WRITE_CREATE', 				'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 			'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./system/application/config/constants.php */