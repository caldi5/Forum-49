<?php 
	
	session_start();
	require_once __DIR__.'/../includes/dbconn.php';
	require_once __DIR__.'/../functions/alerts.php';
	require_once __DIR__.'/../functions/user.php';
	require_once __DIR__.'/../functions/email.php';
	require_once __DIR__.'/../functions/forum.php';

	$currentUser = new currentUser();

 ?>