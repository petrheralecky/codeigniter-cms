<?php
/* 
 * @version  1.2
 * @author Petr Heralecky
 */
class Form
{
	public static $img_path = "form/formfiles/images/";
	public static $img_app_path = "form/formfiles/images/";
	public static $ajax_path = "form/formfiles/ajax.php";
	public static $log_tool = NULL; // connect validity error reporting with flash-messages
	// example: Form::$log_tool = Tools::getInstance();   (my Tools class is useable)
	public static $charset = "UTF-8";
	public static $captcha_anti_alias = false; // better captcha image, but slower
	public static $changing_checkbox_classes = false; // true = umozni zmenu class i radiobuttonum a checkboxum (required...)
	public static $ajax = true; // aktivuje ajax (prida onBlur even)
	public static $loading_text = "Zpracovávám..."; // prazdne => vůbec se ne vypíše
    public static $method = "post";
	public static $escape_quotes = false;
	public static $escaped_characters = array("\\","'",'"'); // this chars will be returned width \ in get_data() ... "\" take like first!
	public $captcha = true; // provede zakladni javascript kontrolu humanoity
	public $post_by_only_submit = true; // true = odeslani formulare jde jenom submitem (ne javascriptem)
	public $no_error_names = array(); // pole prvku, u kterych se nebudou vypisovat error hlasky (ty budou pristupne jen pres metodu not_valid)
	public $not_valid_data = array(); // array whichones are not valid
	public $elements; //ulozene elementy tohoto formulre v sessnu (nesahat)
	public $data = array(); // default data in forms
	public $form_data = array(); // posted data ... attention that $_POST[a][1] = $this->form_data[a[1]]
    public static $errors_decorator = '%input%<span class="error_msg">%msg%</span>';


	protected static $instance;
	protected static $errors_near_input = false; // true = error hlasky se budou vypisovat pod inputy
	protected $form_name = "";
    protected $session_name = "";
    protected $errors_saved = false;
    protected $errors = array(); // seznam erroru... jsou k dostani pomoci get_errors()
    protected $ses; // session pro ukadani potrebných dat formulare
	protected $ses_captcha; // session ukladajici text captchy (session se prideluje v constructu)
    protected $array_abacus = array(); // nejake pocitadlo
    protected $validators;
	protected $save_errors = true;
	protected $options = array(); // static variables which shoult be rewrite to this value in stop() method

    /**
	 * vytvori formular, nastartuje sessions
	 *
	 * @param string $namespace identifikator formulare
	 * @param array $options ...	there can be each of pubic variables, for example:
	 *								data => array of values (for edit)
	 *								img_path => /base/img/  (for captcha)
	 *								img_app_path => ./img/
	 *								ajax_path => "/Form/ajax.php" or "/ajax/form"; (can be controller-action)
	 *								method => post/get
	 *							this variables will be rewrite in method stop()!
	 */
    public function __construct($namespace,$options = array()){
		if(strpos($namespace," ")){
			$this->log("wrong form name [Form - __construct()]","critical");
		}
        if(!isset($_SESSION)) session_start();
        require_once 'validators.php';
        $this->validators = new FormValidators; // instance pro volani validatoru
        $this->session_name = 'f_'.$namespace;
		$this->form_name = $namespace;
		$this->options = $options;
		foreach($options as $var=>$option){
			$this->set($var,$option,true);		// set variables from option parameter
		}
		if((self::$method=='post' && empty($_POST))||(self::$method=='get' && empty($_GET))) unset($_SESSION[$this->session_name]);
        $this->ses = &$_SESSION[$this->session_name];
		$this->ses_captcha = &$_SESSION['captcha_str']; // misto pro ukladani overovaciho textu - tuto session pouziu i ve validatoru
		$this->elements  = $this->ses['elements'];  // vytazeni jiz ulozenych elementu ze sesny
    }

	/**
     * Singleton instance
     *
     * @return Form (self)
     */
    public static function get_instance($namespace)
    {
        if (null === self::$instance) {
            self::$instance = new self($namespace);
        }

        return self::$instance;
    }

	public function load_data($data){  // Myslim ze jasne...
		if(is_array($data)){
			$data = $this->transform2str($data);
			foreach($data as $i => $d){
				$this->data[$i] = htmlentities($d, ENT_QUOTES,self::$charset);
				//$this->data[$i] = $d;
			}
		}
	}

	/**
	 * Vypise neco jako <form name="m-form" ....
	 *
	 * @param string $action
	 * @param string $other_params example: 'id="my-form" class="formular"'
	 */
	public function start($action="",$other_params=""){
		foreach($this->options as $var=>$option){
			$this->set($var,$option); // set variables and rewrite static variables in $this->options
		}
		echo '<form method="'.self::$method.'" name="'.$this->form_name.'" action="'.$action.'" enctype="multipart/form-data" class="m-form m-form-'.$this->form_name.'" '.$other_params.'>';
		if(self::$loading_text){
			echo '<div id="form-loading-'.$this->form_name.'" class="form-loading"><div><span>'.self::$loading_text.'</span></div></div>';
		}
	}

	/**
	 *
	 */
	public function stop(){
		echo '</form>';
		foreach($this->options as $var=>$option){
			$this->set($var,$option); // set variables back
		}
	}

