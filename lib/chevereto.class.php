<?php
#doc
#	classname:	chevereto
#	scope:		PUBLIC
#	builder:	Gamerlv And rodolfo berrios
#	Version:	2.1 NB Revision 11
#
#/doc
if (!defined('BASEPATH'))
	define('BASEPATH', getcwd() . "..");

// Config
require_once(BASEPATH . '/config.php');
include_once(BASEPATH . '/lib/error.class.php'); //i like reuse and separation 
include_once(BASEPATH . '/lib/plugins.php'); //load up the plugins.

class chevereto
{
	#	internal variables
	private $version = "NB 2.1 Revision 13";
	var $title = "";
	var $mode = 1;
	var $lang = "en";
	var $image = array(); //the image object. will contain all image dat. e.g: filename, tmp name, size.
	
	#	Constructor
	function __construct ()
	{
		
	}
	###
	public function init()
	{
		global $config,$firephp,$e,$errors,$max_by;
		
		$pluginsStart = setupPlugins(); unset($pluginsStart);
		$e = new errorSystem; $errors[0] = false; //new error sys init and old compact
		$max_by = $config['max_filesize']*1048576; // (bytes)
		
		/* firephpmake page white. debug later.
		// Pseudo Debug NOTE: maybe change this to a func or move to boot up file?
		if(!$config['debug']['active']) {
			error_reporting(0);
			include( BASEPATH . "/" . "lib/Firephp_fake.php");
		} else {
			error_reporting(E_ALL); //be prepeared to get a LOT of errors
			include( BASEPATH . "/" . "lib/FirePHP.class.php");
	
			$firephp = FirePHP::getInstance(true);
			$firephp->registerErrorHandler();
			$firephp->registerExceptionHandler();
		}*/
		
		$this->loadLanguage($config['lang']);
		$this->check_everything();
		$e->legeacyCompat($errors); //NOTE: too lazy to convert the old stuff to the new. will do in 2.2
		
	}
	
	##doc
	# Purpos: Check directories premissions
	# Creator: Rodolfo Berrios
	##/doc
	function check_dir_permissions($dir) {
		global $errors;
		if(!is_writable($dir)) {
			$errors[0] = true;
			//'Critital error 01: There is no write permission in '.  //NOTE: use this or not hhmmm
			$errors[1][] = $dir;
		}
	}

	##doc
	# Purpos: check if everything is ok
	# Creator: Gamerlv
	##/doc
	function check_everything(){
		global $config, $errors, $DOM_SCRIPT;

		// Check Permissions
		foreach ($config['dir'] as $key => $dir)
		{
			$this->check_dir_permissions($dir);
		}

		//Check Upload config
		$ini_upload = trim(ini_get('upload_max_filesize'), 'M');
		if( $ini_upload < $config['max_filesize'] ) {
			$errors[0] = true;
			$errors[2] = 'Critital error 02: Max. image size ('.$config['max_filesize'].'Mb) is greater than the value in php.ini ('.$ini_upload.'Mb)';
		}

		// Check GD
		if (!extension_loaded('gd') && !function_exists('gd_info')) {
			$errors[0] = true;
			$errors[3] = 'Critital error 03: You must load the GD extension. <a href="http://php.net/manual/en/book.image.php">http://php.net/manual/en/book.image.php</a>';
		}

		// Check $DOM_SCRIPT
		if(!isset($DOM_SCRIPT) or empty($DOM_SCRIPT)) {
			$errors[0] = true;
			$errors[4] = 'Critital error 04: Invalid $DOM_SCRIPT, edit it manually in config.php';
		
			if ($config['debug']['active'])
				$errors[4] .= "\nYour current dom is set to: ".$DOM_SCRIPT;
		}

		// CH-CH-Chek cURL
		if (!extension_loaded('curl')) {
			$errors[0] = true;
			$errors[5] = 'Critital error 05: You must load the cURL extension. <a href="http://php.net/manual/en/book.curl.php">http://php.net/manual/en/book.curl.php</a>';
		}
	}
	
	public function toWorking($tmpName, $newName)
	{
		if (!is_array($tmpName))
			$tmpName[0] = $tmpName;
		if (!is_array($newName) )
			$newName[0] = $newName;
			
		foreach ($tmpName as $key => $path)
		{
			copy($path, $config['dir']['working']. $newName[$key]);
		}
		return true;		
	}
	
