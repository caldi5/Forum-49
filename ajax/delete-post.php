<?php
	require_once("../includes/init.php");

	if(!isset($_GET['id']))
		die();

	try
	{
		$post = new post($_GET['id']);
	}
	catch(Exception $e)
	{
		echo "false";
		die();
	}

	if($currentUser->id === $post->creator || $currentUser->isAdmin())
	{
		echo $post->delete();
	}
	else
	{
		echo "false";
	}
?>