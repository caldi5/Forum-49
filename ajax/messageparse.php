<?php
	require_once("../includes/init.php");

	if(isset($_POST['message']) && isset($_POST['reciever']))
	{
		$touser = user::getUserID($_POST['reciever']);
		
		if($touser !== false)
		{
			$currentUser->sendMessage($touser, $_POST['message']);
		}
		else
			echo '<p>User does not exist</p>';  
	}
	else
		echo '<p> Missing Data To Continue </p>';		
?>