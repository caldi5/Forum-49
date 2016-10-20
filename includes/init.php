<?php 
	
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	
	session_start();
	require_once __DIR__.'/../includes/dbconn.php';
	require_once __DIR__.'/../functions/alerts.php';
	require_once __DIR__.'/../functions/get.php';
	require_once __DIR__.'/../functions/user.php';
	require_once __DIR__.'/../functions/email.php';

	$currentUser = new user();

 ?>