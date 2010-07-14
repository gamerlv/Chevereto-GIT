<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Html Template</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 
    <style type="text/css">
    
    </style>
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> 
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
 
    <script type="text/javascript">
        $(document).ready(function() {
        
        });
    </script>
</head>
<body>
    <div>
        <?php
        
        /*
        function genRandomString($length=10,$timestamp=0) {
			$characters = "0123456789abcdefghijklmnopqrstuvwxyz"; //the char we use
			$len = strlen($characters) - 1; //get the total length(ammount of characters) of $characters
			$string = ""; //init the output string

			for ($p = 0; $p < $length; $p++) {
				$string .= $characters[mt_rand(0, $len)]; //add a character to the string, until we have $length 
			}
			if ($timestamp){
				$string .= "-".date("j-m-y\:H:i:s");
				
			}
			
			return $string; //output the string.
		}*/
		
		
		if (isset($_FILES))
		{
			echo "<pre><code>";
			
			var_dump($_FILES);
			
			var_dump($_POST);
			
			echo "</code></pre>";
		}
		
        ?>
        
        <form action="" method="post" enctype="multipart/form-data">
        	<p><input type="file" name="ufile[]" /></p>
        	<p><input type="file" name="ufile[]" /></p>
        	<p><input type="file" name="ufile[]" /></p>
        	<p><input type="file" name="ufile[]" /></p>
        	<p><input type="submit" /></p>
        </form>
        
    </div>
</body>
</html>

