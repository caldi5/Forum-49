<?php

	require_once("../includes/init.php");

	if(isset($_GET['to']) && isset($_GET['message']))
		echo $currentUser->sendMessage($_GET['to'], $_GET['message']);

	?>