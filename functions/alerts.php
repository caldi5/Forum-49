<?php

	function displayAlerts()
	{
		global $error;
		global $success;

		if(isset($error))
		{
			foreach ($error as $err) 
			{
				echo "<div class=\"alert alert-danger\">\r\n";
				echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>\r\n";
				echo "<strong>Error:</strong> ". $err. "\r\n";
				echo "</div>" . "\r\n";
			}
		}
		if(isset($success))
		{
			foreach ($success as $succ) 
			{
				echo "<div class=\"alert alert-success\">\r\n";
				echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>\r\n";
				echo "<strong>Success:</strong> ". $succ. "\r\n";
				echo "</div>" . "\r\n";
			}
		}
	}
?>