	##doc
	# Purpos:  proccess the image input. and drives all the other functions
	# Creator: Gamerlv
	# Returns: array('mode'=>'single/multi', 'filenames'=> a imploded string if multie or a single filename )
	##/doc
	public function process($files, $newSize=false, $origin ,$mode=1)
	{
			$this->mode = 3; //we are uploading
			if (is_array($files['name'])){
			
				$this->images['mode'] = 'multi';
				$this->images['localUP'] = $files; //just in case i need it again
				
				foreach ($files["error"] as $key => $error) {
					if ($error == UPLOAD_ERR_OK) {
						$name = $files["name"][$key];						
						if ( ! file_exists($config['dir']['working']. $name) ){
							$this->toWorking($files["tmp_name"][$key], $name);
						}						
						//remove the key. so I'm sure I cant use it again. we have a new location now.
						unset($files["tmp_name"][$key]);
					}
				}
				if ($newSize != false && false) { //resize disabled for now
					foreach ($files['name'] as $key => $name)
					{
						$this->resize($name, null, null, $config['dir']['working'] . $name ,$newSize, $newSize);
					}
				}
				foreach ($files['name'] as $id => $name)
				{
					$files['name'][$id] = $this->fileNameChecks($name);
					$this->generateThumb($name);
				}
				
			} else {
				$this->images['mode'] = 'single';
			
			
			}
		//TODO: make this dynamic.
		$output = array(
			'mode' => $this->image['mode'],
			'success'=> true,
			'errors' => false,
			'thumbs' => false, //like /thumbs/files-xxxx.jpg or file-001.jpg;file-002.png;file-003.bmp;
			'uploadedFiles'=> false //like file-001.jpg;file-002.png;file-003.bmp;  always have the ; in the url. add it if needed.
		);
		
		return $output;
	}
	
	/* lets just do this later @ 14;11 29 aug
	* okay. this needs to be merged into resize now. 
	* TODO: merge preresize into resize.
	* NOTE: most of this is error catching. first get the app working right, then worrie about ppl doing bad stuff.
	function preresize($path, $size)
	{
		global $e;
		if(preg_match("/[^0-9\.]/",$size)) { // Queremos solo numeros!|| numbers only please
			/*JUST_NUMBERS;
			UPLOADED_BUT_NOT_RESIZED.ESP_TITULO;*/
			/*somthing like: * / $e->fatalError(JUST_NUMBERS, UPLOADED_BUT_NOT_RESIZED.ESP_TITULO);
		} 
		if($allow_over_resize==false && $resize>$ancho) {
				$errormsg = OVER_RESIZE_ERROR.' ('.$ancho.'px)';
				$titulo = UPLOADED_BUT_NOT_RESIZED.ESP_TITULO;
			
		} else {
		if ($resize<=$higres && $resize>=$lowres) {
			$new_alto = round($resize*$alto/$ancho);
		} else {
			$errormsg = RESIZE_LIMITS.' '.$lowres.' '.ANDTEXT.' '.$higres.' (pixels)';
			$titulo = UPLOADED_BUT_NOT_RESIZED.ESP_TITULO;
		}
		
	 $this->redimensionar(); //what? idk how to use this.....
	} */
	
	
	//wrapper for redimensionar. those spanish varibles are peskey. (sorry rodolfo)
	public function resize($workingName = null, $originPath = null , $extention = null, $targetPath, $width, $height)
	{
		global $config, $e;
		// no extension is set. the function needs it so lets grab it from the working Name
		if ($extension == null && $workingName != null ) $extension = sub_str($workingName, -3, 3);
		if ( $workingName != null && $originPath == null ) $originPath = $config['dir']['working'] . $workingName;
		if ( !file_exists(BASEPATH . $originPath) ) return false; //I dont want the other function to error. because i know it wont handle it nice.
		
		/*User upload error checking. */
		if(preg_match("/[^0-9\.]/",$width)) { // Queremos solo numeros!|| numbers only please
			#UPLOADED_BUT_NOT_RESIZED.ESP_TITULO;
			$e->fatalError(JUST_NUMBERS, UPLOADED_BUT_NOT_RESIZED.ESP_TITULO);
			return false; 
		}
		if(!$config['allow_over_resize'] && $resize>$ancho) {
			$e->fatalError(OVER_RESIZE_ERROR.' ('.$ancho.'px)',
						UPLOADED_BUT_NOT_RESIZED.ESP_TITULO);			
		} else {
			if ($width<=$config['max_res_size'] && $width>=$config['min_res_size']) {
				$new_alto = round($resize*$alto/$ancho);//what?
			} else {
				$e->fatalError(
					RESIZE_LIMITS.' '.$config['min_res_size'].' '.ANDTEXT.' '.$config['max_res_size'].' (pixels)', 
					UPLOADED_BUT_NOT_RESIZED.ESP_TITULO ); 
				return false;
			}
		}
		
		$this->redimensionar($extension, $originPath, $targetPath, $width, $height);
		
		return $targetPath;
	}
	
