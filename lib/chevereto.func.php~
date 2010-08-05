<?php
#this files just contains all function
# everything in here will be moved to chevereto.class.php in the future.
#after building of chevereto.class.php is coplet this needs to become a wrapper class, for those who don't know object oriented php.
//TODO: move all functions to chevereto.class.php
//TODO: make this file a wrapper for chevereto.class.php

/* -----------------------------------------

  Chevereto - Script de hosting de imagenes
  Nightly Build 2.0 (25/07/2010)
  http://www.chevereto.com/

  Released under the GPL 2.0
  Copyright (C) 2008 by Rodolfo Berrios
  <inbox at rodolfoberrios dot com>

  ----------------------------------------- */

// VERSION DEL SCRIPT
define('SC_VERSION','NB2.0'); //FIXME: Final name of this version will be 1.0 or 2.0. No nightly

// Config
require('config.php');

// Pseudo Debug NOTE: maybe change this to a func or move to boot up file?
if(!$config['debug']['active']) {
	error_reporting(0);
	include( BASEPATH . "/" . "lib/Firephp_fake.php");
} else {
	error_reporting(E_ALL); //be prepeared to get a LOT of errors
	include( BASEPATH . "/" . "lib/FirePHP.class.php");
}

// Critital error box
$errors = array();
$errors[0] = false; //NOTE: this shouldn't stay here, i guess
$o_errorbox = '<div style="background: #F00; color: #FFF; font-family: Courier, monospace; font-weight: bold; padding: 1em; text-align: center;">';
$c_errorbox = '</div>';

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
	global $config, $errors;

	// Check Permissions
	foreach ($config['dir'] as $key => $dir)
	{
		check_dir_permissions($dir);
	}

	//Check Upload config
	$ini_upload = trim(ini_get('upload_max_filesize'), 'M');
	if($ini_upload<$max_mb) {
		$errors[0] = true;
		$errors[2] = 'Critital error 02: Max. image size ('.$max_mb.'Mb) is greater than the value in php.ini ('.$ini_upload.'Mb)';
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


// DOCTITLE
define('ESP_TITULO',' | '); //TODO: move to config

// VARIABLES
$filesArray = $_FILES['fileup'];
$remote_up_url = $_POST['remota'];
$resizf = str_replace(' ', '', $_POST['resize']); // Resize via POST
$url = $_GET['url'];
$urlrez = $_GET['urlrez'];
$v = $_GET['v'];  if ($v=='.htaccess') { unset($v); $v=''; }
$page = $_GET['p'];
$view_fld = $_GET['folder'];
$resizr = $_GET['ancho']; // Resize via GET | NOTE: ancho is width
// SET Modo default
// TODO: move to index.php
if (!isset($modo))
	$modo = 1;


// SHORT URL SERVICE
function short_url_setup()
{
	global $config, $tiny_service, $tiny_api;
		$tiny_service = $config['short_url']['selected'];
		$tiny_api = $config['short_url'][$tiny_service];
}

/*switch($cut_url_service) {
	case 'tinyurl':
		$tiny_api = 'http://tinyurl.com/api-create.php?url=';
		$tiny_service = 'TinyURL';
		break;
	default:
		$tiny_api = 'http://tinyurl.com/api-create.php?url=';
		$tiny_service = 'TinyURL';
		break;
}*/

// LANGUAGE
#TODO: check if this works/ optimize?
#CHANGED: (11/07/10) preped this for the language selecter
function load_lang($flang=null)
{
	global $config, $lang;
	if ($flang == null) $flang = $config['lang']; //$flang not set, lets set it. We'll most problay overwrite it later but still.
	$sesname = $config['prefix']."currentlang";
	
	//make sure the user didn't change his language already
	if (isset($_COOKIE[$sesname])){
		$flang = $_COOKIE[$sesname];
	} elseif (isset($_SESSION[$sesname])){
		$flang = $_SESSION[$sesname];
	}
	
	include('lang/'.$flang.'.php');
	if ($flang!=='es') { $lang = $flang; }
	if (!$flang == $config['lang']){ 
		session_start();
		$_SESSION[$sesname] = $flang;
	 }
}

function check_ref()
{
	global $config, $errors, $DOM_SCRIPT, $filesArray, $remote_up_url, $referido;

	// DE DONDE VIENES? || Where are you from?|| Check wheter they can uplad from
	$referer = parse_url($_SERVER['HTTP_REFERER']);
	if (empty($referer['host']) && !isset($referer['host'])) {
		$referido = $DOM_SCRIPT;
	} else {
		$referido = $referer['host'];
	}

	// Limite de actividad
	//if (isset($filesArray) || isset($remote_up_url)) {
		if ($referido !== $DOM_SCRIPT && $config['same_domain']) {
			if (!isset($url)) {
				$errors[0] = true;
				$errors[6] = ERROR_REF;
			}
		}
	//}
}

if (isset($remote_up_url)) {
	$ref3 = explode('?',$ref2); // img.godlike.cl?urlrez=http:
	$refok = $ref3['0'];
} else {
	$refok = $ref2;
}

// EL REZ
if (isset($resizr)) {
		$resize = str_replace(' ', '', $resizr);
	} else {
		$resize = str_replace(' ', '', $resizf);
	}


// MANEJEMOS LA RUP
if (isset($url)) {
		$rup = str_replace(' ', '', $url);
	} else {
		$rup = str_replace(' ', '', $remote_up_url);
	}

// Si hay posteo / urleo
if (isset($filesArray) || isset($remote_up_url) || isset($url)) {
	if ($filesArray[size] ==! null || !empty($remote_up_url) || !empty($url)) {
		unset($modo);
		$modo = 3;
	} else {
		unset($modo);
		$modo = 1;
		$spit = true;
		$errormsg = FORM_INCOMPLETE;
		$titulo = TITLE_FORM_INCOMPLETE.ESP_TITULO;
	}
}

function check_uploaded()
{
	global $spit,$DOM_SCRIPT,$modo,$errormsg,$titulo,$rup,$urlrez;
	// SI HAY DOBLE POSTEO...
	//check if not both are set
	if (!empty($rup) && !empty($filesArray['type'])) {
	return false;
	//TODO: move this to the function calling
		unset($modo);
		$modo = 1;
		$spit = true;
		$errormsg = DOBLE_POSTED;
		$titulo = FATAL_ERROR_TITLE.ESP_TITULO;
		return false;
	}
	
	$string = $rup.$urlrez;

	if (preg_match("@".$DOM_SCRIPT."/(site-img|js|images	)/@", $string)) {
		unset($modo);
		$modo = 1;
		$spit = true;
		$errormsg = NO_SELF_UPLOAD;
		$titulo = CANT_UPLOAD_TITLE.ESP_TITULO;	
		return false;
	}
	unset($string);
	return true;
}

// Si hay urlrez, seteamos el modo rr
if (isset($urlrez)) {
	if (!empty($urlrez)) {
		// veamos la extension...
		$urlrez3 = substr($urlrez, -3);
		if ($urlrez3=='jpg' || $urlrez3=='peg' || $urlrez3=='gif' || $urlrez3=='bmp' || $urlrez3=='png') {
			unset($modo);
			$modo = 'rr';
			$titulo = ENTER_WIDTH.ESP_TITULO;
		} else {
			$spit = true;
			$errormsg = INVALID_EXT;
			$titulo = INVALID_TITLE.ESP_TITULO;
		}
	} else {
		$spit = true;
		$errormsg = NOTHING_TO_RESIZE;
		$titulo = INPUT_ERROR.ESP_TITULO;
	}
}

// Si hay V, seteamos el modo 2
if (isset($v)) {
	if (!empty($v)) {
		unset($modo);
		$modo = 2;
		$name = $v;
	} else {
		$spit = true;
		$errormsg = NO_ID;
		$titulo = NO_ID_TITLE.ESP_TITULO;
	}
}


/* HAGAMOS EL UPLOADING ---MODO 3--- */
if ($modo==3) {

	// Primer filtro (LOCAL)
	function checklocal($filesArray) {	
		if (preg_match("@image/(gif|pjpeg|jpeg|png|x-png|bmp)$@", $filesArray['type'])) { return true; }
		if ($filesArray['size']<$max_by) { return true; }
		return false;
	}
	
	// Filtro (REMOTO)
	function checkremota($rup) {
		if (!empty($rup)) {
			$rup3 = substr($rup, -3);
			if ($rup3=='bmp') {
				return true;
			} else {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$rup); 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,100);
				$result = curl_exec($ch); 
				$imgstr = imagecreatefromstring($result);
				curl_close($ch);
				if ($imgstr==true) {
					return true;
				}
				imagedestroy($imgstr);
			}
		}
		return false;
	}

	if (checklocal($filesArray) || checkremota($rup)) {
			
		// LA SUBIDA LOCAL
		if ($filesArray['size'] ==! null) {
			
			copy($filesArray['tmp_name'], DIR_WORKING.$filesArray['name']);		
			$tmp_name = $filesArray[name]; // Temp name
		}
		// LA SUBIDA REMOTA
		if (!empty($rup)) {
			// Veamos si viene del resize
			$grabname = substr("$rup", -21); // up/temp/000000000.jpg
			if (file_exists($grabname)) {
				$tmp_name = substr("$rup", -13);
				rename($grabname, DIR_WORKING.$tmp_name);
			} else {
				// GET A NAME
				$partes = explode('/', $rup);
				$rname = $partes[count($partes) - 1];		
				// Conectando
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$rup);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				set_time_limit(300); // 5 Min. PHP
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,300); // 5 Min.cURL
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; es-ES; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6');
				
				$rup_parse = parse_url($rup);
				$rup_temp = substr($rup_parse['path'], 1);
				
				if (preg_match("@".DIR_TEMP."@", $rup_temp)) {
					$delete_temp = true;
				}
								
				$out = fopen(DIR_WORKING.$rname, 'wb');
				curl_setopt($ch, CURLOPT_FILE, $out);
				// grab
				$resultado = curl_exec($ch);
				fclose($out);
				curl_close($ch);
				$tmp_name = $rname;
				}
		} // remota
			
		// Manejemos la temporal
		$handlework = DIR_WORKING.$tmp_name;
		
		$info = getimagesize($handlework);
		
		// Otras lecturas
		$statinfo = @stat($handlework);
		$tamano = $statinfo['size']; // BYTES
		$tamano_kb = round($tamano/1024,2);
		$mimosa = $info['mime']; // SI POR LA CONCHETUMADRE
		$ancho = $info[0]; // Fijate en esto!
		$alto = $info[1];
		$mime = $info['mime'];

		if (!$ancho || !$alto || !$mime || !$tamano) { // Fallan esas leseras
			$invalida = true;
			$inv_txt = INVALID_CORRUPT;
			$no = true; 
		}
		if ($tamano > $max_by) { // Muy pesada
			$peso = true;
			$no = true;
		}
			
		// Manejemos el mime tipe para los "amigos" que usan otras extensiones...
		if ($mimosa=="image/gif") { $exten = 'gif'; }
		if ($mimosa=="image/pjeg") { $exten = 'jpg'; }
		if ($mimosa=="image/jpeg") { $exten = 'jpg'; }
		if ($mimosa=="image/png") { $exten = 'png'; }
		if ($mimosa=="image/bmp") { $exten = 'bmp'; }
		
		if (!isset($no)) {
			$up = true;
		}
		
		if ($no==true) {
			// Eliminamos la imagen del up/working..
			unlink($handlework);

			$spit = true;
			unset($modo);
			$modo = 1;
			if ($peso==true) {
				$pes_txt = TOO_HEAVY.' ('.$max_mb.'MB max.)';
			}
			if ($peso==true && $invalida==true) {
				$ademas = ' '.ANDTEXT.' ';
				$errormsg = $pes_txt.$ademas;
			} else {
				$errormsg = INVALID_EXT;
			}
			
			
			
			
		} // no!
		
		// Hay subida compadre...
		if ($up) {
			
			//gen random name
			function genRandomString($length=10,$timestamp=0) {
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
			
			/* TODO opciones de renombre */
			
			// Limpiemos el nombre
			// --> Tambien me quedo "super rico".
			$lower = strtolower($tmp_name); // Solo minusculas
			$alnum = ereg_replace("[^[:alnum:]]","",$lower); // Solo alfanumericos
			if ($exten==peg) { unset($exten); $exten = 'jpg'; }
			$clear = genRandomString($max_name);
			//$clear = substr_replace($alnum, '', -3); // sin extension ni punto
			// Cortemos el nombre (si hace falta)
			$conteo = strlen($clear);
			
			// Random
			$ch_1 = chr(rand(ord("a"), ord("z")));
			$ch_2 = chr(rand(ord("z"), ord("a")));
			
			if ($conteo>$max_name) {
				$renombre = substr("$clear", 0, $max_name);
			} else {
				if (empty($clear)) {
					$renombre = $ch_1.$ch_2.$ch_1;
				} else {
					$renombre = $clear;
				}
			}
				
			// Si existe el nombre, renombramos el que estamos subiendo.
        	if (file_exists(DIR_IM.$renombre.'.'.$exten)) {
				if ($conteo>$totalchars) { 
					// Si el nombre es muy largo, corta
					$renombra = substr("$renombre", 0, $max_name-4); // 4 -> El remplazo de mas abajo			
				} else { 
					$renombra = $renombre;	
				}
				// Vamos a darle caracteres aleatorios.			
				$name = $renombra.$ch_1.$ch_2.$ch_1.'.'.$exten;
			} else { 
				$name = $renombre.'.'.$exten;
			}		
			
			// Setiemos el redimensionamiento
			if (!empty($resize)) {				
				if(preg_match("/[^0-9\.]/",$resize)) { // Queremos solo numeros!
					$errormsg = JUST_NUMBERS;
					$titulo = UPLOADED_BUT_NOT_RESIZED.ESP_TITULO;
					$spit = true;
					$red = 2;
				} else {
					if($allow_over_resize==false && $resize>$ancho) {
						$errormsg = OVER_RESIZE_ERROR.' ('.$ancho.'px)';
						$titulo = UPLOADED_BUT_NOT_RESIZED.ESP_TITULO;
						$spit = true;
						$red = 2;				
					} else {
						if ($resize<=$higres && $resize>=$lowres) {
							$new_alto = round($resize*$alto/$ancho);
							// Seteamos el nuevo alto y ancho
							unset($ancho);
							unset($alto);
							$ancho = $resize;
							$alto = $new_alto;
							$red = 1;
						} else {
							$errormsg = RESIZE_LIMITS.' '.$lowres.' '.ANDTEXT.' '.$higres.' (pixels)';
							$titulo = UPLOADED_BUT_NOT_RESIZED.ESP_TITULO;
							$spit = true;
							$red = 2;
						}
					}
				}
			}
			
			// Target -> Archivo a redimensionar (handlework)
			// Archivo -> Archivo work (DIR_WORKING.$name)
			function redimensionar($tipo,$target,$archivo,$ancho_n,$alto_n) {
				
				/* TODO agregar un handle pa esta wea cuando se cae */
				if ($tipo==gif) {
					$src = imagecreatefromgif($target);
				}
				if ($tipo==png) {
					$src = imagecreatefrompng($target);
				}
				if ($tipo==jpg) {
					$src = imagecreatefromjpeg($target);
				}
						
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
				
			} // La funcion
			
			if (empty($resize)) {
				// Haga como si nada...
				copy($handlework, DIR_IM.$name);
				$titulo = UPLOAD_OK.ESP_TITULO;
			}
			if ($red==1) {
				// Correr la funcion redimensionamiento *img en el working
				redimensionar($exten,$handlework,DIR_WORKING.$name,$ancho,$alto);
						
				// Mover la redimensionada
				copy(DIR_WORKING.$name, DIR_IM.$name);
				$titulo = UPLOAD_AND_RESIZED.ESP_TITULO;
								
				// Borramos
				unlink(DIR_WORKING.$name);
				if($delete_temp==true) { unlink($rup_temp); }

			}
			if ($red==2) {
				// No red correcto, renombra la temp (vea si esta)
				$tname = DIR_TEMP.$name;			
				if (file_exists(DIR_TEMP.$name)) {
					$numletra = ereg_replace("[^[:alnum:]]","",$name);
					$cleartemp = substr_replace($numletra, '', -3); // sin extension ni punto
					$randtemp = rand(000,999);
					$tname = DIR_TEMP.$cleartemp.$randtemp.'.'.$exten;						
				} else {
					$tname = DIR_TEMP.$name;
				}
				rename($handlework, $tname);
				$URLrdn = URL_SCRIPT.$tname;
				
				// Setea el modo 4!
				unset($modo);
				$modo = 4;
			}
			
			// si se sube algo creemos el thumb
			if ((empty($resize) || $red==1)) {
				// thumb
				if ($ancho>$alto) {
					unset($mini_alto);
					$mini_alto = round($mini_ancho*$alto/$ancho);	
				}
				if ($ancho<$alto) {
					unset($mini_ancho);
					$mini_ancho = round($mini_alto*$ancho/$alto);
				}
				// Thumb
				redimensionar($exten,$handlework,DIR_TH.$name,$mini_ancho,$mini_alto);	
				// Chao work
				unlink($handlework);		
			}
			
		}
	
	} else { // Check local + remote 
		unset($modo);
		$modo = 1;
		$spit = true;
		$errormsg = CRITIC_ERROR_INPUT;
		$titulo = ERROR_UPLOADING.ESP_TITULO;
	}
		
}