    /**
	 * Prekouse formularovy tag s parametry zadanymi primov nem a prepise s odpovidajici value a class
	 *
     * @param string $tag zachycuje html tag a na konci jej vyplivne se spravnyma hodnotama
     * @param bool $requiredOrOptions required/not
     * @param array $requiredOrOptions napr: array('cz'=>'cestina','rus'=>'rustina')
     * @param array/string $validators obsahuje jeden ci vice validatoru/filtru
	 * @param array $for_validator predane do validatoru
	 * @param bool $print if tag should be echo in the end
	 *
	 * @return string printed value
     */
    public function _($tag, $requiredOrOptions = 0, $validators = "", $for_validator=array(), $print=true){
   /********************     zpracovani tagu      ************************/
        $tag = str_replace("\n","%break%",$tag); // nahrada odradkovani (kvuli preg_replace)
		$tag_lower = strtolower($tag);
        $name = preg_replace("#(.*)(name *= *(\"|\')([^\"\']*)(.*))#e","'$4'",$tag_lower);  // separuje name
		$onBlur = preg_replace("#(.*)(onblur *= *(\"|\')([^\"]*)(.*))#e","'$4'",$tag_lower);  // separuje onBlur
		$onKeyUp = preg_replace("#(.*)(onkeyup *= *(\"|\')([^\"]*)(.*))#e","'$4'",$tag_lower);  // separuje onKeyUp
		$onChange = preg_replace("#(.*)(onchange *= *(\"|\')([^\"]*)(.*))#e","'$4'",$tag_lower);  // separuje onChange
		$onMouseUp = preg_replace("#(.*)(onmouseup *= *(\"|\')([^\"]*)(.*))#e","'$4'",$tag_lower);  // separuje onMouseup
		$onClick = preg_replace("#(.*)(onclick *= *(\"|\')([^\"]*)(.*))#e","'$4'",$tag_lower);  // separuje onClick
        $class = preg_replace("#(.*)(class *= *(\"|\')([^\"\']*)(.*))#e","'$4'",$tag_lower);// separuje class
        $tagname = preg_replace("#( *< *)([^ ]*)(.*)#e","'$2'",$tag_lower);// separuje jmeno tagu
		if(stripos($tag, 'multiple')) $multiple = true;
		else $multiple = false;
        if(stripos($tagname, 'textarea')!==false) $tagname = "textarea"; // doladeni u textarey
        $required = $requiredOrOptions;
		$value=false;
        if($tagname=="input"){
            $type = preg_replace("#(.*)(type *= *(\"|\')([^\"\']*)(.*))#e","'$4'",$tag_lower);  // separuje typ
            $value = preg_replace("#(.*)(value *= *(\"|\')([^\"\']*)(.*))#e","'$4'",$tag_lower);// separuje value
        }elseif($tagname == "select"){
            $options = $requiredOrOptions;
            $required = 0;
            $type = $tagname;
        }elseif($tagname == "textarea"){
            $type = $tagname; 
        }else{
            $this->log("wrong syntax [Form - _()]".$tagname."-","critical");
        }

        // pokud nebyli nalezeny, prepisou se na false
        if($name==$tag_lower) $name=false;			if($type==$tag_lower){ $type="text"; $type_miss=true; }
        if(@$value==$tag_lower) $value=false;		if($class==$tag_lower) $class=false;
		if($onBlur==$tag_lower) $onBlur=false;		if($onKeyUp==$tag_lower) $onKeyUp=false;
		if($onChange==$tag_lower) $onChange=false;	if($onMouseUp==$tag_lower) $onMouseUp=false;
		if($onClick==$tag_lower) $onClick=false;

		// all types of buttons are rewrited to type "button" (just for needs this class)
		$submit = false;
		if(in_array($type,array('submit','button','reset','image'))){
			if($type=='submit') $submit = true;
			$type = "button";
		}

		//
		if($tagname=="textarea" && !empty($value)){
			$tag = str_replace(">".$value."<","><",$tag);
		}

        // naplneni [] cislama
        if($multiple){
            $name = str_replace('[]','',$name);
        }else{
            if(!isset($this->array_abacus[$name])) $this->array_abacus[$name] = 0;
			$old_name = $name;
            $name = str_replace('[]','['. $this->array_abacus[$name]++ .']',$name);
			if($this->array_abacus[$old_name]){ // prepsani name v tagu pokud jde o array-name
				$tag = preg_replace("#((name)( *= *(\"|\'))([^\"\']*)(\"|\'))#e","'$2=\"$name\"'",$tag);
			}
        }

        if(!is_array($validators) && $validators){ // vyrobi z jednoho prvku pole pokud zatim neni
            $validators = array($validators);
        }

   /********************     doplneni chybjejicich casti do tagu      ************************/
        $dodatek = ""; // zde se ulozi chybejici class nebo value, ktere se pripadne vlozi do tagu
		if(isset($type_miss) && $type_miss){
			$dodatek .= ' type="text"';
		}
        if(!in_array($type,array('hidden','button')) && $class === false){ // pokud chybi class
            $dodatek .= ' class=""';
        }
        if(!in_array($type,array('checkbox','textarea','select','button')) && $value === false ){
            $dodatek .= ' value=""';  // pokud chybi value a neni nevaluovy prvek
        }
		if(self::$ajax && !in_array($type,array('hidden','button')) && $onBlur === false ){
            $dodatek .= ' onBlur=""';  // pokud chybi onBlur
        }
		if(self::$ajax && !in_array($type,array('hidden','select','radio','checkbox','button')) && $onKeyUp === false ){
            $dodatek .= ' onKeyUp=""';  // pokud chybi onKeyUp
        }
		if(self::$ajax && in_array($type,array('select')) && $onChange === false ){
            $dodatek .= ' onChange=""';  // pokud chybi onChange
        }
		if(self::$ajax && in_array($type,array('radio','checkbox')) && $onMouseUp === false ){
            $dodatek .= ' onMouseUp=""';  // pokud chybi onChange
        }
		if($type == "button" && $onClick === false){
            $dodatek .= ' onClick=""';  // pokud chybi onChange
        }
		// write missing parts
		if($dodatek){
			$tag = substr($tag,0,strpos($tag,' ',2)) . $dodatek . strstr(trim($tag),' ');
		}

   /********************     konec u nekterych prvku      ************************/
        if(in_array($type,array('submit','button','reset','image'))){
            if($submit){
                if(!$name){
                    $tag = substr($tag,0,strpos($tag,' ',2)) . ' name="' . $this->session_name. '"' . strstr(trim($tag),' ');
                }
				if(self::$loading_text){
					//$tag = substr($tag,0,strpos($tag,' ',2)) . ' onclick="document.getElementById(\'form-loading-'.$this->form_name.'\').style.display=\'block\';"' . strstr(trim($tag),' ');
					$onClick.=" document.getElementById(\'form-loading-".$this->form_name."\').style.display=\'block\';";
					$tag = preg_replace("#((onClick)( *= *(\"|\'))([^\"]*)(\"|\'))#e","'$2=\"$onClick\"'",$tag);
					//var_dump($tag);
				}
                $this->ses['ok'][] = $name;
				echo $tag;
				if($this->captcha){
					$this->captcha(true);
				}
            }else{
				echo $tag;      // butony are ignored
			}
            return 1;			// a ukonci metodu
        }
        if(!$name){
            $this->log("wrong syntax, name is missing [Form - _()]","critical");
        }


	/********************     plneni tridy tagu      ************************/
        // moznost omezeni vypisu tridy u checkboxu a radia... metoda setCheckboxClasses();
        if(self::$changing_checkbox_classes || ($type != 'checkbox' && $type != 'radio')){
            if($class===false) $class = "";
            if($required){
                $class .= " required";
            }else{
                $class .= " optional";
            }
            if(isset($this->not_valid_data[$name])){
                $class .= " not_valid";
            }
            $tag = preg_replace("#((class)( *= *(\"|\'))([^\"\']*)(\"|\'))#e","'$2=\"$class\"'",$tag);
        }

   /********************     ulozeni vlastnosti do session      ************************/
		if(!empty($other_values)&&is_string($other_values)) //predela pripadne slovo na pole
		$other_values = array(is_array($other_values));

        $this->ses['elements'][$name] = array(
            'type' => $type,
            'class' => $class,
            'validators' => $validators,
            'required' => $required,
			'multiple' => $multiple,
			'for_validator' => $for_validator,
        );

	/********************     pridani onBlur even - AJAX      ************************/
		if(self::$ajax){
			$onBlur = str_replace("'", "\'", $onBlur);			$onChange = str_replace("'", "\'", $onChange);
			$onMouseUp = str_replace("'", "\'", $onMouseUp);	$onKeyUp = str_replace("'", "\'", $onKeyUp);
			$onBlur.=" control_input(\'{$name}\',\'{$this->form_name}\',0,\'".self::$ajax_path."\',\'".$type."\');";
			$tag = preg_replace("#((onBlur)( *= *(\"|\'))([^\"]*)(\"|\'))#e","'$2=\"$onBlur\"'",$tag);
			
			if($type=="select"){
				$onChange.=" control_input(\'{$name}\',\'{$this->form_name}\',1,\'".self::$ajax_path."\',\'".$type."\');";
				$tag = preg_replace("#((onChange)( *= *(\"|\'))([^\"]*)(\"|\'))#e","'$2=\"$onChange\"'",$tag);
			}elseif(in_array($type,array('radio','checkbox'))){
				$onMouseUp.=" control_input(\'{$name}\',\'{$this->form_name}\',0,\'".self::$ajax_path."\',\'".$type."\');";
				$tag = preg_replace("#((onMouseUp)( *= *(\"|\'))([^\"]*)(\"|\'))#e","'$2=\"$onMouseUp\"'",$tag);
			}else{
				$onKeyUp.=" control_input(\'{$name}\',\'{$this->form_name}\',1,\'".self::$ajax_path."\',\'".$type."\');";
				$tag = preg_replace("#((onKeyUp)( *= *(\"|\'))([^\"]*)(\"|\'))#e","'$2=\"$onKeyUp\"'",$tag);
			}
		}

   /********************     plneni hodnot tagu      ************************/
        // predani hodnoty z postu nebo z $this->data do $post_value
        $post_value=false;
        if($this->is_post() && isset($this->form_data[$name])) $post_value = $this->form_data[$name]; // value = post
		elseif(isset($this->data[$name])) $post_value = $this->data[$name];  // value = data
		//elseif($type != 'checkbox' && $type != 'radio') $post_value = $value;    //neaktivní protože sere diakritiku
		if(is_string($post_value) && $type!="textarea") $post_value = str_replace(array('"',"'"),array("&quot;","&lsquo;"),$post_value);

		// prepsani value v tagu
        if($post_value!==false && !in_array($type,array('checkbox','textarea','select','submit','radio','reset','image','button'))){ // pokud ani jedno zustane hodnota v editu
			//$tag = preg_replace("#((value)( *= *(\"|\'))([^\"\']*)(\"|\'))#e","'$2=\"".str_replace(array("'",'"'),array("\\'",'\\"'),$post_value)."\"'",$tag);
			$tag = preg_replace("#((value)( *= *(\"))([^\"]*)(\"))#e","'$2=\"".str_replace(array("'",'"'),array("\\'",'\\"'),$post_value)."\"'",$tag);
			$tag = preg_replace("#((value)( *= *(\'))([^\']*)(\'))#e","'$2=\"".str_replace(array("'",'"'),array("\\'",'\\"'),$post_value)."\"'",$tag);
        }elseif($post_value!==false && $type=='textarea'){
            $tag = preg_replace("#(>)(.*)(</? *textarea *>)#e","'>".str_replace("'","\\'",$post_value)."</textarea>'",$tag);
        }
		if(is_string($post_value)){
			$post_value = html_entity_decode($post_value, ENT_QUOTES,self::$charset);
		}
		
		$return_value = $post_value;

        // oznaceni radiobuttonu a checkboxu
        if( ($type == 'checkbox' || $type == 'radio')){ // $this->is_post() &&
			if(!empty($this->data)){
				$tag = preg_replace("#(checked)( *= *(\"|\') *checked *(\"|\'))?#e","''",$tag);
			}
            // nastavi checked
			//var_dump($post_value);var_dump($value);
            if($type == 'checkbox' && $post_value != false){ //$post_value === $value
                $tag = substr($tag,0,strpos($tag,' ',2)) . ' checked="checked"' . strstr(trim($tag),' ');
            }
            if($type == 'radio' && $post_value === $value){ //$this->form_data[$name]===$value
                $tag = substr($tag,0,strpos($tag,' ',2)) . ' checked="checked"' . strstr(trim($tag),' ');
            }
        }
		// označení polozek select
        if($type == 'select' ){
            $tag = str_replace('</select>','',$tag);
			if(empty($options) || !is_array($options)){
				$this->log("some select tag get wrong \$options parameter [Form->_()]");
			}else{
				if(isset($options[0])){
					$tag .= '<option value="0">' . $options[0] . '</option>'; // to avoid selecting unselected options
					unset($options[0]);
				}
				foreach($options as $val=>$cont){
					$sel = '';
					if(($post_value==$val || (is_array($post_value) && in_array($val,$post_value))) && $post_value!==false)
						$sel = ' selected="selected"';
					$tag .= '<option value="' . $val . '"' . $sel . '>' . $cont . '</option>';

				}
			}
            $tag .= '</select>';
        }

        $tag = str_replace("%break%","\n",$tag); // opetovne nahrazeni odradkovani (kvuli preg_replace)

		if($print){
			echo $tag; // a konecne zpetne vypsani tagu
			echo "\n";
		}

		if(stripos($class,"datetime")!==false){
			echo $this->calendar(1,$name);
		}elseif(stripos($class,"date")!==false){
			echo $this->calendar(0,$name);
		}elseif(stripos($class,"datetimesec")!==false){
			echo $this->calendar(2,$name);
		}

		//vypis erroru primo pod input
		if(self::$errors_near_input && (empty($this->no_error_names) || !in_array($name,$this->no_error_names) && $print)){ // pokud není v zakázaných
			$this->print_under($name);
		}

		return $return_value;
    }

