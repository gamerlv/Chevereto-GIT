<?php
#doc
#	classname:	ErrorSystem
#	scope:		PUBLIC
#	Maker:		Gamerlv
#
#/doc

class ErrorSystem
{
	#	internal variables
	var $gotErrors = false;
	var $errors = array();
	var $spitError = "";
	var $title = "";
	
	#	Constructor
	function __construct ()
	{
		# code...
		
	}
	###
	
	public function gotErrors()
	{
		return $this->gotErrors;
	}
	
	public function spitError()
	{
		return $this->spitError;
	}
	
	public function fatalError($text, $title)
	{
		$this->gotErrors = true;
		$this->spitError = $text;		
		$this->title = $title;
		return true;
	}	
	
	public function addError($severity, $text, $title=ERROR_UPLOADING)
	{
		$this->errors[$severity][] = $text;
		$this->gotErrors = true;
		$this->title = $title;
		return true;
	}
	
	public function outputErrors($severity)
	{
		if (!$this->gotErrors){
			return false; // don't wanna run if there are no errors
		}

		$output = '<div id="errorBox">
						<div class="errorInner">
							<p class="errorText">An error ocured. I don\'t know what exacly but these where the error(s):</p>
							<ul>';
				foreach ($this->errors[$severity] as $key => $error)
				{
					//if ($key == 0) continue; //This is for something else, is true if there is an error
				
					$output .= "<li>"; //open up the li
				
						if (($key == 1) && is_array($error)){ //is there an array in the array? TODO: move $key == 1 to the line below
							$output .= "<p class=\"errorText\">It looks like ther are some premissions errors, I can't access/write to these folders:</p>\n";
							$output .= "<ul>";
							foreach ($error as $key => $dir)
							{
								$output .= "<li>".$dir."</li>\n";
							}
							$output .= "</ul>\n";					
						} else {
					
							$output .= $error;
							$output .= "</li>\n";
						}			
				
				}				
		$output .= '	</ul>
					</div>
				</div>';		
		
		return $output;
	}
	public function legeacyCompat($errors)
	{
		if ($errors[0] == false) return true;
		foreach ($errors as $key => $error)
		{
			if ($key == 0) continue; //This is for something else, is true if there is an error
			
			$this->addError('critical', $error);
		}
		return true;
	}

}
###
