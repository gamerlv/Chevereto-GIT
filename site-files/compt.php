<?php
//This file just provideds the backwards compatilbe for the theme. There for it is only loaded at the start of theme loading.
//Please do not relei on these varibels.
$titulo = $chevereto->getTitle();
$modo = $chevereto->mode;
define('LANG', $chevereto->lang);
$error = &new errorSystem;
$lang = $chevereto->lang;
	
	
?>
