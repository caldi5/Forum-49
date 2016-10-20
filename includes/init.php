<?php 
	session_start();
	require_once __DIR__.'/../includes/dbconn.php';
	require_once __DIR__.'/../functions/alerts.php';
	require_once __DIR__.'/../functions/get.php';
	require_once __DIR__.'/../functions/user.php';

	$user = new user();

 ?>