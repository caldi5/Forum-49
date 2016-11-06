<?php

	require_once("../includes/init.php");

	if(isset($_GET['to']) && isset($_GET['message']))
		$currentUser->sendMessage($_GET['to'], $_GET['message']);

	?>