	/**
	 * vraci ikonku kalendare
	 * @param int $with_time 0-bez casu, 1-s hodinama a minutama, 2-i sekundy
	 * @param string $name jmeno inputu
	 * @return string ... html tag
	 */
	public function calendar($with_time,$name){
		$result = '<a class="form-calendar" href="#" onclick="show_calendar(\'document.'.$this->form_name.'.'.$name.'\',
					document.'.$this->form_name.'.'.$name.'.value, '.$with_time.', \''.self::$img_path.'\'); return false;">';
		$result .= '	<img src="'.self::$img_path.'cal.png" width="23" height="19" alt="Klikněte pro výběr data">';
		$result .= ' </a>';
		return $result;
	}

    /**
     * zkontroluje validitu poslanych dat primo z postu a ulozi do $this->form_data[]
	 * erory ulozi do $this-errors[], z kama se daji vytahnout get_errors() nebo print_errors()
	 *
     * @return bool zda jsou data validni
     */
    public function validate($save_errors = true){
		$this->save_errors = $save_errors;
        if(!$this->is_post()){
			$this->log("Try to validate, but it not posted [Form->validate()]","debug");
            return false;  // formular nebyl odeslan -> not valid
        }
		if(!empty($this->errors)){
			$result = false; // navratova hodnota jesli jsou data validni
		}else{
			$result = true;
		}
        if(self::$method=="post") $d = $_POST;
        else $d = $_GET;
		//$this->form_data = $d; //up

        $d = $this->transform2str($d);

        if(!empty($_FILES)){ // prevzedni jmen inputu typu "file"
            foreach($_FILES as $i=>$file){
                $d[$i] = $file['tmp_name'];
            }
        }
        foreach($this->elements as $index=>$element){
            if(!isset($d[$index])){
				if(isset($element['multiple']) && $element['multiple']){
					$d = $this->mult_transform($d,$index);
				}else{
					$d[$index] = false;
				}
				//var_dump($element); echo"<br><br>";
            }

			if(isset($element['multiple']) && $element['multiple']){
				$d = $this->mult_transform($d,$index);
			}
			
			if($element['type']=='checkbox' && $d[$index]){
				$d[$index] = 1;
			}

			if($element['type']=='file'){
				if(isset($_FILES[$index])) $value = $_FILES[$index];
				else $value = "";
			}else{
				$value = &$d[$index];
			}
			if(!$this->validate_one($value,$index,false)){ // $element['for_validator']
				$result = false;
			}
			
            // ulozi data z postu do $form_data
            if(isset($_FILES[$index])){
                $this->form_data[$index] = $_FILES[$index];
            }else{
                $this->form_data[$index] = $d[$index];
            }
			
        }
        $this->errors_saved = true;
		if(self::$log_tool){
			$this->flash_errors();
		}
		
        return $result;
    }

