<?php
	require_once("../includes/init.php");

	if(isset($_POST['message']) && isset($_POST['reciever']))
	{
		$touser = getUserID($_POST['reciever']);
		
		if($touser !== false)
		{
			if($currentUser->sendMessage($touser, $_POST['message']))
				echo '<p>Message sent successfully</p>';
		}
		else
			echo '<p>User does not exist</p>';  
	}
	else
		echo '<p> Missing Data To Continue </p>';		
?>