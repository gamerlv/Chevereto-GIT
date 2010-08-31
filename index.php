<?php
/* -----------------------------------------

  Chevereto - Script de hosting de imagenes
  Nightly Build 2.0 (25/07/2010)
  http://www.chevereto.com/

  Released under the GPL 2.0
  Copyright (C) 2008 by Rodolfo Berrios
  <inbox at rodolfoberrios dot com>

  ----------------------------------------- */

/* Please link us.. http://chevereto.com/ */

//setup the basepath. aka, where this script runs from.(wih this i mean index.php)
define('BASEPATH', getcwd() );
//load up the lib
require_once(BASEPATH . '/lib/chevereto.class.php');
$chevereto = new chevereto();
$chevereto->init();

//is resize set?
if (isset($_POST['resize']) && $_POST['resize'] > $config['min_re_size']): $resize = $_POST['resize']; else: $resize = false; endif;
//is something posted? aka some one wants to upload
if ( isset($_POST['tab']) && $_POST['tab'] == "local" ){
	$chevereto->toWorking($_FILES['localUP']['tmp_name'], $_FILES['localUP']['name']);
	$imagedata = $chevereto->process($_FILES['localUP'], $_POST['tab'] ,$resize);
	$chevereto->redirect('self', '?v=' . $imagedata['uploadedFiles']); //redirect to viewer
}elseif ( isset($_POST['tab']) && $_POST['tab'] == "remote" ) {
	//remote. First get the files in $config['dir']['working'] and then proccess them.
	$filenames = $chevereto->getRemote($_POST['remota']);//post remota can be an array.
	$chevereto ->process($filenames, $_POST['tab'] ,$resize);
}

//ES
// DETERMINAMOS QUE MOSTRAMOS Y HACEMOS
	//  1 = Mostrar formulario.
	//  2 = Muetsra el visualizador
	//  3 = Sube un archivo
	//  4 = muestra la pag del error de redimensionamiento
	//  5 = Muestra una pag. estatica
	//  spit = devuelve los mensajes de error.
	
//EN 	
// Show and DETERMINE WHAT WE DO --> $modo && $chevereto->mode
	// 1 = Show form.
	// 2 = display resize fom
	// 3 = Upload a file
	// 4 = shows the error of downsizing pag
	// 5 = Display a pag. static aka 404
	// Spit = returns the error messages.

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

include('./site-files/wrapper.php');
?>
