<?php
#doc
#	classname:	chevereto
#	scope:		PUBLIC
#	builder:	Gamerlv And rodolfo berrios
#
#/doc

class chevereto
{
	#	internal variables
	var $title = "";
	var $lang = "eng";
	var $image = array(); //the image object. will contain all image dat. e.g: filename, tmp name, size.
	
	#	Constructor
	function __construct ()
	{
		
	}
	###
	
	public function version()
	{
		return "2.0 Nightly"
	}
	
	function getTitle()
	{
		if ($this->title == "") $this ->title ="Not set, This should never happen"; //debug, for while building
		
		return $this->title;
	}
	
	function checkups()
	{
		
		
	}

}
###

?>