	// Target -> Archivo a redimensionar (handlework)
	// Archivo -> Archivo work 
	function redimensionar($tipo,$target,$archivo,$ancho_n,$alto_n) {
		
		/* TODO agregar un handle pa esta wea cuando se cae */				
		if ($tipo== "gif") {
			$src = imagecreatefromgif($target);
		}
		if ($tipo== "png") {
			$src = imagecreatefrompng($target);
		}
		if ($tipo== "jpg") {
			$src = imagecreatefromjpeg($target);
		}
		if ($tipo == "bmp") {
			$src = imagecreatefromwbmp($target);
		}
		if ( !isset($src) ) return false; //no image created. die

		$era_x = imageSX($src);
		$era_y = imageSY($src);
		$destino = imagecreatetruecolor($ancho_n,$alto_n);
	
		// gif
		if ($tipo==gif) {
			$transparente = imagecolortransparent($src);
			imagepalettecopy($src, $destino);
			imagefill($destino, 0, 0, $transparente);
			imagecolortransparent($destino, $transparente);
			imagetruecolortopalette($destino, true, 256);
		} else {
			imagecolortransparent($destino, imagecolorallocate($destino, 0, 0, 0) );
		}
	
		imagealphablending($destino, false);
		    		imagesavealpha($destino, true);
	
		imagecopyresampled($destino,$src,0,0,0,0,$ancho_n,$alto_n,$era_x,$era_y);
	
		if ($tipo==gif) { imagegif($destino, $archivo); }
		if ($tipo==png) { imagepng($destino, $archivo); }
		if ($tipo==jpg) { imagejpeg($destino, $archivo, 86); }
		if ($tipo==bmp) { imagewbmp($destino, $archivo); }
	
		imagedestroy($destino); 
		imagedestroy($src);
	return true;		
	} // La funcion | end of func
	
	#TODO: check if this works/ optimize?
	#CHANGED: (11/07/10) preped this for the language selecter
	function loadLanguage($flang=null)
	{
		global $config;
		
		//small fix. so i dont have to edit all language files:
		$tiny_service = ""; $page = "";
		if ($flang == null) $flang = $config['lang']; //$flang not set, lets set it. We'll most problay overwrite it later but still.
		$sesname = $config['prefix']."currentlang";
	
		//make sure the user didn't change his language already
		if (isset($_COOKIE[$sesname])){
			$flang = $_COOKIE[$sesname];
		} elseif (isset($_SESSION[$sesname])){
			$flang = $_SESSION[$sesname];
		}
	
		include(BASEPATH . '/lang/'.$flang.'.php');
		if ($flang!=='es') { $lang = $flang; }
		if (!$flang == $config['lang']){ 
			session_start();
			$_SESSION[$sesname] = $flang;
		 }
		 $this->lang = $lang;
		 return true;
	}
	
	
	public function version()
	{
		return $this->version;
	}
	
	function getTitle()
	{
		global $e,$config;
		if ($this->title == "") $this ->title = WELCOME;	
		$errorT = $e->title;
		if (isset($errorT) && $e->gotErrors === true) //for error title
			$this->title = $errorT;
		
		return $this->title;
	}
	
	//gen random image name
	function genarateRandomName($length=10,$timestamp=0) {
		$characters = "0123456789abcdefghijklmnopqrstuvwxyz"; //the char we use
		$len = strlen($characters) - 1; //get the total length(ammount of characters) of $characters
		$string = ""; //init the output string
		for ($p = 0; $p < $length; $p++) {
			$string .= $characters[mt_rand(0, $len)]; //add a character to the string, until we have $length 
		}
		if ($timestamp){
			$string .= "-".date("j-m-y\:H:i:s"); // aad timestamp !warning this does not account for the max char
		}
		return $string; //output the string.
	}
	
	function fileNameChecks($filename, $length=null)
	{
		global $config;
		if ($length == null) $length = $config['max_name_len'];
		$oldname = $filename;
		if ($config['randomName']) $filename = $this->genarateRandomName($length); //make a name random
		$filename = substr($filename, 0 , $length);
		$a = 0;
		while (file_exists($config['dir']['im'] . $filename)
		{
			$filename .= $a;
			$a++;
		}
		unset($a);
		if ($oldname != $filename) {
			rename($config['dir']['working'].$oldname, $config['dir']['working'].$filename);
		}
		return $filename;
	}
	
	function generateThumb($filename, $width = null, $height = null)
	{
		global $config;
		if ( $width  == null  )	$width	= $config['thumb_width'];
		if ( $height == null )	$height = $config['thumb_height'];
		
		$thumbpath = $this->resize($filename, null, null, BASEPATH . $config['dir']['thumb'] . $filename );
		
		return $thumbpath;
	}
	
	
	public function getVarInfo($variable)
	{
		#NOTE: this is not smart. :(
		return $this->$variable;
	}
	
	public function pageSystem($page=1)
	{
		
	}
	public function redirect($file, $attributes)
	{
		if ($file == 'self') $file = $_SERVER['PHP_SELF']; //little short cut :D		
		header("Location: " . $file . $attributes);
		return true;
	}

}
###

?>
