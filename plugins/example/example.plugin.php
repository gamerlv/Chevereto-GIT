<?php
$thisPlugin['name'] = "Example";
$thisPlugin['version'] = 0.1;


function addtxttopage($txt="Hello World")
{
	echo $txt;
	return $txt; //all return's get buffered and echoed out later. exept for trues or falses. those dont make it:D
}

//it is bast to have these at the end of the file.
//This way if there are any errors, the function that couse them are never called.
registerTrigger($thisPlugin['name'], "afterRender", "addtxttopage"); //make sure our func gets called after the page loads.

?>
