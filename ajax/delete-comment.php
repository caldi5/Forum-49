<?php
	require_once("../includes/init.php");

	if(!isset($_GET['id']))
		die();

	try
	{
		$comment = new Comment($_GET['id']);
	}
	catch(Exception $e)
	{
		echo "false";
		die();
	}

	if($currentUser->id === $comment->creator || $currentUser->isAdmin())
	{
		echo $comment->deleteComment();
	}
	else
	{
		echo "false";
		die();
	}
	?>