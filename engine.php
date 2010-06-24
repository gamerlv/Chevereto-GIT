<?

/* -----------------------------------------

  Chevereto - Script de hosting de imagenes
  Nightly Build 2.0 (25/07/2010)
  http://www.chevereto.com/

  Released under the GPL 2.0
  Copyright (C) 2008 by Rodolfo Berrios
  <inbox at rodolfoberrios dot com>

  ----------------------------------------- */

// VERSION DEL SCRIPT
define('SC_VERSION','NB1.9');

// Config
require('config.php');

// Pseudo Debug
if(!$debug_mode) {
	error_reporting(0);
}

// Critital error box
$o_errorbox = '<div style="background: #F00; color: #FFF; font-family: Courier, monospace; font-weight: bold; padding: 1em; text-align: center;">';
$c_errorbox = '</div>';

function check_permissions($dir) {
	global $o_errorbox, $c_errorbox;
	if(!is_writable($dir)) {
		echo $o_errorbox.'Critital error 01: There is no write permission in '.$dir.$c_errorbox;
		die();
	}
}
// CH-Ch-Check Permissions
check_permissions(DIR_UP);
check_permissions(DIR_IM);
check_permissions(DIR_WORKING);
check_permissions(DIR_TEMP);
check_permissions(DIR_TH);

// CH-CH-Check Upload config
$ini_upload = trim(ini_get('upload_max_filesize'), 'M');
if($ini_upload<$max_mb) {
	echo $o_errorbox.'Critital error 02: Max. image size ('.$max_mb.'Mb) is greater than the value in php.ini ('.$ini_upload.'Mb)'.$c_errorbox;
	die();
}

// CH-CH-Check GD
if (!extension_loaded('gd') && !function_exists('gd_info')) {
	echo $o_errorbox.'Critital error 03: You must load the GD extension. <a href="http://php.net/manual/en/book.image.php">http://php.net/manual/en/book.image.php</a>'.$c_errorbox;
	die();
}

// CH-CH-Check $DOM_SCRIPT
if(!isset($DOM_SCRIPT) or empty($DOM_SCRIPT)) {
	echo $o_errorbox.'Critital error 04: Invalid $DOM_SCRIPT, edit it manually in config.php'.$c_errorbox;
	die();
}

// CH-CH-Chek cURL
if (!extension_loaded('curl')) {
	echo $o_errorbox.'Critital error 05: You must load the cURL extension. <a href="http://php.net/manual/en/book.curl.php">http://php.net/manual/en/book.curl.php</a>'.$c_errorbox;
	die();
}

// DOCTITLE
define('ESP_TITULO',' | ');

// VARIABLES
$lup = $_FILES['fileup'];
$remota = $_POST['remota'];
$resizf = str_replace(' ', '', $_POST['resize']); // Resize via POST
$url = $_GET['url'];
$urlrez = $_GET['urlrez'];
$v = $_GET['v'];  if ($v=='.htaccess') { unset($v); $v=''; }
$page = $_GET['p'];
$view_fld = $_GET['folder'];
$resizr = $_GET['ancho']; // Resize via GET

// SHORT URL SERVICE
switch($cut_url_service) {
	case 'tinyurl':
		$tiny_api = 'http://tinyurl.com/api-create.php?url=';
		$tiny_service = 'TinyURL';
		break;
	default:
		$tiny_api = 'http://tinyurl.com/api-create.php?url=';
		$tiny_service = 'TinyURL';
		break;
}

// LANGUAGE
include('lang/'.$config['lang'].'.php');
if ($config['lang']!=='es') { $lang = $config['lang']; }

// DE DONDE VIENES?
$referer = parse_url($_SERVER['HTTP_REFERER']);
if (empty($referer['host']) && !isset($referer['host'])) {
	$referido = $DOM_SCRIPT;
} else {
	$referido = $referer['host'];
}

if (isset($remota)) {
	$ref3 = explode('?',$ref2); // img.godlike.cl?urlrez=http:
	$refok = $ref3['0'];
} else {
	$refok = $ref2;
}

