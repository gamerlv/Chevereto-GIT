<?php
$modo = 3; //set the mode to 3 aka upload. For legacy reasons
require_once("./lib/chevereto.func.php");
/*
This is want i want it to look, kina
$chev->init();
$chev->checkupload()
$chev->save()
$chev->get_img_data()
*/

$filearray = $_FILES['up'];
?>
