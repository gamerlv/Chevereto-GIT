<?

/* -----------------------------------------

  Chevereto - Images hosting script - Script de hosting de imagenes
  Nightly Build 2.1 (30/12/2010)
  http://www.chevereto.com/

  Released under the GPL 2.0
  Copyright (C) 2008 by Rodolfo Berrios
  <inbox at rodolfoberrios dot com>
  <levi dot voorintholt at gmail dot com>

  ----------------------------------------- */
  
// Language
$config['lang'] = 'en'; // en - English | es - Espa�ol | fa - Farsi | fr - Fran�ais | nl - Dutch | cn - ZH-CN (Chinese)


// App
$config['name'] = 'Chevereto NB 2.1';  // Your image hosting name
$config['slang'] = 'Image Hosting';// Your tagline (for doctitles and logo)
$config['description'] = $config['name'].' is a free image hosting service powered by Chevereto'; // For meta description
$config['keywords'] = 'images, photos, image hosting, photo hosting, free image hosting';// For meta keywords| Google doesn't use this.

// Folders
/* Most of the time you don't want to change this, if you do. Don't forget to update this. 
		!!USE CLOSING SLASH (/) ON ALL DIRS!!!
*/
$config['dir']['im'] = 'images/';
$config['dir']['up'] = 'up/';
$config['dir']['working'] = $config['dir']['up'].'working/';
$config['dir']['temp'] = $config['dir']['up'].'temp/';
$config['dir']['thumb'] = 'thumbs/';
$config['dir']['plugins'] = 'plugins/';

// Min-Max values -> php.ini read the manuel
$config['max_filesize'] = '2'; // Max. image size (Mbytes)

//File name conventions
$config['max_name_len'] = '13'; // Max. file name lenght.
$config['randomName'] = false; //genarated random image names?

// Thumbs
$config['thumb_width'] = '150';  // Thumb width in pixels
$config['thumb_height'] = '150'; // Thumb height in pixels

// Resize
$config['min_res_size'] =  '16';  // Min. resize value (pixels)
$config['max_res_size'] = '1280'; // Max. resize value (pixels)
$config['allow_over_resize'] = false; // true: Allows over resize (aka: make it bigger then there orginal resolution) images - false: Don't allow over resize.

// Options
$config['same_domain'] = false; // true: Allows uploading just for your domain - false: Allows upload from anywhere (post from another website)
$config['cut_url_enabled'] = true; //if false no url at all will be cut (shortened)
$config['cut_url_allow'] = true; // If $cut_url = true -> true: Allows your users to cut their urls (preference) - false: Users can't choose to cut or not. It will always be cut
$config['prefix'] = "chev_"; //prefx useded in any session or cookie actions (maybe later also db)

//short url making
$config['short_url']['selected'] = 'tinyurl'; // tinyurl is the default
$config['short_url']['tinyurl'] = "http://tinyurl.com/api-create.php?url=";
//to add more, just copy past tinyurl, and change its id, then update $config['short_url']['selected'] to the new id.

$config['multi']['enabled'] = false;
$config['multi']['noflash'] = false; //it is not recommanded to turn this on, only do it if you know 95% of your clients have no flash support 
									// (eg: there all apple fan boys/girls)
$config['plugins'] = ""; //Add only the filename. like so: file called: example.plugin.php you enter example. 
						//To add more then one sepereate by comma (,).

//debug
$config['debug']['active'] = true; // false: Debug OFF - true: Debug ON.
$config['debug']['system'] = 'print'; //options are print and firephp, print adds everything to the bottom op the page, firephp logs it using the firebug logger.

///////////////////////////////////////////////////////////////////
///// DO NOT EDIT BELOW THIS - (do it if the script can't run) ////
///////////////////////////////////////////////////////////////////

/* We get this value with $_SERVER. If your server doesn't resolve this value, the script will not work.
   If the script doesn't work, you must manually set this value. (look the example) */

$DOM_SCRIPT = $_SERVER['SERVER_NAME']; // --> EXAMPLE: $DOM_SCRIPT = 'mysite.com'; FIXME: don't do this here, to engin with it!
																				#	NOTE: @fixme maybe it is smart to have it here, but blank, eg: do if null in engin

///////////////////////////////
/// DO NOT TOUCH BELOW THIS ///
///////////////////////////////

// PATH
// TODO: Move to engin
/* Uhhh.. Can't touch this!. */
$path = dirname($_SERVER['PHP_SELF']);
if (strlen($path)>1) { 
	define('PATH_SCRIPT', $path.'/');
} else {
	define('PATH_SCRIPT', $path);
}

// URL
/* Uhhh... Uhhh.. Can't touch this!. */
define('URL_SCRIPT', 'http://'.$DOM_SCRIPT.PATH_SCRIPT);

?>