if ($modo==2 || $modo==3) {
	// INFORMACION (ANCHO, ALTO y PESO)
	if ($modo==2) {
		if ($_GET['v']) {
			$id = $_GET['v'];
			$imagen = DIR_IM.$id;
			if (file_exists($imagen)==true) {
				$title = SEEING.' '.$id; 
				$titulo = $id.' '.AT.' ';
				$info = getimagesize($imagen); //Obtenemos la informacion
				$statinfo = @stat($imagen);
					$ancho = $info[0];
					$alto = $info[1];	
					$mime = $info['mime']; 
						$tamano = $statinfo['size']; //Bytes
						$tamano_kb = round($tamano*0.0009765625, 2);
					$canales = $info['channels'];
			} else {
				unset($modo);
				$modo = 1;
				$spit = true;
				$errormsg = NOT_EXISTS;
				$titulo = NOT_EXISTS_TITLE.ESP_TITULO;
			}
		}
	}
	
	// LAS URL
	$URLimg = URL_SCRIPT.DIR_IM.$name;
	$URLthm = URL_SCRIPT.DIR_TH.$name;
	$URLvim = URL_SCRIPT.'?v='.$name;
	$URLshr = $URLvim; // Para no cambiar mas abajo
	$eu_img = urlencode($URLimg);
	
	// Short URL using services like TinyURL.com		
	function cortar_url($url) {	
		global $tiny_api;
		$tiny = $tiny_api.$url;
		$chtny = curl_init();
		curl_setopt($chtny, CURLOPT_URL, $tiny);
		curl_setopt($chtny, CURLOPT_RETURNTRANSFER, 1);
		$ShortURL = curl_exec($chtny);
		curl_close($chtny);
		return $ShortURL;
	}
	
	// SI esta habilitado cortar url.. hagamolo.
	if ($cut_url==true) {
		
		// Si se da a elegir al usuario, cortemos si el quiere.
		if ($cut_url_user==true) {
			// El usuario quiere cortar url...
			if (isset($_COOKIE['prefurl'])) {
				$ShortURL = cortar_url($URLimg);
			}
		// Cortamos si o si ya que la prefencia es de script y no de usuario.
		} else {
			$ShortURL = cortar_url($URLimg);
		}

	}

}

if (!isset($titulo)) {
	$titulo = WELCOME;
}

function proccessErrors($echo=false)
{
global $errors;
	if (!$errors[0]){
		return false; // don't wanna run if there are no errors
	}

	$output = '<div id="errorBox">
					<div class="errorInner">
						<p class="errorText">An error ocured. I don\'t know what exacly but these where the error(s):</p>
						<ul>';
			foreach ($errors as $key => $error)
			{
				if ($key == 0) continue; //This is for something else, is true if there is an error
				
				$output .= "<li>"; //open up the li
				
					if (($key == 1) && is_array($error)){ //is there an array in the array? TODO: move $key == 1 to the line below
						$output .= "<p class=\"errorText\">It looks like ther are some premissions errors, I can't access/write to these folders:</p>\n";
						$output .= "<ul>";
						foreach ($error as $key => $dir)
						{
							$output .= "<li>".$dir."</li>\n";
						}
						$output .= "</ul>\n";					
					} else {
					
						$output .= $error;
						$output .= "</li>\n";
					}			
				
			}				
	$output .= '	</ul>
				</div>
			</div>';
				
	if ($echo){
		echo $output;
	} else {
		return $output;
	}
	
}


?>
