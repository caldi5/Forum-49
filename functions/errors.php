<?php

	function displayErrors()
	{
		global $error;
		if(isset($error))
		{
			foreach ($error as $err) 
			{
				echo "<div class=\"alert alert-danger\">\r\n";
				echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>\r\n";
				echo "<strong>Error</strong> ". $err. "\r\n";
				echo "</div>" . "\r\n";
			}
		}
	}
?>