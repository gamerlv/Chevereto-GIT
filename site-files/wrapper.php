<?
global $spit, $config;
include_once(BASEPATH . '/site-files/compt.php');
trigger('beforeRender');
/* -----------------------------------------

  Chevereto - Script de hosting de imagenes
  Nightly Build 2.1 (11/04/2010)
  http://www.chevereto.com/

  Released under the GPL 2.0
  Copyright (C) 2008 by Rodolfo Berrios
  <inbox at rodolfoberrios dot com>
  
  2.* builds by Gamerlv
  

  ----------------------------------------- */

/* Please link us.. http://chevereto.com/ */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo LANG;?>" lang="<?php echo LANG;?>">
<head>

<meta name="generator" content="Chevereto <?php echo $chevereto->version(); ?>" /><!-- LET IT BIT! -->

<title><? echo $titulo.' | ' . $config['name'];?> - <?php echo $config['slang'];?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="<?php echo LANG;?>" />
<link href="<?php echo URL_SCRIPT;?>style.css" rel="stylesheet" type="text/css" />

<meta name="description" content="<?php echo $config['description'];?>"/>
<meta name="keywords" content="<?php echo $config['keywords']; ?>"/>
<meta name="robots" content="index,follow"/>

<link rel="shortcut icon" href="<?php echo PATH_SCRIPT;?>favicon.ico" type="image/x-icon" />

<!--[if lt IE 7.]>
<script defer type="text/javascript" src="<?php echo PATH_SCRIPT;?>js/pngfix.js"></script>
<![endif]-->

<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">google.load("jquery", "1.4.2");</script>
<script type="text/javascript" src="<?php echo PATH_SCRIPT;?>js/jquery.scrollTo-min.js"></script>
<script type="text/javascript" src="<?php echo PATH_SCRIPT;?>js/jquery_support.js"></script>

<script type="text/javascript">
// <![CDATA[
$(document).ready(function(){

	<?php 
	//i don't know why i formatted it like this. problably felt like it ? :D
	 if ($spit) { 					echo "error(); \n"; }
	 if ($modo==1 || $modo=='rr') { echo "upload(); \n"; } 
	 if ($modo==3) { 				echo "process(); \n"; } 
	 if ($modo==2) { 				echo "viewer(); \n"; } 
	 if ($modo==2 || $modo==3) { 	echo "social(); \n"; } 
	 if ($config['cut_url_enabled'] && $modo==1) { 	echo "pref(); \n"; } 
	?>
});
// ]]>
</script>

<?php
	if (isset($extra['header']))
		echo $extra['header'];	
?>

</head>

<body>

<?php
//it looks nice here
//these are the admin/setup errors.
/*
if ($errors[0]){
	echo "<div id=\"errorWrapper\">\n";
	echo proccessErrors();
	echo "</div>\n";
	die();
	}*/
?>

<div id="top">
	<div id="logo">
	  <a href="<?php echo URL_SCRIPT;?>"><img src="<?php echo PATH_SCRIPT;?>site-img/logo.png" alt="<?php echo $config['name'];?>" /></a>
    </div>
    <div id="tagline"><?php echo $config['slang'];?></div><div id="limite">JPG PNG BMP GIF <span>Max.<?php echo $config['max_filesize'];?>Mb</span></div>
</div>

<? if ($error->gotErrors()) { ?>
<!--The are the client/ upload errors -->
<h1 id="error"><span><?php echo $error->spitError; ?></span></h1>
<? }
		 
	 if ( ($modo==2 || $modo==3) && !isset($page)) {
	 	include(BASEPATH.'/site-files/content-modo_3-4.php');
	 } elseif (isset($page)) {
	 	include(BASEPATH.'/site-files/content-modo_static.php');
	 } else {
	 	include(BASEPATH.'/site-files/content-modo_'.$modo.'.php');
	 }
?>

</div> <!-- contenido -->

<div id="foot">
		<div class="foot-d2">
		<!-- YOU MAY NOT REMOVE THIS LINK BACK ULNESS YOU HAVE WRITTEN APPROVAL OF THE ORIGINAL CREATOR-->
			<?php echo $config['name'];?>, Powered by <a href="http://chevereto.com/" target="_blank">Chevereto Version: <?php echo $chevereto->version();?></a>
		</div>
		<?php
		if (isset($extra['footer']))
			echo $extra['footer'];	
		?>
	</div>

</body>
</html>
<?php
	trigger('afterRender');
?>
