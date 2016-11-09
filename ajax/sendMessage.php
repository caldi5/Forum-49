<?php

	/*

		This file is used by the live messaging system to send messages.

	*/

	require_once("../includes/init.php");

	//function echoes the retunvalue of the function sendMessage() this is to be able to handle the error in javascript
	if(isset($_GET['to']) && isset($_GET['message']))
		echo $currentUser->sendMessage($_GET['to'], $_GET['message']);

	?>