	/**
	 * Zkontroluje validitu 1 prvku (pri pouziti filtru muze value take pozmenit).
	 * Error se ulozi metoda save_error().
	 * @param string &$value hodnota prvku (muze byt zmenena)
	 * @param string $index name prvku
	 * @return bool validni/nevalidni
	 */
	public function validate_one(&$value,$index,$ajax=false){
		$element = $this->elements[$index];
		if(is_array($value) && !empty($value)){
			$value_bez_mezer = 'array';
		}else{
			$value_bez_mezer = @trim($value);
		}
		if($ajax) $this->elements[$index]['for_validator']['ajax'] = true;
		else $this->elements[$index]['for_validator']['ajax'] = false;
		// cyklus pro zjistni zda nebyl uveden validator s "empty" => potom se preskakuje cast pro required
		$preskoc_required = false;
		if(!empty($element['validators'])){
			foreach($element['validators'] as $val){
				if(stripos($val, 'empty')!==false) $preskoc_required = true;
			}
		}
		if($element['required'] && ($value_bez_mezer==="" || $value_bez_mezer===false || ($element['type']=='file'&&!$_FILES[$index]['tmp_name'])) && !$preskoc_required){
			if($element['type']=='file'){ // pridani eroru pro Povinne polozky (empty v FormValidators.php)
				$this->save_error('emptyFile',$index);
			}else{
				$this->save_error('empty',$index);
			}
			return false;
		}elseif($element['type']=='file' &&
				!in_array($_FILES[$index]['error'],array(UPLOAD_ERR_OK,UPLOAD_ERR_NO_FILE))){
			$this->save_error('fileError',$index);
			return false;
		}else{			// pokud prosel pres required vetvi muze se validovat
			if(!empty($element['validators'])){
				$validators = $this->validators; // instance pro volani validatoru
				foreach($this->elements[$index]['validators'] as $val){    //pruchod pres validatory
						// pokud neni validni vrati false
					if(	($value || $value==="0" || stripos($val, 'empty')!==false)
						&& !$validators->$val($value,$this->elements[$index]['for_validator'])
						&& !(isset($value['tmp_name']) && $value['tmp_name']=="")){
						// prida odpovidajici eror z FormValidators
						if(!empty($validators->emsg[$val])){
							$this->save_error($val,$index);
						}else{
							$this->save_error('%empty%',$index);
						}
						return false;
					}
				}
			}
		}
		return true;
    }

