<?php
	
	class alert
	{
		public $type;
		public $title;
		public $message;

		function __construct($type, $title, $message)
		{
			$this->type = $type;
			$this->title = $title;
			$this->message = $message;
		}
	}

	function displayAlerts()
	{
		global $alerts;

		if(isset($alerts))
		{
			foreach ($alerts as $alert) 
			{
				echo "<div class=\"alert alert-". $alert->type ."\">\r\n";
				echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>\r\n";
				echo "<strong>". $alert->title ."</strong> ". $alert->message. "\r\n";
				echo "</div>" . "\r\n";
			}
		}

		//Backwardscompability, please remove
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