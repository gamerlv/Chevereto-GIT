<?

/* -----------------------------------------

  Chevereto - Images hosting script - Script de hosting de imagenes
  Nightly Build 2.0 (11/04/2010)
  http://www.chevereto.com/

  Released under the GPL 2.0
  Copyright (C) 2008 by Rodolfo Berrios
  <inbox at rodolfoberrios dot com>
  <levi dot voorintholt at gmail dot com>

  ----------------------------------------- */
  
// Language
$config['lang'] = 'en'; // en - English | es - Español | fa - Farsi | fr - Français | nl - Dutch | cn - ZH-CN (Chinese)


// App
$config['name'] = 'Chevereto NB 2.0';  // Your image hosting name
$config['slang'] = 'Image Hosting';// Your tagline (for doctitles and logo)
$config['description'] = $config['name'].' is a free image hosting service powered by Chevereto'; // For meta description
$config['keywords'] = 'images, photos, image hosting, photo hosting, free image hosting';// For meta keywords| Google doesn't use this.

// Folders
/* Most of the time you don't want to change this, if you do. Don't forget to update this. */
$config['di']['im'] = 'images/';
$config['dir']['up'] = 'up/';
$config['dir']['working'] = $config['dir']['up'].'working/';
$config['dir']['temp'] = $config['dir']['up'].'temp/';
$config['dir']['thumb'] = 'thumbs/';

// Min-Max values -> php.ini read the manuel
$config['max_filesize'] = '2'; // Max. image size (Mbytes)

// TODO: move to engin.
$max_by = $config['max_filesize']*1048576; // (bytes)
$config['max_name_len'] = '10'; // Max. file name lenght.

// Thumbs
$config['thumb_width'] = '150'; // Thumb width in pixels
$config['thumb_height'] = '150'; // Thumb height in pixels

// Resize
$config['min_re_size'] =  '16'; // Min. resize value (pixels)
$config['max_re_size'] = '1280'; // Max. resize value (pixels)

// Options
$config['same_domain'] = false; // true: Allows uploading just for your domain - false: Allows upload from anywhere (post from another website)
$config['cut_url_service'] = 'tinyurl'; // tinyurl
$config['cut_url_allow'] = true; // If $cut_url = true -> true: Allows your users to cut their urls (preference) - false: Users can't choose to cut or not.
$config['allow_over_resize'] = false; // true: Allows over resize images - false: Don't allow over resize.

//debug
$config['debug']['mode'] = false; // false: Debug OFF - true: Debug ON.
$config['debug']['system'] = 'print'; //options are print and firephp, print addss everything to the bottom op the page, firephp logs it using the firebug logger.

///////////////////////////////////////////////////////////////////
///// DO NOT EDIT BELOW THIS - (do it if the script can't run) ////
///////////////////////////////////////////////////////////////////

/* We get this value with $_SERVER. If your server doesn't resolve this value, the script will not work.
   If the script doesn't work, you must manually set this value. (look the example) */

$DOM_SCRIPT = $_SERVER['SERVER_NAME']; // --> EXAMPLE: $DOM_SCRIPT = 'mysite.com'; FIXME: don't do this here, to engin with it!

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
echo PATH_SCRIPT;

// URL
/* Uhhh... Uhhh.. Can't touch this!. */
define('URL_SCRIPT', 'http://'.$DOM_SCRIPT.PATH_SCRIPT);

?>