	/**
	 * @return array of errors for next html stylize
	 */
    public function get_errors(){
        if(!empty($this->elements)){
            if(!$this->errors_saved){
                $this->validate();
            }
			foreach($this->errors as $index=>$error){
				if($error=="%empty%")
					unset($this->errors[$index]);
			}
			return $this->errors;
        }  
    }

	public function extra_validator($condition,$emsg,$index = ""){
		if($condition){
			$this->save_error($emsg,$index);
			if(self::$log_tool){
				$this->flash_errors();
			}
		}
	}

	/**
	 * sets dynamic and static variables and rewrites $this->options by
	 *
	 * @param string $variable name of public variable which should be set... allowed are these: "img_path","img_app_path","ajax_path","data","method","ajax","captcha","errors_near_input","changing_checkbox_classes","post_by_only_submit","no_error_names","msg","loading_text"
	 * @param string $value ...
	 */
	public function set($variable,$value,$without_static=false){
		$possible_options = array("img_path","img_app_path","ajax_path","data","method","ajax","captcha","errors_near_input","changing_checkbox_classes","post_by_only_submit","no_error_names","msg","loading_text");
		if(in_array($variable, $possible_options)){
			// start special conditions
			if($variable=="data" && is_array($value)){
				$this->load_data($value);
			}
			if($variable=="method" && in_array(strtolower($value),array("get","post"))){
				$value = strtolower($value);
			}
			if(in_array($variable,array("img_app_path","img_path"))){ // if slash isn't in some path, add it
				if(strpos($value,"/",strlen($value)-1)!==false) $value = $value."/";
			}
			// end conditions
			if(isset($this->$variable)){
				$this->options[$variable] = $this->$variable;
				$this->$variable = $value;
			}elseif(isset(self::$$variable) && !$without_static){
				$this->options[$variable] = self::$$variable;
				self::$$variable = $value;
			}
		}else{
			$this->log("method Form::set() has wrong first parameter","critical");
		}
		
	}

