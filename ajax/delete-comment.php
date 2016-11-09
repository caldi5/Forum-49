<?php
	require_once("../includes/init.php");

	if(!isset($_GET['id']))
		die();

	try
	{
		$comment = new Comment($_GET['id']);
		$post = new post($comment->post);
	}
	catch(Exception $e)
	{
		echo "false";
		die();
	}

	if($currentUser->id === $comment->creator || $currentUser->isAdmin() || $currentUser->isModerator($post->forum))
	{
		echo $comment->delete();
	}
	else
	{
		echo "false";
	}
	?>