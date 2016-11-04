<?php
/*
	
	Examples:

	$alerts[] = new alert("danger", "Error:", "You are banned");
	$alerts[] = new alert("success", "Success:", "Successfully Updated User");

*/
	
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
				echo '<div class="alert alert-'. $alert->type .'">';
				echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
				echo '<strong>'. $alert->title .'</strong>'. $alert->message;
				echo '</div>';
			}
		}

		//Backwardscompability, please remove
		global $error;
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
	}
?>