	/**
	 * print html list of errors width "form_errors" class
	 */
	public function print_errors(){
		$errors = $this->get_errors();
		if(!empty($errors)){
			echo '<ul class="form-errors">';
			foreach($errors as $index=>$error){
				echo '<li>' . $error . '</li>';
			}
			echo '</ul>';
		}
	}

	/**
	 * gives errors to flash messager
	 */
	public function flash_errors(){
		$errors = $this->get_errors();
		if(!empty($errors)){
			foreach($errors as $index=>$error){
				$this->log($error);
			}
		}
	}

	/**
	 * logs errors e.t.c. by flashLooger
	 *
	 * @param string $msg
	 * @param string $type one of this: "succ","warn","alert","critical"
	 * @return bool
	 */
	private function log($msg,$type = "alert"){
		$type = strtolower($type);
		$types = array("succ","warn","alert","critical","debug");
		if(!in_array($type,$types)){
			$this->log("wrong parameter \$type [Form - log()]","critical");
			return false;
		}
		if(self::$log_tool && !is_object(self::$log_tool)){
			throw new Exception('Form::loghTool in not good set.');
		}
		if(is_object(self::$log_tool)){
			self::$log_tool->flash($msg,$type);
		}else{
			if($type=="critical"){
				echo "<div style='border: 2px solid red;  padding: 10px 20px'>";
				echo $msg;
				echo "<br/><small>logger is off or bad set :(</small></div>";
			}
		}
		return true;
	}

    /**
	 * nastavi tag, ve kterem bude obsazen error, ktery se napise hned za postizeny input
	 * @param string $before
	 * @param string $after
	 * @param bool $after_tag
	 */
    public static function set_errors_near_input( $enable = true,
			$before = '<span class="error_msg">', $after = '</span>', $after_tag = true){
		self::$errors_near_input = $enable;
        self::$errors_decorator = $before . '%msg%' . $after;
		if($after_tag){
			self::$errors_decorator .= "%input%";
		}else{
			self::$errors_decorator = "%input%" . self::$errors_decorator;
		}
    }

    /**
	 * @return bool jesli je formular radne odeslan a validni
	 */
    public function ready(){
		if($this->is_post() && $this->validate()){
			return true;
		}else{
			return false;
		}
    }

    /**
	 * @param bool $free_data destroy sessions of this form (default==false)
	 * @param bool $make_alerts if you want to save errors (default==true)
	 * @return array form data
	 */
    public function get_data($free_data = false,$save_errors = true) {
        if(empty($this->form_data)){
            $this->validate($save_errors);
            if(empty($this->form_data)){
                $this->log("nowhing was sent by form. Maybe no elements was defined [Form - get_data()]","critical");
                return false;
            }
        }
		$data = $this->form_data;
		if($free_data){
			$this->free();
		}
		if(self::$escape_quotes){
			foreach($data as $i=>$d){
				// this space between backshash and quotes is to avoid sql-hacking...
				$data[$i] = str_replace(array("\\'",'\\"',"'",'"'),array("\\ '",'\\ "',"\\'",'\\"'),$d);
			}
		}
		$data = $this->transform2array($data);
        return $data;
    }

	/**
	 * free session data
	 */
	public function free(){
		unset($_SESSION[$this->session_name]);
	}

	/**
	 * Vrací true pokud byl form odeslán (ovlivneno prom post_by_only_submit)
	 * @return bool odeslan/neodeslan
	 */
    public function is_post(){
		if(empty ($this->elements)){
			return false;
		}
        if(self::$method=="post") $d = $_POST;
        else $d = $_GET;
        if( isset($d[$this->session_name]) ){  // (isset($this->ses['ok'])&&isset($d[$this->ses['ok']])) || 
            return true;
        }
		if(!empty($this->ses['ok'])) foreach($this->ses['ok'] as $ok){
			if(isset($d[$ok])) return true;
		}
        if(!$this->post_by_only_submit){ // bude brat formular za odeslany jiz pri prvnim nastavenem prvku v postu
			foreach($this->elements as $index=>$element){
				if(isset($d[$index]) && $index!="captcha"){  // pokud bude nastaveny jeden z elementu formulare v postech,
					return true;     // bude to znacit odeslani formulare
				}
			}		
        }
		//var_dump(self::$post_by_only_submit);
        return false;
    }

