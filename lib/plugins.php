<?php
/*
PLUGIN SYS FOR CHEVEROTO IMAGE UPLOADER
written by: Gamerlv
Realeased under gpl licens.
<insert short licens from index.php>
TODO: Move header from index.php here.
*/

/*
This one mutherfucker big array.
$plugins = array(
		'active' => $settings['plugins'],
		'inActive' => array(),
		'triggers' => array(
			beforeRender => array(
				'$pluginName' => 'functionName'
			),
			'afterRender' => array(),
			'beforeSave' => array(),
			'afterSave' => array(),
			'beforeResize' => array(),
			'afterResize' => array(),

		),
	);
*/

function setupPlugins()
{
	global $config, $plugins;
	
	/*
	we in hell did i gave them a chose of these 2?
	maybe just use , ?
	then i can write explode($config['plugins'] ,',') in the array.... w/e :D  $plugins['path']
	*/
	if ($config['plugins'] == "") {echo "<!--debug: No plugins to load, aborting -->"; return false;}
	
	if (strpos($config['plugins'], ",")){
		$pluginArray = explode($config['plugins'] ,',');
	} elseif (strpos($config['plugins'], '|')){
		$pluginArray = explode($config['plugins'] ,'|');
	}
	
	$plugins = array(
		'active' => $pluginArray,
		'inActive' => array(),
		'path' => $settings['dir']['plugins'],
		'ext' => '.plugin.php',
		'settSep' => '|||', //settings sepereator.
		'triggers' => array(
			'beforeRender' => array(),
			'afterRender' => array(),
			'beforeSave' => array(),
			'afterSave' => array(),
			'beforeResize' => array(),
			'afterResize' => array(), 
		),
	);
	$foundPlugins = getFiles(BASEPATH."/".$plugins['path'], $plugins['ext']);
	$pluginsNeedingLoad = array();
	foreach ($foundPlugins as $key => $file)
	{
		$lower = strtolower($file); // is this smart?
		$fileNoExt = substr($lower, -strlen($plugins['ext'])); // i dont need the ext. just give me the base filename. (that's with out plugin.php)
		if (in_array($plugins['active'], $fileNoExt))
		{
			$pluginsNeedingLoad += array( //wtf is += acctualy? idk, but still use it :D
					$fileNoExt => $file,
				);			
		}
		
	}
	
	//find all plugin files in the plugins folder and put them in a array called $foundPlugins
	//Then make sure there also in the $plugins['active'] array. 
	//Lastly give it to loadPlugins() and load it all up.
	
	loadPlugins($pluginsNeedingLoad);
return true;
}

function getFiles($folder, $ext=null)
{
	if ($foldername = opendir($folder)) {
	  while (false !== ($filename = readdir($foldername))) {
		if ($filename != "." && $filename != ".." && ($ext != null && !substr($filename, -strlen($ext)) == $ext)) {
		  $files[] = $filename;
		}
	  }
	  closedir($foldername);
	}
return $files;
}

function loadPlugins($plugins)
{
	if (!defined(BASEPATH)){die('no base path');return false;} //make sure we have a base path. or we cant include
	if (!is_array($plugins)) {return false;	} // not an array? crap, cant do shit then.
	/* This is how i expect the array.
		$plugins = array(
			(folder)name => file(name),		
		);	
	*/	
	foreach ($plugins as $name => $file)
	{
		include(BASEPATH . "/" . $plugins['path'] . $name . "/" . $file);
	}	
}

//ofc we need to be able to run all triggers
//TODO: add all trigers to chevereto.func.php .
function trigger($action, $extraparams=null)
{
	global $plugins; //add a outside var for var store	
	$html = ""; // I dont know if this will work. but buffer all echo's and echo it all at once
	foreach ($plugins['triggers'][$action] as $pluginName => $function)
	{
		echo "Running " . $function . " From plugin " . $pluginName; //debug stuff
		//TODO: give the plugin some img info. That way they can edit the images wich are uploaded.
		$pout = "\n" . $function($content,$extraparams);
		if (!$pout == true && !$pout === false)
			$html .= $pout;
	}
echo $html;
return true;
}

//basic func to just add a plugin to the trigger list.
function registerTrigger($pluginName ,$trigger, $function)
{
	global $plugins;
	$plugins['triggers'][$trigger] += array(
			$pluginName => $function,
		);
return true;	
}
/* so not case-sensitive after all
function registertrigger($pluginName ,$trigger, $function)
{
	//wrapper. I KNOW YOU CANT SPELL
	registerTrigger($pluginName ,$trigger, $function);
	return true;
}*/

//may be handy. db settings anyone?
function loadSettings($pluginName,$var="all")
{
	global $plugins;
	$settingsFile = $plugins['path'].$pluginName . "/" . "settings.txt";
	if (!file_exists($settingsFile)) return false;
	$data = file_get_contents($settingsFile);
	$data = explode($plugins['settSep'], $data);	
	
	return $data;
}

function saveSettings($pluginName, $data, $overwrite = false)
{
	global $plugins;
	$settingsFile = $plugins['path'].$pluginName . "/" . "settings.txt";
	$write = $data; // just so I know it is ALWAYS set.
	if (file_exists($settingsFile) && !$overwrite){ $oldData = loadSettings($pluginName); $exists=true;}
	
	if (!is_array($data)){
		$write = array();
		if (isset($oldData))		
			$write = $oldData;
		$write[] = $data;
	}
	$write = implode($plugins['settSep'], $write);// i always want to write a imploded array. for small file size and easy rereading.
	
	//lets try not opening it till the last point. so not to lagg the i/o.
	$handle = fopen($settingsFile, 'w+');// kills the file. make sure we have the data or it will be lost.
	fwrite($handle,$write);
	fclose($handle);
	
}
?>
