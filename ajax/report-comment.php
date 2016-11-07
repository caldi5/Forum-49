<?php
	require_once("../includes/init.php");
	require_once("../functions/report.php");

	if(!isset($_GET['id']) || !$currentUser->isLoggedIn())
		die();

	echo reportComment($_GET['id'], $_GET['message']);

	?>