// Limite de actividad
if (isset($lup) || isset($remota)) {
	if ($referido !== $DOM_SCRIPT && $lim_act) {
		if (!isset($url)) {
			echo $o_errorbox.ERROR_REF.$c_errorbox;
			die;
		}
	}
}

// EL REZ
if (isset($resizr)) {
		$resize = str_replace(' ', '', $resizr);
	} else {
		$resize = str_replace(' ', '', $resizf);
	}

// SET Modo default
$modo = 1;

// MANEJEMOS LA RUP
if (isset($url)) {
		$rup = str_replace(' ', '', $url);
	} else {
		$rup = str_replace(' ', '', $remota);
	}

// DETERMINAMOS QUE MOSTRAMOS Y HACEMOS
	//  1 = Mostrar formulario.
	//  2 = Muetsra el visualizador
	//  3 = Sube un archivo
	//  4 = muestra la pag del error de redimensionamiento
	//  5 = Muestra una pag. estatica
	//  spit = devuelve los mensajes de error.

// Modo pagina
if (isset($page)) {
	unset($modo);
	$modo = 5;
	// haga el switch
	switch ($page) {
		// Los errores
		case '400':
        	$h1 = TITLE_400;
			$explained = DESC_400;
        break;
		case '401':
        	$h1 = TITLE_401;
			$explained = DESC_401;
        break;
		case '403':
        	$h1 = TITLE_403;
			$explained = DESC_403;
        break;
		case '404':
        	$h1 = TITLE_404;
			$explained = DESC_404;
        break;
		case '500':
        	$h1 = TITLE_500;
			$explained = DESC_500;
		case '503':
        	$h1 = TITLE_503;
			$explained = DESC_503;
        break;
		// Los directorios
		case 'up':
        	$h1 = TITLE_DIR_NO;
			$explained = DESC_DIR_NO;
        break;
		case 'up/temp':
        	$h1 = TITLE_DIR_NO;
			$explained = DESC_DIR_NO;
        break;
		case 'up/working':
        	$h1 = TITLE_DIR_NO;
			$explained = DESC_DIR_NO;
        break;
		case 'images':
        	$h1 = TITLE_DIR_NO;
			$explained = DESC_DIR_NO;
        break;
		default:
			$h1 = TITLE_404;
			$explained = DESC_404;
			$page = 'generico';
		break;
	}
	$titulo = $h1.ESP_TITULO;
}

// Si hay posteo / urleo
if (isset($lup) || isset($remota) || isset($url)) {
	if ($lup[size] ==! null || !empty($remota) || !empty($url)) {
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

// SI HAY DOBLE POSTEO...
if (!empty($rup) && !empty($lup['type'])) {
	unset($modo);
	$modo = 1;
	$spit = true;
	$errormsg = DOBLE_POSTED;
	$titulo = FATAL_ERROR_TITLE.ESP_TITULO;
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

$string = $rup.$urlrez;

if (preg_match("@".$DOM_SCRIPT."/(site-img|js|images	)/@", $string)) {
	unset($modo);
	$modo = 1;
	$spit = true;
	$errormsg = NO_SELF_UPLOAD;
	$titulo = CANT_UPLOAD_TITLE.ESP_TITULO;	
}

/* HAGAMOS EL UPLOADING ---MODO 3--- */
if ($modo==3) {

	// Primer filtro (LOCAL)
	function checklocal($lup) {	
		if (preg_match("@image/(gif|pjpeg|jpeg|png|x-png|bmp)$@", $lup['type'])) { return true; }
		if ($lup['size']<$max_by) { return true; }
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

	if (checklocal($lup) || checkremota($rup)) {
			
		// LA SUBIDA LOCAL
		if ($lup['size'] ==! null) {
			
			copy($lup['tmp_name'], DIR_WORKING.$lup['name']);		
			$tmp_name = $lup[name]; // Temp name
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

?>