	/**
	 * pokud prvek s uvedenym jmenem nebyl validni, vrati chybovou hlasku
	 * @param string $name jmeno validovaneho prvku
	 * @return string chybova hlaska
	 */
    public function not_valid($name){
        if(!empty($this->not_valid_data[$name])){
            return $this->not_valid_data[$name];
        }else{
            return "";
        }
    }

	/**
	 * Vrari error hlasku s prislusnym id pro ajax a prislusnym html decoratorem
	 * @param string $name jmeno validovaneho prvku
	 */
    public function print_error($name){
		echo '<span id="error_'.$name.'">';
        if($this->not_valid($name)){
            echo str_replace('%msg%', $this->not_valid_data[$name], self::$errors_decorator);
        }else{
            echo "";
        }
		echo '</span>';
    }

	/**
	 * Zkontroluje zda je uzivatel podezreli nebo ma moc pokusu a kdyztak vyrobi captcha pro kontrolu
	 * @param string $captcha_str string to transform
	 */
	public function captcha($javascript=false){
		if(strlen($this->ses_captcha)==4){
			$captcha_str = $this->ses_captcha;
		}else{
			$captcha_str = $this->rand_str(4);
		}
		// konfigurace rozměrů a pozic
		$matrix_dim = array('x' => 52, 'y' => 32);
		$captcha_dim = array('x' => 240, 'y' => 62);
		$distance = array('x' => -30, 'y' => 1, 'z' => 1);
		$metric = array('x' => 12, 'y' => 16, 'z' => 5);
		$offset = array('x' => 0, 'y' => -60);
		$cam_location = array('x' => -30, 'y' => 0, 'z' => -250);
		$cam_rotation = array('x' => -1, 'y' => 0, 'z' => 0);
		$viewer_position = array('x' => 0, 'y' => -500, 'z' => -80);
		// matrice
		$matrix = imagecreatetruecolor($matrix_dim['x'], $matrix_dim['y']);
		$black = imagecolorexact($matrix, 0, 0, 0);
		$white = imagecolorexact($matrix, 255, 255, 255);
		imagefill($matrix, 0, 0, $white);
		// font lucon
		imagefttext($matrix, 13.5, 0, 1, 16, $black, self::$img_app_path . 'lucon.ttf', $captcha_str);
		// výpočet bodů ve 3d
		$point = array();
		for ($x = 0; $x < $matrix_dim['x']; $x++) {
			for ($y = 0; $y < $matrix_dim['y']; $y++) {
				$img = $matrix;
				$c = @imagecolorat($img, $x, $y);
				if($c === false) return false;
				if(imageistruecolor($img)){
					$red = ($c >> 16) & 0xFF;
					$green = ($c >> 8) & 0xFF;
					$blue = $c & 0xFF;
				}else{
					$i = imagecolorsforindex($img, $c);
					$red = $i['red'];
					$green = $i['green'];
					$blue = $i['blue'];
				}
				$m = min($red, $green, $blue);
				$n = max($red, $green, $blue);
				$lightness = (double)(($m + $n) / 510.0);
				$p = array('x' => $x * $metric['x'] + $distance['x'], 'y' => $lightness * $metric['y'] + $distance['y'], 'z' => ($matrix_dim['y'] - $y) * $metric['z'] + $distance['z']);
				$translation = array();
				$projection = array();
				$translation['x'] = cos($cam_rotation['y']) * (sin($cam_rotation['z']) * ($p['y'] - $cam_location['y']) + cos($cam_rotation['z']) * ($p['x'] - $cam_location['x'])) - sin($cam_rotation['y']) * ($p['z'] - $cam_location['z']);
				$translation['y'] = sin($cam_rotation['x']) * (cos($cam_rotation['y']) * ($p['z'] - $cam_location['z']) + sin($cam_rotation['y']) * (sin($cam_rotation['z']) * ($p['y'] - $cam_location['y']) + cos($cam_rotation['z']) * ($p['x'] - $cam_location['x']))) + cos($cam_rotation['z']) * (cos($cam_rotation['z']) * ($p['y'] - $cam_location['y']) - sin($cam_rotation['z']) * ($p['x'] - $cam_location['x']));
				$translation['z'] = cos($cam_rotation['x']) * (cos($cam_rotation['y']) * ($p['z'] - $cam_location['z']) + sin($cam_rotation['y']) * (sin($cam_rotation['z']) * ($p['y'] - $cam_location['y']) + cos($cam_rotation['z']) * ($p['x'] - $cam_location['x']))) - sin($cam_rotation['z']) * (cos($cam_rotation['z']) * ($p['y'] - $cam_location['y']) - sin($cam_rotation['z']) * ($p['x'] - $cam_location['x']));
				$projection['x'] = ($translation['x'] - $viewer_position['x']) * ($viewer_position['z'] / $translation['z']);
				$projection['y'] = ($translation['y'] - $viewer_position['y']) * ($viewer_position['z'] / $translation['z']);
				$point[$x][$y] = $projection;
			}
		}
		imagedestroy($matrix);
		// obrázek captcha
		$captcha = imagecreatetruecolor($captcha_dim['x'], $captcha_dim['y']);
		// antialiasing čar - pro menší zátěž lze vypnout
		if(self::$captcha_anti_alias){
			imageantialias($captcha, true);
		}
		$black = imagecolorexact($captcha, 0, 0, 0);
		$white = imagecolorexact($captcha, 255, 255, 255);
		imagefill($captcha, 0, 0, $white);
		// vykreslení vrstevnic
		for ($x = 1; $x < $matrix_dim['x']; $x++) {
			for ($y = 1; $y < $matrix_dim['y']; $y++) {
				imageline($captcha, -$point[$x - 1][$y - 1]['x'] + $offset['x'], -$point[$x - 1][$y - 1]['y'] + $offset['y'], -$point[$x][$y]['x'] + $offset['x'], -$point[$x][$y]['y'] + $offset['y'], $black);
			}
		}
		// vystup do souboru ktery se nasledovne echne
		if($javascript){
		echo '<noscript>';
		}
		imagepng($captcha,self::$img_app_path . 'captcha.png');
		echo '<img class="captcha-img" src="' . self::$img_path . 'captcha.png" alt="" /><br class="captcha-br" />';
		$this->_('<input type="text" name="captcha" class="captcha-input" />',1,'emptyCaptcha');
		if($javascript){
			echo '</noscript><br class="captcha-br" />';
			echo '<script type="text/javascript">
						document.write(\'<input type="hidden" name="captcha" value="'. substr($captcha_str, 0, 2) .'\' + \''. substr($captcha_str, 2, 2) .'" />\');
				  </script> ';
		}

		// ulozeni hodnoty do session
		//$this->ses['captcha'] = $captcha_str;
		$this->ses_captcha = $captcha_str; // ulozeni primo do session aby se dala hodnota jednoduse testovat ve validatoru
		$this->captcha = false; // zakaze znovuvypsani captchy
	}

	/**
	 * @param int $len string length
	 * @return string rand string
	 */
	private function rand_str($len){
		// generování náhodného textu
		$pool = 'abcdefghijkmnopqrstuvwxyz';
		$captcha = '';
		for ($i=0; $i < $len; $i++) {
			$captcha .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
		}
		return $captcha;
	}

	// Ulozi errory
	private function save_error($emsg,$index=""){
		if(!empty($this->validators->emsg[$emsg])){
			$msg = $this->validators->emsg[$emsg];
		}else{
			$msg = $emsg;
		}
		if($index){
			$this->not_valid_data[$index] = $msg; // uloeeni nevalidniho prvku
		}
        if($this->save_errors && $emsg!='%empty%' && !in_array($msg,$this->errors)
				&& !(!empty($this->no_error_names) && in_array($index,$this->no_error_names))){ // pokud nenĂ­ v zakĂˇzanĂ˝ch
			$this->errors[] = $msg;
        }
    }

    // specificky predelava pole na string.. napr: a[1][2][3]="b" -> a['1[2][3]']="b"
    private function transform2str($data,$prefix="") {
        foreach($data as $i=>$d){
            if($prefix){
                $i = $prefix."[".$i."]";
            }
            if(is_array($d)){
                $this->transform2str($d,$i);
            }else{
                $this->transData[$i] = $d;
            }
        }
        if(!$prefix){
            $result = $this->transData;
            $this->transData = array();
            return $result;
        }
    }
	private $transData = array(); // promenna pro funkci transform2str

    // zpetna funkce pro transform2str
    private function transform2array($data) {
        foreach($data as $i=>$d){
            $ii = explode('[', $i);
            $stack = $d;
            for($c=count($ii)-1;$c>=0;$c--){
                $stack2 = array();
                if($c==0){
                    $stack2[$ii[0]] = $stack;
                    if(isset($result) && is_array($result)){
                        $result = array_merge_recursive($result,$stack2);
                    }else{
                        $result = $stack2;
                    }
                }elseif($c==count($ii)-1){
                    $stack = array(str_replace("]","",$ii[$c])=>$d);
                }else{
                    $stack = array(str_replace("]","",$ii[$c])=>$stack);
                }
            }
        }
        return $result;
    }
    
    // specialni transformace nazvu pro multiple-select
	private function mult_transform($d,$index) {
		$last_index = "";
		foreach($d as $i_d=>$d_d){
			if(strpos($i_d,$index)!==false){
				$c = 0;
				while(strlen($i_d)>$c){
					if($i_d[$c]!="[" && $i_d[$c]!="]"){
						$last_index .= $i_d[$c];
					}elseif($i_d[$c]=="[")
						$last_index = "";
					$c++;
				}
				$d[$index][$last_index]=$d_d;
			}
		}
		return $d;
	}
	/**
	 * Vypise error zvoleneho name, nebo vytvori tag pro ajaxove vypisovani
	 * @param string $name
	 */
	private function print_under($name){
		echo $decorator = str_replace("%id%", "error_".$name,'<span id="%id%">');
		if(isset($this->not_valid_data[$name]) && $this->not_valid_data[$name]!='%empty%'){ // vypsani error hlasky pod imput
			echo str_replace('%msg%', $this->not_valid_data[$name], self::$errors_decorator);
		}
		echo '</span>';
	}
}
?>
